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
        
        return view('dashboard.patient', compact('user'));
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
