<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LabTestRequest extends Model
{
    protected $fillable = [
        'doctor_id',
        'patient_id',
        'medical_report_id',
        'laboratory_id',
        'request_number',
        'test_name',
        'test_type',
        'test_description',
        'clinical_notes',
        'priority',
        'status',
        'requested_date',
        'preferred_date',
        'scheduled_at',
        'completed_at',
        'test_results',
        'lab_notes',
        'estimated_cost',
        'actual_cost',
    ];

    protected $casts = [
        'requested_date' => 'date',
        'preferred_date' => 'date',
        'scheduled_at' => 'datetime',
        'completed_at' => 'datetime',
        'test_results' => 'array',
        'estimated_cost' => 'decimal:2',
        'actual_cost' => 'decimal:2',
    ];

    /**
     * Get the doctor who requested this test.
     */
    public function doctor(): BelongsTo
    {
        return $this->belongsTo(Doctor::class);
    }

    /**
     * Get the patient this test is for.
     */
    public function patient(): BelongsTo
    {
        return $this->belongsTo(User::class, 'patient_id');
    }

    /**
     * Get the medical report this test belongs to.
     */
    public function medicalReport(): BelongsTo
    {
        return $this->belongsTo(MedicalReport::class);
    }

    /**
     * Get the laboratory assigned to this test.
     */
    public function laboratory(): BelongsTo
    {
        return $this->belongsTo(Laboratory::class);
    }

    /**
     * Get the lab appointments for this test request.
     */
    public function labAppointments(): HasMany
    {
        return $this->hasMany(LabAppointment::class);
    }

    /**
     * Get the latest lab appointment.
     */
    public function latestAppointment(): BelongsTo
    {
        return $this->belongsTo(LabAppointment::class, 'id', 'lab_test_request_id')
                    ->latestOfMany();
    }

    /**
     * Check if test has a confirmed appointment.
     */
    public function hasConfirmedAppointment(): bool
    {
        return $this->labAppointments()
                    ->where('status', 'confirmed')
                    ->exists();
    }

    /**
     * Generate a unique request number.
     */
    public static function generateRequestNumber(): string
    {
        $prefix = 'LT';
        $date = now()->format('Ymd');
        $random = str_pad(mt_rand(1, 9999), 4, '0', STR_PAD_LEFT);
        
        return "{$prefix}{$date}{$random}";
    }

    /**
     * Check if test is pending.
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Check if test is completed.
     */
    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    /**
     * Get priority badge class.
     */
    public function getPriorityBadgeClassAttribute(): string
    {
        return match($this->priority) {
            'stat' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200',
            'urgent' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200',
            'routine' => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200',
            default => 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200',
        };
    }

    /**
     * Get status badge class.
     */
    public function getStatusBadgeClassAttribute(): string
    {
        return match($this->status) {
            'completed' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
            'scheduled' => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200',
            'pending' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200',
            'cancelled' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200',
            default => 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200',
        };
    }
}
