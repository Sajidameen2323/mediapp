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
    }    /**
     * Get the prescription medications.
     */
    public function prescriptionMedications(): HasMany
    {
        return $this->hasMany(PrescriptionMedication::class);
    }

    /**
     * Get the pharmacy orders for this prescription.
     */
    public function pharmacyOrders(): HasMany
    {
        return $this->hasMany(PharmacyOrder::class);
    }

    /**
     * Get active pharmacy orders (excluding cancelled orders).
     */
    public function getActivePharmacyOrdersAttribute()
    {
        return $this->pharmacyOrders->where('status', '!=', 'cancelled');
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
               ($this->valid_until === null || $this->valid_until > now());
    }

    /**
     * Check if prescription has refills remaining.
     */
    public function hasRefillsRemaining(): bool
    {
        return $this->is_repeatable && $this->refills_remaining > 0;
    }

    /**
     * Check if prescription can be ordered from pharmacy.
     */
    public function canBeOrdered(): bool
    {
        return in_array($this->status, ['pending', 'partial', 'active']) && 
               ($this->valid_until === null || $this->valid_until > now());
    }/**
     * Check if prescription can be marked as completed.
     */
    public function canBeCompleted(): bool
    {
        return in_array($this->status, ['active']);
    }

    /**
     * Check if prescription can be marked as active.
     */
    public function canBeActivated(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Mark prescription as completed.
     */
    public function markAsCompleted(): bool
    {
        if (!$this->canBeCompleted()) {
            return false;
        }

        return $this->update(['status' => 'completed']);
    }

    /**
     * Mark prescription as active.
     */
    public function markAsActive(): bool
    {
        if (!$this->canBeActivated()) {
            return false;
        }

        return $this->update(['status' => 'active']);
    }

    /**
     * Process a refill.
     */
    public function processRefill(): bool
    {
        if (!$this->hasRefillsRemaining()) {
            return false;
        }

        return $this->decrement('refills_remaining');
    }

    /**
     * Get the latest pharmacy order (excluding cancelled orders).
     */
    public function latestPharmacyOrder()
    {
        return $this->pharmacyOrders()->where('status', '!=', 'cancelled')->latest()->first();
    }    
    /**
     * Get prescription status badge color.
     */
    public function getStatusBadgeColor(): string
    {
        return match($this->status) {
            'pending' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-800 dark:text-yellow-200',
            'active' => 'bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-blue-200',
            'partial' => 'bg-indigo-100 text-indigo-800 dark:bg-indigo-800 dark:text-indigo-200',
            'completed' => 'bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-200',
            'dispensed' => 'bg-emerald-100 text-emerald-800 dark:bg-emerald-800 dark:text-emerald-200',
            'cancelled' => 'bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-200',
            'expired' => 'bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-200',
            default => 'bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-200',
        };
    }

    /**
     * Get available statuses for filtering.
     */
    public static function getAvailableStatuses(): array
    {
        return [
            'pending' => 'Pending',
            'active' => 'Active',
            'partial' => 'Partial',
            'completed' => 'Completed',
            'dispensed' => 'Dispensed',
            'cancelled' => 'Cancelled',
            'expired' => 'Expired',
        ];
    }
}
