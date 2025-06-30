@extends('layouts.laboratory')

@section('title', 'Lab Appointment Details')

@section('content')
<div class="p-4 sm:p-6 lg:p-8">
    <!-- Header -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 mb-6">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">
                    Lab Appointment #{{ $labAppointment->id }}
                </h1>
                <div class="flex items-center space-x-4">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                        {{ $labAppointment->status === 'confirmed' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : '' }}
                        {{ $labAppointment->status === 'pending' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' : '' }}
                        {{ $labAppointment->status === 'rejected' ? 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' : '' }}
                        {{ $labAppointment->status === 'completed' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200' : '' }}
                        {{ $labAppointment->status === 'cancelled' ? 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200' : '' }}">
                        <i class="fas fa-circle mr-2 text-xs"></i>
                        {{ ucfirst($labAppointment->status) }}
                    </span>
                    <span class="text-sm text-gray-500 dark:text-gray-400">
                        Created {{ $labAppointment->created_at->diffForHumans() }}
                    </span>
                </div>
            </div>
            <div class="mt-4 lg:mt-0 flex space-x-3">
                <a href="{{ route('laboratory.appointments.index') }}" 
                   class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors duration-200">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Back to Appointments
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Appointment Details -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Patient Information -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                    <i class="fas fa-user mr-2 text-blue-500"></i>
                    Patient Information
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Full Name</label>
                        <p class="text-gray-900 dark:text-white">{{ $labAppointment->patient->name }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Email</label>
                        <p class="text-gray-900 dark:text-white">{{ $labAppointment->patient->email }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Phone</label>
                        <p class="text-gray-900 dark:text-white">{{ $labAppointment->patient->phone ?? 'Not provided' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Age</label>
                        <p class="text-gray-900 dark:text-white">
                            @if($labAppointment->patient->date_of_birth)
                                {{ $labAppointment->patient->date_of_birth->age }} years
                            @else
                                Not provided
                            @endif
                        </p>
                    </div>
                </div>
            </div>

            <!-- Lab Test Information -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                    <i class="fas fa-vial mr-2 text-green-500"></i>
                    Lab Test Details
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Test Name</label>
                        <p class="text-gray-900 dark:text-white font-medium">{{ $labAppointment->labTestRequest->test_name }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Test Type</label>
                        <p class="text-gray-900 dark:text-white">{{ $labAppointment->labTestRequest->test_type }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Prescribed By</label>
                        <p class="text-gray-900 dark:text-white">{{ $labAppointment->labTestRequest->doctor->name }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Priority</label>
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                            {{ $labAppointment->labTestRequest->priority === 'urgent' ? 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' : '' }}
                            {{ $labAppointment->labTestRequest->priority === 'normal' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : '' }}">
                            {{ ucfirst($labAppointment->labTestRequest->priority) }}
                        </span>
                    </div>
                </div>
                @if($labAppointment->labTestRequest->instructions)
                    <div class="mt-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Doctor's Instructions</label>
                        <p class="text-gray-900 dark:text-white bg-gray-50 dark:bg-gray-700 p-3 rounded-md">{{ $labAppointment->labTestRequest->instructions }}</p>
                    </div>
                @endif
            </div>

            <!-- Appointment Information -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                    <i class="fas fa-calendar-alt mr-2 text-purple-500"></i>
                    Appointment Details
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Appointment Date</label>
                        <p class="text-gray-900 dark:text-white">{{ $labAppointment->appointment_date->format('M d, Y') }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Time Slot</label>
                        <p class="text-gray-900 dark:text-white">
                            @if($labAppointment->start_time && $labAppointment->end_time)
                                {{ \Carbon\Carbon::parse($labAppointment->start_time)->format('g:i A') }} - 
                                {{ \Carbon\Carbon::parse($labAppointment->end_time)->format('g:i A') }}
                            @elseif($labAppointment->appointment_time)
                                {{ $labAppointment->appointment_time }}
                            @else
                                Not scheduled
                            @endif
                        </p>
                    </div>
                    @if($labAppointment->estimated_cost || $labAppointment->final_cost)
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Cost</label>
                            <div class="space-y-1">
                                @if($labAppointment->estimated_cost)
                                    <p class="text-sm text-gray-600 dark:text-gray-400">
                                        Estimated: ${{ number_format($labAppointment->estimated_cost, 2) }}
                                    </p>
                                @endif
                                @if($labAppointment->final_cost)
                                    <p class="text-gray-900 dark:text-white font-medium">
                                        Final: ${{ number_format($labAppointment->final_cost, 2) }}
                                    </p>
                                @endif
                            </div>
                        </div>
                    @endif
                    @if($labAppointment->requires_fasting)
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Fasting Required</label>
                            <p class="text-gray-900 dark:text-white">
                                <i class="fas fa-utensils mr-2 text-orange-500"></i>
                                {{ $labAppointment->fasting_hours ? $labAppointment->fasting_hours . ' hours' : 'Yes' }}
                            </p>
                        </div>
                    @endif
                    @if($labAppointment->confirmed_at)
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Confirmed At</label>
                            <p class="text-gray-900 dark:text-white">{{ $labAppointment->confirmed_at->format('M d, Y g:i A') }}</p>
                        </div>
                    @endif
                    @if($labAppointment->completed_at)
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Completed At</label>
                            <p class="text-gray-900 dark:text-white">{{ $labAppointment->completed_at->format('M d, Y g:i A') }}</p>
                        </div>
                    @endif
                </div>
                @if($labAppointment->lab_instructions)
                    <div class="mt-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Lab Instructions</label>
                        <p class="text-gray-900 dark:text-white bg-gray-50 dark:bg-gray-700 p-3 rounded-md">{{ $labAppointment->lab_instructions }}</p>
                    </div>
                @endif
            </div>

            <!-- Results Section -->
            @if($labAppointment->status === 'completed')
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                        <i class="fas fa-file-medical mr-2 text-blue-500"></i>
                        Test Results
                    </h2>
                    
                    <!-- Results Content -->
                    <div class="space-y-6">
                        <!-- Text Results -->
                        @if($labAppointment->test_results)
                            <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-green-900 dark:text-green-100 mb-3 flex items-center">
                                    <i class="fas fa-chart-line mr-2"></i>
                                    Test Results
                                </h3>
                                <div class="prose dark:prose-invert max-w-none">
                                    <p class="text-green-800 dark:text-green-200 leading-relaxed whitespace-pre-wrap">{{ $labAppointment->test_results }}</p>
                                </div>
                            </div>
                        @endif

                        <!-- Additional Notes -->
                        @if($labAppointment->result_notes)
                            <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-blue-900 dark:text-blue-100 mb-3 flex items-center">
                                    <i class="fas fa-sticky-note mr-2"></i>
                                    Additional Notes
                                </h3>
                                <p class="text-blue-800 dark:text-blue-200 leading-relaxed whitespace-pre-wrap">{{ $labAppointment->result_notes }}</p>
                            </div>
                        @endif

                        <!-- File Download -->
                        @if($labAppointment->labTestRequest->results_file_path || $labAppointment->results_file_path)
                            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                                    <i class="fas fa-file-pdf mr-2 text-red-500"></i>
                                    Results Document
                                </h3>
                                <div class="flex items-center justify-between p-4 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-600 rounded-lg">
                                    <div class="flex items-center">
                                        <i class="fas fa-file-pdf text-red-500 text-2xl mr-3"></i>
                                        <div>
                                            <p class="font-medium text-gray-900 dark:text-white">Laboratory Results.pdf</p>
                                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                                Uploaded {{ $labAppointment->result_uploaded_at ? $labAppointment->result_uploaded_at->format('M d, Y g:i A') : $labAppointment->updated_at->format('M d, Y g:i A') }}
                                            </p>
                                        </div>
                                    </div>
                                    <div class="flex space-x-2">
                                        @php
                                            $filePath = $labAppointment->labTestRequest->results_file_path ?? $labAppointment->results_file_path;
                                        @endphp
                                        <a href="{{ asset('storage/' . $filePath) }}" 
                                           target="_blank"
                                           class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-md transition-colors duration-200">
                                            <i class="fas fa-eye mr-2"></i>
                                            View
                                        </a>
                                        <a href="{{ asset('storage/' . $filePath) }}" 
                                           download
                                           class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-md transition-colors duration-200">
                                            <i class="fas fa-download mr-2"></i>
                                            Download
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Results Summary -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="bg-white dark:bg-gray-700 p-4 rounded-lg border border-gray-200 dark:border-gray-600">
                                <div class="flex items-center">
                                    <i class="fas fa-calendar-check text-green-500 mr-3"></i>
                                    <div>
                                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Completed On</p>
                                        <p class="text-lg font-semibold text-gray-900 dark:text-white">
                                            {{ $labAppointment->completed_at->format('M d, Y') }}
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-white dark:bg-gray-700 p-4 rounded-lg border border-gray-200 dark:border-gray-600">
                                <div class="flex items-center">
                                    <i class="fas fa-clock text-blue-500 mr-3"></i>
                                    <div>
                                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Processing Time</p>
                                        <p class="text-lg font-semibold text-gray-900 dark:text-white">
                                            {{ number_format($labAppointment->confirmed_at->diffInDays($labAppointment->completed_at, false), 1) }} days
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-white dark:bg-gray-700 p-4 rounded-lg border border-gray-200 dark:border-gray-600">
                                <div class="flex items-center">
                                    <i class="fas fa-user-md text-purple-500 mr-3"></i>
                                    <div>
                                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Test Type</p>
                                        <p class="text-lg font-semibold text-gray-900 dark:text-white">
                                            {{ $labAppointment->labTestRequest->test_type ?? 'Lab Test' }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Actions Panel -->
        <div class="space-y-6">
            <!-- Status Actions -->
            @if($labAppointment->status === 'pending')
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                        <i class="fas fa-check-circle mr-2 text-green-500"></i>
                        Confirm Appointment
                    </h3>
                    <form action="{{ route('laboratory.appointments.confirm', $labAppointment) }}" method="POST" class="space-y-4">
                        @csrf
                        @method('PATCH')
                        
                        <!-- Cost Settings -->
                        <div>
                            <label for="final_cost" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                <i class="fas fa-dollar-sign mr-1 text-green-500"></i>
                                Final Cost ($)
                            </label>
                            <input type="number" 
                                   id="final_cost" 
                                   name="final_cost" 
                                   step="0.01" 
                                   min="0"
                                   value="{{ old('final_cost', $labAppointment->estimated_cost) }}"
                                   placeholder="Enter final cost"
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                            @error('final_cost')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Fasting Requirements -->
                        <div>
                            <div class="flex items-center mb-3">
                                <input type="checkbox" 
                                       id="requires_fasting" 
                                       name="requires_fasting" 
                                       value="1"
                                       {{ old('requires_fasting') ? 'checked' : '' }}
                                       onchange="toggleFastingHours()"
                                       class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 dark:border-gray-600 rounded">
                                <label for="requires_fasting" class="ml-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                                    <i class="fas fa-utensils mr-1 text-orange-500"></i>
                                    Requires Fasting
                                </label>
                            </div>
                            
                            <div id="fasting_hours_container" style="display: none;">
                                <label for="fasting_hours" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Fasting Hours Required
                                </label>
                                <select id="fasting_hours" 
                                        name="fasting_hours"
                                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                                    <option value="">Select hours</option>
                                    @for($i = 4; $i <= 16; $i += 2)
                                        <option value="{{ $i }}" {{ old('fasting_hours') == $i ? 'selected' : '' }}>
                                            {{ $i }} hours
                                        </option>
                                    @endfor
                                </select>
                                @error('fasting_hours')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $error->message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Special Instructions -->
                        <div>
                            <label for="lab_instructions" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                <i class="fas fa-clipboard-list mr-1 text-blue-500"></i>
                                Instructions for Patient
                            </label>
                            <textarea id="lab_instructions" 
                                      name="lab_instructions" 
                                      rows="3"
                                      placeholder="Add any special instructions for the patient..."
                                      class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">{{ old('lab_instructions', $labAppointment->lab_instructions) }}</textarea>
                            @error('lab_instructions')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $error->message }}</p>
                            @enderror
                        </div>

                        <div class="flex space-x-3">
                            <button type="submit" 
                                    class="flex-1 inline-flex justify-center items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-md transition-colors duration-200">
                                <i class="fas fa-check mr-2"></i>
                                Confirm Appointment
                            </button>
                        </div>
                    </form>

                    <!-- Reject Form -->
                    <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-600">
                        <h4 class="text-md font-medium text-gray-900 dark:text-white mb-3">
                            <i class="fas fa-times-circle mr-2 text-red-500"></i>
                            Or Reject Appointment
                        </h4>
                        <form action="{{ route('laboratory.appointments.reject', $labAppointment) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <div class="mb-3">
                                <label for="rejection_reason" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Reason for Rejection
                                </label>
                                <textarea id="rejection_reason" 
                                          name="rejection_reason" 
                                          rows="2"
                                          required
                                          placeholder="Please provide a reason for rejecting this appointment..."
                                          class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">{{ old('rejection_reason') }}</textarea>
                                @error('rejection_reason')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $error->message }}</p>
                                @enderror
                            </div>
                            <button type="submit" 
                                    class="w-full inline-flex justify-center items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-md transition-colors duration-200">
                                <i class="fas fa-times mr-2"></i>
                                Reject Appointment
                            </button>
                        </form>
                    </div>
                </div>
            @endif

            <!-- Instructions Form -->
            @if(in_array($labAppointment->status, ['pending', 'confirmed']))
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                        <i class="fas fa-edit mr-2 text-blue-500"></i>
                        Update Instructions
                    </h3>
                    <form action="{{ route('laboratory.appointments.update', $labAppointment) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <div class="mb-4">
                            <label for="lab_instructions" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Instructions for Patient
                            </label>
                            <textarea id="lab_instructions" name="lab_instructions" rows="4" 
                                      class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                                      placeholder="Add any special instructions for the patient...">{{ $labAppointment->lab_instructions }}</textarea>
                        </div>
                        <button type="submit" 
                                class="w-full inline-flex justify-center items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-md transition-colors duration-200">
                            <i class="fas fa-save mr-2"></i>
                            Save Instructions
                        </button>
                    </form>
                </div>
            @endif

            <!-- Update Cost & Time (for confirmed appointments) -->
            @if($labAppointment->status === 'confirmed')
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                        <i class="fas fa-cog mr-2 text-purple-500"></i>
                        Update Details
                    </h3>
                    <form action="{{ route('laboratory.appointments.update', $labAppointment) }}" method="POST" class="space-y-4">
                        @csrf
                        @method('PATCH')
                        
                        <!-- Update Final Cost -->
                        <div>
                            <label for="update_final_cost" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                <i class="fas fa-dollar-sign mr-1 text-green-500"></i>
                                Update Final Cost ($)
                            </label>
                            <input type="number" 
                                   id="update_final_cost" 
                                   name="final_cost" 
                                   step="0.01" 
                                   min="0"
                                   value="{{ $labAppointment->final_cost ?? $labAppointment->estimated_cost }}"
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                        </div>

                        <!-- Update Fasting Requirements -->
                        <div>
                            <div class="flex items-center mb-3">
                                <input type="checkbox" 
                                       id="update_requires_fasting" 
                                       name="requires_fasting" 
                                       value="1"
                                       {{ $labAppointment->requires_fasting ? 'checked' : '' }}
                                       onchange="toggleUpdateFastingHours()"
                                       class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 dark:border-gray-600 rounded">
                                <label for="update_requires_fasting" class="ml-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                                    <i class="fas fa-utensils mr-1 text-orange-500"></i>
                                    Requires Fasting
                                </label>
                            </div>
                            
                            <div id="update_fasting_hours_container" style="{{ $labAppointment->requires_fasting ? 'display: block;' : 'display: none;' }}">
                                <label for="update_fasting_hours" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Fasting Hours Required
                                </label>
                                <select id="update_fasting_hours" 
                                        name="fasting_hours"
                                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                                    <option value="">Select hours</option>
                                    @for($i = 4; $i <= 16; $i += 2)
                                        <option value="{{ $i }}" {{ $labAppointment->fasting_hours == $i ? 'selected' : '' }}>
                                            {{ $i }} hours
                                        </option>
                                    @endfor
                                </select>
                            </div>
                        </div>

                        <button type="submit" 
                                class="w-full inline-flex justify-center items-center px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white text-sm font-medium rounded-md transition-colors duration-200">
                            <i class="fas fa-save mr-2"></i>
                            Update Details
                        </button>
                    </form>
                </div>
            @endif

            <!-- Complete Test & Upload Results -->
            @if($labAppointment->status === 'confirmed')
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                        <i class="fas fa-check-circle mr-2 text-green-500"></i>
                        Complete Test & Upload Results
                    </h3>
                    <form action="{{ route('laboratory.appointments.complete', $labAppointment) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                        @csrf
                        @method('PATCH')
                        
                        <!-- Test Results Text -->
                        <div>
                            <label for="test_results" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                <i class="fas fa-chart-line mr-1 text-blue-500"></i>
                                Test Results *
                            </label>
                            <textarea id="test_results" 
                                      name="test_results" 
                                      rows="6"
                                      required
                                      placeholder="Enter detailed test results, measurements, findings, etc..."
                                      class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">{{ old('test_results') }}</textarea>
                            @error('test_results')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Additional Notes -->
                        <div>
                            <label for="result_notes" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                <i class="fas fa-sticky-note mr-1 text-yellow-500"></i>
                                Additional Notes
                            </label>
                            <textarea id="result_notes" 
                                      name="result_notes" 
                                      rows="3"
                                      placeholder="Additional observations, recommendations, or notes for the doctor..."
                                      class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">{{ old('result_notes') }}</textarea>
                            @error('result_notes')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $error->message }}</p>
                            @enderror
                        </div>

                        <!-- Results File Upload -->
                        <div>
                            <label for="results_file" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                <i class="fas fa-file-pdf mr-1 text-red-500"></i>
                                Upload Results File (PDF) *
                            </label>
                            <input type="file" 
                                   id="results_file" 
                                   name="results_file" 
                                   accept=".pdf"
                                   required
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Maximum file size: 10MB. PDF format only.</p>
                            @error('results_file')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $error->message }}</p>
                            @enderror
                        </div>

                        <button type="submit" 
                                class="w-full inline-flex justify-center items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-md transition-colors duration-200">
                            <i class="fas fa-check-circle mr-2"></i>
                            Complete Test & Upload Results
                        </button>
                    </form>
                </div>
            @endif

            <!-- Appointment Info Card -->
            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                    Quick Info
                </h3>
                <div class="space-y-3 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-600 dark:text-gray-400">Appointment ID:</span>
                        <span class="font-medium text-gray-900 dark:text-white">#{{ $labAppointment->id }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600 dark:text-gray-400">Patient ID:</span>
                        <span class="font-medium text-gray-900 dark:text-white">#{{ $labAppointment->patient->id }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600 dark:text-gray-400">Test Request ID:</span>
                        <span class="font-medium text-gray-900 dark:text-white">#{{ $labAppointment->lab_test_request_id }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600 dark:text-gray-400">Days Until Appointment:</span>
                        <span class="font-medium text-gray-900 dark:text-white">
                            @if($labAppointment->appointment_date->isToday())
                                Today
                            @elseif($labAppointment->appointment_date->isTomorrow())
                                Tomorrow
                            @elseif($labAppointment->appointment_date->isPast())
                                {{ $labAppointment->appointment_date->diffForHumans() }}
                            @else
                                {{ $labAppointment->appointment_date->diffForHumans() }}
                            @endif
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Toggle fasting hours input
    function toggleFastingHours() {
        const checkbox = document.getElementById('requires_fasting');
        const container = document.getElementById('fasting_hours_container');
        const select = document.getElementById('fasting_hours');
        
        if (checkbox && container) {
            if (checkbox.checked) {
                container.style.display = 'block';
            } else {
                container.style.display = 'none';
                if (select) select.value = '';
            }
        }
    }

    // Toggle update fasting hours input
    function toggleUpdateFastingHours() {
        const checkbox = document.getElementById('update_requires_fasting');
        const container = document.getElementById('update_fasting_hours_container');
        const select = document.getElementById('update_fasting_hours');
        
        if (checkbox && container) {
            if (checkbox.checked) {
                container.style.display = 'block';
            } else {
                container.style.display = 'none';
                if (select) select.value = '';
            }
        }
    }

    // Auto-submit forms with confirmation
    document.addEventListener('DOMContentLoaded', function() {
        const confirmButton = document.querySelector('form[action*="confirm"] button[type="submit"]');
        const rejectButton = document.querySelector('form[action*="reject"] button[type="submit"]');
        
        if (confirmButton) {
            confirmButton.addEventListener('click', function(e) {
                if (!confirm('Are you sure you want to confirm this appointment?')) {
                    e.preventDefault();
                }
            });
        }
        
        if (rejectButton) {
            rejectButton.addEventListener('click', function(e) {
                if (!confirm('Are you sure you want to reject this appointment?')) {
                    e.preventDefault();
                }
            });
        }

        // Initialize fasting hours toggle
        const fastingCheckbox = document.getElementById('requires_fasting');
        if (fastingCheckbox) {
            toggleFastingHours();
        }

        const updateFastingCheckbox = document.getElementById('update_requires_fasting');
        if (updateFastingCheckbox) {
            toggleUpdateFastingHours();
        }
    });
</script>
@endpush
