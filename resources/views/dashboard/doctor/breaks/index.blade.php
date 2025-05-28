@extends('layouts.app')

@section('title', 'Break Management - Doctor Dashboard')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Break Management</h1>
                <p class="mt-2 text-gray-600 dark:text-gray-400">Manage your lunch breaks and personal time</p>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('doctor.dashboard') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors duration-200">
                    <i class="fas fa-arrow-left mr-2"></i>Back to Dashboard
                </a>
                <a href="{{ route('breaks.create') }}" class="bg-orange-600 hover:bg-orange-700 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors duration-200">
                    <i class="fas fa-plus mr-2"></i>Add Break
                </a>
            </div>
        </div>
    </div>

    <!-- Success Message -->
    @if(session('success'))
        <div class="mb-6 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-md p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-check-circle text-green-400"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-green-800 dark:text-green-200">
                        {{ session('success') }}
                    </p>
                </div>
            </div>
        </div>
    @endif

    <!-- Error Message -->
    @if(session('error'))
        <div class="mb-6 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-md p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-exclamation-circle text-red-400"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-red-800 dark:text-red-200">
                        {{ session('error') }}
                    </p>
                </div>
            </div>
        </div>
    @endif

    <!-- Breaks Overview -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-coffee text-2xl text-orange-600"></i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Total Breaks</dt>
                            <dd class="text-lg font-medium text-gray-900 dark:text-white">{{ $breaks->count() }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-redo text-2xl text-green-600"></i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Recurring Breaks</dt>
                            <dd class="text-lg font-medium text-gray-900 dark:text-white">{{ $breaks->where('is_recurring', true)->count() }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-check-circle text-2xl text-blue-600"></i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Active Breaks</dt>
                            <dd class="text-lg font-medium text-gray-900 dark:text-white">{{ $breaks->where('is_active', true)->count() }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Breaks by Day -->
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white">Weekly Breaks Overview</h3>
        </div>
        
        <div class="p-6">
            @php
                $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
                $dayNames = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
            @endphp
            
            <div class="space-y-6">
                @foreach($days as $index => $day)
                    @php
                        $dayBreaks = $breaks->where('day_of_week', $day)->where('is_active', true);
                    @endphp
                    
                    <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4">                        <div class="flex items-center justify-between mb-4">
                            <h4 class="text-lg font-medium text-gray-900 dark:text-white">{{ $dayNames[$index] }}</h4>
                            <a href="{{ route('breaks.create', ['day' => $day]) }}" class="text-orange-600 hover:text-orange-800 text-sm font-medium">
                                <i class="fas fa-plus mr-1"></i>Add Break
                            </a>
                        </div>
                        
                        @if($dayBreaks->count() > 0)
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                @foreach($dayBreaks as $break)
                                    <div class="bg-orange-50 dark:bg-orange-900 rounded-lg p-4 border border-orange-200 dark:border-orange-700">
                                        <div class="flex items-center justify-between mb-2">
                                            <h5 class="font-medium text-orange-900 dark:text-orange-100">{{ $break->title }}</h5>
                                            <div class="flex space-x-1">
                                                <a href="{{ route('breaks.edit', $break) }}" class="text-blue-600 hover:text-blue-800 text-sm">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form method="POST" action="{{ route('breaks.destroy', $break) }}" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-800 text-sm" onclick="return confirm('Are you sure?')">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                        <div class="text-sm text-orange-700 dark:text-orange-300">
                                            <div class="flex items-center mb-1">
                                                <i class="fas fa-clock mr-2"></i>
                                                {{ $break->start_time->format('H:i') }} - {{ $break->end_time->format('H:i') }}
                                            </div>
                                            <div class="flex items-center mb-1">
                                                <i class="fas fa-tag mr-2"></i>
                                                {{ ucfirst($break->type) }}
                                            </div>
                                            @if($break->is_recurring)
                                                <div class="flex items-center">
                                                    <i class="fas fa-redo mr-2"></i>
                                                    Recurring
                                                </div>
                                            @endif
                                        </div>
                                        @if($break->notes)
                                            <div class="mt-2 text-xs text-orange-600 dark:text-orange-400">
                                                {{ Str::limit($break->notes, 50) }}
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @else                            <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                                <i class="fas fa-coffee text-4xl mb-4"></i>
                                <p>No breaks scheduled for {{ $dayNames[$index] }}</p>
                                <a href="{{ route('breaks.create', ['day' => $day]) }}" class="mt-2 text-orange-600 hover:text-orange-800 font-medium">
                                    Add your first break
                                </a>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="mt-8">
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">Quick Actions</h3>
            </div>
            <div class="p-6">                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <a href="{{ route('breaks.create', ['type' => 'lunch']) }}" class="flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-orange-600 hover:bg-orange-700 transition-colors duration-200">
                        <i class="fas fa-utensils mr-2"></i>
                        Set Lunch Break
                    </a>
                    <button id="copy-to-all-days" class="flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 transition-colors duration-200">
                        <i class="fas fa-copy mr-2"></i>
                        Copy to All Days
                    </button>
                    <a href="{{ route('breaks.create', ['type' => 'personal', 'duration' => '15']) }}" class="flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 transition-colors duration-200">
                        <i class="fas fa-clock mr-2"></i>
                        15 Min Break
                    </a>
                    <a href="{{ route('breaks.create', ['type' => 'other']) }}" class="flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-purple-600 hover:bg-purple-700 transition-colors duration-200">
                        <i class="fas fa-calendar-times mr-2"></i>
                        One-time Break
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
