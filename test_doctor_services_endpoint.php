<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Http\Request;
use App\Http\Controllers\Patient\AppointmentController;
use App\Models\Doctor;

try {
    echo "Testing getDoctorServices method authorization logic...\n";
    
    // Create a mock request without authentication
    $request = Request::create('/api/doctors/1/services', 'GET');
    $request->setRouteResolver(function () use ($request) {
        $route = new \Illuminate\Routing\Route(['GET'], '/api/doctors/1/services', []);
        $route->name('api.doctors.services.public');
        return $route;
    });
    
    echo "1. Request created with route name: " . $request->route()->getName() . "\n";
    
    // Check if user is authenticated
    echo "2. Is user authenticated: " . (auth()->check() ? 'YES' : 'NO') . "\n";
    
    // Check if route contains 'patient.'
    $routeName = $request->route()->getName();
    $containsPatient = str_contains($routeName, 'patient.');
    echo "3. Route name contains 'patient.': " . ($containsPatient ? 'YES' : 'NO') . "\n";
    
    // Check the authorization condition
    $shouldAuthorize = auth()->check() && str_contains($routeName, 'patient.');
    echo "4. Should authorize: " . ($shouldAuthorize ? 'YES' : 'NO') . "\n";
    
    // Test if doctor exists
    $doctor = Doctor::find(1);
    if ($doctor) {
        echo "5. Doctor found: " . $doctor->user->name . "\n";
        echo "6. Doctor has services: " . $doctor->services()->count() . "\n";
    } else {
        echo "5. No doctor found with ID 1\n";
    }
    
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . " Line: " . $e->getLine() . "\n";
}
