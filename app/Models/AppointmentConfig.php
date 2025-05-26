<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppointmentConfig extends Model
{
    use HasFactory;

    protected $fillable = [
        'buffer_time_before',
        'buffer_time_after',
        'max_booking_days_ahead',
        'min_booking_hours_ahead',
        'tax_rate',
        'tax_enabled',
        'cancellation_hours_limit',
        'reschedule_hours_limit',
        'allow_cancellation',
        'allow_rescheduling',
        'max_appointments_per_patient_per_day',
        'max_appointments_per_doctor_per_day',
        'accepted_payment_methods',
        'require_payment_on_booking',
        'booking_deposit_percentage',
        'auto_approve_appointments',
        'require_admin_approval',
        'send_confirmation_email',
        'send_reminder_email',
        'reminder_hours_before',
        'default_start_time',
        'default_end_time',
        'default_slot_duration',
        'allow_emergency_bookings',
        'emergency_booking_hours_limit',
        'is_active',
        'timezone',
    ];

    protected $casts = [
        'tax_rate' => 'decimal:4',
        'booking_deposit_percentage' => 'decimal:2',
        'tax_enabled' => 'boolean',
        'allow_cancellation' => 'boolean',
        'allow_rescheduling' => 'boolean',
        'require_payment_on_booking' => 'boolean',
        'auto_approve_appointments' => 'boolean',
        'require_admin_approval' => 'boolean',
        'send_confirmation_email' => 'boolean',
        'send_reminder_email' => 'boolean',
        'allow_emergency_bookings' => 'boolean',
        'is_active' => 'boolean',
        'accepted_payment_methods' => 'array',
        'default_start_time' => 'datetime:H:i:s',
        'default_end_time' => 'datetime:H:i:s',
    ];

    /**
     * Get the active configuration.
     */
    public static function getActive()
    {
        return self::where('is_active', true)->first() ?? self::getDefault();
    }

    /**
     * Get default configuration.
     */
    public static function getDefault()
    {
        return new self([
            'buffer_time_before' => 15,
            'buffer_time_after' => 15,
            'max_booking_days_ahead' => 30,
            'min_booking_hours_ahead' => 2,
            'tax_rate' => 0.0000,
            'tax_enabled' => false,
            'cancellation_hours_limit' => 24,
            'reschedule_hours_limit' => 24,
            'allow_cancellation' => true,
            'allow_rescheduling' => true,
            'max_appointments_per_patient_per_day' => 3,
            'max_appointments_per_doctor_per_day' => 20,
            'accepted_payment_methods' => ['cash', 'card'],
            'require_payment_on_booking' => false,
            'booking_deposit_percentage' => 0.00,
            'auto_approve_appointments' => true,
            'require_admin_approval' => false,
            'send_confirmation_email' => true,
            'send_reminder_email' => true,
            'reminder_hours_before' => 24,
            'default_start_time' => '09:00:00',
            'default_end_time' => '17:00:00',
            'default_slot_duration' => 30,
            'allow_emergency_bookings' => true,
            'emergency_booking_hours_limit' => 1,
            'is_active' => true,
            'timezone' => 'UTC',
        ]);
    }

    /**
     * Get available payment methods.
     */
    public static function getPaymentMethods()
    {
        return [
            'cash' => 'Cash',
            'card' => 'Credit/Debit Card',
            'online' => 'Online Payment',
            'insurance' => 'Insurance',
            'bank_transfer' => 'Bank Transfer',
        ];
    }

    /**
     * Get available timezones.
     */
    public static function getTimezones()
    {
        return [
            'UTC' => 'UTC',
            'America/New_York' => 'Eastern Time',
            'America/Chicago' => 'Central Time',
            'America/Denver' => 'Mountain Time',
            'America/Los_Angeles' => 'Pacific Time',
            'Europe/London' => 'London',
            'Europe/Paris' => 'Paris',
            'Asia/Tokyo' => 'Tokyo',
            'Asia/Shanghai' => 'Shanghai',
            'Asia/Kolkata' => 'India Standard Time',
        ];
    }
}
