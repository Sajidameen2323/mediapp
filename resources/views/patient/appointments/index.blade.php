@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header Section -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8">
            <div class="mb-4 sm:mb-0">
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">My Appointments</h1>
                <p class="mt-2 text-gray-600 dark:text-gray-400">Manage and track your medical appointments</p>
            </div>
            <a href="{{ route('patient.appointments.create') }}"
                class="inline-flex items-center px-6 py-3 bg-gray-900 dark:bg-gray-700 text-white font-semibold rounded-lg shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200">
                <i class="fas fa-plus mr-2"></i>
                Book New Appointment
            </a>
        </div>

        {{-- Log all page errors --}}
        @if (session('error'))
            <div
                class="bg-red-100 dark:bg-red-900/20 border border-red-200 dark:border-red-800 text-red-800 dark:text-red-200 rounded-lg p-4 mb-6">
                <div class="flex items-center">
                    <i class="fas fa-exclamation-triangle mr-2"></i>
                    <span class="font-medium">{{ session('error') }}</span>
                </div>
            </div>
        @endif

        <!-- Filter Section -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 mb-8">
            <div class="p-6">
                <form method="GET" action="{{ route('patient.appointments.index') }}"
                    class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-6">
                    <div>
                        <label for="status"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Status</label>
                        <select name="status" id="status"
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                            <option value="all" {{ ($filters['status'] ?? 'all') == 'all' ? 'selected' : '' }}>All
                                Statuses</option>
                            <option value="pending" {{ ($filters['status'] ?? '') == 'pending' ? 'selected' : '' }}>Pending
                            </option>
                            <option value="confirmed" {{ ($filters['status'] ?? '') == 'confirmed' ? 'selected' : '' }}>
                                Confirmed</option>
                            <option value="completed" {{ ($filters['status'] ?? '') == 'completed' ? 'selected' : '' }}>
                                Completed</option>
                            <option value="cancelled" {{ ($filters['status'] ?? '') == 'cancelled' ? 'selected' : '' }}>
                                Cancelled</option>
                            <option value="no_show" {{ ($filters['status'] ?? '') == 'no_show' ? 'selected' : '' }}>No Show
                            </option>
                        </select>
                    </div>
                    <div>
                        <label for="date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Date
                            Filter</label>
                        <select name="date" id="date"
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                            <option value="all" {{ ($filters['date'] ?? 'upcoming') == 'all' ? 'selected' : '' }}>All
                                Dates</option>
                            <option value="upcoming"
                                {{ ($filters['date'] ?? 'upcoming') == 'upcoming' ? 'selected' : '' }}>Upcoming</option>
                            <option value="past" {{ ($filters['date'] ?? '') == 'past' ? 'selected' : '' }}>Past</option>
                            <option value="today" {{ ($filters['date'] ?? '') == 'today' ? 'selected' : '' }}>Today
                            </option>
                            <option value="week" {{ ($filters['date'] ?? '') == 'week' ? 'selected' : '' }}>This Week
                            </option>
                            <option value="custom" {{ ($filters['date'] ?? '') == 'custom' ? 'selected' : '' }}>Custom
                                Range</option>
                        </select>
                    </div>
                    <div id="custom-date-fields"
                        class="{{ ($filters['date'] ?? 'upcoming') == 'custom' ? '' : 'hidden' }}">
                        <label for="from_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">From
                            Date</label>
                        <input type="date" name="from_date" id="from_date"
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                            value="{{ $filters['from_date'] ?? '' }}">
                    </div>
                    <div id="custom-date-to-field"
                        class="{{ ($filters['date'] ?? 'upcoming') == 'custom' ? '' : 'hidden' }}">
                        <label for="to_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">To
                            Date</label>
                        <input type="date" name="to_date" id="to_date"
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                            value="{{ $filters['to_date'] ?? '' }}">
                    </div>
                    <div class="flex flex-col justify-end">
                        <label for="per_page"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Records per page</label>
                        <select name="per_page" id="per_page"
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                            <option value="5" {{ ($filters['per_page'] ?? 10) == 5 ? 'selected' : '' }}>5</option>
                            <option value="10" {{ ($filters['per_page'] ?? 10) == 10 ? 'selected' : '' }}>10</option>
                            <option value="15" {{ ($filters['per_page'] ?? 10) == 15 ? 'selected' : '' }}>15</option>
                            <option value="25" {{ ($filters['per_page'] ?? 10) == 25 ? 'selected' : '' }}>25</option>
                            <option value="50" {{ ($filters['per_page'] ?? 10) == 50 ? 'selected' : '' }}>50</option>
                        </select>
                    </div>
                    <div class="flex flex-col justify-end">
                        <div class="flex flex-col sm:flex-row gap-3">
                            <button type="submit"
                                class="flex-1 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800">
                                <i class="fas fa-search mr-2"></i>Filter
                            </button>
                            <a href="{{ route('patient.appointments.index') }}"
                                class="flex-1 px-4 py-2 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 font-medium rounded-lg transition-colors duration-200 text-center">
                                <i class="fas fa-times mr-2"></i>Clear
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div> <!-- Appointment Policies Info Panel -->
        @if ($config)
            <div
                class="bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 rounded-lg border border-blue-200 dark:border-blue-800 mb-8">
                <div class="p-6">
                    <div class="flex items-start space-x-3">
                        <div class="flex-shrink-0">
                            <i class="fas fa-info-circle text-blue-600 dark:text-blue-400 text-xl mt-0.5"></i>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-lg font-semibold text-blue-900 dark:text-blue-100 mb-3">Appointment Policies
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 text-sm">
                                @if ($config->allow_cancellation)
                                    <div class="flex items-center space-x-2 text-blue-800 dark:text-blue-200">
                                        <i class="fas fa-clock w-4"></i>
                                        <span><strong>Cancellation:</strong> {{ $config->cancellation_hours_limit }} hours
                                            before appointment</span>
                                    </div>
                                @else
                                    <div class="flex items-center space-x-2 text-red-600 dark:text-red-400">
                                        <i class="fas fa-ban w-4"></i>
                                        <span><strong>Cancellation:</strong> Not allowed</span>
                                    </div>
                                @endif

                                @if ($config->allow_rescheduling)
                                    <div class="flex items-center space-x-2 text-blue-800 dark:text-blue-200">
                                        <i class="fas fa-calendar-alt w-4"></i>
                                        <span><strong>Rescheduling:</strong> {{ $config->reschedule_hours_limit }} hours
                                            before appointment</span>
                                    </div>
                                @else
                                    <div class="flex items-center space-x-2 text-gray-600 dark:text-gray-400">
                                        <i class="fas fa-calendar-times w-4"></i>
                                        <span><strong>Rescheduling:</strong> Not available</span>
                                    </div>
                                @endif

                                <div class="flex items-center space-x-2 text-blue-800 dark:text-blue-200">
                                    <i class="fas fa-calendar-plus w-4"></i>
                                    <span><strong>Booking Window:</strong> Up to {{ $config->max_booking_days_ahead }} days
                                        ahead</span>
                                </div>

                                @if ($config->max_appointments_per_patient_per_day > 1)
                                    <div class="flex items-center space-x-2 text-blue-800 dark:text-blue-200">
                                        <i class="fas fa-user-clock w-4"></i>
                                        <span><strong>Daily Limit:</strong>
                                            {{ $config->max_appointments_per_patient_per_day }} appointments per day</span>
                                    </div>
                                @endif

                                @if ($config->send_reminder_email)
                                    <div class="flex items-center space-x-2 text-green-600 dark:text-green-400">
                                        <i class="fas fa-bell w-4"></i>
                                        <span><strong>Reminders:</strong> {{ $config->reminder_hours_before }} hours
                                            before</span>
                                    </div>
                                @endif

                                @if ($config->auto_approve_appointments)
                                    <div class="flex items-center space-x-2 text-green-600 dark:text-green-400">
                                        <i class="fas fa-check-circle w-4"></i>
                                        <span><strong>Approval:</strong> Automatic confirmation</span>
                                    </div>
                                @else
                                    <div class="flex items-center space-x-2 text-yellow-600 dark:text-yellow-400">
                                        <i class="fas fa-hourglass-half w-4"></i>
                                        <span><strong>Approval:</strong> Manual review required</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif <!-- Appointments Grid -->
        @if ($appointments->count() > 0)
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                @foreach ($appointments as $appointment)
                    <div
                        class="appointment-card bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 hover:shadow-lg transition-all duration-200 hover:-translate-y-1">
                        <!-- Card Header -->
                        <div class="flex items-center justify-between p-6 border-b border-gray-200 dark:border-gray-700">
                            <div class="flex items-center space-x-3">
                                <div class="bg-blue-100 dark:bg-blue-900 p-2 rounded-lg">
                                    <i class="fas fa-calendar-alt text-blue-600 dark:text-blue-400"></i>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                                        {{ \Carbon\Carbon::parse($appointment->appointment_date)->format('M d, Y') }}
                                    </h3>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                        {{ \Carbon\Carbon::parse($appointment->start_time)->format('h:i A') }}
                                    </p>
                                </div>
                            </div>
                            <span
                                class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium
                            {{ $appointment->status == 'confirmed'
                                ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300'
                                : ($appointment->status == 'pending'
                                    ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300'
                                    : ($appointment->status == 'cancelled'
                                        ? 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300'
                                        : ($appointment->status == 'completed'
                                            ? 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300'
                                            : 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300'))) }}">
                                <div
                                    class="w-2 h-2 rounded-full mr-2
                                {{ $appointment->status == 'confirmed'
                                    ? 'bg-green-500'
                                    : ($appointment->status == 'pending'
                                        ? 'bg-yellow-500'
                                        : ($appointment->status == 'cancelled'
                                            ? 'bg-red-500'
                                            : ($appointment->status == 'completed'
                                                ? 'bg-blue-500'
                                                : 'bg-gray-500'))) }}">
                                </div>
                                {{ ucfirst($appointment->status) }}
                            </span>
                        </div>

                        <!-- Card Body -->
                        <div class="p-6 space-y-4">
                            <!-- Doctor and Priority Row -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div class="flex items-center space-x-3">
                                    <div class="bg-gray-100 dark:bg-gray-700 p-2 rounded-lg">
                                        <i class="fas fa-user-md text-gray-600 dark:text-gray-400"></i>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wide">Doctor
                                        </p>
                                        <p class="text-sm font-semibold text-gray-900 dark:text-white">Dr.
                                            {{ $appointment->doctor->user->name }}</p>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-3">
                                    <div class="bg-gray-100 dark:bg-gray-700 p-2 rounded-lg">
                                        <i class="fas fa-exclamation-triangle text-gray-600 dark:text-gray-400"></i>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wide">
                                            Priority
                                        </p>
                                        <span
                                            class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                        {{ $appointment->priority == 'high' || $appointment->priority == 'urgent'
                                            ? 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300'
                                            : ($appointment->priority == 'medium'
                                                ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300'
                                                : 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300') }}">
                                            {{ ucfirst($appointment->priority) }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <!-- Type and Reason -->
                            <div class="space-y-3">
                                <div>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1">Type
                                    </p>
                                    <p class="text-sm text-gray-900 dark:text-white">
                                        {{ ucfirst(str_replace('_', ' ', $appointment->appointment_type)) }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1">Reason
                                    </p>
                                    <p class="text-sm text-gray-700 dark:text-gray-300">
                                        {{ Str::limit($appointment->reason, 100) }}</p>
                                </div>
                            </div>

                            @if ($appointment->symptoms)
                                <div>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1">
                                        Symptoms</p>
                                    <p class="text-sm text-gray-700 dark:text-gray-300">
                                        {{ Str::limit($appointment->symptoms, 80) }}</p>
                                </div>
                            @endif
                        </div>

                        <!-- Card Footer -->
                        <div
                            class="px-6 py-4 bg-gray-50 dark:bg-gray-700/50 border-t border-gray-200 dark:border-gray-700 rounded-b-lg">
                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                                <x-appointment.action-buttons :appointment="$appointment" :config="$config" layout="horizontal"
                                    size="sm" />
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Rating Modals for each appointment -->
            @foreach ($appointments as $appointment)
                @if ($appointment->canBeRated())
                    <x-appointment.rating-modal :appointment="$appointment" modal-id="ratingModal_{{ $appointment->id }}" />
                @endif
            @endforeach

            <!-- Pagination and Results Info -->
            @if ($appointments->total() > 0)
                <div class="mt-8 mb-4">
                    <!-- Results Summary -->
                    <div
                        class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-4 px-4 py-3 bg-gray-50 dark:bg-gray-800/50 rounded-lg border border-gray-200 dark:border-gray-700">
                        <div class="text-sm text-gray-700 dark:text-gray-300 mb-2 sm:mb-0">
                            <i class="fas fa-info-circle mr-2 text-blue-500"></i>
                            Showing <span class="font-semibold">{{ $appointments->firstItem() ?? 0 }}</span>
                            to <span class="font-semibold">{{ $appointments->lastItem() ?? 0 }}</span>
                            of <span class="font-semibold">{{ $appointments->total() }}</span> appointments
                        </div>
                        <div class="text-sm text-gray-600 dark:text-gray-400">
                            <i class="fas fa-layer-group mr-1"></i>
                            {{ $appointments->perPage() }} per page
                        </div>
                    </div>

                    <!-- Pagination Controls -->
                    <div class="flex justify-center">
                        {{ $appointments->withQueryString()->links('pagination::custom') }}
                    </div>
                </div>
            @endif
        @else
            <!-- Empty State -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
                <div class="text-center py-16 px-6">
                    <div
                        class="bg-gray-100 dark:bg-gray-700 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-calendar-times text-3xl text-gray-400 dark:text-gray-500"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">No Appointments Found</h3>
                    <p class="text-gray-500 dark:text-gray-400 mb-8 max-w-md mx-auto">
                        You haven't booked any appointments yet or no appointments match your filter criteria.
                    </p>
                    <a href="{{ route('patient.appointments.create') }}"
                        class="inline-flex items-center px-6 py-3 bg-gray-800 dark:bg-gray-700 text-white font-semibold rounded-lg shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200">
                        <i class="fas fa-plus mr-2"></i>
                        Book Your First Appointment
                    </a>
                </div>
            </div>
        @endif
    </div>
@endsection


@push('styles')
    <style>
        /* Smooth transitions for all interactive elements */
        .transition-all {
            transition: all 0.2s ease-in-out;
        }

        /* Enhanced hover effects for appointment cards */
        .appointment-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }

        /* Custom focus styles for accessibility */
        .focus-ring:focus {
            outline: 2px solid transparent;
            outline-offset: 2px;
            box-shadow: 0 0 0 2px #3B82F6;
        }

        /* Dark mode enhancements */
        @media (prefers-color-scheme: dark) {
            .appointment-card:hover {
                box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.3), 0 10px 10px -5px rgba(0, 0, 0, 0.2);
            }
        }
    </style>
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const dateFilter = document.getElementById('date');
            const customDateFields = document.getElementById('custom-date-fields');
            const customDateToField = document.getElementById('custom-date-to-field');
            const perPageSelect = document.getElementById('per_page');
            const form = dateFilter.closest('form');

            function toggleCustomDateFields() {
                if (dateFilter.value === 'custom') {
                    customDateFields.classList.remove('hidden');
                    customDateToField.classList.remove('hidden');
                } else {
                    customDateFields.classList.add('hidden');
                    customDateToField.classList.add('hidden');
                }
            }

            // Initial state
            toggleCustomDateFields();

            // Listen for changes
            dateFilter.addEventListener('change', toggleCustomDateFields);

            // Auto-submit form when per_page changes
            perPageSelect.addEventListener('change', function() {
                form.submit();
            });
        });
    </script>
@endpush
