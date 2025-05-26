<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pharmacy;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class PharmacyController extends Controller
{
    use AuthorizesRequests;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('admin-access');
        
        $pharmacies = Pharmacy::with('user')
            ->paginate(10);

        return view('admin.pharmacies.index', compact('pharmacies'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('admin-access');
        
        $workingDays = Pharmacy::getDefaultWorkingDays();
        
        return view('admin.pharmacies.create', compact('workingDays'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('admin-access');
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|confirmed|min:8',
            'phone_number' => 'nullable|string|max:20',
            'address' => 'required|string|max:500',
            
            // Pharmacy Information
            'pharmacy_name' => 'required|string|max:255',
            'pharmacist_in_charge' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'license_number' => 'required|string|unique:pharmacies,license_number',
            'services_offered' => 'nullable|string',
            
            // Location Information
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'postal_code' => 'required|string|max:20',
            'country' => 'required|string|max:100',
            
            // Working Hours
            'opening_time' => 'required|date_format:H:i',
            'closing_time' => 'required|date_format:H:i|after:opening_time',
            'working_days' => 'required|array|min:1',
            'working_days.*' => 'string|in:monday,tuesday,wednesday,thursday,friday,saturday,sunday',
            
            // Services & Features
            'specializations' => 'nullable|array',
            'specializations.*' => 'string',
            'accepts_insurance' => 'boolean',
            
            // Contact Information
            'contact_person_name' => 'nullable|string|max:255',
            'contact_person_phone' => 'nullable|string|max:20',
            'emergency_contact' => 'nullable|string|max:20',
            'website' => 'nullable|url|max:255',
            
            // Delivery & Prescription
            'home_delivery_available' => 'boolean',
            'home_delivery_fee' => 'required_if:home_delivery_available,true|nullable|numeric|min:0',
            'minimum_order_amount' => 'nullable|numeric|min:0',
            'prescription_required' => 'boolean',
        ]);

        try {
            DB::beginTransaction();

            // Create user first
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'phone_number' => $request->phone_number,
                'address' => $request->address,
                'user_type' => 'pharmacist',
                'is_active' => true,
            ]);
            
            $user->assignRole('pharmacist');

            // Create pharmacy profile
            Pharmacy::create([
                'user_id' => $user->id,
                'pharmacy_name' => $request->pharmacy_name,
                'pharmacist_in_charge' => $request->pharmacist_in_charge,
                'description' => $request->description,
                'license_number' => $request->license_number,
                'services_offered' => $request->services_offered,
                
                // Location Information
                'city' => $request->city,
                'state' => $request->state,
                'postal_code' => $request->postal_code,
                'country' => $request->country,
                
                // Working Hours
                'opening_time' => $request->opening_time,
                'closing_time' => $request->closing_time,
                'working_days' => $request->working_days,
                
                // Services & Features
                'specializations' => $request->specializations ? json_encode($request->specializations) : null,
                'accepts_insurance' => $request->boolean('accepts_insurance'),
                
                // Contact Information
                'contact_person_name' => $request->contact_person_name,
                'contact_person_phone' => $request->contact_person_phone,
                'emergency_contact' => $request->emergency_contact,
                'website' => $request->website,
                
                // Delivery & Prescription
                'home_delivery_available' => $request->boolean('home_delivery_available'),
                'home_delivery_fee' => $request->home_delivery_fee ?? 0,
                'minimum_order_amount' => $request->minimum_order_amount ?? 0,
                'prescription_required' => $request->boolean('prescription_required'),
                'is_available' => true,
            ]);

            DB::commit();

            return redirect()->route('admin.pharmacies.index')
                ->with('success', 'Pharmacy registered successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            
            return back()->withInput()
                ->with('error', 'Failed to register pharmacy: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Pharmacy $pharmacy)
    {
        $this->authorize('admin-access');
        
        $pharmacy->load('user');
        
        return view('admin.pharmacies.show', compact('pharmacy'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Pharmacy $pharmacy)
    {
        $this->authorize('admin-access');
        
        $pharmacy->load('user');
        $workingDays = Pharmacy::getDefaultWorkingDays();
        
        return view('admin.pharmacies.edit', compact('pharmacy', 'workingDays'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Pharmacy $pharmacy)
    {
        $this->authorize('admin-access');
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $pharmacy->user_id,
            'password' => 'nullable|confirmed|min:8',
            'phone_number' => 'nullable|string|max:20',
            'address' => 'required|string|max:500',
            
            // Pharmacy Information
            'pharmacy_name' => 'required|string|max:255',
            'pharmacist_in_charge' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'license_number' => 'required|string|unique:pharmacies,license_number,' . $pharmacy->id,
            'services_offered' => 'nullable|string',
            
            // Location Information
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'postal_code' => 'required|string|max:20',
            'country' => 'required|string|max:100',
            
            // Working Hours
            'opening_time' => 'required|date_format:H:i',
            'closing_time' => 'required|date_format:H:i|after:opening_time',
            'working_days' => 'required|array|min:1',
            'working_days.*' => 'string|in:monday,tuesday,wednesday,thursday,friday,saturday,sunday',
            
            // Services & Features
            'specializations' => 'nullable|array',
            'specializations.*' => 'string',
            'accepts_insurance' => 'boolean',
            
            // Contact Information
            'contact_person_name' => 'nullable|string|max:255',
            'contact_person_phone' => 'nullable|string|max:20',
            'emergency_contact' => 'nullable|string|max:20',
            'website' => 'nullable|url|max:255',
            
            // Delivery & Prescription
            'home_delivery_available' => 'boolean',
            'home_delivery_fee' => 'required_if:home_delivery_available,true|nullable|numeric|min:0',
            'minimum_order_amount' => 'nullable|numeric|min:0',
            'prescription_required' => 'boolean',
        ]);

        try {
            DB::beginTransaction();

            // Update user data
            $userData = [
                'name' => $request->name,
                'email' => $request->email,
                'phone_number' => $request->phone_number,
                'address' => $request->address,
            ];

            if ($request->filled('password')) {
                $userData['password'] = Hash::make($request->password);
            }

            $pharmacy->user->update($userData);

            // Update pharmacy data
            $pharmacy->update([
                'pharmacy_name' => $request->pharmacy_name,
                'pharmacist_in_charge' => $request->pharmacist_in_charge,
                'description' => $request->description,
                'license_number' => $request->license_number,
                'services_offered' => $request->services_offered,
                
                // Location Information
                'city' => $request->city,
                'state' => $request->state,
                'postal_code' => $request->postal_code,
                'country' => $request->country,
                
                // Working Hours
                'opening_time' => $request->opening_time,
                'closing_time' => $request->closing_time,
                'working_days' => $request->working_days,
                
                // Services & Features
                'specializations' => $request->specializations ? json_encode($request->specializations) : null,
                'accepts_insurance' => $request->boolean('accepts_insurance'),
                
                // Contact Information
                'contact_person_name' => $request->contact_person_name,
                'contact_person_phone' => $request->contact_person_phone,
                'emergency_contact' => $request->emergency_contact,
                'website' => $request->website,
                
                // Delivery & Prescription
                'home_delivery_available' => $request->boolean('home_delivery_available'),
                'home_delivery_fee' => $request->home_delivery_fee ?? 0,
                'minimum_order_amount' => $request->minimum_order_amount ?? 0,
                'prescription_required' => $request->boolean('prescription_required'),
            ]);

            DB::commit();

            return redirect()->route('admin.pharmacies.index')
                ->with('success', 'Pharmacy updated successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            
            return back()->withInput()
                ->with('error', 'Failed to update pharmacy: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Pharmacy $pharmacy)
    {
        $this->authorize('admin-access');
        
        try {
            DB::beginTransaction();

            $user = $pharmacy->user;
            $pharmacy->delete();
            $user->delete();

            DB::commit();

            return redirect()->route('admin.pharmacies.index')
                ->with('success', 'Pharmacy deleted successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            
            return back()->with('error', 'Failed to delete pharmacy: ' . $e->getMessage());
        }
    }

    /**
     * Toggle the availability status of the specified pharmacy.
     */
    public function toggleAvailability(Request $request, Pharmacy $pharmacy)
    {
        $this->authorize('admin-access');
        
        try {
            $pharmacy->update([
                'is_available' => !$pharmacy->is_available
            ]);
            
            return response()->json([
                'success' => true,
                'is_available' => $pharmacy->is_available,
                'message' => 'Pharmacy availability status updated successfully.'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update availability status: ' . $e->getMessage()
            ], 500);
        }
    }
}
