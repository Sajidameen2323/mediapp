<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\Prescription;
use App\Models\LabTestRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class PrescriptionController extends Controller
{    /**
     * Display a listing of patient's prescriptions.
     */
    public function index(Request $request)
    {
        Gate::authorize('patient-access');

        $patient = auth()->user();
        
        // Start with basic query
        $query = $patient->prescriptions()
            ->with([
                'doctor.user', 
                'medicalReport.doctor.user', 
                'prescriptionMedications.medication'
            ])
            ->orderBy('created_at', 'desc');

        // Apply filters
        if ($request->filled('doctor')) {
            $query->where(function($q) use ($request) {
                $q->where('doctor_id', $request->doctor)
                  ->orWhereHas('medicalReport', function($q) use ($request) {
                      $q->where('doctor_id', $request->doctor);
                  });
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('prescription_number', 'like', "%{$searchTerm}%")
                  ->orWhere('notes', 'like', "%{$searchTerm}%")
                  ->orWhereHas('doctor.user', function($q) use ($searchTerm) {
                      $q->where('name', 'like', "%{$searchTerm}%");
                  })
                  ->orWhereHas('medicalReport.doctor.user', function($q) use ($searchTerm) {
                      $q->where('name', 'like', "%{$searchTerm}%");
                  })
                  ->orWhereHas('medicalReport', function($q) use ($searchTerm) {
                      $q->where('diagnosis', 'like', "%{$searchTerm}%");
                  });
            });
        }

        $prescriptions = $query->paginate(12)->appends($request->except('page'));        // Get unique doctors for filter dropdown - improved logic
        try {
            $doctorIds = collect();
            
            // Get all prescriptions for this patient first
            $patientPrescriptions = $patient->prescriptions()
                ->with(['doctor.user', 'medicalReport.doctor.user'])
                ->get();
            
            // Collect doctor IDs from both direct and medical report relationships
            foreach ($patientPrescriptions as $prescription) {
                if ($prescription->doctor_id && $prescription->doctor) {
                    $doctorIds->push($prescription->doctor_id);
                }
                
                if ($prescription->medicalReport && 
                    $prescription->medicalReport->doctor_id && 
                    $prescription->medicalReport->doctor) {
                    $doctorIds->push($prescription->medicalReport->doctor_id);
                }
            }
            
            $doctorIds = $doctorIds->unique();
            
            // Get the actual doctor models
            $doctors = \App\Models\Doctor::whereIn('id', $doctorIds)
                ->with('user')
                ->get()
                ->filter(function($doctor) {
                    return $doctor->user !== null;
                })
                ->sortBy('user.name');
          } catch (\Exception $e) {
            // Fallback to empty collection if there's an error
            $doctors = collect();
        }

        // Get available statuses
        $availableStatuses = Prescription::getAvailableStatuses();

        return view('dashboard.patient.prescriptions.index', compact('prescriptions', 'doctors', 'availableStatuses'));
    }

    /**
     * Display the specified prescription.
     */public function show(Prescription $prescription)
    {
        Gate::authorize('patient-access');

        // Ensure the prescription belongs to the authenticated patient
        if ($prescription->patient_id !== auth()->id()) {
            abort(403);
        }        $prescription->load([
            'medicalReport.doctor.user',
            'doctor.user',
            'prescriptionMedications.medication',
            'pharmacyOrders.pharmacy'
        ]);
        return view('dashboard.patient.prescriptions.show', compact('prescription'));
    }
}

// Add to User model relationships
