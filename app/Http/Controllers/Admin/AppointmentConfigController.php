<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AppointmentConfig;
use App\Models\Holiday;
use App\Models\BlockedTimeSlot;
use App\Models\Doctor;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Validation\Rule;

class AppointmentConfigController extends Controller
{
    use AuthorizesRequests;

    /**
     * Display the appointment configuration dashboard.
     */
    public function index()
    {
        $this->authorize('admin-access');
        
        $config = AppointmentConfig::getActive();
        $holidaysCount = Holiday::where('is_active', true)->count();
        $blockedSlotsCount = BlockedTimeSlot::where('is_active', true)->count();
        $totalDoctors = Doctor::count();
        
        return view('admin.appointment-config.index', compact(
            'config',
            'holidaysCount',
            'blockedSlotsCount',
            'totalDoctors'
        ));
    }

    /**
     * Show the form for editing the appointment configuration.
     */
    public function edit()
    {
        $this->authorize('admin-access');
        
        $config = AppointmentConfig::getActive();
        $paymentMethods = AppointmentConfig::getPaymentMethods();
        $timezones = AppointmentConfig::getTimezones();
        
        return view('admin.appointment-config.edit', compact(
            'config',
            'paymentMethods',
            'timezones'
        ));
    }

    /**
     * Update the appointment configuration.
     */
    public function update(Request $request)
    {
        $this->authorize('admin-access');
        
        $validated = $request->validate([
            'buffer_time_before' => 'required|integer|min:0|max:120',
            'buffer_time_after' => 'required|integer|min:0|max:120',
            'max_booking_days_ahead' => 'required|integer|min:1|max:365',
            'min_booking_hours_ahead' => 'required|integer|min:0|max:168',
            'tax_rate' => 'required|numeric|min:0|max:99.9999',
            'tax_enabled' => 'boolean',
            'cancellation_hours_limit' => 'required|integer|min:0|max:168',
            'reschedule_hours_limit' => 'required|integer|min:0|max:168',
            'allow_cancellation' => 'boolean',
            'allow_rescheduling' => 'boolean',
            'max_appointments_per_patient_per_day' => 'required|integer|min:1|max:20',
            'max_appointments_per_doctor_per_day' => 'required|integer|min:1|max:50',
            'accepted_payment_methods' => 'required|array|min:1',
            'accepted_payment_methods.*' => 'string|in:cash,card,online,insurance,bank_transfer',
            'require_payment_on_booking' => 'boolean',
            'booking_deposit_percentage' => 'required|numeric|min:0|max:100',
            'auto_approve_appointments' => 'boolean',
            'require_admin_approval' => 'boolean',
            'send_confirmation_email' => 'boolean',
            'send_reminder_email' => 'boolean',
            'reminder_hours_before' => 'required|integer|min:1|max:168',
            'default_start_time' => 'required|date_format:H:i',
            'default_end_time' => 'required|date_format:H:i|after:default_start_time',
            'default_slot_duration' => 'required|integer|min:15|max:240',
            'allow_emergency_bookings' => 'boolean',
            'emergency_booking_hours_limit' => 'required|integer|min:0|max:24',
            'timezone' => 'required|string|in:' . implode(',', array_keys(AppointmentConfig::getTimezones())),
        ]);

        // Convert boolean fields that may not be present in request
        $booleanFields = [
            'tax_enabled',
            'allow_cancellation',
            'allow_rescheduling',
            'require_payment_on_booking',
            'auto_approve_appointments',
            'require_admin_approval',
            'send_confirmation_email',
            'send_reminder_email',
            'allow_emergency_bookings',
        ];

        foreach ($booleanFields as $field) {
            $validated[$field] = $request->boolean($field);
        }

        // Convert time fields to proper format
        $validated['default_start_time'] = $validated['default_start_time'] . ':00';
        $validated['default_end_time'] = $validated['default_end_time'] . ':00';

        // Get current active config or create new one
        $config = AppointmentConfig::where('is_active', true)->first();
        
        if ($config) {
            $config->update($validated);
        } else {
            $validated['is_active'] = true;
            $config = AppointmentConfig::create($validated);
        }

        return redirect()
            ->route('admin.appointment-config.index')
            ->with('success', 'Appointment configuration updated successfully.');
    }

    /**
     * Display holidays management.
     */
    public function holidays()
    {
        $this->authorize('admin-access');
        
        $holidays = Holiday::where('is_active', true)
            ->orderBy('date')
            ->paginate(15);
            
        return view('admin.appointment-config.holidays', compact('holidays'));
    }

    /**
     * Store a new holiday.
     */
    public function storeHoliday(Request $request)
    {
        $this->authorize('admin-access');
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'date' => 'required|date|after_or_equal:today',
            'type' => 'required|string|in:national,religious,custom',
            'recurring_yearly' => 'boolean',
        ]);

        $validated['recurring_yearly'] = $request->boolean('recurring_yearly');
        $validated['is_active'] = true;

        Holiday::create($validated);

        return redirect()
            ->route('admin.appointment-config.holidays')
            ->with('success', 'Holiday added successfully.');
    }

    /**
     * Remove a holiday.
     */
    public function destroyHoliday(Holiday $holiday)
    {
        $this->authorize('admin-access');
        
        $holiday->update(['is_active' => false]);

        return redirect()
            ->route('admin.appointment-config.holidays')
            ->with('success', 'Holiday removed successfully.');
    }

    /**
     * Display blocked time slots management.
     */
    public function blockedSlots()
    {
        $this->authorize('admin-access');
        
        $blockedSlots = BlockedTimeSlot::with('doctor')
            ->where('is_active', true)
            ->orderBy('date')
            ->orderBy('start_time')
            ->paginate(15);
            
        $doctors = Doctor::where('is_available', true)->get();
            
        return view('admin.appointment-config.blocked-slots', compact('blockedSlots', 'doctors'));
    }

    /**
     * Store a new blocked time slot.
     */
    public function storeBlockedSlot(Request $request)
    {
        $this->authorize('admin-access');
        
        $validated = $request->validate([
            'doctor_id' => 'nullable|exists:doctors,id',
            'date' => 'required|date|after_or_equal:today',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'reason' => 'required|string|max:255',
            'notes' => 'nullable|string|max:1000',
            'type' => 'required|string|in:personal,maintenance,holiday,emergency,other',
            'is_recurring' => 'boolean',
            'recurring_pattern' => 'nullable|string|in:daily,weekly,monthly',
            'recurring_end_date' => 'nullable|date|after:date',
        ]);

        // Convert time fields to proper format
        $validated['start_time'] = $validated['start_time'] . ':00';
        $validated['end_time'] = $validated['end_time'] . ':00';
        $validated['is_recurring'] = $request->boolean('is_recurring');
        $validated['is_active'] = true;

        // Validate recurring fields if recurring is enabled
        if ($validated['is_recurring']) {
            $request->validate([
                'recurring_pattern' => 'required|string|in:daily,weekly,monthly',
                'recurring_end_date' => 'required|date|after:date',
            ]);
        }

        BlockedTimeSlot::create($validated);

        return redirect()
            ->route('admin.appointment-config.blocked-slots')
            ->with('success', 'Blocked time slot added successfully.');
    }

    /**
     * Remove a blocked time slot.
     */
    public function destroyBlockedSlot(BlockedTimeSlot $blockedSlot)
    {
        $this->authorize('admin-access');
        
        $blockedSlot->update(['is_active' => false]);

        return redirect()
            ->route('admin.appointment-config.blocked-slots')
            ->with('success', 'Blocked time slot removed successfully.');
    }
}
