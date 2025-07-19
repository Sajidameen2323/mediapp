<?php

namespace App\Policies;

use App\Models\Prescription;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class PrescriptionPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return in_array($user->user_type, ['patient', 'doctor']);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Prescription $prescription): bool
    {
        return $user->user_type === 'patient' && $user->id === $prescription->patient_id ||
               $user->user_type === 'doctor' && $user->doctor && $user->doctor->id === $prescription->doctor_id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->user_type === 'doctor' && $user->doctor;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Prescription $prescription): bool
    {
        return $user->user_type === 'doctor' && 
               $user->doctor && 
               $user->doctor->id === $prescription->doctor_id &&
               in_array($prescription->status, ['pending', 'active']);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Prescription $prescription): bool
    {
        return $user->user_type === 'doctor' && 
               $user->doctor && 
               $user->doctor->id === $prescription->doctor_id &&
               $prescription->status === 'pending';
    }

    /**
     * Determine whether the user can order from pharmacy.
     */
    public function orderFromPharmacy(User $user, Prescription $prescription): bool
    {
        return $user->user_type === 'patient' && 
               $user->id === $prescription->patient_id &&
               $prescription->canBeOrdered() &&
               ($prescription->valid_until === null || $prescription->valid_until->isFuture());
    }

    /**
     * Determine whether the user can request a refill.
     */
    public function requestRefill(User $user, Prescription $prescription): bool
    {
        return $user->user_type === 'patient' && 
               $user->id === $prescription->patient_id &&
               $prescription->hasRefillsRemaining() &&
               ($prescription->valid_until === null || $prescription->valid_until->isFuture()) &&
               in_array($prescription->status, ['active', 'partial']);
    }

    /**
     * Determine whether the user can mark as active.
     */
    public function markAsActive(User $user, Prescription $prescription): bool
    {
        return $user->user_type === 'patient' && 
               $user->id === $prescription->patient_id &&
               $prescription->canBeActivated() &&
               ($prescription->valid_until === null || $prescription->valid_until->isFuture());
    }

    /**
     * Determine whether the user can mark as completed.
     */
    public function markAsCompleted(User $user, Prescription $prescription): bool
    {
        return $user->user_type === 'patient' && 
               $user->id === $prescription->patient_id &&
               $prescription->canBeCompleted() &&
               // Additional safety check: only allow completion if there are active orders or it's been active for some time
               ($prescription->pharmacyOrders()->where('status', '!=', 'cancelled')->exists() || 
                $prescription->updated_at->diffInDays(now()) >= 1);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Prescription $prescription): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Prescription $prescription): bool
    {
        return false;
    }
}
