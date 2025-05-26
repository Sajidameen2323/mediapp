<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BlockedTimeSlot extends Model
{
    use HasFactory;

    protected $fillable = [
        'doctor_id',
        'date',
        'start_time',
        'end_time',
        'reason',
        'notes',
        'type',
        'is_recurring',
        'recurring_pattern',
        'recurring_end_date',
        'is_active',
    ];

    protected $casts = [
        'date' => 'date',
        'start_time' => 'datetime:H:i:s',
        'end_time' => 'datetime:H:i:s',
        'recurring_end_date' => 'date',
        'is_recurring' => 'boolean',
        'is_active' => 'boolean',
    ];

    /**
     * Get the doctor that owns the blocked time slot.
     */
    public function doctor(): BelongsTo
    {
        return $this->belongsTo(Doctor::class);
    }

    /**
     * Get blocked slots for a specific doctor and date range.
     */
    public static function getForDoctorAndDateRange($doctorId, $startDate, $endDate)
    {
        return self::where('is_active', true)
            ->where(function ($query) use ($doctorId) {
                $query->where('doctor_id', $doctorId)
                    ->orWhereNull('doctor_id'); // Global blocks
            })
            ->whereBetween('date', [$startDate, $endDate])
            ->orderBy('date')
            ->orderBy('start_time')
            ->get();
    }

    /**
     * Check if a time slot is blocked.
     */
    public static function isTimeSlotBlocked($doctorId, $date, $startTime, $endTime)
    {
        return self::where('is_active', true)
            ->where(function ($query) use ($doctorId) {
                $query->where('doctor_id', $doctorId)
                    ->orWhereNull('doctor_id'); // Global blocks
            })
            ->where('date', $date)
            ->where(function ($query) use ($startTime, $endTime) {
                $query->where(function ($q) use ($startTime, $endTime) {
                    // Check if the requested slot overlaps with blocked time
                    $q->where('start_time', '<', $endTime)
                        ->where('end_time', '>', $startTime);
                });
            })
            ->exists();
    }

    /**
     * Get blocked time slot types.
     */
    public static function getTypes()
    {
        return [
            'personal' => 'Personal Time',
            'maintenance' => 'System Maintenance',
            'holiday' => 'Holiday',
            'emergency' => 'Emergency Block',
            'other' => 'Other',
        ];
    }

    /**
     * Get recurring patterns.
     */
    public static function getRecurringPatterns()
    {
        return [
            'daily' => 'Daily',
            'weekly' => 'Weekly',
            'monthly' => 'Monthly',
        ];
    }
}
