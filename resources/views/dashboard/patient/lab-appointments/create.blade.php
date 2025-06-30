@extends('layouts.app')

@section('title', 'Book Lab Appointment')

@section('content')
<x-patient-navigation />

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

        <!-- Booking Form -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
            <div class="p-6">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">
                    <i class="fas fa-calendar-plus text-green-500 mr-2"></i>
                    Appointment Details
                </h2>

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
                                    class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-green-500 focus:border-green-500">
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
                                   class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-green-500 focus:border-green-500">
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
                                    class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-green-500 focus:border-green-500"
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
                                      class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-green-500 focus:border-green-500">{{ old('patient_notes') }}</textarea>
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
                                      class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-green-500 focus:border-green-500">{{ old('special_instructions') }}</textarea>
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

    function updateTimeSlots() {
        const laboratoryId = laboratorySelect.value;
        const date = dateInput.value;

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
                }
            })
            .catch(error => {
                console.error('Error fetching time slots:', error);
                timeSelect.innerHTML = '<option value="">Error loading times</option>';
                timeSelect.disabled = true;
            });
    }

    laboratorySelect.addEventListener('change', updateTimeSlots);
    dateInput.addEventListener('change', updateTimeSlots);

    // Form validation
    function validateForm() {
        const isValid = laboratorySelect.value && dateInput.value && timeSelect.value;
        submitBtn.disabled = !isValid;
    }

    [laboratorySelect, dateInput, timeSelect].forEach(element => {
        element.addEventListener('change', validateForm);
    });

    // Initial validation
    validateForm();
});
</script>
@endpush
@endsection
