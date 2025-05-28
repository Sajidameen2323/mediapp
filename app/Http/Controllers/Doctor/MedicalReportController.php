<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Http\Requests\Doctor\StoreMedicalReportRequest;
use App\Http\Requests\Doctor\UpdateMedicalReportRequest;
use App\Models\MedicalReport;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class MedicalReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */    public function index(Request $request)
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
     */    public function store(StoreMedicalReportRequest $request)
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
        
        // Prepare success message based on status
        $successMessage = $status === 'completed' 
            ? 'Medical report completed successfully!' 
            : 'Medical report saved as draft successfully!';
        
        // Redirect to medical reports index with success message
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
        
        // Ensure the report belongs to the authenticated doctor
        if ($medicalReport->doctor_id !== auth()->user()->doctor->id) {
            abort(403);
        }
        
        $medicalReport->load('patient', 'doctor.user');
        
        return view('dashboard.doctor.medical-reports.show', compact('medicalReport'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MedicalReport $medicalReport)
    {
        Gate::authorize('doctor-access');
        
        // Ensure the report belongs to the authenticated doctor
        if ($medicalReport->doctor_id !== auth()->user()->doctor->id) {
            abort(403);
        }
        
        // Get patients for the dropdown
        $patients = User::role('patient')->select('id', 'name', 'email')->orderBy('name')->get();
        
        return view('dashboard.doctor.medical-reports.edit', compact('medicalReport', 'patients'));
    }

    /**
     * Update the specified resource in storage.
     */    public function update(UpdateMedicalReportRequest $request, MedicalReport $medicalReport)
    {
        Gate::authorize('doctor-access');
        
        // Ensure the report belongs to the authenticated doctor
        if ($medicalReport->doctor_id !== auth()->user()->doctor->id) {
            abort(403);
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
        
        return response()->json([
            'success' => true,
            'message' => 'Medical report deleted successfully.'
        ]);
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
}
