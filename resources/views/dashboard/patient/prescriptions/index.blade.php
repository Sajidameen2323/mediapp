@extends('layouts.app')

@section('title', 'My Prescriptions')

@section('content')
<x-patient-navigation />

<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="px-4 py-6 sm:px-0">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">My Prescriptions</h1>
                    <p class="mt-2 text-gray-600">View and manage your medication prescriptions</p>
                </div>
                <div class="flex items-center space-x-3">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                        {{ $prescriptions->count() }} Total
                    </span>
                </div>
            </div>
        </div>

        @if($prescriptions->isEmpty())
            <!-- Empty State -->
            <div class="text-center py-12">
                <div class="mx-auto h-24 w-24 text-gray-400">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <h3 class="mt-4 text-lg font-medium text-gray-900">No prescriptions yet</h3>
                <p class="mt-2 text-gray-500">Your prescriptions from medical consultations will appear here.</p>
            </div>
        @else
            <!-- Prescriptions Grid -->
            <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                @foreach($prescriptions as $prescription)
                    <div class="bg-white overflow-hidden shadow-sm rounded-lg border border-gray-200 hover:shadow-md transition-shadow duration-200">
                        <!-- Card Header -->
                        <div class="px-6 py-4 border-b border-gray-200">
                            <div class="flex items-center justify-between">
                                <h3 class="text-lg font-semibold text-gray-900">
                                    Prescription #{{ $prescription->id }}
                                </h3>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $prescription->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                    {{ ucfirst($prescription->status) }}
                                </span>
                            </div>
                            <p class="mt-1 text-sm text-gray-600">
                                Dr. {{ $prescription->medicalReport->doctor->name }}
                            </p>
                        </div>

                        <!-- Card Content -->
                        <div class="px-6 py-4">
                            <div class="space-y-3">
                                <!-- Medical Report -->
                                <div>
                                    <span class="text-sm font-medium text-gray-700">Report:</span>
                                    <p class="text-sm text-gray-600">{{ $prescription->medicalReport->diagnosis ?? 'General Consultation' }}</p>
                                </div>                                <!-- Medications Count -->
                                <div>
                                    <span class="text-sm font-medium text-gray-700">Medications:</span>
                                    <p class="text-sm text-gray-600">{{ $prescription->prescriptionMedications->count() }} prescribed</p>
                                </div>

                                <!-- Date -->
                                <div>
                                    <span class="text-sm font-medium text-gray-700">Prescribed:</span>
                                    <p class="text-sm text-gray-600">{{ $prescription->created_at->format('M d, Y') }}</p>
                                </div>

                                @if($prescription->notes)
                                    <div>
                                        <span class="text-sm font-medium text-gray-700">Notes:</span>
                                        <p class="text-sm text-gray-600">{{ Str::limit($prescription->notes, 100) }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Card Footer -->
                        <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                            <div class="flex items-center justify-between">
                                <a href="{{ route('patient.prescriptions.show', $prescription) }}" 
                                   class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-blue-700 bg-blue-100 hover:bg-blue-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                    View Details
                                </a>
                                
                                @if($prescription->status === 'active')
                                    <button type="button" 
                                            class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-green-700 bg-green-100 hover:bg-green-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors duration-200">
                                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M8 11v6a2 2 0 002 2h4a2 2 0 002-2v-6M8 11h8"></path>
                                        </svg>
                                        Order from Pharmacy
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            @if($prescriptions->hasPages())
                <div class="mt-8">
                    {{ $prescriptions->links() }}
                </div>
            @endif
        @endif
    </div>
</div>
@endsection
