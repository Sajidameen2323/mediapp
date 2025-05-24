<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Gate;

echo "Testing Authorization Gates...\n\n";

// Get first user of each type
$admin = User::where('user_type', 'admin')->first();
$doctor = User::where('user_type', 'doctor')->first();
$patient = User::where('user_type', 'patient')->first();

if ($admin) {
    echo "Testing Admin User: {$admin->name} (ID: {$admin->id})\n";
    echo "- user_type: {$admin->user_type}\n";
    echo "- is_active: " . ($admin->is_active ? 'true' : 'false') . "\n";
    echo "- admin-access gate: " . (Gate::forUser($admin)->allows('admin-access') ? 'ALLOWED' : 'DENIED') . "\n";
    echo "- doctor-access gate: " . (Gate::forUser($admin)->allows('doctor-access') ? 'ALLOWED' : 'DENIED') . "\n";
    echo "- Has admin role: " . ($admin->hasRole('admin') ? 'YES' : 'NO') . "\n\n";
} else {
    echo "No admin user found\n\n";
}

if ($doctor) {
    echo "Testing Doctor User: {$doctor->name} (ID: {$doctor->id})\n";
    echo "- user_type: {$doctor->user_type}\n";
    echo "- is_active: " . ($doctor->is_active ? 'true' : 'false') . "\n";
    echo "- admin-access gate: " . (Gate::forUser($doctor)->allows('admin-access') ? 'ALLOWED' : 'DENIED') . "\n";
    echo "- doctor-access gate: " . (Gate::forUser($doctor)->allows('doctor-access') ? 'ALLOWED' : 'DENIED') . "\n";
    echo "- Has doctor role: " . ($doctor->hasRole('doctor') ? 'YES' : 'NO') . "\n\n";
} else {
    echo "No doctor user found\n\n";
}

if ($patient) {
    echo "Testing Patient User: {$patient->name} (ID: {$patient->id})\n";
    echo "- user_type: {$patient->user_type}\n";
    echo "- is_active: " . ($patient->is_active ? 'true' : 'false') . "\n";
    echo "- admin-access gate: " . (Gate::forUser($patient)->allows('admin-access') ? 'ALLOWED' : 'DENIED') . "\n";
    echo "- patient-access gate: " . (Gate::forUser($patient)->allows('patient-access') ? 'ALLOWED' : 'DENIED') . "\n";
    echo "- Has patient role: " . ($patient->hasRole('patient') ? 'YES' : 'NO') . "\n\n";
} else {
    echo "No patient user found\n\n";
}

echo "All users count: " . User::count() . "\n";
