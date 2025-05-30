<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class DoctorController extends Controller
{
    /**
     * Get available doctors for the welcome page
     */
    public function getAvailableDoctors(Request $request): JsonResponse
    {
        $query = Doctor::with(['user', 'services'])
            ->available()
            ->orderBy('created_at', 'desc');

        // Filter by specialization if provided
        if ($request->filled('specialty') && $request->specialty !== 'All Specialties') {
            $query->bySpecialization($request->specialty);
        }

        // Filter by location (search in user's address)
        if ($request->filled('location')) {
            $query->whereHas('user', function ($userQuery) use ($request) {
                $userQuery->where('address', 'like', '%' . $request->location . '%');
            });
        }

        $doctors = $query->limit(6)->get();

        return response()->json([
            'success' => true,
            'doctors' => $doctors->map(function ($doctor) {
                return [
                    'id' => $doctor->id,
                    'name' => $doctor->user->name,
                    'specialization' => $doctor->specialization,
                    'experience_years' => $doctor->experience_years,
                    'consultation_fee' => $doctor->consultation_fee,
                    'profile_image' => $doctor->profile_image,
                    'is_available' => $doctor->is_available,
                    'bio' => $doctor->bio,
                    'services_count' => $doctor->services->count(),
                    'initials' => $this->getInitials($doctor->user->name)
                ];
            })
        ]);
    }

    /**
     * Search doctors based on criteria
     */
    public function searchDoctors(Request $request): JsonResponse
    {
        $query = Doctor::with(['user', 'services'])
            ->available();

        // Filter by specialization
        if ($request->filled('specialty') && $request->specialty !== 'All Specialties') {
            $query->bySpecialization($request->specialty);
        }

        // Filter by location
        if ($request->filled('location')) {
            $query->whereHas('user', function ($userQuery) use ($request) {
                $userQuery->where('address', 'like', '%' . $request->location . '%');
            });
        }

        // Search by doctor name
        if ($request->filled('search')) {
            $query->whereHas('user', function ($userQuery) use ($request) {
                $userQuery->where('name', 'like', '%' . $request->search . '%');
            });
        }

        $doctors = $query->orderBy('created_at', 'desc')->get();

        return response()->json([
            'success' => true,
            'doctors' => $doctors->map(function ($doctor) {
                return [
                    'id' => $doctor->id,
                    'name' => $doctor->user->name,
                    'specialization' => $doctor->specialization,
                    'experience_years' => $doctor->experience_years,
                    'consultation_fee' => $doctor->consultation_fee,
                    'profile_image' => $doctor->profile_image,
                    'is_available' => $doctor->is_available,
                    'bio' => $doctor->bio,
                    'services_count' => $doctor->services->count(),
                    'initials' => $this->getInitials($doctor->user->name),
                    'address' => $doctor->user->address,
                    'phone' => $doctor->user->phone_number,
                    'email' => $doctor->user->email
                ];
            }),
            'total' => $doctors->count()
        ]);
    }

    /**
     * Get unique specializations for filter dropdown
     */
    public function getSpecializations(): JsonResponse
    {
        $specializations = Doctor::available()
            ->distinct()
            ->pluck('specialization')
            ->filter()
            ->sort()
            ->values();

        return response()->json([
            'success' => true,
            'specializations' => $specializations
        ]);    }

    /**
     * Get a specific doctor for API
     */
    public function show(Doctor $doctor): JsonResponse
    {
        $doctor->load(['user', 'services']);

        return response()->json([
            'success' => true,
            'doctor' => [
                'id' => $doctor->id,
                'name' => $doctor->user->name,
                'user' => [
                    'name' => $doctor->user->name,
                    'email' => $doctor->user->email
                ],
                'specialization' => $doctor->specialization,
                'experience_years' => $doctor->experience_years,
                'consultation_fee' => $doctor->consultation_fee,
                'profile_image' => $doctor->profile_image,
                'is_available' => $doctor->is_available,
                'bio' => $doctor->bio,
                'services' => $doctor->services,
                'initials' => $this->getInitials($doctor->user->name)
            ]
        ]);
    }

    /**
     * Get doctor initials from name
     */
    private function getInitials(string $name): string
    {
        $words = explode(' ', trim($name));
        $initials = '';
        
        foreach ($words as $word) {
            if (!empty($word)) {
                $initials .= strtoupper(substr($word, 0, 1));
            }
        }
        
        return substr($initials, 0, 2); // Limit to 2 characters
    }
}
