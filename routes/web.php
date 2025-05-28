<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DoctorController;

// Public routes
Route::get('/', function () {
    return view('welcome');
});

// Public doctor search routes
Route::prefix('api')->group(function () {
    Route::get('/doctors/available', [DoctorController::class, 'getAvailableDoctors'])->name('api.doctors.available');
    Route::get('/doctors/search', [DoctorController::class, 'searchDoctors'])->name('api.doctors.search');
    Route::get('/doctors/specializations', [DoctorController::class, 'getSpecializations'])->name('api.doctors.specializations');
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
        
        // Doctor availability toggle
        Route::patch('/admin/doctors/{doctor}/toggle-availability', [\App\Http\Controllers\Admin\DoctorController::class, 'toggleAvailability'])
            ->name('admin.doctors.toggle-availability');
        
        // Service Management
        Route::resource('/admin/services', \App\Http\Controllers\Admin\ServiceController::class, [
            'as' => 'admin'
        ]);
        
        // Additional service routes for doctor assignment
        Route::get('/admin/services/{service}/assign-doctors', [\App\Http\Controllers\Admin\ServiceController::class, 'assignDoctors'])
            ->name('admin.services.assign-doctors');
        Route::put('/admin/services/{service}/doctor-assignments', [\App\Http\Controllers\Admin\ServiceController::class, 'updateDoctorAssignments'])
            ->name('admin.services.update-doctor-assignments');
        
        // Laboratory Management
        Route::resource('/admin/laboratories', \App\Http\Controllers\Admin\LaboratoryController::class, [
            'as' => 'admin'
        ]);
        
        // Laboratory availability toggle
        Route::patch('/admin/laboratories/{laboratory}/toggle-availability', [\App\Http\Controllers\Admin\LaboratoryController::class, 'toggleAvailability'])
            ->name('admin.laboratories.toggle-availability');
        
        // Pharmacy Management
        Route::resource('/admin/pharmacies', \App\Http\Controllers\Admin\PharmacyController::class, [
            'as' => 'admin'
        ]);
        
        // Pharmacy availability toggle
        Route::patch('/admin/pharmacies/{pharmacy}/toggle-availability', [\App\Http\Controllers\Admin\PharmacyController::class, 'toggleAvailability'])
            ->name('admin.pharmacies.toggle-availability');
        
        // Appointment Configuration Management
        Route::prefix('admin/appointment-config')->name('admin.appointment-config.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\AppointmentConfigController::class, 'index'])->name('index');
            Route::get('/edit', [\App\Http\Controllers\Admin\AppointmentConfigController::class, 'edit'])->name('edit');
            Route::put('/update', [\App\Http\Controllers\Admin\AppointmentConfigController::class, 'update'])->name('update');
            
            // Holiday Management
            Route::get('/holidays', [\App\Http\Controllers\Admin\AppointmentConfigController::class, 'holidays'])->name('holidays');
            Route::post('/holidays', [\App\Http\Controllers\Admin\AppointmentConfigController::class, 'storeHoliday'])->name('holidays.store');
            Route::delete('/holidays/{holiday}', [\App\Http\Controllers\Admin\AppointmentConfigController::class, 'destroyHoliday'])->name('holidays.destroy');
            
            // Blocked Time Slots Management
            Route::get('/blocked-slots', [\App\Http\Controllers\Admin\AppointmentConfigController::class, 'blockedSlots'])->name('blocked-slots');
            Route::post('/blocked-slots', [\App\Http\Controllers\Admin\AppointmentConfigController::class, 'storeBlockedSlot'])->name('blocked-slots.store');
            Route::delete('/blocked-slots/{blockedSlot}', [\App\Http\Controllers\Admin\AppointmentConfigController::class, 'destroyBlockedSlot'])->name('blocked-slots.destroy');
        });
    });
    
    // Doctor routes
    Route::middleware(['user.type:doctor'])->group(function () {
        Route::get('/doctor/dashboard', [\App\Http\Controllers\Doctor\DoctorDashboardController::class, 'index'])->name('doctor.dashboard');
        
        // Doctor Dashboard Features
        Route::get('/doctor/schedule', [\App\Http\Controllers\Doctor\DoctorDashboardController::class, 'schedule'])->name('doctor.schedule');
        
        // Break Management
        Route::resource('/doctor/manage-breaks', \App\Http\Controllers\Doctor\BreakController::class, [
            'as' => 'doctor',
            'parameters' => ['manage-breaks' => 'break'],
            'names' => [
                'index' => 'breaks.index',
                'create' => 'breaks.create',
                'store' => 'breaks.store',
                'show' => 'breaks.show',
                'edit' => 'breaks.edit',
                'update' => 'breaks.update',
                'destroy' => 'breaks.destroy'
            ]
        ]);
        
        // Holiday Management
        Route::resource('/doctor/manage-holidays', \App\Http\Controllers\Doctor\HolidayController::class, [
            'as' => 'doctor',
            'names' => [
                'index' => 'holidays.index',
                'create' => 'holidays.create',
                'store' => 'holidays.store',
                'show' => 'holidays.show',
                'edit' => 'holidays.edit',
                'update' => 'holidays.update',
                'destroy' => 'holidays.destroy'
            ]
        ]);
        
        // Medical Report Management
        Route::resource('/doctor/manage-reports', \App\Http\Controllers\Doctor\MedicalReportController::class, [
            'as' => 'doctor',
            'names' => [
                'index' => 'reports.index',
                'create' => 'reports.create',
                'store' => 'reports.store',
                'show' => 'reports.show',
                'edit' => 'reports.edit',
                'update' => 'reports.update',
                'destroy' => 'reports.destroy'
            ]
        ]);
        
        // Medical Report PDF Export
        Route::get('/doctor/manage-reports/{medicalReport}/pdf', [\App\Http\Controllers\Doctor\MedicalReportController::class, 'exportPdf'])
            ->name('doctor.reports.pdf');
    });
    
    // Patient routes
    Route::middleware(['user.type:patient'])->group(function () {
        Route::get('/patient/dashboard', [DashboardController::class, 'patientDashboard'])->name('patient.dashboard');
        
        // Health Profile Management
        Route::get('/patient/health-profile', [\App\Http\Controllers\Patient\HealthProfileController::class, 'index'])->name('patient.health-profile.index');
        Route::get('/patient/health-profile/create', [\App\Http\Controllers\Patient\HealthProfileController::class, 'create'])->name('patient.health-profile.create');
        Route::post('/patient/health-profile', [\App\Http\Controllers\Patient\HealthProfileController::class, 'store'])->name('patient.health-profile.store');
        Route::get('/patient/health-profile/edit', [\App\Http\Controllers\Patient\HealthProfileController::class, 'edit'])->name('patient.health-profile.edit');
        Route::put('/patient/health-profile', [\App\Http\Controllers\Patient\HealthProfileController::class, 'update'])->name('patient.health-profile.update');
        Route::delete('/patient/health-profile', [\App\Http\Controllers\Patient\HealthProfileController::class, 'destroy'])->name('patient.health-profile.destroy');
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
