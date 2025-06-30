<?php

// Test script to verify the lab appointment results functionality

echo "Testing Lab Appointment Results Functionality\n";
echo "============================================\n\n";

// Check if database tables have the required fields
try {
    $pdo = new PDO('sqlite:database/database.sqlite');
    
    // Check lab_appointments table
    echo "1. Checking lab_appointments table structure...\n";
    $stmt = $pdo->query("PRAGMA table_info(lab_appointments)");
    $columns = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $columns[] = $row['name'];
    }
    
    $requiredFields = ['test_results', 'result_notes', 'results_file_path', 'result_uploaded_at'];
    foreach ($requiredFields as $field) {
        if (in_array($field, $columns)) {
            echo "   ✓ {$field} field exists\n";
        } else {
            echo "   ✗ {$field} field missing\n";
        }
    }
    
    echo "\n2. Checking lab_test_requests table structure...\n";
    $stmt = $pdo->query("PRAGMA table_info(lab_test_requests)");
    $columns = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $columns[] = $row['name'];
    }
    
    $requiredFieldsLabTest = ['test_results', 'results_file_path', 'result_notes'];
    foreach ($requiredFieldsLabTest as $field) {
        if (in_array($field, $columns)) {
            echo "   ✓ {$field} field exists\n";
        } else {
            echo "   ✗ {$field} field missing\n";
        }
    }
    
    echo "\n✅ Database structure verification complete!\n\n";
    
} catch (Exception $e) {
    echo "❌ Database check failed: " . $e->getMessage() . "\n";
}

// Check model fillable arrays
echo "3. Checking model configurations...\n";

// Include Laravel bootstrap
require_once 'vendor/autoload.php';

try {
    $app = require_once 'bootstrap/app.php';
    $app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
    
    // Check LabAppointment model
    $labAppointment = new App\Models\LabAppointment();
    $fillable = $labAppointment->getFillable();
    
    $requiredFillable = ['test_results', 'result_notes', 'results_file_path', 'result_uploaded_at'];
    foreach ($requiredFillable as $field) {
        if (in_array($field, $fillable)) {
            echo "   ✓ LabAppointment::{$field} is fillable\n";
        } else {
            echo "   ✗ LabAppointment::{$field} is not fillable\n";
        }
    }
    
    // Check LabTestRequest model
    $labTestRequest = new App\Models\LabTestRequest();
    $fillable = $labTestRequest->getFillable();
    
    $requiredFillableLabTest = ['test_results', 'results_file_path', 'result_notes'];
    foreach ($requiredFillableLabTest as $field) {
        if (in_array($field, $fillable)) {
            echo "   ✓ LabTestRequest::{$field} is fillable\n";
        } else {
            echo "   ✗ LabTestRequest::{$field} is not fillable\n";
        }
    }
    
    echo "\n✅ Model configuration verification complete!\n\n";
    
} catch (Exception $e) {
    echo "❌ Model check failed: " . $e->getMessage() . "\n";
}

echo "Test completed!\n";
