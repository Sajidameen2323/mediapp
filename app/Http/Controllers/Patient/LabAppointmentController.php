<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\LabAppointment;
use App\Models\LabTestRequest;
use App\Models\Laboratory;
use App\Http\Requests\Patient\BookLabAppointmentRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Carbon\Carbon;

class LabAppointmentController extends Controller
{
    /**
     * Display a listing of patient's lab appointments.
     */
    public function index(Request $request)
    {
        Gate::authorize('patient-access');

        $patient = auth()->user();
        
        $query = $patient->labAppointments()
            ->with(['laboratory', 'labTestRequest.medicalReport.doctor'])
            ->orderBy('appointment_date', 'desc')
            ->orderBy('start_time', 'desc');

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->where('appointment_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->where('appointment_date', '<=', $request->date_to);
        }

        $labAppointments = $query->paginate(12)->appends($request->except('page'));

        return view('dashboard.patient.lab-appointments.index', compact('labAppointments'));
    }

    /**
     * Show the form for creating a new lab appointment.
     */
    public function create(Request $request)
    {
        Gate::authorize('patient-access');

        $labTestRequest = null;
        if ($request->filled('lab_test_request_id')) {
            $labTestRequest = LabTestRequest::with(['medicalReport.doctor'])
                ->where('id', $request->lab_test_request_id)
                ->where('patient_id', auth()->id())
                ->first();

            if (!$labTestRequest) {
                return redirect()->route('patient.lab-tests.index')
                    ->with('error', 'Lab test request not found.');
            }
        }

        $laboratories = Laboratory::where('is_available', true)
            ->orderBy('name')
            ->get();

        return view('dashboard.patient.lab-appointments.create', compact('labTestRequest', 'laboratories'));
    }

    /**
     * Store a newly created lab appointment.
     */
    public function store(BookLabAppointmentRequest $request)
    {
        Gate::authorize('patient-access');

        $patient = auth()->user();
        $labTestRequest = LabTestRequest::findOrFail($request->lab_test_request_id);
        $laboratory = Laboratory::findOrFail($request->laboratory_id);

        // Calculate end time (default 30 minutes)
        $startTime = Carbon::parse($request->start_time);
        $endTime = $startTime->copy()->addMinutes(30);

        try {
            $labAppointment = LabAppointment::create([
                'patient_id' => $patient->id,
                'laboratory_id' => $laboratory->id,
                'lab_test_request_id' => $labTestRequest->id,
                'appointment_date' => $request->appointment_date,
                'start_time' => $request->start_time,
                'end_time' => $endTime->format('H:i:s'),
                'duration_minutes' => 30,
                'status' => 'pending',
                'patient_notes' => $request->patient_notes,
                'special_instructions' => $request->special_instructions,
                'booked_at' => now(),
                'estimated_cost' => $laboratory->consultation_fee ?? 0,
            ]);

            // Update lab test request status to scheduled
            $labTestRequest->update([
                'status' => 'scheduled',
                'laboratory_id' => $laboratory->id,
                'scheduled_at' => Carbon::parse($request->appointment_date . ' ' . $request->start_time),
            ]);

            return redirect()->route('patient.lab-appointments.show', $labAppointment)
                ->with('success', 'Lab appointment booked successfully! The laboratory will confirm your appointment.');

        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Failed to book appointment: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified lab appointment.
     */
    public function show(LabAppointment $labAppointment)
    {
        Gate::authorize('patient-access');

        // Ensure the appointment belongs to the authenticated patient
        if ($labAppointment->patient_id !== auth()->id()) {
            abort(403);
        }

        $labAppointment->load([
            'laboratory',
            'labTestRequest.medicalReport.doctor'
        ]);

        return view('dashboard.patient.lab-appointments.show', compact('labAppointment'));
    }

    /**
     * Cancel a lab appointment.
     */
    public function cancel(Request $request, LabAppointment $labAppointment)
    {
        Gate::authorize('patient-access');

        // Ensure the appointment belongs to the authenticated patient
        if ($labAppointment->patient_id !== auth()->id()) {
            abort(403);
        }

        if (!$labAppointment->canBeCancelled()) {
            return back()->with('error', 'This appointment cannot be cancelled.');
        }

        $request->validate([
            'cancellation_reason' => 'required|string|max:500'
        ]);

        try {
            $labAppointment->update([
                'status' => 'cancelled',
                'cancelled_at' => now(),
                'cancelled_by' => 'patient',
                'cancellation_reason' => $request->cancellation_reason,
            ]);

            // Update lab test request status back to pending if no other confirmed appointments
            $hasOtherConfirmedAppointments = $labAppointment->labTestRequest
                ->labAppointments()
                ->where('id', '!=', $labAppointment->id)
                ->whereIn('status', ['confirmed', 'pending'])
                ->exists();

            if (!$hasOtherConfirmedAppointments) {
                $labAppointment->labTestRequest->update([
                    'status' => 'pending',
                    'laboratory_id' => null,
                    'scheduled_at' => null,
                ]);
            }

            return redirect()->route('patient.lab-appointments.index')
                ->with('success', 'Lab appointment cancelled successfully.');

        } catch (\Exception $e) {
            return back()->with('error', 'Failed to cancel appointment: ' . $e->getMessage());
        }
    }

    /**
     * Get available time slots for a laboratory on a specific date (AJAX).
     */
    public function getAvailableSlots(Request $request)
    {
        // Ensure user is authenticated and is a patient
        if (!auth()->check() || auth()->user()->user_type !== 'patient') {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access.'
            ], 403);
        }

        $request->validate([
            'laboratory_id' => 'required|exists:laboratories,id',
            'date' => 'required|date|after_or_equal:today'
        ]);

        $laboratory = Laboratory::findOrFail($request->laboratory_id);
        $date = Carbon::parse($request->date);

        // Check if laboratory is available on this date
        if (!$laboratory->isAvailableOnDate($date)) {
            return response()->json([
                'success' => false,
                'message' => 'Laboratory is not available on this date.'
            ]);
        }

        // Get laboratory working hours
        $openingTime = Carbon::parse($laboratory->opening_time ?? '09:00');
        $closingTime = Carbon::parse($laboratory->closing_time ?? '17:00');

        // If the date is today, make sure we don't show past times
        if ($date->isToday()) {
            $now = Carbon::now();
            if ($now->gt($openingTime)) {
                $openingTime = $now->copy()->addMinutes(30 - ($now->minute % 30)); // Round up to next 30-min slot
            }
        }

        // Get existing appointments for this date
        $existingAppointments = LabAppointment::where('laboratory_id', $laboratory->id)
            ->where('appointment_date', $date->toDateString())
            ->whereNotIn('status', ['cancelled', 'rejected'])
            ->pluck('start_time')
            ->map(function ($time) {
                return Carbon::parse($time)->format('H:i');
            })
            ->toArray();

        // Generate available slots (30-minute intervals)
        $slots = [];
        $currentTime = $openingTime->copy();

        // Ensure we have valid opening and closing times
        if ($closingTime->lte($openingTime)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid laboratory working hours.'
            ]);
        }

        while ($currentTime->lt($closingTime)) {
            $timeSlot = $currentTime->format('H:i');
            
            if (!in_array($timeSlot, $existingAppointments)) {
                $slots[] = [
                    'time' => $timeSlot,
                    'formatted' => $currentTime->format('g:i A')
                ];
            }

            $currentTime->addMinutes(30);
        }

        return response()->json([
            'success' => true,
            'slots' => $slots
        ]);
    }
}
