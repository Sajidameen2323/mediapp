@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-100 dark:bg-gray-900">
    <div class="container mx-auto px-4 py-8">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Appointment Details</h1>
                <p class="text-gray-600 dark:text-gray-400">Appointment #{{ $appointment->id }}</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('admin.appointments.index') }}" 
                   class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition duration-200">
                    <i class="fas fa-arrow-left mr-2"></i>Back to List
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Information -->
            <div class="lg:col-span-2">
                <!-- Appointment Overview -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow mb-6">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                        <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Appointment Overview</h2>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Status</label>
                                @php
                                    $statusColors = [
                                        'pending' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300',
                                        'confirmed' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300',
                                        'cancelled' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300',
                                        'completed' => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300',
                                        'no_show' => 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-300',
                                    ];
                                @endphp
                                <span class="inline-flex px-3 py-1 text-sm font-medium rounded-full {{ $statusColors[$appointment->status] ?? 'bg-gray-100 text-gray-800' }}">
                                    {{ ucfirst(str_replace('_', ' ', $appointment->status)) }}
                                </span>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Appointment Date</label>
                                <p class="text-gray-900 dark:text-white">{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('l, F j, Y') }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Appointment Time</label>
                                <p class="text-gray-900 dark:text-white">{{ \Carbon\Carbon::parse($appointment->appointment_time)->format('g:i A') }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Created</label>
                                <p class="text-gray-900 dark:text-white">{{ $appointment->created_at->format('M j, Y g:i A') }}</p>
                            </div>
                            @if($appointment->appointment_type)
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Type</label>
                                <p class="text-gray-900 dark:text-white">{{ ucfirst($appointment->appointment_type) }}</p>
                            </div>
                            @endif
                            @if($appointment->notes)
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Notes</label>
                                <p class="text-gray-900 dark:text-white">{{ $appointment->notes }}</p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Patient Information -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow mb-6">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                        <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Patient Information</h2>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Name</label>
                                <p class="text-gray-900 dark:text-white">{{ $appointment->patient->name }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Email</label>
                                <p class="text-gray-900 dark:text-white">{{ $appointment->patient->email }}</p>
                            </div>
                            @if($appointment->patient->phone)
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Phone</label>
                                <p class="text-gray-900 dark:text-white">{{ $appointment->patient->phone }}</p>
                            </div>
                            @endif
                            @if($appointment->patient->date_of_birth)
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Date of Birth</label>
                                <p class="text-gray-900 dark:text-white">{{ \Carbon\Carbon::parse($appointment->patient->date_of_birth)->format('F j, Y') }}</p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Doctor Information -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow mb-6">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                        <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Doctor Information</h2>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Name</label>
                                <p class="text-gray-900 dark:text-white">{{ $appointment->doctor->user->name }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Email</label>
                                <p class="text-gray-900 dark:text-white">{{ $appointment->doctor->user->email }}</p>
                            </div>
                            @if($appointment->doctor->specialization)
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Specialization</label>
                                <p class="text-gray-900 dark:text-white">{{ $appointment->doctor->specialization }}</p>
                            </div>
                            @endif
                            @if($appointment->doctor->consultation_fee)
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Consultation Fee</label>
                                <p class="text-gray-900 dark:text-white">${{ number_format($appointment->doctor->consultation_fee, 2) }}</p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Reschedule History -->
                @if($appointment->reschedule_count > 0)
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow mb-6">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                        <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Reschedule History</h2>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Reschedule Count</label>
                                <p class="text-gray-900 dark:text-white">{{ $appointment->reschedule_count }}</p>
                            </div>
                            @if($appointment->original_appointment_date)
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Original Date</label>
                                <p class="text-gray-900 dark:text-white">
                                    {{ \Carbon\Carbon::parse($appointment->original_appointment_date)->format('M j, Y') }} 
                                    at {{ \Carbon\Carbon::parse($appointment->original_appointment_time)->format('g:i A') }}
                                </p>
                            </div>
                            @endif
                            @if($appointment->rescheduled_at)
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Last Rescheduled</label>
                                <p class="text-gray-900 dark:text-white">{{ $appointment->rescheduled_at->format('M j, Y g:i A') }}</p>
                            </div>
                            @endif
                            @if($appointment->rescheduledBy)
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Rescheduled By</label>
                                <p class="text-gray-900 dark:text-white">{{ $appointment->rescheduledBy->name }}</p>
                            </div>
                            @endif
                            @if($appointment->reschedule_reason)
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Reschedule Reason</label>
                                <p class="text-gray-900 dark:text-white">{{ $appointment->reschedule_reason }}</p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                @endif

                <!-- Cancellation Details -->
                @if($appointment->status === 'cancelled')
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow mb-6">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                        <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Cancellation Details</h2>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            @if($appointment->cancelled_at)
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Cancelled At</label>
                                <p class="text-gray-900 dark:text-white">{{ $appointment->cancelled_at->format('M j, Y g:i A') }}</p>
                            </div>
                            @endif
                            @if($appointment->cancelledBy)
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Cancelled By</label>
                                <p class="text-gray-900 dark:text-white">{{ $appointment->cancelledBy->name }}</p>
                            </div>
                            @endif
                            @if($appointment->cancellation_reason)
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Cancellation Reason</label>
                                <p class="text-gray-900 dark:text-white">{{ $appointment->cancellation_reason }}</p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                @endif

                <!-- Payment Information -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow mb-6">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                        <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Payment Information</h2>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            <!-- Total Amount -->
                            <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 w-8 h-8 bg-blue-100 dark:bg-blue-900 rounded-full flex items-center justify-center mr-3">
                                        <i class="fas fa-dollar-sign text-blue-600 dark:text-blue-400 text-sm"></i>
                                    </div>
                                    <div>
                                        <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Total Amount</label>
                                    </div>
                                </div>
                                <p class="text-lg font-bold text-gray-900 dark:text-white">${{ number_format($appointment->total_amount ?? 0, 2) }}</p>
                            </div>
                            
                            <!-- Paid Amount -->
                            <div class="flex items-center justify-between p-3 bg-green-50 dark:bg-green-900/20 rounded-lg">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 w-8 h-8 bg-green-100 dark:bg-green-900 rounded-full flex items-center justify-center mr-3">
                                        <i class="fas fa-check-circle text-green-600 dark:text-green-400 text-sm"></i>
                                    </div>
                                    <div>
                                        <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Paid Amount</label>
                                    </div>
                                </div>
                                <p class="text-lg font-bold text-green-600 dark:text-green-400">${{ number_format($appointment->paid_amount ?? 0, 2) }}</p>
                            </div>
                            
                            <!-- Outstanding Balance -->
                            @if(($appointment->total_amount ?? 0) > ($appointment->paid_amount ?? 0))
                                <div class="flex items-center justify-between p-3 bg-red-50 dark:bg-red-900/20 rounded-lg border border-red-200 dark:border-red-800">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 w-8 h-8 bg-red-100 dark:bg-red-900 rounded-full flex items-center justify-center mr-3">
                                            <i class="fas fa-exclamation-triangle text-red-600 dark:text-red-400 text-sm"></i>
                                        </div>
                                        <div>
                                            <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Outstanding Balance</label>
                                        </div>
                                    </div>
                                    <p class="text-lg font-bold text-red-600 dark:text-red-400">
                                        ${{ number_format(($appointment->total_amount ?? 0) - ($appointment->paid_amount ?? 0), 2) }}
                                    </p>
                                </div>
                            @endif
                            
                            <!-- Payment Status -->
                            <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 w-8 h-8 bg-purple-100 dark:bg-purple-900 rounded-full flex items-center justify-center mr-3">
                                        <i class="fas fa-credit-card text-purple-600 dark:text-purple-400 text-sm"></i>
                                    </div>
                                    <div>
                                        <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Payment Status</label>
                                    </div>
                                </div>
                                <div>
                                    @php
                                        $paymentStatusColors = [
                                            'pending' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300',
                                            'paid' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300',
                                            'partially_paid' => 'bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-300',
                                            'refunded' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300',
                                        ];
                                    @endphp
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium {{ $paymentStatusColors[$appointment->payment_status ?? 'pending'] ?? 'bg-gray-100 text-gray-800' }}">
                                        <i class="fas fa-circle w-2 h-2 mr-1"></i>
                                        {{ ucfirst(str_replace('_', ' ', $appointment->payment_status ?? 'pending')) }}
                                    </span>
                                </div>
                            </div>
                            
                            <!-- Tax Information -->
                            @if($appointment->tax_amount > 0)
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tax Amount</label>
                                        <p class="text-gray-900 dark:text-white">${{ number_format($appointment->tax_amount ?? 0, 2) }}</p>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tax Rate</label>
                                        <p class="text-gray-900 dark:text-white">{{ number_format($appointment->tax_percentage ?? 0, 2) }}%</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actions Sidebar -->
            <div class="lg:col-span-1">
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 sticky top-6">
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">Actions</h2>
                    
                    <div class="space-y-3">
                        @if($appointment->status === 'pending')
                            <form method="POST" action="{{ route('admin.appointments.approve', $appointment) }}">
                                @csrf
                                <button type="submit" 
                                        class="w-full bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition duration-200"
                                        onclick="return confirm('Are you sure you want to approve this appointment?')">
                                    <i class="fas fa-check mr-2"></i>Approve Appointment
                                </button>
                            </form>
                        @endif

                        @if($appointment->canBeCancelled())
                            <button type="button" 
                                    class="w-full bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg transition duration-200"
                                    onclick="showCancelModal()">
                                <i class="fas fa-times mr-2"></i>Cancel Appointment
                            </button>
                        @endif

                        @if($appointment->canBeRescheduled())
                            <a href="{{ route('admin.appointments.reschedule', $appointment) }}" 
                               class="w-full bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-2 rounded-lg transition duration-200 block text-center">
                                <i class="fas fa-calendar-alt mr-2"></i>Reschedule Appointment
                            </a>
                        @endif

                        <div class="border-t border-gray-200 dark:border-gray-700 pt-3">
                            <a href="mailto:{{ $appointment->patient->email }}" 
                               class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition duration-200 block text-center">
                                <i class="fas fa-envelope mr-2"></i>Email Patient
                            </a>
                        </div>

                        @if($appointment->patient->phone)
                        <a href="tel:{{ $appointment->patient->phone }}" 
                           class="w-full bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition duration-200 block text-center">
                            <i class="fas fa-phone mr-2"></i>Call Patient
                        </a>
                        @endif
                    </div>

                    <!-- Appointment Timeline -->
                    <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-3">Timeline</h3>
                        <div class="space-y-3">
                            <div class="flex items-center">
                                <div class="w-2 h-2 bg-blue-600 rounded-full mr-3"></div>
                                <div class="text-sm">
                                    <p class="text-gray-900 dark:text-white">Appointment created</p>
                                    <p class="text-gray-500 dark:text-gray-400">{{ $appointment->created_at->format('M j, Y g:i A') }}</p>
                                </div>
                            </div>

                            @if($appointment->confirmed_at)
                            <div class="flex items-center">
                                <div class="w-2 h-2 bg-green-600 rounded-full mr-3"></div>
                                <div class="text-sm">
                                    <p class="text-gray-900 dark:text-white">Appointment confirmed</p>
                                    <p class="text-gray-500 dark:text-gray-400">{{ $appointment->confirmed_at->format('M j, Y g:i A') }}</p>
                                </div>
                            </div>
                            @endif

                            @if($appointment->rescheduled_at)
                            <div class="flex items-center">
                                <div class="w-2 h-2 bg-yellow-600 rounded-full mr-3"></div>
                                <div class="text-sm">
                                    <p class="text-gray-900 dark:text-white">Appointment rescheduled</p>
                                    <p class="text-gray-500 dark:text-gray-400">{{ $appointment->rescheduled_at->format('M j, Y g:i A') }}</p>
                                </div>
                            </div>
                            @endif

                            @if($appointment->cancelled_at)
                            <div class="flex items-center">
                                <div class="w-2 h-2 bg-red-600 rounded-full mr-3"></div>
                                <div class="text-sm">
                                    <p class="text-gray-900 dark:text-white">Appointment cancelled</p>
                                    <p class="text-gray-500 dark:text-gray-400">{{ $appointment->cancelled_at->format('M j, Y g:i A') }}</p>
                                </div>
                            </div>
                            @endif

                            @if($appointment->completed_at)
                            <div class="flex items-center">
                                <div class="w-2 h-2 bg-purple-600 rounded-full mr-3"></div>
                                <div class="text-sm">
                                    <p class="text-gray-900 dark:text-white">Appointment completed</p>
                                    <p class="text-gray-500 dark:text-gray-400">{{ $appointment->completed_at->format('M j, Y g:i A') }}</p>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Cancel Modal -->
<div id="cancelModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white dark:bg-gray-800">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Cancel Appointment</h3>
            <form method="POST" action="{{ route('admin.appointments.cancel', $appointment) }}">
                @csrf
                <div class="mb-4">
                    <label for="cancellation_reason" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Cancellation Reason
                    </label>
                    <textarea name="cancellation_reason" id="cancellation_reason" rows="3" required
                              class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                              placeholder="Enter reason for cancellation..."></textarea>
                </div>
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="hideCancelModal()" 
                            class="px-4 py-2 bg-gray-300 dark:bg-gray-600 text-gray-700 dark:text-gray-300 rounded-md hover:bg-gray-400 dark:hover:bg-gray-500">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">
                        Confirm Cancellation
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
function showCancelModal() {
    document.getElementById('cancelModal').classList.remove('hidden');
}

function hideCancelModal() {
    document.getElementById('cancelModal').classList.add('hidden');
    document.getElementById('cancellation_reason').value = '';
}

// Close modal when clicking outside
document.getElementById('cancelModal').addEventListener('click', function(e) {
    if (e.target === this) {
        hideCancelModal();
    }
});
</script>
@endpush
