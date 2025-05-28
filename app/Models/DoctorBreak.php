<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DoctorBreak extends Model
{
    protected $fillable = [
        'doctor_id',
        'title',
        'start_time',
        'end_time',
        'day_of_week',
        'type',
        'specific_date',
        'is_recurring',
        'is_active',
        'notes',
    ];

    protected $casts = [
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
        'specific_date' => 'date',
        'is_recurring' => 'boolean',
        'is_active' => 'boolean',
    ];

    /**
     * Get the doctor that owns the break.
     */
    public function doctor(): BelongsTo
    {
        return $this->belongsTo(Doctor::class);
    }

    /**
     * Scope to get active breaks.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to get recurring breaks.
     */
    public function scopeRecurring($query)
    {
        return $query->where('is_recurring', true);
    }

    /**
     * Scope to filter by day of week.
     */
    public function scopeByDay($query, $day)
    {
        return $query->where('day_of_week', $day);
    }
}
