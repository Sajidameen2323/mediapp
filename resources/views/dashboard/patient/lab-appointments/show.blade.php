@extends('layouts.patient')

@section('title', 'Lab Appointment Details - ' . $labAppointment->appointment_number)

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
                            <a href="{{ route('patient.lab-appointments.index') }}" 
                               class="text-sm font-medium text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 transition-colors duration-200">
                                Lab Appointments
                            </a>
                        </div>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <i class="fas fa-chevron-right text-gray-300 dark:text-gray-600 mx-2"></i>
                            <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $labAppointment->appointment_number }}</span>
                        </div>
                    </li>
                </ol>
            </nav>

            <!-- Header Content -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Lab Appointment Details</h1>
                    <p class="mt-2 text-gray-600 dark:text-gray-400">{{ $labAppointment->appointment_number }}</p>
                </div>
                <div class="mt-4 sm:mt-0 flex items-center space-x-3">
                    <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium 
                        @if($labAppointment->status === 'pending') bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300
                        @elseif($labAppointment->status === 'confirmed') bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300
                        @elseif($labAppointment->status === 'completed') bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300
                        @elseif($labAppointment->status === 'cancelled') bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300
                        @else bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300
                        @endif">
                        @if($labAppointment->status === 'pending')
                            <i class="fas fa-clock mr-2"></i>
                        @elseif($labAppointment->status === 'confirmed')
                            <i class="fas fa-check-circle mr-2"></i>
                        @elseif($labAppointment->status === 'completed')
                            <i class="fas fa-check-double mr-2"></i>
                        @elseif($labAppointment->status === 'cancelled')
                            <i class="fas fa-times-circle mr-2"></i>
                        @elseif($labAppointment->status === 'rejected')
                            <i class="fas fa-exclamation-triangle mr-2"></i>
                        @endif
                        {{ ucfirst($labAppointment->status) }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Appointment Information -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 mb-8">
            <div class="p-6">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">
                    <i class="fas fa-calendar-check text-blue-500 dark:text-blue-400 mr-2"></i>
                    Appointment Information
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Basic Details -->
                    <div class="space-y-4">
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Test Name</h3>
                            <p class="text-lg font-medium text-gray-900 dark:text-gray-100">{{ $labAppointment->labTestRequest->test_name }}</p>
                        </div>

                        <div>
                            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Test Type</h3>
                            <p class="text-sm text-gray-900 dark:text-gray-100">{{ ucfirst($labAppointment->labTestRequest->test_type) }}</p>
                        </div>

                        <div>
                            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Laboratory</h3>
                            <p class="text-sm text-gray-900 dark:text-gray-100">{{ $labAppointment->laboratory->name }}</p>
                            <p class="text-xs text-gray-600 dark:text-gray-400">{{ $labAppointment->laboratory->address }}, {{ $labAppointment->laboratory->city }}</p>
                        </div>

                        @if($labAppointment->labTestRequest->medicalReport && $labAppointment->labTestRequest->medicalReport->doctor)
                            <div>
                                <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Requested by</h3>
                                <p class="text-sm text-gray-900 dark:text-gray-100">{{ $labAppointment->labTestRequest->medicalReport->doctor->user->name }}</p>
                            </div>
                        @endif
                    </div>

                    <!-- Appointment Details -->
                    <div class="space-y-4">
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Date & Time</h3>
                            <div class="flex items-center space-x-4 mt-1">
                                <div class="flex items-center text-sm text-gray-900 dark:text-gray-100">
                                    <i class="fas fa-calendar text-blue-500 dark:text-blue-400 mr-2"></i>
                                    {{ $labAppointment->appointment_date->format('l, F j, Y') }}
                                </div>
                                <div class="flex items-center text-sm text-gray-900 dark:text-gray-100">
                                    <i class="fas fa-clock text-green-500 dark:text-green-400 mr-2"></i>
                                    {{ $labAppointment->formatted_time }}
                                </div>
                            </div>
                        </div>

                        <div>
                            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Duration</h3>
                            <p class="text-sm text-gray-900 dark:text-gray-100">{{ $labAppointment->duration_minutes }} minutes</p>
                        </div>

                        @if($labAppointment->final_cost || $labAppointment->estimated_cost)
                            <div>
                                <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Cost</h3>
                                <p class="text-sm text-gray-900 dark:text-gray-100">
                                    ${{ number_format($labAppointment->final_cost ?? $labAppointment->estimated_cost, 2) }}
                                    @if($labAppointment->final_cost && $labAppointment->estimated_cost && $labAppointment->final_cost != $labAppointment->estimated_cost)
                                        <span class="text-xs text-gray-500 dark:text-gray-400">(Est: ${{ number_format($labAppointment->estimated_cost, 2) }})</span>
                                    @endif
                                </p>
                            </div>
                        @endif

                        <div>
                            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Booked On</h3>
                            <p class="text-sm text-gray-900 dark:text-gray-100">{{ $labAppointment->booked_at->format('M j, Y g:i A') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Lab Instructions & Preparation -->
        @if($labAppointment->lab_instructions || $labAppointment->requires_fasting || $labAppointment->special_instructions)
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 mb-8">
                <div class="p-6">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">
                        <i class="fas fa-info-circle text-blue-500 dark:text-blue-400 mr-2"></i>
                        Instructions & Preparation
                    </h2>

                    @if($labAppointment->requires_fasting)
                        <div class="bg-orange-50 dark:bg-orange-900/20 rounded-lg p-4 mb-4">
                            <div class="flex items-start">
                                <i class="fas fa-utensils text-orange-500 dark:text-orange-400 mr-3 mt-1"></i>
                                <div>
                                    <h3 class="text-sm font-semibold text-orange-800 dark:text-orange-300">Fasting Required</h3>
                                    <p class="text-sm text-orange-700 dark:text-orange-400 mt-1">
                                        Please fast for {{ $labAppointment->fasting_hours ?? 12 }} hours before your appointment. 
                                        You may drink water, but avoid food, drinks with calories, gum, or candy.
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if($labAppointment->lab_instructions)
                        <div class="bg-blue-50 dark:bg-blue-900/20 rounded-lg p-4 mb-4">
                            <div class="flex items-start">
                                <i class="fas fa-clipboard-list text-blue-500 dark:text-blue-400 mr-3 mt-1"></i>
                                <div>
                                    <h3 class="text-sm font-semibold text-blue-800 dark:text-blue-300">Laboratory Instructions</h3>
                                    <p class="text-sm text-blue-700 dark:text-blue-400 mt-1">{{ $labAppointment->lab_instructions }}</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if($labAppointment->special_instructions)
                        <div class="bg-purple-50 dark:bg-purple-900/20 rounded-lg p-4">
                            <div class="flex items-start">
                                <i class="fas fa-star text-purple-500 dark:text-purple-400 mr-3 mt-1"></i>
                                <div>
                                    <h3 class="text-sm font-semibold text-purple-800 dark:text-purple-300">Special Instructions</h3>
                                    <p class="text-sm text-purple-700 dark:text-purple-400 mt-1">{{ $labAppointment->special_instructions }}</p>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        @endif

        <!-- Test Results -->
        @if($labAppointment->status === 'completed' && $labAppointment->test_results)
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 mb-8">
                <div class="p-6">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">
                        <i class="fas fa-file-medical text-green-500 dark:text-green-400 mr-2"></i>
                        Test Results
                    </h2>

                    <div class="bg-green-50 dark:bg-green-900/20 rounded-lg p-4">
                        @if(is_array($labAppointment->test_results))
                            @foreach($labAppointment->test_results as $key => $result)
                                <div class="mb-3">
                                    <h4 class="text-sm font-medium text-green-800 dark:text-green-300">{{ ucfirst(str_replace('_', ' ', $key)) }}</h4>
                                    <p class="text-sm text-green-700 dark:text-green-400">{{ $result }}</p>
                                </div>
                            @endforeach
                        @else
                            <div class="mb-3">
                                <h4 class="text-sm font-medium text-green-800 dark:text-green-300">Test Results</h4>
                                <p class="text-sm text-green-700 dark:text-green-400 whitespace-pre-wrap">{{ $labAppointment->test_results }}</p>
                            </div>
                        @endif

                        @if($labAppointment->result_notes)
                            <div class="mt-4 pt-3 border-t border-green-200 dark:border-green-700">
                                <h4 class="text-sm font-medium text-green-800 dark:text-green-300">Lab Notes</h4>
                                <p class="text-sm text-green-700 dark:text-green-400 mt-1">{{ $labAppointment->result_notes }}</p>
                            </div>
                        @endif

                        <div class="mt-4 pt-3 border-t border-green-200 dark:border-green-700">
                            <p class="text-xs text-green-600 dark:text-green-500">
                                Results uploaded on {{ $labAppointment->result_uploaded_at->format('M j, Y g:i A') }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Notes -->
        @if($labAppointment->patient_notes || $labAppointment->rejection_reason || $labAppointment->cancellation_reason)
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 mb-8">
                <div class="p-6">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">
                        <i class="fas fa-sticky-note text-yellow-500 dark:text-yellow-400 mr-2"></i>
                        Notes
                    </h2>

                    @if($labAppointment->patient_notes)
                        <div class="mb-4">
                            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Your Notes</h3>
                            <p class="text-sm text-gray-900 dark:text-gray-100 mt-1">{{ $labAppointment->patient_notes }}</p>
                        </div>
                    @endif

                    @if($labAppointment->rejection_reason)
                        <div class="bg-red-50 dark:bg-red-900/20 rounded-lg p-4">
                            <h3 class="text-sm font-medium text-red-800 dark:text-red-300">Rejection Reason</h3>
                            <p class="text-sm text-red-700 dark:text-red-400 mt-1">{{ $labAppointment->rejection_reason }}</p>
                        </div>
                    @endif

                    @if($labAppointment->cancellation_reason)
                        <div class="bg-red-50 dark:bg-red-900/20 rounded-lg p-4">
                            <h3 class="text-sm font-medium text-red-800 dark:text-red-300">Cancellation Reason</h3>
                            <p class="text-sm text-red-700 dark:text-red-400 mt-1">{{ $labAppointment->cancellation_reason }}</p>
                            <p class="text-xs text-red-600 dark:text-red-500 mt-2">
                                Cancelled by {{ ucfirst($labAppointment->cancelled_by) }} on {{ $labAppointment->cancelled_at->format('M j, Y g:i A') }}
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        @endif

        <!-- Action Buttons -->
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center">
            <a href="{{ route('patient.lab-appointments.index') }}" 
               class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 mb-3 sm:mb-0">
                <i class="fas fa-arrow-left mr-2"></i>
                Back to Appointments
            </a>
            
            <div class="flex items-center space-x-3">
                @if($labAppointment->canBeCancelled())
                    <button type="button" onclick="openCancelModal()" 
                            class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 dark:bg-red-700 dark:hover:bg-red-600 text-white font-medium rounded-lg transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                        <i class="fas fa-times mr-2"></i>
                        Cancel Appointment
                    </button>
                @endif

                @if($labAppointment->status === 'completed')
                    <a href="{{ route('patient.lab-tests.show', $labAppointment->labTestRequest) }}" 
                       class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 dark:bg-blue-700 dark:hover:bg-blue-600 text-white font-medium rounded-lg transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                        <i class="fas fa-eye mr-2"></i>
                        View Test Details
                    </a>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Cancel Appointment Modal -->
@if($labAppointment->canBeCancelled())
<div id="cancelModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 hidden">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white dark:bg-gray-800">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">Cancel Appointment</h3>
                <button type="button" onclick="closeCancelModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <form action="{{ route('patient.lab-appointments.cancel', $labAppointment) }}" method="POST">
                @csrf
                @method('PATCH')
                
                <div class="mb-4">
                    <label for="cancellation_reason" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Reason for cancellation <span class="text-red-500">*</span>
                    </label>
                    <textarea name="cancellation_reason" id="cancellation_reason" required rows="3"
                              class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-red-500 focus:border-red-500"
                              placeholder="Please provide a reason for cancelling this appointment..."></textarea>
                </div>
                
                <div class="flex items-center justify-end space-x-3">
                    <button type="button" onclick="closeCancelModal()" 
                            class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 rounded-lg transition-colors duration-200">
                        Keep Appointment
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 text-sm font-medium text-white bg-red-600 hover:bg-red-700 rounded-lg transition-colors duration-200">
                        Cancel Appointment
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

@push('scripts')
<script>
function openCancelModal() {
    document.getElementById('cancelModal').classList.remove('hidden');
}

function closeCancelModal() {
    document.getElementById('cancelModal').classList.add('hidden');
}

// Close modal when clicking outside
document.getElementById('cancelModal')?.addEventListener('click', function(e) {
    if (e.target === this) {
        closeCancelModal();
    }
});
</script>
@endpush
@endsection
