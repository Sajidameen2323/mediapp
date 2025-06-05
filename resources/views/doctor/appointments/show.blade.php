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
                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white mb-4">
                    <i class="fas fa-user text-blue-600 mr-2"></i>
                    Patient Information
                </h3>
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

        <!-- Appointment Details -->
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white mb-4">
                    <i class="fas fa-calendar-check text-green-600 mr-2"></i>
                    Appointment Details
                </h3>
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
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white font-medium">{{ $appointment->appointment_date->format('l, F d, Y') }}</dd>
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

        <!-- Payment Information -->
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white mb-4">
                    <i class="fas fa-credit-card text-purple-600 mr-2"></i>
                    Payment Information
                </h3>
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
                        <dd class="text-lg font-bold text-gray-900 dark:text-white">${{ number_format($appointment->total_amount, 2) }}</dd>
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
                        <dd class="text-lg font-bold text-green-600 dark:text-green-400">${{ number_format($appointment->paid_amount, 2) }}</dd>
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

        <!-- Special Instructions & Notes -->
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white mb-4">
                    <i class="fas fa-sticky-note text-orange-600 mr-2"></i>
                    Instructions & Notes
                </h3>
                
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

    <!-- Action Buttons -->
    <div class="mt-8 bg-white dark:bg-gray-800 shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white mb-4">
                <i class="fas fa-cogs text-gray-600 mr-2"></i>
                Actions
            </h3>
            
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

    <!-- Medical Reports Section -->
    <div class="mt-8 bg-white dark:bg-gray-800 shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">
                    <i class="fas fa-file-medical text-blue-600 mr-2"></i>
                    Medical Reports
                </h3>
                <button onclick="openCreateReportModal()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors duration-200">
                    <i class="fas fa-plus mr-2"></i>Create Report
                </button>
            </div>
            
            @php
                // Get medical reports for this patient from this doctor
                $medicalReports = \App\Models\MedicalReport::where('doctor_id', auth()->user()->doctor->id)
                    ->where('patient_id', $appointment->patient_id)
                    ->orderBy('consultation_date', 'desc')
                    ->get();
            @endphp

            @if($medicalReports->count() > 0)
                <div class="space-y-4">
                    @foreach($medicalReports as $report)
                        <div class="border border-gray-200 dark:border-gray-600 rounded-lg p-4 hover:border-blue-300 dark:hover:border-blue-500 transition">
                            <div class="flex justify-between items-start">
                                <div class="flex-1">
                                    <h4 class="font-semibold text-gray-800 dark:text-white mb-2">
                                        Report #{{ $report->id }} - {{ $report->consultation_date->format('M d, Y') }}
                                    </h4>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-600 dark:text-gray-400">
                                        <div>
                                            <span class="font-medium">Chief Complaint:</span>
                                            <p class="mt-1">{{ Str::limit($report->chief_complaint, 100) }}</p>
                                        </div>
                                        <div>
                                            <span class="font-medium">Diagnosis:</span>
                                            <p class="mt-1">{{ Str::limit($report->diagnosis, 100) }}</p>
                                        </div>
                                    </div>
                                    @if($report->prescription)
                                        <div class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                                            <span class="font-medium">Prescription:</span>
                                            <p class="mt-1">{{ Str::limit($report->prescription, 150) }}</p>
                                        </div>
                                    @endif
                                </div>
                                <div class="flex space-x-2 ml-4">
                                    <a href="{{ route('doctor.medical-reports.show', $report) }}" 
                                       class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-200 px-3 py-1 rounded-md border border-blue-300 dark:border-blue-600 hover:border-blue-400 dark:hover:border-blue-500 transition text-sm">
                                        View
                                    </a>
                                    <a href="{{ route('doctor.medical-reports.edit', $report) }}" 
                                       class="text-green-600 hover:text-green-800 dark:text-green-400 dark:hover:text-green-200 px-3 py-1 rounded-md border border-green-300 dark:border-green-600 hover:border-green-400 dark:hover:border-green-500 transition text-sm">
                                        Edit
                                    </a>
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
                    <p class="text-gray-600 dark:text-gray-400 mb-4">No medical reports found for this patient.</p>
                    <button onclick="openCreateReportModal()" class="bg-blue-600 text-white px-6 py-2 rounded-lg font-medium hover:bg-blue-700 transition">
                        Create First Report
                    </button>
                </div>
            @endif
        </div>
    </div>

    <!-- Patient Health Profile Section -->
    <div class="mt-8 bg-white dark:bg-gray-800 shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">
                    <i class="fas fa-user-md text-green-600 mr-2"></i>
                    Patient Health Profile
                </h3>
                @if(!$hasHealthProfileAccess)
                    <div class="flex items-center text-sm text-amber-600 dark:text-amber-400">
                        <i class="fas fa-lock mr-2"></i>
                        Access Restricted
                    </div>
                @endif
            </div>
            
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
            @endif
        </div>
    </div>
</div>

<!-- Create Medical Report Modal -->
<div id="createReportModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl w-full max-w-4xl max-h-[90vh] overflow-y-auto">
            <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-4">
                <div class="flex justify-between items-center">
                    <h3 class="text-xl font-bold text-white">Create Medical Report</h3>
                    <button onclick="closeCreateReportModal()" class="text-white hover:text-gray-200">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
            </div>
            
            <form action="{{ route('doctor.medical-reports.store') }}" method="POST" class="p-6">
                @csrf
                <input type="hidden" name="patient_id" value="{{ $appointment->patient_id }}">
                <input type="hidden" name="consultation_date" value="{{ $appointment->appointment_date }}">
                
                <div class="space-y-6">                    <!-- Patient Information (Read-only) -->
                    <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                        <h4 class="font-semibold text-gray-800 dark:text-white mb-2">Patient Information</h4>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                            <div>
                                <span class="font-medium text-gray-600 dark:text-gray-400">Name:</span>
                                <p class="text-gray-800 dark:text-white">{{ $appointment->patient->name }}</p>
                            </div>
                            <div>
                                <span class="font-medium text-gray-600 dark:text-gray-400">Phone:</span>
                                <p class="text-gray-800 dark:text-white">{{ $appointment->patient->phone_number }}</p>
                            </div>
                            <div>
                                <span class="font-medium text-gray-600 dark:text-gray-400">Appointment Date:</span>
                                <p class="text-gray-800 dark:text-white">{{ $appointment->appointment_date->format('M d, Y') }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Chief Complaint -->
                    <div>
                        <label for="chief_complaint" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Chief Complaint <span class="text-red-500">*</span>
                        </label>
                        <textarea 
                            name="chief_complaint" 
                            id="chief_complaint" 
                            rows="3" 
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Patient's main concern or reason for visit..."
                            required
                        ></textarea>
                    </div>

                    <!-- History of Present Illness -->
                    <div>
                        <label for="history_present_illness" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            History of Present Illness
                        </label>
                        <textarea 
                            name="history_present_illness" 
                            id="history_present_illness" 
                            rows="3" 
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Details about the current illness..."
                        ></textarea>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Past Medical History -->
                        <div>
                            <label for="past_medical_history" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Past Medical History
                            </label>
                            <textarea 
                                name="past_medical_history" 
                                id="past_medical_history" 
                                rows="3" 
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                placeholder="Previous medical conditions, surgeries, etc..."
                            ></textarea>
                        </div>

                        <!-- Family History -->
                        <div>
                            <label for="family_history" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Family History
                            </label>
                            <textarea 
                                name="family_history" 
                                id="family_history" 
                                rows="3" 
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                placeholder="Relevant family medical history..."
                            ></textarea>
                        </div>
                    </div>

                    <!-- Physical Examination -->
                    <div>
                        <label for="physical_examination" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Physical Examination <span class="text-red-500">*</span>
                        </label>
                        <textarea 
                            name="physical_examination" 
                            id="physical_examination" 
                            rows="4" 
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Physical examination findings..."
                            required
                        ></textarea>
                    </div>

                    <!-- Diagnosis -->
                    <div>
                        <label for="diagnosis" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Diagnosis <span class="text-red-500">*</span>
                        </label>
                        <textarea 
                            name="diagnosis" 
                            id="diagnosis" 
                            rows="3" 
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Primary and secondary diagnoses..."
                            required
                        ></textarea>
                    </div>

                    <!-- Prescription -->
                    <div>
                        <label for="prescription" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Prescription & Treatment Plan
                        </label>
                        <textarea 
                            name="prescription" 
                            id="prescription" 
                            rows="4" 
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Medications, dosages, treatment plan, and follow-up instructions..."
                        ></textarea>
                    </div>

                    <!-- Follow-up Instructions -->
                    <div>
                        <label for="follow_up_instructions" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Follow-up Instructions
                        </label>
                        <textarea 
                            name="follow_up_instructions" 
                            id="follow_up_instructions" 
                            rows="3" 
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            placeholder="When to return, what to watch for, lifestyle recommendations..."
                        ></textarea>
                    </div>
                </div>

                <div class="flex justify-end space-x-4 mt-6 pt-6 border-t border-gray-200 dark:border-gray-600">
                    <button type="button" onclick="closeCreateReportModal()" class="px-6 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 font-medium transition">
                        Cancel
                    </button>
                    <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium transition">
                        <i class="fas fa-save mr-2"></i>Create Report
                    </button>
                </div>
            </form>
        </div>
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

@section('scripts')
<script>
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

function openCreateReportModal() {
    document.getElementById('createReportModal').classList.remove('hidden');
}

function closeCreateReportModal() {
    document.getElementById('createReportModal').classList.add('hidden');
}
</script>
</div>
@endsection
