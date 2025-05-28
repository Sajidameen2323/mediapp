@extends('layouts.app')

@section('title', 'Doctor Dashboard - Medi App')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header Section -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Doctor Dashboard</h1>
                <p class="mt-2 text-gray-600 dark:text-gray-400">Welcome, Dr. {{ $user->name }}</p>
            </div>
            <div class="flex items-center space-x-3">
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium {{ $doctor->is_available ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' }}">
                    <i class="fas fa-circle w-2 h-2 mr-1"></i>
                    {{ $doctor->is_available ? 'Available' : 'Unavailable' }}
                </span>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-users text-2xl text-blue-600"></i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Total Patients</dt>
                            <dd class="text-lg font-medium text-gray-900 dark:text-white">{{ $totalPatients }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-file-medical-alt text-2xl text-green-600"></i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Today's Reports</dt>
                            <dd class="text-lg font-medium text-gray-900 dark:text-white">{{ $todayReports }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-clipboard-list text-2xl text-orange-600"></i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Pending Reports</dt>
                            <dd class="text-lg font-medium text-gray-900 dark:text-white">{{ $pendingReports }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-calendar-alt text-2xl text-purple-600"></i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Upcoming Holidays</dt>
                            <dd class="text-lg font-medium text-gray-900 dark:text-white">{{ $upcomingHolidays->count() }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Feature Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Schedule Management -->
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <div class="flex items-center mb-4">
                    <i class="fas fa-calendar-week text-2xl text-blue-600 mr-3"></i>
                    <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">Schedule Management</h3>
                </div>
                <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">Manage your working hours and availability</p>
                <div class="mt-4">
                    <a href="{{ route('doctor.schedule') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors duration-200">
                        <i class="fas fa-cog mr-2"></i>Manage Schedule
                    </a>
                </div>
            </div>
        </div>

        <!-- Break Management -->
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <div class="flex items-center mb-4">
                    <i class="fas fa-coffee text-2xl text-orange-600 mr-3"></i>
                    <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">Break Management</h3>
                </div>
                <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">Set lunch breaks and personal time slots</p>
                <div class="mt-4">
                    <a href="{{ route('doctor.breaks.index') }}" class="bg-orange-600 hover:bg-orange-700 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors duration-200">
                        <i class="fas fa-pause mr-2"></i>Manage Breaks
                    </a>
                </div>
            </div>
        </div>

        <!-- Holiday Management -->
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <div class="flex items-center mb-4">
                    <i class="fas fa-umbrella-beach text-2xl text-green-600 mr-3"></i>
                    <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">Holiday Management</h3>
                </div>
                <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">Request time off and manage holidays</p>
                <div class="mt-4">
                    <a href="{{ route('doctor.holidays.index') }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors duration-200">
                        <i class="fas fa-calendar-plus mr-2"></i>Manage Holidays
                    </a>
                </div>
            </div>
        </div>

        <!-- Medical Reports -->
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <div class="flex items-center mb-4">
                    <i class="fas fa-file-medical text-2xl text-purple-600 mr-3"></i>
                    <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">Medical Reports</h3>
                </div>
                <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">Create and manage patient medical reports</p>
                <div class="mt-4 flex space-x-2">
                    <a href="{{ route('doctor.medical-reports.index') }}" class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors duration-200">
                        <i class="fas fa-list mr-2"></i>View Reports
                    </a>
                    <a href="{{ route('doctor.medical-reports.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-3 py-2 rounded-md text-sm font-medium transition-colors duration-200">
                        <i class="fas fa-plus mr-1"></i>New
                    </a>
                </div>
            </div>
        </div>

        <!-- Appointments -->
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <div class="flex items-center mb-4">
                    <i class="fas fa-calendar-check text-2xl text-teal-600 mr-3"></i>
                    <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">Appointments</h3>
                </div>
                <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">View and manage your appointments</p>
                <div class="mt-4">
                    <button class="bg-teal-600 hover:bg-teal-700 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors duration-200">
                        <i class="fas fa-calendar mr-2"></i>View Schedule
                    </button>
                </div>
            </div>
        </div>

        <!-- Patient Records -->
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <div class="flex items-center mb-4">
                    <i class="fas fa-user-injured text-2xl text-red-600 mr-3"></i>
                    <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">Patient Records</h3>
                </div>
                <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">Access patient medical records</p>
                <div class="mt-4">
                    <button class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors duration-200">
                        <i class="fas fa-folder-open mr-2"></i>View Records
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Upcoming Holidays Section -->
    @if($upcomingHolidays->count() > 0)
    <div class="mt-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white mb-4">
                    <i class="fas fa-calendar-alt mr-2 text-purple-600"></i>
                    Upcoming Holidays
                </h3>
                <div class="space-y-3">
                    @foreach($upcomingHolidays as $holiday)
                    <div class="flex items-center justify-between py-2 px-3 bg-gray-50 dark:bg-gray-700 rounded-md">
                        <div>
                            <span class="font-medium text-gray-900 dark:text-white">{{ $holiday->title }}</span>
                            <span class="text-sm text-gray-500 dark:text-gray-400 ml-2">
                                {{ $holiday->start_date->format('M d, Y') }} - {{ $holiday->end_date->format('M d, Y') }}
                            </span>
                        </div>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                            {{ $holiday->status }}
                        </span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection
