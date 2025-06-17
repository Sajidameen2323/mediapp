<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\LabTestRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class LabTestController extends Controller
{
    /**
     * Display a listing of patient's lab test requests.
     */    public function index(Request $request)
    {
        Gate::authorize('patient-access');

        $patient = auth()->user();
        
        $query = $patient->labTestRequests()
            ->with(['medicalReport.doctor'])
            ->orderBy('created_at', 'desc');

        // Filter by medical report
        if ($request->filled('medical_report')) {
            $query->where('medical_report_id', $request->medical_report);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by priority
        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->where('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->where('created_at', '<=', $request->date_to);
        }

        $labTests = $query->paginate(12)->appends($request->except('page'));

        return view('dashboard.patient.lab-tests.index', compact('labTests'));
    }

    /**
     * Display the specified lab test request.
     */    public function show(LabTestRequest $labTest)
    {
        Gate::authorize('patient-access');

        // Ensure the lab test request belongs to the authenticated patient
        if ($labTest->patient_id !== auth()->id()) {
            abort(403);
        }

        $labTest->load([
            'medicalReport.doctor'
        ]);

        return view('dashboard.patient.lab-tests.show', compact('labTest'));
    }
}
