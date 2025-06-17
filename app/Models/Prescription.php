<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Prescription extends Model
{
    protected $fillable = [
        'doctor_id',
        'patient_id',
        'medical_report_id',
        'prescription_number',
        'notes',
        'status',
        'prescribed_date',
        'valid_until',
        'is_repeatable',
        'refills_allowed',
        'refills_remaining',
    ];

    protected $casts = [
        'prescribed_date' => 'date',
        'valid_until' => 'date',
        'is_repeatable' => 'boolean',
    ];

    /**
     * Get the doctor that prescribed this.
     */
    public function doctor(): BelongsTo
    {
        return $this->belongsTo(Doctor::class);
    }

    /**
     * Get the patient this prescription is for.
     */
    public function patient(): BelongsTo
    {
        return $this->belongsTo(User::class, 'patient_id');
    }

    /**
     * Get the medical report this prescription belongs to.
     */
    public function medicalReport(): BelongsTo
    {
        return $this->belongsTo(MedicalReport::class);
    }

    /**
     * Get the medications for this prescription.
     */
    public function medications(): BelongsToMany
    {
        return $this->belongsToMany(Medication::class, 'prescription_medications')
            ->withPivot(['dosage', 'frequency', 'duration', 'instructions', 'quantity_prescribed', 'quantity_dispensed', 'unit_price', 'total_price'])
            ->withTimestamps();
    }

    /**
     * Get the prescription medications.
     */
    public function prescriptionMedications(): HasMany
    {
        return $this->hasMany(PrescriptionMedication::class);
    }

    /**
     * Generate a unique prescription number.
     */
    public static function generatePrescriptionNumber(): string
    {
        $prefix = 'RX';
        $date = now()->format('Ymd');
        $random = str_pad(mt_rand(1, 9999), 4, '0', STR_PAD_LEFT);
        
        return "{$prefix}{$date}{$random}";
    }

    /**
     * Check if prescription is valid.
     */
    public function isValid(): bool
    {
        return $this->status === 'pending' && 
               ($this->valid_until === null || $this->valid_until->isFuture());
    }

    /**
     * Check if prescription has refills remaining.
     */
    public function hasRefillsRemaining(): bool
    {
        return $this->is_repeatable && $this->refills_remaining > 0;
    }
}
