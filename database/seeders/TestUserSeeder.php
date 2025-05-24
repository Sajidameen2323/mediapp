<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class TestUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create test users if they don't exist
        $testUsers = [
            [
                'name' => 'Test Admin',
                'email' => 'admin@test.com',
                'password' => Hash::make('password'),
                'user_type' => 'admin',
                'is_active' => true,
            ],
            [
                'name' => 'Test Doctor',
                'email' => 'doctor@test.com',
                'password' => Hash::make('password'),
                'user_type' => 'doctor',
                'is_active' => true,
            ],
            [
                'name' => 'Test Patient',
                'email' => 'patient@test.com',
                'password' => Hash::make('password'),
                'user_type' => 'patient',
                'is_active' => true,
            ],
        ];

        foreach ($testUsers as $userData) {
            $user = User::firstOrCreate(
                ['email' => $userData['email']],
                $userData
            );

            // Assign role based on user_type
            if (!$user->hasRole($userData['user_type'])) {
                $user->assignRole($userData['user_type']);
            }
            
            echo "Created/found user: {$user->name} ({$user->user_type})\n";
        }
    }
}
