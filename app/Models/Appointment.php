<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;

class Appointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'doctor_id',
        'patient_id',
        'service_id',
        'appointment_number',
        'appointment_date',
        'start_time',
        'end_time',
        'duration_minutes',
        'reason',
        'symptoms',
        'notes',
        'priority',
        'appointment_type',
        'status',
        'payment_status',
        'total_amount',
        'paid_amount',
        'tax_amount',
        'tax_percentage',
        'booked_at',
        'booking_source',
        'special_instructions',
        'cancelled_at',
        'cancelled_by',
        'cancellation_reason',
        'rescheduled_from',
        'rescheduled_at',
        'rescheduled_by',
        'reschedule_reason',
        'original_date',
        'original_time',
        'completed_at',
        'completion_notes',
        'is_rated',
        'rated_at',
        'doctor_rating_id',
    ];

    protected $casts = [
        'appointment_date' => 'date',
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
        'total_amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'tax_percentage' => 'decimal:2',
        'booked_at' => 'datetime',
        'cancelled_at' => 'datetime',
        'rescheduled_at' => 'datetime',
        'original_date' => 'date',
        'original_time' => 'datetime:H:i',
        'completed_at' => 'datetime',
        'is_rated' => 'boolean',
        'rated_at' => 'datetime',
    ];

    /**
     * The "booted" method of the model.
     */
    protected static function booted()
    {
        static::creating(function ($appointment) {
            if (empty($appointment->appointment_number)) {
                $appointment->appointment_number = self::generateAppointmentNumber();
            }
            if (empty($appointment->booked_at)) {
                $appointment->booked_at = now();
            }
        });
    }

    /**
     * Get the doctor associated with the appointment.
     */
    public function doctor(): BelongsTo
    {
        return $this->belongsTo(Doctor::class);
    }

    /**
     * Get the patient associated with the appointment.
     */
    public function patient(): BelongsTo
    {
        return $this->belongsTo(User::class, 'patient_id');
    }

    /**
     * Get the service associated with the appointment.
     */
    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    /**
     * Get the rating for this appointment.
     */
    public function doctorRating(): HasOne
    {
        return $this->hasOne(DoctorRating::class);
    }

    /**
     * Get the user who cancelled the appointment.
     */
    public function cancelledBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'cancelled_by');
    }

    /**
     * Get the user who rescheduled the appointment.
     */
    public function rescheduledBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'rescheduled_by');
    }

    /**
     * Get the original appointment if this was rescheduled.
     */
    public function originalAppointment(): BelongsTo
    {
        return $this->belongsTo(Appointment::class, 'rescheduled_from');
    }

    /**
     * Scope for filtering by status.
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope for filtering by date range.
     */
    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('appointment_date', [$startDate, $endDate]);
    }

    /**
     * Scope for filtering by doctor.
     */
    public function scopeByDoctor($query, $doctorId)
    {
        return $query->where('doctor_id', $doctorId);
    }

    /**
     * Scope for upcoming appointments.
     */
    public function scopeUpcoming($query)
    {
        return $query->where('appointment_date', '>=', now()->toDateString())
                     ->where('status', '!=', 'cancelled');
    }

    /**
     * Scope for today's appointments.
     */
    public function scopeToday($query)
    {
        return $query->where('appointment_date', now()->toDateString());
    }

    /**
     * Generate a unique appointment number.
     */
    public static function generateAppointmentNumber()
    {
        $prefix = 'APT';
        $date = now()->format('Ymd');
        $random = str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
        
        $appointmentNumber = $prefix . $date . $random;
        
        // Ensure uniqueness
        while (self::where('appointment_number', $appointmentNumber)->exists()) {
            $random = str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
            $appointmentNumber = $prefix . $date . $random;
        }
        
        return $appointmentNumber;
    }

    /**
     * Check if appointment can be cancelled.
     */
    public function canBeCancelled()
    {
        if (in_array($this->status, ['cancelled', 'completed'])) {
            return false;
        }

        $config = AppointmentConfig::getActive();
        if (!$config || !$config->allow_cancellation) {
            return false;
        }

        // Create proper datetime by combining date and time, respecting config timezone
        $appointmentDateTime = $this->appointment_date->copy()->setTimeFromTimeString($this->getRawOriginal('start_time'));
        
        // Get current time in the appointment config timezone
        $configTimezone = $config->timezone ?? 'UTC';
        $currentTime = now()->setTimezone($configTimezone);
        $appointmentDateTime->setTimezone($configTimezone);
        
        $hoursUntilAppointment = $currentTime->diffInHours($appointmentDateTime, false);

        return $hoursUntilAppointment >= $config->cancellation_hours_limit;
    }

    /**
     * Check if appointment can be rescheduled.
     */
    public function canBeRescheduled()
    {
        if (in_array($this->status, ['cancelled', 'completed'])) {
            return false;
        }

        $config = AppointmentConfig::getActive();
        if (!$config || !$config->allow_rescheduling) {
            return false;
        }

        // Create proper datetime by combining date and time, respecting config timezone
        $appointmentDateTime = $this->appointment_date->copy()->setTimeFromTimeString($this->getRawOriginal('start_time'));
        
        // Get current time in the appointment config timezone
        $configTimezone = $config->timezone ?? 'UTC';
        $currentTime = now()->setTimezone($configTimezone);
        $appointmentDateTime->setTimezone($configTimezone);
        
        $hoursUntilAppointment = $currentTime->diffInHours($appointmentDateTime, false);

        return $hoursUntilAppointment >= $config->reschedule_hours_limit;
    }

    /**
     * Check if appointment can be confirmed by doctor.
     */

    public function canBeConfirmedByDoctor()
    {
        $config = AppointmentConfig::getActive();
        if ($config && $config->require_admin_approval) {
            return false;
        }
        return $this->status === 'pending';
    }

    /**
     * Check if appointment can be rated.
     */
    public function canBeRated()
    {
        // Appointment must be completed to be rated
        if ($this->status !== 'completed') {
            return false;
        }

        // Appointment must not be already rated
        if ($this->is_rated) {
            return false;
        }

        // For testing purposes, no time restriction
        // In production, you might want to add a minimum time delay:
        // $completedAt = $this->completed_at ?: $this->updated_at;
        // if (!$completedAt || $completedAt->diffInHours(now()) < 1) {
        //     return false;
        // }

        return true;
    }

    /**
     * Check if appointment has been rated.
     */
    public function isRated()
    {
        return $this->is_rated || $this->doctorRating()->exists();
    }

    /**
     * Get the rating for this appointment.
     */
    public function getRating()
    {
        return $this->doctorRating;
    }

    /**
     * Mark appointment as rated.
     */
    public function markAsRated(DoctorRating $rating)
    {
        $this->update([
            'is_rated' => true,
            'rated_at' => now(),
            'doctor_rating_id' => $rating->id
        ]);
    }

    /**
     * Get formatted appointment time.
     */
    public function getFormattedTimeAttribute()
    {
        return $this->start_time->format('g:i A') . ' - ' . $this->end_time->format('g:i A');
    }

    /**
     * Get status badge color.
     */
    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'pending' => 'yellow',
            'confirmed' => 'green',
            'cancelled' => 'red',
            'completed' => 'blue',
            'no_show' => 'gray',
            default => 'gray'
        };
    }

    /**
     * Debug method to check timezone calculation differences
     * This can be used to verify that timezone handling is working correctly
     */
    public function getTimezoneDebugInfo()
    {
        $config = AppointmentConfig::getActive();
        if (!$config) {
            return ['error' => 'No appointment config found'];
        }

        $appointmentDateTime = $this->appointment_date->copy()->setTimeFromTimeString($this->getRawOriginal('start_time'));
        $configTimezone = $config->timezone ?? 'UTC';
        
        // Times in different contexts
        $currentTimeDefault = now();
        $currentTimeConfig = now()->setTimezone($configTimezone);
        $appointmentTimeConfig = $appointmentDateTime->setTimezone($configTimezone);
        
        return [
            'config_timezone' => $configTimezone,
            'appointment_datetime_original' => $appointmentDateTime->toDateTimeString(),
            'appointment_datetime_config_tz' => $appointmentTimeConfig->toDateTimeString(),
            'current_time_default' => $currentTimeDefault->toDateTimeString(),
            'current_time_config_tz' => $currentTimeConfig->toDateTimeString(),
            'hours_until_appointment_default' => $currentTimeDefault->diffInHours($appointmentDateTime, false),
            'hours_until_appointment_config_tz' => $currentTimeConfig->diffInHours($appointmentTimeConfig, false),
            'can_be_cancelled' => $this->canBeCancelled(),
            'can_be_rescheduled' => $this->canBeRescheduled(),
            'cancellation_limit' => $config->cancellation_hours_limit,
            'reschedule_limit' => $config->reschedule_hours_limit,
        ];
    }
    
    /**
     * Get the payments associated with this appointment.
     */
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}
