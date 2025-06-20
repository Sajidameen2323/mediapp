<?php

namespace App\Policies;

use App\Models\PharmacyOrder;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class PharmacyOrderPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->user_type === 'pharmacist' && $user->pharmacy;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, PharmacyOrder $pharmacyOrder): bool
    {
        return $user->user_type === 'pharmacist' && 
               $user->pharmacy && 
               $user->pharmacy->id === $pharmacyOrder->pharmacy_id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return false; // Pharmacy can't create orders, only patients can
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, PharmacyOrder $pharmacyOrder): bool
    {
        return $user->user_type === 'pharmacist' && 
               $user->pharmacy && 
               $user->pharmacy->id === $pharmacyOrder->pharmacy_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, PharmacyOrder $pharmacyOrder): bool
    {
        return false; // Orders shouldn't be deleted, only cancelled
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, PharmacyOrder $pharmacyOrder): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, PharmacyOrder $pharmacyOrder): bool
    {
        return false;
    }
}
