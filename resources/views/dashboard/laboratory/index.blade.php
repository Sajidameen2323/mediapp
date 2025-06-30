@extends('layouts.laboratory')

@section('title', 'Laboratory Dashboard')

@section('content')
<div class="p-4 sm:p-6 lg:p-8">
    <!-- Welcome Section -->
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Welcome back, {{ auth()->user()->name }}!</h1>
        <p class="mt-2 text-gray-600 dark:text-gray-400">{{ $laboratory->name }} - {{ now()->format('l, F j, Y') }}</p>
    </div>

    <!-- Laboratory Status Banner -->
    <div class="mb-8">
        <div class="rounded-lg border {{ $laboratory->is_available ? 'bg-green-50 border-green-200 dark:bg-green-900/20 dark:border-green-800' : 'bg-red-50 border-red-200 dark:bg-red-900/20 dark:border-red-800' }} p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    @if($laboratory->is_available)
                        <i class="fas fa-check-circle text-green-600 dark:text-green-400 text-xl"></i>
                    @else
                        <i class="fas fa-times-circle text-red-600 dark:text-red-400 text-xl"></i>
                    @endif
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium {{ $laboratory->is_available ? 'text-green-800 dark:text-green-200' : 'text-red-800 dark:text-red-200' }}">
                        Laboratory Status: {{ $laboratory->is_available ? 'Available' : 'Unavailable' }}
                    </h3>
                    <p class="mt-1 text-sm {{ $laboratory->is_available ? 'text-green-700 dark:text-green-300' : 'text-red-700 dark:text-red-300' }}">
                        @if($laboratory->is_available)
                            Your laboratory is currently accepting new appointments.
                        @else
                            Your laboratory is currently not accepting new appointments.
                        @endif
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Today's Appointments -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-calendar-day text-2xl text-blue-600 dark:text-blue-400"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ $stats['today']['total'] }}</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Today's Appointments</p>
                    <div class="mt-2 flex space-x-4 text-xs">
                        <span class="text-yellow-600 dark:text-yellow-400">{{ $stats['today']['pending'] }} Pending</span>
                        <span class="text-green-600 dark:text-green-400">{{ $stats['today']['confirmed'] }} Confirmed</span>
                        <span class="text-blue-600 dark:text-blue-400">{{ $stats['today']['completed'] }} Completed</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- This Week -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-calendar-week text-2xl text-green-600 dark:text-green-400"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ $stats['this_week']['total'] }}</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400">This Week</p>
                    <p class="mt-2 text-xs text-green-600 dark:text-green-400">Revenue: ${{ number_format($stats['this_week']['revenue'], 2) }}</p>
                </div>
            </div>
        </div>

        <!-- This Month -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-calendar-alt text-2xl text-purple-600 dark:text-purple-400"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ $stats['this_month']['total'] }}</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400">This Month</p>
                    <p class="mt-2 text-xs text-green-600 dark:text-green-400">Revenue: ${{ number_format($stats['this_month']['revenue'], 2) }}</p>
                </div>
            </div>
        </div>

        <!-- Pending Results -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-file-medical text-2xl text-orange-600 dark:text-orange-400"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ $stats['pending_results'] }}</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Pending Results</p>
                    <p class="mt-2 text-xs text-orange-600 dark:text-orange-400">Requires attention</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Two Column Layout -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Appointments -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow border border-gray-200 dark:border-gray-700">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Upcoming Appointments</h3>
                    <a href="{{ route('laboratory.appointments.index') }}" class="text-sm text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300">
                        View all <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
            </div>
            <div class="p-6">
                @if($recentAppointments->count() > 0)
                    <div class="space-y-4">
                        @foreach($recentAppointments as $appointment)
                            <div class="flex items-center p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                <div class="flex-shrink-0">
                                    <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900/30 rounded-full flex items-center justify-center">
                                        <i class="fas fa-user text-blue-600 dark:text-blue-400"></i>
                                    </div>
                                </div>
                                <div class="ml-4 flex-1">
                                    <h4 class="text-sm font-medium text-gray-900 dark:text-white">{{ $appointment->patient->name }}</h4>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">
                                        {{ $appointment->appointment_date->format('M d, Y') }} at {{ $appointment->start_time }}
                                    </p>
                                    @if($appointment->labTestRequest)
                                        <p class="text-xs text-gray-500 dark:text-gray-500">
                                            Test: {{ $appointment->labTestRequest->test_type }}
                                        </p>
                                    @endif
                                </div>
                                <div class="flex-shrink-0">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        @if($appointment->status === 'pending') bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300
                                        @elseif($appointment->status === 'confirmed') bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300
                                        @elseif($appointment->status === 'completed') bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300
                                        @else bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300 @endif">
                                        {{ ucfirst($appointment->status) }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <i class="fas fa-calendar-times text-4xl text-gray-400 dark:text-gray-600 mb-4"></i>
                        <p class="text-gray-600 dark:text-gray-400">No upcoming appointments</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Pending Appointments -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow border border-gray-200 dark:border-gray-700">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Pending Requests</h3>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300">
                        {{ $pendingAppointments->count() }} pending
                    </span>
                </div>
            </div>
            <div class="p-6">
                @if($pendingAppointments->count() > 0)
                    <div class="space-y-4">
                        @foreach($pendingAppointments as $appointment)
                            <div class="flex items-center p-4 bg-yellow-50 dark:bg-yellow-900/20 rounded-lg border border-yellow-200 dark:border-yellow-800">
                                <div class="flex-shrink-0">
                                    <div class="w-10 h-10 bg-yellow-100 dark:bg-yellow-900/30 rounded-full flex items-center justify-center">
                                        <i class="fas fa-clock text-yellow-600 dark:text-yellow-400"></i>
                                    </div>
                                </div>
                                <div class="ml-4 flex-1">
                                    <h4 class="text-sm font-medium text-gray-900 dark:text-white">{{ $appointment->patient->name }}</h4>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">
                                        {{ $appointment->appointment_date->format('M d, Y') }} at {{ $appointment->start_time }}
                                    </p>
                                    @if($appointment->labTestRequest)
                                        <p class="text-xs text-gray-500 dark:text-gray-500">
                                            Test: {{ $appointment->labTestRequest->test_type }}
                                        </p>
                                    @endif
                                </div>
                                <div class="flex-shrink-0">
                                    <a href="{{ route('laboratory.appointments.show', $appointment) }}" 
                                       class="inline-flex items-center px-3 py-1.5 bg-blue-600 text-white text-xs font-medium rounded-md hover:bg-blue-700 transition-colors duration-200">
                                        <i class="fas fa-eye mr-1"></i>
                                        Review
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <i class="fas fa-check-circle text-4xl text-green-400 dark:text-green-600 mb-4"></i>
                        <p class="text-gray-600 dark:text-gray-400">No pending requests</p>
                        <p class="text-sm text-gray-500 dark:text-gray-500 mt-1">All caught up!</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="mt-8">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow border border-gray-200 dark:border-gray-700 p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Quick Actions</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- View Appointments -->
                <a href="{{ route('laboratory.appointments.index') }}" 
                   class="flex items-center p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg border border-blue-200 dark:border-blue-800 hover:bg-blue-100 dark:hover:bg-blue-900/30 transition-colors duration-200">
                    <div class="flex-shrink-0">
                        <i class="fas fa-calendar-check text-xl text-blue-600 dark:text-blue-400"></i>
                    </div>
                    <div class="ml-3">
                        <h4 class="text-sm font-medium text-blue-900 dark:text-blue-100">Manage Appointments</h4>
                        <p class="text-xs text-blue-700 dark:text-blue-300">View and manage bookings</p>
                    </div>
                </a>

                <!-- Test Results -->
                <a href="{{ route('laboratory.results.index') }}" 
                   class="flex items-center p-4 bg-purple-50 dark:bg-purple-900/20 rounded-lg border border-purple-200 dark:border-purple-800 hover:bg-purple-100 dark:hover:bg-purple-900/30 transition-colors duration-200">
                    <div class="flex-shrink-0">
                        <i class="fas fa-file-medical text-xl text-purple-600 dark:text-purple-400"></i>
                    </div>
                    <div class="ml-3">
                        <h4 class="text-sm font-medium text-purple-900 dark:text-purple-100">Test Results</h4>
                        <p class="text-xs text-purple-700 dark:text-purple-300">Release lab results</p>
                    </div>
                </a>

                <!-- Settings -->
                <a href="{{ route('laboratory.settings.index') }}" 
                   class="flex items-center p-4 bg-gray-50 dark:bg-gray-700 rounded-lg border border-gray-200 dark:border-gray-600 hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors duration-200">
                    <div class="flex-shrink-0">
                        <i class="fas fa-cog text-xl text-gray-600 dark:text-gray-400"></i>
                    </div>
                    <div class="ml-3">
                        <h4 class="text-sm font-medium text-gray-900 dark:text-gray-100">Lab Settings</h4>
                        <p class="text-xs text-gray-700 dark:text-gray-300">Configure working hours</p>
                    </div>
                </a>

                <!-- Toggle Availability -->
                <button onclick="toggleAvailability()" 
                        class="flex items-center p-4 {{ $laboratory->is_available ? 'bg-red-50 dark:bg-red-900/20 border-red-200 dark:border-red-800 hover:bg-red-100 dark:hover:bg-red-900/30' : 'bg-green-50 dark:bg-green-900/20 border-green-200 dark:border-green-800 hover:bg-green-100 dark:hover:bg-green-900/30' }} rounded-lg border transition-colors duration-200">
                    <div class="flex-shrink-0">
                        <i class="fas {{ $laboratory->is_available ? 'fa-times-circle' : 'fa-check-circle' }} text-xl {{ $laboratory->is_available ? 'text-red-600 dark:text-red-400' : 'text-green-600 dark:text-green-400' }}"></i>
                    </div>
                    <div class="ml-3">
                        <h4 class="text-sm font-medium {{ $laboratory->is_available ? 'text-red-900 dark:text-red-100' : 'text-green-900 dark:text-green-100' }}">
                            {{ $laboratory->is_available ? 'Go Offline' : 'Go Online' }}
                        </h4>
                        <p class="text-xs {{ $laboratory->is_available ? 'text-red-700 dark:text-red-300' : 'text-green-700 dark:text-green-300' }}">
                            {{ $laboratory->is_available ? 'Stop accepting appointments' : 'Start accepting appointments' }}
                        </p>
                    </div>
                </button>
            </div>
        </div>
    </div>
</div>
@endsection
