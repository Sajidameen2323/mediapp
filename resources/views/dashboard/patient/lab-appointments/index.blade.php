@extends('layouts.patient')

@section('title', 'My Lab Appointments')

@section('content')


<div class="min-h-screen bg-gray-50 dark:bg-gray-900">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Page Header -->
        <div class="mb-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white">My Lab Appointments</h1>
                    <p class="mt-2 text-gray-600 dark:text-gray-400">View and manage your laboratory appointments</p>
                </div>
                <div class="mt-4 sm:mt-0 flex items-center space-x-3">
                    <!-- Stats Badge -->
                    <div class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-sm">
                        <i class="fas fa-calendar-check text-blue-500 dark:text-blue-400 mr-2"></i>
                        <span class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $labAppointments->total() }} Total Appointments</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 mb-8">
            <div class="p-6">
                <form method="GET" action="{{ route('patient.lab-appointments.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <!-- Status Filter -->
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Status</label>
                        <select name="status" id="status" 
                                class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">All Statuses</option>
                            <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="confirmed" {{ request('status') === 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                            <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
                        </select>
                    </div>

                    <!-- Date From -->
                    <div>
                        <label for="date_from" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">From Date</label>
                        <input type="date" name="date_from" id="date_from" value="{{ request('date_from') }}"
                               class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <!-- Date To -->
                    <div>
                        <label for="date_to" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">To Date</label>
                        <input type="date" name="date_to" id="date_to" value="{{ request('date_to') }}"
                               class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <!-- Filter Actions -->
                    <div class="flex items-end space-x-2">
                        <button type="submit" 
                                class="flex-1 inline-flex items-center justify-center px-4 py-2 bg-blue-600 hover:bg-blue-700 dark:bg-blue-700 dark:hover:bg-blue-600 text-white font-medium rounded-lg transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                            <i class="fas fa-search mr-2"></i>
                            Filter
                        </button>
                        @if(request()->hasAny(['status', 'date_from', 'date_to']))
                            <a href="{{ route('patient.lab-appointments.index') }}" 
                               class="inline-flex items-center px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                <i class="fas fa-times"></i>
                            </a>
                        @endif
                    </div>
                </form>
            </div>
        </div>

        @if($labAppointments->isEmpty())
            <!-- Empty State -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
                <div class="p-12 text-center">
                    <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-gray-100 dark:bg-gray-700 mb-4">
                        <i class="fas fa-calendar-times text-2xl text-gray-400 dark:text-gray-500"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">No Lab Appointments Found</h3>
                    <p class="text-gray-600 dark:text-gray-400 max-w-md mx-auto">
                        @if(request()->hasAny(['status', 'date_from', 'date_to']))
                            No appointments match your current filters. Try adjusting your search criteria.
                        @else
                            You haven't booked any lab appointments yet. Book your first appointment for your lab tests.
                        @endif
                    </p>
                    <div class="mt-6">
                        <a href="{{ route('patient.lab-tests.index') }}" 
                           class="inline-flex items-center px-6 py-3 bg-green-600 hover:bg-green-700 dark:bg-green-700 dark:hover:bg-green-600 text-white font-medium rounded-lg transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                            <i class="fas fa-vial mr-2"></i>
                            View Lab Tests
                        </a>
                    </div>
                </div>
            </div>
        @else
            <!-- Appointments Grid -->
            <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @foreach($labAppointments as $appointment)
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg rounded-xl border border-gray-200 dark:border-gray-700 hover:shadow-xl dark:hover:shadow-2xl transition-all duration-300">
                        <!-- Appointment Header -->
                        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">
                                        {{ $appointment->labTestRequest->test_name }}
                                    </h3>
                                    <div class="flex items-center text-sm text-gray-600 dark:text-gray-400 mb-2">
                                        <i class="fas fa-building mr-2 text-blue-500 dark:text-blue-400"></i>
                                        {{ $appointment->laboratory->name }}
                                    </div>
                                    <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                                        <i class="fas fa-user-md mr-2 text-green-500 dark:text-green-400"></i>
                                        @if($appointment->labTestRequest->medicalReport && $appointment->labTestRequest->medicalReport->doctor)
                                            {{ $appointment->labTestRequest->medicalReport->doctor->user->name }}
                                        @else
                                            <span class="text-gray-400 dark:text-gray-500">No doctor assigned</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="flex flex-col items-end space-y-2">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium 
                                        @if($appointment->status === 'pending') bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300
                                        @elseif($appointment->status === 'confirmed') bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300
                                        @elseif($appointment->status === 'completed') bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300
                                        @elseif($appointment->status === 'cancelled') bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300
                                        @else bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300
                                        @endif">
                                        @if($appointment->status === 'pending')
                                            <i class="fas fa-clock mr-1.5"></i>
                                        @elseif($appointment->status === 'confirmed')
                                            <i class="fas fa-check-circle mr-1.5"></i>
                                        @elseif($appointment->status === 'completed')
                                            <i class="fas fa-check-double mr-1.5"></i>
                                        @elseif($appointment->status === 'cancelled')
                                            <i class="fas fa-times-circle mr-1.5"></i>
                                        @elseif($appointment->status === 'rejected')
                                            <i class="fas fa-exclamation-triangle mr-1.5"></i>
                                        @endif
                                        {{ ucfirst($appointment->status) }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Appointment Details -->
                        <div class="p-6 space-y-4">
                            <!-- Date and Time -->
                            <div class="flex items-center space-x-4">
                                <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                                    <i class="fas fa-calendar text-blue-500 dark:text-blue-400 mr-2"></i>
                                    {{ $appointment->appointment_date->format('M d, Y') }}
                                </div>
                                <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                                    <i class="fas fa-clock text-green-500 dark:text-green-400 mr-2"></i>
                                    {{ $appointment->formatted_time }}
                                </div>
                            </div>

                            <!-- Appointment Number -->
                            <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                                <i class="fas fa-hashtag text-purple-500 dark:text-purple-400 mr-2"></i>
                                {{ $appointment->appointment_number }}
                            </div>

                            <!-- Cost -->
                            @if($appointment->final_cost || $appointment->estimated_cost)
                                <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                                    <i class="fas fa-dollar-sign text-yellow-500 dark:text-yellow-400 mr-2"></i>
                                    Cost: ${{ number_format($appointment->final_cost ?? $appointment->estimated_cost, 2) }}
                                </div>
                            @endif

                            <!-- Special Instructions -->
                            @if($appointment->lab_instructions)
                                <div class="bg-blue-50 dark:bg-blue-900/20 rounded-lg p-3">
                                    <div class="flex items-start">
                                        <i class="fas fa-info-circle text-blue-500 dark:text-blue-400 mr-2 mt-0.5"></i>
                                        <div>
                                            <p class="text-sm font-medium text-blue-800 dark:text-blue-300">Lab Instructions:</p>
                                            <p class="text-sm text-blue-700 dark:text-blue-400 mt-1">{{ $appointment->lab_instructions }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <!-- Fasting Requirements -->
                            @if($appointment->requires_fasting)
                                <div class="bg-orange-50 dark:bg-orange-900/20 rounded-lg p-3">
                                    <div class="flex items-start">
                                        <i class="fas fa-utensils text-orange-500 dark:text-orange-400 mr-2 mt-0.5"></i>
                                        <div>
                                            <p class="text-sm font-medium text-orange-800 dark:text-orange-300">Fasting Required:</p>
                                            <p class="text-sm text-orange-700 dark:text-orange-400 mt-1">
                                                {{ $appointment->fasting_hours ?? 12 }} hours before your appointment
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <!-- Appointment Actions -->
                        <div class="px-6 pb-6">
                            <div class="flex items-center justify-between">
                                <a href="{{ route('patient.lab-appointments.show', $appointment) }}" 
                                   class="inline-flex items-center px-3 py-2 text-sm font-medium text-blue-600 dark:text-blue-400 hover:text-blue-500 dark:hover:text-blue-300 transition-colors duration-200">
                                    <i class="fas fa-eye mr-2"></i>
                                    View Details
                                </a>
                                
                                @if($appointment->canBeCancelled())
                                    <form action="{{ route('patient.lab-appointments.cancel', $appointment) }}" method="POST" 
                                          onsubmit="return confirm('Are you sure you want to cancel this appointment?')">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="cancellation_reason" value="Patient requested cancellation">
                                        <button type="submit" 
                                                class="inline-flex items-center px-3 py-2 text-sm font-medium text-red-600 dark:text-red-400 hover:text-red-500 dark:hover:text-red-300 transition-colors duration-200">
                                            <i class="fas fa-times mr-2"></i>
                                            Cancel
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            @if($labAppointments->hasPages())
                <div class="mt-8 flex justify-center">
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-4">
                        {{ $labAppointments->appends(request()->query())->links() }}
                    </div>
                </div>
            @endif
        @endif
    </div>
</div>
@endsection
