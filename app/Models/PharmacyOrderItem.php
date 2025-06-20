<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PharmacyOrderItem extends Model
{
    protected $fillable = [
        'pharmacy_order_id',
        'prescription_medication_id',
        'medication_name',
        'dosage',
        'frequency',
        'duration',
        'instructions',
        'quantity_prescribed',
        'quantity_dispensed',
        'is_available',
        'unit_price',
        'total_price',
        'pharmacy_notes',
        'status',
    ];

    protected $casts = [
        'is_available' => 'boolean',
        'quantity_prescribed' => 'integer',
        'quantity_dispensed' => 'integer',
        'unit_price' => 'decimal:2',
        'total_price' => 'decimal:2',
    ];

    /**
     * Get the pharmacy order this item belongs to.
     */
    public function pharmacyOrder(): BelongsTo
    {
        return $this->belongsTo(PharmacyOrder::class);
    }

    /**
     * Get the prescription medication this item is based on.
     */
    public function prescriptionMedication(): BelongsTo
    {
        return $this->belongsTo(PrescriptionMedication::class);
    }

    /**
     * Get status badge color.
     */
    public function getStatusBadgeColor(): string
    {
        return match($this->status) {
            'pending' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-800 dark:text-yellow-200',
            'available' => 'bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-200',
            'partial' => 'bg-orange-100 text-orange-800 dark:bg-orange-800 dark:text-orange-200',
            'unavailable' => 'bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-200',
            'dispensed' => 'bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-blue-200',
            default => 'bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-200',
        };
    }

    /**
     * Calculate total price based on quantity and unit price.
     */
    public function calculateTotalPrice(): float
    {
        return $this->quantity_dispensed * $this->unit_price;
    }
}
