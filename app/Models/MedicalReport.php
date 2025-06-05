<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MedicalReport extends Model
{
    protected $fillable = [
        'doctor_id',
        'patient_id',
        'title',
        'report_type',
        'consultation_date',
        'chief_complaint',
        'history_of_present_illness',
        'physical_examination',
        'assessment_diagnosis',
        'treatment_plan',
        'medications_prescribed',
        'follow_up_instructions',
        'additional_notes',
        'vital_signs',
        'lab_tests_ordered',
        'imaging_studies',
        'priority_level',
        'follow_up_required',
        'status',
        'completed_at',
    ];

    protected $casts = [
        'consultation_date' => 'date',
        'vital_signs' => 'array',
        'completed_at' => 'datetime',
    ];

    /**
     * Get the doctor that created the report.
     */
    public function doctor(): BelongsTo
    {
        return $this->belongsTo(Doctor::class);
    }

    /**
     * Get the patient for whom the report was created.
     */
    public function patient(): BelongsTo
    {
        return $this->belongsTo(User::class, 'patient_id');
    }

    /**
     * Scope to get completed reports.
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * Scope to get draft reports.
     */
    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    /**
     * Scope to filter by consultation date range.
     */
    public function scopeInDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('consultation_date', [$startDate, $endDate]);
    }

    /**
     * Mark the report as completed.
     */
    public function markAsCompleted()
    {
        $this->update([
            'status' => 'completed',
            'completed_at' => now(),
        ]);
    }
}
