@extends('layouts.app')



@section('title', 'Book Appointment')

{{-- Preselected Doctor and Service --}}
{{-- <input type="hidden" id="doctor_id" name="doctor_id" value="{{ $preselectedDoctor?->id ?? '' }}">
<input type="hidden" id="selected_service_id" name="service_id" value="{{ $preselectedService?->id ?? '' }}">
<input type="hidden" id="selected_date" name="appointment_date" value="">
<input type="hidden" id="selected_time" name="start_time" value=""> --}}
<input type="hidden" id="current_step" value="1">
{{-- Hidden inputs for patient details --}}
<input type="hidden" id="hidden_reason" name="reason" value="">
<input type="hidden" id="hidden_symptoms" name="symptoms" value="">
<input type="hidden" id="hidden_priority" name="priority" value="">
<input type="hidden" id="hidden_appointment_type" name="appointment_type" value="">


@section('content')
    {{-- log all errors --}}
    @if ($errors->any())
        <div class="bg-red-100 text-red-700 p-4 rounded-lg mb-6">
            <h2 class="font-semibold">Please fix the following errors:</h2>
            <ul class="list-disc pl-5 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    {{-- Main Appointment Booking Container --}}
    <div
        class="min-h-screen bg-gradient-to-br from-gray-50 via-blue-50 to-indigo-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            {{-- Header --}}
            <div class="text-center mb-12">
                <div class="mb-4">
                    <i class="fas fa-calendar-plus text-5xl text-blue-600 dark:text-blue-400 mb-4"></i>
                </div>
                <h1 class="text-4xl font-bold text-gray-900 dark:text-gray-100 mb-3">
                    Book Your Appointment
                </h1>
                <p class="text-lg text-gray-600 dark:text-gray-400 max-w-2xl mx-auto">
                    Schedule your appointment with our expert doctors in just a few simple steps.
                    We're here to make healthcare accessible and convenient for you.
                </p>
            </div>

            {{-- Enhanced Progress Stepper --}}
            <x-appointment.progress-stepper :currentStep="1" :totalSteps="5" />

            {{-- Main Booking Form --}}
            <form id="appointmentBookingForm" action="{{ route('patient.appointments.store') }}" method="POST"
                class="max-w-6xl mx-auto">
                @csrf

                {{-- Hidden inputs for form data --}}
                <input type="hidden" id="doctor_id" name="doctor_id" value="{{ $preselectedDoctor?->id ?? '' }}">
                <input type="hidden" id="selected_service_id" name="service_id"
                    value="{{ $preselectedService?->id ?? '' }}">
                <input type="hidden" id="selected_date" name="appointment_date" value="">
                <input type="hidden" id="selected_time" name="start_time" value="">
                <input type="hidden" id="current_step" value="1">

                {{-- Step Container --}}
                <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-2xl overflow-hidden">
                    {{-- Step 1: Doctor Search and Selection --}}
                    <div id="step_1" class="step-content transition-all duration-500">
                        <div class="p-8 sm:p-12">
                            <div class="text-center mb-8">
                                <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-2">
                                    <i class="fas fa-user-md mr-3 text-blue-600 dark:text-blue-400"></i>
                                    Find Your Doctor
                                </h2>
                                <p class="text-gray-600 dark:text-gray-400">
                                    Search and select from our qualified healthcare professionals
                                </p>
                            </div>
                            <x-appointment.doctor-search :services="$services" />
                            <x-appointment.doctor-results />
                        </div>
                    </div>

                    {{-- Step 2: Service Selection --}}
                    <div id="step_2" class="step-content hidden transition-all duration-500">
                        <div class="p-8 sm:p-12">
                            <div class="text-center mb-8">
                                <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-2">
                                    <i class="fas fa-stethoscope mr-3 text-green-600 dark:text-green-400"></i>
                                    Choose Service
                                </h2>
                                <p class="text-gray-600 dark:text-gray-400">
                                    Select the type of consultation or service you need
                                </p>
                            </div>
                            <x-appointment.service-selection />
                        </div>
                    </div>

                    {{-- Step 3: Date and Time Selection --}}
                    <div id="step_3" class="step-content hidden transition-all duration-500">
                        <div class="p-8 sm:p-12">
                            <div class="text-center mb-8">
                                <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-2">
                                    <i class="fas fa-calendar-check mr-3 text-purple-600 dark:text-purple-400"></i>
                                    Select Date & Time
                                </h2>
                                <p class="text-gray-600 dark:text-gray-400">
                                    Pick your preferred appointment date and time slot
                                </p>
                            </div>
                            <x-appointment.datetime-selection />
                        </div>
                    </div>

                    {{-- Step 4: Patient Details --}}
                    <div id="step_4" class="step-content hidden transition-all duration-500">
                        <div class="p-8 sm:p-12">
                            <div class="text-center mb-8">
                                <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-2">
                                    <i class="fas fa-edit mr-3 text-orange-600 dark:text-orange-400"></i>
                                    Additional Details
                                </h2>
                                <p class="text-gray-600 dark:text-gray-400">
                                    Provide additional information for your appointment
                                </p>
                            </div>
                            <x-appointment.patient-details />
                        </div>
                    </div>

                    {{-- Step 5: Confirmation --}}
                    <div id="step_5" class="step-content hidden transition-all duration-500">
                        <div class="p-8 sm:p-12">
                            <div class="text-center mb-8">
                                <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-2">
                                    <i class="fas fa-check-circle mr-3 text-green-600 dark:text-green-400"></i>
                                    Confirm Appointment
                                </h2>
                                <p class="text-gray-600 dark:text-gray-400">
                                    Review your appointment details and confirm your booking
                                </p>
                            </div>
                            <x-appointment.confirmation />
                        </div>
                    </div>

                    {{-- Enhanced Navigation Controls --}}
                    <div class="bg-gray-50 dark:bg-gray-700 px-8 py-6 border-t border-gray-200 dark:border-gray-600">
                        <x-appointment.navigation :currentStep="1" :totalSteps="5" />
                    </div>
                </div>
            </form>
        </div>
    </div>
    {{-- Enhanced Booking JavaScript --}}
    <link rel="stylesheet" href="{{ asset('css/appointment-calendar.css') }}">
    <script>
        // Single variable
        window.AppointmentConfig = @json($config);
        

        console.log('Config Object:', window.AppointmentConfig);
      
    </script>
    <script src="{{ asset('js/appointment-calendar.js') }}"></script>
    <script src="{{ asset('js/appointment-booking.js') }}"></script>
    {{-- @vite('resources/js/appointment-calendar.js') --}}
    <script>
        // Initialize the booking system when DOM is ready
        document.addEventListener('DOMContentLoaded', function() {
            window.appointmentBooking = new AppointmentBooking();
        });
    </script>
@endsection
