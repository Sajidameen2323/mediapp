<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\Prescription;
use App\Models\LabTestRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class PrescriptionController extends Controller
{
    /**
     * Display a listing of patient's prescriptions.
     */    public function index(Request $request)
    {
        Gate::authorize('patient-access');

        $patient = auth()->user();
          $query = $patient->prescriptions()
            ->with(['medicalReport.doctor', 'prescriptionMedications.medication'])
            ->orderBy('created_at', 'desc');

        // Filter by medical report
        if ($request->filled('medical_report')) {
            $query->where('medical_report_id', $request->medical_report);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->where('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->where('created_at', '<=', $request->date_to);
        }

        $prescriptions = $query->paginate(12)->appends($request->except('page'));

        return view('dashboard.patient.prescriptions.index', compact('prescriptions'));
    }

    /**
     * Display the specified prescription.
     */    public function show(Prescription $prescription)
    {
        Gate::authorize('patient-access');

        // Ensure the prescription belongs to the authenticated patient
        if ($prescription->patient_id !== auth()->id()) {
            abort(403);
        }        $prescription->load([
            'medicalReport.doctor',
            'prescriptionMedications.medication'
        ]);

        return view('dashboard.patient.prescriptions.show', compact('prescription'));
    }
}

// Add to User model relationships
