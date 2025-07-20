<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pharmacy extends Model
{
    protected $fillable = [
        'user_id',
        'pharmacy_name',
        'pharmacist_in_charge',
        'description',
        'address',
        'city',
        'state',
        'postal_code',
        'country',
        'license_number',
        'services_offered',
        'specializations',
        'opening_time',
        'closing_time',
        'working_days',
        'contact_person_name',
        'contact_person_phone',
        'emergency_contact',
        'website',
        'home_delivery_available',
        'home_delivery_fee',
        'minimum_order_amount',
        'prescription_required',
        'accepts_insurance',
        'is_available',
    ];

    protected $casts = [
        'working_days' => 'array',
        'specializations' => 'array',
        'home_delivery_fee' => 'decimal:2',
        'minimum_order_amount' => 'decimal:2',
        'home_delivery_available' => 'boolean',
        'prescription_required' => 'boolean',
        'accepts_insurance' => 'boolean',
        'is_available' => 'boolean',
        'opening_time' => 'datetime:H:i',
        'closing_time' => 'datetime:H:i',
    ];

    /**
     * Get the user associated with the pharmacy.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the pharmacy orders.
     */
    public function orders()
    {
        return $this->hasMany(PharmacyOrder::class);
    }

    /**
     * Scope to get available pharmacies.
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
        return ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
    }
}
