<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\LabTestRequest;
use App\Models\LabTestAccess;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LabTestAccessController extends Controller
{
    /**
     * Display access management for a lab test.
     */
    public function index(LabTestRequest $labTest)
    {
        // Ensure the lab test belongs to the authenticated patient
        $this->authorize('view', $labTest);

        $labTest->load([
            'accessRecords' => function ($query) {
                $query->with('doctor.user')->orderBy('created_at', 'desc');
            },
            'doctor.user'
        ]);

        // Get all doctors for granting new access
        $availableDoctors = Doctor::with('user')
            ->whereHas('user', function ($query) {
                $query->where('is_active', true);
            })
            ->whereNotIn('id', function ($query) use ($labTest) {
                $query->select('doctor_id')
                    ->from('lab_test_access')
                    ->where('lab_test_request_id', $labTest->id)
                    ->where('status', 'active');
            })
            ->get();

        return view('dashboard.patient.lab-tests.access.index', compact('labTest', 'availableDoctors'));
    }

    /**
     * Grant access to a doctor.
     */
    public function grant(Request $request, LabTestRequest $labTest)
    {
        $this->authorize('update', $labTest);

        $request->validate([
            'doctor_id' => 'required|exists:doctors,id',
            'notes' => 'nullable|string|max:500',
            'expires_at' => 'nullable|date|after:today',
        ]);

        // Check if doctor already has access
        $existingAccess = $labTest->accessRecords()
            ->where('doctor_id', $request->doctor_id)
            ->where('status', 'active')
            ->first();

        if ($existingAccess) {
            return back()->with('error', 'Doctor already has access to this lab test.');
        }

        // Grant access
        $labTest->grantAccessToDoctor(
            $request->doctor_id,
            $request->notes,
            $request->expires_at
        );

        $doctor = Doctor::with('user')->find($request->doctor_id);
        
        return back()->with('success', "Access granted to Dr. {$doctor->user->name} successfully.");
    }

    /**
     * Update access record.
     */
    public function update(Request $request, LabTestRequest $labTest, LabTestAccess $access)
    {
        $this->authorize('update', $labTest);

        // Ensure the access record belongs to this lab test
        if ($access->lab_test_request_id !== $labTest->id) {
            abort(404);
        }

        // Cannot modify author access
        if ($access->access_type === 'author') {
            return back()->with('error', 'Cannot modify access for the ordering doctor.');
        }

        $request->validate([
            'notes' => 'nullable|string|max:500',
            'expires_at' => 'nullable|date|after:today',
        ]);

        $access->update([
            'notes' => $request->notes,
            'expires_at' => $request->expires_at,
        ]);

        return back()->with('success', 'Access updated successfully.');
    }

    /**
     * Revoke access from a doctor.
     */
    public function revoke(Request $request, LabTestRequest $labTest, LabTestAccess $access)
    {
        $this->authorize('update', $labTest);

        // Ensure the access record belongs to this lab test
        if ($access->lab_test_request_id !== $labTest->id) {
            abort(404);
        }

        // Cannot revoke author access
        if ($access->access_type === 'author') {
            return back()->with('error', 'Cannot revoke access for the ordering doctor.');
        }

        $request->validate([
            'revocation_reason' => 'nullable|string|max:500',
        ]);

        $access->revoke($request->revocation_reason);

        $doctorName = $access->doctor->user->name;
        
        return back()->with('success', "Access revoked from Dr. {$doctorName} successfully.");
    }

    /**
     * Bulk manage access records.
     */
    public function bulkManage(Request $request, LabTestRequest $labTest)
    {
        $this->authorize('update', $labTest);

        $request->validate([
            'action' => 'required|in:revoke,extend',
            'access_ids' => 'required|array',
            'access_ids.*' => 'exists:lab_test_access,id',
            'notes' => 'nullable|string|max:500',
            'expires_at' => 'nullable|date|after:today',
        ]);

        $accessRecords = LabTestAccess::whereIn('id', $request->access_ids)
            ->where('lab_test_request_id', $labTest->id)
            ->where('access_type', '!=', 'author') // Cannot modify author access
            ->get();

        $count = 0;
        
        foreach ($accessRecords as $access) {
            if ($request->action === 'revoke') {
                $access->revoke($request->notes);
                $count++;
            } elseif ($request->action === 'extend' && $request->expires_at) {
                $access->update([
                    'expires_at' => $request->expires_at,
                    'notes' => $request->notes,
                ]);
                $count++;
            }
        }

        $message = $request->action === 'revoke' 
            ? "Access revoked for {$count} doctor(s) successfully."
            : "Access extended for {$count} doctor(s) successfully.";

        return back()->with('success', $message);
    }
}
