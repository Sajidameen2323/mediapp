<?php

namespace App\Policies;

use App\Models\LabTestRequest;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class LabTestRequestPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasRole(['patient', 'doctor', 'admin']);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, LabTestRequest $labTestRequest): bool
    {
        // Patient can view their own lab tests
        if ($user->hasRole('patient') && $labTestRequest->patient_id == $user->id) {
            return true;
        }

        // Doctor can view if they have access or are the ordering doctor
        if ($user->hasRole('doctor')) {
            $doctor = $user->doctor;
            if ($doctor && $labTestRequest->doctorHasAccess($doctor->id)) {
                return true;
            }
        }

        // Admin can view all
        if ($user->hasRole('admin')) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasRole(['doctor', 'admin']);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, LabTestRequest $labTestRequest): bool
    {
        // Patient can manage access for their own lab tests
        if ($user->hasRole('patient') && $labTestRequest->patient_id == $user->id) {
            return true;
        }

        // Doctor can update if they are the ordering doctor
        if ($user->hasRole('doctor')) {
            $doctor = $user->doctor;
            if ($doctor && $labTestRequest->doctor_id == $doctor->id) {
                return true;
            }
        }

        // Admin can update all
        if ($user->hasRole('admin')) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, LabTestRequest $labTestRequest): bool
    {
        // Only ordering doctor and admin can delete
        if ($user->hasRole('doctor')) {
            $doctor = $user->doctor;
            if ($doctor && $labTestRequest->doctor_id == $doctor->id) {
                return true;
            }
        }

        if ($user->hasRole('admin')) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, LabTestRequest $labTestRequest): bool
    {
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, LabTestRequest $labTestRequest): bool
    {
        return $user->hasRole('admin');
    }
}
