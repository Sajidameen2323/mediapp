<?php

namespace Database\Seeders;

use App\Models\MedicalReport;
use App\Models\MedicalReportAccess;
use Illuminate\Database\Seeder;

class MedicalReportAccessSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create author access for existing medical reports
        $medicalReports = MedicalReport::all();
        
        foreach ($medicalReports as $report) {
            // Create author access if it doesn't exist
            MedicalReportAccess::firstOrCreate(
                [
                    'medical_report_id' => $report->id,
                    'doctor_id' => $report->doctor_id,
                ],
                [
                    'patient_id' => $report->patient_id,
                    'access_type' => 'author',
                    'status' => 'active',
                    'granted_at' => $report->created_at,
                    'notes' => 'Automatic access as report author',
                ]
            );
        }

        $this->command->info('Medical report access records created successfully.');
    }
}
