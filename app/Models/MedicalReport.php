<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
     * Get the prescriptions for this medical report.
     */
    public function prescriptions(): HasMany
    {
        return $this->hasMany(Prescription::class);
    }

    /**
     * Get the lab test requests for this medical report.
     */
    public function labTestRequests(): HasMany
    {
        return $this->hasMany(LabTestRequest::class);
    }

    /**
     * Get the access records for this medical report.
     */
    public function accessRecords(): HasMany
    {
        return $this->hasMany(MedicalReportAccess::class);
    }

    /**
     * Get the active access records for this medical report.
     */
    public function activeAccessRecords()
    {
        return $this->hasMany(MedicalReportAccess::class)->active();
    }

    /**
     * Get doctors who have access to this medical report.
     */
    public function authorizedDoctors()
    {
        return $this->belongsToMany(Doctor::class, 'medical_report_access')
            ->wherePivot('status', 'active')
            ->wherePivot('expires_at', '>', now())
            ->orWherePivotNull('expires_at')
            ->withPivot(['access_type', 'status', 'granted_at', 'expires_at', 'notes']);
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

    /**
     * Check if a doctor has access to this medical report.
     */
    public function doctorHasAccess($doctorId): bool
    {
        // Author always has access
        if ($this->doctor_id == $doctorId) {
            return true;
        }

        // Check for active access record
        return $this->accessRecords()
            ->where('doctor_id', $doctorId)
            ->where('status', 'active')
            ->where(function ($query) {
                $query->whereNull('expires_at')
                    ->orWhere('expires_at', '>', now());
            })
            ->exists();
    }

    /**
     * Grant access to a doctor.
     */
    public function grantAccessToDoctor($doctorId, $notes = null, $expiresAt = null)
    {
        return MedicalReportAccess::updateOrCreate(
            [
                'medical_report_id' => $this->id,
                'doctor_id' => $doctorId,
            ],
            [
                'patient_id' => $this->patient_id,
                'access_type' => 'granted',
                'status' => 'active',
                'granted_at' => now(),
                'revoked_at' => null,
                'expires_at' => $expiresAt,
                'notes' => $notes,
            ]
        );
    }

    /**
     * Revoke access from a doctor.
     */
    public function revokeAccessFromDoctor($doctorId, $notes = null)
    {
        return $this->accessRecords()
            ->where('doctor_id', $doctorId)
            ->where('access_type', '!=', 'author')
            ->update([
                'status' => 'revoked',
                'revoked_at' => now(),
                'notes' => $notes,
            ]);
    }

    /**
     * Create automatic access record for the report author.
     */
    public function createAuthorAccess()
    {
        return MedicalReportAccess::firstOrCreate(
            [
                'medical_report_id' => $this->id,
                'doctor_id' => $this->doctor_id,
            ],
            [
                'patient_id' => $this->patient_id,
                'access_type' => 'author',
                'status' => 'active',
                'granted_at' => now(),
                'notes' => 'Automatic access as report author',
            ]
        );
    }
}
