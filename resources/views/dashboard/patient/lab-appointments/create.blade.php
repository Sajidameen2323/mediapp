@extends('layouts.patient')

@section('title', 'Book Lab Appointment')

@section('content')


<div class="min-h-screen bg-gray-50 dark:bg-gray-900">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Page Header -->
        <div class="mb-8">
            <!-- Breadcrumb -->
            <nav class="flex mb-4" aria-label="Breadcrumb">
                <ol class="flex items-center space-x-4">
                    <li>
                        <a href="{{ route('patient.dashboard') }}" 
                           class="text-gray-400 dark:text-gray-500 hover:text-gray-600 dark:hover:text-gray-400 transition-colors duration-200">
                            <i class="fas fa-home"></i>
                            <span class="sr-only">Dashboard</span>
                        </a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <i class="fas fa-chevron-right text-gray-300 dark:text-gray-600 mx-2"></i>
                            <a href="{{ route('patient.lab-tests.index') }}" 
                               class="text-sm font-medium text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 transition-colors duration-200">
                                Lab Tests
                            </a>
                        </div>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <i class="fas fa-chevron-right text-gray-300 dark:text-gray-600 mx-2"></i>
                            <span class="text-sm font-medium text-gray-900 dark:text-white">Book Appointment</span>
                        </div>
                    </li>
                </ol>
            </nav>

            <!-- Header Content -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Book Lab Appointment</h1>
                    <p class="mt-2 text-gray-600 dark:text-gray-400">Schedule your laboratory test appointment</p>
                </div>
            </div>
        </div>

        <!-- Lab Test Info -->
        @if($labTestRequest)
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 mb-8">
                <div class="p-6">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                        <i class="fas fa-vial text-blue-500 dark:text-blue-400 mr-2"></i>
                        Lab Test Information
                    </h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">{{ $labTestRequest->test_name }}</h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">{{ ucfirst($labTestRequest->test_type) }}</p>
                            
                            @if($labTestRequest->medicalReport && $labTestRequest->medicalReport->doctor)
                                <div class="mt-3">
                                    <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Requested by:</span>
                                    <span class="text-sm text-gray-900 dark:text-gray-100 ml-1">
                                        {{ $labTestRequest->medicalReport->doctor->user->name }}
                                    </span>
                                </div>
                            @endif
                        </div>
                        
                        <div>
                            <x-lab-test.priority-badge :priority="$labTestRequest->priority" class="mb-3" />
                            
                            @if($labTestRequest->clinical_notes)
                                <div>
                                    <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Clinical Notes:</span>
                                    <p class="text-sm text-gray-700 dark:text-gray-300 mt-1">{{ $labTestRequest->clinical_notes }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Booking Instructions -->
        <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-6 mb-8">
            <div class="flex items-start">
                <i class="fas fa-info-circle text-blue-500 mr-3 mt-0.5"></i>
                <div>
                    <h3 class="text-blue-900 dark:text-blue-100 font-medium mb-2">Booking Information</h3>
                    <ul class="text-blue-800 dark:text-blue-200 text-sm space-y-1">
                        <li>• Appointments can be booked 1-30 days in advance</li>
                        <li>• Available time slots depend on laboratory working hours</li>
                        <li>• You will receive a confirmation once your appointment is approved</li>
                        <li>• Please arrive 15 minutes before your scheduled time</li>
                        <li>• Bring a valid ID and any required preparation instructions</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Booking Form -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
            <div class="p-6">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">
                    <i class="fas fa-calendar-plus text-green-500 mr-2"></i>
                    Appointment Details
                </h2>

                <!-- Success/Error Messages -->
                @if(session('success'))
                    <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-4 mb-8">
                        <div class="flex items-center">
                            <i class="fas fa-check-circle text-green-400 mr-3"></i>
                            <p class="text-green-800 dark:text-green-200 font-medium">{{ session('success') }}</p>
                        </div>
                    </div>
                @endif

                @if(session('error'))
                    <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-4 mb-8">
                        <div class="flex items-center">
                            <i class="fas fa-exclamation-circle text-red-400 mr-3"></i>
                            <p class="text-red-800 dark:text-red-200 font-medium">{{ session('error') }}</p>
                        </div>
                    </div>
                @endif

                @if($errors->any())
                    <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-4 mb-8">
                        <div class="flex items-start">
                            <i class="fas fa-exclamation-triangle text-red-400 mr-3 mt-0.5"></i>
                            <div>
                                <h3 class="text-red-800 dark:text-red-200 font-medium mb-2">Please correct the following errors:</h3>
                                <ul class="list-disc list-inside text-red-700 dark:text-red-300 space-y-1">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                @endif

                <form action="{{ route('patient.lab-appointments.store') }}" method="POST" id="bookingForm">
                    @csrf
                    
                    @if($labTestRequest)
                        <input type="hidden" name="lab_test_request_id" value="{{ $labTestRequest->id }}">
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Laboratory Selection -->
                        <div class="md:col-span-2">
                            <label for="laboratory_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Select Laboratory <span class="text-red-500">*</span>
                            </label>
                            <select name="laboratory_id" id="laboratory_id" required
                                    class="w-full border @error('laboratory_id') border-red-500 dark:border-red-400 @else border-gray-300 dark:border-gray-600 @enderror rounded-lg px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-green-500 focus:border-green-500">
                                <option value="">Choose a laboratory...</option>
                                @foreach($laboratories as $laboratory)
                                    <option value="{{ $laboratory->id }}" 
                                            data-opening-time="{{ $laboratory->opening_time ? $laboratory->opening_time->format('H:i') : '09:00' }}"
                                            data-closing-time="{{ $laboratory->closing_time ? $laboratory->closing_time->format('H:i') : '17:00' }}"
                                            {{ old('laboratory_id') == $laboratory->id ? 'selected' : '' }}>
                                        {{ $laboratory->name }} - {{ $laboratory->city }}
                                        @if($laboratory->consultation_fee)
                                            (Fee: ${{ number_format($laboratory->consultation_fee, 2) }})
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                            @error('laboratory_id')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Appointment Date -->
                        <div>
                            <label for="appointment_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Appointment Date <span class="text-red-500">*</span>
                            </label>
                            <input type="date" name="appointment_date" id="appointment_date" required
                                   min="{{ Carbon\Carbon::tomorrow()->toDateString() }}"
                                   max="{{ Carbon\Carbon::now()->addDays(30)->toDateString() }}"
                                   value="{{ old('appointment_date') }}"
                                   class="w-full border @error('appointment_date') border-red-500 dark:border-red-400 @else border-gray-300 dark:border-gray-600 @enderror rounded-lg px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-green-500 focus:border-green-500">
                            @error('appointment_date')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Appointment Time -->
                        <div>
                            <label for="start_time" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Appointment Time <span class="text-red-500">*</span>
                            </label>
                            <select name="start_time" id="start_time" required
                                    class="w-full border @error('start_time') border-red-500 dark:border-red-400 @else border-gray-300 dark:border-gray-600 @enderror rounded-lg px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                    disabled>
                                <option value="">Select date and laboratory first</option>
                            </select>
                            @error('start_time')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Patient Notes -->
                        <div class="md:col-span-2">
                            <label for="patient_notes" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Additional Notes
                            </label>
                            <textarea name="patient_notes" id="patient_notes" rows="3"
                                      placeholder="Any additional information or special requests..."
                                      class="w-full border @error('patient_notes') border-red-500 dark:border-red-400 @else border-gray-300 dark:border-gray-600 @enderror rounded-lg px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-green-500 focus:border-green-500">{{ old('patient_notes') }}</textarea>
                            @error('patient_notes')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Special Instructions -->
                        <div class="md:col-span-2">
                            <label for="special_instructions" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Special Instructions
                            </label>
                            <textarea name="special_instructions" id="special_instructions" rows="2"
                                      placeholder="Any special preparation requirements or medical conditions to note..."
                                      class="w-full border @error('special_instructions') border-red-500 dark:border-red-400 @else border-gray-300 dark:border-gray-600 @enderror rounded-lg px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-green-500 focus:border-green-500">{{ old('special_instructions') }}</textarea>
                            @error('special_instructions')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mt-8 pt-6 border-t border-gray-200 dark:border-gray-700">
                        <a href="{{ route('patient.lab-tests.index') }}" 
                           class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 mb-3 sm:mb-0">
                            <i class="fas fa-arrow-left mr-2"></i>
                            Back to Lab Tests
                        </a>
                        
                        <button type="submit" id="submitBtn"
                                class="inline-flex items-center px-6 py-2 bg-green-600 hover:bg-green-700 dark:bg-green-700 dark:hover:bg-green-600 text-white font-medium rounded-lg transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed">
                            <i class="fas fa-calendar-check mr-2"></i>
                            Book Appointment
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const laboratorySelect = document.getElementById('laboratory_id');
    const dateInput = document.getElementById('appointment_date');
    const timeSelect = document.getElementById('start_time');
    const submitBtn = document.getElementById('submitBtn');
    const form = document.getElementById('bookingForm');

    // Add loading state management
    function setLoadingState(isLoading) {
        if (isLoading) {
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Processing...';
        } else {
            submitBtn.innerHTML = '<i class="fas fa-calendar-check mr-2"></i>Book Appointment';
            validateForm(); // Re-enable based on validation
        }
    }

    function showFieldError(fieldId, message) {
        const field = document.getElementById(fieldId);
        const existingError = field.parentNode.querySelector('.field-error');
        
        // Remove existing error
        if (existingError) {
            existingError.remove();
        }
        
        // Add error styling
        field.classList.add('border-red-500', 'dark:border-red-400');
        field.classList.remove('border-gray-300', 'dark:border-gray-600');
        
        // Add error message
        const errorDiv = document.createElement('div');
        errorDiv.className = 'field-error mt-1 text-sm text-red-600 dark:text-red-400';
        errorDiv.textContent = message;
        field.parentNode.appendChild(errorDiv);
    }

    function clearFieldError(fieldId) {
        const field = document.getElementById(fieldId);
        const existingError = field.parentNode.querySelector('.field-error');
        
        if (existingError) {
            existingError.remove();
        }
        
        // Reset styling
        field.classList.remove('border-red-500', 'dark:border-red-400');
        field.classList.add('border-gray-300', 'dark:border-gray-600');
    }

    function updateTimeSlots() {
        const laboratoryId = laboratorySelect.value;
        const date = dateInput.value;

        // Clear any previous time selection errors
        clearFieldError('start_time');

        if (!laboratoryId || !date) {
            timeSelect.innerHTML = '<option value="">Select laboratory and date first</option>';
            timeSelect.disabled = true;
            return;
        }

        // Show loading
        timeSelect.innerHTML = '<option value="">Loading available times...</option>';
        timeSelect.disabled = true;

        // Fetch available slots
        fetch(`{{ route('patient.lab-appointments.available-slots') }}?laboratory_id=${laboratoryId}&date=${date}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                timeSelect.innerHTML = '';

                if (data.success && data.slots && data.slots.length > 0) {
                    timeSelect.innerHTML = '<option value="">Select a time...</option>';
                    data.slots.forEach(slot => {
                        const option = document.createElement('option');
                        option.value = slot.time;
                        option.textContent = slot.formatted;
                        timeSelect.appendChild(option);
                    });
                    timeSelect.disabled = false;
                } else {
                    timeSelect.innerHTML = '<option value="">No available time slots</option>';
                    timeSelect.disabled = true;
                    showFieldError('start_time', 'No available time slots for the selected date. Please choose a different date.');
                }
            })
            .catch(error => {
                console.error('Error fetching time slots:', error);
                timeSelect.innerHTML = '<option value="">Error loading times</option>';
                timeSelect.disabled = true;
                showFieldError('start_time', 'Unable to load available times. Please refresh the page and try again.');
            });
    }

    laboratorySelect.addEventListener('change', function() {
        clearFieldError('laboratory_id');
        updateTimeSlots();
    });
    
    dateInput.addEventListener('change', function() {
        clearFieldError('appointment_date');
        updateTimeSlots();
    });

    timeSelect.addEventListener('change', function() {
        clearFieldError('start_time');
        validateForm();
    });

    // Form validation
    function validateForm() {
        const isValid = laboratorySelect.value && dateInput.value && timeSelect.value && !timeSelect.disabled;
        submitBtn.disabled = !isValid;
        return isValid;
    }

    // Form submission handling
    form.addEventListener('submit', function(e) {
        if (!validateForm()) {
            e.preventDefault();
            
            // Show specific validation errors
            if (!laboratorySelect.value) {
                showFieldError('laboratory_id', 'Please select a laboratory.');
            }
            if (!dateInput.value) {
                showFieldError('appointment_date', 'Please select an appointment date.');
            }
            if (!timeSelect.value) {
                showFieldError('start_time', 'Please select an appointment time.');
            }
            
            return false;
        }
        
        setLoadingState(true);
    });

    // Real-time validation
    [laboratorySelect, dateInput, timeSelect].forEach(element => {
        element.addEventListener('change', validateForm);
        element.addEventListener('blur', function() {
            if (this.hasAttribute('required') && !this.value) {
                showFieldError(this.id, `${this.labels[0].textContent.replace(' *', '')} is required.`);
            }
        });
    });

    // Initial validation
    validateForm();

    // Auto-clear server-side errors when user interacts with fields
    document.querySelectorAll('input, select, textarea').forEach(field => {
        field.addEventListener('input', function() {
            // Remove server-side error styling when user starts typing
            const serverError = this.parentNode.querySelector('.text-red-600, .text-red-400');
            if (serverError && this.classList.contains('border-red-500')) {
                this.classList.remove('border-red-500', 'dark:border-red-400');
                this.classList.add('border-gray-300', 'dark:border-gray-600');
            }
        });
    });
});
</script>
@endpush
@endsection
