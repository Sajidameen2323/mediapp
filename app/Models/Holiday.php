<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Holiday extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'date',
        'type',
        'recurring_yearly',
        'is_active',
    ];

    protected $casts = [
        'date' => 'date',
        'recurring_yearly' => 'boolean',
        'is_active' => 'boolean',
    ];

    /**
     * Get holidays for a specific date range.
     */
    public static function getForDateRange($startDate, $endDate)
    {
        return self::where('is_active', true)
            ->where(function ($query) use ($startDate, $endDate) {
                $query->whereBetween('date', [$startDate, $endDate])
                    ->orWhere(function ($q) use ($startDate, $endDate) {
                        $q->where('recurring_yearly', true)
                            ->whereRaw('DATE_FORMAT(date, "%m-%d") BETWEEN ? AND ?', [
                                Carbon::parse($startDate)->format('m-d'),
                                Carbon::parse($endDate)->format('m-d')
                            ]);
                    });
            })
            ->orderBy('date')
            ->get();
    }

    /**
     * Check if a specific date is a holiday.
     */
    public static function isHoliday($date)
    {
        $checkDate = Carbon::parse($date);
        
        return self::where('is_active', true)
            ->where(function ($query) use ($checkDate) {
                $query->where('date', $checkDate->format('Y-m-d'))
                    ->orWhere(function ($q) use ($checkDate) {
                        $q->where('recurring_yearly', true)
                            ->whereRaw('DATE_FORMAT(date, "%m-%d") = ?', [
                                $checkDate->format('m-d')
                            ]);
                    });
            })
            ->exists();
    }

    /**
     * Get holiday types.
     */
    public static function getTypes()
    {
        return [
            'national' => 'National Holiday',
            'religious' => 'Religious Holiday',
            'custom' => 'Custom Holiday',
        ];
    }
}
