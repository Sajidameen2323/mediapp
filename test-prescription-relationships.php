<?php

// Quick test to verify prescription relationships and methods
require_once 'vendor/autoload.php';
require_once 'bootstrap/app.php';

use App\Models\Prescription;
use App\Models\PharmacyOrder;

echo "Testing Prescription Model Relationships...\n";

// Test getting a prescription with relationships
$prescription = Prescription::with([
    'doctor.user',
    'medicalReport.doctor.user', 
    'prescriptionMedications.medication',
    'pharmacyOrders.pharmacy'
])->first();

if ($prescription) {
    echo "✓ Prescription found: ID {$prescription->id}\n";
    
    // Test status badge method
    echo "✓ Status badge color: {$prescription->getStatusBadgeColor()}\n";
    
    // Test capability methods
    echo "✓ Can be ordered: " . ($prescription->canBeOrdered() ? 'Yes' : 'No') . "\n";
    echo "✓ Can be completed: " . ($prescription->canBeCompleted() ? 'Yes' : 'No') . "\n";
    echo "✓ Has refills remaining: " . ($prescription->hasRefillsRemaining() ? 'Yes' : 'No') . "\n";
    
    // Test relationships
    echo "✓ Medications count: " . $prescription->prescriptionMedications->count() . "\n";
    echo "✓ Pharmacy orders count: " . $prescription->pharmacyOrders->count() . "\n";
    
    if ($prescription->doctor && $prescription->doctor->user) {
        echo "✓ Doctor: Dr. {$prescription->doctor->user->name}\n";
    } elseif ($prescription->medicalReport && $prescription->medicalReport->doctor && $prescription->medicalReport->doctor->user) {
        echo "✓ Doctor (via medical report): Dr. {$prescription->medicalReport->doctor->user->name}\n";
    }
    
} else {
    echo "✗ No prescriptions found in database\n";
}

echo "\nTesting PharmacyOrder Model...\n";

$pharmacyOrder = PharmacyOrder::with('pharmacy')->first();
if ($pharmacyOrder) {
    echo "✓ Pharmacy order found: ID {$pharmacyOrder->id}\n";
    echo "✓ Status badge color: {$pharmacyOrder->getStatusBadgeColor()}\n";
    echo "✓ Can be cancelled: " . ($pharmacyOrder->canBeCancelled() ? 'Yes' : 'No') . "\n";
} else {
    echo "✗ No pharmacy orders found in database\n";
}

echo "\nAll tests completed!\n";
