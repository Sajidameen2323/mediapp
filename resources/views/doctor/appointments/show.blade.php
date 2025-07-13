@extends('layouts.app')

@section('title', 'Appointment Details - Medi App')

@section('content')
<div class="appointment-details-content">
    <!-- Flash Messages -->
    @if(session('success'))
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 mb-4">
            <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-700 rounded-lg p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-check-circle text-green-400 text-lg"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-green-800 dark:text-green-200">
                            {{ session('success') }}
                        </p>
                        @if(session('report_details'))
                            <p class="text-xs text-green-600 dark:text-green-300 mt-1">
                                Report for {{ session('report_details.patient_name') }} on {{ session('report_details.consultation_date') }}
                            </p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 mb-4">
            <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-700 rounded-lg p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-exclamation-circle text-red-400 text-lg"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-red-800 dark:text-red-200">
                            {{ session('error') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    @endif

<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">    <!-- Header Section -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <div class="flex items-center space-x-3">
                    <a href="{{ route('doctor.appointments.index') }}" class="text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white transition-colors duration-200 p-2 rounded-md hover:bg-gray-100 dark:hover:bg-gray-700">
                        <i class="fas fa-arrow-left text-xl"></i>
                    </a>
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Appointment Details</h1>
                        <div class="flex items-center space-x-4 mt-1">
                            <p class="text-gray-600 dark:text-gray-400">ID: #{{ $appointment->appointment_number }}</p>
                            <span class="text-gray-400">•</span>
                            <p class="text-gray-600 dark:text-gray-400">{{ $appointment->patient->name }}</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="flex items-center space-x-3">
                <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold
                    {{ $appointment->status === 'pending' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200 border border-yellow-300' : '' }}
                    {{ $appointment->status === 'confirmed' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200 border border-green-300' : '' }}
                    {{ $appointment->status === 'completed' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200 border border-blue-300' : '' }}
                    {{ $appointment->status === 'cancelled' ? 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200 border border-red-300' : '' }}
                    {{ $appointment->status === 'no_show' ? 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200 border border-gray-300' : '' }}">
                    <i class="fas fa-circle w-2 h-2 mr-2"></i>
                    {{ ucfirst($appointment->status) }}
                </span>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Patient Information -->
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <div class="flex items-center justify-between cursor-pointer" onclick="toggleSection('patient-info')">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">
                        <i class="fas fa-user text-blue-600 mr-2"></i>
                        Patient Information
                    </h3>
                    <i class="fas fa-chevron-down text-gray-400 dark:text-gray-500 transition-transform duration-200" id="patient-info-icon"></i>
                </div>
                <div id="patient-info-content" class="mt-4">
                  <dl class="space-y-4">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 w-8 h-8 bg-blue-100 dark:bg-blue-900 rounded-full flex items-center justify-center mr-3">
                            <i class="fas fa-user text-blue-600 dark:text-blue-400 text-sm"></i>
                        </div>
                        <div>
                            <dt class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">Full Name</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white font-semibold">{{ $appointment->patient->name }}</dd>
                        </div>
                    </div>
                    
                    <div class="flex items-center">
                        <div class="flex-shrink-0 w-8 h-8 bg-green-100 dark:bg-green-900 rounded-full flex items-center justify-center mr-3">
                            <i class="fas fa-phone text-green-600 dark:text-green-400 text-sm"></i>
                        </div>
                        <div>
                            <dt class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">Phone Number</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                                <a href="tel:{{ $appointment->patient->phone_number }}" class="text-blue-600 hover:text-blue-500 font-medium">
                                    {{ $appointment->patient->phone_number }}
                                </a>
                            </dd>
                        </div>
                    </div>
                    
                    @if($appointment->patient->email)
                        <div class="flex items-center">
                            <div class="flex-shrink-0 w-8 h-8 bg-purple-100 dark:bg-purple-900 rounded-full flex items-center justify-center mr-3">
                                <i class="fas fa-envelope text-purple-600 dark:text-purple-400 text-sm"></i>
                            </div>
                            <div>
                                <dt class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">Email Address</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                                    <a href="mailto:{{ $appointment->patient->email }}" class="text-blue-600 hover:text-blue-500 font-medium">
                                        {{ $appointment->patient->email }}
                                    </a>
                                </dd>
                            </div>
                        </div>
                    @endif
                    
                    @if($appointment->patient->date_of_birth)
                        <div class="flex items-center">
                            <div class="flex-shrink-0 w-8 h-8 bg-orange-100 dark:bg-orange-900 rounded-full flex items-center justify-center mr-3">
                                <i class="fas fa-birthday-cake text-orange-600 dark:text-orange-400 text-sm"></i>
                            </div>
                            <div>
                                <dt class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">Age</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-white font-medium">
                                    {{ \Carbon\Carbon::parse($appointment->patient->date_of_birth)->age }} years old
                                </dd>
                            </div>
                        </div>
                    @endif
                      @if($appointment->patient_notes)
                        <div class="flex items-start">
                            <div class="flex-shrink-0 w-8 h-8 bg-yellow-100 dark:bg-yellow-900 rounded-full flex items-center justify-center mr-3 mt-1">
                                <i class="fas fa-sticky-note text-yellow-600 dark:text-yellow-400 text-sm"></i>
                            </div>
                            <div>
                                <dt class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">Patient Notes</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-white bg-yellow-50 dark:bg-yellow-900/20 p-3 rounded-md border-l-4 border-yellow-400">{{ $appointment->patient_notes }}</dd>
                            </div>
                        </div>
                    @endif
                </dl>
                </div>
            </div>
        </div>

        <!-- Appointment Details -->
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <div class="flex items-center justify-between cursor-pointer" onclick="toggleSection('appointment-details')">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">
                        <i class="fas fa-calendar-check text-green-600 mr-2"></i>
                        Appointment Details
                    </h3>
                    <i class="fas fa-chevron-down text-gray-400 dark:text-gray-500 transition-transform duration-200" id="appointment-details-icon"></i>
                </div>
                <div id="appointment-details-content" class="mt-4">
                  <dl class="space-y-4">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 w-8 h-8 bg-blue-100 dark:bg-blue-900 rounded-full flex items-center justify-center mr-3">
                            <i class="fas fa-stethoscope text-blue-600 dark:text-blue-400 text-sm"></i>
                        </div>
                        <div>
                            <dt class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">Service</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white font-semibold">{{ $appointment->service->name }}</dd>
                        </div>
                    </div>
                    
                    <div class="flex items-center">
                        <div class="flex-shrink-0 w-8 h-8 bg-green-100 dark:bg-green-900 rounded-full flex items-center justify-center mr-3">
                            <i class="fas fa-calendar text-green-600 dark:text-green-400 text-sm"></i>
                        </div>
                        <div>
                            <dt class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">Date</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white font-medium">{{ date('l, F d, Y', strtotime((string)$appointment->appointment_date)) }}</dd>
                        </div>
                    </div>
                    
                    <div class="flex items-center">
                        <div class="flex-shrink-0 w-8 h-8 bg-purple-100 dark:bg-purple-900 rounded-full flex items-center justify-center mr-3">
                            <i class="fas fa-clock text-purple-600 dark:text-purple-400 text-sm"></i>
                        </div>
                        <div>
                            <dt class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">Time</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white font-medium">{{ $appointment->formatted_time }}</dd>
                        </div>
                    </div>
                    
                    <div class="flex items-center">
                        <div class="flex-shrink-0 w-8 h-8 bg-orange-100 dark:bg-orange-900 rounded-full flex items-center justify-center mr-3">
                            <i class="fas fa-hourglass-half text-orange-600 dark:text-orange-400 text-sm"></i>
                        </div>
                        <div>
                            <dt class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">Duration</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white font-medium">{{ $appointment->duration_minutes }} minutes</dd>
                        </div>
                    </div>
                    
                    <div class="flex items-center">
                        <div class="flex-shrink-0 w-8 h-8 bg-indigo-100 dark:bg-indigo-900 rounded-full flex items-center justify-center mr-3">
                            <i class="fas fa-laptop text-indigo-600 dark:text-indigo-400 text-sm"></i>
                        </div>
                        <div>
                            <dt class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">Booking Source</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white font-medium">{{ ucfirst(str_replace('_', ' ', $appointment->booking_source)) }}</dd>
                        </div>
                    </div>
                    
                    <div class="flex items-center">
                        <div class="flex-shrink-0 w-8 h-8 bg-gray-100 dark:bg-gray-900 rounded-full flex items-center justify-center mr-3">
                            <i class="fas fa-calendar-plus text-gray-600 dark:text-gray-400 text-sm"></i>
                        </div>
                        <div>
                            <dt class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">Booked At</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white font-medium">{{ $appointment->booked_at->format('M d, Y g:i A') }}</dd>
                        </div>
                    </div>
                </dl>
                </div>
            </div>
        </div>

        <!-- Payment Information -->
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <div class="flex items-center justify-between cursor-pointer" onclick="toggleSection('payment-info')">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">
                        <i class="fas fa-credit-card text-purple-600 mr-2"></i>
                        Payment Information
                    </h3>
                    <i class="fas fa-chevron-down text-gray-400 dark:text-gray-500 transition-transform duration-200" id="payment-info-icon"></i>
                </div>
                <div id="payment-info-content" class="mt-4">
                  <dl class="space-y-4">
                    <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 w-8 h-8 bg-blue-100 dark:bg-blue-900 rounded-full flex items-center justify-center mr-3">
                                <i class="fas fa-dollar-sign text-blue-600 dark:text-blue-400 text-sm"></i>
                            </div>
                            <div>
                                <dt class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">Total Amount</dt>
                            </div>
                        </div>
                        <dd class="text-lg font-bold text-gray-900 dark:text-white">${{ number_format((float)$appointment->total_amount, 2) }}</dd>
                    </div>
                    
                    <div class="flex items-center justify-between p-3 bg-green-50 dark:bg-green-900/20 rounded-lg">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 w-8 h-8 bg-green-100 dark:bg-green-900 rounded-full flex items-center justify-center mr-3">
                                <i class="fas fa-check-circle text-green-600 dark:text-green-400 text-sm"></i>
                            </div>
                            <div>
                                <dt class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">Paid Amount</dt>
                            </div>
                        </div>
                        <dd class="text-lg font-bold text-green-600 dark:text-green-400">${{ number_format((float)$appointment->paid_amount, 2) }}</dd>
                    </div>
                    
                    @if($appointment->total_amount > $appointment->paid_amount)
                        <div class="flex items-center justify-between p-3 bg-red-50 dark:bg-red-900/20 rounded-lg border border-red-200 dark:border-red-800">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 w-8 h-8 bg-red-100 dark:bg-red-900 rounded-full flex items-center justify-center mr-3">
                                    <i class="fas fa-exclamation-triangle text-red-600 dark:text-red-400 text-sm"></i>
                                </div>
                                <div>
                                    <dt class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">Outstanding Balance</dt>
                                </div>
                            </div>
                            <dd class="text-lg font-bold text-red-600 dark:text-red-400">
                                ${{ number_format($appointment->total_amount - $appointment->paid_amount, 2) }}
                            </dd>
                        </div>
                    @endif
                    
                    <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 w-8 h-8 bg-purple-100 dark:bg-purple-900 rounded-full flex items-center justify-center mr-3">
                                <i class="fas fa-credit-card text-purple-600 dark:text-purple-400 text-sm"></i>
                            </div>
                            <div>
                                <dt class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">Payment Status</dt>
                            </div>
                        </div>
                        <dd>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium
                                {{ $appointment->payment_status === 'paid' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : '' }}
                                {{ $appointment->payment_status === 'pending' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' : '' }}
                                {{ $appointment->payment_status === 'partially_paid' ? 'bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-200' : '' }}
                                {{ $appointment->payment_status === 'refunded' ? 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' : '' }}">
                                <i class="fas fa-circle w-2 h-2 mr-1"></i>
                                {{ ucfirst(str_replace('_', ' ', $appointment->payment_status)) }}
                            </span>
                        </dd>
                    </div>
                </dl>
                </div>
            </div>
        </div>

        <!-- Special Instructions & Notes -->
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <div class="flex items-center justify-between cursor-pointer" onclick="toggleSection('instructions-notes')">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">
                        <i class="fas fa-sticky-note text-orange-600 mr-2"></i>
                        Instructions & Notes
                    </h3>
                    <i class="fas fa-chevron-down text-gray-400 dark:text-gray-500 transition-transform duration-200" id="instructions-notes-icon"></i>
                </div>
                <div id="instructions-notes-content" class="mt-4">
                
                @if($appointment->special_instructions)
                    <div class="mb-4">
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Special Instructions</dt>
                        <dd class="text-sm text-gray-900 dark:text-white bg-yellow-50 dark:bg-yellow-900/20 p-3 rounded-md">
                            {{ $appointment->special_instructions }}
                        </dd>
                    </div>
                @endif
                
                @if($appointment->completion_notes)
                    <div class="mb-4">
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Completion Notes</dt>
                        <dd class="text-sm text-gray-900 dark:text-white bg-blue-50 dark:bg-blue-900/20 p-3 rounded-md">
                            {{ $appointment->completion_notes }}
                        </dd>
                    </div>
                @endif
                
                @if($appointment->status === 'cancelled')
                    <div class="mb-4">
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Cancellation Details</dt>
                        <dd class="text-sm text-gray-900 dark:text-white bg-red-50 dark:bg-red-900/20 p-3 rounded-md">
                            <p><strong>Cancelled by:</strong> {{ ucfirst($appointment->cancelled_by) }}</p>
                            <p><strong>Date:</strong> {{ $appointment->cancelled_at->format('M d, Y g:i A') }}</p>
                            @if($appointment->cancellation_reason)
                                <p><strong>Reason:</strong> {{ $appointment->cancellation_reason }}</p>
                            @endif
                        </dd>
                    </div>
                @endif
                
                @if(!$appointment->special_instructions && !$appointment->completion_notes && $appointment->status !== 'cancelled')
                    <p class="text-sm text-gray-500 dark:text-gray-400 italic">No additional instructions or notes.</p>
                @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="mt-8 bg-white dark:bg-gray-800 shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <div class="flex items-center justify-between cursor-pointer" onclick="toggleSection('actions')">
                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">
                    <i class="fas fa-cogs text-gray-600 mr-2"></i>
                    Actions
                </h3>
                <i class="fas fa-chevron-down text-gray-400 dark:text-gray-500 transition-transform duration-200" id="actions-icon"></i>
            </div>
            <div id="actions-content" class="mt-4">
            
            <div class="flex flex-wrap gap-3">
                @if($appointment->status === 'pending')
                    <form action="{{ route('doctor.appointments.confirm', $appointment) }}" method="POST" class="inline">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors duration-200">
                            <i class="fas fa-check mr-2"></i>Confirm Appointment
                        </button>
                    </form>
                @endif
                
                @if($appointment->status === 'confirmed')
                    <button onclick="showCompleteModal()" class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors duration-200">
                        <i class="fas fa-check-double mr-2"></i>Mark as Completed
                    </button>
                    
                    <form action="{{ route('doctor.appointments.no-show', $appointment) }}" method="POST" class="inline">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors duration-200">
                            <i class="fas fa-user-times mr-2"></i>Mark as No Show
                        </button>
                    </form>
                @endif
                
                @if(!in_array($appointment->status, ['cancelled', 'completed']))
                    <button onclick="showCancelModal()" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors duration-200">
                        <i class="fas fa-times mr-2"></i>Cancel Appointment
                    </button>
                @endif
                  <a href="{{ route('doctor.appointments.index') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors duration-200">
                    <i class="fas fa-list mr-2"></i>Back to List
                </a>
            </div>
            </div>
        </div>
    </div>

    <!-- Medical Reports Section -->
    <div class="mt-8 bg-white dark:bg-gray-800 shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <div class="flex justify-between items-center cursor-pointer" onclick="toggleSection('medical-reports')">
                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">
                    <i class="fas fa-file-medical text-blue-600 mr-2"></i>
                    Medical Reports
                </h3>
                <div class="flex items-center space-x-3">
                    <a href="{{ route('doctor.medical-reports.create', ['patient_id' => $appointment->patient_id]) }}" 
                       class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors duration-200 inline-flex items-center"
                       onclick="event.stopPropagation();"
                       target="_blank"
                       rel="noopener noreferrer">
                        <i class="fas fa-plus mr-2"></i>Create Report
                    </a>
                    <i class="fas fa-chevron-down text-gray-400 dark:text-gray-500 transition-transform duration-200" id="medical-reports-icon"></i>
                </div>
            </div>
            <div id="medical-reports-content" class="mt-4">
            
            @if($medicalReports->count() > 0)
                <div class="space-y-4">
                    @foreach($medicalReports as $report)
                        <div class="border border-gray-200 dark:border-gray-600 rounded-lg p-4 hover:border-blue-300 dark:hover:border-blue-500 transition">
                            <div class="flex justify-between items-start">
                                <div class="flex-1">
                                    <div class="flex items-center gap-3 mb-2">
                                        <h4 class="font-semibold text-gray-800 dark:text-white">
                                            {{ Str::limit($report->title, 50) }}
                                        </h4>
                                        @if($report->doctor_id !== auth()->user()->doctor->id)
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                                <i class="fas fa-share-alt mr-1"></i>
                                                Shared Access
                                            </span>
                                        @endif
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium 
                                            @if($report->status === 'completed') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                            @else bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200 @endif">
                                            <i class="fas fa-circle text-xs mr-1"></i>
                                            {{ ucfirst($report->status) }}
                                        </span>
                                    </div>
                                    <div class="flex items-center gap-4 text-sm text-gray-600 dark:text-gray-400 mb-3">
                                        <span class="flex items-center">
                                            <i class="fas fa-calendar mr-1"></i>
                                            {{ date('M d, Y', strtotime((string)$report->consultation_date)) }}
                                        </span>
                                        <span class="flex items-center">
                                            <i class="fas fa-user-md mr-1"></i>
                                            {{ $report->doctor->user->name }}
                                        </span>
                                    </div>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-600 dark:text-gray-400">
                                        <div>
                                            <span class="font-medium">Chief Complaint:</span>
                                            <p class="mt-1">{{ Str::limit($report->chief_complaint, 100) }}</p>
                                        </div>
                                        <div>
                                            <span class="font-medium">Diagnosis:</span>
                                            <p class="mt-1">{{ Str::limit($report->assessment_diagnosis, 100) }}</p>
                                        </div>
                                    </div>
                                    @if($report->prescriptions->count() > 0)
                                        <div class="mt-3 text-sm text-gray-600 dark:text-gray-400">
                                            <span class="font-medium flex items-center">
                                                <i class="fas fa-pills mr-1"></i>
                                                Prescriptions ({{ $report->prescriptions->count() }}):
                                            </span>
                                            <div class="mt-1 flex flex-wrap gap-1">
                                                @foreach($report->prescriptions->take(3) as $prescription)
                                                    <span class="inline-flex items-center px-2 py-1 rounded-md text-xs bg-blue-50 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300">
                                                        {{ $prescription->medication_name }}
                                                    </span>
                                                @endforeach
                                                @if($report->prescriptions->count() > 3)
                                                    <span class="inline-flex items-center px-2 py-1 rounded-md text-xs bg-gray-50 text-gray-600 dark:bg-gray-700 dark:text-gray-400">
                                                        +{{ $report->prescriptions->count() - 3 }} more
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    @endif
                                    @if($report->labTestRequests->count() > 0)
                                        <div class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                                            <span class="font-medium flex items-center">
                                                <i class="fas fa-flask mr-1"></i>
                                                Lab Tests ({{ $report->labTestRequests->count() }}):
                                            </span>
                                            <div class="mt-1 flex flex-wrap gap-1">
                                                @foreach($report->labTestRequests->take(2) as $labTest)
                                                    <span class="inline-flex items-center px-2 py-1 rounded-md text-xs bg-green-50 text-green-700 dark:bg-green-900/30 dark:text-green-300">
                                                        {{ $labTest->test_name }}
                                                    </span>
                                                @endforeach
                                                @if($report->labTestRequests->count() > 2)
                                                    <span class="inline-flex items-center px-2 py-1 rounded-md text-xs bg-gray-50 text-gray-600 dark:bg-gray-700 dark:text-gray-400">
                                                        +{{ $report->labTestRequests->count() - 2 }} more
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    @endif
                                </div>
                                <div class="flex flex-col space-y-2 ml-4">
                                    <a href="{{ route('doctor.medical-reports.show', $report) }}" 
                                       class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-200 px-3 py-1 rounded-md border border-blue-300 dark:border-blue-600 hover:border-blue-400 dark:hover:border-blue-500 transition text-sm text-center inline-flex items-center justify-center"
                                       target="_blank"
                                       rel="noopener noreferrer">
                                        <i class="fas fa-eye mr-1"></i>
                                        View
                                    </a>
                                    @if($report->doctor_id === auth()->user()->doctor->id)
                                        <a href="{{ route('doctor.medical-reports.edit', $report) }}" 
                                           class="text-green-600 hover:text-green-800 dark:text-green-400 dark:hover:text-green-200 px-3 py-1 rounded-md border border-green-300 dark:border-green-600 hover:border-green-400 dark:hover:border-green-500 transition text-sm text-center inline-flex items-center justify-center"
                                           target="_blank"
                                           rel="noopener noreferrer">
                                            <i class="fas fa-edit mr-1"></i>
                                            Edit
                                        </a>
                                    @else
                                        <span class="text-gray-400 dark:text-gray-500 px-3 py-1 rounded-md border border-gray-300 dark:border-gray-600 text-sm text-center inline-flex items-center justify-center">
                                            <i class="fas fa-lock mr-1"></i>
                                            Read Only
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8">
                    <div class="text-gray-400 dark:text-gray-500 mb-4">
                        <i class="fas fa-file-medical text-4xl"></i>
                    </div>
                    <p class="text-gray-600 dark:text-gray-400 mb-4">No medical reports available for this patient.</p>
                    <a href="{{ route('doctor.medical-reports.create', ['patient_id' => $appointment->patient_id]) }}" class="bg-blue-600 text-white px-6 py-2 rounded-lg font-medium hover:bg-blue-700 transition inline-flex items-center" target="_blank" rel="noopener noreferrer">
                        <i class="fas fa-plus mr-2"></i>Create New Report
                    </a>
                </div>
            @endif
            </div>
        </div>
    </div>

    <!-- Lab Test Results Section -->
    <div class="mt-8 bg-white dark:bg-gray-800 shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <div class="flex justify-between items-center cursor-pointer" onclick="toggleSection('lab-test-results')">
                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">
                    <i class="fas fa-vial text-purple-600 mr-2"></i>
                    Lab Test Results
                </h3>
                <div class="flex items-center space-x-3">
                    <div class="flex items-center text-sm text-gray-500 dark:text-gray-400">
                        <i class="fas fa-info-circle mr-2"></i>
                        Access-controlled results only
                    </div>
                    <i class="fas fa-chevron-down text-gray-400 dark:text-gray-500 transition-transform duration-200" id="lab-test-results-icon"></i>
                </div>
            </div>
            <div id="lab-test-results-content" class="mt-4">
            
            @if($labTestRequests->count() > 0)
                <div class="space-y-4">
                    @foreach($labTestRequests as $labTest)
                        <div class="border border-gray-200 dark:border-gray-600 rounded-lg p-4 hover:border-purple-300 dark:hover:border-purple-500 transition">
                            <div class="flex justify-between items-start">
                                <div class="flex-1">
                                    <div class="flex items-center gap-3 mb-2">
                                        <h4 class="font-semibold text-gray-800 dark:text-white">
                                            {{ $labTest->test_name }}
                                        </h4>
                                        @if($labTest->doctor_id !== auth()->user()->doctor->id)
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                                <i class="fas fa-share-alt mr-1"></i>
                                                Shared Access
                                            </span>
                                        @endif
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium 
                                            @if($labTest->status === 'completed') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                            @elseif($labTest->status === 'pending') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                                            @elseif($labTest->status === 'scheduled') bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200
                                            @else bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200 @endif">
                                            <i class="fas fa-circle text-xs mr-1"></i>
                                            {{ ucfirst($labTest->status) }}
                                        </span>
                                        @if($labTest->priority && $labTest->priority !== 'routine')
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium 
                                                @if($labTest->priority === 'stat') bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200
                                                @elseif($labTest->priority === 'urgent') bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-200
                                                @else bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200 @endif">
                                                <i class="fas fa-exclamation mr-1"></i>
                                                {{ ucfirst($labTest->priority) }}
                                            </span>
                                        @endif
                                    </div>
                                    <div class="flex items-center gap-4 text-sm text-gray-600 dark:text-gray-400 mb-3">
                                        <span class="flex items-center">
                                            <i class="fas fa-calendar mr-1"></i>
                                            Ordered: {{ $labTest->created_at->format('M d, Y') }}
                                        </span>
                                        <span class="flex items-center">
                                            <i class="fas fa-user-md mr-1"></i>
                                            {{ $labTest->doctor->user->name }}
                                        </span>
                                        @if($labTest->laboratory)
                                            <span class="flex items-center">
                                                <i class="fas fa-building mr-1"></i>
                                                {{ $labTest->laboratory->name }}
                                            </span>
                                        @endif
                                        @if($labTest->completed_at)
                                            <span class="flex items-center">
                                                <i class="fas fa-check-circle text-green-500 mr-1"></i>
                                                Completed: {{ $labTest->completed_at->format('M d, Y') }}
                                            </span>
                                        @endif
                                    </div>

                                    <!-- Test Description and Notes -->
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-600 dark:text-gray-400">
                                        @if($labTest->test_description)
                                            <div>
                                                <span class="font-medium">Test Description:</span>
                                                <p class="mt-1">{{ Str::limit($labTest->test_description, 100) }}</p>
                                            </div>
                                        @endif
                                        @if($labTest->clinical_notes)
                                            <div>
                                                <span class="font-medium">Clinical Notes:</span>
                                                <p class="mt-1">{{ Str::limit($labTest->clinical_notes, 100) }}</p>
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Test Results (if completed) -->
                                    @if($labTest->status === 'completed')
                                        <div class="mt-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-4">
                                            <h5 class="font-medium text-green-900 dark:text-green-100 mb-2 flex items-center">
                                                <i class="fas fa-file-medical mr-2"></i>
                                                Test Results
                                            </h5>
                                            @if($labTest->test_results)
                                                <div class="text-sm text-green-800 dark:text-green-200">
                                                    @if(is_array($labTest->test_results))
                                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                                            @foreach($labTest->test_results as $key => $value)
                                                                <div class="flex justify-between">
                                                                    <span class="font-medium">{{ ucfirst($key) }}:</span>
                                                                    <span>{{ $value }}</span>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    @else
                                                        <p class="whitespace-pre-wrap">{{ $labTest->test_results }}</p>
                                                    @endif
                                                </div>
                                            @endif
                                            @if($labTest->result_notes)
                                                <div class="mt-3 text-sm text-green-800 dark:text-green-200">
                                                    <span class="font-medium">Additional Notes:</span>
                                                    <p class="mt-1">{{ $labTest->result_notes }}</p>
                                                </div>
                                            @endif
                                            @if($labTest->results_file_path)
                                                <div class="mt-3">
                                                    <a href="{{ asset('storage/' . $labTest->results_file_path) }}" 
                                                       target="_blank"
                                                       class="inline-flex items-center px-3 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-md transition-colors duration-200">
                                                        <i class="fas fa-file-pdf mr-2"></i>
                                                        View Results PDF
                                                    </a>
                                                </div>
                                            @endif
                                        </div>
                                    @elseif($labTest->status === 'pending')
                                        <div class="mt-4 bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg p-3">
                                            <p class="text-sm text-yellow-800 dark:text-yellow-200">
                                                <i class="fas fa-clock mr-2"></i>
                                                Test is pending. Results will be available once completed.
                                            </p>
                                        </div>
                                    @elseif($labTest->status === 'scheduled')
                                        <div class="mt-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-3">
                                            <p class="text-sm text-blue-800 dark:text-blue-200">
                                                <i class="fas fa-calendar-check mr-2"></i>
                                                Test has been scheduled. 
                                                @if($labTest->scheduled_at)
                                                    Appointment on {{ $labTest->scheduled_at->format('M d, Y g:i A') }}
                                                @endif
                                            </p>
                                        </div>
                                    @endif

                                    <!-- Request Information -->
                                    @if($labTest->request_number)
                                        <div class="mt-3 text-xs text-gray-500 dark:text-gray-400">
                                            <span class="font-medium">Request Number:</span> {{ $labTest->request_number }}
                                            @if($labTest->medicalReport)
                                                | <span class="font-medium">Medical Report:</span> 
                                                <a href="{{ route('doctor.medical-reports.show', $labTest->medicalReport) }}" 
                                                   class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300">
                                                    {{ Str::limit($labTest->medicalReport->title, 30) }}
                                                </a>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                                <div class="flex flex-col space-y-2 ml-4">
                                    @if($labTest->status === 'completed')
                                        <span class="text-green-600 dark:text-green-400 px-3 py-1 rounded-md border border-green-300 dark:border-green-600 text-sm text-center inline-flex items-center justify-center">
                                            <i class="fas fa-check-circle mr-1"></i>
                                            Completed
                                        </span>
                                    @elseif($labTest->status === 'pending')
                                        <span class="text-yellow-600 dark:text-yellow-400 px-3 py-1 rounded-md border border-yellow-300 dark:border-yellow-600 text-sm text-center inline-flex items-center justify-center">
                                            <i class="fas fa-clock mr-1"></i>
                                            Pending
                                        </span>
                                    @elseif($labTest->status === 'scheduled')
                                        <span class="text-blue-600 dark:text-blue-400 px-3 py-1 rounded-md border border-blue-300 dark:border-blue-600 text-sm text-center inline-flex items-center justify-center">
                                            <i class="fas fa-calendar-check mr-1"></i>
                                            Scheduled
                                        </span>
                                    @endif
                                    @if($labTest->doctor_id !== auth()->user()->doctor->id)
                                        <span class="text-gray-400 dark:text-gray-500 px-3 py-1 rounded-md border border-gray-300 dark:border-gray-600 text-xs text-center inline-flex items-center justify-center">
                                            <i class="fas fa-eye mr-1"></i>
                                            View Only
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                <!-- Summary Statistics -->
                <div class="mt-6 grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                        <div class="flex items-center">
                            <i class="fas fa-vial text-purple-500 text-xl mr-3"></i>
                            <div>
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Tests</p>
                                <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ $labTestRequests->count() }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-green-50 dark:bg-green-900/20 rounded-lg p-4">
                        <div class="flex items-center">
                            <i class="fas fa-check-circle text-green-500 text-xl mr-3"></i>
                            <div>
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Completed</p>
                                <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ $labTestRequests->where('status', 'completed')->count() }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-yellow-50 dark:bg-yellow-900/20 rounded-lg p-4">
                        <div class="flex items-center">
                            <i class="fas fa-clock text-yellow-500 text-xl mr-3"></i>
                            <div>
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Pending</p>
                                <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ $labTestRequests->where('status', 'pending')->count() }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-blue-50 dark:bg-blue-900/20 rounded-lg p-4">
                        <div class="flex items-center">
                            <i class="fas fa-share-alt text-blue-500 text-xl mr-3"></i>
                            <div>
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Shared Access</p>
                                <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ $labTestRequests->where('doctor_id', '!=', auth()->user()->doctor->id)->count() }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="text-center py-8">
                    <div class="text-gray-400 dark:text-gray-500 mb-4">
                        <i class="fas fa-vial text-4xl"></i>
                    </div>
                    <p class="text-gray-600 dark:text-gray-400 mb-4">No lab test results available for this patient.</p>
                    <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4 max-w-md mx-auto">
                        <div class="flex items-start">
                            <i class="fas fa-info-circle text-blue-600 dark:text-blue-400 mt-1 mr-3"></i>
                            <div class="text-left">
                                <h5 class="font-medium text-blue-900 dark:text-blue-100">Access Information</h5>
                                <p class="text-sm text-blue-700 dark:text-blue-300 mt-1">
                                    You can only view lab tests that you have ordered or been granted access to by the patient.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            </div>
        </div>
    </div>

    <!-- Patient Health Profile Section -->
    <div class="mt-8 bg-white dark:bg-gray-800 shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <div class="flex justify-between items-center cursor-pointer" onclick="toggleSection('health-profile')">
                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">
                    <i class="fas fa-user-md text-green-600 mr-2"></i>
                    Patient Health Profile
                </h3>
                <div class="flex items-center space-x-3">
                    @if(!$hasHealthProfileAccess)
                        <div class="flex items-center text-sm text-amber-600 dark:text-amber-400">
                            <i class="fas fa-lock mr-2"></i>
                            Access Restricted
                        </div>
                    @endif
                    <i class="fas fa-chevron-down text-gray-400 dark:text-gray-500 transition-transform duration-200" id="health-profile-icon"></i>
                </div>
            </div>
            <div id="health-profile-content" class="mt-4">
            
            @if(!$hasHealthProfileAccess)
                <!-- No Permission Message -->
                <div class="text-center py-12">
                    <div class="text-gray-400 dark:text-gray-500 mb-4">
                        <i class="fas fa-shield-alt text-6xl"></i>
                    </div>
                    <h4 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Health Profile Access Required</h4>
                    <p class="text-gray-600 dark:text-gray-400 mb-6 max-w-md mx-auto">
                        This patient has not granted you permission to view their health profile. The patient can grant access through their appointment or health profile settings.
                    </p>
                    <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4 max-w-lg mx-auto">
                        <div class="flex items-start">
                            <i class="fas fa-info-circle text-blue-600 dark:text-blue-400 mt-1 mr-3"></i>
                            <div class="text-left">
                                <h5 class="font-medium text-blue-900 dark:text-blue-100">Why is access restricted?</h5>
                                <p class="text-sm text-blue-700 dark:text-blue-300 mt-1">
                                    Patient health profiles contain sensitive medical information. Patients control who can access their health data to ensure privacy and compliance with medical regulations.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            @elseif(!$healthProfile)
                <!-- No Health Profile -->
                <div class="text-center py-12">
                    <div class="text-gray-400 dark:text-gray-500 mb-4">
                        <i class="fas fa-heart-broken text-5xl"></i>
                    </div>
                    <h4 class="text-lg font-medium text-gray-900 dark:text-white mb-2">No Health Profile</h4>
                    <p class="text-gray-600 dark:text-gray-400">
                        This patient has not created a health profile yet.
                    </p>
                </div>
            @else
                <!-- Health Profile Content -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Basic Health Information -->
                    <div class="space-y-6">
                        <!-- Basic Health Data -->
                        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-gray-700 dark:to-gray-600 rounded-lg p-4">
                            <h4 class="font-semibold text-gray-900 dark:text-white mb-3 flex items-center">
                                <i class="fas fa-heart text-red-600 dark:text-red-400 mr-2"></i>
                                Basic Health Information
                            </h4>
                            <div class="grid grid-cols-2 gap-4 text-sm">
                                @if($healthProfile->blood_type)
                                    <div>
                                        <span class="font-medium text-gray-600 dark:text-gray-400">Blood Type:</span>
                                        <p class="text-gray-900 dark:text-white">{{ $healthProfile->blood_type }}</p>
                                    </div>
                                @endif
                                @if($healthProfile->height)
                                    <div>
                                        <span class="font-medium text-gray-600 dark:text-gray-400">Height:</span>
                                        <p class="text-gray-900 dark:text-white">{{ $healthProfile->height }} cm</p>
                                    </div>
                                @endif
                                @if($healthProfile->weight)
                                    <div>
                                        <span class="font-medium text-gray-600 dark:text-gray-400">Weight:</span>
                                        <p class="text-gray-900 dark:text-white">{{ $healthProfile->weight }} kg</p>
                                    </div>
                                @endif
                                @if($healthProfile->height && $healthProfile->weight)
                                    @php
                                        $bmi = round($healthProfile->weight / (($healthProfile->height / 100) ** 2), 1);
                                        $bmiCategory = $bmi < 18.5 ? 'Underweight' : ($bmi < 25 ? 'Normal' : ($bmi < 30 ? 'Overweight' : 'Obese'));
                                        $bmiColor = $bmi < 18.5 ? 'text-blue-600' : ($bmi < 25 ? 'text-green-600' : ($bmi < 30 ? 'text-yellow-600' : 'text-red-600'));
                                    @endphp
                                    <div class="col-span-2">
                                        <span class="font-medium text-gray-600 dark:text-gray-400">BMI:</span>
                                        <p class="text-gray-900 dark:text-white">
                                            {{ $bmi }} <span class="text-sm {{ $bmiColor }}">{{ $bmiCategory }}</span>
                                        </p>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Medical Information -->
                        <div class="bg-gradient-to-r from-green-50 to-emerald-50 dark:from-gray-700 dark:to-gray-600 rounded-lg p-4">
                            <h4 class="font-semibold text-gray-900 dark:text-white mb-3 flex items-center">
                                <i class="fas fa-notes-medical text-green-600 dark:text-green-400 mr-2"></i>
                                Medical Information
                            </h4>
                            <div class="space-y-3">
                                @if($healthProfile->allergies)
                                    <div>
                                        <span class="font-medium text-gray-600 dark:text-gray-400 block mb-1">Allergies:</span>
                                        <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded p-2">
                                            @if(str_contains($healthProfile->allergies, ';'))
                                                <ul class="text-sm text-gray-800 dark:text-gray-200 space-y-1">
                                                    @foreach(array_filter(array_map('trim', explode(';', $healthProfile->allergies))) as $allergy)
                                                        <li class="flex items-center">
                                                            <i class="fas fa-exclamation-triangle text-red-500 mr-2 text-xs"></i>
                                                            {{ $allergy }}
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            @else
                                                <p class="text-sm text-gray-800 dark:text-gray-200 flex items-center">
                                                    <i class="fas fa-exclamation-triangle text-red-500 mr-2 text-xs"></i>
                                                    {{ $healthProfile->allergies }}
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                @endif

                                @if($healthProfile->medications)
                                    <div>
                                        <span class="font-medium text-gray-600 dark:text-gray-400 block mb-1">Current Medications:</span>
                                        <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded p-2">
                                            @if(str_contains($healthProfile->medications, ';'))
                                                <ul class="text-sm text-gray-800 dark:text-gray-200 space-y-1">
                                                    @foreach(array_filter(array_map('trim', explode(';', $healthProfile->medications))) as $medication)
                                                        <li class="flex items-center">
                                                            <i class="fas fa-capsules text-blue-500 mr-2 text-xs"></i>
                                                            {{ $medication }}
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            @else
                                                <p class="text-sm text-gray-800 dark:text-gray-200 flex items-center">
                                                    <i class="fas fa-capsules text-blue-500 mr-2 text-xs"></i>
                                                    {{ $healthProfile->medications }}
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                @endif

                                @if($healthProfile->medical_conditions)
                                    <div>
                                        <span class="font-medium text-gray-600 dark:text-gray-400 block mb-1">Medical Conditions:</span>
                                        <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded p-2">
                                            @if(str_contains($healthProfile->medical_conditions, ';'))
                                                <ul class="text-sm text-gray-800 dark:text-gray-200 space-y-1">
                                                    @foreach(array_filter(array_map('trim', explode(';', $healthProfile->medical_conditions))) as $condition)
                                                        <li class="flex items-center">
                                                            <i class="fas fa-notes-medical text-yellow-500 mr-2 text-xs"></i>
                                                            {{ $condition }}
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            @else
                                                <p class="text-sm text-gray-800 dark:text-gray-200 flex items-center">
                                                    <i class="fas fa-notes-medical text-yellow-500 mr-2 text-xs"></i>
                                                    {{ $healthProfile->medical_conditions }}
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                @endif

                                @if($healthProfile->family_history)
                                    <div>
                                        <span class="font-medium text-gray-600 dark:text-gray-400 block mb-1">Family History:</span>
                                        <div class="bg-purple-50 dark:bg-purple-900/20 border border-purple-200 dark:border-purple-800 rounded p-2">
                                            <p class="text-sm text-gray-800 dark:text-gray-200">{{ $healthProfile->family_history }}</p>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Lifestyle & Contact Information -->
                    <div class="space-y-6">
                        <!-- Lifestyle Information -->
                        <div class="bg-gradient-to-r from-orange-50 to-amber-50 dark:from-gray-700 dark:to-gray-600 rounded-lg p-4">
                            <h4 class="font-semibold text-gray-900 dark:text-white mb-3 flex items-center">
                                <i class="fas fa-running text-orange-600 dark:text-orange-400 mr-2"></i>
                                Lifestyle Information
                            </h4>
                            <div class="space-y-3 text-sm">
                                @if($healthProfile->exercise_frequency)
                                    <div class="flex items-center justify-between">
                                        <span class="font-medium text-gray-600 dark:text-gray-400">Exercise Frequency:</span>
                                        <span class="text-gray-900 dark:text-white capitalize">{{ str_replace('_', ' ', $healthProfile->exercise_frequency) }}</span>
                                    </div>
                                @endif
                                <div class="flex items-center justify-between">
                                    <span class="font-medium text-gray-600 dark:text-gray-400">Smoker:</span>
                                    <span class="text-gray-900 dark:text-white">{{ $healthProfile->is_smoker ? 'Yes' : 'No' }}</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="font-medium text-gray-600 dark:text-gray-400">Alcohol Consumer:</span>
                                    <span class="text-gray-900 dark:text-white">{{ $healthProfile->is_alcohol_consumer ? 'Yes' : 'No' }}</span>
                                </div>
                                @if($healthProfile->dietary_restrictions)
                                    <div>
                                        <span class="font-medium text-gray-600 dark:text-gray-400 block mb-1">Dietary Restrictions:</span>
                                        <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded p-2">
                                            @if(str_contains($healthProfile->dietary_restrictions, ';'))
                                                <ul class="text-sm text-gray-800 dark:text-gray-200 space-y-1">
                                                    @foreach(array_filter(array_map('trim', explode(';', $healthProfile->dietary_restrictions))) as $restriction)
                                                        <li class="flex items-center">
                                                            <i class="fas fa-utensils text-green-500 mr-2 text-xs"></i>
                                                            {{ $restriction }}
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            @else
                                                <p class="text-sm text-gray-800 dark:text-gray-200 flex items-center">
                                                    <i class="fas fa-utensils text-green-500 mr-2 text-xs"></i>
                                                    {{ $healthProfile->dietary_restrictions }}
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                @endif
                                @if($healthProfile->lifestyle_notes)
                                    <div>
                                        <span class="font-medium text-gray-600 dark:text-gray-400 block mb-1">Lifestyle Notes:</span>
                                        <div class="bg-gray-50 dark:bg-gray-900/20 border border-gray-200 dark:border-gray-800 rounded p-2">
                                            <p class="text-sm text-gray-800 dark:text-gray-200">{{ $healthProfile->lifestyle_notes }}</p>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Emergency Contact -->
                        @if($healthProfile->emergency_contact_name)
                            <div class="bg-gradient-to-r from-red-50 to-pink-50 dark:from-gray-700 dark:to-gray-600 rounded-lg p-4">
                                <h4 class="font-semibold text-gray-900 dark:text-white mb-3 flex items-center">
                                    <i class="fas fa-phone-alt text-red-600 dark:text-red-400 mr-2"></i>
                                    Emergency Contact
                                </h4>
                                <div class="space-y-2 text-sm">
                                    <div class="flex items-center justify-between">
                                        <span class="font-medium text-gray-600 dark:text-gray-400">Name:</span>
                                        <span class="text-gray-900 dark:text-white">{{ $healthProfile->emergency_contact_name }}</span>
                                    </div>
                                    @if($healthProfile->emergency_contact_phone)
                                        <div class="flex items-center justify-between">
                                            <span class="font-medium text-gray-600 dark:text-gray-400">Phone:</span>
                                            <span class="text-gray-900 dark:text-white">{{ $healthProfile->emergency_contact_phone }}</span>
                                        </div>
                                    @endif
                                    @if($healthProfile->emergency_contact_relationship)
                                        <div class="flex items-center justify-between">
                                            <span class="font-medium text-gray-600 dark:text-gray-400">Relationship:</span>
                                            <span class="text-gray-900 dark:text-white">{{ $healthProfile->emergency_contact_relationship }}</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endif

                        <!-- Insurance Information -->
                        @if($healthProfile->insurance_provider)
                            <div class="bg-gradient-to-r from-blue-50 to-cyan-50 dark:from-gray-700 dark:to-gray-600 rounded-lg p-4">
                                <h4 class="font-semibold text-gray-900 dark:text-white mb-3 flex items-center">
                                    <i class="fas fa-shield-alt text-blue-600 dark:text-blue-400 mr-2"></i>
                                    Insurance Information
                                </h4>
                                <div class="space-y-2 text-sm">
                                    <div class="flex items-center justify-between">
                                        <span class="font-medium text-gray-600 dark:text-gray-400">Provider:</span>
                                        <span class="text-gray-900 dark:text-white">{{ $healthProfile->insurance_provider }}</span>
                                    </div>
                                    @if($healthProfile->insurance_policy_number)
                                        <div class="flex items-center justify-between">
                                            <span class="font-medium text-gray-600 dark:text-gray-400">Policy Number:</span>
                                            <span class="text-gray-900 dark:text-white">{{ $healthProfile->insurance_policy_number }}</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endif

                        <!-- Additional Notes -->
                        @if($healthProfile->additional_notes)
                            <div class="bg-gradient-to-r from-purple-50 to-indigo-50 dark:from-gray-700 dark:to-gray-600 rounded-lg p-4">
                                <h4 class="font-semibold text-gray-900 dark:text-white mb-3 flex items-center">
                                    <i class="fas fa-sticky-note text-purple-600 dark:text-purple-400 mr-2"></i>
                                    Additional Notes
                                </h4>
                                <div class="text-sm text-gray-800 dark:text-gray-200">
                                    {{ $healthProfile->additional_notes }}
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @endif            </div>
        </div>
    </div>

<!-- Complete Appointment Modal -->
<div id="completeModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white dark:bg-gray-800">
        <div class="mt-3 text-center">
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-green-100 dark:bg-green-900">
                <i class="fas fa-check-double text-green-600 dark:text-green-400 text-xl"></i>
            </div>
            <h3 class="text-lg font-medium text-gray-900 dark:text-white mt-5">Complete Appointment</h3>
            <div class="mt-2 px-7 py-3">
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Add any completion notes (optional):
                </p>
            </div>
            
            <form action="{{ route('doctor.appointments.complete', $appointment) }}" method="POST">
                @csrf
                @method('PATCH')
                <div class="px-7 py-3">
                    <textarea name="completion_notes" rows="3"
                              class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md text-sm"
                              placeholder="Enter completion notes..."></textarea>
                </div>
                
                <div class="items-center px-4 py-3">
                    <button type="submit" 
                            class="px-4 py-2 bg-purple-600 text-white text-base font-medium rounded-md w-full shadow-sm hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-300">
                        Mark as Completed
                    </button>
                    <button type="button" onclick="hideCompleteModal()" 
                            class="mt-3 px-4 py-2 bg-gray-300 dark:bg-gray-600 text-gray-800 dark:text-white text-base font-medium rounded-md w-full shadow-sm hover:bg-gray-400 dark:hover:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-gray-300">
                        Close
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Cancel Appointment Modal -->
<div id="cancelModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white dark:bg-gray-800">
        <div class="mt-3 text-center">
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 dark:bg-red-900">
                <i class="fas fa-exclamation-triangle text-red-600 dark:text-red-400 text-xl"></i>
            </div>
            <h3 class="text-lg font-medium text-gray-900 dark:text-white mt-5">Cancel Appointment</h3>
            <div class="mt-2 px-7 py-3">
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Please provide a reason for cancelling this appointment:
                </p>
            </div>
            
            <form action="{{ route('doctor.appointments.cancel', $appointment) }}" method="POST">
                @csrf
                @method('PATCH')
                <div class="px-7 py-3">
                    <textarea name="cancellation_reason" rows="3" required
                              class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md text-sm"
                              placeholder="Enter cancellation reason..."></textarea>
                </div>
                
                <div class="items-center px-4 py-3">
                    <button type="submit" 
                            class="px-4 py-2 bg-red-600 text-white text-base font-medium rounded-md w-full shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-300">
                        Cancel Appointment
                    </button>
                    <button type="button" onclick="hideCancelModal()" 
                            class="mt-3 px-4 py-2 bg-gray-300 dark:bg-gray-600 text-gray-800 dark:text-white text-base font-medium rounded-md w-full shadow-sm hover:bg-gray-400 dark:hover:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-gray-300">
                        Close
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
// Toggle section visibility
function toggleSection(sectionId) {
    const content = document.getElementById(sectionId + '-content');
    const icon = document.getElementById(sectionId + '-icon');
    
    if (content.style.display === 'none') {
        content.style.display = 'block';
        icon.classList.remove('fa-chevron-right');
        icon.classList.add('fa-chevron-down');
        // Store the state in localStorage
        localStorage.setItem('appointment-section-' + sectionId, 'open');
    } else {
        content.style.display = 'none';
        icon.classList.remove('fa-chevron-down');
        icon.classList.add('fa-chevron-right');
        // Store the state in localStorage
        localStorage.setItem('appointment-section-' + sectionId, 'closed');
    }
}

// Initialize sections based on saved state or default to open for important sections
document.addEventListener('DOMContentLoaded', function() {
    const sections = [
        'patient-info', 
        'appointment-details', 
        'payment-info', 
        'instructions-notes', 
        'actions', 
        'medical-reports', 
        'lab-test-results', 
        'health-profile'
    ];
    
    // Default open sections (most important for doctors)
    const defaultOpenSections = ['patient-info', 'appointment-details', 'actions'];
    
    sections.forEach(function(sectionId) {
        const content = document.getElementById(sectionId + '-content');
        const icon = document.getElementById(sectionId + '-icon');
        const savedState = localStorage.getItem('appointment-section-' + sectionId);
        
        if (content && icon) {
            // Use saved state, or default state
            const shouldBeOpen = savedState === 'open' || 
                                 (savedState === null && defaultOpenSections.includes(sectionId));
            
            if (shouldBeOpen) {
                content.style.display = 'block';
                icon.classList.remove('fa-chevron-right');
                icon.classList.add('fa-chevron-down');
            } else {
                content.style.display = 'none';
                icon.classList.remove('fa-chevron-down');
                icon.classList.add('fa-chevron-right');
            }
        }
    });
});

// Add keyboard accessibility
document.addEventListener('keydown', function(e) {
    if (e.key === 'Enter' || e.key === ' ') {
        const target = e.target;
        if (target.getAttribute('onclick') && target.getAttribute('onclick').includes('toggleSection')) {
            e.preventDefault();
            target.click();
        }
    }
});

// Modal functions
function showCompleteModal() {
    document.getElementById('completeModal').classList.remove('hidden');
}

function hideCompleteModal() {
    document.getElementById('completeModal').classList.add('hidden');
}

function showCancelModal() {
    document.getElementById('cancelModal').classList.remove('hidden');
}

function hideCancelModal() {
    document.getElementById('cancelModal').classList.add('hidden');
}
</script>
@endpush

