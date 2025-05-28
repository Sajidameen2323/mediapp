@extends('layouts.app')

@section('title', 'Holiday Management - Doctor Dashboard')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Holiday Management</h1>
                <p class="mt-2 text-gray-600 dark:text-gray-400">Request time off and manage your holidays</p>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('doctor.dashboard') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors duration-200">
                    <i class="fas fa-arrow-left mr-2"></i>Back to Dashboard
                </a>
                <a href="{{ route('doctor.holidays.create') }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors duration-200">
                    <i class="fas fa-plus mr-2"></i>Request Holiday
                </a>
            </div>
        </div>
    </div>

    <!-- Holiday Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-calendar-alt text-2xl text-blue-600"></i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Total Holidays</dt>
                            <dd class="text-lg font-medium text-gray-900 dark:text-white">{{ $holidays->total() }}</dd>
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
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Approved</dt>
                            <dd class="text-lg font-medium text-gray-900 dark:text-white">
                                {{ $holidays->where('status', 'approved')->count() }}
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
                        <i class="fas fa-hourglass-half text-2xl text-orange-600"></i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Pending</dt>
                            <dd class="text-lg font-medium text-gray-900 dark:text-white">
                                {{ $holidays->where('status', 'pending')->count() }}
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
                        <i class="fas fa-times-circle text-2xl text-red-600"></i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Rejected</dt>
                            <dd class="text-lg font-medium text-gray-900 dark:text-white">
                                {{ $holidays->where('status', 'rejected')->count() }}
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="mb-6">
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-4">
            <div class="flex flex-wrap items-center space-x-4">
                <div class="flex items-center">
                    <label class="text-sm font-medium text-gray-700 dark:text-gray-300 mr-2">Status:</label>
                    <select class="border border-gray-300 dark:border-gray-600 rounded-md px-3 py-1 text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                        <option value="">All</option>
                        <option value="pending">Pending</option>
                        <option value="approved">Approved</option>
                        <option value="rejected">Rejected</option>
                    </select>
                </div>
                <div class="flex items-center">
                    <label class="text-sm font-medium text-gray-700 dark:text-gray-300 mr-2">Type:</label>
                    <select class="border border-gray-300 dark:border-gray-600 rounded-md px-3 py-1 text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                        <option value="">All</option>
                        <option value="vacation">Vacation</option>
                        <option value="sick_leave">Sick Leave</option>
                        <option value="personal">Personal</option>
                        <option value="conference">Conference</option>
                        <option value="other">Other</option>
                    </select>
                </div>
                <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-1 rounded-md text-sm transition-colors duration-200">
                    <i class="fas fa-filter mr-1"></i>Filter
                </button>
            </div>
        </div>
    </div>

    <!-- Holidays List -->
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white">Holiday Requests</h3>
        </div>
        
        @if($holidays->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Holiday Details
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Duration
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Type
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Status
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($holidays as $holiday)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div>
                                        <div class="text-sm font-medium text-gray-900 dark:text-white">
                                            {{ $holiday->title }}
                                        </div>
                                        <div class="text-sm text-gray-500 dark:text-gray-400">
                                            {{ $holiday->start_date->format('M d, Y') }} - {{ $holiday->end_date->format('M d, Y') }}
                                        </div>
                                        @if($holiday->reason)
                                            <div class="text-xs text-gray-400 dark:text-gray-500 mt-1">
                                                {{ Str::limit($holiday->reason, 50) }}
                                            </div>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                        {{ $holiday->duration }} {{ $holiday->duration == 1 ? 'day' : 'days' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                        @if($holiday->type == 'vacation') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                        @elseif($holiday->type == 'sick_leave') bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200
                                        @elseif($holiday->type == 'personal') bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200
                                        @elseif($holiday->type == 'conference') bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200
                                        @else bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200
                                        @endif">
                                        {{ ucfirst(str_replace('_', ' ', $holiday->type)) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        @if($holiday->status == 'approved') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                        @elseif($holiday->status == 'pending') bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-200
                                        @else bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200
                                        @endif">
                                        <i class="fas fa-circle w-2 h-2 mr-1"></i>
                                        {{ ucfirst($holiday->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex items-center space-x-2">
                                        <a href="{{ route('doctor.holidays.show', $holiday) }}" class="text-blue-600 hover:text-blue-900 dark:hover:text-blue-400">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @if($holiday->status == 'pending')
                                            <a href="{{ route('doctor.holidays.edit', $holiday) }}" class="text-orange-600 hover:text-orange-900 dark:hover:text-orange-400">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form method="POST" action="{{ route('doctor.holidays.destroy', $holiday) }}" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900 dark:hover:text-red-400" onclick="return confirm('Are you sure?')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                {{ $holidays->links() }}
            </div>
        @else
            <div class="text-center py-12">
                <i class="fas fa-umbrella-beach text-6xl text-gray-400 dark:text-gray-600 mb-4"></i>
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">No holidays requested yet</h3>
                <p class="text-gray-500 dark:text-gray-400 mb-6">Start by requesting your first holiday or time off.</p>
                <a href="{{ route('doctor.holidays.create') }}" class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-md font-medium transition-colors duration-200">
                    <i class="fas fa-plus mr-2"></i>Request Holiday
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
