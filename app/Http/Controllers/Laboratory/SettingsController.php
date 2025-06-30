<?php

namespace App\Http\Controllers\Laboratory;

use App\Http\Controllers\Controller;
use App\Models\Laboratory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class SettingsController extends Controller
{
    /**
     * Display the laboratory settings.
     */
    public function index()
    {
        Gate::authorize('laboratory-staff-access');

        $user = auth()->user();
        $laboratory = $user->laboratory()->first();

        if (!$laboratory) {
            return redirect()->route('dashboard')
                ->with('error', 'Laboratory profile not found.');
        }

        return view('dashboard.laboratory.settings.index', compact('laboratory'));
    }

    /**
     * Update the laboratory settings.
     */
    public function update(Request $request)
    {
        Gate::authorize('laboratory-staff-access');

        $user = auth()->user();
        $laboratory = $user->laboratory()->first();

        if (!$laboratory) {
            return redirect()->route('dashboard')
                ->with('error', 'Laboratory profile not found.');
        }

        $request->validate([
            'opening_time' => 'required|date_format:H:i',
            'closing_time' => 'required|date_format:H:i|after:opening_time',
            'working_days' => 'required|array|min:1',
            'working_days.*' => 'in:monday,tuesday,wednesday,thursday,friday,saturday,sunday',
            'consultation_fee' => 'nullable|numeric|min:0',
            'services_offered' => 'nullable|array',
            'services_offered.*' => 'string|max:100',
            'equipment_details' => 'nullable|string|max:1000',
            'home_service_available' => 'boolean',
            'home_service_fee' => 'nullable|numeric|min:0',
            'contact_person_name' => 'nullable|string|max:255',
            'contact_person_phone' => 'nullable|string|max:20',
        ]);

        try {
            $laboratory->update([
                'opening_time' => $request->opening_time,
                'closing_time' => $request->closing_time,
                'working_days' => $request->working_days,
                'consultation_fee' => $request->consultation_fee ?? 0,
                'services_offered' => $request->services_offered ? implode(',', $request->services_offered) : null,
                'equipment_details' => $request->equipment_details,
                'home_service_available' => $request->boolean('home_service_available'),
                'home_service_fee' => $request->home_service_available ? ($request->home_service_fee ?? 0) : 0,
                'contact_person_name' => $request->contact_person_name,
                'contact_person_phone' => $request->contact_person_phone,
            ]);

            return back()->with('success', 'Laboratory settings updated successfully.');

        } catch (\Exception $e) {
            return back()->with('error', 'Failed to update settings: ' . $e->getMessage());
        }
    }

    /**
     * Toggle laboratory availability.
     */
    public function toggleAvailability(Request $request)
    {
        Gate::authorize('laboratory-staff-access');

        $user = auth()->user();
        $laboratory = $user->laboratory()->first();

        if (!$laboratory) {
            return response()->json([
                'success' => false,
                'message' => 'Laboratory profile not found.'
            ], 404);
        }

        try {
            $laboratory->update([
                'is_available' => !$laboratory->is_available
            ]);

            return response()->json([
                'success' => true,
                'is_available' => $laboratory->is_available,
                'message' => $laboratory->is_available 
                    ? 'Laboratory is now available for appointments.' 
                    : 'Laboratory is now unavailable for appointments.'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update availability: ' . $e->getMessage()
            ], 500);
        }
    }
}
