<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

class Laboratory extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'description',
        'address',
        'city',
        'state',
        'postal_code',
        'country',
        'website',
        'accreditation',
        'emergency_contact',
        'license_number',
        'services_offered',
        'opening_time',
        'closing_time',
        'working_days',
        'consultation_fee',
        'contact_person_name',
        'contact_person_phone',
        'equipment_details',
        'home_service_available',
        'home_service_fee',
        'is_available',
    ];

    protected $casts = [
        'working_days' => 'array',
        'services_offered' => 'array',
        'consultation_fee' => 'decimal:2',
        'home_service_fee' => 'decimal:2',
        'home_service_available' => 'boolean',
        'is_available' => 'boolean',
        'opening_time' => 'datetime:H:i',
        'closing_time' => 'datetime:H:i',
    ];

    /**
     * Get the user associated with the laboratory.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope to get available laboratories.
     */
    public function scopeAvailable($query)
    {
        return $query->where('is_available', true);
    }

    /**
     * Get working days as comma-separated string.
     */
    public function getWorkingDaysStringAttribute()
    {
        if (!$this->working_days || empty($this->working_days)) {
            return 'Not specified';
        }
        
        return implode(', ', array_map('ucfirst', $this->working_days));
    }

    /**
     * Get default working days for forms.
     */
    public static function getDefaultWorkingDays()
    {
        return ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'];
    }

    /**
     * Get the lab appointments for this laboratory.
     */
    public function labAppointments(): HasMany
    {
        return $this->hasMany(LabAppointment::class);
    }

    /**
     * Get lab test requests assigned to this laboratory.
     */
    public function labTestRequests(): HasMany
    {
        return $this->hasMany(LabTestRequest::class);
    }

    /**
     * Check if laboratory is available for appointments on a given date.
     */
    public function isAvailableOnDate($date): bool
    {
        if (!$this->is_available) {
            return false;
        }

        $workingDays = $this->working_days;
        
        // If no working days are specified, assume available all days
        if (empty($workingDays)) {
            return true;
        }

        $dayOfWeek = Carbon::parse($date)->format('l');
        
        return in_array(strtolower($dayOfWeek), array_map('strtolower', $workingDays));
    }
}
