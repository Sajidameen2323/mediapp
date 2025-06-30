@extends('layouts.app')

@section('title', 'Reschedule Appointment')

@section('content')
<x-patient-navigation />
    {{-- Safety Check: Ensure appointment can be rescheduled --}}
    @if (!$appointment->canBeRescheduled())
        <div class="container mx-auto px-4 py-8">
            <div class="max-w-2xl mx-auto">
                <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-xl p-8 text-center">
                    <div class="flex flex-col items-center space-y-4">
                        <div class="w-16 h-16 bg-red-100 dark:bg-red-900/30 rounded-full flex items-center justify-center">
                            <i class="fas fa-exclamation-triangle text-2xl text-red-500 dark:text-red-400"></i>
                        </div>
                        <h2 class="text-xl font-bold text-red-900 dark:text-red-100">Cannot Reschedule Appointment</h2>
                        <p class="text-red-700 dark:text-red-300 text-center max-w-md">
                            @if (in_array($appointment->status, ['cancelled', 'completed']))
                                This appointment cannot be rescheduled because it is {{ $appointment->status }}.
                            @else
                                This appointment cannot be rescheduled because it's less than {{ $config->reschedule_hours_limit ?? 24 }} hours before the scheduled time.
                            @endif
                        </p>
                        <div class="flex flex-col sm:flex-row gap-3 mt-6">
                            <a href="{{ route('patient.appointments.show', $appointment) }}"
                                class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600 text-white font-medium rounded-lg transition-colors duration-200">
                                <i class="fas fa-arrow-left mr-2"></i>
                                Back to Appointment
                            </a>
                            <a href="{{ route('patient.appointments.index') }}"
                                class="inline-flex items-center px-6 py-3 bg-gray-600 hover:bg-gray-700 dark:bg-gray-500 dark:hover:bg-gray-600 text-white font-medium rounded-lg transition-colors duration-200">
                                <i class="fas fa-list mr-2"></i>
                                All Appointments
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
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

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
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
                            <p class="text-gray-900 dark:text-white">{{ $appointment->doctor->user->name }}</p>
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
                                {{ $appointment->getFormattedTimeAttribute() }}
                            </p>
                        </div>

                        <div>
                            <p class="text-sm font-medium text-gray-700 dark:text-gray-300">Duration</p>
                            <p class="text-gray-900 dark:text-white">{{ $appointment->service->duration_minutes ?? 30 }}
                                minutes</p>
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
            <div class="lg:col-span-3">
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">
                        <i class="fas fa-calendar-alt mr-2 text-green-600"></i>
                        Select New Date & Time
                    </h2>
                    <form action="{{ route('patient.appointments.reschedule.update', $appointment) }}" method="POST"
                        id="rescheduleForm">
                        @csrf
                        @method('PATCH')

                        <!-- Modern Calendar Component -->
                        <div class="mb-6">
                            <x-appointment.custom-calendar />

                            <!-- Hidden inputs for form submission -->
                            {{-- <input type="hidden" name="new_date" id="new_date" value="{{ old('new_date') }}">
                            <input type="hidden" name="new_time" id="new_time" value="{{ old('new_time') }}"> --}}

                            @error('new_date')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            @error('new_time')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Reschedule Reason -->
                        <div class="mb-6">
                            <label for="reschedule_reason"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Reason for Rescheduling (Optional)
                            </label>
                            <textarea name="reschedule_reason" id="reschedule_reason" rows="3"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                                placeholder="Please provide a reason for rescheduling (optional)">{{ old('reschedule_reason') }}</textarea>
                            @error('reschedule_reason')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex flex-col sm:flex-row gap-4">
                            <button type="submit" id="submitBtn"
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
                    serviceId: {{ $appointment->service_id }},
                    currentAppointmentDate: '{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('Y-m-d') }}',
                    currentAppointmentTime: '{{ \Carbon\Carbon::parse($appointment->appointment_time)->format('H:i') }}',
                    minRescheduleHours: {{ $config->reschedule_hours_limit ?? 24 }},

                    // Calendar configuration from appointment config
                    config: {
                        minAdvanceDays: {{ $config->min_booking_hours_ahead ? ceil($config->min_booking_hours_ahead / 24) : 1 }},
                        maxAdvanceDays: {{ $config->max_booking_days_ahead ?? 30 }}
                    },

                    // Date selection callback
                    onDateSelect: function(dateString, dateData) {
                        document.getElementById('new_date').value = dateString;
                        document.getElementById('appointment_date').value = dateString;

                        // Show feedback
                        console.log('Date selected for reschedule:', dateString, dateData);

                        // Check form completion and validation
                        checkFormCompletion();
                        showRescheduleValidation();
                    }, // Time selection callback
                    onTimeSelect: function(timeString, timeData) {
                        // Ensure time is in H:i format (Laravel validation expects this)
                        // If timeString is in H:i:s format, convert to H:i
                        let formattedTime = timeString;
                        if (timeString && timeString.includes(':')) {
                            const timeParts = timeString.split(':');
                            if (timeParts.length >= 2) {
                                formattedTime = `${timeParts[0]}:${timeParts[1]}`;
                            }
                        }

                        document.getElementById('new_time').value = formattedTime;
                        document.getElementById('appointment_time').value = formattedTime;

                        // Show feedback
                        console.log('Time selected for reschedule:', formattedTime, timeData);

                        // Check form completion and validation
                        checkFormCompletion();
                        showRescheduleValidation();
                    }
                });

                console.log('Reschedule calendar initialized successfully');

            } catch (error) {
                console.error('Failed to initialize reschedule calendar:', error);

                // Fallback: Show error message
                const calendarContainer = document.getElementById('appointment-calendar-container');
                if (calendarContainer) {
                    calendarContainer.innerHTML = `
                <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-xl p-6 text-center">
                    <i class="fas fa-exclamation-triangle text-4xl text-red-500 dark:text-red-400 mb-4"></i>
                    <h4 class="text-lg font-medium text-red-900 dark:text-red-100 mb-2">Calendar Error</h4>
                    <p class="text-red-700 dark:text-red-300">Unable to load the appointment calendar. Please refresh the page and try again.</p>
                    <button onclick="location.reload()" class="mt-4 bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg transition-colors">
                        <i class="fas fa-redo mr-2"></i>Refresh Page
                    </button>
                </div>
            `;
                }
            }

            // Function to check if form is complete
            function checkFormCompletion() {
                const newDate = document.getElementById('new_date').value;
                const newTime = document.getElementById('new_time').value;

                if (newDate && newTime) {
                    submitBtn.disabled = false;
                    submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                } else {
                    submitBtn.disabled = true;
                    submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
                }
            }

            // Function to show reschedule validation feedback
            function showRescheduleValidation() {
                if (!rescheduleCalendar) return;

                const validation = rescheduleCalendar.getRescheduleValidation();

                // Remove any existing validation messages
                const existingMessages = document.querySelectorAll('.reschedule-validation');
                existingMessages.forEach(msg => msg.remove());

                // Show validation errors or warnings
                if (validation.errors.length > 0) {
                    showValidationMessage(validation.errors, 'error');
                } else if (validation.warnings.length > 0) {
                    showValidationMessage(validation.warnings, 'warning');
                } else if (validation.isValid) {
                    showValidationMessage(['Great! Your new appointment time is available.'], 'success');
                }
            }

            // Function to display validation messages
            function showValidationMessage(messages, type) {
                const container = document.querySelector('#rescheduleForm');
                const messageDiv = document.createElement('div');
                messageDiv.className = `reschedule-validation mb-4 p-4 rounded-lg ${getValidationStyles(type)}`;

                const icon = type === 'error' ? 'fa-exclamation-circle' :
                    type === 'warning' ? 'fa-exclamation-triangle' : 'fa-check-circle';

                messageDiv.innerHTML = `
            <div class="flex items-start">
                <i class="fas ${icon} mt-1 mr-3"></i>
                <div>
                    ${messages.map(msg => `<p class="text-sm">${msg}</p>`).join('')}
                </div>
            </div>
        `;

                // Insert before the reschedule reason section
                const reasonSection = document.querySelector('#rescheduleForm .mb-6:last-of-type');
                if (reasonSection) {
                    reasonSection.insertAdjacentElement('beforebegin', messageDiv);
                }
            }

            // Get validation message styles
            function getValidationStyles(type) {
                switch (type) {
                    case 'error':
                        return 'bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 text-red-800 dark:text-red-200';
                    case 'warning':
                        return 'bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 text-yellow-800 dark:text-yellow-200';
                    case 'success':
                        return 'bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 text-green-800 dark:text-green-200';
                    default:
                        return 'bg-gray-50 dark:bg-gray-900/20 border border-gray-200 dark:border-gray-800 text-gray-800 dark:text-gray-200';
                }
            }

            // Form submission validation
            if (rescheduleForm) {
                rescheduleForm.addEventListener('submit', function(e) {
                    const newDate = document.getElementById('new_date').value;
                    const newTime = document.getElementById('new_time').value;

                    if (!newDate || !newTime) {
                        e.preventDefault();
                        alert('Please select both a new date and time for your appointment.');
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
                    } else {
                        // Fallback validation
                        const currentDate =
                            '{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('Y-m-d') }}';
                        const currentTime =
                            '{{ \Carbon\Carbon::parse($appointment->appointment_time)->format('H:i') }}';

                        if (newDate === currentDate && newTime === currentTime) {
                            e.preventDefault();
                            alert(
                                'Please select a different date or time. The selected date and time are the same as your current appointment.'
                                );
                            return false;
                        }
                    }

                    // Show loading state
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Processing...';
                    return false; // Prevent default form submission to handle via AJAX
                });
            }

            // Initial form state
            checkFormCompletion();
        });
    </script>
    @endif
@endsection
