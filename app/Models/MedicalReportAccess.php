<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MedicalReportAccess extends Model
{
    protected $table = 'medical_report_access';
    
    protected $fillable = [
        'medical_report_id',
        'doctor_id',
        'patient_id',
        'access_type',
        'status',
        'notes',
        'granted_at',
        'revoked_at',
        'expires_at',
    ];

    protected $casts = [
        'granted_at' => 'datetime',
        'revoked_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    /**
     * Get the medical report for this access record.
     */
    public function medicalReport(): BelongsTo
    {
        return $this->belongsTo(MedicalReport::class);
    }

    /**
     * Get the doctor for this access record.
     */
    public function doctor(): BelongsTo
    {
        return $this->belongsTo(Doctor::class);
    }

    /**
     * Get the patient for this access record.
     */
    public function patient(): BelongsTo
    {
        return $this->belongsTo(User::class, 'patient_id');
    }

    /**
     * Scope to get active access records.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope to get pending access records.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope to get revoked access records.
     */
    public function scopeRevoked($query)
    {
        return $query->where('status', 'revoked');
    }

    /**
     * Scope to get author access records.
     */
    public function scopeAuthor($query)
    {
        return $query->where('access_type', 'author');
    }

    /**
     * Scope to get granted access records.
     */
    public function scopeGranted($query)
    {
        return $query->where('access_type', 'granted');
    }

    /**
     * Check if this access has expired.
     */
    public function isExpired(): bool
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    /**
     * Check if this access is currently valid.
     */
    public function isValid(): bool
    {
        return $this->status === 'active' && !$this->isExpired();
    }

    /**
     * Grant access to the doctor.
     */
    public function grant($notes = null, $expiresAt = null)
    {
        $this->update([
            'status' => 'active',
            'granted_at' => now(),
            'revoked_at' => null,
            'expires_at' => $expiresAt,
            'notes' => $notes,
        ]);
    }

    /**
     * Revoke access from the doctor.
     */
    public function revoke($notes = null)
    {
        $this->update([
            'status' => 'revoked',
            'revoked_at' => now(),
            'notes' => $notes,
        ]);
    }
}
