<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RolePermissionSeeder::class,
        ]);

        // Create test users
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@mediapp.com',
            'password' => bcrypt('password'),
            'user_type' => 'admin',
            'is_active' => true,
        ]);
        $admin->assignRole('admin');


        // Create AppointmentConfig for doctor
        \App\Models\AppointmentConfig::create([
            'buffer_time_before' => 10,
            'buffer_time_after' => 10,
            'max_booking_days_ahead' => 30,
            'min_booking_hours_ahead' => 2,
            'tax_rate' => 0.05,
            'tax_enabled' => true,
            'cancellation_hours_limit' => 24,
            'reschedule_hours_limit' => 24,
            'allow_cancellation' => true,
            'allow_rescheduling' => true,
            'max_appointments_per_patient_per_day' => 2,
            'max_appointments_per_doctor_per_day' => 20,
            'accepted_payment_methods' => ['cash', 'card'],
            'require_payment_on_booking' => false,
            'booking_deposit_percentage' => 0,
            'auto_approve_appointments' => true,
            'require_admin_approval' => false,
            'send_confirmation_email' => true,
            'send_reminder_email' => true,
            'reminder_hours_before' => 2,
            'default_start_time' => '09:00',
            'default_end_time' => '17:00',
            'default_slot_duration' => 30,
            'allow_emergency_bookings' => false,
            'emergency_booking_hours_limit' => 0,
            'is_active' => true,
            'timezone' => 'Asia/Kolkata',
        ]);


    }
}
