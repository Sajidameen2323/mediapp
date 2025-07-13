<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;

class HealthProfileMedicationController extends Controller
{
    /**
     * Get active medications for current patient from their prescriptions.
     */
    public function getActiveMedications(): JsonResponse
    {
        Gate::authorize('patient-access');
        
        $patient = auth()->user();
        
        // Get active prescriptions for the patient
        $activePrescriptions = $patient->prescriptions()
            ->whereIn('status', ['active', 'partial'])
            ->where(function($query) {
                $query->whereNull('valid_until')
                      ->orWhere('valid_until', '>', now());
            })
            ->with(['prescriptionMedications.medication'])
            ->get();
        
        $medications = [];
        
        foreach ($activePrescriptions as $prescription) {
            foreach ($prescription->prescriptionMedications as $prescriptionMedication) {
                $medication = $prescriptionMedication->medication;
                
                if ($medication) {
                    // Format medication with dosage and frequency
                    $medicationString = $medication->name;
                    
                    if ($prescriptionMedication->dosage) {
                        $medicationString .= ' ' . $prescriptionMedication->dosage;
                    }
                    
                    if ($prescriptionMedication->frequency) {
                        $medicationString .= ' ' . $prescriptionMedication->frequency;
                    }
                    
                    // Avoid duplicates
                    if (!in_array($medicationString, $medications)) {
                        $medications[] = $medicationString;
                    }
                }
            }
        }
        
        return response()->json([
            'success' => true,
            'medications' => $medications,
            'count' => count($medications)
        ]);
    }
}
