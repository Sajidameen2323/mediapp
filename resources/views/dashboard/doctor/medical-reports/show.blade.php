@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 dark:bg-gray-900 py-8">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    @if($medicalReport->title)
                        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">{{ $medicalReport->title }}</h1>
                        <p class="mt-1 text-lg text-gray-700 dark:text-gray-300">Medical Report</p>
                    @else
                        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Medical Report</h1>
                    @endif                    <div class="flex items-center gap-3">
                        <p class="mt-2 text-gray-600 dark:text-gray-400">
                            Patient: {{ $medicalReport->patient->name }} | 
                            Date: {{ $medicalReport->consultation_date->format('M d, Y') }}
                        </p>
                        @if(!$isAuthor)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                <i class="fas fa-share-alt mr-1.5"></i>
                                Shared Access
                            </span>
                        @endif
                    </div>
                </div>
                <div class="flex space-x-3">
                    @if($hasEditAccess)
                        <a href="{{ route('doctor.medical-reports.edit', $medicalReport) }}" 
                           class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-md transition-colors duration-200">
                            <i class="fas fa-edit mr-2"></i>
                            Edit Report
                        </a>
                    @else
                        <span class="inline-flex items-center px-4 py-2 bg-gray-300 text-gray-500 text-sm font-medium rounded-md cursor-not-allowed">
                            <i class="fas fa-lock mr-2"></i>
                            Read Only
                        </span>
                    @endif<a href="{{ route('doctor.reports.pdf', $medicalReport) }}" 
                       target="_blank"
                       class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-md transition-colors duration-200">
                        <i class="fas fa-file-pdf mr-2"></i>
                        Export PDF
                    </a>
                    <a href="{{ route('doctor.medical-reports.index') }}" 
                       class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium rounded-md transition-colors duration-200">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Back to Reports
                    </a>
                </div>
            </div>
        </div>

        <!-- Report Content -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
            <div class="p-8 space-y-8">                <!-- Header Information -->
                <div class="border-b border-gray-200 dark:border-gray-700 pb-6">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">Patient Information</h3>
                            <div class="mt-2">
                                <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ $medicalReport->patient->name }}</p>
                                <p class="text-sm text-gray-600 dark:text-gray-400">{{ $medicalReport->patient->email }}</p>
                            </div>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">Report Author</h3>
                            <div class="mt-2">
                                <p class="text-lg font-semibold text-gray-900 dark:text-white">
                                    @if($medicalReport->doctor && $medicalReport->doctor->user)
                                        {{ $medicalReport->doctor->user->name }}
                                    @else
                                        No doctor assigned
                                    @endif
                                </p>
                                <p class="text-sm text-gray-600 dark:text-gray-400">
                                    @if($medicalReport->doctor)
                                        {{ $medicalReport->doctor->specialization ?? 'General Practice' }}
                                    @endif
                                </p>
                            </div>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">Consultation Date</h3>
                            <div class="mt-2">
                                <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ $medicalReport->consultation_date->format('M d, Y') }}</p>
                                <p class="text-sm text-gray-600 dark:text-gray-400">{{ $medicalReport->consultation_date->format('g:i A') }}</p>
                            </div>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">Your Access</h3>
                            <div class="mt-2">
                                @if($isAuthor)
                                    <p class="text-lg font-semibold text-green-600 dark:text-green-400">Report Author</p>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Full Access</p>
                                @else
                                    <p class="text-lg font-semibold text-blue-600 dark:text-blue-400">Shared Access</p>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Read Only</p>
                                    @if($accessDetails && $accessDetails['granted_at'])
                                        <p class="text-xs text-gray-500 dark:text-gray-500 mt-1">
                                            Granted: {{ $accessDetails['granted_at']->format('M d, Y') }}
                                        </p>
                                        @if($accessDetails['expires_at'])
                                            <p class="text-xs text-gray-500 dark:text-gray-500">
                                                Expires: {{ $accessDetails['expires_at']->format('M d, Y') }}
                                            </p>
                                        @endif
                                    @endif
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    @if(!$isAuthor && $accessDetails && $accessDetails['notes'])
                        <div class="mt-4 p-3 bg-blue-50 dark:bg-blue-900/20 rounded-lg border border-blue-200 dark:border-blue-800">
                            <h4 class="text-sm font-medium text-blue-800 dark:text-blue-200 mb-1">Access Notes</h4>
                            <p class="text-sm text-blue-700 dark:text-blue-300">{{ $accessDetails['notes'] }}</p>
                        </div>
                    @endif
                </div>

                <!-- Chief Complaint -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-3">Chief Complaint</h3>
                    <div class="bg-blue-50 dark:bg-blue-900 border border-blue-200 dark:border-blue-700 rounded-md p-4">
                        <p class="text-gray-800 dark:text-gray-200">{{ $medicalReport->chief_complaint }}</p>
                    </div>
                </div>

                <!-- History -->
                @if($medicalReport->history_of_present_illness)
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-3">History of Present Illness</h3>
                    <div class="prose dark:prose-invert max-w-none">
                        <p class="text-gray-700 dark:text-gray-300 whitespace-pre-wrap">{{ $medicalReport->history_of_present_illness }}</p>
                    </div>
                </div>
                @endif

                <!-- Medical History Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @if($medicalReport->past_medical_history)
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-3">Past Medical History</h3>
                        <div class="bg-gray-50 dark:bg-gray-700 rounded-md p-4">
                            <p class="text-gray-700 dark:text-gray-300 whitespace-pre-wrap">{{ $medicalReport->past_medical_history }}</p>
                        </div>
                    </div>
                    @endif

                    @if($medicalReport->medications)
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-3">Current Medications</h3>
                        <div class="bg-gray-50 dark:bg-gray-700 rounded-md p-4">
                            <p class="text-gray-700 dark:text-gray-300 whitespace-pre-wrap">{{ $medicalReport->medications }}</p>
                        </div>
                    </div>
                    @endif

                    @if($medicalReport->allergies)
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-3">Allergies</h3>
                        <div class="bg-red-50 dark:bg-red-900 border border-red-200 dark:border-red-700 rounded-md p-4">
                            <p class="text-red-800 dark:text-red-200 whitespace-pre-wrap">{{ $medicalReport->allergies }}</p>
                        </div>
                    </div>
                    @endif

                    @if($medicalReport->social_history)
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-3">Social History</h3>
                        <div class="bg-gray-50 dark:bg-gray-700 rounded-md p-4">
                            <p class="text-gray-700 dark:text-gray-300 whitespace-pre-wrap">{{ $medicalReport->social_history }}</p>
                        </div>
                    </div>
                    @endif

                    @if($medicalReport->family_history)
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-3">Family History</h3>
                        <div class="bg-gray-50 dark:bg-gray-700 rounded-md p-4">
                            <p class="text-gray-700 dark:text-gray-300 whitespace-pre-wrap">{{ $medicalReport->family_history }}</p>
                        </div>
                    </div>
                    @endif
                </div>

                <!-- Vital Signs -->
                @if($medicalReport->vital_signs)
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-3">Vital Signs</h3>
                    @php
                        $vitalSigns = is_array($medicalReport->vital_signs)
                            ? $medicalReport->vital_signs
                            : json_decode($medicalReport->vital_signs, true);
                    @endphp
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        @if(isset($vitalSigns['blood_pressure']) && $vitalSigns['blood_pressure'])
                        <div class="bg-blue-50 dark:bg-blue-900 border border-blue-200 dark:border-blue-700 rounded-md p-4 text-center">
                            <i class="fas fa-heartbeat text-blue-600 dark:text-blue-400 text-2xl mb-2"></i>
                            <p class="text-sm font-medium text-blue-800 dark:text-blue-200">Blood Pressure</p>
                            <p class="text-lg font-bold text-blue-900 dark:text-blue-100">{{ $vitalSigns['blood_pressure'] }}</p>
                        </div>
                        @endif

                        @if(isset($vitalSigns['heart_rate']) && $vitalSigns['heart_rate'])
                        <div class="bg-red-50 dark:bg-red-900 border border-red-200 dark:border-red-700 rounded-md p-4 text-center">
                            <i class="fas fa-heart text-red-600 dark:text-red-400 text-2xl mb-2"></i>
                            <p class="text-sm font-medium text-red-800 dark:text-red-200">Heart Rate</p>
                            <p class="text-lg font-bold text-red-900 dark:text-red-100">{{ $vitalSigns['heart_rate'] }} bpm</p>
                        </div>
                        @endif

                        @if(isset($vitalSigns['temperature']) && $vitalSigns['temperature'])
                        <div class="bg-yellow-50 dark:bg-yellow-900 border border-yellow-200 dark:border-yellow-700 rounded-md p-4 text-center">
                            <i class="fas fa-thermometer-half text-yellow-600 dark:text-yellow-400 text-2xl mb-2"></i>
                            <p class="text-sm font-medium text-yellow-800 dark:text-yellow-200">Temperature</p>
                            <p class="text-lg font-bold text-yellow-900 dark:text-yellow-100">{{ $vitalSigns['temperature'] }}°F</p>
                        </div>
                        @endif

                        @if(isset($vitalSigns['oxygen_saturation']) && $vitalSigns['oxygen_saturation'])
                        <div class="bg-green-50 dark:bg-green-900 border border-green-200 dark:border-green-700 rounded-md p-4 text-center">
                            <i class="fas fa-lungs text-green-600 dark:text-green-400 text-2xl mb-2"></i>
                            <p class="text-sm font-medium text-green-800 dark:text-green-200">O2 Saturation</p>
                            <p class="text-lg font-bold text-green-900 dark:text-green-100">{{ $vitalSigns['oxygen_saturation'] }}%</p>
                        </div>
                        @endif

                        @if(isset($vitalSigns['weight']) && $vitalSigns['weight'])
                        <div class="bg-purple-50 dark:bg-purple-900 border border-purple-200 dark:border-purple-700 rounded-md p-4 text-center">
                            <i class="fas fa-weight text-purple-600 dark:text-purple-400 text-2xl mb-2"></i>
                            <p class="text-sm font-medium text-purple-800 dark:text-purple-200">Weight</p>
                            <p class="text-lg font-bold text-purple-900 dark:text-purple-100">{{ $vitalSigns['weight'] }} lbs</p>
                        </div>
                        @endif

                        @if(isset($vitalSigns['height']) && $vitalSigns['height'])
                        <div class="bg-indigo-50 dark:bg-indigo-900 border border-indigo-200 dark:border-indigo-700 rounded-md p-4 text-center">
                            <i class="fas fa-ruler-vertical text-indigo-600 dark:text-indigo-400 text-2xl mb-2"></i>
                            <p class="text-sm font-medium text-indigo-800 dark:text-indigo-200">Height</p>
                            <p class="text-lg font-bold text-indigo-900 dark:text-indigo-100">{{ $vitalSigns['height'] }} ft</p>
                        </div>
                        @endif

                        @if(isset($vitalSigns['respiratory_rate']) && $vitalSigns['respiratory_rate'])
                        <div class="bg-teal-50 dark:bg-teal-900 border border-teal-200 dark:border-teal-700 rounded-md p-4 text-center">
                            <i class="fas fa-wind text-teal-600 dark:text-teal-400 text-2xl mb-2"></i>
                            <p class="text-sm font-medium text-teal-800 dark:text-teal-200">Respiratory Rate</p>
                            <p class="text-lg font-bold text-teal-900 dark:text-teal-100">{{ $vitalSigns['respiratory_rate'] }} /min</p>
                        </div>
                        @endif
                    </div>
                </div>
                @endif

                <!-- Physical Examination -->
                @if($medicalReport->physical_examination)
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-3">Physical Examination</h3>
                    <div class="bg-gray-50 dark:bg-gray-700 rounded-md p-4">
                        <p class="text-gray-700 dark:text-gray-300 whitespace-pre-wrap">{{ $medicalReport->physical_examination }}</p>
                    </div>
                </div>
                @endif

                <!-- Diagnosis -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-3">Diagnosis</h3>
                    <div class="bg-green-50 dark:bg-green-900 border border-green-200 dark:border-green-700 rounded-md p-4">
                        <p class="text-green-800 dark:text-green-200 whitespace-pre-wrap">{{ $medicalReport->assessment_diagnosis }}</p>
                    </div>
                </div>

                <!-- Treatment Plan -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-3">Treatment Plan</h3>
                    <div class="bg-blue-50 dark:bg-blue-900 border border-blue-200 dark:border-blue-700 rounded-md p-4">
                        <p class="text-blue-800 dark:text-blue-200 whitespace-pre-wrap">{{ $medicalReport->treatment_plan }}</p>
                    </div>
                </div>                <!-- Enhanced Prescriptions Section -->
                @if($medicalReport->prescriptions->count() > 0)
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-3">
                        <i class="fas fa-prescription-bottle-alt mr-2 text-blue-500"></i>Prescriptions
                    </h3>
                    <div class="space-y-4">
                        @foreach($medicalReport->prescriptions as $prescription)
                            <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-700 rounded-lg p-4">
                                <div class="flex justify-between items-start mb-3">
                                    <div>
                                        <span class="text-sm font-medium text-blue-800 dark:text-blue-300">
                                            Prescription #{{ $prescription->prescription_number }}
                                        </span>
                                        <span class="ml-2 px-2 py-1 text-xs rounded-full {{ 
                                            $prescription->status === 'pending' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' :
                                            ($prescription->status === 'dispensed' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' :
                                            ($prescription->status === 'partial' ? 'bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-200' :
                                            'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200'))
                                        }}">
                                            {{ ucfirst($prescription->status) }}
                                        </span>
                                    </div>
                                    <span class="text-sm text-gray-600 dark:text-gray-400">
                                        Valid until: {{ $prescription->valid_until?->format('M d, Y') ?? 'No expiry' }}
                                    </span>
                                </div>
                                
                                <div class="space-y-3">
                                    @foreach($prescription->prescriptionMedications as $prescriptionMed)
                                        <div class="bg-white dark:bg-gray-700 rounded-md p-3 border-l-4 border-blue-400">
                                            <div class="flex justify-between items-start">
                                                <div class="flex-1">
                                                    <h4 class="font-semibold text-gray-900 dark:text-white">
                                                        {{ $prescriptionMed->medication->name }}
                                                    </h4>
                                                    @if($prescriptionMed->medication->generic_name && $prescriptionMed->medication->generic_name !== $prescriptionMed->medication->name)
                                                        <p class="text-sm text-gray-600 dark:text-gray-400">
                                                            Generic: {{ $prescriptionMed->medication->generic_name }}
                                                        </p>
                                                    @endif
                                                    <div class="mt-2 grid grid-cols-1 md:grid-cols-3 gap-2 text-sm">
                                                        <div>
                                                            <span class="font-medium text-gray-700 dark:text-gray-300">Dosage:</span>
                                                            <span class="text-gray-900 dark:text-white">{{ $prescriptionMed->dosage }}</span>
                                                        </div>
                                                        <div>
                                                            <span class="font-medium text-gray-700 dark:text-gray-300">Frequency:</span>
                                                            <span class="text-gray-900 dark:text-white">{{ $prescriptionMed->frequency }}</span>
                                                        </div>
                                                        <div>
                                                            <span class="font-medium text-gray-700 dark:text-gray-300">Duration:</span>
                                                            <span class="text-gray-900 dark:text-white">{{ $prescriptionMed->duration }}</span>
                                                        </div>
                                                    </div>
                                                    @if($prescriptionMed->instructions)
                                                        <div class="mt-2 p-2 bg-gray-50 dark:bg-gray-600 rounded text-sm">
                                                            <span class="font-medium text-gray-700 dark:text-gray-300">Instructions:</span>
                                                            <span class="text-gray-900 dark:text-white">{{ $prescriptionMed->instructions }}</span>
                                                        </div>
                                                    @endif
                                                </div>
                                                @if($prescriptionMed->quantity_prescribed)
                                                    <div class="text-right text-sm text-gray-600 dark:text-gray-400">
                                                        Qty: {{ $prescriptionMed->quantity_prescribed }}
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                
                                @if($prescription->notes)
                                    <div class="mt-3 p-2 bg-blue-100 dark:bg-blue-800/30 rounded text-sm">
                                        <span class="font-medium text-blue-800 dark:text-blue-300">Notes:</span>
                                        <span class="text-blue-900 dark:text-blue-200">{{ $prescription->notes }}</span>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
                @elseif($medicalReport->medications_prescribed)
                <!-- Fallback to legacy text display if no structured prescriptions -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-3">Medications Prescribed</h3>
                    <div class="bg-yellow-50 dark:bg-yellow-900 border border-yellow-200 dark:border-yellow-700 rounded-md p-4">
                        <p class="text-yellow-800 dark:text-yellow-200 whitespace-pre-wrap">{{ $medicalReport->medications_prescribed }}</p>
                    </div>
                </div>
                @endif

                <!-- Follow-up Instructions -->
                @if($medicalReport->follow_up_instructions)
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-3">Follow-up Instructions</h3>
                    <div class="bg-purple-50 dark:bg-purple-900 border border-purple-200 dark:border-purple-700 rounded-md p-4">
                        <p class="text-purple-800 dark:text-purple-200 whitespace-pre-wrap">{{ $medicalReport->follow_up_instructions }}</p>
                        @if($medicalReport->follow_up_date)
                            <div class="mt-3 pt-3 border-t border-purple-200 dark:border-purple-700">
                                <p class="text-purple-700 dark:text-purple-300 font-medium">
                                    Next appointment: {{ $medicalReport->follow_up_date->format('M d, Y') }}
                                </p>
                            </div>
                        @endif
                    </div>
                </div>
                @endif

                <!-- Report Type -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-3">Report Type</h3>
                    <div class="bg-gray-50 dark:bg-gray-700 rounded-md p-4">
                        <p class="text-gray-700 dark:text-gray-300">{{ $medicalReport->report_type }}</p>
                    </div>
                </div>                <!-- Enhanced Lab Test Requests Section -->
                @if($medicalReport->labTestRequests->count() > 0)
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-3">
                        <i class="fas fa-flask mr-2 text-green-500"></i>Lab Test Requests
                    </h3>
                    <div class="space-y-4">
                        @foreach($medicalReport->labTestRequests as $labTest)
                            <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-700 rounded-lg p-4">
                                <div class="flex justify-between items-start mb-3">
                                    <div>
                                        <h4 class="font-semibold text-gray-900 dark:text-white">{{ $labTest->test_name }}</h4>
                                        <span class="text-sm text-green-600 dark:text-green-400">
                                            Request #{{ $labTest->request_number }}
                                        </span>
                                    </div>
                                    <div class="flex flex-col items-end space-y-1">
                                        <span class="px-2 py-1 text-xs rounded-full {{ $labTest->priority_badge_class }}">
                                            {{ strtoupper($labTest->priority) }}
                                        </span>
                                        <span class="px-2 py-1 text-xs rounded-full {{ $labTest->status_badge_class }}">
                                            {{ ucfirst($labTest->status) }}
                                        </span>
                                    </div>
                                </div>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                                    <div>
                                        <span class="font-medium text-gray-700 dark:text-gray-300">Test Type:</span>
                                        <span class="text-gray-900 dark:text-white ml-2">{{ ucfirst($labTest->test_type) }}</span>
                                    </div>
                                    <div>
                                        <span class="font-medium text-gray-700 dark:text-gray-300">Requested Date:</span>
                                        <span class="text-gray-900 dark:text-white ml-2">{{ $labTest->requested_date->format('M d, Y') }}</span>
                                    </div>
                                    @if($labTest->preferred_date)
                                    <div>
                                        <span class="font-medium text-gray-700 dark:text-gray-300">Preferred Date:</span>
                                        <span class="text-gray-900 dark:text-white ml-2">{{ $labTest->preferred_date->format('M d, Y') }}</span>
                                    </div>
                                    @endif
                                    @if($labTest->scheduled_at)
                                    <div>
                                        <span class="font-medium text-gray-700 dark:text-gray-300">Scheduled:</span>
                                        <span class="text-gray-900 dark:text-white ml-2">{{ $labTest->scheduled_at->format('M d, Y \a\t H:i') }}</span>
                                    </div>
                                    @endif
                                </div>
                                
                                @if($labTest->clinical_notes)
                                    <div class="mt-3 p-2 bg-green-100 dark:bg-green-800/30 rounded text-sm">
                                        <span class="font-medium text-green-800 dark:text-green-300">Clinical Notes:</span>
                                        <span class="text-green-900 dark:text-green-200">{{ $labTest->clinical_notes }}</span>
                                    </div>
                                @endif
                                
                                @if($labTest->test_results)
                                    <div class="mt-3 p-3 bg-white dark:bg-gray-700 border border-green-200 dark:border-green-600 rounded">
                                        <h5 class="font-medium text-gray-900 dark:text-white mb-2">Test Results:</h5>
                                        <div class="text-sm text-gray-700 dark:text-gray-300">
                                            @if(is_array($labTest->test_results))
                                                @foreach($labTest->test_results as $key => $value)
                                                    <div class="flex justify-between py-1">
                                                        <span>{{ ucfirst(str_replace('_', ' ', $key)) }}:</span>
                                                        <span class="font-medium">{{ $value }}</span>
                                                    </div>
                                                @endforeach
                                            @else
                                                <div class="whitespace-pre-wrap">{{ $labTest->test_results }}</div>
                                            @endif
                                        </div>
                                    </div>
                                @endif
                                
                                @if($labTest->laboratory)
                                    <div class="mt-3 text-sm text-gray-600 dark:text-gray-400">
                                        <i class="fas fa-building mr-1"></i>
                                        Laboratory: {{ $labTest->laboratory->name }}
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
                @elseif($medicalReport->lab_tests_ordered)
                <!-- Fallback to legacy text display if no structured lab tests -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-3">Lab Tests Ordered</h3>
                    <div class="bg-gray-50 dark:bg-gray-700 rounded-md p-4">
                        <p class="text-gray-700 dark:text-gray-300 whitespace-pre-wrap">{{ $medicalReport->lab_tests_ordered }}</p>
                    </div>
                </div>
                @endif

                <!-- Imaging Studies -->
                @if($medicalReport->imaging_studies)
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-3">Imaging Studies</h3>
                    <div class="bg-gray-50 dark:bg-gray-700 rounded-md p-4">
                        <p class="text-gray-700 dark:text-gray-300 whitespace-pre-wrap">{{ $medicalReport->imaging_studies }}</p>
                    </div>
                </div>
                @endif

                <!-- Priority Level -->
                @if($medicalReport->priority_level)
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-3">Priority Level</h3>
                    <div class="bg-gray-50 dark:bg-gray-700 rounded-md p-4">
                        <p class="text-gray-700 dark:text-gray-300">{{ ucfirst($medicalReport->priority_level) }}</p>
                    </div>
                </div>
                @endif

                <!-- Follow Up Required -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-3">Follow Up Required</h3>
                    <div class="bg-gray-50 dark:bg-gray-700 rounded-md p-4">
                        <p class="text-gray-700 dark:text-gray-300">{{ $medicalReport->follow_up_required ? 'Yes' : 'No' }}</p>
                    </div>
                </div>

                <!-- Status -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-3">Status</h3>
                    <div class="bg-gray-50 dark:bg-gray-700 rounded-md p-4">
                        <p class="text-gray-700 dark:text-gray-300">{{ ucfirst($medicalReport->status) }}</p>
                    </div>
                </div>

                <!-- Completed At -->
                @if($medicalReport->completed_at)
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-3">Completed At</h3>
                    <div class="bg-gray-50 dark:bg-gray-700 rounded-md p-4">
                        <p class="text-gray-700 dark:text-gray-300">{{ $medicalReport->completed_at->format('M d, Y g:i A') }}</p>
                    </div>
                </div>
                @endif

                <!-- Additional Notes -->
                @if($medicalReport->additional_notes)
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-3">Additional Notes</h3>
                    <div class="bg-gray-50 dark:bg-gray-700 rounded-md p-4">
                        <p class="text-gray-700 dark:text-gray-300 whitespace-pre-wrap">{{ $medicalReport->additional_notes }}</p>
                    </div>
                </div>
                @endif

                <!-- Footer -->
                <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                    <div class="flex justify-between items-center text-sm text-gray-500 dark:text-gray-400">
                        <p>Report created on {{ $medicalReport->created_at->format('M d, Y \a\t g:i A') }}</p>
                        @if($medicalReport->updated_at != $medicalReport->created_at)
                            <p>Last updated on {{ $medicalReport->updated_at->format('M d, Y \a\t g:i A') }}</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
