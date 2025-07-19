@extends('layouts.doctor')

@section('title', 'Holiday Requests')

@section('content')
<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="md:flex md:items-center md:justify-between mb-6">
        <div class="min-w-0 flex-1">
            <h2 class="text-2xl font-bold leading-7 text-gray-900 dark:text-white sm:truncate">
                <i class="fas fa-umbrella-beach text-green-600 mr-3"></i>Holiday Requests
            </h2>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                Request time off and manage your holiday schedule
            </p>
        </div>
        <div class="mt-4 flex md:ml-4 md:mt-0">
            <a href="{{ route('doctor.holidays.create') }}" 
               class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors duration-200 flex items-center">
                <i class="fas fa-plus mr-2"></i>Request Holiday
            </a>
        </div>
    </div>

    <!-- Filter Tabs -->
    <div class="mb-6">
        <div class="border-b border-gray-200 dark:border-gray-700">
            <nav class="-mb-px flex space-x-8">
                <a href="{{ route('doctor.holidays.index') }}" 
                   class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300 whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm">
                    All Requests
                </a>
                <a href="{{ route('doctor.holidays.index', ['status' => 'pending']) }}" 
                   class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300 whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm">
                    Pending
                </a>
                <a href="{{ route('doctor.holidays.index', ['status' => 'approved']) }}" 
                   class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300 whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm">
                    Approved
                </a>
                <a href="{{ route('doctor.holidays.index', ['status' => 'rejected']) }}" 
                   class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300 whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm">
                    Rejected
                </a>
            </nav>
        </div>
    </div>

    <!-- Holidays List -->
    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            @if($holidays->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Dates
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Reason
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Status
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Duration
                                </th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($holidays as $holiday)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <i class="fas fa-calendar text-green-500 mr-3"></i>
                                            <div>
                                                <div class="text-sm font-medium text-gray-900 dark:text-white">
                                                    {{ $holiday->start_date->format('M d, Y') }}
                                                </div>
                                                <div class="text-sm text-gray-500 dark:text-gray-400">
                                                    to {{ $holiday->end_date->format('M d, Y') }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-900 dark:text-white font-medium">
                                            {{ $holiday->reason }}
                                        </div>
                                        @if($holiday->notes)
                                            <div class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                                {{ Str::limit($holiday->notes, 50) }}
                                            </div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @php
                                            $statusClasses = [
                                                'pending' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200',
                                                'approved' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
                                                'rejected' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200'
                                            ];
                                        @endphp
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusClasses[$holiday->status] }}">
                                            @if($holiday->status === 'pending')
                                                <i class="fas fa-clock mr-1"></i>
                                            @elseif($holiday->status === 'approved')
                                                <i class="fas fa-check mr-1"></i>
                                            @else
                                                <i class="fas fa-times mr-1"></i>
                                            @endif
                                            {{ ucfirst($holiday->status) }}
                                        </span>
                                        @if($holiday->admin_notes)
                                            <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                                {{ Str::limit($holiday->admin_notes, 30) }}
                                            </div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900 dark:text-white font-medium">
                                            {{ $holiday->start_date->diffInDays($holiday->end_date) + 1 }} 
                                            {{ ($holiday->start_date->diffInDays($holiday->end_date) + 1) === 1 ? 'day' : 'days' }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex items-center justify-end space-x-2">
                                            <a href="{{ route('doctor.holidays.show', $holiday) }}" 
                                               class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300 transition-colors duration-200"
                                               title="View Details">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            @if($holiday->status === 'pending')
                                                <a href="{{ route('doctor.holidays.edit', $holiday) }}" 
                                                   class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 transition-colors duration-200"
                                                   title="Edit Request">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form method="POST" action="{{ route('doctor.holidays.destroy', $holiday) }}" 
                                                      class="inline"
                                                      onsubmit="return confirm('Are you sure you want to cancel this holiday request?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" 
                                                            class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300 transition-colors duration-200 ml-2"
                                                            title="Cancel Request">
                                                        <i class="fas fa-times"></i>
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
            @else
                <!-- Empty State -->
                <div class="text-center py-12">
                    <i class="fas fa-umbrella-beach text-gray-400 text-6xl mb-4"></i>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">No holiday requests</h3>
                    <p class="text-gray-600 dark:text-gray-400 mb-6">
                        You haven't submitted any holiday requests yet. Start planning your time off!
                    </p>
                    <a href="{{ route('doctor.holidays.create') }}" 
                       class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-md text-sm font-medium transition-colors duration-200 inline-flex items-center">
                        <i class="fas fa-plus mr-2"></i>Request Your First Holiday
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
