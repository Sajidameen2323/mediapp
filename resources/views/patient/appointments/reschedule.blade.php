@extends('layouts.app')

@section('title', 'Reschedule Appointment')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Reschedule Appointment</h1>
                <p class="text-gray-600 dark:text-gray-400 mt-2">Choose a new date and time for your appointment</p>
            </div>
            <a href="{{ route('patient.appointments.show', $appointment) }}" 
               class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                <i class="fas fa-arrow-left mr-2"></i>
                Back to Appointment
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Current Appointment Details -->
        <div class="lg:col-span-1">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                    <i class="fas fa-calendar-check mr-2 text-blue-600"></i>
                    Current Appointment
                </h2>
                
                <div class="space-y-4">
                    <div>
                        <p class="text-sm font-medium text-gray-700 dark:text-gray-300">Doctor</p>
                        <p class="text-gray-900 dark:text-white">Dr. {{ $appointment->doctor->user->name }}</p>
                    </div>
                    
                    <div>
                        <p class="text-sm font-medium text-gray-700 dark:text-gray-300">Service</p>
                        <p class="text-gray-900 dark:text-white">{{ $appointment->service->name }}</p>
                    </div>
                    
                    <div>
                        <p class="text-sm font-medium text-gray-700 dark:text-gray-300">Current Date & Time</p>
                        <p class="text-gray-900 dark:text-white">
                            {{ \Carbon\Carbon::parse($appointment->appointment_date)->format('M d, Y') }}
                        </p>
                        <p class="text-gray-900 dark:text-white">
                            {{ \Carbon\Carbon::parse($appointment->appointment_time)->format('g:i A') }}
                        </p>
                    </div>
                    
                    <div>
                        <p class="text-sm font-medium text-gray-700 dark:text-gray-300">Duration</p>
                        <p class="text-gray-900 dark:text-white">{{ $appointment->service->duration_minutes ?? 30 }} minutes</p>
                    </div>
                </div>
            </div>

            <!-- Rescheduling Policy -->
            <div class="bg-blue-50 dark:bg-blue-900/30 rounded-lg border border-blue-200 dark:border-blue-700 p-4 mt-6">
                <h3 class="text-sm font-medium text-blue-800 dark:text-blue-200 mb-2">
                    <i class="fas fa-info-circle mr-2"></i>
                    Rescheduling Policy
                </h3>
                <ul class="text-sm text-blue-700 dark:text-blue-300 space-y-1">
                    <li>• Must reschedule at least {{ $config->reschedule_hours_limit }} hours before appointment</li>
                    <li>• Free rescheduling once per appointment</li>
                    <li>• Subject to doctor availability</li>
                </ul>
            </div>
        </div>

        <!-- Reschedule Form -->
        <div class="lg:col-span-2">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">
                    <i class="fas fa-calendar-alt mr-2 text-green-600"></i>
                    Select New Date & Time
                </h2>

                <form action="{{ route('patient.appointments.reschedule.update', $appointment) }}" method="POST" id="rescheduleForm">
                    @csrf
                    @method('PATCH')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <!-- New Date -->
                        <div>
                            <label for="new_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                New Date <span class="text-red-500">*</span>
                            </label>
                            <input type="date" 
                                   name="new_date" 
                                   id="new_date"
                                   value="{{ old('new_date') }}"
                                   min="{{ \Carbon\Carbon::tomorrow()->format('Y-m-d') }}"
                                   max="{{ \Carbon\Carbon::now()->addDays($config->max_booking_days_ahead)->format('Y-m-d') }}"
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white" 
                                   required>
                            @error('new_date')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- New Time -->
                        <div>
                            <label for="new_time" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                New Time <span class="text-red-500">*</span>
                            </label>
                            <select name="new_time" 
                                    id="new_time"
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white" 
                                    required>
                                <option value="">Select a time slot</option>
                                <!-- Time slots will be populated via JavaScript -->
                            </select>
                            @error('new_time')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <div id="time-slots-loading" class="hidden mt-2 text-sm text-gray-500 dark:text-gray-400">
                                <i class="fas fa-spinner fa-spin mr-2"></i>Loading available times...
                            </div>
                        </div>
                    </div>

                    <!-- Reschedule Reason -->
                    <div class="mb-6">
                        <label for="reschedule_reason" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Reason for Rescheduling (Optional)
                        </label>
                        <textarea name="reschedule_reason" 
                                  id="reschedule_reason" 
                                  rows="3"
                                  class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                                  placeholder="Please provide a reason for rescheduling (optional)">{{ old('reschedule_reason') }}</textarea>
                        @error('reschedule_reason')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex flex-col sm:flex-row gap-4">
                        <button type="submit" 
                                id="submitBtn"
                                class="flex-1 inline-flex items-center justify-center px-6 py-3 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition-colors duration-200 disabled:opacity-50 disabled:cursor-not-allowed">
                            <i class="fas fa-calendar-check mr-2"></i>
                            Confirm Reschedule
                        </button>
                        
                        <a href="{{ route('patient.appointments.show', $appointment) }}" 
                           class="flex-1 inline-flex items-center justify-center px-6 py-3 bg-gray-600 hover:bg-gray-700 text-white font-medium rounded-lg transition-colors duration-200">
                            <i class="fas fa-times mr-2"></i>
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const dateInput = document.getElementById('new_date');
    const timeSelect = document.getElementById('new_time');
    const loadingIndicator = document.getElementById('time-slots-loading');
    const submitBtn = document.getElementById('submitBtn');
    
    dateInput.addEventListener('change', function() {
        const selectedDate = this.value;
        if (selectedDate) {
            loadAvailableTimeSlots(selectedDate);
        } else {
            timeSelect.innerHTML = '<option value="">Select a time slot</option>';
            timeSelect.disabled = true;
        }
    });
    
    async function loadAvailableTimeSlots(date) {
        timeSelect.disabled = true;
        timeSelect.innerHTML = '<option value="">Loading...</option>';
        loadingIndicator.classList.remove('hidden');
        
        try {
            const response = await fetch(`/api/appointments/available-slots?doctor_id={{ $appointment->doctor_id }}&date=${date}&service_id={{ $appointment->service_id }}`);
            const data = await response.json();
            
            timeSelect.innerHTML = '<option value="">Select a time slot</option>';
            
            if (data.success && data.slots && data.slots.length > 0) {
                data.slots.forEach(slot => {
                    const option = document.createElement('option');
                    option.value = slot.start_time;
                    option.textContent = formatTime(slot.start_time) + ' - ' + formatTime(slot.end_time);
                    timeSelect.appendChild(option);
                });
                timeSelect.disabled = false;
            } else {
                timeSelect.innerHTML = '<option value="">No available time slots</option>';
            }
        } catch (error) {
            console.error('Error loading time slots:', error);
            timeSelect.innerHTML = '<option value="">Error loading time slots</option>';
        } finally {
            loadingIndicator.classList.add('hidden');
        }
    }
    
    function formatTime(timeString) {
        const time = new Date('2000-01-01 ' + timeString);
        return time.toLocaleTimeString('en-US', {
            hour: 'numeric',
            minute: '2-digit',
            hour12: true
        });
    }
    
    // Form validation
    document.getElementById('rescheduleForm').addEventListener('submit', function(e) {
        if (!timeSelect.value) {
            e.preventDefault();
            alert('Please select a time slot.');
            return;
        }
        
        // Disable submit button to prevent double submission
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Rescheduling...';
    });
});
</script>
@endsection
