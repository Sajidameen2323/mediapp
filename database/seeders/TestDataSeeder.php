<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Doctor;
use App\Models\Service;
use App\Models\DoctorSchedule;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class TestDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create services first
        $services = [
            [
                'name' => 'General Consultation',
                'description' => 'Comprehensive health checkup and consultation with experienced physicians',
                'price' => 150.00,
                'duration_minutes' => 30,
                'category' => 'General Medicine',
                'is_active' => true,
            ],
            [
                'name' => 'Cardiology Consultation',
                'description' => 'Specialized heart and cardiovascular system examination',
                'price' => 300.00,
                'duration_minutes' => 45,
                'category' => 'Cardiology',
                'is_active' => true,
            ],
            [
                'name' => 'Dermatology Treatment',
                'description' => 'Skin conditions diagnosis and treatment',
                'price' => 200.00,
                'duration_minutes' => 30,
                'category' => 'Dermatology',
                'is_active' => true,
            ],
            [
                'name' => 'Pediatric Consultation',
                'description' => 'Specialized care for children and adolescents',
                'price' => 180.00,
                'duration_minutes' => 40,
                'category' => 'Pediatrics',
                'is_active' => true,
            ],
            [
                'name' => 'Mental Health Consultation',
                'description' => 'Psychological evaluation and mental health support',
                'price' => 250.00,
                'duration_minutes' => 60,
                'category' => 'Psychiatry',
                'is_active' => true,
            ],
        ];

        foreach ($services as $serviceData) {
            Service::create($serviceData);
        }

        // Ensure doctor role exists
        $doctorRole = Role::firstOrCreate(['name' => 'doctor']);

        // Create sample doctors
        $doctors = [
            [
                'user' => [
                    'name' => 'Dr. Sarah Johnson',
                    'email' => 'sarah.johnson@mediapp.com',
                    'phone_number' => '+1-555-0101',
                    'gender' => 'female',
                    'address' => '123 Medical Center Dr, Healthcare City, HC 12345',
                    'date_of_birth' => '1980-03-15',
                    'user_type' => 'doctor',
                    'is_active' => true,
                ],
                'doctor' => [
                    'specialization' => 'Cardiology',
                    'qualifications' => 'MD Cardiology, FACC, Fellowship in Interventional Cardiology',
                    'experience_years' => 12,
                    'license_number' => 'MD12345678',
                    'consultation_fee' => 300.00,
                    'bio' => 'Dr. Sarah Johnson is a board-certified cardiologist with over 12 years of experience in treating cardiovascular diseases. She specializes in interventional cardiology and has performed over 1000 successful procedures.',
                    'is_available' => true,
                ],
                'schedules' => [
                    ['monday', '09:00', '17:00'],
                    ['tuesday', '09:00', '17:00'],
                    ['wednesday', '09:00', '17:00'],
                    ['thursday', '09:00', '17:00'],
                    ['friday', '09:00', '15:00'],
                ],
                'services' => ['General Consultation', 'Cardiology Consultation'],
            ],
            [
                'user' => [
                    'name' => 'Dr. Michael Chen',
                    'email' => 'michael.chen@mediapp.com',
                    'phone_number' => '+1-555-0102',
                    'gender' => 'male',
                    'address' => '456 Wellness Ave, Medical District, MD 67890',
                    'date_of_birth' => '1975-08-22',
                    'user_type' => 'doctor',
                    'is_active' => true,
                ],
                'doctor' => [
                    'specialization' => 'Dermatology',
                    'qualifications' => 'MD Dermatology, Board Certified, Fellowship in Dermatopathology',
                    'experience_years' => 18,
                    'license_number' => 'MD87654321',
                    'consultation_fee' => 200.00,
                    'bio' => 'Dr. Michael Chen is a renowned dermatologist with expertise in both medical and cosmetic dermatology. He has been treating skin conditions for over 18 years and is known for his patient-centered approach.',
                    'is_available' => true,
                ],
                'schedules' => [
                    ['monday', '08:00', '16:00'],
                    ['tuesday', '08:00', '16:00'],
                    ['wednesday', '08:00', '16:00'],
                    ['thursday', '08:00', '16:00'],
                    ['friday', '08:00', '14:00'],
                    ['saturday', '10:00', '14:00'],
                ],
                'services' => ['General Consultation', 'Dermatology Treatment'],
            ],
            [
                'user' => [
                    'name' => 'Dr. Emily Rodriguez',
                    'email' => 'emily.rodriguez@mediapp.com',
                    'phone_number' => '+1-555-0103',
                    'gender' => 'female',
                    'address' => '789 Children\'s Hospital Rd, Pediatric City, PC 54321',
                    'date_of_birth' => '1982-11-10',
                    'user_type' => 'doctor',
                    'is_active' => true,
                ],
                'doctor' => [
                    'specialization' => 'Pediatrics',
                    'qualifications' => 'MD Pediatrics, Board Certified in Pediatrics, FAAP',
                    'experience_years' => 10,
                    'license_number' => 'MD11223344',
                    'consultation_fee' => 180.00,
                    'bio' => 'Dr. Emily Rodriguez is a dedicated pediatrician who provides comprehensive care for children from infancy through adolescence. She is passionate about preventive care and child development.',
                    'is_available' => true,
                ],
                'schedules' => [
                    ['monday', '08:30', '17:30'],
                    ['tuesday', '08:30', '17:30'],
                    ['wednesday', '08:30', '17:30'],
                    ['thursday', '08:30', '17:30'],
                    ['friday', '08:30', '16:00'],
                ],
                'services' => ['General Consultation', 'Pediatric Consultation'],
            ],
            [
                'user' => [
                    'name' => 'Dr. James Wilson',
                    'email' => 'james.wilson@mediapp.com',
                    'phone_number' => '+1-555-0104',
                    'gender' => 'male',
                    'address' => '321 Mental Health Center, Wellness Town, WT 98765',
                    'date_of_birth' => '1978-05-18',
                    'user_type' => 'doctor',
                    'is_active' => true,
                ],
                'doctor' => [
                    'specialization' => 'Psychiatry',
                    'qualifications' => 'MD Psychiatry, Board Certified, Fellowship in Child and Adolescent Psychiatry',
                    'experience_years' => 14,
                    'license_number' => 'MD55667788',
                    'consultation_fee' => 250.00,
                    'bio' => 'Dr. James Wilson is a compassionate psychiatrist specializing in anxiety, depression, and mood disorders. He uses evidence-based treatments and has extensive experience in both individual and group therapy.',
                    'is_available' => true,
                ],
                'schedules' => [
                    ['tuesday', '09:00', '18:00'],
                    ['wednesday', '09:00', '18:00'],
                    ['thursday', '09:00', '18:00'],
                    ['friday', '09:00', '17:00'],
                    ['saturday', '10:00', '15:00'],
                ],
                'services' => ['General Consultation', 'Mental Health Consultation'],
            ],
        ];

        foreach ($doctors as $doctorData) {
            // Create user
            $user = User::create([
                ...$doctorData['user'],
                'password' => Hash::make('password123'),
            ]);

            // Assign doctor role
            $user->assignRole($doctorRole);

            // Create doctor profile
            $doctor = Doctor::create([
                'user_id' => $user->id,
                ...$doctorData['doctor'],
            ]);

            // Create schedules
            foreach ($doctorData['schedules'] as $schedule) {
                DoctorSchedule::create([
                    'doctor_id' => $doctor->id,
                    'day_of_week' => $schedule[0],
                    'start_time' => $schedule[1],
                    'end_time' => $schedule[2],
                    'is_available' => true,
                ]);
            }

            // Assign services
            $serviceIds = Service::whereIn('name', $doctorData['services'])->pluck('id');
            $doctor->services()->attach($serviceIds);
        }

        $this->command->info('Test data created successfully!');
        $this->command->info('Created ' . count($services) . ' services and ' . count($doctors) . ' doctors');
        $this->command->info('All doctors have password: password123');
    }
}
