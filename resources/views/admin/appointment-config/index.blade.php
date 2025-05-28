@extends('layouts.app')

@section('title', 'Appointment Configuration - Admin')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Appointment Configuration</h1>
                <p class="mt-2 text-gray-600 dark:text-gray-400">Manage appointment settings, holidays, and blocked time slots</p>
            </div>
            <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-medium rounded-lg transition-colors duration-200">
                <i class="fas fa-arrow-left mr-2"></i>
                Back to Dashboard
            </a>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg border dark:border-gray-700">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center">
                            <i class="fas fa-cogs text-white text-sm"></i>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Configuration Status</dt>
                            <dd class="text-lg font-medium text-gray-900 dark:text-white">
                                @if($config->is_active)
                                    <span class="text-green-600 dark:text-green-400">Active</span>
                                @else
                                    <span class="text-red-600 dark:text-red-400">Inactive</span>
                                @endif
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg border dark:border-gray-700">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-red-500 rounded-full flex items-center justify-center">
                            <i class="fas fa-calendar-times text-white text-sm"></i>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Active Holidays</dt>
                            <dd class="text-lg font-medium text-gray-900 dark:text-white">{{ $holidaysCount }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg border dark:border-gray-700">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-orange-500 rounded-full flex items-center justify-center">
                            <i class="fas fa-ban text-white text-sm"></i>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Blocked Slots</dt>
                            <dd class="text-lg font-medium text-gray-900 dark:text-white">{{ $blockedSlotsCount }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg border dark:border-gray-700">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-purple-500 rounded-full flex items-center justify-center">
                            <i class="fas fa-user-md text-white text-sm"></i>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Total Doctors</dt>
                            <dd class="text-lg font-medium text-gray-900 dark:text-white">{{ $totalDoctors }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Management Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- General Configuration -->
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg border dark:border-gray-700">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">General Configuration</h3>
                <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">Configure appointment settings, buffer times, payment options, and system preferences</p>
                <div class="mt-4">
                    <a href="{{ route('admin.appointment-config.edit') }}" class="block w-full bg-gray-900 dark:bg-gray-700 text-white px-4 py-2 rounded transition-colors duration-200 text-center">
                        <i class="fas fa-cog mr-2"></i>Configure Settings
                    </a>
                </div>
            </div>
        </div>

        <!-- Holiday Management -->
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg border dark:border-gray-700">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">Holiday Management</h3>
                <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">Manage holidays and special days when appointments are not available</p>
                <div class="mt-4">
                    <a href="{{ route('admin.appointment-config.holidays') }}" class="block w-full bg-gray-900 dark:bg-gray-700 text-white px-4 py-2 rounded transition-colors duration-200 text-center">
                        <i class="fas fa-calendar-times mr-2"></i>Manage Holidays
                    </a>
                </div>
            </div>
        </div>

        <!-- Blocked Time Slots -->
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg border dark:border-gray-700">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">Blocked Time Slots</h3>
                <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">Block specific time slots for maintenance, personal time, or emergencies</p>
                <div class="mt-4">
                    <a href="{{ route('admin.appointment-config.blocked-slots') }}" class="block w-full bg-gray-900 dark:bg-gray-700 text-white px-4 py-2 rounded transition-colors duration-200 text-center">
                        <i class="fas fa-ban mr-2"></i>Manage Blocked Slots
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Current Configuration Summary -->
    <div class="mt-8">
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg border dark:border-gray-700">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white mb-4">Current Configuration Summary</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div>
                        <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Booking Settings</h4>
                        <ul class="text-sm text-gray-600 dark:text-gray-400 space-y-1">
                            <li><strong>Max Days Ahead:</strong> {{ $config->max_booking_days_ahead }} days</li>
                            <li><strong>Min Hours Ahead:</strong> {{ $config->min_booking_hours_ahead }} hours</li>
                            <li><strong>Default Slot Duration:</strong> {{ $config->default_slot_duration }} minutes</li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Buffer Times</h4>
                        <ul class="text-sm text-gray-600 dark:text-gray-400 space-y-1">
                            <li><strong>Before Appointment:</strong> {{ $config->buffer_time_before }} minutes</li>
                            <li><strong>After Appointment:</strong> {{ $config->buffer_time_after }} minutes</li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Payment Settings</h4>
                        <ul class="text-sm text-gray-600 dark:text-gray-400 space-y-1">
                            <li><strong>Tax Rate:</strong> {{ number_format($config->tax_rate * 100, 2) }}%</li>
                            <li><strong>Payment Required:</strong> {{ $config->require_payment_on_booking ? 'Yes' : 'No' }}</li>
                            <li><strong>Deposit:</strong> {{ number_format($config->booking_deposit_percentage, 2) }}%</li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Cancellation Policy</h4>
                        <ul class="text-sm text-gray-600 dark:text-gray-400 space-y-1">
                            <li><strong>Allow Cancellation:</strong> {{ $config->allow_cancellation ? 'Yes' : 'No' }}</li>
                            <li><strong>Hours Limit:</strong> {{ $config->cancellation_hours_limit }} hours</li>
                            <li><strong>Allow Rescheduling:</strong> {{ $config->allow_rescheduling ? 'Yes' : 'No' }}</li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Approval Settings</h4>
                        <ul class="text-sm text-gray-600 dark:text-gray-400 space-y-1">
                            <li><strong>Auto Approve:</strong> {{ $config->auto_approve_appointments ? 'Yes' : 'No' }}</li>
                            <li><strong>Admin Approval:</strong> {{ $config->require_admin_approval ? 'Required' : 'Not Required' }}</li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Operating Hours</h4>
                        <ul class="text-sm text-gray-600 dark:text-gray-400 space-y-1">
                            <li><strong>Start Time:</strong> {{ \Carbon\Carbon::parse($config->default_start_time)->format('H:i') }}</li>
                            <li><strong>End Time:</strong> {{ \Carbon\Carbon::parse($config->default_end_time)->format('H:i') }}</li>
                            <li><strong>Timezone:</strong> {{ $config->timezone }}</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
