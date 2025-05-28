@extends('layouts.app')

@section('title', 'Holiday Details')

@section('content')
<div class="min-h-screen bg-gray-50 dark:bg-gray-900 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Holiday Request Details</h1>
                    <p class="mt-2 text-gray-600 dark:text-gray-400">View your holiday request information</p>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('doctor.holidays.index') }}" 
                       class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium rounded-md transition-colors duration-200">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Back to Holidays
                    </a>
                    @if($holiday->status === 'pending')
                        <a href="{{ route('doctor.holidays.edit', $holiday) }}" 
                           class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-md transition-colors duration-200">
                            <i class="fas fa-edit mr-2"></i>
                            Edit Request
                        </a>
                    @endif
                </div>
            </div>
        </div>

        <!-- Holiday Details Card -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
            <div class="p-6">
                <!-- Title and Status -->
                <div class="flex items-start justify-between mb-6">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">{{ $holiday->title }}</h2>
                        <p class="text-gray-600 dark:text-gray-400">
                            Submitted on {{ $holiday->created_at->format('M d, Y \a\t g:i A') }}
                        </p>
                    </div>
                    <div>
                        @php
                            $statusClasses = [
                                'pending' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200',
                                'approved' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
                                'rejected' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200'
                            ];
                        @endphp
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $statusClasses[$holiday->status] }}">
                            @if($holiday->status === 'pending')
                                <i class="fas fa-clock mr-2"></i>
                            @elseif($holiday->status === 'approved')
                                <i class="fas fa-check mr-2"></i>
                            @else
                                <i class="fas fa-times mr-2"></i>
                            @endif
                            {{ ucfirst($holiday->status) }}
                        </span>
                    </div>
                </div>

                <!-- Holiday Information Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <!-- Dates -->
                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-3 flex items-center">
                            <i class="fas fa-calendar text-blue-600 mr-2"></i>
                            Holiday Dates
                        </h3>
                        <div class="space-y-2">
                            <div class="flex justify-between">
                                <span class="text-gray-600 dark:text-gray-400">Start Date:</span>
                                <span class="font-medium text-gray-900 dark:text-white">{{ $holiday->start_date->format('M d, Y') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600 dark:text-gray-400">End Date:</span>
                                <span class="font-medium text-gray-900 dark:text-white">{{ $holiday->end_date->format('M d, Y') }}</span>
                            </div>
                            <div class="flex justify-between border-t border-gray-200 dark:border-gray-600 pt-2">
                                <span class="text-gray-600 dark:text-gray-400">Duration:</span>
                                <span class="font-medium text-gray-900 dark:text-white">
                                    {{ $holiday->start_date->diffInDays($holiday->end_date) + 1 }} 
                                    {{ ($holiday->start_date->diffInDays($holiday->end_date) + 1) === 1 ? 'day' : 'days' }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Request Information -->
                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-3 flex items-center">
                            <i class="fas fa-info-circle text-green-600 mr-2"></i>
                            Request Details
                        </h3>
                        <div class="space-y-2">
                            <div>
                                <span class="text-gray-600 dark:text-gray-400 block">Reason:</span>
                                <span class="font-medium text-gray-900 dark:text-white">{{ $holiday->reason }}</span>
                            </div>
                            @if($holiday->notes)
                                <div class="pt-2 border-t border-gray-200 dark:border-gray-600">
                                    <span class="text-gray-600 dark:text-gray-400 block mb-1">Additional Notes:</span>
                                    <p class="text-gray-900 dark:text-white text-sm leading-relaxed">{{ $holiday->notes }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Admin Response (if any) -->
                @if($holiday->admin_notes && $holiday->status !== 'pending')
                    <div class="bg-blue-50 dark:bg-blue-900 border border-blue-200 dark:border-blue-700 rounded-lg p-4 mb-6">
                        <h3 class="text-lg font-semibold text-blue-900 dark:text-blue-200 mb-2 flex items-center">
                            <i class="fas fa-user-shield mr-2"></i>
                            Admin Response
                        </h3>
                        <p class="text-blue-800 dark:text-blue-300">{{ $holiday->admin_notes }}</p>
                    </div>
                @endif

                <!-- Action Buttons -->
                @if($holiday->status === 'pending')
                    <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200 dark:border-gray-700">
                        <form method="POST" action="{{ route('doctor.holidays.destroy', $holiday) }}" 
                              class="inline"
                              onsubmit="return confirm('Are you sure you want to cancel this holiday request?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-md transition-colors duration-200">
                                <i class="fas fa-times mr-2"></i>
                                Cancel Request
                            </button>
                        </form>
                        <a href="{{ route('doctor.holidays.edit', $holiday) }}" 
                           class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-md transition-colors duration-200">
                            <i class="fas fa-edit mr-2"></i>
                            Edit Request
                        </a>
                    </div>
                @endif
            </div>
        </div>

        <!-- Timeline (Optional - shows the status history) -->
        <div class="mt-8 bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
            <div class="p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                    <i class="fas fa-history text-gray-600 mr-2"></i>
                    Request Timeline
                </h3>
                <div class="space-y-4">
                    <!-- Request Submitted -->
                    <div class="flex items-start space-x-3">
                        <div class="flex-shrink-0 w-8 h-8 bg-blue-100 dark:bg-blue-900 rounded-full flex items-center justify-center">
                            <i class="fas fa-plus text-blue-600 dark:text-blue-400 text-sm"></i>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-900 dark:text-white">Request Submitted</p>
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ $holiday->created_at->format('M d, Y \a\t g:i A') }}</p>
                        </div>
                    </div>

                    @if($holiday->status !== 'pending')
                        <!-- Request Processed -->
                        <div class="flex items-start space-x-3">
                            <div class="flex-shrink-0 w-8 h-8 {{ $holiday->status === 'approved' ? 'bg-green-100 dark:bg-green-900' : 'bg-red-100 dark:bg-red-900' }} rounded-full flex items-center justify-center">
                                <i class="fas {{ $holiday->status === 'approved' ? 'fa-check text-green-600 dark:text-green-400' : 'fa-times text-red-600 dark:text-red-400' }} text-sm"></i>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm font-medium text-gray-900 dark:text-white">Request {{ ucfirst($holiday->status) }}</p>
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $holiday->updated_at->format('M d, Y \a\t g:i A') }}</p>
                                @if($holiday->admin_notes)
                                    <p class="text-sm text-gray-600 dark:text-gray-300 mt-1">{{ $holiday->admin_notes }}</p>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
