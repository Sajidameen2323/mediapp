<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        \App\Models\PharmacyOrder::class => \App\Policies\PharmacyOrderPolicy::class,
        \App\Models\Prescription::class => \App\Policies\PrescriptionPolicy::class,
    ];    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();
          // Define gates for different user types
        Gate::define('admin-access', function ($user) {
            return $user && $user->user_type === 'admin' && $user->is_active;
        });

        Gate::define('doctor-access', function ($user) {
            return $user && $user->user_type === 'doctor' && $user->is_active;
        });

        Gate::define('patient-access', function ($user) {
            return $user && $user->user_type === 'patient' && $user->is_active;
        });

        Gate::define('lab-access', function ($user) {
            return $user && $user->user_type === 'laboratory_staff' && $user->is_active;
        });

        Gate::define('laboratory-staff-access', function ($user) {
            return $user && $user->user_type === 'laboratory_staff' && $user->is_active;
        });

        Gate::define('pharmacy-access', function ($user) {
            return $user && $user->user_type === 'pharmacist' && $user->is_active;
        });

        // Define role-based gates
        Gate::define('manage-users', function ($user) {
            return $user && $user->hasRole('admin') && $user->is_active;
        });

        Gate::define('manage-patients', function ($user) {
            return $user && in_array($user->user_type, ['admin', 'doctor']) && $user->is_active;
        });

        Gate::define('view-medical-records', function ($user) {
            return $user && in_array($user->user_type, ['admin', 'doctor', 'patient']) && $user->is_active;
        });

        Gate::define('manage-lab-results', function ($user) {
            return $user && in_array($user->user_type, ['admin', 'doctor', 'laboratory_staff']) && $user->is_active;
        });

        Gate::define('manage-prescriptions', function ($user) {
            return $user && in_array($user->user_type, ['admin', 'doctor', 'pharmacist']) && $user->is_active;
        });
    }
}
