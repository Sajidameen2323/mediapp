<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreLaboratoryRequest;
use App\Http\Requests\UpdateLaboratoryRequest;
use App\Models\Laboratory;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class LaboratoryController extends Controller
{
    use AuthorizesRequests;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('admin-access');
        
        $laboratories = Laboratory::with('user')
            ->paginate(10);

        return view('admin.laboratories.index', compact('laboratories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('admin-access');
        
        $workingDays = Laboratory::getDefaultWorkingDays();
        
        return view('admin.laboratories.create', compact('workingDays'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreLaboratoryRequest $request)
    {
        try {
            DB::beginTransaction();

            // Create user first
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'phone_number' => $request->phone_number,
                'user_type' => 'laboratory_staff',
                'is_active' => true,
            ]);
            
            $user->assignRole('laboratory_staff');

            // Prepare services_offered as array
            $servicesOffered = $request->services_offered ?? [];

            // Create laboratory profile
            Laboratory::create([
                'user_id' => $user->id,
                'name' => $request->lab_name,
                'description' => $request->description,
                'address' => $request->address,
                'city' => $request->city,
                'state' => $request->state,
                'postal_code' => $request->postal_code,
                'country' => $request->country,
                'website' => $request->website,
                'accreditation' => $request->accreditation,
                'emergency_contact' => $request->emergency_contact,
                'license_number' => $request->license_number,
                'services_offered' => $servicesOffered,
                'opening_time' => $request->opening_time,
                'closing_time' => $request->closing_time,
                'working_days' => $request->working_days,
                'consultation_fee' => $request->consultation_fee,
                'contact_person_name' => $request->contact_person_name,
                'contact_person_phone' => $request->contact_person_phone,
                'equipment_details' => $request->equipment_details,
                'home_service_available' => $request->boolean('home_service_available'),
                'home_service_fee' => $request->home_service_fee ?? 0,
                'is_available' => true,
            ]);

            DB::commit();

            return redirect()->route('admin.laboratories.index')
                ->with('success', 'Laboratory registered successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            
            return back()->withInput()
                ->with('error', 'Failed to register laboratory: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Laboratory $laboratory)
    {
        $this->authorize('admin-access');
        
        $laboratory->load('user');
        
        return view('admin.laboratories.show', compact('laboratory'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Laboratory $laboratory)
    {
        $this->authorize('admin-access');
        
        $laboratory->load('user');
        $workingDays = Laboratory::getDefaultWorkingDays();
        
        return view('admin.laboratories.edit', compact('laboratory', 'workingDays'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateLaboratoryRequest $request, Laboratory $laboratory)
    {
        try {
            DB::beginTransaction();

            // Update user data
            $userData = [
                'name' => $request->name,
                'email' => $request->email,
                'phone_number' => $request->phone_number,
            ];

            if ($request->filled('password')) {
                $userData['password'] = Hash::make($request->password);
            }

            $laboratory->user->update($userData);

            // Prepare services_offered as array
            $servicesOffered = $request->services_offered ?? [];

            // Update laboratory data
            $laboratory->update([
                'name' => $request->lab_name,
                'description' => $request->description,
                'address' => $request->address,
                'city' => $request->city,
                'state' => $request->state,
                'postal_code' => $request->postal_code,
                'country' => $request->country,
                'website' => $request->website,
                'accreditation' => $request->accreditation,
                'emergency_contact' => $request->emergency_contact,
                'license_number' => $request->license_number,
                'services_offered' => $servicesOffered,
                'opening_time' => $request->opening_time,
                'closing_time' => $request->closing_time,
                'working_days' => $request->working_days,
                'consultation_fee' => $request->consultation_fee,
                'contact_person_name' => $request->contact_person_name,
                'contact_person_phone' => $request->contact_person_phone,
                'equipment_details' => $request->equipment_details,
                'home_service_available' => $request->boolean('home_service_available'),
                'home_service_fee' => $request->home_service_fee ?? 0,
            ]);

            DB::commit();

            return redirect()->route('admin.laboratories.index')
                ->with('success', 'Laboratory updated successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            
            return back()->withInput()
                ->with('error', 'Failed to update laboratory: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Laboratory $laboratory)
    {
        $this->authorize('admin-access');
        
        try {
            DB::beginTransaction();

            $user = $laboratory->user;
            $laboratory->delete();
            $user->delete();

            DB::commit();

            return redirect()->route('admin.laboratories.index')
                ->with('success', 'Laboratory deleted successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            
            return back()->with('error', 'Failed to delete laboratory: ' . $e->getMessage());
        }
    }

    /**
     * Toggle the availability status of the specified laboratory.
     */
    public function toggleAvailability(Request $request, Laboratory $laboratory)
    {
        $this->authorize('admin-access');
        
        try {
            $laboratory->update([
                'is_available' => !$laboratory->is_available
            ]);
            
            return response()->json([
                'success' => true,
                'is_available' => $laboratory->is_available,
                'message' => 'Laboratory availability status updated successfully.'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update availability status: ' . $e->getMessage()
            ], 500);
        }
    }
}
