<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\DoctorRequest;
use App\Models\Doctor;
use App\Models\User;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class DoctorController extends Controller
{
    use AuthorizesRequests;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('admin-access');
        
        $doctors = Doctor::with(['user', 'schedules', 'services'])
            ->paginate(10);

        return view('admin.doctors.index', compact('doctors'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('admin-access');
        
        $services = Service::active()->get();
        
        return view('admin.doctors.create', compact('services'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(DoctorRequest $request)
    {
        $this->authorize('admin-access');
        try {
            DB::beginTransaction();

            // Create user first
            $userData = [
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'phone_number' => $request->phone_number,
                'gender' => $request->gender,
                'address' => $request->address,
                'date_of_birth' => $request->date_of_birth,
                'user_type' => 'doctor',
                'is_active' => true,
            ];

            $user = User::create($userData);
            $user->assignRole('doctor');

            // Handle profile image upload
            $profileImage = null;
            if ($request->hasFile('profile_image')) {
                $profileImage = $request->file('profile_image')->store('doctors', 'public');
            }

            // Create doctor profile
            $doctorData = [
                'user_id' => $user->id,
                'specialization' => $request->specialization,
                'qualifications' => $request->qualifications,
                'experience_years' => $request->experience_years,
                'license_number' => $request->license_number,
                'consultation_fee' => $request->consultation_fee,
                'bio' => $request->bio,
                'profile_image' => $profileImage,
                'is_available' => $request->boolean('is_available', true),
            ];

            $doctor = Doctor::create($doctorData);

            // Create schedules
            if ($request->has('schedules')) {
                foreach ($request->schedules as $schedule) {
                    if (isset($schedule['is_available']) && $schedule['is_available']) {
                        $doctor->schedules()->create([
                            'day_of_week' => $schedule['day_of_week'],
                            'start_time' => $schedule['start_time'],
                            'end_time' => $schedule['end_time'],
                            'is_available' => true,
                        ]);
                    }
                }
            }

            // Assign services
            if ($request->has('services')) {
                $doctor->services()->attach($request->services);
            }

            DB::commit();

            return redirect()->route('admin.doctors.index')
                ->with('success', 'Doctor created successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            
            return back()->withInput()
                ->with('error', 'Failed to create doctor: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Doctor $doctor)
    {
        $this->authorize('admin-access');
        
        $doctor->load(['user', 'schedules', 'services']);
        
        return view('admin.doctors.show', compact('doctor'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Doctor $doctor)
    {
        $this->authorize('admin-access');
        
        $doctor->load(['user', 'schedules', 'services']);
        $services = Service::active()->get();
        
        return view('admin.doctors.edit', compact('doctor', 'services'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(DoctorRequest $request, Doctor $doctor)
    {
        $this->authorize('admin-access');
        
        try {
            DB::beginTransaction();

            // Update user data
            $userData = [
                'name' => $request->name,
                'email' => $request->email,
                'phone_number' => $request->phone_number,
                'gender' => $request->gender,
                'address' => $request->address,
                'date_of_birth' => $request->date_of_birth,
            ];

            if ($request->filled('password')) {
                $userData['password'] = Hash::make($request->password);
            }

            $doctor->user->update($userData);

            // Handle profile image upload
            if ($request->hasFile('profile_image')) {
                // Delete old image
                if ($doctor->profile_image) {
                    Storage::disk('public')->delete($doctor->profile_image);
                }
                $profileImage = $request->file('profile_image')->store('doctors', 'public');
            } else {
                $profileImage = $doctor->profile_image;
            }

            // Update doctor data
            $doctorData = [
                'specialization' => $request->specialization,
                'qualifications' => $request->qualifications,
                'experience_years' => $request->experience_years,
                'license_number' => $request->license_number,
                'consultation_fee' => $request->consultation_fee,
                'bio' => $request->bio,
                'profile_image' => $profileImage,
                'is_available' => $request->boolean('is_available', true),
            ];

            $doctor->update($doctorData);

            // Update schedules
            $doctor->schedules()->delete();
            if ($request->has('schedules')) {
                foreach ($request->schedules as $schedule) {
                    if (isset($schedule['is_available']) && $schedule['is_available']) {
                        $doctor->schedules()->create([
                            'day_of_week' => $schedule['day_of_week'],
                            'start_time' => $schedule['start_time'],
                            'end_time' => $schedule['end_time'],
                            'is_available' => true,
                        ]);
                    }
                }
            }

            // Update services
            $doctor->services()->sync($request->services ?? []);

            DB::commit();

            return redirect()->route('admin.doctors.index')
                ->with('success', 'Doctor updated successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            
            return back()->withInput()
                ->with('error', 'Failed to update doctor: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Doctor $doctor)
    {
        $this->authorize('admin-access');
        
        try {
            DB::beginTransaction();

            // Delete profile image
            if ($doctor->profile_image) {
                Storage::disk('public')->delete($doctor->profile_image);
            }

            // Delete doctor and associated user
            $user = $doctor->user;
            $doctor->delete();
            $user->delete();

            DB::commit();

            return redirect()->route('admin.doctors.index')
                ->with('success', 'Doctor deleted successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            
            return back()->with('error', 'Failed to delete doctor: ' . $e->getMessage());
        }
    }
}
