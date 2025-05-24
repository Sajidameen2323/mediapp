<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ServiceRequest;
use App\Models\Service;
use App\Models\Doctor;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ServiceController extends Controller
{
    use AuthorizesRequests;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('admin-access');
        
        $services = Service::with('doctors')->paginate(10);
        
        return view('admin.services.index', compact('services'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('admin-access');
        
        return view('admin.services.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ServiceRequest $request)
    {
        $this->authorize('admin-access');
        
        $service = Service::create([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'duration_minutes' => $request->duration_minutes,
            'category' => $request->category,
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()->route('admin.services.index')
            ->with('success', 'Service created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Service $service)
    {
        $this->authorize('admin-access');
        
        $service->load('doctors.user');
        
        return view('admin.services.show', compact('service'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Service $service)
    {
        $this->authorize('admin-access');
        
        return view('admin.services.edit', compact('service'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ServiceRequest $request, Service $service)
    {
        $this->authorize('admin-access');
        
        $service->update([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'duration_minutes' => $request->duration_minutes,
            'category' => $request->category,
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()->route('admin.services.index')
            ->with('success', 'Service updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Service $service)
    {
        $this->authorize('admin-access');
        
        $service->delete();

        return redirect()->route('admin.services.index')
            ->with('success', 'Service deleted successfully.');
    }

    /**
     * Show the form for assigning doctors to service.
     */
    public function assignDoctors(Service $service)
    {
        $this->authorize('admin-access');
        
        $doctors = Doctor::with('user')->available()->get();
        $assignedDoctors = $service->doctors->pluck('id')->toArray();
        
        return view('admin.services.assign-doctors', compact('service', 'doctors', 'assignedDoctors'));
    }

    /**
     * Update doctor assignments for service.
     */
    public function updateDoctorAssignments(Request $request, Service $service)
    {
        $this->authorize('admin-access');
        
        $request->validate([
            'doctors' => 'array',
            'doctors.*' => 'exists:doctors,id',
        ]);

        $service->doctors()->sync($request->doctors ?? []);

        return redirect()->route('admin.services.index')
            ->with('success', 'Doctor assignments updated successfully.');
    }
}
