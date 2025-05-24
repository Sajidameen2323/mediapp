<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;

// Public routes
Route::get('/', function () {
    return view('welcome');
});

// Authentication routes
Route::middleware('custom.guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Protected routes
Route::middleware(['auth'])->group(function () {
    // Test route for authorization
    Route::get('/test-auth', function () {
        $user = auth()->user();
        $results = [
            'user_info' => [
                'id' => $user->id,
                'name' => $user->name,
                'user_type' => $user->user_type,
                'is_active' => $user->is_active,
            ],
            'gates' => [
                'admin-access' => Gate::allows('admin-access'),
                'doctor-access' => Gate::allows('doctor-access'),
                'patient-access' => Gate::allows('patient-access'),
                'lab-access' => Gate::allows('lab-access'),
                'pharmacy-access' => Gate::allows('pharmacy-access'),
            ],
            'roles' => [
                'hasAdminRole' => $user->hasRole('admin'),
                'hasDoctorRole' => $user->hasRole('doctor'),
                'hasPatientRole' => $user->hasRole('patient'),
                'hasLabRole' => $user->hasRole('laboratory_staff'),
                'hasPharmacyRole' => $user->hasRole('pharmacist'),
            ]
        ];
        
        return response()->json($results, 200, [], JSON_PRETTY_PRINT);
    })->name('test.auth');
    
    // Admin routes
    Route::middleware(['role:admin'])->group(function () {
        Route::get('/admin/dashboard', [DashboardController::class, 'adminDashboard'])->name('admin.dashboard');
        
        // Doctor Management
        Route::resource('/admin/doctors', \App\Http\Controllers\Admin\DoctorController::class, [
            'as' => 'admin'
        ]);
        
        // Service Management
        Route::resource('/admin/services', \App\Http\Controllers\Admin\ServiceController::class, [
            'as' => 'admin'
        ]);
        
        // Additional service routes for doctor assignment
        Route::get('/admin/services/{service}/assign-doctors', [\App\Http\Controllers\Admin\ServiceController::class, 'assignDoctors'])
            ->name('admin.services.assign-doctors');
        Route::put('/admin/services/{service}/doctor-assignments', [\App\Http\Controllers\Admin\ServiceController::class, 'updateDoctorAssignments'])
            ->name('admin.services.update-doctor-assignments');
    });
    
    // Doctor routes
    Route::middleware(['user.type:doctor'])->group(function () {
        Route::get('/doctor/dashboard', [DashboardController::class, 'doctorDashboard'])->name('doctor.dashboard');
    });
    
    // Patient routes
    Route::middleware(['user.type:patient'])->group(function () {
        Route::get('/patient/dashboard', [DashboardController::class, 'patientDashboard'])->name('patient.dashboard');
    });
    
    // Laboratory staff routes
    Route::middleware(['user.type:laboratory_staff'])->group(function () {
        Route::get('/lab/dashboard', [DashboardController::class, 'labDashboard'])->name('lab.dashboard');
    });
    
    // Pharmacy routes
    Route::middleware(['user.type:pharmacist'])->group(function () {
        Route::get('/pharmacy/dashboard', [DashboardController::class, 'pharmacyDashboard'])->name('pharmacy.dashboard');
    });
});
