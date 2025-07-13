<?php

// Test script for Lab Test Access Management
// This script demonstrates the access management functionality

require_once __DIR__ . '/bootstrap/app.php';

use App\Models\User;
use App\Models\Doctor;
use App\Models\LabTestRequest;
use App\Models\LabTestAccess;

echo "Testing Lab Test Access Management...\n\n";

// Find a patient and doctors
$patient = User::whereHas('roles', function($q) {
    $q->where('name', 'patient');
})->first();

$doctors = Doctor::with('user')->take(2)->get();

if (!$patient || $doctors->count() < 2) {
    echo "Error: Need at least 1 patient and 2 doctors to test access management.\n";
    exit;
}

$orderingDoctor = $doctors->first();
$consultingDoctor = $doctors->last();

echo "Patient: {$patient->name}\n";
echo "Ordering Doctor: {$orderingDoctor->user->name}\n";
echo "Consulting Doctor: {$consultingDoctor->user->name}\n\n";

// Create a sample lab test request
$labTest = LabTestRequest::create([
    'doctor_id' => $orderingDoctor->id,
    'patient_id' => $patient->id,
    'request_number' => LabTestRequest::generateRequestNumber(),
    'test_name' => 'Complete Blood Count (CBC)',
    'test_type' => 'Blood Test',
    'test_description' => 'Routine blood work to check overall health',
    'clinical_notes' => 'Patient complains of fatigue and weakness',
    'priority' => 'routine',
    'status' => 'completed',
    'requested_date' => now()->subDays(7),
    'completed_at' => now()->subDays(2),
    'test_results' => json_encode([
        'WBC' => '7.2 (4.0-11.0 K/uL)',
        'RBC' => '4.8 (4.5-5.5 M/uL)',
        'Hemoglobin' => '14.2 (12.0-16.0 g/dL)',
        'Hematocrit' => '42.1 (36.0-48.0 %)',
        'Platelets' => '285 (150-450 K/uL)'
    ]),
    'result_notes' => 'All values within normal limits. No abnormalities detected.',
    'estimated_cost' => 75.00,
    'actual_cost' => 75.00,
]);

echo "Created lab test: {$labTest->test_name}\n";
echo "Request Number: {$labTest->request_number}\n";
echo "Status: {$labTest->status}\n\n";

// Check automatic author access creation
$authorAccess = $labTest->accessRecords()->where('access_type', 'author')->first();
if ($authorAccess) {
    echo "✓ Automatic author access created for ordering doctor\n";
} else {
    echo "✗ Failed to create automatic author access\n";
}

// Test access check for ordering doctor
$hasAccess = $labTest->doctorHasAccess($orderingDoctor->id);
echo "Ordering doctor has access: " . ($hasAccess ? "✓ Yes" : "✗ No") . "\n";

// Test access check for consulting doctor (should be false initially)
$hasAccess = $labTest->doctorHasAccess($consultingDoctor->id);
echo "Consulting doctor has access: " . ($hasAccess ? "✓ Yes" : "✗ No") . "\n\n";

// Grant access to consulting doctor
echo "Granting access to consulting doctor...\n";
$labTest->grantAccessToDoctor(
    $consultingDoctor->id,
    'Access granted for consultation purposes',
    now()->addDays(30) // Expires in 30 days
);

// Test access check again
$hasAccess = $labTest->doctorHasAccess($consultingDoctor->id);
echo "Consulting doctor has access after grant: " . ($hasAccess ? "✓ Yes" : "✗ No") . "\n";

// List all access records
echo "\nAccess Records:\n";
echo "==============\n";
foreach ($labTest->accessRecords()->with('doctor.user')->get() as $access) {
    echo "- Dr. {$access->doctor->user->name}\n";
    echo "  Type: {$access->access_type}\n";
    echo "  Status: {$access->status}\n";
    echo "  Granted: {$access->granted_at}\n";
    if ($access->expires_at) {
        echo "  Expires: {$access->expires_at}\n";
    }
    if ($access->notes) {
        echo "  Notes: {$access->notes}\n";
    }
    echo "\n";
}

echo "Test completed successfully!\n";
echo "You can now visit the appointment show page to see the lab test results section.\n";
