<?php

require_once __DIR__ . '/../vendor/autoload.php';

use App\Models\MedicalReport;
use App\Models\Doctor;
use App\Models\User;
use App\Models\Appointment;

// Bootstrap Laravel
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== Doctor Appointment Medical Report Access Test ===\n\n";

try {
    // Get a sample appointment
    $appointment = Appointment::with(['patient', 'doctor.user'])->first();
    
    if (!$appointment) {
        echo "❌ No appointments found. Please create an appointment first.\n";
        exit(1);
    }
    
    echo "✅ Testing with Appointment:\n";
    echo "   Patient: {$appointment->patient->name}\n";
    echo "   Doctor: Dr. {$appointment->doctor->user->name}\n";
    echo "   Date: {$appointment->appointment_date}\n\n";
    
    // Test the updated controller logic
    echo "📋 Testing Medical Report Access Logic\n";
    
    $doctor = $appointment->doctor;
    $patientId = $appointment->patient_id;
    
    // Simulate the controller query
    $medicalReports = \App\Models\MedicalReport::where('patient_id', $patientId)
        ->where(function ($query) use ($doctor) {
            // Include reports authored by this doctor
            $query->where('doctor_id', $doctor->id)
                // Or reports where this doctor has been granted access
                ->orWhereHas('accessRecords', function ($accessQuery) use ($doctor) {
                    $accessQuery->where('doctor_id', $doctor->id)
                        ->where('status', 'active')
                        ->where(function ($expQuery) {
                            $expQuery->whereNull('expires_at')
                                ->orWhere('expires_at', '>', now());
                        });
                });
        })
        ->with(['doctor.user', 'prescriptions', 'labTestRequests'])
        ->orderBy('consultation_date', 'desc')
        ->get();
    
    echo "   Medical reports accessible to this doctor: {$medicalReports->count()}\n\n";
    
    foreach ($medicalReports as $report) {
        $isAuthor = $report->doctor_id === $doctor->id;
        $accessType = $isAuthor ? 'Author' : 'Shared Access';
        
        echo "   📄 Report: {$report->title}\n";
        echo "      Access Type: {$accessType}\n";
        echo "      Author: Dr. {$report->doctor->user->name}\n";
        echo "      Status: {$report->status}\n";
        echo "      Prescriptions: {$report->prescriptions->count()}\n";
        echo "      Lab Tests: {$report->labTestRequests->count()}\n";
        
        if (!$isAuthor) {
            $accessRecord = $report->accessRecords->where('doctor_id', $doctor->id)->where('status', 'active')->first();
            if ($accessRecord) {
                echo "      Access Granted: {$accessRecord->granted_at->format('M d, Y')}\n";
                if ($accessRecord->expires_at) {
                    echo "      Access Expires: {$accessRecord->expires_at->format('M d, Y')}\n";
                }
            }
        }
        echo "\n";
    }
    
    // Test access scenarios
    echo "📋 Testing Access Scenarios\n";
    
    // Get all reports for this patient
    $allPatientReports = \App\Models\MedicalReport::where('patient_id', $patientId)->get();
    echo "   Total reports for patient: {$allPatientReports->count()}\n";
    echo "   Reports accessible to current doctor: {$medicalReports->count()}\n";
    
    $restrictedReports = $allPatientReports->count() - $medicalReports->count();
    if ($restrictedReports > 0) {
        echo "   Reports with restricted access: {$restrictedReports}\n";
        echo "   ✅ Access control is working - doctor can only see authorized reports\n";
    } else {
        echo "   ℹ️  All reports are accessible (either authored or granted access)\n";
    }
    
    echo "\n✅ Doctor appointment medical report access test completed!\n";
    
} catch (Exception $e) {
    echo "❌ Error during testing: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
    exit(1);
}
