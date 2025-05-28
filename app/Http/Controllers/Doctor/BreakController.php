<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Http\Requests\Doctor\StoreDoctorBreakRequest;
use App\Http\Requests\Doctor\UpdateDoctorBreakRequest;
use App\Models\DoctorBreak;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;

class BreakController extends Controller
{
    /**
     * Convert time string to proper format for database
     * 
     * @param string $timeStr Time string in H:i format
     * @return string Formatted time string
     */
    protected function formatTimeForDB($timeStr)
    {
        if (!$timeStr) {
            return null;
        }
        
        try {
            return Carbon::createFromFormat('H:i', $timeStr)->format('H:i:s');
        } catch (\Exception $e) {
            Log::warning("Time format conversion failed", ['time' => $timeStr, 'error' => $e->getMessage()]);
            return $timeStr;
        }
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        Gate::authorize('doctor-access');
        
        $doctor = auth()->user()->doctor;
        
        if (!$doctor) {
            abort(404, 'Doctor profile not found');
        }
        
        $breaks = $doctor->breaks()->orderBy('start_time')->get();
        
        return view('dashboard.doctor.breaks.index', compact('breaks'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        Gate::authorize('doctor-access');
        
        $selectedDay = $request->get('day');
        
        return view('dashboard.doctor.breaks.create', compact('selectedDay'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDoctorBreakRequest $request)
    {
        Gate::authorize('doctor-access');
        $doctor = auth()->user()->doctor;
        $data = $request->validated();
        $data['doctor_id'] = $doctor->id;
        $data['is_active'] = true;

        // Handle multiple days (array) or single day
        $days = $data['selected_days'] ?? ($data['day_of_week'] ? [$data['day_of_week']] : []);
        unset($data['selected_days']);
        $created = [];
        foreach ($days as $day) {
            $data['day_of_week'] = $day;
            $created[] = DoctorBreak::create($data);
        }
        if (count($created) > 1) {
            return response()->json(['success' => true, 'message' => 'Breaks created for selected days.']);
        }
        $break = $created[0] ?? null;
        return response()->json([
            'success' => true,
            'message' => 'Break created successfully.',
            'break' => $break
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(DoctorBreak $break)
    {
        Gate::authorize('doctor-access');
        
        // Ensure the break belongs to the authenticated doctor
        if ($break->doctor_id !== auth()->user()->doctor->id) {
            abort(403);
        }
        
        return response()->json([
            'break' => [
                'id' => $break->id,
                'date' => $break->date,
                'start_time' => $break->start_time,
                'end_time' => $break->end_time,
                'is_recurring' => $break->is_recurring,
                'recurring_days' => $break->recurring_days,
                'notes' => $break->notes,
                'formatted_date' => $break->date->format('M d, Y'),
                'formatted_time' => $break->start_time . ' - ' . $break->end_time,
            ]
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(DoctorBreak $break)
    {
        Gate::authorize('doctor-access');
        
        // Ensure the break belongs to the authenticated doctor
        if ($break->doctor_id !== auth()->user()->doctor->id) {
            abort(403);
        }
        
        return view('dashboard.doctor.breaks.edit', compact('break'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDoctorBreakRequest $request, DoctorBreak $break)
    {
        Gate::authorize('doctor-access');

        // Ensure the break belongs to the authenticated doctor
        if ($break->doctor_id !== auth()->user()->doctor->id) {
            abort(403);
        }
        
        try {
            $data = $request->validated();
            
            error_log("Updating break with data: " . json_encode($data));
            Log::info("Updating doctor break", ['break_id' => $break->id, 'data' => $data]);

            // Explicitly set each field to ensure proper type handling
            if (isset($data['title'])) {
                $break->title = $data['title'];
            }
            
            if (isset($data['day_of_week'])) {
                $break->day_of_week = $data['day_of_week'];
            }
            
            if (isset($data['start_time'])) {
                // Ensure proper time format conversion using the helper method
                $break->start_time = $this->formatTimeForDB($data['start_time']);
                Log::info("Setting start_time", ['input' => $data['start_time'], 'formatted' => $break->start_time]);
            }
            
            if (isset($data['end_time'])) {
                // Ensure proper time format conversion using the helper method
                $break->end_time = $this->formatTimeForDB($data['end_time']);
                Log::info("Setting end_time", ['input' => $data['end_time'], 'formatted' => $break->end_time]);
            }
            

            if (isset($data['type'])) {
                $break->type = $data['type'];
            }
            
            if (isset($data['specific_date'])) {
                $break->specific_date = $data['specific_date'];
            }
            
            if (isset($data['is_recurring'])) {
                $break->is_recurring = (bool)$data['is_recurring'];
            }
            
            if (isset($data['is_active'])) {
                $break->is_active = (bool)$data['is_active'];
            }
            
            // Handle recurring days if provided
            if (isset($data['recurring_days']) && is_array($data['recurring_days'])) {
                // The model doesn't have a direct field for recurring_days
                // This would need to be handled according to your application's design
                // It might be stored in a relationship or as JSON
                Log::info("Recurring days provided but not processed", ['days' => $data['recurring_days']]);
            }
            
            if (isset($data['notes'])) {
                $break->notes = $data['notes'];
            }
            
            // Save the model with full timestamps and events
            $success = $break->save();
            
            if (!$success) {
                Log::error("Failed to save doctor break: ", ['id' => $break->id, 'data' => $data]);
            }
            
            // Refresh the model to get updated data
            $break = $break->fresh();
            
            return redirect()->route('breaks.index')
                ->with('success', 'Break updated successfully!');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Failed to update break: ' . $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DoctorBreak $break)
    {
        Gate::authorize('doctor-access');
        
        // Ensure the break belongs to the authenticated doctor
        if ($break->doctor_id !== auth()->user()->doctor->id) {
            abort(403);
        }
        
        try {
            // Store break details for logging
            $breakId = $break->id;
            $breakDetails = [
                'id' => $breakId,
                'doctor_id' => $break->doctor_id,
                'start_time' => $break->start_time,
                'end_time' => $break->end_time,
            ];
            
            // Delete the break
            $break->delete();
            Log::info('Doctor break deleted successfully', $breakDetails);
            
            return redirect()->route('breaks.index')
                ->with('success', 'Break deleted successfully!');
        } catch (\Exception $e) {
            Log::error('Failed to delete doctor break', [
                'break_id' => $break->id ?? null,
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            
            return redirect()->route('breaks.index')
                ->with('error', 'Failed to delete break: ' . $e->getMessage());
        }
    }
}
