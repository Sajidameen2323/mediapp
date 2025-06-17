@extends('layouts.app')

@section('title', 'My Lab Tests')

@section('content')
<x-patient-navigation />

<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="px-4 py-6 sm:px-0">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">My Lab Tests</h1>
                    <p class="mt-2 text-gray-600">View and manage your laboratory test requests</p>
                </div>
                <div class="flex items-center space-x-3">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                        {{ $labTests->count() }} Total
                    </span>
                </div>
            </div>
        </div>

        @if($labTests->isEmpty())
            <!-- Empty State -->
            <div class="text-center py-12">
                <div class="mx-auto h-24 w-24 text-gray-400">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                    </svg>
                </div>
                <h3 class="mt-4 text-lg font-medium text-gray-900">No lab tests yet</h3>
                <p class="mt-2 text-gray-500">Your laboratory test requests from medical consultations will appear here.</p>
            </div>
        @else
            <!-- Lab Tests Grid -->
            <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                @foreach($labTests as $labTest)
                    <div class="bg-white overflow-hidden shadow-sm rounded-lg border border-gray-200 hover:shadow-md transition-shadow duration-200">
                        <!-- Card Header -->
                        <div class="px-6 py-4 border-b border-gray-200">
                            <div class="flex items-center justify-between">
                                <h3 class="text-lg font-semibold text-gray-900">
                                    {{ $labTest->test_name }}
                                </h3>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                    @if($labTest->status === 'pending') bg-yellow-100 text-yellow-800
                                    @elseif($labTest->status === 'completed') bg-green-100 text-green-800
                                    @elseif($labTest->status === 'in_progress') bg-blue-100 text-blue-800
                                    @else bg-gray-100 text-gray-800
                                    @endif">
                                    {{ ucfirst(str_replace('_', ' ', $labTest->status)) }}
                                </span>
                            </div>
                            <p class="mt-1 text-sm text-gray-600">
                                Dr. {{ $labTest->medicalReport->doctor->name }}
                            </p>
                        </div>

                        <!-- Card Content -->
                        <div class="px-6 py-4">
                            <div class="space-y-3">
                                <!-- Medical Report -->
                                <div>
                                    <span class="text-sm font-medium text-gray-700">Report:</span>
                                    <p class="text-sm text-gray-600">{{ $labTest->medicalReport->diagnosis ?? 'General Consultation' }}</p>
                                </div>

                                <!-- Test Type -->
                                @if($labTest->test_type)
                                    <div>
                                        <span class="text-sm font-medium text-gray-700">Type:</span>
                                        <p class="text-sm text-gray-600">{{ $labTest->test_type }}</p>
                                    </div>
                                @endif

                                <!-- Date Requested -->
                                <div>
                                    <span class="text-sm font-medium text-gray-700">Requested:</span>
                                    <p class="text-sm text-gray-600">{{ $labTest->created_at->format('M d, Y') }}</p>
                                </div>

                                <!-- Priority -->
                                @if($labTest->priority && $labTest->priority !== 'normal')
                                    <div>
                                        <span class="text-sm font-medium text-gray-700">Priority:</span>
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium 
                                            @if($labTest->priority === 'urgent') bg-red-100 text-red-800
                                            @elseif($labTest->priority === 'high') bg-orange-100 text-orange-800
                                            @else bg-gray-100 text-gray-800
                                            @endif">
                                            {{ ucfirst($labTest->priority) }}
                                        </span>
                                    </div>
                                @endif

                                @if($labTest->instructions)
                                    <div>
                                        <span class="text-sm font-medium text-gray-700">Instructions:</span>
                                        <p class="text-sm text-gray-600">{{ Str::limit($labTest->instructions, 100) }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Card Footer -->
                        <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                            <div class="flex items-center justify-between">
                                <a href="{{ route('patient.lab-tests.show', $labTest) }}" 
                                   class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-blue-700 bg-blue-100 hover:bg-blue-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                    View Details
                                </a>
                                
                                @if($labTest->status === 'pending')
                                    <button type="button" 
                                            class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-green-700 bg-green-100 hover:bg-green-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors duration-200">
                                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a1 1 0 011-1h6a1 1 0 011 1v4h3a1 1 0 011 1v8a1 1 0 01-1 1H5a1 1 0 01-1-1V8a1 1 0 011-1h3z"></path>
                                        </svg>
                                        Book Appointment
                                    </button>
                                @elseif($labTest->status === 'completed')
                                    <span class="inline-flex items-center px-3 py-2 text-sm leading-4 font-medium text-green-700">
                                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        Completed
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            @if($labTests->hasPages())
                <div class="mt-8">
                    {{ $labTests->links() }}
                </div>
            @endif
        @endif
    </div>
</div>
@endsection
