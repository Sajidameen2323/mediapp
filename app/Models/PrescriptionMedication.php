<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PrescriptionMedication extends Model
{
    protected $fillable = [
        'prescription_id',
        'medication_id',
        'dosage',
        'frequency',
        'duration',
        'instructions',
        'quantity_prescribed',
        'quantity_dispensed',
        'unit_price',
        'total_price',
    ];

    protected $casts = [
        'unit_price' => 'decimal:2',
        'total_price' => 'decimal:2',
    ];

    /**
     * Get the prescription this medication belongs to.
     */
    public function prescription(): BelongsTo
    {
        return $this->belongsTo(Prescription::class);
    }

    /**
     * Get the medication.
     */
    public function medication(): BelongsTo
    {
        return $this->belongsTo(Medication::class);
    }

    /**
     * Get the remaining quantity.
     */
    public function getRemainingQuantityAttribute(): int
    {
        return $this->quantity_prescribed - $this->quantity_dispensed;
    }

    /**
     * Check if medication is fully dispensed.
     */
    public function isFullyDispensed(): bool
    {
        return $this->quantity_dispensed >= $this->quantity_prescribed;
    }

    /**
     * Get formatted dosage instruction.
     */
    public function getFormattedInstructionAttribute(): string
    {
        $instruction = "{$this->dosage} {$this->frequency}";
        if ($this->duration) {
            $instruction .= " for {$this->duration}";
        }
        if ($this->instructions) {
            $instruction .= " - {$this->instructions}";
        }
        return $instruction;
    }
}
