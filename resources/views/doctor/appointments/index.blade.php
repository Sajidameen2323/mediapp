@extends('layouts.app')

@section('title', 'My Appointments - Medi App')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Success/Error Messages -->
        @if (session('success'))
            <div class="mb-6 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-700 rounded-md p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-check-circle text-green-400"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-green-800 dark:text-green-200">
                            {{ session('success') }}
                        </p>
                    </div>
                    <div class="ml-auto pl-3">
                        <div class="-mx-1.5 -my-1.5">
                            <button type="button"
                                onclick="this.parentElement.parentElement.parentElement.parentElement.remove()"
                                class="inline-flex bg-green-50 dark:bg-green-900/20 rounded-md p-1.5 text-green-500 hover:bg-green-100 dark:hover:bg-green-900/40 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-600">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        @if (session('error'))
            <div class="mb-6 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-700 rounded-md p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-exclamation-circle text-red-400"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-red-800 dark:text-red-200">
                            {{ session('error') }}
                        </p>
                    </div>
                    <div class="ml-auto pl-3">
                        <div class="-mx-1.5 -my-1.5">
                            <button type="button"
                                onclick="this.parentElement.parentElement.parentElement.parentElement.remove()"
                                class="inline-flex bg-red-50 dark:bg-red-900/20 rounded-md p-1.5 text-red-500 hover:bg-red-100 dark:hover:bg-red-900/40 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-600">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-6 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-700 rounded-md p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-exclamation-triangle text-red-400"></i>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-red-800 dark:text-red-200">
                            There were some errors with your request:
                        </h3>
                        <div class="mt-2 text-sm text-red-700 dark:text-red-300">
                            <ul class="list-disc list-inside space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        @endif <!-- Header Section -->
        <div class="mb-6">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 border border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-semibold text-gray-900 dark:text-white mb-1">
                            <i class="fas fa-calendar-medical mr-2 text-blue-600"></i>My Appointments
                        </h1>
                        <p class="text-gray-600 dark:text-gray-400">{{ now()->format('l, F j, Y') }}</p>
                    </div>
                    <div class="flex items-center space-x-3">
                        <a href="{{ route('doctor.appointments.calendar') }}"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                            <i class="fas fa-calendar-alt mr-2"></i>Calendar
                        </a>
                        <a href="{{ route('doctor.dashboard') }}"
                            class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                            <i class="fas fa-home mr-2"></i>Dashboard
                        </a>
                    </div>
                </div>
            </div>
        </div> <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-6">
            <div class="bg-white dark:bg-gray-800 p-4 rounded-lg border border-gray-200 dark:border-gray-700">
                <div class="flex items-center">
                    <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900/50 rounded-lg flex items-center justify-center mr-3">
                        <i class="fas fa-calendar-check text-blue-600 dark:text-blue-400"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Total</p>
                        <p class="text-xl font-semibold text-gray-900 dark:text-white">{{ $stats['total'] }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 p-4 rounded-lg border border-gray-200 dark:border-gray-700">
                <div class="flex items-center">
                    <div
                        class="w-10 h-10 bg-green-100 dark:bg-green-900/50 rounded-lg flex items-center justify-center mr-3">
                        <i class="fas fa-clock text-green-600 dark:text-green-400"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Today</p>
                        <p class="text-xl font-semibold text-gray-900 dark:text-white">{{ $stats['today'] }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white dark:bg-gray-800 p-4 rounded-lg border border-gray-200 dark:border-gray-700">
                <div class="flex items-center">
                    <div
                        class="w-10 h-10 bg-purple-100 dark:bg-purple-900/50 rounded-lg flex items-center justify-center mr-3">
                        <i class="fas fa-arrow-up text-purple-600 dark:text-purple-400"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Upcoming</p>
                        <p class="text-xl font-semibold text-gray-900 dark:text-white">{{ $stats['upcoming'] }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 p-4 rounded-lg border border-gray-200 dark:border-gray-700">
                <div class="flex items-center">
                    <div
                        class="w-10 h-10 bg-yellow-100 dark:bg-yellow-900/50 rounded-lg flex items-center justify-center mr-3">
                        <i class="fas fa-hourglass-half text-yellow-600 dark:text-yellow-400"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Pending</p>
                        <p class="text-xl font-semibold text-gray-900 dark:text-white">{{ $stats['pending'] }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 p-4 rounded-lg border border-gray-200 dark:border-gray-700">
                <div class="flex items-center">
                    <div
                        class="w-10 h-10 bg-emerald-100 dark:bg-emerald-900/50 rounded-lg flex items-center justify-center mr-3">
                        <i class="fas fa-check-circle text-emerald-600 dark:text-emerald-400"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Confirmed</p>
                        <p class="text-xl font-semibold text-gray-900 dark:text-white">{{ $stats['confirmed'] }}</p>
                    </div>
                </div>
            </div>
        </div> <!-- Filters -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm mb-6 border border-gray-200 dark:border-gray-700">
            <div class="p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                    <i class="fas fa-filter mr-2 text-blue-600"></i>Filter Appointments
                </h3>

                <div class="grid grid-cols-1 lg:grid-cols-4 gap-4 mb-4">
                    <!-- Search Filter -->
                    <div class="lg:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Search</label>
                        <input type="text" id="searchFilter"
                            placeholder="Search by patient name, email, appointment number, or service..."
                            value="{{ request('search') }}"
                            class="w-full rounded-md border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-3 py-2 text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                    </div>

                    <!-- Status Filter -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Status</label>
                        <select id="statusFilter"
                            class="w-full rounded-md border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-3 py-2 text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
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
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Period</label>
                        <select id="dateFilter"
                            class="w-full rounded-md border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-3 py-2 text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                            <option value="today" {{ $date === 'today' ? 'selected' : '' }}>Today</option>
                            <option value="upcoming" {{ $date === 'upcoming' ? 'selected' : '' }}>Upcoming</option>
                            <option value="week" {{ $date === 'week' ? 'selected' : '' }}>This Week</option>
                            <option value="month" {{ $date === 'month' ? 'selected' : '' }}>This Month</option>
                            <option value="all" {{ $date === 'all' ? 'selected' : '' }}>All Time</option>
                        </select>
                    </div>
                </div>

                <div class="flex space-x-3 pt-4 border-t border-gray-200 dark:border-gray-700">
                    <button onclick="refreshAppointments()"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                        <i class="fas fa-sync-alt mr-2"></i>Refresh
                    </button>
                    <button onclick="clearFilters()"
                        class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                        <i class="fas fa-times mr-2"></i>Clear
                    </button>
                </div>
            </div>
        </div> <!-- Appointments List -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
            <div class="p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                    <i class="fas fa-calendar-check mr-2 text-blue-600"></i>Appointments
                </h3>

                @if ($appointments->count() > 0)
                    <div class="space-y-4">
                        @foreach ($appointments as $appointment)
                            <div
                                class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg p-4">
                                <div class="flex items-center justify-between mb-3">
                                    <!-- Status Badge -->
                                    <span
                                        class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                        {{ $appointment->status === 'pending' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-200' : '' }}
                                        {{ $appointment->status === 'confirmed' ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-200' : '' }}
                                        {{ $appointment->status === 'completed' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-200' : '' }}
                                        {{ $appointment->status === 'cancelled' ? 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-200' : '' }}
                                        {{ $appointment->status === 'no_show' ? 'bg-gray-100 text-gray-800 dark:bg-gray-900/30 dark:text-gray-200' : '' }}">
                                        {{ ucfirst($appointment->status) }}
                                    </span>

                                    <!-- Quick Actions Dropdown -->
                                    <div class="relative">
                                        <button type="button"
                                            onclick="toggleDropdown('dropdown-{{ $appointment->id }}')"
                                            class="p-2 text-gray-400 hover:text-gray-600 dark:text-gray-500 dark:hover:text-gray-300 rounded">
                                            <i class="fas fa-ellipsis-v"></i>
                                        </button>

                                        <div id="dropdown-{{ $appointment->id }}"
                                            class="hidden absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 z-10">
                                            <div class="py-1">
                                                <a href="{{ route('doctor.appointments.show', $appointment) }}"
                                                    class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                                                    <i class="fas fa-eye mr-2"></i>View Details
                                                </a>

                                                @if ($appointment->canBeConfirmedByDoctor())
                                                    <form
                                                        action="{{ route('doctor.appointments.confirm', $appointment) }}"
                                                        method="POST" class="inline w-full">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit"
                                                            class="flex items-center w-full px-4 py-2 text-sm text-green-700 dark:text-green-300 hover:bg-green-50 dark:hover:bg-green-900/20">
                                                            <i class="fas fa-check mr-2"></i>Confirm
                                                        </button>
                                                    </form>
                                                @endif

                                                @if ($appointment->status === 'confirmed')
                                                    <form
                                                        action="{{ route('doctor.appointments.complete', $appointment) }}"
                                                        method="POST" class="inline w-full">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit"
                                                            class="flex items-center w-full px-4 py-2 text-sm text-blue-700 dark:text-blue-300 hover:bg-blue-50 dark:hover:bg-blue-900/20">
                                                            <i class="fas fa-check-double mr-2"></i>Mark Complete
                                                        </button>
                                                    </form>
                                                @endif

                                                @if (!in_array($appointment->status, ['cancelled', 'completed']))
                                                    <button onclick="showCancelModal({{ $appointment->id }})"
                                                        class="flex items-center w-full px-4 py-2 text-sm text-red-700 dark:text-red-300 hover:bg-red-50 dark:hover:bg-red-900/20">
                                                        <i class="fas fa-times mr-2"></i>Cancel
                                                    </button>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="flex items-start space-x-3">
                                    <!-- Patient Avatar -->
                                    <div
                                        class="w-10 h-10 bg-blue-500 rounded-full flex items-center justify-center text-white font-medium text-sm">
                                        {{ strtoupper(substr($appointment->patient->name ?? 'UN', 0, 2)) }}
                                    </div>

                                    <!-- Patient and Appointment Info -->
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center space-x-2 mb-1">
                                            <h4 class="font-semibold text-gray-900 dark:text-white">
                                                {{ $appointment->patient->name ?? 'Unknown Patient' }}
                                            </h4>
                                            <span
                                                class="text-xs text-gray-500 dark:text-gray-400 bg-gray-100 dark:bg-gray-700 px-2 py-1 rounded">
                                                #{{ $appointment->appointment_number }}
                                            </span>
                                        </div>

                                        @if ($appointment->patient->email)
                                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">
                                                <i class="fas fa-envelope mr-1"></i>{{ $appointment->patient->email }}
                                            </p>
                                        @endif

                                        <div class="grid grid-cols-1 md:grid-cols-3 gap-3 text-sm">
                                            <div class="flex items-center text-gray-600 dark:text-gray-300">
                                                <i class="fas fa-calendar mr-2 text-blue-500"></i>
                                                <span>{{ $appointment->appointment_date->format('M d, Y') }}</span>
                                            </div>
                                            <div class="flex items-center text-gray-600 dark:text-gray-300">
                                                <i class="fas fa-clock mr-2 text-green-500"></i>
                                                <span>{{ \Carbon\Carbon::parse($appointment->start_time)->format('h:i A') }}</span>
                                            </div>
                                            <div class="flex items-center text-gray-600 dark:text-gray-300">
                                                <i class="fas fa-stethoscope mr-2 text-purple-500"></i>
                                                <span>{{ $appointment->service->name }}</span>
                                            </div>
                                        </div>

                                        @if ($appointment->reason)
                                            <div class="mt-3 p-3 bg-gray-50 dark:bg-gray-700 rounded text-sm">
                                                <p class="text-gray-700 dark:text-gray-300">
                                                    <i class="fas fa-comment-medical mr-2 text-yellow-500"></i>
                                                    <strong>Reason:</strong> {{ Str::limit($appointment->reason, 100) }}
                                                </p>
                                            </div>
                                        @endif

                                        @if ($appointment->notes)
                                            <div class="mt-3 p-3 bg-amber-50 dark:bg-amber-900/20 rounded text-sm">
                                                <p class="text-amber-800 dark:text-amber-200">
                                                    <i class="fas fa-sticky-note mr-2"></i>
                                                    <strong>Notes:</strong> {{ Str::limit($appointment->notes, 100) }}
                                                </p>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <!-- Action Buttons -->
                                <div
                                    class="mt-4 pt-3 border-t border-gray-200 dark:border-gray-700 flex items-center justify-between">
                                    <span class="text-sm text-gray-500 dark:text-gray-400">
                                        {{ $appointment->appointment_date->diffForHumans() }}
                                    </span>

                                    <div class="flex space-x-2">
                                        <a href="{{ route('doctor.appointments.show', $appointment) }}"
                                            class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-sm">
                                            <i class="fas fa-eye mr-1"></i>View
                                        </a>

                                        @if ($appointment->status === 'pending')
                                            <form action="{{ route('doctor.appointments.confirm', $appointment) }}"
                                                method="POST" class="inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit"
                                                    class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded text-sm">
                                                    <i class="fas fa-check mr-1"></i>Confirm
                                                </button>
                                            </form>
                                        @endif

                                        @if ($appointment->status === 'confirmed')
                                            <form action="{{ route('doctor.appointments.complete', $appointment) }}"
                                                method="POST" class="inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit"
                                                    class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-sm">
                                                    <i class="fas fa-check-double mr-1"></i>Complete
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    <div class="mt-6 pt-4 border-t border-gray-200 dark:border-gray-700">
                        {{ $appointments->appends(['status' => $status, 'date' => $date, 'search' => $search])->links() }}
                    </div>
                @else
                    <div class="text-center py-12">
                        <div
                            class="w-16 h-16 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-calendar-times text-2xl text-gray-400 dark:text-gray-500"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">No appointments found</h3>
                        <p class="text-gray-500 dark:text-gray-400 mb-4">No appointments match your current filters.</p>
                        <button onclick="clearFilters()"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded font-medium">
                            Clear Filters
                        </button>
                    </div>
                @endif
            </div>
        </div>
    </div> <!-- Cancel Appointment Modal -->
    <div id="cancelModal" class="fixed inset-0 bg-black bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-6 border w-full max-w-md shadow-lg rounded-lg bg-white dark:bg-gray-800">
            <div class="text-center">
                <div
                    class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 dark:bg-red-900/50 mb-4">
                    <i class="fas fa-exclamation-triangle text-red-600 dark:text-red-400"></i>
                </div>

                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Cancel Appointment</h3>
                <p class="text-gray-600 dark:text-gray-300 mb-4 text-sm">
                    Please provide a reason for cancelling this appointment.
                </p>

                <form id="cancelForm" method="POST" onsubmit="return validateCancelForm()" class="space-y-4">
                    @csrf
                    @method('PATCH')

                    <div class="text-left">
                        <label for="cancellation_reason"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Cancellation Reason <span class="text-red-500">*</span>
                        </label>
                        <textarea name="cancellation_reason" id="cancellation_reason" rows="3" required
                            class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md text-sm focus:border-red-500 focus:ring-1 focus:ring-red-500 px-3 py-2"
                            placeholder="Please provide a reason for cancelling this appointment..."></textarea>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Minimum 10 characters required</p>
                    </div>

                    <div class="flex space-x-3 pt-4">
                        <button type="submit"
                            class="flex-1 bg-red-600 hover:bg-red-700 text-white py-2 px-4 rounded font-medium">
                            <i class="fas fa-times mr-2"></i>Cancel Appointment
                        </button>
                        <button type="button" onclick="hideCancelModal()"
                            class="flex-1 bg-gray-600 hover:bg-gray-700 text-white py-2 px-4 rounded font-medium">
                            <i class="fas fa-arrow-left mr-2"></i>Go Back
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize filter functionality
            initializeFilters();

            // Initialize action button confirmations
            initializeActionButtons();

            // Initialize modal functionality
            initializeModals();
        });

        function initializeFilters() {
            const statusFilter = document.getElementById('statusFilter');
            const dateFilter = document.getElementById('dateFilter');
            const searchFilter = document.getElementById('searchFilter');

            if (statusFilter) {
                statusFilter.addEventListener('change', refreshAppointments);
            }

            if (dateFilter) {
                dateFilter.addEventListener('change', refreshAppointments);
            }

            if (searchFilter) {
                // Add debounced search functionality
                let searchTimeout;
                searchFilter.addEventListener('input', function() {
                    clearTimeout(searchTimeout);
                    searchTimeout = setTimeout(refreshAppointments, 500);
                });

                // Clear search when escape is pressed
                searchFilter.addEventListener('keydown', function(e) {
                    if (e.key === 'Escape') {
                        this.value = '';
                        refreshAppointments();
                    }
                });
            }
        }

        function refreshAppointments() {
            const status = document.getElementById('statusFilter')?.value || 'all';
            const date = document.getElementById('dateFilter')?.value || 'today';
            const search = document.getElementById('searchFilter')?.value || '';

            // Show loading state
            showLoadingState();

            const url = new URL(window.location.href);
            url.searchParams.set('status', status);
            url.searchParams.set('date', date);

            if (search.trim()) {
                url.searchParams.set('search', search.trim());
            } else {
                url.searchParams.delete('search');
            }

            window.location.href = url.toString();
        }

        function showLoadingState() {
            const refreshBtn = document.querySelector('button[onclick="refreshAppointments()"]');
            if (refreshBtn) {
                refreshBtn.disabled = true;
                refreshBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Loading...';
            }
        }

        function initializeActionButtons() {
            // Add confirmation to action buttons
            const confirmButtons = document.querySelectorAll('form[action*="/confirm"] button[type="submit"]');
            const completeButtons = document.querySelectorAll('form[action*="/complete"] button[type="submit"]');
            const noShowButtons = document.querySelectorAll('form[action*="/no-show"] button[type="submit"]');

            confirmButtons.forEach(button => {
                button.addEventListener('click', function(e) {
                    if (!confirm('Are you sure you want to confirm this appointment?')) {
                        e.preventDefault();
                    }
                });
            });

            completeButtons.forEach(button => {
                button.addEventListener('click', function(e) {
                    if (!confirm('Are you sure you want to mark this appointment as completed?')) {
                        e.preventDefault();
                    }
                });
            });

            noShowButtons.forEach(button => {
                button.addEventListener('click', function(e) {
                    if (!confirm('Are you sure you want to mark this appointment as no-show?')) {
                        e.preventDefault();
                    }
                });
            });
        }

        function initializeModals() {
            // Close modal when clicking outside
            const modal = document.getElementById('cancelModal');
            if (modal) {
                modal.addEventListener('click', function(e) {
                    if (e.target === modal) {
                        hideCancelModal();
                    }
                });
            }

            // Handle escape key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    hideCancelModal();
                }
            });
        }

        function showCancelModal(appointmentId) {
            const modal = document.getElementById('cancelModal');
            const form = document.getElementById('cancelForm');
            const textarea = form?.querySelector('textarea[name="cancellation_reason"]');

            if (!modal || !form) {
                console.error('Modal or form not found');
                return;
            }

            // Set form action
            form.action = `/doctor/appointments/${appointmentId}/cancel`;

            // Clear previous input
            if (textarea) {
                textarea.value = '';
                textarea.focus();
            }

            // Show modal
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function hideCancelModal() {
            const modal = document.getElementById('cancelModal');
            if (modal) {
                modal.classList.add('hidden');
                document.body.style.overflow = '';
            }
        }

        // Form validation for cancel modal
        function validateCancelForm() {
            const form = document.getElementById('cancelForm');
            const textarea = form?.querySelector('textarea[name="cancellation_reason"]');

            if (!textarea || textarea.value.trim().length < 10) {
                alert('Please provide a cancellation reason (minimum 10 characters).');
                textarea?.focus();
                return false;
            }

            return true;
        }

        function clearFilters() {
            const statusFilter = document.getElementById('statusFilter');
            const dateFilter = document.getElementById('dateFilter');
            const searchFilter = document.getElementById('searchFilter');

            if (statusFilter) statusFilter.value = 'all';
            if (dateFilter) dateFilter.value = 'today';
            if (searchFilter) searchFilter.value = '';

            window.location.href = window.location.pathname;
        }

        function toggleDropdown(dropdownId) {
            const dropdown = document.getElementById(dropdownId);
            const isHidden = dropdown.classList.contains('hidden');

            // Close all other dropdowns
            document.querySelectorAll('[id^="dropdown-"]').forEach(d => {
                if (d.id !== dropdownId) {
                    d.classList.add('hidden');
                }
            });

            // Toggle current dropdown
            if (isHidden) {
                dropdown.classList.remove('hidden');
            } else {
                dropdown.classList.add('hidden');
            }
        }

        // Close dropdowns when clicking outside
        document.addEventListener('click', function(event) {
            const isDropdownButton = event.target.closest('[onclick*="toggleDropdown"]');
            const isDropdownContent = event.target.closest('[id^="dropdown-"]');

            if (!isDropdownButton && !isDropdownContent) {
                document.querySelectorAll('[id^="dropdown-"]').forEach(dropdown => {
                    dropdown.classList.add('hidden');
                });
            }
        });
    </script>
@endpush
