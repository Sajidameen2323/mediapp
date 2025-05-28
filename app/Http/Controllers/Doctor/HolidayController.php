<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Http\Requests\Doctor\StoreDoctorHolidayRequest;
use App\Http\Requests\Doctor\UpdateDoctorHolidayRequest;
use App\Models\DoctorHoliday;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class HolidayController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        Gate::authorize('doctor-access');
        
        $doctor = auth()->user()->doctor;
        $query = $doctor->holidays()->orderBy('start_date', 'desc');
        
        // Filter by status if provided
        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }
        
        // Filter by date range if provided
        if ($request->has('from_date') && $request->from_date) {
            $query->where('start_date', '>=', $request->from_date);
        }
        
        if ($request->has('to_date') && $request->to_date) {
            $query->where('end_date', '<=', $request->to_date);
        }
          $holidays = $query->get();
        
        return view('dashboard.doctor.holidays.index', compact('holidays'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        Gate::authorize('doctor-access');
        
        return view('dashboard.doctor.holidays.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDoctorHolidayRequest $request)
    {
        Gate::authorize('doctor-access');
        
        $doctor = auth()->user()->doctor;
        
        $data = $request->validated();
        $data['doctor_id'] = $doctor->id;
        $data['status'] = 'pending'; // Always start as pending
        
        $holiday = DoctorHoliday::create($data);
        
        return response()->json([
            'success' => true,
            'message' => 'Holiday request submitted successfully and is pending approval.',
            'holiday' => [
                'id' => $holiday->id,
                'start_date' => $holiday->start_date,
                'end_date' => $holiday->end_date,
                'reason' => $holiday->reason,
                'notes' => $holiday->notes,
                'status' => $holiday->status,
                'formatted_dates' => $holiday->start_date->format('M d, Y') . ' - ' . $holiday->end_date->format('M d, Y'),
                'duration_days' => $holiday->start_date->diffInDays($holiday->end_date) + 1,
                'status_badge_class' => $this->getStatusBadgeClass($holiday->status),
            ]
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(DoctorHoliday $holiday)
    {
        Gate::authorize('doctor-access');
        
        // Ensure the holiday belongs to the authenticated doctor
        if ($holiday->doctor_id !== auth()->user()->doctor->id) {
            abort(403);
        }
        
        return response()->json([
            'holiday' => [
                'id' => $holiday->id,
                'start_date' => $holiday->start_date,
                'end_date' => $holiday->end_date,
                'reason' => $holiday->reason,
                'notes' => $holiday->notes,
                'status' => $holiday->status,
                'admin_notes' => $holiday->admin_notes,
                'formatted_dates' => $holiday->start_date->format('M d, Y') . ' - ' . $holiday->end_date->format('M d, Y'),
                'duration_days' => $holiday->start_date->diffInDays($holiday->end_date) + 1,
                'status_badge_class' => $this->getStatusBadgeClass($holiday->status),
            ]
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(DoctorHoliday $holiday)
    {
        Gate::authorize('doctor-access');
        
        // Ensure the holiday belongs to the authenticated doctor
        if ($holiday->doctor_id !== auth()->user()->doctor->id) {
            abort(403);
        }
        
        // Only allow editing if status is pending
        if ($holiday->status !== 'pending') {
            return redirect()->route('doctor.holidays.index')
                ->with('error', 'You can only edit holiday requests that are pending approval.');
        }
        
        return view('dashboard.doctor.holidays.edit', compact('holiday'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDoctorHolidayRequest $request, DoctorHoliday $holiday)
    {
        Gate::authorize('doctor-access');
        
        // Ensure the holiday belongs to the authenticated doctor
        if ($holiday->doctor_id !== auth()->user()->doctor->id) {
            abort(403);
        }
        
        // Only allow updating if status is pending
        if ($holiday->status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'You can only edit holiday requests that are pending approval.'
            ], 403);
        }
        
        $data = $request->validated();
        $holiday->update($data);
        
        return response()->json([
            'success' => true,
            'message' => 'Holiday request updated successfully.',
            'holiday' => [
                'id' => $holiday->id,
                'start_date' => $holiday->start_date,
                'end_date' => $holiday->end_date,
                'reason' => $holiday->reason,
                'notes' => $holiday->notes,
                'status' => $holiday->status,
                'admin_notes' => $holiday->admin_notes,
                'formatted_dates' => $holiday->start_date->format('M d, Y') . ' - ' . $holiday->end_date->format('M d, Y'),
                'duration_days' => $holiday->start_date->diffInDays($holiday->end_date) + 1,
                'status_badge_class' => $this->getStatusBadgeClass($holiday->status),
            ]
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DoctorHoliday $holiday)
    {
        Gate::authorize('doctor-access');
        
        // Ensure the holiday belongs to the authenticated doctor
        if ($holiday->doctor_id !== auth()->user()->doctor->id) {
            abort(403);
        }
        
        // Only allow deleting if status is pending
        if ($holiday->status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'You can only delete holiday requests that are pending approval.'
            ], 403);
        }
        
        $holiday->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Holiday request deleted successfully.'
        ]);
    }

    /**
     * Get the CSS class for status badge
     */
    private function getStatusBadgeClass(string $status): string
    {
        return match($status) {
            'pending' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300',
            'approved' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300',
            'rejected' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300',
            default => 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-300',
        };
    }
}
