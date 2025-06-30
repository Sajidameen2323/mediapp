@extends('layouts.laboratory')

@section('title', 'Lab Appointment Details')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 mb-6">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">
                    Lab Appointment #{{ $appointment->id }}
                </h1>
                <div class="flex items-center space-x-4">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                        {{ $appointment->status === 'confirmed' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : '' }}
                        {{ $appointment->status === 'pending' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' : '' }}
                        {{ $appointment->status === 'rejected' ? 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' : '' }}
                        {{ $appointment->status === 'completed' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200' : '' }}
                        {{ $appointment->status === 'cancelled' ? 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200' : '' }}">
                        <i class="fas fa-circle mr-2 text-xs"></i>
                        {{ ucfirst($appointment->status) }}
                    </span>
                    <span class="text-sm text-gray-500 dark:text-gray-400">
                        Created {{ $appointment->created_at->diffForHumans() }}
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
                        <p class="text-gray-900 dark:text-white">{{ $appointment->patient->name }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Email</label>
                        <p class="text-gray-900 dark:text-white">{{ $appointment->patient->email }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Phone</label>
                        <p class="text-gray-900 dark:text-white">{{ $appointment->patient->phone ?? 'Not provided' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Age</label>
                        <p class="text-gray-900 dark:text-white">
                            @if($appointment->patient->date_of_birth)
                                {{ $appointment->patient->date_of_birth->age }} years
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
                        <p class="text-gray-900 dark:text-white font-medium">{{ $appointment->labTestRequest->test_name }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Test Type</label>
                        <p class="text-gray-900 dark:text-white">{{ $appointment->labTestRequest->test_type }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Prescribed By</label>
                        <p class="text-gray-900 dark:text-white">{{ $appointment->labTestRequest->doctor->name }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Priority</label>
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                            {{ $appointment->labTestRequest->priority === 'urgent' ? 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' : '' }}
                            {{ $appointment->labTestRequest->priority === 'normal' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : '' }}">
                            {{ ucfirst($appointment->labTestRequest->priority) }}
                        </span>
                    </div>
                </div>
                @if($appointment->labTestRequest->instructions)
                    <div class="mt-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Doctor's Instructions</label>
                        <p class="text-gray-900 dark:text-white bg-gray-50 dark:bg-gray-700 p-3 rounded-md">{{ $appointment->labTestRequest->instructions }}</p>
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
                        <p class="text-gray-900 dark:text-white">{{ $appointment->appointment_date->format('M d, Y') }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Time Slot</label>
                        <p class="text-gray-900 dark:text-white">{{ $appointment->appointment_time }}</p>
                    </div>
                    @if($appointment->confirmed_at)
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Confirmed At</label>
                            <p class="text-gray-900 dark:text-white">{{ $appointment->confirmed_at->format('M d, Y g:i A') }}</p>
                        </div>
                    @endif
                    @if($appointment->completed_at)
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Completed At</label>
                            <p class="text-gray-900 dark:text-white">{{ $appointment->completed_at->format('M d, Y g:i A') }}</p>
                        </div>
                    @endif
                </div>
                @if($appointment->lab_instructions)
                    <div class="mt-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Lab Instructions</label>
                        <p class="text-gray-900 dark:text-white bg-gray-50 dark:bg-gray-700 p-3 rounded-md">{{ $appointment->lab_instructions }}</p>
                    </div>
                @endif
            </div>

            <!-- Results Section -->
            @if($appointment->status === 'completed' && $appointment->results_file_path)
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                        <i class="fas fa-file-medical mr-2 text-blue-500"></i>
                        Test Results
                    </h2>
                    <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700 rounded-md">
                        <div class="flex items-center">
                            <i class="fas fa-file-pdf text-red-500 text-2xl mr-3"></i>
                            <div>
                                <p class="font-medium text-gray-900 dark:text-white">Test Results</p>
                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                    Uploaded {{ $appointment->updated_at->format('M d, Y g:i A') }}
                                </p>
                            </div>
                        </div>
                        <a href="{{ asset('storage/' . $appointment->results_file_path) }}" 
                           target="_blank"
                           class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-md transition-colors duration-200">
                            <i class="fas fa-download mr-2"></i>
                            Download
                        </a>
                    </div>
                </div>
            @endif
        </div>

        <!-- Actions Panel -->
        <div class="space-y-6">
            <!-- Status Actions -->
            @if($appointment->status === 'pending')
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                        Appointment Actions
                    </h3>
                    <div class="space-y-3">
                        <form action="{{ route('laboratory.appointments.confirm', $appointment) }}" method="POST" class="inline">
                            @csrf
                            @method('PATCH')
                            <button type="submit" 
                                    class="w-full inline-flex justify-center items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-md transition-colors duration-200">
                                <i class="fas fa-check mr-2"></i>
                                Confirm Appointment
                            </button>
                        </form>
                        <form action="{{ route('laboratory.appointments.reject', $appointment) }}" method="POST" class="inline">
                            @csrf
                            @method('PATCH')
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
            @if(in_array($appointment->status, ['pending', 'confirmed']))
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                        Add Instructions
                    </h3>
                    <form action="{{ route('laboratory.appointments.update', $appointment) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <div class="mb-4">
                            <label for="lab_instructions" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Instructions for Patient
                            </label>
                            <textarea id="lab_instructions" name="lab_instructions" rows="4" 
                                      class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                                      placeholder="Add any special instructions for the patient...">{{ $appointment->lab_instructions }}</textarea>
                        </div>
                        <button type="submit" 
                                class="w-full inline-flex justify-center items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-md transition-colors duration-200">
                            <i class="fas fa-save mr-2"></i>
                            Save Instructions
                        </button>
                    </form>
                </div>
            @endif

            <!-- Complete Test & Upload Results -->
            @if($appointment->status === 'confirmed')
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                        Complete Test
                    </h3>
                    <form action="{{ route('laboratory.appointments.complete', $appointment) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PATCH')
                        <div class="mb-4">
                            <label for="results_file" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Upload Results (PDF)
                            </label>
                            <input type="file" id="results_file" name="results_file" accept=".pdf"
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                        </div>
                        <button type="submit" 
                                class="w-full inline-flex justify-center items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-md transition-colors duration-200">
                            <i class="fas fa-check-circle mr-2"></i>
                            Complete & Upload Results
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
                        <span class="font-medium text-gray-900 dark:text-white">#{{ $appointment->id }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600 dark:text-gray-400">Patient ID:</span>
                        <span class="font-medium text-gray-900 dark:text-white">#{{ $appointment->patient->id }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600 dark:text-gray-400">Test Request ID:</span>
                        <span class="font-medium text-gray-900 dark:text-white">#{{ $appointment->lab_test_request_id }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600 dark:text-gray-400">Days Until Appointment:</span>
                        <span class="font-medium text-gray-900 dark:text-white">
                            @if($appointment->appointment_date->isToday())
                                Today
                            @elseif($appointment->appointment_date->isTomorrow())
                                Tomorrow
                            @elseif($appointment->appointment_date->isPast())
                                {{ $appointment->appointment_date->diffForHumans() }}
                            @else
                                {{ $appointment->appointment_date->diffForHumans() }}
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
    // Auto-submit forms with confirmation
    document.addEventListener('DOMContentLoaded', function() {
        const confirmButton = document.querySelector('form[action*="confirm"] button');
        const rejectButton = document.querySelector('form[action*="reject"] button');
        
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
    });
</script>
@endpush
