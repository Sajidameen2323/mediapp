<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\AppointmentConfig;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Carbon\Carbon;

class AppointmentController extends Controller
{
    use AuthorizesRequests;

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
        
        // Handle JSON response for calendar
        if ($request->get('format') === 'json') {
            $appointmentData = $appointments->getCollection()->map(function ($appointment) {
                return [
                    'id' => $appointment->id,
                    'appointment_date' => $appointment->appointment_date,
                    'appointment_time' => $appointment->start_time,
                    'patient_name' => $appointment->patient->name,
                    'service_name' => $appointment->service->name,
                    'status' => $appointment->status,
                ];
            });
            
            return response()->json([
                'appointments' => $appointmentData,
                'stats' => $stats
            ]);
        }
        
        return view('doctor.appointments.index', compact('appointments', 'stats', 'status', 'date'));
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
        
        return view('doctor.appointments.show', compact('appointment'));
    }

    /**
     * Confirm an appointment.
     */
    public function confirm(Appointment $appointment)
    {
        $doctor = Doctor::where('user_id', auth()->id())->first();
        
        if ($appointment->doctor_id !== $doctor->id) {
            abort(403, 'Unauthorized access to appointment.');
        }
        
        if ($appointment->status !== 'pending') {
            return redirect()->back()->with('error', 'Only pending appointments can be confirmed.');
        }
        
        $appointment->update([
            'status' => 'confirmed'
        ]);
        
        return redirect()->route('doctor.appointments.index')
                        ->with('success', 'Appointment confirmed successfully.');
    }

    /**
     * Cancel an appointment.
     */
    public function cancel(Request $request, Appointment $appointment)
    {
        $doctor = Doctor::where('user_id', auth()->id())->first();
        
        if ($appointment->doctor_id !== $doctor->id) {
            abort(403, 'Unauthorized access to appointment.');
        }
        
        if (in_array($appointment->status, ['cancelled', 'completed'])) {
            return redirect()->back()->with('error', 'This appointment cannot be cancelled.');
        }
        
        $request->validate([
            'cancellation_reason' => 'required|string|max:500'
        ]);
        
        $appointment->update([
            'status' => 'cancelled',
            'cancelled_at' => now(),
            'cancelled_by' => 'doctor',
            'cancellation_reason' => $request->cancellation_reason
        ]);
        
        return redirect()->route('doctor.appointments.index')
                        ->with('success', 'Appointment cancelled successfully.');
    }

    /**
     * Mark appointment as completed.
     */
    public function complete(Request $request, Appointment $appointment)
    {
        $doctor = Doctor::where('user_id', auth()->id())->first();
        
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
        
        return redirect()->route('doctor.appointments.index')
                        ->with('success', 'Appointment marked as completed.');
    }

    /**
     * Mark appointment as no-show.
     */
    public function noShow(Appointment $appointment)
    {
        $doctor = Doctor::where('user_id', auth()->id())->first();
        
        if ($appointment->doctor_id !== $doctor->id) {
            abort(403, 'Unauthorized access to appointment.');
        }
        
        if ($appointment->status !== 'confirmed') {
            return redirect()->back()->with('error', 'Only confirmed appointments can be marked as no-show.');
        }
        
        $appointment->update([
            'status' => 'no_show'
        ]);
        
        return redirect()->route('doctor.appointments.index')
                        ->with('success', 'Appointment marked as no-show.');
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
        
        $month = $request->get('month', now()->month);
        $year = $request->get('year', now()->year);
        
        $startDate = Carbon::create($year, $month, 1)->startOfMonth();
        $endDate = Carbon::create($year, $month, 1)->endOfMonth();
        
        $appointments = $doctor->appointments()
                              ->with(['patient', 'service'])
                              ->byDateRange($startDate->toDateString(), $endDate->toDateString())
                              ->where('status', '!=', 'cancelled')
                              ->orderBy('appointment_date')
                              ->orderBy('start_time')
                              ->get();
        
        return view('doctor.appointments.calendar', compact('appointments', 'month', 'year', 'startDate', 'endDate'));
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
}
