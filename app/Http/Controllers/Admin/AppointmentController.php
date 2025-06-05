<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CancelAppointmentRequest;
use App\Http\Requests\Admin\RescheduleAppointmentRequest;
use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\User;
use App\Models\AppointmentConfig;
use App\Services\AppointmentSlotService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AppointmentController extends Controller
{
    protected $slotService;

    public function __construct(AppointmentSlotService $slotService)
    {
        $this->slotService = $slotService;
        $this->middleware('auth');
        $this->middleware('role:admin');
    }

    /**
     * Display a listing of appointments.
     */
    public function index(Request $request)
    {
        $this->authorize('admin-access');        $query = Appointment::with(['doctor.user', 'patient', 'cancelledBy', 'rescheduledBy'])
            ->latest('appointment_date');

        // Filter by status
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('appointment_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('appointment_date', '<=', $request->date_to);
        }        // Filter by doctor
        if ($request->filled('doctor_id')) {
            $query->where('doctor_id', $request->doctor_id);
        }

        // Filter by payment status
        if ($request->filled('payment_status') && $request->payment_status !== 'all') {
            $query->where('payment_status', $request->payment_status);
        }

        // Search by patient name or appointment ID
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('id', 'like', "%{$search}%")
                  ->orWhereHas('patient', function ($patientQuery) use ($search) {
                      $patientQuery->where('name', 'like', "%{$search}%")
                                   ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        $appointments = $query->paginate(15);

        // Get doctors for filter dropdown
        $doctors = Doctor::with('user')->get();        // Get appointment statistics
        $stats = [
            'total' => Appointment::count(),
            'pending' => Appointment::where('status', 'pending')->count(),
            'confirmed' => Appointment::where('status', 'confirmed')->count(),
            'cancelled' => Appointment::where('status', 'cancelled')->count(),
            'completed' => Appointment::where('status', 'completed')->count(),
            'today' => Appointment::whereDate('appointment_date', today())->count(),
            'unpaid' => Appointment::whereIn('payment_status', ['pending', 'partially_paid'])->count(),
        ];

        return view('admin.appointments.index', compact('appointments', 'doctors', 'stats'));
    }

    /**
     * Display the specified appointment.
     */
    public function show(Appointment $appointment)
    {
        $this->authorize('admin-access');

        $appointment->load(['doctor.user', 'patient', 'cancelledBy', 'rescheduledBy']);

        return view('admin.appointments.show', compact('appointment'));
    }    /**
     * Approve a pending appointment.
     */
    public function approve(Appointment $appointment)
    {
        $this->authorize('admin-access');

        if ($appointment->status !== 'pending') {
            return back()->with('error', 'Only pending appointments can be approved.');
        }

        // Check for conflicts using proper field names
        $conflict = Appointment::where('doctor_id', $appointment->doctor_id)
            ->where('appointment_date', $appointment->appointment_date)
            ->where('start_time', $appointment->start_time)
            ->where('status', 'confirmed')
            ->where('id', '!=', $appointment->id)
            ->exists();

        if ($conflict) {
            return back()->with('error', 'There is already a confirmed appointment at this time slot.');
        }

        $appointment->update([
            'status' => 'confirmed',
            'confirmed_at' => now(),
            'confirmed_by' => auth()->id(),
        ]);

        return back()->with('success', 'Appointment approved successfully.');
    }/**
     * Cancel an appointment.
     */
    public function cancel(CancelAppointmentRequest $request, Appointment $appointment)
    {
        $this->authorize('admin-access');

        if (!$appointment->canBeCancelled()) {
            return back()->with('error', 'This appointment cannot be cancelled.');
        }

        $appointment->update([
            'status' => 'cancelled',
            'cancelled_at' => now(),
            'cancelled_by' => auth()->id(),
            'cancellation_reason' => $request->cancellation_reason,
        ]);

        return back()->with('success', 'Appointment cancelled successfully.');
    }    /**
     * Show reschedule form.
     */
    public function reschedule(Appointment $appointment)
    {
        $this->authorize('admin-access');

        if (!$appointment->canBeRescheduled()) {
            return back()->with('error', 'This appointment cannot be rescheduled.');
        }

        $appointment->load(['doctor.user', 'patient', 'service']);
        $config = AppointmentConfig::getActive();

        if (!$config) {
            return back()->with('error', 'Appointment configuration not found.');
        }

        return view('admin.appointments.reschedule', compact('appointment', 'config'));
    }    /**
     * Update appointment schedule.
     */
    public function updateSchedule(RescheduleAppointmentRequest $request, Appointment $appointment)
    {
        $this->authorize('admin-access');

        if (!$appointment->canBeRescheduled()) {
            return back()->with('error', 'This appointment cannot be rescheduled.');
        }

        $newDateTime = Carbon::parse($request->appointment_date . ' ' . $request->appointment_time);

        // Verify the new slot is available using the slot service
        if (!$this->slotService->isSlotAvailable($appointment->doctor, $newDateTime->toDateTimeString(), $appointment->service_id)) {
            return back()
                ->withInput()
                ->with('error', 'The selected time slot is no longer available. Please choose another slot.');
        }

        // Store original schedule for reference
        $originalDate = $appointment->appointment_date;
        $originalTime = $appointment->start_time;

        // Calculate end time based on service duration
        $config = AppointmentConfig::getActive();
        $duration = $appointment->service->duration_minutes ?? $config->default_slot_duration ?? 15;
        $endTime = $newDateTime->copy()->addMinutes($duration);

        $appointment->update([
            'appointment_date' => $request->appointment_date,
            'start_time' => $request->appointment_time,
            'end_time' => $endTime->format('H:i:s'),
            'original_appointment_date' => $originalDate,
            'original_appointment_time' => $originalTime,
            'rescheduled_at' => now(),
            'rescheduled_by' => auth()->id(),
            'reschedule_reason' => $request->reschedule_reason,
            'reschedule_count' => ($appointment->reschedule_count ?? 0) + 1,
        ]);

        return redirect()->route('admin.appointments.show', $appointment)
            ->with('success', 'Appointment rescheduled successfully.');
    }

    /**
     * Bulk update appointment statuses.
     */
    public function bulkUpdate(Request $request)
    {
        $this->authorize('admin-access');

        $request->validate([
            'appointment_ids' => 'required|array',
            'appointment_ids.*' => 'exists:appointments,id',
            'bulk_action' => 'required|in:approve,cancel',
            'bulk_reason' => 'required_if:bulk_action,cancel|string|max:255',
        ]);

        $appointments = Appointment::whereIn('id', $request->appointment_ids)->get();
        $updated = 0;        foreach ($appointments as $appointment) {
            if ($request->bulk_action === 'approve' && $appointment->status === 'pending') {
                // Check for conflicts using proper field names
                $conflict = Appointment::where('doctor_id', $appointment->doctor_id)
                    ->where('appointment_date', $appointment->appointment_date)
                    ->where('start_time', $appointment->start_time)
                    ->where('status', 'confirmed')
                    ->where('id', '!=', $appointment->id)
                    ->exists();

                if (!$conflict) {
                    $appointment->update([
                        'status' => 'confirmed',
                        'confirmed_at' => now(),
                        'confirmed_by' => auth()->id(),
                    ]);
                    $updated++;
                }
            } elseif ($request->bulk_action === 'cancel' && $appointment->canBeCancelled()) {
                $appointment->update([
                    'status' => 'cancelled',
                    'cancelled_at' => now(),
                    'cancelled_by' => auth()->id(),
                    'cancellation_reason' => $request->bulk_reason,
                ]);
                $updated++;
            }
        }

        return back()->with('success', "Successfully updated {$updated} appointments.");
    }

    /**
     * Export appointments data.
     */
    public function export(Request $request)
    {
        $this->authorize('admin-access');

        $query = Appointment::with(['doctor.user', 'patient']);

        // Apply same filters as index
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('appointment_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('appointment_date', '<=', $request->date_to);
        }        if ($request->filled('doctor_id')) {
            $query->where('doctor_id', $request->doctor_id);
        }

        if ($request->filled('payment_status') && $request->payment_status !== 'all') {
            $query->where('payment_status', $request->payment_status);
        }

        $appointments = $query->get();

        $csvData = "ID,Patient Name,Patient Email,Doctor,Date,Time,Status,Payment Status,Total Amount,Paid Amount,Created At\n";        foreach ($appointments as $appointment) {
            $csvData .= sprintf(
                "%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s\n",
                $appointment->id,
                $appointment->patient->name,
                $appointment->patient->email,
                $appointment->doctor->user->name,
                $appointment->appointment_date,
                $appointment->start_time,
                ucfirst($appointment->status),
                ucfirst(str_replace('_', ' ', $appointment->payment_status ?? 'pending')),
                number_format($appointment->total_amount ?? 0, 2),
                number_format($appointment->paid_amount ?? 0, 2),
                $appointment->created_at->format('Y-m-d H:i:s')
            );
        }

        return response($csvData)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="appointments-' . now()->format('Y-m-d') . '.csv"');
    }
}
