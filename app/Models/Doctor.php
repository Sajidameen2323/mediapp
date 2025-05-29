<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Doctor extends Model
{
    protected $fillable = [
        'user_id',
        'specialization',
        'qualifications',
        'experience_years',
        'license_number',
        'consultation_fee',
        'bio',
        'profile_image',
        'is_available',
    ];

    protected $casts = [
        'is_available' => 'boolean',
        'consultation_fee' => 'decimal:2',
    ];

    /**
     * Get the user associated with the doctor.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the schedules for the doctor.
     */
    public function schedules(): HasMany
    {
        return $this->hasMany(DoctorSchedule::class);
    }

    /**
     * Get the services associated with the doctor.
     */
    public function services(): BelongsToMany
    {
        return $this->belongsToMany(Service::class)
                    ->select('services.id', 'services.name', 'services.description', 'services.price', 'services.duration_minutes', 'services.category', 'services.is_active');
    }

    /**
     * Get the breaks for the doctor.
     */
    public function breaks(): HasMany
    {
        return $this->hasMany(DoctorBreak::class);
    }

    /**
     * Get the holidays for the doctor.
     */
    public function holidays(): HasMany
    {
        return $this->hasMany(DoctorHoliday::class);
    }

    /**
     * Get the medical reports created by the doctor.
     */
    public function medicalReports(): HasMany
    {
        return $this->hasMany(MedicalReport::class);
    }

    /**
     * Get the appointments for the doctor.
     */
    public function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class);
    }

    /**
     * Scope to get available doctors.
     */
    public function scopeAvailable($query)
    {
        return $query->where('is_available', true);
    }

    /**
     * Scope to filter by specialization.
     */
    public function scopeBySpecialization($query, $specialization)
    {
        return $query->where('specialization', $specialization);
    }
}
