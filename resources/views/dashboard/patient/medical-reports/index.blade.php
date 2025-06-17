@extends('layouts.app')

@section('title', 'My Medical Reports')

@section('content')
<x-patient-navigation />

<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="px-4 py-6 sm:px-0">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">My Medical Reports</h1>
                    <p class="mt-2 text-gray-600">View your medical consultation reports and history</p>
                </div>
                <div class="flex items-center space-x-3">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                        {{ $medicalReports->count() }} Total
                    </span>
                </div>
            </div>
        </div>

        @if($medicalReports->isEmpty())
            <!-- Empty State -->
            <div class="text-center py-12">
                <div class="mx-auto h-24 w-24 text-gray-400">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <h3 class="mt-4 text-lg font-medium text-gray-900">No medical reports yet</h3>
                <p class="mt-2 text-gray-500">Your medical consultation reports will appear here after doctor visits.</p>
            </div>
        @else
            <!-- Medical Reports Grid -->
            <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                @foreach($medicalReports as $report)
                    <div class="bg-white overflow-hidden shadow-sm rounded-lg border border-gray-200 hover:shadow-md transition-shadow duration-200">
                        <!-- Card Header -->
                        <div class="px-6 py-4 border-b border-gray-200">
                            <div class="flex items-center justify-between">
                                <h3 class="text-lg font-semibold text-gray-900">
                                    {{ $report->diagnosis ?? 'General Consultation' }}
                                </h3>
                            </div>
                            <p class="mt-1 text-sm text-gray-600">
                                Dr. {{ $report->doctor->name }}
                            </p>
                        </div>

                        <!-- Card Content -->
                        <div class="px-6 py-4">
                            <div class="space-y-3">
                                <!-- Appointment -->
                                @if($report->appointment)
                                    <div>
                                        <span class="text-sm font-medium text-gray-700">Appointment:</span>
                                        <p class="text-sm text-gray-600">{{ $report->appointment->appointment_date->format('M d, Y \a\t g:i A') }}</p>
                                    </div>
                                @endif

                                <!-- Symptoms -->
                                @if($report->symptoms)
                                    <div>
                                        <span class="text-sm font-medium text-gray-700">Symptoms:</span>
                                        <p class="text-sm text-gray-600">{{ Str::limit($report->symptoms, 80) }}</p>
                                    </div>
                                @endif

                                <!-- Treatment -->
                                @if($report->treatment)
                                    <div>
                                        <span class="text-sm font-medium text-gray-700">Treatment:</span>
                                        <p class="text-sm text-gray-600">{{ Str::limit($report->treatment, 80) }}</p>
                                    </div>
                                @endif

                                <!-- Date -->
                                <div>
                                    <span class="text-sm font-medium text-gray-700">Created:</span>
                                    <p class="text-sm text-gray-600">{{ $report->created_at->format('M d, Y') }}</p>
                                </div>

                                <!-- Counts -->
                                <div class="flex items-center space-x-4 text-sm text-gray-600">
                                    @if($report->prescriptions_count > 0)
                                        <span class="inline-flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path>
                                            </svg>
                                            {{ $report->prescriptions_count }} Rx
                                        </span>
                                    @endif
                                    @if($report->lab_test_requests_count > 0)
                                        <span class="inline-flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                                            </svg>
                                            {{ $report->lab_test_requests_count }} Labs
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Card Footer -->
                        <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                            <div class="flex items-center justify-between">
                                <a href="{{ route('patient.medical-reports.show', $report) }}" 
                                   class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-blue-700 bg-blue-100 hover:bg-blue-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                    View Details
                                </a>
                                
                                <div class="flex items-center space-x-2">
                                    @if($report->prescriptions_count > 0)
                                        <a href="{{ route('patient.prescriptions.index', ['medical_report' => $report->id]) }}" 
                                           class="text-green-600 hover:text-green-500 text-sm font-medium">
                                            View Prescriptions
                                        </a>
                                    @endif
                                    @if($report->lab_test_requests_count > 0)
                                        <a href="{{ route('patient.lab-tests.index', ['medical_report' => $report->id]) }}" 
                                           class="text-purple-600 hover:text-purple-500 text-sm font-medium">
                                            View Lab Tests
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            @if($medicalReports->hasPages())
                <div class="mt-8">
                    {{ $medicalReports->links() }}
                </div>
            @endif
        @endif
    </div>
</div>
@endsection
