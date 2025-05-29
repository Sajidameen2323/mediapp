<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
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
        'patient_name',
        'patient_phone',
        'patient_email',
        'patient_notes',
        'status',
        'payment_status',
        'total_amount',
        'paid_amount',
        'booked_at',
        'booking_source',
        'special_instructions',
        'cancelled_at',
        'cancelled_by',
        'cancellation_reason',
        'rescheduled_from',
        'completed_at',
        'completion_notes',
    ];

    protected $casts = [
        'appointment_date' => 'date',
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
        'total_amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'booked_at' => 'datetime',
        'cancelled_at' => 'datetime',
        'completed_at' => 'datetime',
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
        $appointmentDateTime = Carbon::parse($this->appointment_date . ' ' . $this->start_time);
        $hoursUntilAppointment = now()->diffInHours($appointmentDateTime, false);

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
        $appointmentDateTime = Carbon::parse($this->appointment_date . ' ' . $this->start_time);
        $hoursUntilAppointment = now()->diffInHours($appointmentDateTime, false);

        return $hoursUntilAppointment >= $config->reschedule_hours_limit;
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
}
