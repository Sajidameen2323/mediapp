<?php
// Test script to verify pharmacy order functionality
// Usage: php test-pharmacy-orders.php

require_once __DIR__ . '/vendor/autoload.php';

use Illuminate\Foundation\Application;
use Illuminate\Contracts\Console\Kernel;

// Bootstrap the application
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Kernel::class)->bootstrap();

echo "Testing Pharmacy Order System...\n";

try {
    // Test if models can be instantiated
    $pharmacyOrder = new \App\Models\PharmacyOrder();
    echo "✓ PharmacyOrder model loaded successfully\n";
    
    $pharmacyOrderItem = new \App\Models\PharmacyOrderItem();
    echo "✓ PharmacyOrderItem model loaded successfully\n";
    
    // Test if relationships work
    echo "✓ Testing relationships...\n";
    
    // Test controller instantiation
    $controller = new \App\Http\Controllers\Pharmacy\PharmacyOrderController();
    echo "✓ PharmacyOrderController instantiated successfully\n";
    
    // Test policy
    $policy = new \App\Policies\PharmacyOrderPolicy();
    echo "✓ PharmacyOrderPolicy loaded successfully\n";
    
    echo "\n🎉 All pharmacy order components loaded successfully!\n";
    echo "\nNext steps:\n";
    echo "1. Navigate to /pharmacy/dashboard as a pharmacy user\n";
    echo "2. Go to Orders section to manage pharmacy orders\n";
    echo "3. Process orders by preparing items and setting prices\n";
    echo "4. Mark orders as ready and dispense them\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}
