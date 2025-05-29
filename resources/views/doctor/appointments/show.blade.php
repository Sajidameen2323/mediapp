@extends('layouts.app')

@section('title', 'Appointment Details - Medi App')

@section('content')
<div class="appointment-details-content">
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header Section -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <div class="flex items-center space-x-3">
                    <a href="{{ route('doctor.appointments.index') }}" class="text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white">
                        <i class="fas fa-arrow-left text-xl"></i>
                    </a>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Appointment Details</h1>
                </div>
                <p class="mt-2 text-gray-600 dark:text-gray-400">Appointment #{{ $appointment->appointment_number }}</p>
            </div>
            <div class="flex items-center space-x-3">
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                    {{ $appointment->status === 'pending' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' : '' }}
                    {{ $appointment->status === 'confirmed' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : '' }}
                    {{ $appointment->status === 'completed' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200' : '' }}
                    {{ $appointment->status === 'cancelled' ? 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' : '' }}
                    {{ $appointment->status === 'no_show' ? 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200' : '' }}">
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
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Name</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-white font-semibold">{{ $appointment->patient_name }}</dd>
                    </div>
                    
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Phone</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                            <a href="tel:{{ $appointment->patient_phone }}" class="text-blue-600 hover:text-blue-500">
                                {{ $appointment->patient_phone }}
                            </a>
                        </dd>
                    </div>
                    
                    @if($appointment->patient_email)
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Email</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                                <a href="mailto:{{ $appointment->patient_email }}" class="text-blue-600 hover:text-blue-500">
                                    {{ $appointment->patient_email }}
                                </a>
                            </dd>
                        </div>
                    @endif
                    
                    @if($appointment->patient_notes)
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Patient Notes</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $appointment->patient_notes }}</dd>
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
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Service</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-white font-semibold">{{ $appointment->service->name }}</dd>
                    </div>
                    
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Date</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $appointment->appointment_date->format('l, F d, Y') }}</dd>
                    </div>
                    
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Time</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $appointment->formatted_time }}</dd>
                    </div>
                    
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Duration</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $appointment->duration_minutes }} minutes</dd>
                    </div>
                    
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Booking Source</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ ucfirst(str_replace('_', ' ', $appointment->booking_source)) }}</dd>
                    </div>
                    
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Booked At</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $appointment->booked_at->format('M d, Y g:i A') }}</dd>
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
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Amount</dt>
                        <dd class="mt-1 text-lg font-semibold text-gray-900 dark:text-white">${{ number_format($appointment->total_amount, 2) }}</dd>
                    </div>
                    
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Paid Amount</dt>
                        <dd class="mt-1 text-lg font-semibold text-gray-900 dark:text-white">${{ number_format($appointment->paid_amount, 2) }}</dd>
                    </div>
                    
                    @if($appointment->total_amount > $appointment->paid_amount)
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Balance</dt>
                            <dd class="mt-1 text-lg font-semibold text-red-600 dark:text-red-400">
                                ${{ number_format($appointment->total_amount - $appointment->paid_amount, 2) }}
                            </dd>
                        </div>
                    @endif
                    
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Payment Status</dt>
                        <dd class="mt-1">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                {{ $appointment->payment_status === 'paid' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : '' }}
                                {{ $appointment->payment_status === 'pending' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' : '' }}
                                {{ $appointment->payment_status === 'partially_paid' ? 'bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-200' : '' }}
                                {{ $appointment->payment_status === 'refunded' ? 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' : '' }}">
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
</script>
</div>
@endsection
