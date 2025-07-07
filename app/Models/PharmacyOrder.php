<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PharmacyOrder extends Model
{
    protected $fillable = [
        'order_number',
        'prescription_id',
        'patient_id',
        'pharmacy_id',
        'status',
        'payment_status',
        'delivery_method',
        'delivery_address',
        'subtotal',
        'delivery_fee',
        'tax_amount',
        'total_amount',
        'notes',
        'pharmacy_notes',
        'estimated_ready_at',
        'confirmed_at',
        'ready_at',
        'delivered_at',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'delivery_fee' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'estimated_ready_at' => 'datetime',
        'confirmed_at' => 'datetime',
        'ready_at' => 'datetime',
        'delivered_at' => 'datetime',
    ];

    /**
     * Get the prescription for this order.
     */
    public function prescription(): BelongsTo
    {
        return $this->belongsTo(Prescription::class);
    }

    /**
     * Get the patient for this order.
     */
    public function patient(): BelongsTo
    {
        return $this->belongsTo(User::class, 'patient_id');
    }

    /**
     * Get the pharmacy for this order.
     */
    public function pharmacy(): BelongsTo
    {
        return $this->belongsTo(Pharmacy::class);
    }

    /**
     * Get the pharmacy order items for this order.
     */
    public function items(): HasMany
    {
        return $this->hasMany(PharmacyOrderItem::class);
    }

    /**
     * Generate a unique order number.
     */
    public static function generateOrderNumber(): string
    {
        $prefix = 'PO';
        $date = now()->format('Ymd');
        $random = str_pad(mt_rand(1, 9999), 4, '0', STR_PAD_LEFT);
        
        return "{$prefix}{$date}{$random}";
    }

    /**
     * Check if order can be cancelled.
     */
    public function canBeCancelled(): bool
    {
        return in_array($this->status, ['pending', 'confirmed']);
    }

    /**
     * Get status badge color.
     */
    public function getStatusBadgeColor(): string
    {
        return match($this->status) {
            'pending' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-800 dark:text-yellow-200',
            'confirmed' => 'bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-blue-200',
            'preparing' => 'bg-indigo-100 text-indigo-800 dark:bg-indigo-800 dark:text-indigo-200',
            'ready' => 'bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-200',
            'delivered' => 'bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-200',
            'cancelled' => 'bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-200',
            default => 'bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-200',
        };
    }

    /**
     * Get payment status badge color.
     */
    public function getPaymentStatusBadgeColor(): string
    {
        return match($this->payment_status) {
            'pending' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-800 dark:text-yellow-200',
            'paid' => 'bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-200',
            'refunded' => 'bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-200',
            default => 'bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-200',
        };
    }

    /**
     * Check if payment can be processed.
     * Payment can only be processed when:
     * - Order is in 'ready' state (price is finalized)
     * - OR order is 'delivered' but payment is still pending
     */
    public function canProcessPayment(): bool
    {
        return $this->payment_status === 'pending' && in_array($this->status, ['ready', 'delivered']);
    }

    /**
     * Check if payment is completed.
     */
    public function isPaymentCompleted(): bool
    {
        return $this->payment_status === 'paid';
    }
}
