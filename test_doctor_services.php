<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Doctor;
use App\Models\Service;

try {
    echo "Testing Doctor with Services relationship...\n";
    
    // Test 1: Get doctors with services using eager loading
    echo "1. Testing Doctor::with(['user', 'services'])->first()...\n";
    $doctor = Doctor::with(['user', 'services'])->first();
    
    if ($doctor) {
        echo "✓ Success: Found doctor: " . $doctor->user->name . "\n";
        echo "✓ Services count: " . $doctor->services->count() . "\n";
        
        foreach ($doctor->services as $service) {
            echo "  - Service: " . $service->name . " (ID: " . $service->id . ")\n";
        }
    } else {
        echo "! No doctors found in database\n";
    }
    
    echo "\n";
    
    // Test 2: Test the getDoctorServices method logic
    echo "2. Testing doctor->services() query directly...\n";
    if ($doctor) {
        $services = $doctor->services()
                          ->where('services.is_active', true)
                          ->select('services.id', 'services.name', 'services.description', 'services.duration_minutes as duration', 'services.price')
                          ->get();
        
        echo "✓ Success: Direct services query returned " . $services->count() . " services\n";
        
        foreach ($services as $service) {
            echo "  - Service: " . $service->name . " (Duration: " . $service->duration . " min, Price: $" . $service->price . ")\n";
        }
    }
    
    echo "\n";
    
    // Test 3: Test Service with Doctors relationship
    echo "3. Testing Service::with('doctors')->first()...\n";
    $service = Service::with('doctors')->first();
    
    if ($service) {
        echo "✓ Success: Found service: " . $service->name . "\n";
        echo "✓ Doctors count: " . $service->doctors->count() . "\n";
    } else {
        echo "! No services found in database\n";
    }
    
    echo "\n✓ All tests completed successfully! The SQL ambiguity issue is resolved.\n";
    
} catch (\Exception $e) {
    echo "✗ Error occurred: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}
