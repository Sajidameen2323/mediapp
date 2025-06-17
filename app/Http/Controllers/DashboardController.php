<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class DashboardController extends Controller
{
    use AuthorizesRequests;

    /**
     * Display the admin dashboard.
     */
    public function adminDashboard(): View
    {
        $this->authorize('admin-access');
        
        $user = auth()->user();
        $totalUsers = \App\Models\User::count();
        $activeUsers = \App\Models\User::active()->count();
        
        return view('dashboard.admin', compact('user', 'totalUsers', 'activeUsers'));
    }

    /**
     * Display the doctor dashboard.
     */
    public function doctorDashboard(): View
    {
        $this->authorize('doctor-access');
        
        $user = auth()->user();
        
        return view('dashboard.doctor', compact('user'));
    }

    /**
     * Display the patient dashboard.
     */
    public function patientDashboard(): View
    {
        $this->authorize('patient-access');
        
        $user = auth()->user();
        
        // Get patient statistics
        $stats = [
            'totalAppointments' => $user->appointments()->count(),
            'upcomingAppointments' => $user->appointments()->where('appointment_date', '>', now())->count(),
            'totalPrescriptions' => $user->prescriptions()->count(),
            'activePrescriptions' => $user->prescriptions()->where('status', 'active')->count(),
            'totalLabTests' => $user->labTestRequests()->count(),
            'pendingLabTests' => $user->labTestRequests()->where('status', 'pending')->count(),
            'completedLabTests' => $user->labTestRequests()->where('status', 'completed')->count(),
            'totalMedicalReports' => $user->medicalReports()->count(),
            'recentMedicalReports' => $user->medicalReports()->latest()->take(3)->count(),
        ];
        
        return view('dashboard.patient', compact('user', 'stats'));
    }

    /**
     * Display the laboratory staff dashboard.
     */
    public function labDashboard(): View
    {
        $this->authorize('lab-access');
        
        $user = auth()->user();
        
        return view('dashboard.lab', compact('user'));
    }

    /**
     * Display the pharmacy dashboard.
     */
    public function pharmacyDashboard(): View
    {
        $this->authorize('pharmacy-access');
        
        $user = auth()->user();
        
        return view('dashboard.pharmacy', compact('user'));
    }
}
