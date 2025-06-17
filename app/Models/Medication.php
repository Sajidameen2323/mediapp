<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Medication extends Model
{
    protected $fillable = [
        'name',
        'generic_name',
        'brand_name',
        'dosage_form',
        'strength',
        'description',
        'manufacturer',
        'price',
        'requires_prescription',
        'is_controlled',
        'side_effects',
        'contraindications',
        'warnings',
        'is_active',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'requires_prescription' => 'boolean',
        'is_controlled' => 'boolean',
        'is_active' => 'boolean',
    ];

    /**
     * Get the prescriptions that include this medication.
     */
    public function prescriptions(): BelongsToMany
    {
        return $this->belongsToMany(Prescription::class, 'prescription_medications')
            ->withPivot(['dosage', 'frequency', 'duration', 'instructions', 'quantity_prescribed', 'quantity_dispensed', 'unit_price', 'total_price'])
            ->withTimestamps();
    }

    /**
     * Scope to get active medications.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Search medications by name or generic name.
     */
    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('generic_name', 'like', "%{$search}%")
              ->orWhere('brand_name', 'like', "%{$search}%");
        });
    }

    /**
     * Get full medication name.
     */
    public function getFullNameAttribute(): string
    {
        $name = $this->name;
        if ($this->strength) {
            $name .= " {$this->strength}";
        }
        if ($this->dosage_form) {
            $name .= " ({$this->dosage_form})";
        }
        return $name;
    }
}
