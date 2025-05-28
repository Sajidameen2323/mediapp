<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Http\Requests\Doctor\StoreMedicalReportRequest;
use App\Http\Requests\Doctor\UpdateMedicalReportRequest;
use App\Models\MedicalReport;
use App\Models\User;
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
        
        // Filter by date range if provided
        if ($request->has('from_date') && $request->from_date) {
            $query->where('consultation_date', '>=', $request->from_date);
        }
        
        if ($request->has('to_date') && $request->to_date) {
            $query->where('consultation_date', '<=', $request->to_date);
        }
        
        // Search by diagnosis or chief complaint
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('diagnosis', 'like', "%{$search}%")
                  ->orWhere('chief_complaint', 'like', "%{$search}%")
                  ->orWhere('treatment_plan', 'like', "%{$search}%");
            });
        }
        
        $reports = $query->paginate(15);
        
        // Get patients for the filter dropdown
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
        
        $report = MedicalReport::create($data);
        
        return response()->json([
            'success' => true,
            'message' => 'Medical report created successfully.',
            'report' => [
                'id' => $report->id,
                'patient_name' => $report->patient->name,
                'consultation_date' => $report->consultation_date->format('M d, Y'),
                'chief_complaint' => $report->chief_complaint,
                'diagnosis' => $report->diagnosis,
            ],
            'redirect_url' => route('doctor.medical-reports.show', $report)
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
     */
    public function update(UpdateMedicalReportRequest $request, MedicalReport $medicalReport)
    {
        Gate::authorize('doctor-access');
        
        // Ensure the report belongs to the authenticated doctor
        if ($medicalReport->doctor_id !== auth()->user()->doctor->id) {
            abort(403);
        }
        
        $data = $request->validated();
        $medicalReport->update($data);
        
        return response()->json([
            'success' => true,
            'message' => 'Medical report updated successfully.',
            'report' => [
                'id' => $medicalReport->id,
                'patient_name' => $medicalReport->patient->name,
                'consultation_date' => $medicalReport->consultation_date->format('M d, Y'),
                'chief_complaint' => $medicalReport->chief_complaint,
                'diagnosis' => $medicalReport->diagnosis,
            ],
            'redirect_url' => route('doctor.medical-reports.show', $medicalReport)
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
        
        // This would typically use a PDF library like dompdf or wkhtmltopdf
        // For now, return a simple view that can be printed
        return view('dashboard.doctor.medical-reports.pdf', compact('medicalReport'));
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
