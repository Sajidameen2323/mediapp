<?php

namespace App\Http\Controllers\Laboratory;

use App\Http\Controllers\Controller;
use App\Models\LabAppointment;
use App\Models\Laboratory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Carbon\Carbon;

class LabAppointmentController extends Controller
{
    /**
     * Display a listing of laboratory appointments.
     */
    public function index(Request $request)
    {
        Gate::authorize('laboratory-staff-access');

        $user = auth()->user();
        $laboratory = $user->laboratory;

        if (!$laboratory) {
            return redirect()->route('dashboard')
                ->with('error', 'Laboratory profile not found.');
        }

        $query = $laboratory->labAppointments()
            ->with(['patient', 'labTestRequest.medicalReport.doctor'])
            ->orderBy('appointment_date', 'asc')
            ->orderBy('start_time', 'asc');

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

        // Default to upcoming appointments
        if (!$request->filled(['status', 'date_from', 'date_to'])) {
            $query->where('appointment_date', '>=', Carbon::today());
        }

        $labAppointments = $query->paginate(12)->appends($request->except('page'));

        return view('dashboard.laboratory.appointments.index', compact('labAppointments', 'laboratory'));
    }

    /**
     * Display the specified lab appointment.
     */
    public function show(LabAppointment $labAppointment)
    {
        Gate::authorize('laboratory-staff-access');

        $user = auth()->user();
        $laboratory = $user->laboratory;

        // Ensure the appointment belongs to this laboratory
        if ($labAppointment->laboratory_id !== $laboratory->id) {
            abort(403);
        }

        $labAppointment->load([
            'patient',
            'labTestRequest.medicalReport.doctor'
        ]);

        return view('dashboard.laboratory.appointments.show', compact('labAppointment'));
    }

    /**
     * Update lab appointment (for adding instructions).
     */
    public function update(Request $request, LabAppointment $labAppointment)
    {
        Gate::authorize('laboratory-staff-access');

        $user = auth()->user();
        $laboratory = $user->laboratory;

        // Ensure the appointment belongs to this laboratory
        if ($labAppointment->laboratory_id !== $laboratory->id) {
            abort(403);
        }

        $request->validate([
            'lab_instructions' => 'nullable|string|max:1000',
        ]);

        try {
            $labAppointment->update([
                'lab_instructions' => $request->lab_instructions,
            ]);

            return back()->with('success', 'Instructions updated successfully.');

        } catch (\Exception $e) {
            return back()->with('error', 'Failed to update instructions: ' . $e->getMessage());
        }
    }

    /**
     * Confirm a lab appointment.
     */
    public function confirm(Request $request, LabAppointment $labAppointment)
    {
        Gate::authorize('laboratory-staff-access');

        $user = auth()->user();
        $laboratory = $user->laboratory;

        // Ensure the appointment belongs to this laboratory
        if ($labAppointment->laboratory_id !== $laboratory->id) {
            abort(403);
        }

        if (!$labAppointment->canBeConfirmed()) {
            return back()->with('error', 'This appointment cannot be confirmed.');
        }

        $request->validate([
            'lab_instructions' => 'nullable|string|max:1000',
            'final_cost' => 'nullable|numeric|min:0',
            'requires_fasting' => 'boolean',
            'fasting_hours' => 'nullable|integer|min:1|max:24',
        ]);

        try {
            $labAppointment->update([
                'status' => 'confirmed',
                'confirmed_at' => now(),
                'lab_instructions' => $request->lab_instructions,
                'final_cost' => $request->final_cost ?? $labAppointment->estimated_cost,
                'requires_fasting' => $request->boolean('requires_fasting'),
                'fasting_hours' => $request->requires_fasting ? $request->fasting_hours : null,
            ]);

            return back()->with('success', 'Appointment confirmed successfully.');

        } catch (\Exception $e) {
            return back()->with('error', 'Failed to confirm appointment: ' . $e->getMessage());
        }
    }

    /**
     * Reject a lab appointment.
     */
    public function reject(Request $request, LabAppointment $labAppointment)
    {
        Gate::authorize('laboratory-staff-access');

        $user = auth()->user();
        $laboratory = $user->laboratory;

        // Ensure the appointment belongs to this laboratory
        if ($labAppointment->laboratory_id !== $laboratory->id) {
            abort(403);
        }

        if ($labAppointment->status !== 'pending') {
            return back()->with('error', 'Only pending appointments can be rejected.');
        }

        $request->validate([
            'rejection_reason' => 'required|string|max:500'
        ]);

        try {
            $labAppointment->update([
                'status' => 'rejected',
                'rejected_at' => now(),
                'rejection_reason' => $request->rejection_reason,
            ]);

            // Update lab test request status back to pending
            $labAppointment->labTestRequest->update([
                'status' => 'pending',
                'laboratory_id' => null,
                'scheduled_at' => null,
            ]);

            return back()->with('success', 'Appointment rejected.');

        } catch (\Exception $e) {
            return back()->with('error', 'Failed to reject appointment: ' . $e->getMessage());
        }
    }

    /**
     * Complete a lab appointment and upload results.
     */
    public function complete(Request $request, LabAppointment $labAppointment)
    {
        Gate::authorize('laboratory-staff-access');

        $user = auth()->user();
        $laboratory = $user->laboratory;

        // Ensure the appointment belongs to this laboratory
        if ($labAppointment->laboratory_id !== $laboratory->id) {
            abort(403);
        }

        if (!$labAppointment->canBeCompleted()) {
            return back()->with('error', 'This appointment cannot be completed.');
        }

        $request->validate([
            'results_file' => 'required|file|mimes:pdf|max:10240', // 10MB max
        ]);

        try {
            // Store the results file
            $resultsPath = $request->file('results_file')->store('lab-results', 'public');

            $labAppointment->update([
                'status' => 'completed',
                'completed_at' => now(),
                'results_file_path' => $resultsPath,
            ]);

            // Update lab test request status to completed
            $labAppointment->labTestRequest->update([
                'status' => 'completed',
                'completed_at' => now(),
                'results_file_path' => $resultsPath,
            ]);

            return back()->with('success', 'Test results uploaded successfully. Patient will be notified.');

        } catch (\Exception $e) {
            return back()->with('error', 'Failed to complete appointment: ' . $e->getMessage());
        }
    }

    /**
     * Get laboratory schedule for calendar view (AJAX).
     */
    public function schedule(Request $request)
    {
        Gate::authorize('laboratory-staff-access');

        $user = auth()->user();
        $laboratory = $user->laboratory;

        $start = $request->get('start', Carbon::now()->startOfMonth());
        $end = $request->get('end', Carbon::now()->endOfMonth());

        $appointments = $laboratory->labAppointments()
            ->with(['patient', 'labTestRequest'])
            ->whereBetween('appointment_date', [$start, $end])
            ->get()
            ->map(function ($appointment) {
                return [
                    'id' => $appointment->id,
                    'title' => $appointment->patient->name . ' - ' . $appointment->labTestRequest->test_name,
                    'start' => $appointment->appointment_date . 'T' . $appointment->start_time,
                    'end' => $appointment->appointment_date . 'T' . $appointment->end_time,
                    'color' => $this->getStatusColor($appointment->status),
                    'status' => $appointment->status,
                    'url' => route('laboratory.appointments.show', $appointment),
                ];
            });

        return response()->json($appointments);
    }

    /**
     * Get color for appointment status.
     */
    private function getStatusColor($status): string
    {
        return match($status) {
            'pending' => '#fbbf24', // yellow
            'confirmed' => '#3b82f6', // blue
            'completed' => '#10b981', // green
            'cancelled' => '#ef4444', // red
            'rejected' => '#f87171', // light red
            default => '#6b7280' // gray
        };
    }
}
