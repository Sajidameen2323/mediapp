@extends('layouts.app')

@section('title', 'Schedule Management - Doctor Dashboard')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Schedule Management</h1>
                <p class="mt-2 text-gray-600 dark:text-gray-400">View your working hours and availability</p>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('doctor.dashboard') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors duration-200">
                    <i class="fas fa-arrow-left mr-2"></i>Back to Dashboard
                </a>
            </div>
        </div>
    </div>

    <!-- Weekly Schedule Grid -->
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white">Weekly Schedule</h3>
        </div>
        
        <div class="p-6">
            <div class="grid grid-cols-7 gap-4">
                @php
                    $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
                    $dayNames = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
                @endphp
                
                @foreach($days as $index => $day)
                    <div class="text-center">
                        <h4 class="font-medium text-gray-900 dark:text-white mb-3">{{ $dayNames[$index] }}</h4>
                        
                        @php
                            $daySchedules = $schedules->where('day_of_week', $day);
                        @endphp
                        
                        @if($daySchedules->count() > 0)
                            @foreach($daySchedules as $schedule)
                                <div class="mb-2 p-3 bg-{{ $schedule->is_available ? 'green' : 'red' }}-100 dark:bg-{{ $schedule->is_available ? 'green' : 'red' }}-900 rounded-md">
                                    <div class="text-sm font-medium text-{{ $schedule->is_available ? 'green' : 'red' }}-800 dark:text-{{ $schedule->is_available ? 'green' : 'red' }}-200">
                                        {{ $schedule->start_time->format('H:i') }} - {{ $schedule->end_time->format('H:i') }}
                                    </div>
                                    <div class="text-xs text-{{ $schedule->is_available ? 'green' : 'red' }}-600 dark:text-{{ $schedule->is_available ? 'green' : 'red' }}-300">
                                        {{ $schedule->is_available ? 'Available' : 'Unavailable' }}
                                    </div>
                                    {{-- <div class="mt-1 flex justify-center space-x-1">
                                        <button class="text-blue-600 hover:text-blue-800 text-xs">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="text-red-600 hover:text-red-800 text-xs">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div> --}}
                                </div>
                            @endforeach
                        @else
                            <div class="p-4 text-gray-400 dark:text-gray-600 text-sm">
                                No schedule set
                            </div>
                            {{-- <button class="text-blue-600 hover:text-blue-800 text-sm">
                                <i class="fas fa-plus mr-1"></i>Add
                            </button> --}}
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Schedule Statistics -->
    <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-clock text-2xl text-blue-600"></i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Total Working Hours</dt>
                            <dd class="text-lg font-medium text-gray-900 dark:text-white">
                                @php
                                    $totalHours = 0;
                                    foreach($schedules->where('is_available', true) as $schedule) {
                                        $start = \Carbon\Carbon::parse($schedule->start_time);
                                        $end = \Carbon\Carbon::parse($schedule->end_time);
                                        $totalHours += $start->diffInHours($end);
                                    }
                                @endphp
                                {{ $totalHours }} hours/week
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-calendar-day text-2xl text-green-600"></i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Working Days</dt>
                            <dd class="text-lg font-medium text-gray-900 dark:text-white">
                                {{ $schedules->where('is_available', true)->pluck('day_of_week')->unique()->count() }} days
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-check-circle text-2xl text-purple-600"></i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Active Schedules</dt>
                            <dd class="text-lg font-medium text-gray-900 dark:text-white">
                                {{ $schedules->where('is_available', true)->count() }}
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    {{-- <div class="mt-8">
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">Quick Actions</h3>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <button class="flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 transition-colors duration-200">
                        <i class="fas fa-calendar-plus mr-2"></i>
                        Set Standard Hours
                    </button>
                    <button class="flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 transition-colors duration-200">
                        <i class="fas fa-copy mr-2"></i>
                        Copy Schedule
                    </button>
                    <button class="flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-orange-600 hover:bg-orange-700 transition-colors duration-200">
                        <i class="fas fa-pause mr-2"></i>
                        Block Time
                    </button>
                    <button class="flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 transition-colors duration-200">
                        <i class="fas fa-ban mr-2"></i>
                        Mark Unavailable
                    </button>
                </div>
            </div>
        </div>
    </div> --}}
</div>
@endsection
