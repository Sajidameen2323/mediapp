<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\DoctorBreak;
use App\Models\DoctorHoliday;
use App\Models\DoctorSchedule;
use App\Models\MedicalReport;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Carbon\Carbon;

class DoctorDashboardController extends Controller
{
    use AuthorizesRequests;

    /**
     * Display the doctor dashboard with enhanced features.
     */
    public function index(): View
    {
        $this->authorize('doctor-access');
        
        $user = auth()->user();
        $doctor = $user->doctor;
        
        if (!$doctor) {
            abort(403, 'Doctor profile not found.');
        }

        // Get dashboard statistics
        $totalPatients = MedicalReport::where('doctor_id', $doctor->id)
            ->distinct('patient_id')
            ->count('patient_id');
            
        $todayReports = MedicalReport::where('doctor_id', $doctor->id)
            ->whereDate('consultation_date', today())
            ->count();
            
        $pendingReports = MedicalReport::where('doctor_id', $doctor->id)
            ->where('status', 'draft')
            ->count();
            
        $upcomingHolidays = DoctorHoliday::where('doctor_id', $doctor->id)
            ->where('start_date', '>=', today())
            ->where('status', 'approved')
            ->orderBy('start_date')
            ->limit(3)
            ->get();

        return view('dashboard.doctor', compact(
            'user', 
            'doctor', 
            'totalPatients', 
            'todayReports', 
            'pendingReports', 
            'upcomingHolidays'
        ));
    }

    /**
     * Display schedule management page.
     */
    public function schedule(): View
    {
        $this->authorize('doctor-access');
        
        $user = auth()->user();
        $doctor = $user->doctor;
        
        $schedules = DoctorSchedule::where('doctor_id', $doctor->id)
            ->orderBy('day_of_week')
            ->get();

        return view('dashboard.doctor.schedule', compact('doctor', 'schedules'));
    }

    /**
     * Display breaks management page.
     */
    public function breaks(): View
    {
        $this->authorize('doctor-access');
        
        $user = auth()->user();
        $doctor = $user->doctor;
        
        $breaks = DoctorBreak::where('doctor_id', $doctor->id)
            ->orderBy('day_of_week')
            ->orderBy('start_time')
            ->get();

        return view('dashboard.doctor.breaks', compact('doctor', 'breaks'));
    }

    /**
     * Display holidays management page.
     */
    public function holidays(): View
    {
        $this->authorize('doctor-access');
        
        $user = auth()->user();
        $doctor = $user->doctor;
        
        $holidays = DoctorHoliday::where('doctor_id', $doctor->id)
            ->orderBy('start_date', 'desc')
            ->paginate(10);

        return view('dashboard.doctor.holidays', compact('doctor', 'holidays'));
    }

    /**
     * Display medical reports page.
     */
    public function medicalReports(): View
    {
        $this->authorize('doctor-access');
        
        $user = auth()->user();
        $doctor = $user->doctor;
        
        $reports = MedicalReport::where('doctor_id', $doctor->id)
            ->with('patient')
            ->orderBy('consultation_date', 'desc')
            ->paginate(15);

        return view('dashboard.doctor.medical-reports', compact('doctor', 'reports'));
    }

    /**
     * Show create medical report form.
     */
    public function createMedicalReport(): View
    {
        $this->authorize('doctor-access');
        
        $user = auth()->user();
        $doctor = $user->doctor;
        
        // Get patients who have had appointments with this doctor
        $patients = User::whereHas('roles', function($query) {
            $query->where('name', 'patient');
        })->orderBy('name')->get();

        return view('dashboard.doctor.create-medical-report', compact('doctor', 'patients'));
    }
}
