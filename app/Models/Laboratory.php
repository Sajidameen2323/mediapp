<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
}
