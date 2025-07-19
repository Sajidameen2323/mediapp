<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\MedicalReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class MedicalReportController extends Controller
{
    /**
     * Display a listing of patient's medical reports.
     */
    public function index(Request $request)
    {
        Gate::authorize('patient-access');

        $patient = auth()->user();
        
        $query = $patient->medicalReports()
            ->with(['doctor'])
            ->withCount(['prescriptions', 'labTestRequests'])
            ->orderBy('created_at', 'desc');

        // Filter by doctor
        if ($request->filled('doctor')) {
            $query->where('doctor_id', $request->doctor);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->where('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->where('created_at', '<=', $request->date_to);
        }

        // Search by diagnosis or symptoms
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('diagnosis', 'like', "%{$search}%")
                  ->orWhere('symptoms', 'like', "%{$search}%")
                  ->orWhere('treatment', 'like', "%{$search}%");
            });
        }

        $medicalReports = $query->paginate(12)->appends($request->except('page'));

        return view('dashboard.patient.medical-reports.index', compact('medicalReports'));
    }

    /**
     * Display the specified medical report.
     */
    public function show(MedicalReport $medicalReport)
    {
        Gate::authorize('patient-access');

        // Ensure the medical report belongs to the authenticated patient
        if ($medicalReport->patient_id !== auth()->id()) {
            abort(403);
        }        $medicalReport->load([
            'doctor',
            // 'appointment',
            'prescriptions.prescriptionMedications.medication',
            'labTestRequests'
        ]);

        return view('dashboard.patient.medical-reports.show', compact('medicalReport'));
    }

    /**
     * Delete the specified medical report.
     */
    public function destroy(MedicalReport $medicalReport)
    {
        Gate::authorize('patient-access');

        // Ensure the medical report belongs to the authenticated patient
        if ($medicalReport->patient_id !== auth()->id()) {
            abort(403);
        }

        // Check if the report is allowed to be deleted
        if ($medicalReport->status === 'completed' && $medicalReport->created_at->diffInDays(now()) > 7) {
            return redirect()->route('patient.medical-reports.show', $medicalReport)
                ->with('error', 'You cannot delete reports that are more than a week old.');
        }

        try {
            $medicalReport->delete();
            return redirect()->route('patient.medical-reports.index')
                ->with('success', 'Medical report has been deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->route('patient.medical-reports.show', $medicalReport)
                ->with('error', 'Failed to delete the report. Please try again.');
        }
    }
}
