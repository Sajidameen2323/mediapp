@extends('layouts.app')

@section('title', 'Medical Report Details')

@section('content')
<x-patient-navigation />

<div class="max-w-4xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="px-4 py-6 sm:px-0">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <nav class="flex" aria-label="Breadcrumb">
                        <ol class="flex items-center space-x-4">
                            <li>
                                <a href="{{ route('patient.medical-reports.index') }}" class="text-gray-400 hover:text-gray-500">
                                    <span class="sr-only">Medical Reports</span>
                                    <svg class="flex-shrink-0 h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M4 4a2 2 0 00-2 2v8a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2H4zm0 2h12v8H4V6z"></path>
                                    </svg>
                                </a>
                            </li>
                            <li>
                                <div class="flex items-center">
                                    <svg class="flex-shrink-0 h-5 w-5 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                    <a href="{{ route('patient.medical-reports.index') }}" class="ml-4 text-sm font-medium text-gray-500 hover:text-gray-700">Medical Reports</a>
                                </div>
                            </li>
                            <li>
                                <div class="flex items-center">
                                    <svg class="flex-shrink-0 h-5 w-5 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                    <span class="ml-4 text-sm font-medium text-gray-500" aria-current="page">Report Details</span>
                                </div>
                            </li>
                        </ol>
                    </nav>
                    <h1 class="mt-2 text-3xl font-bold text-gray-900">Medical Report</h1>
                </div>
                <div class="flex items-center space-x-3">
                    <button type="button" 
                            onclick="window.print()" 
                            class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                        </svg>
                        Print Report
                    </button>
                </div>
            </div>
        </div>

        <div class="space-y-6">
            <!-- Report Overview -->
            <div class="bg-white shadow-sm rounded-lg border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Report Overview</h2>
                </div>
                <div class="px-6 py-4">
                    <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Patient</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $medicalReport->patient->name }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Doctor</dt>
                            <dd class="mt-1 text-sm text-gray-900">Dr. {{ $medicalReport->doctor->name }}</dd>
                        </div>
                        @if($medicalReport->appointment)
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Appointment Date</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $medicalReport->appointment->appointment_date->format('F d, Y \a\t g:i A') }}</dd>
                            </div>
                        @endif
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Report Date</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $medicalReport->created_at->format('F d, Y \a\t g:i A') }}</dd>
                        </div>
                    </dl>
                </div>
            </div>

            <!-- Medical Details -->
            <div class="bg-white shadow-sm rounded-lg border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Medical Details</h2>
                </div>
                <div class="px-6 py-4 space-y-6">
                    @if($medicalReport->symptoms)
                        <div>
                            <dt class="text-sm font-medium text-gray-500 mb-2">Symptoms</dt>
                            <dd class="text-sm text-gray-900 whitespace-pre-wrap">{{ $medicalReport->symptoms }}</dd>
                        </div>
                    @endif

                    @if($medicalReport->diagnosis)
                        <div>
                            <dt class="text-sm font-medium text-gray-500 mb-2">Diagnosis</dt>
                            <dd class="text-sm text-gray-900 whitespace-pre-wrap">{{ $medicalReport->diagnosis }}</dd>
                        </div>
                    @endif

                    @if($medicalReport->treatment)
                        <div>
                            <dt class="text-sm font-medium text-gray-500 mb-2">Treatment</dt>
                            <dd class="text-sm text-gray-900 whitespace-pre-wrap">{{ $medicalReport->treatment }}</dd>
                        </div>
                    @endif

                    @if($medicalReport->notes)
                        <div>
                            <dt class="text-sm font-medium text-gray-500 mb-2">Additional Notes</dt>
                            <dd class="text-sm text-gray-900 whitespace-pre-wrap">{{ $medicalReport->notes }}</dd>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Prescriptions -->
            @if($medicalReport->prescriptions->isNotEmpty())
                <div class="bg-white shadow-sm rounded-lg border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                        <h2 class="text-lg font-semibold text-gray-900">Prescriptions</h2>
                        <a href="{{ route('patient.prescriptions.index', ['medical_report' => $medicalReport->id]) }}" 
                           class="text-sm text-blue-600 hover:text-blue-500">
                            View All Prescriptions →
                        </a>
                    </div>
                    <div class="px-6 py-4">
                        <div class="space-y-4">
                            @foreach($medicalReport->prescriptions as $prescription)
                                <div class="border border-gray-200 rounded-lg p-4">
                                    <div class="flex items-center justify-between mb-3">
                                        <h3 class="text-sm font-medium text-gray-900">Prescription #{{ $prescription->id }}</h3>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $prescription->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                            {{ ucfirst($prescription->status) }}
                                        </span>
                                    </div>                                    @if($prescription->prescriptionMedications->isNotEmpty())
                                        <div class="space-y-2">
                                            @foreach($prescription->prescriptionMedications as $prescriptionMedication)
                                                <div class="flex items-center justify-between text-sm">
                                                    <span class="font-medium text-gray-900">{{ $prescriptionMedication->medication->name }}</span>
                                                    <span class="text-gray-600">{{ $prescriptionMedication->dosage }} - {{ $prescriptionMedication->frequency }}</span>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                    <div class="mt-3">
                                        <a href="{{ route('patient.prescriptions.show', $prescription) }}" 
                                           class="text-sm text-blue-600 hover:text-blue-500">
                                            View Full Prescription →
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            <!-- Lab Test Requests -->
            @if($medicalReport->labTestRequests->isNotEmpty())
                <div class="bg-white shadow-sm rounded-lg border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                        <h2 class="text-lg font-semibold text-gray-900">Lab Test Requests</h2>
                        <a href="{{ route('patient.lab-tests.index', ['medical_report' => $medicalReport->id]) }}" 
                           class="text-sm text-blue-600 hover:text-blue-500">
                            View All Lab Tests →
                        </a>
                    </div>
                    <div class="px-6 py-4">
                        <div class="space-y-4">
                            @foreach($medicalReport->labTestRequests as $labTest)
                                <div class="border border-gray-200 rounded-lg p-4">
                                    <div class="flex items-center justify-between mb-3">
                                        <h3 class="text-sm font-medium text-gray-900">{{ $labTest->test_name }}</h3>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                            @if($labTest->status === 'pending') bg-yellow-100 text-yellow-800
                                            @elseif($labTest->status === 'completed') bg-green-100 text-green-800
                                            @elseif($labTest->status === 'in_progress') bg-blue-100 text-blue-800
                                            @else bg-gray-100 text-gray-800
                                            @endif">
                                            {{ ucfirst(str_replace('_', ' ', $labTest->status)) }}
                                        </span>
                                    </div>
                                    @if($labTest->test_type || $labTest->instructions)
                                        <div class="space-y-2 text-sm text-gray-600">
                                            @if($labTest->test_type)
                                                <div><strong>Type:</strong> {{ $labTest->test_type }}</div>
                                            @endif
                                            @if($labTest->instructions)
                                                <div><strong>Instructions:</strong> {{ $labTest->instructions }}</div>
                                            @endif
                                        </div>
                                    @endif
                                    <div class="mt-3">
                                        <a href="{{ route('patient.lab-tests.show', $labTest) }}" 
                                           class="text-sm text-blue-600 hover:text-blue-500">
                                            View Test Details →
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            <!-- Legacy Prescription/Lab Tests (Fallback) -->
            @if($medicalReport->prescriptions->isEmpty() && $medicalReport->labTestRequests->isEmpty())
                @if($medicalReport->prescription)
                    <div class="bg-white shadow-sm rounded-lg border border-gray-200">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h2 class="text-lg font-semibold text-gray-900">Prescription (Legacy)</h2>
                        </div>
                        <div class="px-6 py-4">
                            <div class="text-sm text-gray-900 whitespace-pre-wrap">{{ $medicalReport->prescription }}</div>
                        </div>
                    </div>
                @endif

                @if($medicalReport->lab_tests)
                    <div class="bg-white shadow-sm rounded-lg border border-gray-200">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h2 class="text-lg font-semibold text-gray-900">Lab Tests (Legacy)</h2>
                        </div>
                        <div class="px-6 py-4">
                            <div class="text-sm text-gray-900 whitespace-pre-wrap">{{ $medicalReport->lab_tests }}</div>
                        </div>
                    </div>
                @endif
            @endif

            <!-- Actions -->
            <div class="flex justify-end space-x-3">
                <a href="{{ route('patient.medical-reports.index') }}" 
                   class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to Reports
                </a>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    @media print {
        .no-print {
            display: none !important;
        }
        
        body {
            print-color-adjust: exact;
        }
    }
</style>
@endpush
@endsection
