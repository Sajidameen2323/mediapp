@extends('layouts.admin')

@section('content')
    <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
        <div class="container mx-auto px-4 py-8">
            <!-- Header -->
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Appointment Management</h1>
                <div class="flex space-x-3">
                    <a href="{{ route('admin.appointments.export', request()->query()) }}"
                        class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition duration-200">
                        <i class="fas fa-download mr-2"></i>Export
                    </a>
                </div>
            </div> <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-7 gap-4 mb-6">
                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
                    <div class="flex items-center">
                        <div class="p-2 bg-blue-100 dark:bg-blue-900 rounded-lg">
                            <i class="fas fa-calendar-alt text-blue-600 dark:text-blue-400"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total</p>
                            <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $stats['total'] }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
                    <div class="flex items-center">
                        <div class="p-2 bg-yellow-100 dark:bg-yellow-900 rounded-lg">
                            <i class="fas fa-clock text-yellow-600 dark:text-yellow-400"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Pending</p>
                            <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $stats['pending'] }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
                    <div class="flex items-center">
                        <div class="p-2 bg-green-100 dark:bg-green-900 rounded-lg">
                            <i class="fas fa-check text-green-600 dark:text-green-400"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Confirmed</p>
                            <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $stats['confirmed'] }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
                    <div class="flex items-center">
                        <div class="p-2 bg-red-100 dark:bg-red-900 rounded-lg">
                            <i class="fas fa-times text-red-600 dark:text-red-400"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Cancelled</p>
                            <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $stats['cancelled'] }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
                    <div class="flex items-center">
                        <div class="p-2 bg-purple-100 dark:bg-purple-900 rounded-lg">
                            <i class="fas fa-check-circle text-purple-600 dark:text-purple-400"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Completed</p>
                            <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $stats['completed'] }}</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
                    <div class="flex items-center">
                        <div class="p-2 bg-indigo-100 dark:bg-indigo-900 rounded-lg">
                            <i class="fas fa-calendar-day text-indigo-600 dark:text-indigo-400"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Today</p>
                            <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $stats['today'] }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
                    <div class="flex items-center">
                        <div class="p-2 bg-orange-100 dark:bg-orange-900 rounded-lg">
                            <i class="fas fa-credit-card text-orange-600 dark:text-orange-400"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Unpaid</p>
                            <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $stats['unpaid'] ?? 0 }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filters -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow mb-6 p-6">
                <form method="GET" action="{{ route('admin.appointments.index') }}"
                    class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div>
                        <label for="search"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Search</label>
                        <input type="text" name="search" id="search" value="{{ request('search') }}"
                            placeholder="Patient name, email, or ID"
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                    </div>
                    <div>
                        <label for="status"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Status</label>
                        <select name="status" id="status"
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                            <option value="all" {{ request('status') === 'all' ? 'selected' : '' }}>All Status</option>
                            <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending
                            </option>
                            <option value="confirmed" {{ request('status') === 'confirmed' ? 'selected' : '' }}>Confirmed
                            </option>
                            <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled
                            </option>
                            <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed
                            </option>
                            <option value="no_show" {{ request('status') === 'no_show' ? 'selected' : '' }}>No Show
                            </option>
                        </select>
                    </div>

                    <div>
                        <label for="payment_status"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Payment Status</label>
                        <select name="payment_status" id="payment_status"
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                            <option value="all" {{ request('payment_status') === 'all' ? 'selected' : '' }}>All Payment
                                Status</option>
                            <option value="pending" {{ request('payment_status') === 'pending' ? 'selected' : '' }}>Pending
                            </option>
                            <option value="paid" {{ request('payment_status') === 'paid' ? 'selected' : '' }}>Paid
                            </option>
                            <option value="partially_paid"
                                {{ request('payment_status') === 'partially_paid' ? 'selected' : '' }}>Partially Paid
                            </option>
                            <option value="refunded" {{ request('payment_status') === 'refunded' ? 'selected' : '' }}>
                                Refunded</option>
                        </select>
                    </div>

                    <div>
                        <label for="doctor_id"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Doctor</label>
                        <select name="doctor_id" id="doctor_id"
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                            <option value="">All Doctors</option>
                            @foreach ($doctors as $doctor)
                                <option value="{{ $doctor->id }}"
                                    {{ request('doctor_id') == $doctor->id ? 'selected' : '' }}>
                                    {{ $doctor->user->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="date_from" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">From
                            Date</label>
                        <input type="date" name="date_from" id="date_from" value="{{ request('date_from') }}"
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                    </div>

                    <div class="flex items-end space-x-2">
                        <div class="flex-1">
                            <label for="date_to"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">To Date</label>
                            <input type="date" name="date_to" id="date_to" value="{{ request('date_to') }}"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                        </div>

                        <button type="submit"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md transition duration-200">
                            <i class="fas fa-search"></i>
                        </button>
                        <a href="{{ route('admin.appointments.index') }}"
                            class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md transition duration-200">
                            <i class="fas fa-times"></i>
                        </a>
                    </div>
                </form>
            </div>

            <!-- Bulk Actions -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow mb-6 p-4" id="bulk-actions" style="display: none;">
                <form id="bulk-form" method="POST" action="{{ route('admin.appointments.bulk-update') }}">
                    @csrf
                    <div class="flex items-center space-x-4">
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">
                            <span id="selected-count">0</span> appointments selected
                        </span>
                        <select name="bulk_action" id="bulk_action"
                            class="px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                            <option value="">Select Action</option>
                            <option value="approve">Approve</option>
                            <option value="cancel">Cancel</option>
                        </select>
                        <input type="text" name="bulk_reason" id="bulk_reason"
                            placeholder="Reason (required for cancellation)"
                            class="px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                            style="display: none;">
                        <button type="submit"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md transition duration-200">
                            Apply
                        </button>
                    </div>
                </form>
            </div>        <!-- Appointments Table -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
            <!-- Scroll Hint for smaller screens -->
            <div class="lg:hidden bg-blue-50 dark:bg-blue-900 px-4 py-2 text-sm text-blue-700 dark:text-blue-300 border-b border-blue-200 dark:border-blue-700">
                <i class="fas fa-info-circle mr-1"></i>
                Scroll horizontally to view all columns
            </div>
            
            <!-- Table Container with Horizontal Scroll -->
            <div class="overflow-x-auto">
                <table class="w-full min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-4 py-3 text-left w-12">
                                <input type="checkbox" id="select-all" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider w-20">ID</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Patient</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Doctor</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Date & Time</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider w-32">Status</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider w-36">Payment</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider w-48">Actions</th>
                        </tr>
                    </thead>                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($appointments as $appointment)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                <td class="px-4 py-4 whitespace-nowrap">
                                    <input type="checkbox" name="appointment_ids[]" value="{{ $appointment->id }}" 
                                           class="appointment-checkbox rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                    #{{ $appointment->id }}
                                </td>
                                <td class="px-4 py-4">
                                    <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $appointment->patient->name }}</div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400">{{ $appointment->patient->email }}</div>
                                </td>
                                <td class="px-4 py-4">
                                    <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $appointment->doctor->user->name }}</div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400">{{ $appointment->doctor->specialization }}</div>
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900 dark:text-white">
                                        {{ $appointment->appointment_date->format('M j, Y') }}
                                    </div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400">
                                        {{ \Carbon\Carbon::parse($appointment->appointment_time)->format('g:i A') }}
                                        @if($appointment->end_time)
                                            - {{ \Carbon\Carbon::parse($appointment->end_time)->format('g:i A') }}
                                        @endif
                                    </div>
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap">
                                    @php
                                        $statusColors = [
                                            'pending' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300',
                                            'confirmed' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300',
                                            'cancelled' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300',
                                            'completed' => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300',
                                            'no_show' => 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-300',
                                        ];
                                    @endphp
                                    <span class="status-badge px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusColors[$appointment->status] ?? 'bg-gray-100 text-gray-800' }}">
                                        {{ ucfirst(str_replace('_', ' ', $appointment->status)) }}
                                    </span>
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap">
                                    <div class="flex flex-col space-y-1">
                                        @php
                                            $paymentStatusColors = [
                                                'pending' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300',
                                                'paid' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300',
                                                'partially_paid' => 'bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-300',
                                                'refunded' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300',
                                            ];
                                        @endphp
                                        <span class="status-badge px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $paymentStatusColors[$appointment->payment_status ?? 'pending'] ?? 'bg-gray-100 text-gray-800' }}">
                                            {{ ucfirst(str_replace('_', ' ', $appointment->payment_status ?? 'pending')) }}
                                        </span>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">
                                            ${{ number_format($appointment->total_amount ?? 0, 2) }}
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex items-center space-x-1">
                                        <a href="{{ route('admin.appointments.show', $appointment) }}" 
                                           class="action-button inline-flex items-center px-2 py-1 bg-blue-100 hover:bg-blue-200 text-blue-700 hover:text-blue-800 dark:bg-blue-900 dark:hover:bg-blue-800 dark:text-blue-300 dark:hover:text-blue-200 rounded-md text-xs font-medium transition-colors duration-200"
                                           title="View Details">
                                            <i class="fas fa-eye w-3 h-3"></i>
                                        </a>
                                        
                                        @if($appointment->status === 'pending')
                                            <form method="POST" action="{{ route('admin.appointments.approve', $appointment) }}" class="inline">
                                                @csrf
                                                <button type="submit" 
                                                        class="action-button inline-flex items-center px-2 py-1 bg-green-100 hover:bg-green-200 text-green-700 hover:text-green-800 dark:bg-green-900 dark:hover:bg-green-800 dark:text-green-300 dark:hover:text-green-200 rounded-md text-xs font-medium transition-colors duration-200" 
                                                        title="Approve" 
                                                        onclick="return confirm('Are you sure you want to approve this appointment?')">
                                                    <i class="fas fa-check w-3 h-3"></i>
                                                </button>
                                            </form>
                                        @endif

                                        @if($appointment->canBeRescheduled())
                                            <a href="{{ route('admin.appointments.reschedule', $appointment) }}" 
                                               class="action-button inline-flex items-center px-2 py-1 bg-yellow-100 hover:bg-yellow-200 text-yellow-700 hover:text-yellow-800 dark:bg-yellow-900 dark:hover:bg-yellow-800 dark:text-yellow-300 dark:hover:text-yellow-200 rounded-md text-xs font-medium transition-colors duration-200"
                                               title="Reschedule">
                                                <i class="fas fa-calendar-alt w-3 h-3"></i>
                                            </a>
                                        @endif

                                        @if($appointment->canBeCancelled())
                                            <button type="button" 
                                                    class="action-button inline-flex items-center px-2 py-1 bg-red-100 hover:bg-red-200 text-red-700 hover:text-red-800 dark:bg-red-900 dark:hover:bg-red-800 dark:text-red-300 dark:hover:text-red-200 rounded-md text-xs font-medium transition-colors duration-200"
                                                    title="Cancel" 
                                                    onclick="showCancelModal({{ $appointment->id }})">
                                                <i class="fas fa-times w-3 h-3"></i>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>                        @empty
                            <tr>
                                <td colspan="8" class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">
                                    <div class="flex flex-col items-center">
                                        <i class="fas fa-calendar-times text-4xl mb-2 text-gray-300 dark:text-gray-600"></i>
                                        <p class="text-lg font-medium">No appointments found</p>
                                        <p class="text-sm">Try adjusting your search criteria</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($appointments->hasPages())
                <div class="px-4 py-4 border-t border-gray-200 dark:border-gray-700">
                    {{ $appointments->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
        </div>
    </div>

    <!-- Cancel Modal -->
    <div id="cancelModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white dark:bg-gray-800">
            <div class="mt-3">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Cancel Appointment</h3>
                <form id="cancelForm" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label for="cancellation_reason"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
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
                        <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">
                            Confirm Cancellation
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        /* Custom scrollbar styling for table */
        .scrollbar-thin {
            scrollbar-width: thin;
        }

        .scrollbar-thin::-webkit-scrollbar {
            height: 8px;
        }

        .scrollbar-thin::-webkit-scrollbar-track {
            background: #f1f5f9;
        }

        .scrollbar-thin::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }

        .scrollbar-thin::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        /* Dark mode scrollbar */
        .dark .scrollbar-thin::-webkit-scrollbar-track {
            background: #1f2937;
        }

        .dark .scrollbar-thin::-webkit-scrollbar-thumb {
            background: #4b5563;
        }

        .dark .scrollbar-thin::-webkit-scrollbar-thumb:hover {
            background: #6b7280;
        }

        /* Ensure table container doesn't affect page layout */
        .table-container {
            position: relative;
            max-width: 100%;
            overflow: hidden;
        }

        /* Add subtle scroll shadow */
        .scroll-shadow {
            position: relative;
        }

        .scroll-shadow::after {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            bottom: 0;
            width: 20px;
            background: linear-gradient(90deg, transparent, rgba(0, 0, 0, 0.1));
            pointer-events: none;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .scroll-shadow.has-scroll::after {
            opacity: 1;
        }

        /* Responsive table improvements */
        @media (max-width: 1200px) {
            .table-container {
                border-radius: 0.5rem;
                box-shadow: inset 0 0 0 1px rgba(0, 0, 0, 0.1);
            }
        }

        /* Action buttons enhancements */
        .action-button {
            transition: all 0.2s ease-in-out;
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        }

        .action-button:hover {
            transform: translateY(-1px);
            box-shadow: 0 2px 4px 0 rgba(0, 0, 0, 0.1);
        }

        .action-button:active {
            transform: translateY(0);
        }

        /* Tooltip improvements */
        [title] {
            position: relative;
        }

        /* Status badge improvements */
        .status-badge {
            transition: all 0.2s ease-in-out;
        }

        .status-badge:hover {
            transform: scale(1.05);
        }
    </style>
@endpush

@push('scripts')
    <script>
        // Improved scroll handling for table
        document.addEventListener('DOMContentLoaded', function() {
            const tableContainer = document.querySelector('.overflow-x-auto');
            const table = tableContainer.querySelector('table');

            // Add scroll shadow effect
            function updateScrollShadow() {
                const scrollLeft = tableContainer.scrollLeft;
                const scrollWidth = tableContainer.scrollWidth;
                const clientWidth = tableContainer.clientWidth;

                if (scrollWidth > clientWidth) {
                    tableContainer.parentElement.classList.add('scroll-shadow');
                    if (scrollLeft < scrollWidth - clientWidth - 10) {
                        tableContainer.parentElement.classList.add('has-scroll');
                    } else {
                        tableContainer.parentElement.classList.remove('has-scroll');
                    }
                } else {
                    tableContainer.parentElement.classList.remove('scroll-shadow', 'has-scroll');
                }
            }

            // Check on load and scroll
            updateScrollShadow();
            tableContainer.addEventListener('scroll', updateScrollShadow);
            window.addEventListener('resize', updateScrollShadow);
        });

        // Bulk selection functionality
        document.getElementById('select-all').addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('.appointment-checkbox');
            checkboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
            updateBulkActions();
        });

        document.querySelectorAll('.appointment-checkbox').forEach(checkbox => {
            checkbox.addEventListener('change', updateBulkActions);
        });

        function updateBulkActions() {
            const checkedBoxes = document.querySelectorAll('.appointment-checkbox:checked');
            const bulkActions = document.getElementById('bulk-actions');
            const selectedCount = document.getElementById('selected-count');
            const bulkForm = document.getElementById('bulk-form');

            selectedCount.textContent = checkedBoxes.length;

            if (checkedBoxes.length > 0) {
                bulkActions.style.display = 'block';

                // Add selected IDs to form
                const existingInputs = bulkForm.querySelectorAll('input[name="appointment_ids[]"]');
                existingInputs.forEach(input => input.remove());

                checkedBoxes.forEach(checkbox => {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'appointment_ids[]';
                    input.value = checkbox.value;
                    bulkForm.appendChild(input);
                });
            } else {
                bulkActions.style.display = 'none';
            }
        }

        // Show/hide reason field based on bulk action
        document.getElementById('bulk_action').addEventListener('change', function() {
            const reasonField = document.getElementById('bulk_reason');
            if (this.value === 'cancel') {
                reasonField.style.display = 'block';
                reasonField.required = true;
            } else {
                reasonField.style.display = 'none';
                reasonField.required = false;
            }
        });

        // Cancel modal functions
        function showCancelModal(appointmentId) {
            const modal = document.getElementById('cancelModal');
            const form = document.getElementById('cancelForm');
            form.action = `/admin/appointments/${appointmentId}/cancel`;
            modal.classList.remove('hidden');
        }

        function hideCancelModal() {
            const modal = document.getElementById('cancelModal');
            modal.classList.add('hidden');
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
