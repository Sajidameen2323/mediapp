<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Http\Requests\Doctor\AppointmentCalendarRequest;
use App\Services\Doctor\AppointmentCalendarService;
use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\AppointmentConfig;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Carbon\Carbon;

class AppointmentController extends Controller
{
    use AuthorizesRequests;

    protected $calendarService;

    public function __construct(AppointmentCalendarService $calendarService)
    {
        $this->calendarService = $calendarService;
    }

    /**
     * Display the doctor's appointments.
     */
    public function index(Request $request)
    {
        $doctor = Doctor::where('user_id', auth()->id())->first();

        if (!$doctor) {
            abort(403, 'Doctor profile not found.');
        }

        $status = $request->get('status', 'all');
        $date = $request->get('date', 'today');

        $query = $doctor->appointments()->with(['patient', 'service']);

        // Filter by status
        if ($status !== 'all') {
            $query->byStatus($status);
        }

        // Filter by date
        switch ($date) {
            case 'today':
                $query->today();
                break;
            case 'upcoming':
                $query->upcoming();
                break;
            case 'week':
                $startOfWeek = now()->startOfWeek();
                $endOfWeek = now()->endOfWeek();
                $query->byDateRange($startOfWeek->toDateString(), $endOfWeek->toDateString());
                break;
            case 'month':
                $startOfMonth = now()->startOfMonth();
                $endOfMonth = now()->endOfMonth();
                $query->byDateRange($startOfMonth->toDateString(), $endOfMonth->toDateString());
                break;
        }

        $appointments = $query->orderBy('appointment_date')
            ->orderBy('start_time')
            ->paginate(10);

        // Statistics
        $stats = [
            'total' => $doctor->appointments()->count(),
            'today' => $doctor->appointments()->today()->count(),
            'upcoming' => $doctor->appointments()->upcoming()->count(),
            'pending' => $doctor->appointments()->byStatus('pending')->count(),
            'confirmed' => $doctor->appointments()->byStatus('confirmed')->count(),
        ];

        // Get appointment configuration
        return view('doctor.appointments.index', compact(
            'appointments',
            'stats'
        ) + [
            'status' => $status ?? 'all',
            'date' => $date ?? 'today',
            'search' => $search ?? ''
        ]);
    }

    /**
     * Get calendar data for AJAX requests.
     * This method handles calendar-specific appointment data fetching.
     * 
     * @param AppointmentCalendarRequest $request
     * @param Doctor|null $doctor
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCalendarData(AppointmentCalendarRequest $request, Doctor $doctor = null)
    {
        if (!$doctor) {
            $doctor = Doctor::where('user_id', auth()->id())->first();

            if (!$doctor) {
                return response()->json([
                    'error' => 'Doctor profile not found.'
                ], 403);
            }
        }

        // Get validated filters
        $filters = $request->validated();

        // Get appointments and statistics for calendar view
        $appointments = $this->calendarService->getCalendarAppointments($doctor, $filters);
        $appointmentData = $this->calendarService->formatAppointmentsForCalendar($appointments);
        $stats = $this->calendarService->getAppointmentStats($doctor);

        return response()->json([
            'appointments' => $appointmentData,
            'stats' => $stats
        ]);
    }

    /**
     * Show the specified appointment.
     */
    public function show(Appointment $appointment)
    {
        $doctor = Doctor::where('user_id', auth()->id())->first();

        if ($appointment->doctor_id !== $doctor->id) {
            abort(403, 'Unauthorized access to appointment.');
        }

        $appointment->load(['patient', 'service']);

        // Check if doctor has permission to view patient's health profile
        $hasHealthProfileAccess = auth()->user()->hasHealthProfileAccessFrom($appointment->patient_id);
        
        // Load health profile if permission exists
        $healthProfile = null;
        if ($hasHealthProfileAccess) {
            $healthProfile = $appointment->patient->healthProfile;
        }

        return view('doctor.appointments.show', compact('appointment', 'hasHealthProfileAccess', 'healthProfile'));
    }

    /**
     * Confirm an appointment.
     */
    public function confirm(Appointment $appointment)
    {
        try {
            $doctor = Doctor::where('user_id', auth()->id())->first();

            if (!$doctor) {
                return redirect()->back()->with('error', 'Doctor profile not found.');
            }

            if ($appointment->doctor_id !== $doctor->id) {
                abort(403, 'Unauthorized access to appointment.');
            }

            if ($appointment->status !== 'pending') {
                return redirect()->back()->with('error', 'Only pending appointments can be confirmed.');
            }

            $appointment->update([
                'status' => 'confirmed',
                'confirmed_at' => now()
            ]);

            return back()->with('success', 'Appointment confirmed successfully.');
        } catch (\Exception $e) {
            \Log::error('Error confirming appointment: ' . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred while confirming the appointment. Please try again.');
        }
    }

    /**
     * Cancel an appointment.
     */
    public function cancel(Request $request, Appointment $appointment)
    {
        try {
            $doctor = Doctor::where('user_id', auth()->id())->first();

            if (!$doctor) {
                return redirect()->back()->with('error', 'Doctor profile not found.');
            }

            if ($appointment->doctor_id !== $doctor->id) {
                abort(403, 'Unauthorized access to appointment.');
            }

            if (in_array($appointment->status, ['cancelled', 'completed'])) {
                return redirect()->back()->with('error', 'This appointment cannot be cancelled.');
            }

            $request->validate([
                'cancellation_reason' => 'required|string|min:10|max:500'
            ], [
                'cancellation_reason.required' => 'Please provide a reason for cancelling this appointment.',
                'cancellation_reason.min' => 'Cancellation reason must be at least 10 characters long.',
                'cancellation_reason.max' => 'Cancellation reason cannot exceed 500 characters.'
            ]);

            $appointment->update([
                'status' => 'cancelled',
                'cancelled_at' => now(),
                'cancelled_by' => 'doctor',
                'cancellation_reason' => $request->cancellation_reason
            ]);

            return back()->with('success', 'Appointment cancelled successfully.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            \Log::error('Error cancelling appointment: ' . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred while cancelling the appointment. Please try again.');
        }
    }

    /**
     * Mark appointment as completed.
     */
    public function complete(Request $request, Appointment $appointment)
    {
        try {
            $doctor = Doctor::where('user_id', auth()->id())->first();

            if (!$doctor) {
                return redirect()->back()->with('error', 'Doctor profile not found.');
            }

            if ($appointment->doctor_id !== $doctor->id) {
                abort(403, 'Unauthorized access to appointment.');
            }

            if ($appointment->status !== 'confirmed') {
                return redirect()->back()->with('error', 'Only confirmed appointments can be marked as completed.');
            }

            $request->validate([
                'completion_notes' => 'nullable|string|max:1000'
            ]);

            $appointment->update([
                'status' => 'completed',
                'completed_at' => now(),
                'completion_notes' => $request->completion_notes
            ]);

            return back()->with('success', 'Appointment marked as completed successfully.');
        } catch (\Exception $e) {
            \Log::error('Error completing appointment: ' . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred while completing the appointment. Please try again.');
        }
    }

    /**
     * Mark appointment as no-show.
     */
    public function noShow(Appointment $appointment)
    {
        try {
            $doctor = Doctor::where('user_id', auth()->id())->first();

            if (!$doctor) {
                return redirect()->back()->with('error', 'Doctor profile not found.');
            }

            if ($appointment->doctor_id !== $doctor->id) {
                abort(403, 'Unauthorized access to appointment.');
            }

            if ($appointment->status !== 'confirmed') {
                return redirect()->back()->with('error', 'Only confirmed appointments can be marked as no-show.');
            }

            $appointment->update([
                'status' => 'no_show',
                'no_show_at' => now()
            ]);

            return back()->with('success', 'Appointment marked as no-show successfully.');
        } catch (\Exception $e) {
            \Log::error('Error marking appointment as no-show: ' . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred while updating the appointment. Please try again.');
        }
    }

    /**
     * Get doctor's schedule for calendar view.
     */
    public function calendar(Request $request)
    {
        $doctor = Doctor::where('user_id', auth()->id())->first();

        if (!$doctor) {
            abort(403, 'Doctor profile not found.');
        }

        return view('doctor.appointments.calendar');
    }

    /**
     * Get appointments for a specific date (AJAX).
     */
    public function getAppointmentsByDate(Request $request)
    {
        $doctor = Doctor::where('user_id', auth()->id())->first();
        $date = $request->get('date');

        $appointments = $doctor->appointments()
            ->with(['patient', 'service'])
            ->where('appointment_date', $date)
            ->where('status', '!=', 'cancelled')
            ->orderBy('start_time')
            ->get();

        return response()->json($appointments);
    }

    /**
     * Get calendar appointments data for AJAX calendar requests.
     * This method handles the calendar-specific data fetching that was previously in index.
     * 
     * @param AppointmentCalendarRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCalendarAppointments(AppointmentCalendarRequest $request)
    {
        return $this->getCalendarData($request);
    }
}
