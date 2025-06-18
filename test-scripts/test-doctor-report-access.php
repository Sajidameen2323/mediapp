<?php

require_once __DIR__ . '/../vendor/autoload.php';

use App\Models\MedicalReport;
use App\Models\Doctor;
use App\Models\User;

// Bootstrap Laravel
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== Doctor Medical Report Access Control Test ===\n\n";

try {
    // Get a sample medical report
    $report = MedicalReport::with(['doctor.user', 'patient', 'accessRecords.doctor.user'])->first();
    
    if (!$report) {
        echo "❌ No medical reports found. Please create a medical report first.\n";
        exit(1);
    }
    
    echo "✅ Testing Medical Report Access Control:\n";
    echo "   Report: {$report->title}\n";
    echo "   Author: Dr. {$report->doctor->user->name}\n";
    echo "   Patient: {$report->patient->name}\n\n";
    
    // Test 1: Author access
    echo "📋 Test 1: Author Access Check\n";
    $authorId = $report->doctor_id;
    $hasAuthorAccess = $report->doctorHasAccess($authorId);
    echo "   Author has access: " . ($hasAuthorAccess ? "✅ Yes" : "❌ No") . "\n\n";
    
    // Test 2: Get all doctors
    $allDoctors = Doctor::with('user')->get();
    echo "📋 Test 2: Access Testing for All Doctors\n";
    echo "   Total doctors in system: {$allDoctors->count()}\n\n";
    
    foreach ($allDoctors as $doctor) {
        $hasAccess = $report->doctorHasAccess($doctor->id);
        $isAuthor = $doctor->id === $report->doctor_id;
        $accessType = $isAuthor ? 'Author' : ($hasAccess ? 'Granted' : 'No Access');
        
        echo "   Dr. {$doctor->user->name}: {$accessType}\n";
        
        if ($hasAccess && !$isAuthor) {
            $accessRecord = $report->accessRecords()
                ->where('doctor_id', $doctor->id)
                ->where('status', 'active')
                ->first();
            
            if ($accessRecord) {
                echo "     - Access granted: {$accessRecord->granted_at->format('M d, Y')}\n";
                if ($accessRecord->expires_at) {
                    echo "     - Expires: {$accessRecord->expires_at->format('M d, Y')}\n";
                }
                if ($accessRecord->notes) {
                    echo "     - Notes: {$accessRecord->notes}\n";
                }
            }
        }
    }
    
    // Test 3: Simulate controller access checks
    echo "\n📋 Test 3: Controller Access Simulation\n";
    
    foreach ($allDoctors as $doctor) {
        $hasAccess = $report->doctorHasAccess($doctor->id);
        $isAuthor = $report->doctor_id === $doctor->id;
        $hasEditAccess = $isAuthor;
        
        echo "   Dr. {$doctor->user->name}:\n";
        echo "     - Can View: " . ($hasAccess ? "✅ Yes" : "❌ No") . "\n";
        echo "     - Can Edit: " . ($hasEditAccess ? "✅ Yes" : "❌ No") . "\n";
        echo "     - Access Type: " . ($isAuthor ? "Author" : ($hasAccess ? "Shared" : "None")) . "\n\n";
    }
    
    // Test 4: Access record details
    echo "📋 Test 4: Access Records\n";
    $activeAccessRecords = $report->accessRecords->where('status', 'active');
    echo "   Active access records: {$activeAccessRecords->count()}\n";
    
    foreach ($activeAccessRecords as $access) {
        echo "   - Dr. {$access->doctor->user->name} ({$access->access_type})\n";
        echo "     Status: {$access->status}\n";
        echo "     Granted: {$access->granted_at->format('M d, Y H:i')}\n";
        if ($access->expires_at) {
            echo "     Expires: {$access->expires_at->format('M d, Y H:i')}\n";
        }
        if ($access->notes) {
            echo "     Notes: {$access->notes}\n";
        }
        echo "\n";
    }
    
    echo "✅ Medical report access control test completed successfully!\n";
    
} catch (Exception $e) {
    echo "❌ Error during testing: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
    exit(1);
}
