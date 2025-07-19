<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'amount',
        'payment_method',
        'status',
        'reference',
        'transaction_id',
        'appointment_id',
        'user_id',
        'meta',
        'gateway_response',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'amount' => 'decimal:2',
        'meta' => 'array',
        'gateway_response' => 'array',
    ];

    /**
     * Get the user associated with the payment.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the appointment associated with the payment.
     */
    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }

    /**
     * Get formatted amount with currency.
     *
     * @return string
     */
    public function getFormattedAmountAttribute()
    {
        return '$' . number_format($this->amount, 2);
    }

    /**
     * Get formatted status with badge class.
     *
     * @return array
     */
    public function getStatusBadgeAttribute()
    {
        $classes = [
            'pending' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300',
            'completed' => 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300',
            'failed' => 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300',
            'refunded' => 'bg-indigo-100 text-indigo-800 dark:bg-indigo-900/30 dark:text-indigo-300',
            'cancelled' => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300',
        ];

        return [
            'class' => $classes[$this->status] ?? 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300',
            'status' => ucfirst($this->status)
        ];
    }

    /**
     * Get formatted payment method.
     *
     * @return string
     */
    public function getFormattedPaymentMethodAttribute()
    {
        $methods = [
            'cash' => 'Cash',
            'card' => 'Credit/Debit Card',
            'online' => 'Online Payment',
            'insurance' => 'Insurance',
            'bank_transfer' => 'Bank Transfer',
        ];
        return $methods[$this->payment_method] ?? ucfirst(str_replace('_', ' ', $this->payment_method));
    }

    /**
     * Get payments by status.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $status
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Get payments for a specific appointment.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int $appointmentId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeForAppointment($query, $appointmentId)
    {
        return $query->where('appointment_id', $appointmentId);
    }

    /**
     * Get payments for a specific user.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int $userId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }
}
