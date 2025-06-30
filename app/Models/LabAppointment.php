<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;

class LabAppointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'laboratory_id',
        'lab_test_request_id',
        'appointment_number',
        'appointment_date',
        'start_time',
        'end_time',
        'duration_minutes',
        'status',
        'patient_notes',
        'lab_instructions',
        'rejection_reason',
        'booked_at',
        'confirmed_at',
        'rejected_at',
        'completed_at',
        'cancelled_at',
        'cancelled_by',
        'cancellation_reason',
        'estimated_cost',
        'final_cost',
        'requires_fasting',
        'fasting_hours',
        'special_instructions',
        'test_results',
        'result_notes',
        'result_uploaded_at',
    ];

    protected $casts = [
        'appointment_date' => 'date',
        'booked_at' => 'datetime',
        'confirmed_at' => 'datetime',
        'rejected_at' => 'datetime',
        'completed_at' => 'datetime',
        'cancelled_at' => 'datetime',
        'estimated_cost' => 'decimal:2',
        'final_cost' => 'decimal:2',
        'requires_fasting' => 'boolean',
        'test_results' => 'array',
        'result_uploaded_at' => 'datetime',
    ];

    /**
     * Boot method to generate appointment number
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($labAppointment) {
            if (empty($labAppointment->appointment_number)) {
                $labAppointment->appointment_number = static::generateAppointmentNumber();
            }
        });
    }

    /**
     * Generate unique appointment number
     */
    public static function generateAppointmentNumber(): string
    {
        do {
            $number = 'LAB-' . date('Ymd') . '-' . str_pad(mt_rand(1, 9999), 4, '0', STR_PAD_LEFT);
        } while (static::where('appointment_number', $number)->exists());

        return $number;
    }

    /**
     * Get the patient who booked this appointment.
     */
    public function patient(): BelongsTo
    {
        return $this->belongsTo(User::class, 'patient_id');
    }

    /**
     * Get the laboratory for this appointment.
     */
    public function laboratory(): BelongsTo
    {
        return $this->belongsTo(Laboratory::class);
    }

    /**
     * Get the lab test request for this appointment.
     */
    public function labTestRequest(): BelongsTo
    {
        return $this->belongsTo(LabTestRequest::class);
    }

    /**
     * Check if appointment can be cancelled
     */
    public function canBeCancelled(): bool
    {
        if (in_array($this->status, ['cancelled', 'completed'])) {
            return false;
        }

        // Allow cancellation at least 2 hours before appointment
        return $this->appointment_datetime->gt(Carbon::now()->addHours(2));
    }

    /**
     * Check if appointment can be confirmed by laboratory
     */
    public function canBeConfirmed(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Check if appointment can be completed
     */
    public function canBeCompleted(): bool
    {
        return $this->status === 'confirmed' && 
               $this->appointment_date <= Carbon::today()->toDateString();
    }

    /**
     * Scope to filter by status
     */
    public function scopeWithStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope to filter by date range
     */
    public function scopeInDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('appointment_date', [$startDate, $endDate]);
    }

    /**
     * Get formatted time for display
     */
    public function getFormattedTimeAttribute(): string
    {
        try {
            $startTime = Carbon::parse($this->start_time);
            $endTime = Carbon::parse($this->end_time);
            return $startTime->format('g:i A') . ' - ' . $endTime->format('g:i A');
        } catch (\Exception $e) {
            return $this->start_time . ' - ' . $this->end_time;
        }
    }

    /**
     * Get appointment datetime as Carbon instance
     */
    public function getAppointmentDateTimeAttribute(): Carbon
    {
        try {
            $dateString = $this->appointment_date instanceof Carbon 
                ? $this->appointment_date->format('Y-m-d') 
                : $this->appointment_date;
            return Carbon::parse($dateString . ' ' . $this->start_time);
        } catch (\Exception $e) {
            return Carbon::now();
        }
    }

    /**
     * Get status badge color
     */
    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'pending' => 'yellow',
            'confirmed' => 'blue',
            'completed' => 'green',
            'cancelled' => 'red',
            'rejected' => 'red',
            default => 'gray'
        };
    }
}
