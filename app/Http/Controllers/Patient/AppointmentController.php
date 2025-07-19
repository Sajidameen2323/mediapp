<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\DoctorRating;
use App\Models\Service;
use App\Models\AppointmentConfig;
use App\Models\Holiday;
use App\Models\DoctorHoliday;
use App\Models\DoctorSchedule;
use App\Services\AppointmentSlotService;
use App\Services\DoctorSearchService;
use App\Http\Requests\Patient\BookAppointmentRequest;
use App\Http\Requests\Patient\AppointmentRequest;
use App\Http\Requests\Patient\AppointmentShowRequest;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Carbon\Carbon;

class AppointmentController extends Controller
{
    use AuthorizesRequests;

    protected $slotService;
    protected $doctorSearchService;

    public function __construct(AppointmentSlotService $slotService, DoctorSearchService $doctorSearchService)
    {
        $this->slotService = $slotService;
        $this->doctorSearchService = $doctorSearchService;
    }

    /**
     * Display patient's appointments.
     */
    public function index(AppointmentRequest $request)
    {
        $this->authorize('patient-access');

        $user = auth()->user();

        // Get validated and sanitized filters
        $filters = $request->getValidatedFilters();
        
        $status = $filters['status'];
        $date = $filters['date'];
        $fromDate = $filters['from_date'];
        $toDate = $filters['to_date'];
        $perPage = $filters['per_page'];

        $query = Appointment::where('patient_id', $user->id)->with(['doctor', 'service']);

        // Filter by status
        if ($status !== 'all') {
            $query->byStatus($status);
        }

        // Filter by date
        switch ($date) {
            case 'upcoming':
                $query->upcoming();
                // If from_date is provided, use it as the starting point
                if ($fromDate) {
                    $query->where('appointment_date', '>=', $fromDate);
                }
                break;
            case 'past':
                $query->where('appointment_date', '<', now()->toDateString());
                break;
            case 'today':
                $query->today();
                break;
            case 'week':
                $startOfWeek = now()->startOfWeek();
                $endOfWeek = now()->endOfWeek();
                $query->byDateRange($startOfWeek->toDateString(), $endOfWeek->toDateString());
                break;
            case 'custom':
                if ($fromDate && $toDate) {
                    $query->byDateRange($fromDate, $toDate);
                } elseif ($fromDate) {
                    $query->where('appointment_date', '>=', $fromDate);
                } elseif ($toDate) {
                    $query->where('appointment_date', '<=', $toDate);
                }
                break;
            case 'all':
                // No date filtering
                break;
        }

        $appointments = $query->orderBy('appointment_date', 'desc')
            ->orderBy('start_time', 'desc')
            ->paginate($perPage);

        // Statistics
        $stats = [
            'total' => Appointment::where('patient_id', $user->id)->count(),
            'upcoming' => Appointment::where('patient_id', $user->id)->upcoming()->count(),
            'completed' => Appointment::where('patient_id', $user->id)->byStatus('completed')->count(),
            'cancelled' => Appointment::where('patient_id', $user->id)->byStatus('cancelled')->count(),
        ];

        // Get appointment configuration
        $config = AppointmentConfig::getActive();

        return view('patient.appointments.index', compact(
            'appointments', 
            'stats', 
            'status', 
            'date', 
            'fromDate',
            'toDate',
            'config',
            'filters'
        ));
    }

    /**
     * Show appointment booking form.
     */
    public function create(Request $request)
    {
        $this->authorize('patient-access');

        $config = AppointmentConfig::first();
        if (!$config) {
            return redirect()->back()->with('error', 'Appointment booking is currently unavailable.');
        }

        $doctors = Doctor::with(['user', 'services'])
            ->where('is_available', true)
            ->get();

        $services = Service::where('is_active', true)->get();

        $preselectedDoctor = null;
        $preselectedService = null;

        if ($request->has('doctor_id')) {
            $preselectedDoctor = Doctor::find($request->get('doctor_id'));
        }

        if ($request->has('service_id')) {
            $preselectedService = Service::find($request->get('service_id'));
        }
        return view('patient.appointments.create', compact(
            'doctors',
            'services',
            'config',
            'preselectedDoctor',
            'preselectedService'
        ));
    }

    /**
     * Store a new appointment booking.
     */
    public function store(BookAppointmentRequest $request)
    {
        $this->authorize('patient-access');

        $user = auth()->user();
        /** @var Doctor $doctor */
        $doctor = Doctor::findOrFail($request->doctor_id); // Single doctor instance
        /** @var Service $service */
        $service = Service::findOrFail($request->service_id);

        $appointmentDateTime = Carbon::parse($request->appointment_date . ' ' . $request->start_time);

        // Verify slot is still available
        if (!$this->slotService->isSlotAvailable($doctor, $appointmentDateTime->toDateTimeString(), $service->id)) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'The selected time slot is no longer available. Please choose another slot.');
        }

        // Calculate end time based on service duration or default slot duration
        $config = AppointmentConfig::getActive();
        $duration = $service->duration_minutes ?? $config->default_slot_duration ?? 15;
        $endTime = $appointmentDateTime->copy()->addMinutes($duration);

        // Calculate tax values from config
        $taxRate = ($config->tax_enabled ?? false) ? ($config->tax_rate ?? 0) : 0;
        $taxAmount = 0;
        if ($taxRate > 0) {
            $taxAmount = round(($service->price * $taxRate), 2);
        }

        $initial_status = 'pending';
        if($config->require_admin_approval){
            $initial_status = 'pending';
        } elseif ($config->auto_approve_appointments) {
            $initial_status = 'confirmed';
        }
        // Generate appointment number
        $appointmentNumber = 'APT-' . now()->format('Ymd') . '-' . str_pad(
            Appointment::whereDate('created_at', now()->toDateString())->count() + 1,
            4,
            '0',
            STR_PAD_LEFT
        );

        // Create appointment
        $appointment = Appointment::create([
            'appointment_number' => $appointmentNumber,
            'doctor_id' => $doctor->id,
            'patient_id' => $user->id,
            'service_id' => $service->id,
            'appointment_date' => $request->appointment_date,
            'start_time' => $request->start_time,
            'end_time' => $endTime->format('H:i:s'),
            'duration_minutes' => $duration,
            'reason' => $request->reason,
            'symptoms' => $request->symptoms,
            'notes' => $request->notes,
            'priority' => $request->priority,
            'appointment_type' => $request->appointment_type,
            'status' => $initial_status,
            'payment_status' => 'pending',
            'total_amount' => $service->price + $taxAmount,
            'paid_amount' => 0,
            'tax_amount' => $taxAmount,
            'tax_percentage' => $taxRate * 100, // Store as percentage
            'booked_at' => now(),
            'booking_source' => 'online',
            'special_instructions' => $request->special_instructions,
            // cancellation and completion fields are nullable and not set at creation
        ]);

        // Send notification to doctor (implement notification system as needed)

        return redirect()->route('patient.appointments.show', $appointment)
            ->with('success', 'Appointment booked successfully! Your appointment number is ' . $appointmentNumber);
    }

    /**
     * Display appointment details.
     */
    public function show(Appointment $appointment)
    {
        $this->authorize('patient-access');

        $user = auth()->user();

        if ($appointment->patient_id !== $user->id) {
            abort(403, 'Unauthorized access to appointment.');
        }

        // Load relationships without the profile array method
        $appointment->load([
            'doctor.user', 
            'service', 
            'patient',
            'doctor.user.doctor', // For doctor profile data
            'doctor.user.laboratory', // For laboratory profile data  
            'doctor.user.pharmacy', // For pharmacy profile data
            'doctor.user.healthProfile' // For patient profile data
        ]);
        
        // Get appointment configuration for policy display
        $config = AppointmentConfig::getActive();
        
        // Calculate time remaining for actions
        $canCancel = $appointment->canBeCancelled();
        $canReschedule = $appointment->canBeRescheduled();
        
        return view('patient.appointments.show', compact(
            'appointment', 
            'config', 
            'canCancel', 
            'canReschedule'
        ));
    }

    /**
     * Cancel an appointment.
     */
    public function cancel(AppointmentRequest $request, Appointment $appointment)
    {
        $this->authorize('patient-access');

        $user = auth()->user();

        if ($appointment->patient_id !== $user->id) {
            abort(403, 'Unauthorized access to appointment.');
        }

        if (in_array($appointment->status, ['cancelled', 'completed'])) {
            return redirect()->back()->with('error', 'This appointment cannot be cancelled.');
        }

        // Check cancellation policy using model method
        if (!$appointment->canBeCancelled()) {
            $config = AppointmentConfig::getActive();
            return redirect()->back()->with(
                'error',
                'Appointments can only be cancelled at least ' . $config->cancellation_hours_limit . ' hours in advance.'
            );
        }

        $validated = $request->validated();

        $appointment->update([
            'status' => 'cancelled',
            'cancelled_at' => now(),
            'cancelled_by' => 'patient',
            'cancellation_reason' => $validated['cancellation_reason']
        ]);

        return redirect()->route('patient.appointments.index')
            ->with('success', 'Appointment cancelled successfully.');
    }

    /**
     * Show reschedule form for an appointment.
     */
    public function reschedule(Appointment $appointment)
    {
        $this->authorize('patient-access');

        $user = auth()->user();

        if ($appointment->patient_id !== $user->id) {
            abort(403, 'Unauthorized access to appointment.');
        }

        if (in_array($appointment->status, ['cancelled', 'completed'])) {
            return redirect()->back()->with('error', 'This appointment cannot be rescheduled.');
        }

        // Check rescheduling policy using model method
        if (!$appointment->canBeRescheduled()) {
            $config = AppointmentConfig::getActive();
            return redirect()->back()->with(
                'error',
                'Appointments can only be rescheduled at least ' . $config->reschedule_hours_limit . ' hours in advance.'
            );
        }

        $config = AppointmentConfig::getActive();
        if (!$config->allow_rescheduling) {
            return redirect()->back()->with('error', 'Appointment rescheduling is not allowed.');
        }

        $appointment->load(['doctor', 'service']);

        return view('patient.appointments.reschedule', compact('appointment', 'config'));
    }

    /**
     * Update appointment with new date/time (reschedule).
     */
    public function updateReschedule(Request $request, Appointment $appointment)
    {
        $this->authorize('patient-access');

        $user = auth()->user();

        if ($appointment->patient_id !== $user->id) {
            abort(403, 'Unauthorized access to appointment.');
        }

        if (in_array($appointment->status, ['cancelled', 'completed'])) {
            return redirect()->back()->with('error', 'This appointment cannot be rescheduled.');
        }

        // Check rescheduling policy using model method
        if (!$appointment->canBeRescheduled()) {
            $config = AppointmentConfig::getActive();
            return redirect()->back()->with(
                'error',
                'Appointments can only be rescheduled at least ' . $config->reschedule_hours_limit . ' hours in advance.'
            );
        }

        $request->validate([
            'new_date' => 'required|date|after:today',
            'new_time' => 'required|date_format:H:i',
            'reschedule_reason' => 'nullable|string|max:500'
        ]);

        $newDateTime = Carbon::parse($request->new_date . ' ' . $request->new_time);

        // Verify new slot is available
        if (!$this->slotService->isSlotAvailable($appointment->doctor, $newDateTime->toDateTimeString(), $appointment->service_id)) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'The selected time slot is no longer available. Please choose another slot.');
        }

        // Store old appointment details for reference
        $oldDate = $appointment->appointment_date;
        $oldTime = $appointment->start_time;

        // Calculate end time based on service duration or default slot duration
        $config = AppointmentConfig::getActive();
        $duration = $appointment->service->duration_minutes ?? $config->default_slot_duration ?? 15;
        $endTime = $newDateTime->copy()->addMinutes($duration);

        // Update appointment
        $appointment->update([
            'appointment_date' => $request->new_date,
            'start_time' => $request->new_time, // Store as time string
            'end_time' => $endTime->format('H:i:s'),
            'rescheduled_at' => now(),
            'rescheduled_by' => 'patient',
            'reschedule_reason' => $request->reschedule_reason,
            'original_date' => $oldDate, // Store original date for reference
            'original_time' => $oldTime, // Store original time for reference
            'status' => 'pending'
        ]);

        return redirect()->route('patient.appointments.show', $appointment)
            ->with('success', 'Appointment rescheduled successfully!');
    }

    /**
     * Rate an appointment.
     */
    public function rate(AppointmentRequest $request, Appointment $appointment)
    {
        $this->authorize('patient-access');

        $user = auth()->user();

        if ($appointment->patient_id !== $user->id) {
            error_log('Unauthorized access attempt to appointment: ' . $appointment->id);
            abort(403, 'Unauthorized access to appointment.');
        }

        if (!$appointment->canBeRated()) {
            error_log('Appointment cannot be rated: ' . $appointment->id);
            return redirect()->back()->with('error', 'This appointment cannot be rated at this time.');
        }

        if ($appointment->isRated()) {
            error_log('Appointment already rated: ' . $appointment->id);
            return redirect()->back()->with('error', 'This appointment has already been rated.');
        }
        $validated = $request->validated();

        // Create doctor rating using the new system
        $doctorRating = DoctorRating::create([
            'doctor_id' => $appointment->doctor_id,
            'patient_id' => $user->id,
            'appointment_id' => $appointment->id,
            'rating' => $validated['rating'],
            'review' => $validated['review'] ?? null,
            'is_published' => true, // Auto-publish unless spam detection triggers
            'rated_at' => now(),
        ]);

        // Check for spam and update status if needed
        if ($doctorRating->isPotentialSpam()) {
            $doctorRating->update([
                'is_published' => false,
                'spam_score' => $doctorRating->calculateSpamScore(),
                'review_status' => 'pending'
            ]);
        }

        // Mark appointment as rated
        $appointment->markAsRated($doctorRating);

        $message = $doctorRating->is_published 
            ? 'Thank you for rating this appointment!' 
            : 'Thank you for your rating! It is under review and will be published soon.';

        return redirect()->back()->with('success', $message);
    }

    /**
     * Get available slots for a doctor on a specific date (AJAX).
     */
    public function getAvailableSlots(Request $request)
    {
        $request->validate([
            'doctor_id' => 'required|exists:doctors,id',
            'date' => 'required|date|after_or_equal:today',
            'service_id' => 'nullable|exists:services,id'
        ]);

        /** @var Doctor $doctor */
        $doctor = Doctor::findOrFail($request->doctor_id);
        $slots = $this->slotService->getAvailableSlots(
            $doctor,
            $request->date,
            $request->service_id
        );

        return response()->json([
            'success' => true,
            'slots' => $slots,
            'date' => $request->date
        ]);
    }    /**
         * Search for doctors based on query and filters (AJAX).
         * Can be used both by authenticated patients and for public booking.
         * Now uses Algolia search with symptom matching and fallback support.
         */
    public function searchDoctors(Request $request)
    {
        // Only authorize if user is authenticated and accessing through patient routes
        if (auth()->check() && $request->route()->getName() === 'patient.appointments.search-doctors') {
            $this->authorize('patient-access');
        }

        try {
            $query = $request->get('q', '') ?: '';
            $filters = [
                'service_id' => $request->get('service_id'),
                'specialization' => $request->get('specialization'),
                'available_today' => $request->get('available_today')
            ];

            // Use the new DoctorSearchService for intelligent symptom-based search
            $doctors = $this->doctorSearchService->searchDoctorsV2($query, $filters);

            // Transform data for frontend
            $doctorsData = $this->doctorSearchService->transformDoctorData($doctors);

            return response()->json([
                'success' => true,
                'doctors' => $doctorsData,
                'total' => $doctors->count(),
                'search_type' => empty($query) ? 'all' : 'search',
                'query' => $query
            ]);

        } catch (\Exception $e) {
            \Log::error('Doctor search failed', [
                'error' => $e->getMessage(),
                'query' => $request->get('q'),
                'filters' => $request->only(['service_id', 'specialization', 'available_today'])
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Search temporarily unavailable. Please try again.',
                'doctors' => [],
                'total' => 0
            ], 500);
        }
    }    /**
         * Get services for a specific doctor (AJAX).
         * Can be used both by authenticated patients and for public booking.
         */
    public function getDoctorServices(Request $request, Doctor $doctor)
    {
        // Only authorize if user is authenticated and accessing through patient routes
        if (auth()->check() && str_contains($request->route()->getName(), 'patient.')) {
            $this->authorize('patient-access');
        }

        // Load doctor's services
        $services = $doctor->services()
            ->where('is_active', true)
            ->get()
            ->map(function ($service) {
                return [
                    'id' => $service->id,
                    'name' => $service->name,
                    'description' => $service->description,
                    'duration' => $service->duration ?? 30,
                    'price' => $service->price,
                    'consultation_fee' => $service->consultation_fee ?? $service->price,
                    'type' => $service->type ?? 'consultation'
                ];
            });

        return response()->json([
            'success' => true,
            'services' => $services,
            'doctor' => [
                'id' => $doctor->id,
                'name' => $doctor->user->name ?? $doctor->name,
                'specialization' => $doctor->specialization
            ]
        ]);
    }

    /**
     * Get selectable dates for the calendar based on app configuration and constraints.
     */
    public function getSelectableDates(Request $request)
    {
        $request->validate([
            'doctor_id' => 'required|exists:doctors,id',
            'service_id' => 'nullable|exists:services,id'
        ]);
        /** @var Doctor $doctor */
        $doctor = Doctor::findOrFail($request->doctor_id);

        // Ensure $doctor is a single Doctor instance, not a collection
        assert($doctor instanceof Doctor, 'Doctor must be a single model instance');

        $config = AppointmentConfig::getActive();

        if (!$config) {
            return response()->json([
                'success' => false,
                'message' => 'Appointment booking is currently unavailable.'
            ]);
        }

        // calculate min advance booking days based on min booking hours ahead
        // If min_booking_hours_ahead is not set, default to 1 day (24 hours)
        if (isset($config->min_booking_hours_ahead)) {
            $config->min_advance_booking_days = ceil($config->min_booking_hours_ahead / 24);
        } else {
            $config->min_advance_booking_days = 1; // Default to 1 day if not set
        }
        // Calculate date range
        $startDate = Carbon::now()->addDays($config->min_advance_booking_days ?? 1);
        $endDate = Carbon::now()->addDays($config->max_booking_days_ahead ?? 90);

        //log current date and config min/max days


        $selectableDates = [];
        $unavailableDates = [];

        // Check each date in the range
        for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {
            $dateString = $date->toDateString();

            // Check if date is selectable
            if ($this->isDateSelectable($doctor, $date, $config)) {
                // Check if there are available slots on this date
                error_log("Checking available slots for doctor {$doctor->id} on {$dateString}");
                $slots = $this->slotService->getAvailableSlots(
                    $doctor, // $doctor is guaranteed to be a Doctor model instance from findOrFail above
                    $dateString,
                    $request->service_id
                );
                error_log("Available slots for {$doctor->id} on {$dateString}: " . $slots->count());

                if ($slots->isNotEmpty()) {
                    $selectableDates[] = [
                        'date' => $dateString,
                        'slots_count' => $slots->count(),
                        'earliest_slot' => $slots->first()['start_time'] ?? null,
                        'latest_slot' => $slots->last()['start_time'] ?? null
                    ];
                } else {
                    $unavailableDates[] = [
                        'date' => $dateString,
                        'reason' => 'No available time slots'
                    ];
                }
            } else {
                $unavailableDates[] = [
                    'date' => $dateString,
                    'reason' => $this->getUnavailabilityReason($doctor, $date, $config)
                ];
            }
        }

        return response()->json([
            'success' => true,
            'selectable_dates' => $selectableDates,
            'unavailable_dates' => $unavailableDates,
            'date_range' => [
                'start' => $startDate->toDateString(),
                'end' => $endDate->toDateString()
            ],
            'config' => [
                'min_advance_days' => $config->min_advance_booking_days,
                'max_advance_days' => $config->max_booking_days_ahead
            ]
        ]);
    }

    /**
     * Check if a specific date is selectable for appointments.
     */
    private function isDateSelectable(Doctor $doctor, Carbon $date, AppointmentConfig $config): bool
    {
        // Check if it's a system holiday
        if (
            Holiday::where('date', $date->toDateString())
                ->where('is_active', true)
                ->exists()
        ) {
            return false;
        }

        // Check if doctor is on holiday
        if (
            DoctorHoliday::where('doctor_id', $doctor->id)
                ->where('start_date', '<=', $date->toDateString())
                ->where('end_date', '>=', $date->toDateString())
                ->where('status', 'approved')
                ->exists()
        ) {
            return false;
        }

        // Check if doctor has schedule for this day
        $dayOfWeek = $date->dayOfWeek;
        $hasSchedule = DoctorSchedule::where('doctor_id', $doctor->id)
            ->where('day_of_week', $dayOfWeek)
            ->where('is_available', true)
            ->exists();
        return $hasSchedule;
    }

    /**
     * Get the reason why a date is unavailable.
     */
    private function getUnavailabilityReason(Doctor $doctor, Carbon $date, AppointmentConfig $config): string
    {
        // Check for system holiday
        $holiday = Holiday::where('date', $date->toDateString())
            ->where('is_active', true)
            ->first();
        if ($holiday) {
            return 'Holiday: ' . $holiday->name;
        }

        // Check for doctor holiday
        $doctorHoliday = DoctorHoliday::where('doctor_id', $doctor->id)
            ->where('start_date', '<=', $date->toDateString())
            ->where('end_date', '>=', $date->toDateString())
            ->where('status', 'approved')
            ->first();
        if ($doctorHoliday) {
            return 'Doctor unavailable: ' . $doctorHoliday->reason;
        }

        // Check for no schedule
        $dayOfWeek = $date->dayOfWeek;
        $hasSchedule = DoctorSchedule::where('doctor_id', $doctor->id)
            ->where('day_of_week', $dayOfWeek)
            ->where('is_available', true)
            ->exists();

        if (!$hasSchedule) {
            return 'Doctor not available on ' . $date->format('l');
        }

        return 'No available slots';
    }
}
