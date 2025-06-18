<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\MedicalReport;
use App\Models\MedicalReportAccess;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class MedicalReportAccessController extends Controller
{
    /**
     * Display access management for a medical report.
     */
    public function index(MedicalReport $medicalReport)
    {
        Gate::authorize('patient-access');
        
        // Ensure the medical report belongs to the authenticated patient
        if ($medicalReport->patient_id !== auth()->id()) {
            abort(403, 'You do not have permission to manage access for this medical report.');
        }

        $medicalReport->load([
            'doctor.user',
            'accessRecords.doctor.user',
        ]);

        // Get all doctors for the dropdown (exclude the author)
        $availableDoctors = Doctor::with('user')
            ->whereHas('user', function ($query) {
                $query->where('user_type', 'doctor');
            })
            ->where('id', '!=', $medicalReport->doctor_id)
            ->whereDoesntHave('medicalReportAccess', function ($query) use ($medicalReport) {
                $query->where('medical_report_id', $medicalReport->id)
                    ->where('status', 'active');
            })
            ->get();

        return view('dashboard.patient.medical-reports.access.index', compact(
            'medicalReport',
            'availableDoctors'
        ));
    }

    /**
     * Grant access to a doctor.
     */
    public function grant(Request $request, MedicalReport $medicalReport)
    {
        Gate::authorize('patient-access');
        
        if ($medicalReport->patient_id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'doctor_id' => 'required|exists:doctors,id',
            'expires_at' => 'nullable|date|after:now',
            'notes' => 'nullable|string|max:500',
        ]);

        // Check if doctor already has access
        $existingAccess = $medicalReport->accessRecords()
            ->where('doctor_id', $request->doctor_id)
            ->where('status', 'active')
            ->first();

        if ($existingAccess) {
            return back()->with('error', 'This doctor already has access to this medical report.');
        }

        $medicalReport->grantAccessToDoctor(
            $request->doctor_id,
            $request->notes,
            $request->expires_at
        );

        return back()->with('success', 'Access granted successfully to the selected doctor.');
    }

    /**
     * Revoke access from a doctor.
     */
    public function revoke(Request $request, MedicalReport $medicalReport, MedicalReportAccess $access)
    {
        Gate::authorize('patient-access');
        
        if ($medicalReport->patient_id !== auth()->id() || $access->medical_report_id !== $medicalReport->id) {
            abort(403);
        }

        // Cannot revoke access from the report author
        if ($access->access_type === 'author') {
            return back()->with('error', 'Cannot revoke access from the report author.');
        }

        $request->validate([
            'notes' => 'nullable|string|max:500',
        ]);

        $access->revoke($request->notes);

        return back()->with('success', 'Access revoked successfully.');
    }

    /**
     * Update access settings.
     */
    public function update(Request $request, MedicalReport $medicalReport, MedicalReportAccess $access)
    {
        Gate::authorize('patient-access');
        
        if ($medicalReport->patient_id !== auth()->id() || $access->medical_report_id !== $medicalReport->id) {
            abort(403);
        }

        $request->validate([
            'expires_at' => 'nullable|date|after:now',
            'notes' => 'nullable|string|max:500',
        ]);

        $access->update([
            'expires_at' => $request->expires_at,
            'notes' => $request->notes,
        ]);

        return back()->with('success', 'Access settings updated successfully.');
    }

    /**
     * Bulk manage access for multiple doctors.
     */
    public function bulkManage(Request $request, MedicalReport $medicalReport)
    {
        Gate::authorize('patient-access');
        
        if ($medicalReport->patient_id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'action' => 'required|in:grant,revoke',
            'doctor_ids' => 'required|array',
            'doctor_ids.*' => 'exists:doctors,id',
            'expires_at' => 'nullable|date|after:now',
            'notes' => 'nullable|string|max:500',
        ]);

        $action = $request->action;
        $doctorIds = $request->doctor_ids;
        $successCount = 0;

        foreach ($doctorIds as $doctorId) {
            if ($action === 'grant') {
                // Skip if doctor already has access
                if (!$medicalReport->doctorHasAccess($doctorId)) {
                    $medicalReport->grantAccessToDoctor(
                        $doctorId,
                        $request->notes,
                        $request->expires_at
                    );
                    $successCount++;
                }
            } elseif ($action === 'revoke') {
                // Skip if doctor is the author
                if ($doctorId != $medicalReport->doctor_id) {
                    $medicalReport->revokeAccessFromDoctor($doctorId, $request->notes);
                    $successCount++;
                }
            }
        }

        $actionText = $action === 'grant' ? 'granted' : 'revoked';
        return back()->with('success', "Access {$actionText} for {$successCount} doctor(s) successfully.");
    }
}
