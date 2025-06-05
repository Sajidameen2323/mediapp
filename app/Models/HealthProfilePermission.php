<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HealthProfilePermission extends Model
{
    protected $fillable = [
        'patient_id',
        'doctor_id',
        'is_granted',
        'granted_at',
        'revoked_at',
        'notes'
    ];

    protected $casts = [
        'is_granted' => 'boolean',
        'granted_at' => 'datetime',
        'revoked_at' => 'datetime',
    ];

    /**
     * Get the patient (user) that owns this permission
     */
    public function patient(): BelongsTo
    {
        return $this->belongsTo(User::class, 'patient_id');
    }

    /**
     * Get the doctor (user) that has this permission
     */
    public function doctor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }

    /**
     * Get the doctor profile information
     */
    public function doctorProfile(): BelongsTo
    {
        return $this->belongsTo(Doctor::class, 'doctor_id', 'user_id');
    }

    /**
     * Scope to get only granted permissions
     */
    public function scopeGranted($query)
    {
        return $query->where('is_granted', true)->whereNull('revoked_at');
    }

    /**
     * Grant permission and record timestamp
     */
    public function grant($notes = null)
    {
        $this->update([
            'is_granted' => true,
            'granted_at' => now(),
            'revoked_at' => null,
            'notes' => $notes
        ]);
    }

    /**
     * Revoke permission and record timestamp
     */
    public function revoke($notes = null)
    {
        $this->update([
            'is_granted' => false,
            'revoked_at' => now(),
            'notes' => $notes
        ]);
    }
}
