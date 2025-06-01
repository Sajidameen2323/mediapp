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


        // Create test patient
        $patient = User::create([
            'name' => 'Test Patient',
            'email' => 'patient@mediapp.com',
            'password' => bcrypt('password'),
            'user_type' => 'patient',
            'is_active' => true,
        ]);
        $patient->assignRole('patient');

        // Create test doctor user
        $doctorUser = User::create([
            'name' => 'Dr. John Doe',
            'email' => 'doctor@mediapp.com',
            'password' => bcrypt('password'),
            'user_type' => 'doctor',
            'is_active' => true,
        ]);
        $doctorUser->assignRole('doctor');

        // Create doctor profile
        $doctor = \App\Models\Doctor::create([
            'user_id' => $doctorUser->id,
            'specialization' => 'General Medicine',
            'experience_years' => 10,
            'consultation_fee' => 500,
            'is_available' => true,
            'bio' => 'Experienced general physician.',
            'qualifications' => 'MBBS, MD', // Added required field
            'license_number' => 'DOC123456', // Added required field
        ]);

        // Create laboratory user
        $labUser = User::create([
            'name' => 'Lab User',
            'email' => 'lab@mediapp.com',
            'password' => bcrypt('password'),
            'user_type' => 'laboratory_staff',
            'is_active' => true,
            'phone_number' => '1112223333',
            'address' => '123 Main St, Lab City',
        ]);
        $labUser->assignRole('laboratory_staff');

        // Create laboratory
        $lab = \App\Models\Laboratory::create([
            'user_id' => $labUser->id,
            'name' => 'Central Lab',
            'description' => 'A full service diagnostic laboratory.',
            'address' => '123 Main St',
            'city' => 'Lab City',
            'state' => 'Lab State',
            'postal_code' => '12345',
            'country' => 'Lab Country',
            'license_number' => 'LAB123456',
            'is_available' => true,
        ]);

        // Create pharmacy user
        $pharmacyUser = User::create([
            'name' => 'Pharmacy User',
            'email' => 'pharmacy@mediapp.com',
            'password' => bcrypt('password'),
            'user_type' => 'pharmacist',
            'is_active' => true,
            'phone_number' => '2223334444',
            'address' => '456 Market St, Pharma City',
        ]);
        $pharmacyUser->assignRole('pharmacist');

        // Create pharmacy
        $pharmacy = \App\Models\Pharmacy::create([
            'user_id' => $pharmacyUser->id,
            'pharmacy_name' => 'City Pharmacy',
            'address' => '456 Market St',
            'city' => 'Pharma City',
            'state' => 'Pharma State',
            'postal_code' => '67890',
            'country' => 'Pharma Country',
            'license_number' => 'PHARM123456',
            'is_available' => true,
        ]);

        // Create service
        $service = \App\Models\Service::create([
            'name' => 'General Consultation',
            'description' => 'Consultation with a general physician.',
            'price' => 500,
            'duration_minutes' => 30,
            'is_active' => true,
            'category' => 'consultation',
        ]);

        // Attach service to doctor
        $doctor->services()->attach($service->id);

        // Create doctor schedule
        \App\Models\DoctorSchedule::create([
            'doctor_id' => $doctor->id,
            'day_of_week' => 1, // Monday
            'start_time' => '09:00:00',
            'end_time' => '17:00:00',
            'is_available' => true,
        ]);
    }
}
