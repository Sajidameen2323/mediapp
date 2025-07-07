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
    Route::get('/doctors/{doctor}', [DoctorController::class, 'show'])->name('api.doctors.show');
    Route::get('/services/{service}', [\App\Http\Controllers\Admin\ServiceController::class, 'show'])->name('api.services.show');
    
    // Public appointment-related routes for booking system
    Route::get('/doctors/{doctor}/services', [\App\Http\Controllers\Patient\AppointmentController::class, 'getDoctorServices'])->name('api.doctors.services.public');
    Route::get('/appointments/search-doctors', [\App\Http\Controllers\Patient\AppointmentController::class, 'searchDoctors'])->name('api.appointments.search-doctors');
    Route::get('/appointments/available-slots', [\App\Http\Controllers\Patient\AppointmentController::class, 'getAvailableSlots'])->name('api.appointments.available-slots');
    Route::get('/appointments/selectable-dates', [\App\Http\Controllers\Patient\AppointmentController::class, 'getSelectableDates'])->name('api.appointments.selectable-dates');
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
        
        // Doctor Holiday Request Management
        Route::prefix('admin/holidays')->name('admin.holidays.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\HolidayController::class, 'index'])->name('index');
            Route::get('/{holiday}', [\App\Http\Controllers\Admin\HolidayController::class, 'show'])->name('show');
            Route::post('/{holiday}/approve', [\App\Http\Controllers\Admin\HolidayController::class, 'approve'])->name('approve');
            Route::post('/{holiday}/reject', [\App\Http\Controllers\Admin\HolidayController::class, 'reject'])->name('reject');
            Route::post('/bulk-approve', [\App\Http\Controllers\Admin\HolidayController::class, 'bulkApprove'])->name('bulk-approve');
            Route::post('/bulk-reject', [\App\Http\Controllers\Admin\HolidayController::class, 'bulkReject'])->name('bulk-reject');
        });
        
        // Appointment Management
        Route::prefix('admin/appointments')->name('admin.appointments.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\AppointmentController::class, 'index'])->name('index');
            Route::get('/export', [\App\Http\Controllers\Admin\AppointmentController::class, 'export'])->name('export');
            Route::get('/{appointment}', [\App\Http\Controllers\Admin\AppointmentController::class, 'show'])->name('show');
            Route::post('/{appointment}/approve', [\App\Http\Controllers\Admin\AppointmentController::class, 'approve'])->name('approve');
            Route::post('/{appointment}/cancel', [\App\Http\Controllers\Admin\AppointmentController::class, 'cancel'])->name('cancel');
            Route::get('/{appointment}/reschedule', [\App\Http\Controllers\Admin\AppointmentController::class, 'reschedule'])->name('reschedule');
            Route::post('/{appointment}/reschedule', [\App\Http\Controllers\Admin\AppointmentController::class, 'updateSchedule'])->name('update-schedule');
            Route::post('/bulk-update', [\App\Http\Controllers\Admin\AppointmentController::class, 'bulkUpdate'])->name('bulk-update');
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
                'index' => 'doctor.breaks.index',
                'create' => 'doctor.breaks.create',
                'store' => 'doctor.breaks.store',
                'show' => 'doctor.breaks.show',
                'edit' => 'doctor.breaks.edit',
                'update' => 'doctor.breaks.update',
                'destroy' => 'doctor.breaks.destroy'
            ]
        ]);
        
        // Holiday Management
        Route::resource('/doctor/manage-holidays', \App\Http\Controllers\Doctor\HolidayController::class, [
            'as' => 'doctor',
            'parameters' => ['manage-holidays' => 'holiday'],
            'names' => [
                'index' => 'doctor.holidays.index',
                'create' => 'doctor.holidays.create',
                'store' => 'doctor.holidays.store',
                'show' => 'doctor.holidays.show',
                'edit' => 'doctor.holidays.edit',
                'update' => 'doctor.holidays.update',
                'destroy' => 'doctor.holidays.destroy'
            ]
        ]);
        
        // Medical Report Management
        Route::resource('/doctor/manage-reports', \App\Http\Controllers\Doctor\MedicalReportController::class, [
            'as' => 'doctor',
            'parameters' => ['manage-reports' => 'medicalReport'],
            'names' => [
                'index' => 'doctor.medical-reports.index',
                'create' => 'doctor.medical-reports.create',
                'store' => 'doctor.medical-reports.store',
                'show' => 'doctor.medical-reports.show',
                'edit' => 'doctor.medical-reports.edit',
                'update' => 'doctor.medical-reports.update',
                'destroy' => 'doctor.medical-reports.destroy'
            ]
        ]);
        
        // Medical Report PDF Export
        Route::get('/doctor/manage-reports/{medicalReport}/pdf', [\App\Http\Controllers\Doctor\MedicalReportController::class, 'exportPdf'])
            ->name('doctor.reports.pdf');
        
        // Appointment Management
        Route::prefix('doctor/appointments')->name('doctor.appointments.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Doctor\AppointmentController::class, 'index'])->name('index');
            Route::get('/calendar', [\App\Http\Controllers\Doctor\AppointmentController::class, 'calendar'])->name('calendar');
            Route::get('/calendar/data', [\App\Http\Controllers\Doctor\AppointmentController::class, 'getCalendarAppointments'])->name('calendar.data');
            Route::get('/calendar/date/{date}', [\App\Http\Controllers\Doctor\AppointmentController::class, 'getAppointmentsByDate'])->name('calendar.date');
            Route::get('/{appointment}', [\App\Http\Controllers\Doctor\AppointmentController::class, 'show'])->name('show');
            Route::patch('/{appointment}/confirm', [\App\Http\Controllers\Doctor\AppointmentController::class, 'confirm'])->name('confirm');
            Route::patch('/{appointment}/cancel', [\App\Http\Controllers\Doctor\AppointmentController::class, 'cancel'])->name('cancel');
            Route::patch('/{appointment}/complete', [\App\Http\Controllers\Doctor\AppointmentController::class, 'complete'])->name('complete');
            Route::patch('/{appointment}/no-show', [\App\Http\Controllers\Doctor\AppointmentController::class, 'noShow'])->name('no-show');
        });
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
        
        // Health Profile Permission Management
        Route::prefix('patient/health-profile/permissions')->name('patient.health-profile.permissions.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Patient\HealthProfilePermissionController::class, 'index'])->name('index');
            Route::post('/grant', [\App\Http\Controllers\Patient\HealthProfilePermissionController::class, 'grant'])->name('grant');
            Route::patch('/{permission}/revoke', [\App\Http\Controllers\Patient\HealthProfilePermissionController::class, 'revoke'])->name('revoke');
            Route::get('/{permission}', [\App\Http\Controllers\Patient\HealthProfilePermissionController::class, 'show'])->name('show');
        });
        
        // Appointment Management
        Route::prefix('patient/appointments')->name('patient.appointments.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Patient\AppointmentController::class, 'index'])->name('index');
            Route::get('/create', [\App\Http\Controllers\Patient\AppointmentController::class, 'create'])->name('create');
            Route::get('/create-enhanced', [\App\Http\Controllers\Patient\AppointmentController::class, 'createEnhanced'])->name('create-enhanced');
            Route::post('/', [\App\Http\Controllers\Patient\AppointmentController::class, 'store'])->name('store');
            Route::get('/{appointment}', [\App\Http\Controllers\Patient\AppointmentController::class, 'show'])->name('show');
            Route::patch('/{appointment}/cancel', [\App\Http\Controllers\Patient\AppointmentController::class, 'cancel'])->name('cancel');
            Route::get('/{appointment}/reschedule', [\App\Http\Controllers\Patient\AppointmentController::class, 'reschedule'])->name('reschedule');
            Route::patch('/{appointment}/reschedule', [\App\Http\Controllers\Patient\AppointmentController::class, 'updateReschedule'])->name('reschedule.update');
            Route::post('/{appointment}/rate', [\App\Http\Controllers\Patient\AppointmentController::class, 'rate'])->name('rate');
            
            // AJAX endpoints for appointment booking
            Route::get('/search-doctors', [\App\Http\Controllers\Patient\AppointmentController::class, 'searchDoctors'])->name('search-doctors');
            Route::get('/available-slots', [\App\Http\Controllers\Patient\AppointmentController::class, 'getAvailableSlots'])->name('available-slots');
            Route::get('/selectable-dates', [\App\Http\Controllers\Patient\AppointmentController::class, 'getSelectableDates'])->name('selectable-dates');
        });
        
        // API routes for enhanced booking
        Route::prefix('api')->group(function () {
            Route::get('/doctors/{doctor}/services', [\App\Http\Controllers\Patient\AppointmentController::class, 'getDoctorServices'])->name('api.doctors.services');
        });
        
        // Prescription Management
        Route::prefix('patient/prescriptions')->name('patient.prescriptions.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Patient\PrescriptionController::class, 'index'])->name('index');
            Route::get('/{prescription}', [\App\Http\Controllers\Patient\PrescriptionController::class, 'show'])->name('show');
            
            // Prescription Actions
            Route::get('/{prescription}/order-pharmacy', [\App\Http\Controllers\Patient\PrescriptionActionController::class, 'orderFromPharmacy'])->name('order-pharmacy');
            Route::post('/{prescription}/order-pharmacy', [\App\Http\Controllers\Patient\PrescriptionActionController::class, 'storePharmacyOrder'])->name('store-pharmacy-order');
            Route::post('/{prescription}/refill', [\App\Http\Controllers\Patient\PrescriptionActionController::class, 'requestRefill'])->name('request-refill');
            Route::post('/{prescription}/activate', [\App\Http\Controllers\Patient\PrescriptionActionController::class, 'markAsActive'])->name('mark-active');
            Route::post('/{prescription}/complete', [\App\Http\Controllers\Patient\PrescriptionActionController::class, 'markCompleted'])->name('mark-completed');
        });
        
        // Pharmacy Order Management
        Route::prefix('patient/pharmacy-orders')->name('patient.pharmacy-orders.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Patient\PharmacyOrderController::class, 'index'])->name('index');
            Route::get('/{pharmacyOrder}', [\App\Http\Controllers\Patient\PharmacyOrderController::class, 'show'])->name('show');
            Route::get('/{pharmacyOrder}/payment', [\App\Http\Controllers\Patient\PharmacyOrderController::class, 'showPayment'])->name('payment');
            Route::post('/{pharmacyOrder}/payment', [\App\Http\Controllers\Patient\PharmacyOrderController::class, 'processPayment'])->name('process-payment');
            Route::delete('/{pharmacyOrder}', [\App\Http\Controllers\Patient\PharmacyOrderController::class, 'cancel'])->name('cancel');
        });
        
        // Lab Test Management
        Route::prefix('patient/lab-tests')->name('patient.lab-tests.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Patient\LabTestController::class, 'index'])->name('index');
            Route::get('/{labTest}', [\App\Http\Controllers\Patient\LabTestController::class, 'show'])->name('show');
        });
        
        // Lab Appointment Management  
        Route::prefix('patient/lab-appointments')->name('patient.lab-appointments.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Patient\LabAppointmentController::class, 'index'])->name('index');
            Route::get('/create', [\App\Http\Controllers\Patient\LabAppointmentController::class, 'create'])->name('create');
            Route::get('/available-slots', [\App\Http\Controllers\Patient\LabAppointmentController::class, 'getAvailableSlots'])->name('available-slots');
            Route::post('/', [\App\Http\Controllers\Patient\LabAppointmentController::class, 'store'])->name('store');
            Route::get('/{labAppointment}', [\App\Http\Controllers\Patient\LabAppointmentController::class, 'show'])->name('show');
            Route::patch('/{labAppointment}/cancel', [\App\Http\Controllers\Patient\LabAppointmentController::class, 'cancel'])->name('cancel');
        });
        
        // Medical Reports (Patient View)
        Route::prefix('patient/medical-reports')->name('patient.medical-reports.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Patient\MedicalReportController::class, 'index'])->name('index');
            Route::get('/{medicalReport}', [\App\Http\Controllers\Patient\MedicalReportController::class, 'show'])->name('show');
            Route::get('/{medicalReport}/pdf', [\App\Http\Controllers\Patient\MedicalReportController::class, 'downloadPdf'])->name('pdf');
            
            // Access Management Routes
            Route::prefix('{medicalReport}/access')->name('access.')->group(function () {
                Route::get('/', [\App\Http\Controllers\Patient\MedicalReportAccessController::class, 'index'])->name('index');
                Route::post('/grant', [\App\Http\Controllers\Patient\MedicalReportAccessController::class, 'grant'])->name('grant');
                Route::put('/{access}', [\App\Http\Controllers\Patient\MedicalReportAccessController::class, 'update'])->name('update');
                Route::delete('/{access}', [\App\Http\Controllers\Patient\MedicalReportAccessController::class, 'revoke'])->name('revoke');
                Route::post('/bulk', [\App\Http\Controllers\Patient\MedicalReportAccessController::class, 'bulkManage'])->name('bulk');
            });
        });
    });
    
    // Laboratory staff routes
    Route::middleware(['user.type:laboratory_staff'])->group(function () {
        // Laboratory Dashboard
        Route::get('/laboratory/dashboard', [\App\Http\Controllers\Laboratory\DashboardController::class, 'index'])->name('laboratory.dashboard');
        
        // Laboratory Settings
        Route::prefix('laboratory/settings')->name('laboratory.settings.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Laboratory\SettingsController::class, 'index'])->name('index');
            Route::patch('/', [\App\Http\Controllers\Laboratory\SettingsController::class, 'update'])->name('update');
            Route::patch('/toggle-availability', [\App\Http\Controllers\Laboratory\SettingsController::class, 'toggleAvailability'])->name('toggle-availability');
        });
        
        // Lab Appointment Management
        Route::prefix('laboratory/appointments')->name('laboratory.appointments.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Laboratory\LabAppointmentController::class, 'index'])->name('index');
            Route::get('/{labAppointment}', [\App\Http\Controllers\Laboratory\LabAppointmentController::class, 'show'])->name('show');
            Route::patch('/{labAppointment}', [\App\Http\Controllers\Laboratory\LabAppointmentController::class, 'update'])->name('update');
            Route::patch('/{labAppointment}/confirm', [\App\Http\Controllers\Laboratory\LabAppointmentController::class, 'confirm'])->name('confirm');
            Route::patch('/{labAppointment}/reject', [\App\Http\Controllers\Laboratory\LabAppointmentController::class, 'reject'])->name('reject');
            Route::patch('/{labAppointment}/complete', [\App\Http\Controllers\Laboratory\LabAppointmentController::class, 'complete'])->name('complete');
            Route::get('/schedule', [\App\Http\Controllers\Laboratory\LabAppointmentController::class, 'schedule'])->name('schedule');
        });

        // Laboratory Test Results (placeholder for future implementation)
        Route::prefix('laboratory/results')->name('laboratory.results.')->group(function () {
            Route::get('/', function() { return view('dashboard.laboratory.results.index'); })->name('index');
        });
    });
    
    // Pharmacy routes
    Route::middleware(['user.type:pharmacist'])->group(function () {
        Route::get('/pharmacy/dashboard', [DashboardController::class, 'pharmacyDashboard'])->name('pharmacy.dashboard');
        
        // Pharmacy Order Management
        Route::prefix('pharmacy/orders')->name('pharmacy.orders.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Pharmacy\PharmacyOrderController::class, 'index'])->name('index');
            Route::get('/{order}', [\App\Http\Controllers\Pharmacy\PharmacyOrderController::class, 'show'])->name('show');
            Route::get('/{order}/prepare', [\App\Http\Controllers\Pharmacy\PharmacyOrderController::class, 'prepare'])->name('prepare');
            Route::patch('/{order}/items', [\App\Http\Controllers\Pharmacy\PharmacyOrderController::class, 'updateItems'])->name('update-items');
            Route::patch('/{order}/mark-ready', [\App\Http\Controllers\Pharmacy\PharmacyOrderController::class, 'markReady'])->name('mark-ready');
            Route::patch('/{order}/dispense', [\App\Http\Controllers\Pharmacy\PharmacyOrderController::class, 'dispense'])->name('dispense');
            Route::patch('/{order}/cancel', [\App\Http\Controllers\Pharmacy\PharmacyOrderController::class, 'cancel'])->name('cancel');
        });
    });
});
