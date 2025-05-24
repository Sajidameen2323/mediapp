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

        $doctor = User::create([
            'name' => 'Dr. John Doe',
            'email' => 'doctor@mediapp.com',
            'password' => bcrypt('password'),
            'user_type' => 'doctor',
            'is_active' => true,
        ]);
        $doctor->assignRole('doctor');

        $patient = User::create([
            'name' => 'Patient User',
            'email' => 'patient@mediapp.com',
            'password' => bcrypt('password'),
            'user_type' => 'patient',
            'is_active' => true,
        ]);
        $patient->assignRole('patient');

        $labStaff = User::create([
            'name' => 'Lab Staff',
            'email' => 'lab@mediapp.com',
            'password' => bcrypt('password'),
            'user_type' => 'laboratory_staff',
            'is_active' => true,
        ]);
        $labStaff->assignRole('laboratory_staff');

        $pharmacist = User::create([
            'name' => 'Pharmacist User',
            'email' => 'pharmacist@mediapp.com',
            'password' => bcrypt('password'),
            'user_type' => 'pharmacist',
            'is_active' => true,
        ]);
        $pharmacist->assignRole('pharmacist');
    }
}
