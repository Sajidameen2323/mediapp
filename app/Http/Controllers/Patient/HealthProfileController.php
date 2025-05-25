<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Http\Requests\HealthProfileRequest;
use App\Models\HealthProfile;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class HealthProfileController extends Controller
{
    use AuthorizesRequests;

    /**
     * Display the health profile.
     */
    public function index(): View
    {
        $this->authorize('patient-access');
        
        $user = auth()->user();
        $healthProfile = $user->healthProfile;
        
        return view('patient.health-profile.index', compact('healthProfile'));
    }

    /**
     * Show the form for creating a new health profile.
     */
    public function create(): View
    {
        $this->authorize('patient-access');
        
        // If profile already exists, redirect to edit
        if (auth()->user()->healthProfile) {
            return redirect()->route('patient.health-profile.edit');
        }
        
        return view('patient.health-profile.create');
    }

    /**
     * Store a newly created health profile.
     */
    public function store(HealthProfileRequest $request): RedirectResponse
    {
        $this->authorize('patient-access');
        
        $user = auth()->user();
        
        // Check if profile already exists
        if ($user->healthProfile) {
            return redirect()->route('patient.health-profile.edit')
                ->with('warning', 'You already have a health profile. You can update it here.');
        }
        
        $healthProfile = HealthProfile::create([
            'user_id' => $user->id,
            ...$request->validated()
        ]);
        
        return redirect()->route('patient.health-profile.index')
            ->with('success', 'Health profile created successfully!');
    }

    /**
     * Display the specified health profile.
     */
    public function show(): View
    {
        return $this->index();
    }

    /**
     * Show the form for editing the health profile.
     */
    public function edit(): View
    {
        $this->authorize('patient-access');
        
        $user = auth()->user();
        $healthProfile = $user->healthProfile;
        
        if (!$healthProfile) {
            return redirect()->route('patient.health-profile.create')
                ->with('info', 'Please create your health profile first.');
        }
        
        return view('patient.health-profile.edit', compact('healthProfile'));
    }

    /**
     * Update the health profile.
     */
    public function update(HealthProfileRequest $request): RedirectResponse
    {
        $this->authorize('patient-access');
        
        $user = auth()->user();
        $healthProfile = $user->healthProfile;
        
        if (!$healthProfile) {
            return redirect()->route('patient.health-profile.create')
                ->with('info', 'Please create your health profile first.');
        }
        
        $healthProfile->update($request->validated());
        
        return redirect()->route('patient.health-profile.index')
            ->with('success', 'Health profile updated successfully!');
    }

    /**
     * Remove the health profile.
     */
    public function destroy(): RedirectResponse
    {
        $this->authorize('patient-access');
        
        $user = auth()->user();
        $healthProfile = $user->healthProfile;
        
        if ($healthProfile) {
            $healthProfile->delete();
            return redirect()->route('patient.health-profile.index')
                ->with('success', 'Health profile deleted successfully!');
        }
        
        return redirect()->route('patient.health-profile.index')
            ->with('warning', 'No health profile found to delete.');
    }
}
