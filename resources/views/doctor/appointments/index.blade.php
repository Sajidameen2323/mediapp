@extends('layouts.app')

@section('title', 'My Appointments - Medi App')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header Section -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">My Appointments</h1>
                <p class="mt-2 text-gray-600 dark:text-gray-400">Manage your appointment schedule</p>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('doctor.appointments.calendar') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors duration-200">
                    <i class="fas fa-calendar-alt mr-2"></i>Calendar View
                </a>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-5 gap-6 mb-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-calendar-check text-2xl text-blue-600"></i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Total</dt>
                            <dd class="text-lg font-medium text-gray-900 dark:text-white">{{ $stats['total'] }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-clock text-2xl text-green-600"></i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Today</dt>
                            <dd class="text-lg font-medium text-gray-900 dark:text-white">{{ $stats['today'] }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-arrow-up text-2xl text-purple-600"></i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Upcoming</dt>
                            <dd class="text-lg font-medium text-gray-900 dark:text-white">{{ $stats['upcoming'] }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-hourglass-half text-2xl text-yellow-600"></i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Pending</dt>
                            <dd class="text-lg font-medium text-gray-900 dark:text-white">{{ $stats['pending'] }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-check-circle text-2xl text-green-600"></i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Confirmed</dt>
                            <dd class="text-lg font-medium text-gray-900 dark:text-white">{{ $stats['confirmed'] }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg mb-6">
        <div class="px-4 py-5 sm:p-6">
            <div class="flex flex-wrap items-center justify-between gap-4">
                <div class="flex flex-wrap items-center space-x-4">
                    <!-- Status Filter -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Status</label>
                        <select id="statusFilter" class="rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm">
                            <option value="all" {{ $status === 'all' ? 'selected' : '' }}>All Status</option>
                            <option value="pending" {{ $status === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="confirmed" {{ $status === 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                            <option value="completed" {{ $status === 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="cancelled" {{ $status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            <option value="no_show" {{ $status === 'no_show' ? 'selected' : '' }}>No Show</option>
                        </select>
                    </div>

                    <!-- Date Filter -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Period</label>
                        <select id="dateFilter" class="rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm">
                            <option value="today" {{ $date === 'today' ? 'selected' : '' }}>Today</option>
                            <option value="upcoming" {{ $date === 'upcoming' ? 'selected' : '' }}>Upcoming</option>
                            <option value="week" {{ $date === 'week' ? 'selected' : '' }}>This Week</option>
                            <option value="month" {{ $date === 'month' ? 'selected' : '' }}>This Month</option>
                            <option value="all" {{ $date === 'all' ? 'selected' : '' }}>All Time</option>
                        </select>
                    </div>
                </div>

                <div class="flex space-x-2">
                    <button onclick="refreshAppointments()" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors duration-200">
                        <i class="fas fa-sync-alt mr-2"></i>Refresh
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Appointments List -->
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            @if($appointments->count() > 0)
                <div class="space-y-4">
                    @foreach($appointments as $appointment)
                        <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4 hover:shadow-md transition-shadow duration-200">
                            <div class="flex items-center justify-between">
                                <div class="flex-1">
                                    <div class="flex items-center space-x-4">
                                        <div class="flex-shrink-0">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                {{ $appointment->status === 'pending' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' : '' }}
                                                {{ $appointment->status === 'confirmed' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : '' }}
                                                {{ $appointment->status === 'completed' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200' : '' }}
                                                {{ $appointment->status === 'cancelled' ? 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' : '' }}
                                                {{ $appointment->status === 'no_show' ? 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200' : '' }}">
                                                {{ ucfirst($appointment->status) }}
                                            </span>
                                        </div>
                                        
                                        <div class="flex-1">
                                            <div class="flex items-center space-x-2">
                                                <h4 class="text-lg font-semibold text-gray-900 dark:text-white">
                                                    {{ $appointment->patient_name }}
                                                </h4>
                                                <span class="text-sm text-gray-500 dark:text-gray-400">
                                                    (#{{ $appointment->appointment_number }})
                                                </span>
                                            </div>
                                            
                                            <div class="mt-1 flex items-center space-x-6 text-sm text-gray-500 dark:text-gray-400">
                                                <span>
                                                    <i class="fas fa-calendar mr-1"></i>
                                                    {{ $appointment->appointment_date->format('M d, Y') }}
                                                </span>
                                                <span>
                                                    <i class="fas fa-clock mr-1"></i>
                                                    {{ $appointment->formatted_time }}
                                                </span>
                                                <span>
                                                    <i class="fas fa-stethoscope mr-1"></i>
                                                    {{ $appointment->service->name }}
                                                </span>
                                                <span>
                                                    <i class="fas fa-phone mr-1"></i>
                                                    {{ $appointment->patient_phone }}
                                                </span>
                                            </div>
                                            
                                            @if($appointment->patient_notes)
                                                <div class="mt-2">
                                                    <p class="text-sm text-gray-600 dark:text-gray-400">
                                                        <i class="fas fa-sticky-note mr-1"></i>
                                                        {{ Str::limit($appointment->patient_notes, 100) }}
                                                    </p>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="flex items-center space-x-2">
                                    <a href="{{ route('doctor.appointments.show', $appointment) }}" 
                                       class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-sm font-medium transition-colors duration-200">
                                        <i class="fas fa-eye mr-1"></i>View
                                    </a>
                                    
                                    @if($appointment->status === 'pending')
                                        <form action="{{ route('doctor.appointments.confirm', $appointment) }}" method="POST" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded text-sm font-medium transition-colors duration-200">
                                                <i class="fas fa-check mr-1"></i>Confirm
                                            </button>
                                        </form>
                                    @endif
                                    
                                    @if($appointment->status === 'confirmed')
                                        <form action="{{ route('doctor.appointments.complete', $appointment) }}" method="POST" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="bg-purple-600 hover:bg-purple-700 text-white px-3 py-1 rounded text-sm font-medium transition-colors duration-200">
                                                <i class="fas fa-check-double mr-1"></i>Complete
                                            </button>
                                        </form>
                                        
                                        <form action="{{ route('doctor.appointments.no-show', $appointment) }}" method="POST" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="bg-gray-600 hover:bg-gray-700 text-white px-3 py-1 rounded text-sm font-medium transition-colors duration-200">
                                                <i class="fas fa-user-times mr-1"></i>No Show
                                            </button>
                                        </form>
                                    @endif
                                    
                                    @if(!in_array($appointment->status, ['cancelled', 'completed']))
                                        <button onclick="showCancelModal({{ $appointment->id }})" 
                                                class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded text-sm font-medium transition-colors duration-200">
                                            <i class="fas fa-times mr-1"></i>Cancel
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                <!-- Pagination -->
                <div class="mt-6">
                    {{ $appointments->appends(request()->query())->links() }}
                </div>
            @else
                <div class="text-center py-12">
                    <i class="fas fa-calendar-times text-6xl text-gray-400 dark:text-gray-600 mb-4"></i>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">No appointments found</h3>
                    <p class="text-gray-500 dark:text-gray-400">No appointments match your current filters.</p>
                </div>
            @endif
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
            
            <form id="cancelForm" method="POST">
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
function refreshAppointments() {
    const status = document.getElementById('statusFilter').value;
    const date = document.getElementById('dateFilter').value;
    
    const url = new URL(window.location.href);
    url.searchParams.set('status', status);
    url.searchParams.set('date', date);
    
    window.location.href = url.toString();
}

function showCancelModal(appointmentId) {
    const modal = document.getElementById('cancelModal');
    const form = document.getElementById('cancelForm');
    form.action = `/doctor/appointments/${appointmentId}/cancel`;
    modal.classList.remove('hidden');
}

function hideCancelModal() {
    const modal = document.getElementById('cancelModal');
    modal.classList.add('hidden');
}

// Auto-refresh filters on change
document.getElementById('statusFilter').addEventListener('change', refreshAppointments);
document.getElementById('dateFilter').addEventListener('change', refreshAppointments);
</script>
@endsection
