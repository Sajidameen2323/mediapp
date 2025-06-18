<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Http\Requests\Doctor\StoreMedicalReportRequest;
use App\Http\Requests\Doctor\UpdateMedicalReportRequest;
use App\Models\LabTestRequest;
use App\Models\MedicalReport;
use App\Models\Medication;
use App\Models\Prescription;
use App\Models\PrescriptionMedication;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class MedicalReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        Gate::authorize('doctor-access');
        $doctor = auth()->user()->doctor;
        if (!$doctor) {
            return redirect()->route('dashboard')->with('error', 'Doctor profile not found.');
        }
        $query = $doctor->medicalReports()->with('patient')->orderBy('consultation_date', 'desc');

        // Filter by patient if provided
        if ($request->has('patient_id') && $request->patient_id) {
            $query->where('patient_id', $request->patient_id);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by report type
        if ($request->filled('report_type')) {
            $query->where('report_type', $request->report_type);
        }

        // Filter by date range (date_from, date_to)
        if ($request->filled('date_from')) {
            $query->where('consultation_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->where('consultation_date', '<=', $request->date_to);
        }

        // Search by diagnosis or chief complaint
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('diagnosis', 'like', "%{$search}%")
                    ->orWhere('chief_complaint', 'like', "%{$search}%")
                    ->orWhere('treatment_plan', 'like', "%{$search}%");
            });
        }

        $reports = $query->paginate(15)->appends($request->except('page'));
        $patients = User::role('patient')->select('id', 'name')->orderBy('name')->get();
        return view('dashboard.doctor.medical-reports.index', compact('reports', 'patients'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        Gate::authorize('doctor-access');

        // Get patients for the dropdown
        $patients = User::role('patient')->select('id', 'name', 'email')->orderBy('name')->get();

        // Pre-select patient if provided
        $selectedPatient = null;
        if ($request->has('patient_id')) {
            $selectedPatient = User::find($request->patient_id);
        }

        return view('dashboard.doctor.medical-reports.create', compact('patients', 'selectedPatient'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreMedicalReportRequest $request)
    {
        Gate::authorize('doctor-access');

        $doctor = auth()->user()->doctor;

        $data = $request->validated();
        $data['doctor_id'] = $doctor->id;

        // Set status from button clicked (draft or completed)
        $status = $request->input('status', 'draft');
        $data['status'] = $status;

        // Set completed_at if status is completed
        if ($status === 'completed') {
            $data['completed_at'] = now();
        }

        $report = MedicalReport::create($data);

        // Create automatic access record for the report author
        $report->createAuthorAccess();

        // Process prescriptions if provided
        $this->processPrescriptions($request, $report);

        // Process lab test requests if provided
        $this->processLabTestRequests($request, $report);

        // Prepare success message based on status
        $successMessage = $status === 'completed'
            ? 'Medical report completed successfully!'
            : 'Medical report saved as draft successfully!';

        // Check if request came from appointment page (check for appointment_id or referrer)
        $referrer = $request->headers->get('referer');
        if ($referrer && strpos($referrer, '/appointments/') !== false) {
            // If coming from appointment page, redirect back to appointments
            return redirect()->back()
                ->with('success', $successMessage)
                ->with('report_details', [
                    'patient_name' => $report->patient->name,
                    'consultation_date' => $report->consultation_date->format('M d, Y'),
                    'status' => $report->status,
                ]);
        }

        // Default redirect to medical reports index
        return redirect()->route('doctor.medical-reports.index')
            ->with('success', $successMessage)
            ->with('report_details', [
                'patient_name' => $report->patient->name,
                'consultation_date' => $report->consultation_date->format('M d, Y'),
                'status' => $report->status,
            ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(MedicalReport $medicalReport)
    {
        Gate::authorize('doctor-access');

        // Check if the doctor has access to this medical report
        $doctorId = auth()->user()->doctor->id;
        if (!$medicalReport->doctorHasAccess($doctorId)) {
            abort(403, 'You do not have permission to view this medical report.');
        }

        $medicalReport->load([
            'patient', 
            'doctor.user',
            'prescriptions.prescriptionMedications.medication',
            'labTestRequests.laboratory',
            'accessRecords' => function ($query) use ($doctorId) {
                $query->where('doctor_id', $doctorId)->where('status', 'active');
            }
        ]);

        // Determine the doctor's access level for the view
        $isAuthor = $medicalReport->doctor_id === $doctorId;
        $hasEditAccess = $isAuthor; // Only authors can edit
        
        // Get access details if not the author
        $accessDetails = null;
        if (!$isAuthor) {
            $accessRecord = $medicalReport->accessRecords->first();
            if ($accessRecord) {
                $accessDetails = [
                    'granted_at' => $accessRecord->granted_at,
                    'expires_at' => $accessRecord->expires_at,
                    'notes' => $accessRecord->notes,
                    'access_type' => $accessRecord->access_type
                ];
            }
        }

        return view('dashboard.doctor.medical-reports.show', compact(
            'medicalReport', 
            'isAuthor', 
            'hasEditAccess', 
            'accessDetails'
        ));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MedicalReport $medicalReport)
    {
        Gate::authorize('doctor-access');

        $doctorId = auth()->user()->doctor->id;
        
        // Check if the doctor has access to view this medical report
        if (!$medicalReport->doctorHasAccess($doctorId)) {
            abort(403, 'You do not have permission to access this medical report.');
        }

        // Only the original author can edit the report
        if ($medicalReport->doctor_id !== $doctorId) {
            abort(403, 'You can only edit medical reports that you authored. This report was shared with you for viewing only.');
        }

        // Get patients for the dropdown
        $patients = User::role('patient')->select('id', 'name', 'email')->orderBy('name')->get();

        return view('dashboard.doctor.medical-reports.edit', compact('medicalReport', 'patients'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateMedicalReportRequest $request, MedicalReport $medicalReport)
    {
        Gate::authorize('doctor-access');

        $doctorId = auth()->user()->doctor->id;
        
        // Check if the doctor has access to this medical report
        if (!$medicalReport->doctorHasAccess($doctorId)) {
            abort(403, 'You do not have permission to access this medical report.');
        }

        // Only the original author can edit the report
        if ($medicalReport->doctor_id !== $doctorId) {
            abort(403, 'You can only edit medical reports that you authored. This report was shared with you for viewing only.');
        }

        $data = $request->validated();

        // Set status from button clicked (draft or completed)
        $status = $request->input('status', $medicalReport->status);
        $data['status'] = $status;

        // Set completed_at if status is completed
        if ($status === 'completed' && $medicalReport->status !== 'completed') {
            $data['completed_at'] = now();
        } elseif ($status === 'draft') {
            $data['completed_at'] = null;
        }

        $medicalReport->update($data);

        // Update prescriptions if provided
        $this->updatePrescriptions($request, $medicalReport);

        // Update lab test requests if provided
        $this->updateLabTestRequests($request, $medicalReport);

        // Prepare success message based on status
        $successMessage = $status === 'completed'
            ? 'Medical report updated and completed successfully!'
            : 'Medical report updated successfully!';

        // Redirect to medical reports index with success message
        return redirect()->route('doctor.medical-reports.index')
            ->with('success', $successMessage)
            ->with('report_details', [
                'patient_name' => $medicalReport->patient->name,
                'consultation_date' => $medicalReport->consultation_date->format('M d, Y'),
                'status' => $medicalReport->status,
            ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MedicalReport $medicalReport)
    {
        Gate::authorize('doctor-access');

        // Ensure the report belongs to the authenticated doctor
        if ($medicalReport->doctor_id !== auth()->user()->doctor->id) {
            abort(403);
        }

        $medicalReport->delete();

        return redirect()->route('doctor.medical-reports.index')
            ->with('success', 'Medical report deleted successfully.');
    }

    /**
     * Export a medical report as PDF
     */
    public function exportPdf(MedicalReport $medicalReport)
    {
        Gate::authorize('doctor-access');

        // Ensure the report belongs to the authenticated doctor
        if ($medicalReport->doctor_id !== auth()->user()->doctor->id) {
            abort(403);
        }

        $medicalReport->load('patient', 'doctor.user');

        $pdf = Pdf::loadView('dashboard.doctor.medical-reports.pdf', compact('medicalReport'));
        $filename = 'Medical_Report_' . $medicalReport->patient->name . '_' . $medicalReport->consultation_date->format('Ymd') . '.pdf';
        return $pdf->download($filename);
    }

    /**
     * Get patients for autocomplete
     */
    public function getPatients(Request $request)
    {
        Gate::authorize('doctor-access');

        $search = $request->get('search', '');

        $patients = User::role('patient')
            ->where(function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            })
            ->select('id', 'name', 'email')
            ->limit(10)
            ->get();

        return response()->json([
            'patients' => $patients->map(function ($patient) {
                return [
                    'id' => $patient->id,
                    'name' => $patient->name,
                    'email' => $patient->email,
                    'display' => $patient->name . ' (' . $patient->email . ')',
                ];
            })
        ]);
    }

    /**
     * Process prescription data from the medical report form.
     */
    private function processPrescriptions($request, $report)
    {
        $prescriptions = $request->input('prescriptions', []);
        
        if (empty($prescriptions)) {
            return;
        }

        // Create a prescription record
        $prescription = Prescription::create([
            'doctor_id' => $report->doctor_id,
            'patient_id' => $report->patient_id,
            'medical_report_id' => $report->id,
            'prescription_number' => Prescription::generatePrescriptionNumber(),
            'notes' => 'Prescribed as part of medical consultation',
            'status' => 'pending',
            'prescribed_date' => $report->consultation_date,
            'valid_until' => $report->consultation_date->addDays(30), // Valid for 30 days
            'is_repeatable' => false,
            'refills_allowed' => 0,
            'refills_remaining' => 0,
        ]);

        // Process each medication
        foreach ($prescriptions as $medicationData) {
            if (empty($medicationData['medication_name'])) {
                continue;
            }

            // Find or create medication
            $medication = $this->findOrCreateMedication($medicationData['medication_name']);

            // Create prescription medication record
            PrescriptionMedication::create([
                'prescription_id' => $prescription->id,
                'medication_id' => $medication->id,
                'dosage' => $medicationData['dosage'] ?? '',
                'frequency' => $medicationData['frequency'] ?? '',
                'duration' => $medicationData['duration'] ?? '',
                'instructions' => $medicationData['instructions'] ?? '',
                'quantity_prescribed' => $medicationData['quantity'] ?? 1,
                'quantity_dispensed' => 0,
            ]);
        }
    }

    /**
     * Process lab test request data from the medical report form.
     */
    private function processLabTestRequests($request, $report)
    {
        $labTests = $request->input('lab_tests', []);
        
        if (empty($labTests)) {
            return;
        }

        // Process each lab test request
        foreach ($labTests as $testData) {
            if (empty($testData['test_name'])) {
                continue;
            }

            LabTestRequest::create([
                'doctor_id' => $report->doctor_id,
                'patient_id' => $report->patient_id,
                'medical_report_id' => $report->id,
                'laboratory_id' => null, // Will be assigned later
                'request_number' => LabTestRequest::generateRequestNumber(),
                'test_name' => $testData['test_name'],
                'test_type' => $testData['test_type'] ?? 'other',
                'test_description' => null,
                'clinical_notes' => $testData['clinical_notes'] ?? '',
                'priority' => $testData['priority'] ?? 'routine',
                'status' => 'pending',
                'requested_date' => $report->consultation_date,
                'preferred_date' => isset($testData['preferred_date']) ? 
                    \Carbon\Carbon::parse($testData['preferred_date']) : null,
            ]);
        }
    }

    /**
     * Find existing medication or create a new one.
     */
    private function findOrCreateMedication($medicationName)
    {
        // Try to find existing medication by name
        $medication = Medication::where('name', 'like', "%{$medicationName}%")
            ->orWhere('generic_name', 'like', "%{$medicationName}%")
            ->orWhere('brand_name', 'like', "%{$medicationName}%")
            ->first();

        if (!$medication) {
            // Create a new medication if not found
            $medication = Medication::create([
                'name' => $medicationName,
                'generic_name' => $medicationName,
                'dosage_form' => 'Unknown',
                'strength' => 'As prescribed',
                'description' => 'Added from medical report',
                'requires_prescription' => true,
                'is_controlled' => false,
                'is_active' => true,
            ]);
        }

        return $medication;
    }

    /**
     * Update prescription data from the medical report form.
     */
    private function updatePrescriptions($request, $report)
    {
        // Delete existing prescriptions for this medical report
        $report->prescriptions()->each(function ($prescription) {
            $prescription->prescriptionMedications()->delete();
            $prescription->delete();
        });

        // Process new prescriptions
        $this->processPrescriptions($request, $report);
    }

    /**
     * Update lab test request data from the medical report form.
     */
    private function updateLabTestRequests($request, $report)
    {
        // Delete existing lab test requests for this medical report
        $report->labTestRequests()->delete();

        // Process new lab test requests
        $this->processLabTestRequests($request, $report);
    }
}
