<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\HealthProfilePermission;
use App\Models\User;
use App\Models\Doctor;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class HealthProfilePermissionController extends Controller
{
    use AuthorizesRequests;

    /**
     * Display health profile permissions management page
     */
    public function index(): View
    {
        $this->authorize('patient-access');
        
        $user = auth()->user();
        
        // Get all permissions for this patient
        $permissions = HealthProfilePermission::where('patient_id', $user->id)
            ->with(['doctor', 'doctorProfile'])
            ->orderBy('updated_at', 'desc')
            ->get();

        // Get doctors who have appointments with this patient but no permission yet
        $doctorsWithoutPermission = Doctor::whereHas('appointments', function ($query) use ($user) {
            $query->where('patient_id', $user->id);
        })
        ->whereDoesntHave('healthProfilePermissionsReceived', function ($query) use ($user) {
            $query->where('patient_id', $user->id);
        })
        ->with('user')
        ->get();

        return view('patient.health-profile.permissions.index', compact('permissions', 'doctorsWithoutPermission'));
    }

    /**
     * Grant permission to a doctor
     */
    public function grant(Request $request): RedirectResponse
    {
        $this->authorize('patient-access');
        
        $request->validate([
            'doctor_id' => 'required|exists:users,id',
            'notes' => 'nullable|string|max:500'
        ]);

        $user = auth()->user();
        
        // Verify the doctor_id belongs to a doctor user
        $doctor = User::where('id', $request->doctor_id)
            ->where('user_type', 'doctor')
            ->first();
            
        if (!$doctor) {
            return back()->with('error', 'Invalid doctor selected.');
        }

        // Create or update permission
        $permission = HealthProfilePermission::updateOrCreate(
            [
                'patient_id' => $user->id,
                'doctor_id' => $request->doctor_id
            ],
            [
                'is_granted' => true,
                'granted_at' => now(),
                'revoked_at' => null,
                'notes' => $request->notes
            ]
        );

        return back()->with('success', 'Health profile access granted to Dr. ' . $doctor->name . '.');
    }

    /**
     * Revoke permission from a doctor
     */
    public function revoke(Request $request, HealthProfilePermission $permission): RedirectResponse
    {
        $this->authorize('patient-access');
        
        // Ensure this permission belongs to the authenticated patient
        if ($permission->patient_id !== auth()->id()) {
            abort(403, 'Unauthorized access to permission.');
        }

        $request->validate([
            'notes' => 'nullable|string|max:500'
        ]);

        $permission->revoke($request->notes);

        return back()->with('success', 'Health profile access revoked from Dr. ' . $permission->doctor->name . '.');
    }

    /**
     * Show permission details
     */
    public function show(HealthProfilePermission $permission): View
    {
        $this->authorize('patient-access');
        
        // Ensure this permission belongs to the authenticated patient
        if ($permission->patient_id !== auth()->id()) {
            abort(403, 'Unauthorized access to permission.');
        }

        $permission->load(['doctor', 'doctorProfile']);

        return view('patient.health-profile.permissions.show', compact('permission'));
    }
}
