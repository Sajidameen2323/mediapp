<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;

echo "Testing Web Authorization Flow...\n\n";

// Test with admin user
$admin = User::where('email', 'admin@test.com')->first();

if ($admin) {
    echo "Testing Admin User: {$admin->name}\n";
    echo "- Type: {$admin->user_type}\n";
    echo "- Active: " . ($admin->is_active ? 'true' : 'false') . "\n";
    
    // Simulate login
    Auth::login($admin);
    
    echo "- Authenticated: " . (Auth::check() ? 'Yes' : 'No') . "\n";
    echo "- Current user: " . (Auth::user() ? Auth::user()->name : 'None') . "\n";
    
    // Test gates with authenticated user
    echo "\nGate tests with authenticated user:\n";
    echo "- admin-access: " . (Gate::allows('admin-access') ? 'ALLOWED' : 'DENIED') . "\n";
    echo "- doctor-access: " . (Gate::allows('doctor-access') ? 'ALLOWED' : 'DENIED') . "\n";
    
    // Test authorization method
    try {
        app()->make(\App\Http\Controllers\DashboardController::class)->authorize('admin-access');
        echo "- Controller authorize('admin-access'): SUCCESS\n";
    } catch (\Exception $e) {
        echo "- Controller authorize('admin-access'): FAILED - " . $e->getMessage() . "\n";
    }
    
    Auth::logout();
    echo "\nLogged out.\n";
} else {
    echo "Admin user not found!\n";
}

echo "\n" . str_repeat("=", 50) . "\n\n";

// Test with doctor user
$doctor = User::where('email', 'doctor@test.com')->first();

if ($doctor) {
    echo "Testing Doctor User: {$doctor->name}\n";
    echo "- Type: {$doctor->user_type}\n";
    echo "- Active: " . ($doctor->is_active ? 'true' : 'false') . "\n";
    
    // Simulate login
    Auth::login($doctor);
    
    echo "- Authenticated: " . (Auth::check() ? 'Yes' : 'No') . "\n";
    echo "- Current user: " . (Auth::user() ? Auth::user()->name : 'None') . "\n";
    
    // Test gates with authenticated user
    echo "\nGate tests with authenticated user:\n";
    echo "- admin-access: " . (Gate::allows('admin-access') ? 'ALLOWED' : 'DENIED') . "\n";
    echo "- doctor-access: " . (Gate::allows('doctor-access') ? 'ALLOWED' : 'DENIED') . "\n";
    
    // Test authorization method
    try {
        app()->make(\App\Http\Controllers\DashboardController::class)->authorize('doctor-access');
        echo "- Controller authorize('doctor-access'): SUCCESS\n";
    } catch (\Exception $e) {
        echo "- Controller authorize('doctor-access'): FAILED - " . $e->getMessage() . "\n";
    }
    
    // Test admin access (should fail)
    try {
        app()->make(\App\Http\Controllers\DashboardController::class)->authorize('admin-access');
        echo "- Controller authorize('admin-access'): SUCCESS (UNEXPECTED!)\n";
    } catch (\Exception $e) {
        echo "- Controller authorize('admin-access'): FAILED (EXPECTED) - " . $e->getMessage() . "\n";
    }
    
    Auth::logout();
    echo "\nLogged out.\n";
} else {
    echo "Doctor user not found!\n";
}
