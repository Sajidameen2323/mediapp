<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Doctor;
use App\Models\LabTestRequest;
use App\Models\LabTestAccess;

class TestLabAccessManagement extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:lab-access';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test lab test access management functionality';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Testing Lab Test Access Management...');
        $this->newLine();

        // Find a patient and doctors
        $patient = User::whereHas('roles', function($q) {
            $q->where('name', 'patient');
        })->first();

        $doctors = Doctor::with('user')->take(2)->get();

        if (!$patient || $doctors->count() < 2) {
            $this->error('Need at least 1 patient and 2 doctors to test access management.');
            return;
        }

        $orderingDoctor = $doctors->first();
        $consultingDoctor = $doctors->last();

        $this->info("Patient: {$patient->name}");
        $this->info("Ordering Doctor: {$orderingDoctor->user->name}");
        $this->info("Consulting Doctor: {$consultingDoctor->user->name}");
        $this->newLine();

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

        $this->info("Created lab test: {$labTest->test_name}");
        $this->info("Request Number: {$labTest->request_number}");
        $this->info("Status: {$labTest->status}");
        $this->newLine();

        // Check automatic author access creation
        $authorAccess = $labTest->accessRecords()->where('access_type', 'author')->first();
        if ($authorAccess) {
            $this->info('✓ Automatic author access created for ordering doctor');
        } else {
            $this->error('✗ Failed to create automatic author access');
        }

        // Test access check for ordering doctor
        $hasAccess = $labTest->doctorHasAccess($orderingDoctor->id);
        $this->info('Ordering doctor has access: ' . ($hasAccess ? '✓ Yes' : '✗ No'));

        // Test access check for consulting doctor (should be false initially)
        $hasAccess = $labTest->doctorHasAccess($consultingDoctor->id);
        $this->info('Consulting doctor has access: ' . ($hasAccess ? '✓ Yes' : '✗ No'));
        $this->newLine();

        // Grant access to consulting doctor
        $this->info('Granting access to consulting doctor...');
        $labTest->grantAccessToDoctor(
            $consultingDoctor->id,
            'Access granted for consultation purposes',
            now()->addDays(30) // Expires in 30 days
        );

        // Test access check again
        $hasAccess = $labTest->doctorHasAccess($consultingDoctor->id);
        $this->info('Consulting doctor has access after grant: ' . ($hasAccess ? '✓ Yes' : '✗ No'));
        $this->newLine();

        // List all access records
        $this->info('Access Records:');
        $this->info('==============');
        foreach ($labTest->accessRecords()->with('doctor.user')->get() as $access) {
            $this->info("- Dr. {$access->doctor->user->name}");
            $this->info("  Type: {$access->access_type}");
            $this->info("  Status: {$access->status}");
            $this->info("  Granted: {$access->granted_at}");
            if ($access->expires_at) {
                $this->info("  Expires: {$access->expires_at}");
            }
            if ($access->notes) {
                $this->info("  Notes: {$access->notes}");
            }
            $this->newLine();
        }

        $this->info('Test completed successfully!');
        $this->info('Lab Test ID: ' . $labTest->id);
        $this->info('You can now visit the appointment show page to see the lab test results section.');
        
        return 0;
    }
}
