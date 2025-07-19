@extends('layouts.admin')

@section('content')
<div class="min-h-screen bg-gray-100 dark:bg-gray-900">
    <div class="container mx-auto px-4 py-8">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Reschedule Appointment</h1>
                <p class="text-gray-600 dark:text-gray-400">Appointment #{{ $appointment->id }}</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('admin.appointments.show', $appointment) }}" 
                   class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition duration-200">
                    <i class="fas fa-arrow-left mr-2"></i>Back to Details
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Current Appointment Info -->
            <div class="lg:col-span-1">
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 sticky top-6">
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">Current Appointment</h2>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Patient</label>
                            <p class="text-gray-900 dark:text-white">{{ $appointment->patient->name }}</p>
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ $appointment->patient->email }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Doctor</label>
                            <p class="text-gray-900 dark:text-white">{{ $appointment->doctor->user->name }}</p>
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ $appointment->doctor->specialization }}</p>
                        </div>                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Current Date & Time</label>
                            <p class="text-gray-900 dark:text-white">
                                {{ \Carbon\Carbon::parse($appointment->appointment_date)->format('M j, Y') }}
                                <br>
                                {{ \Carbon\Carbon::parse($appointment->start_time)->format('g:i A') }}
                                @if($appointment->end_time)
                                    - {{ \Carbon\Carbon::parse($appointment->end_time)->format('g:i A') }}
                                @endif
                            </p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Duration</label>
                            <p class="text-gray-900 dark:text-white">
                                @if($appointment->duration_minutes)
                                    {{ $appointment->duration_minutes }} minutes
                                    @if($appointment->duration_minutes >= 60)
                                        <span class="text-sm text-gray-500 dark:text-gray-400">
                                            ({{ floor($appointment->duration_minutes / 60) }}h {{ $appointment->duration_minutes % 60 }}m)
                                        </span>
                                    @endif
                                @else
                                    <span class="text-gray-500 dark:text-gray-400">Not specified</span>
                                @endif
                            </p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Status</label>
                            @php
                                $statusColors = [
                                    'pending' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300',
                                    'confirmed' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300',
                                    'cancelled' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300',
                                    'completed' => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300',
                                    'no_show' => 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-300',
                                ];
                            @endphp
                            <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full {{ $statusColors[$appointment->status] ?? 'bg-gray-100 text-gray-800' }}">
                                {{ ucfirst(str_replace('_', ' ', $appointment->status)) }}
                            </span>
                        </div>

                        @if($appointment->reschedule_count > 0)
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Reschedule Count</label>
                            <p class="text-gray-900 dark:text-white">{{ $appointment->reschedule_count }}</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>            <!-- Reschedule Form -->
            <div class="lg:col-span-2">
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                    <div class="px-0 py-0 border-b border-gray-200 dark:border-gray-700 mb-6">
                        <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Select New Date & Time</h2>
                    </div>
                      <form method="POST" action="{{ route('admin.appointments.update-schedule', $appointment) }}" id="rescheduleForm">
                        @csrf
                        
                        <!-- Reschedule Reason -->
                        <div class="mb-6">
                            <label for="reschedule_reason" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Reschedule Reason <span class="text-red-500">*</span>
                            </label>
                            <textarea name="reschedule_reason" id="reschedule_reason" rows="3" required
                                      class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('reschedule_reason') border-red-500 @enderror"
                                      placeholder="Enter reason for rescheduling...">{{ old('reschedule_reason') }}</textarea>
                            @error('reschedule_reason')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Modern Calendar Component -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-4">
                                Select New Date & Time <span class="text-red-500">*</span>
                            </label>
                            <x-appointment.custom-calendar />
                            
                            @error('appointment_date')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            @error('appointment_time')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex justify-end space-x-3">
                            <a href="{{ route('admin.appointments.show', $appointment) }}" 
                               class="px-6 py-2 bg-gray-300 dark:bg-gray-600 text-gray-700 dark:text-gray-300 rounded-md hover:bg-gray-400 dark:hover:bg-gray-500 transition duration-200">
                                Cancel
                            </a>
                            <button type="submit" id="submitBtn" disabled
                                    class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition duration-200 disabled:bg-gray-400 disabled:cursor-not-allowed">
                                <i class="fas fa-calendar-alt mr-2"></i>Reschedule Appointment
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/appointment-calendar.js') }}"></script>
<script src="{{ asset('js/appointment-reschedule.js') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize the appointment reschedule system
    const rescheduleForm = document.getElementById('rescheduleForm');
    const submitBtn = document.getElementById('submitBtn');
    let rescheduleCalendar = null;

    // Initialize the specialized reschedule calendar
    try {
        rescheduleCalendar = new AppointmentRescheduleCalendar('appointment-calendar-container', {
            // Current appointment details
            doctorId: {{ $appointment->doctor_id }},
            serviceId: {{ $appointment->service_id ?? 'null' }},
            currentAppointmentDate: '{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('Y-m-d') }}',
            currentAppointmentTime: '{{ \Carbon\Carbon::parse($appointment->start_time)->format('H:i') }}',
            minRescheduleHours: {{ $config->reschedule_hours_limit ?? 24 }},

            // Calendar configuration from appointment config
            config: {
                minAdvanceDays: {{ $config->min_booking_hours_ahead ? ceil($config->min_booking_hours_ahead / 24) : 0 }},
                maxAdvanceDays: {{ $config->max_booking_days_ahead ?? 30 }}
            },

            // Date selection callback
            onDateSelect: function(dateString, dateData) {
                console.log('Date selected for reschedule:', dateString, dateData);
                
                // Update hidden form field
                const appointmentDateInput = document.getElementById('appointment_date');
                if (appointmentDateInput) {
                    appointmentDateInput.value = dateString;
                }

                // Check form completion
                checkFormCompletion();
            },

            // Time selection callback
            onTimeSelect: function(timeString, formattedTime) {
                console.log('Time selected for reschedule:', timeString, formattedTime);
                
                // Update hidden form field
                const appointmentTimeInput = document.getElementById('appointment_time');
                if (appointmentTimeInput) {
                    appointmentTimeInput.value = timeString;
                }

                // Check form completion
                checkFormCompletion();
                showRescheduleValidation();
            }
        });

        console.log('Admin reschedule calendar initialized successfully');

    } catch (error) {
        console.error('Failed to initialize admin reschedule calendar:', error);

        // Fallback: Show error message
        const calendarContainer = document.getElementById('appointment-calendar-container');
        if (calendarContainer) {
            calendarContainer.innerHTML = `
                <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-xl p-6 text-center">
                    <i class="fas fa-exclamation-triangle text-4xl text-red-500 dark:text-red-400 mb-4"></i>
                    <h4 class="text-lg font-medium text-red-900 dark:text-red-100 mb-2">Calendar Error</h4>
                    <p class="text-red-700 dark:text-red-300 mb-4">Failed to load the appointment calendar. Please refresh the page and try again.</p>
                    <button onclick="location.reload()" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg">
                        Refresh Page
                    </button>
                </div>
            `;
        }
    }

    // Form completion checking function
    function checkFormCompletion() {
        const dateInput = document.getElementById('appointment_date');
        const timeInput = document.getElementById('appointment_time');
        const reasonInput = document.getElementById('reschedule_reason');

        const hasDate = dateInput && dateInput.value;
        const hasTime = timeInput && timeInput.value;
        const hasReason = reasonInput && reasonInput.value.trim();

        if (submitBtn) {
            submitBtn.disabled = !(hasDate && hasTime && hasReason);
        }
    }

    // Show reschedule validation
    function showRescheduleValidation() {
        if (rescheduleCalendar) {
            const validation = rescheduleCalendar.getRescheduleValidation();
            
            // Remove any existing validation messages
            const existingMessages = document.querySelectorAll('.reschedule-validation-message');
            existingMessages.forEach(msg => msg.remove());

            if (validation.errors.length > 0) {
                const errorDiv = document.createElement('div');
                errorDiv.className = 'reschedule-validation-message bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-3 mt-2';
                errorDiv.innerHTML = `
                    <div class="flex items-start">
                        <i class="fas fa-exclamation-triangle text-red-500 dark:text-red-400 mr-2 mt-0.5"></i>
                        <div>
                            <p class="text-sm font-medium text-red-900 dark:text-red-100">Validation Error</p>
                            <ul class="text-sm text-red-700 dark:text-red-300 mt-1">
                                ${validation.errors.map(error => `<li>• ${error}</li>`).join('')}
                            </ul>
                        </div>
                    </div>
                `;
                
                const calendarContainer = document.getElementById('appointment-calendar-container');
                if (calendarContainer) {
                    calendarContainer.appendChild(errorDiv);
                }
            }

            if (validation.warnings.length > 0) {
                const warningDiv = document.createElement('div');
                warningDiv.className = 'reschedule-validation-message bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg p-3 mt-2';
                warningDiv.innerHTML = `
                    <div class="flex items-start">
                        <i class="fas fa-exclamation-circle text-yellow-500 dark:text-yellow-400 mr-2 mt-0.5"></i>
                        <div>
                            <p class="text-sm font-medium text-yellow-900 dark:text-yellow-100">Notice</p>
                            <ul class="text-sm text-yellow-700 dark:text-yellow-300 mt-1">
                                ${validation.warnings.map(warning => `<li>• ${warning}</li>`).join('')}
                            </ul>
                        </div>
                    </div>
                `;
                
                const calendarContainer = document.getElementById('appointment-calendar-container');
                if (calendarContainer) {
                    calendarContainer.appendChild(warningDiv);
                }
            }
        }
    }

    // Check reason input changes
    const reasonInput = document.getElementById('reschedule_reason');
    if (reasonInput) {
        reasonInput.addEventListener('input', checkFormCompletion);
    }

    // Form submission validation
    if (rescheduleForm) {
        rescheduleForm.addEventListener('submit', function(e) {
            const dateInput = document.getElementById('appointment_date');
            const timeInput = document.getElementById('appointment_time');
            const reasonInput = document.getElementById('reschedule_reason');

            if (!dateInput || !dateInput.value || !timeInput || !timeInput.value) {
                e.preventDefault();
                alert('Please select both a new date and time for the appointment.');
                return false;
            }

            if (!reasonInput || !reasonInput.value.trim()) {
                e.preventDefault();
                alert('Please provide a reason for rescheduling.');
                reasonInput.focus();
                return false;
            }

            // Use reschedule calendar validation if available
            if (rescheduleCalendar) {
                const validation = rescheduleCalendar.getRescheduleValidation();
                if (!validation.isValid) {
                    e.preventDefault();
                    alert(validation.errors.join('\n'));
                    return false;
                }
            }

            // Confirm reschedule
            if (!confirm('Are you sure you want to reschedule this appointment?')) {
                e.preventDefault();
                return false;
            }

            // Show loading state
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Rescheduling...';
            }
        });
    }

    // Initial form check
    checkFormCompletion();
});
</script>
@endpush
