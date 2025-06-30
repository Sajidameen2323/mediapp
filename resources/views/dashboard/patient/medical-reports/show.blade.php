@extends('layouts.app')

@section('title', 'Medical Report Details')

@section('content')
<x-patient-navigation />

<div class="max-w-6xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="px-4 py-6 sm:px-0">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                <div class="flex-1">
                    <nav class="flex mb-4" aria-label="Breadcrumb">
                        <ol class="flex items-center space-x-2 text-sm">
                            <li>
                                <a href="{{ route('patient.medical-reports.index') }}" 
                                   class="text-gray-400 dark:text-gray-500 hover:text-gray-600 dark:hover:text-gray-300 transition-colors">
                                    <i class="fas fa-file-medical text-lg"></i>
                                </a>
                            </li>
                            <li>
                                <i class="fas fa-chevron-right text-gray-300 dark:text-gray-600 text-xs"></i>
                            </li>
                            <li>
                                <a href="{{ route('patient.medical-reports.index') }}" 
                                   class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 font-medium transition-colors">
                                    Medical Reports
                                </a>
                            </li>
                            <li>
                                <i class="fas fa-chevron-right text-gray-300 dark:text-gray-600 text-xs"></i>
                            </li>
                            <li>
                                <span class="text-gray-700 dark:text-gray-300 font-medium">Report Details</span>
                            </li>
                        </ol>
                    </nav>
                    <div class="flex items-center gap-4">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center">
                                <i class="fas fa-stethoscope text-white text-xl"></i>
                            </div>
                        </div>
                        <div>
                            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">
                                {{ $medicalReport->title ?? 'Medical Report' }}
                            </h1>
                            <p class="text-gray-600 dark:text-gray-400 mt-1">
                                {{ $medicalReport->consultation_date ? $medicalReport->consultation_date->format('F d, Y') : $medicalReport->created_at->format('F d, Y') }}
                            </p>
                        </div>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <!-- Status Badge -->
                    <div class="flex items-center">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                            @if($medicalReport->status === 'completed') 
                                bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                            @elseif($medicalReport->status === 'draft') 
                                bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                            @else 
                                bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-200
                            @endif">
                            <i class="fas fa-circle text-xs mr-2
                                @if($medicalReport->status === 'completed') text-green-500
                                @elseif($medicalReport->status === 'draft') text-yellow-500
                                @else text-gray-500
                                @endif"></i>
                            {{ ucfirst($medicalReport->status) }}
                        </span>
                    </div>
                    
                    <!-- Priority Badge -->
                    @if($medicalReport->priority_level)
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                            @if($medicalReport->priority_level === 'emergency') 
                                bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200
                            @elseif($medicalReport->priority_level === 'urgent') 
                                bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-200
                            @else 
                                bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200
                            @endif">
                            <i class="fas fa-exclamation-triangle text-xs mr-2"></i>
                            {{ ucfirst($medicalReport->priority_level) }}
                        </span>
                    @endif
                      <!-- Access Management Button -->
                    <a href="{{ route('patient.medical-reports.access.index', $medicalReport) }}" 
                       class="no-print inline-flex items-center px-4 py-2 border border-blue-300 dark:border-blue-600 rounded-lg shadow-sm text-sm font-medium text-blue-700 dark:text-blue-300 bg-blue-50 dark:bg-blue-900/20 hover:bg-blue-100 dark:hover:bg-blue-900/40 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:focus:ring-offset-gray-800 transition-colors">
                        <i class="fas fa-user-shield mr-2"></i>
                        Manage Access
                    </a>
                    
                    <!-- Print Button -->
                    <button type="button" 
                            onclick="window.print()" 
                            class="no-print inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:focus:ring-offset-gray-800 transition-colors">
                        <i class="fas fa-print mr-2"></i>
                        Print Report
                    </button>
                </div>
            </div>
        </div>        <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
            <!-- Main Content -->
            <div class="xl:col-span-2 space-y-6">
                <!-- Report Overview -->
                <div class="bg-white dark:bg-gray-800 shadow-lg rounded-xl border border-gray-200 dark:border-gray-700">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-gray-700 dark:to-gray-600 rounded-t-xl">
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                            <i class="fas fa-info-circle text-blue-600 dark:text-blue-400 mr-2"></i>
                            Report Overview
                        </h2>
                    </div>
                    <div class="px-6 py-6">
                        <dl class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div class="flex items-start space-x-3">
                                <div class="flex-shrink-0 w-8 h-8 bg-blue-100 dark:bg-blue-900 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-user text-blue-600 dark:text-blue-400 text-sm"></i>
                                </div>
                                <div class="flex-1">
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Patient</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-white font-medium">{{ $medicalReport->patient->name }}</dd>
                                </div>
                            </div>
                            
                            <div class="flex items-start space-x-3">
                                <div class="flex-shrink-0 w-8 h-8 bg-green-100 dark:bg-green-900 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-user-md text-green-600 dark:text-green-400 text-sm"></i>
                                </div>
                                <div class="flex-1">
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Doctor</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-white font-medium">
                                        @if($medicalReport->doctor && $medicalReport->doctor->user)
                                            {{ $medicalReport->doctor->user->name }}
                                        @else
                                            No doctor assigned
                                        @endif
                                    </dd>
                                </div>
                            </div>
                            
                            @if($medicalReport->report_type)
                            <div class="flex items-start space-x-3">
                                <div class="flex-shrink-0 w-8 h-8 bg-purple-100 dark:bg-purple-900 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-clipboard-list text-purple-600 dark:text-purple-400 text-sm"></i>
                                </div>
                                <div class="flex-1">
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Report Type</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-white font-medium">{{ $medicalReport->report_type }}</dd>
                                </div>
                            </div>
                            @endif
                            
                            <div class="flex items-start space-x-3">
                                <div class="flex-shrink-0 w-8 h-8 bg-orange-100 dark:bg-orange-900 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-calendar text-orange-600 dark:text-orange-400 text-sm"></i>
                                </div>
                                <div class="flex-1">
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Consultation Date</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-white font-medium">
                                        {{ $medicalReport->consultation_date ? $medicalReport->consultation_date->format('F d, Y') : $medicalReport->created_at->format('F d, Y') }}
                                    </dd>
                                </div>
                            </div>
                            
                            @if($medicalReport->completed_at)
                            <div class="flex items-start space-x-3">
                                <div class="flex-shrink-0 w-8 h-8 bg-teal-100 dark:bg-teal-900 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-check-circle text-teal-600 dark:text-teal-400 text-sm"></i>
                                </div>
                                <div class="flex-1">
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Completed At</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-white font-medium">{{ $medicalReport->completed_at->format('F d, Y \a\t g:i A') }}</dd>
                                </div>
                            </div>
                            @endif
                            
                            @if($medicalReport->follow_up_required)
                            <div class="flex items-start space-x-3">
                                <div class="flex-shrink-0 w-8 h-8 bg-yellow-100 dark:bg-yellow-900 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-calendar-plus text-yellow-600 dark:text-yellow-400 text-sm"></i>
                                </div>
                                <div class="flex-1">
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Follow-up Required</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-white font-medium">{{ $medicalReport->follow_up_required }}</dd>
                                </div>
                            </div>
                            @endif
                        </dl>
                    </div>
                </div>

                <!-- Chief Complaint & History -->
                @if($medicalReport->chief_complaint || $medicalReport->history_of_present_illness)
                <div class="bg-white dark:bg-gray-800 shadow-lg rounded-xl border border-gray-200 dark:border-gray-700">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-red-50 to-pink-50 dark:from-gray-700 dark:to-gray-600 rounded-t-xl">
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                            <i class="fas fa-comment-medical text-red-600 dark:text-red-400 mr-2"></i>
                            Chief Complaint & History
                        </h2>
                    </div>
                    <div class="px-6 py-6 space-y-6">
                        @if($medicalReport->chief_complaint)
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-3 uppercase tracking-wide">Chief Complaint</h3>
                            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                                <p class="text-gray-900 dark:text-white whitespace-pre-wrap">{{ $medicalReport->chief_complaint }}</p>
                            </div>
                        </div>
                        @endif
                        
                        @if($medicalReport->history_of_present_illness)
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-3 uppercase tracking-wide">History of Present Illness</h3>
                            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                                <p class="text-gray-900 dark:text-white whitespace-pre-wrap">{{ $medicalReport->history_of_present_illness }}</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
                @endif

                <!-- Vital Signs -->
                @if($medicalReport->vital_signs)
                <div class="bg-white dark:bg-gray-800 shadow-lg rounded-xl border border-gray-200 dark:border-gray-700">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-green-50 to-emerald-50 dark:from-gray-700 dark:to-gray-600 rounded-t-xl">
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                            <i class="fas fa-heartbeat text-green-600 dark:text-green-400 mr-2"></i>
                            Vital Signs
                        </h2>
                    </div>
                    <div class="px-6 py-6">
                        @php
                            $vitalSigns = is_array($medicalReport->vital_signs) 
                                ? $medicalReport->vital_signs 
                                : json_decode($medicalReport->vital_signs, true);
                        @endphp
                        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                            @if(isset($vitalSigns['blood_pressure']) && $vitalSigns['blood_pressure'])
                            <div class="bg-gradient-to-br from-red-50 to-red-100 dark:from-red-900/20 dark:to-red-800/20 rounded-lg p-4 border border-red-200 dark:border-red-800">
                                <div class="flex items-center justify-between mb-2">
                                    <i class="fas fa-tint text-red-600 dark:text-red-400"></i>
                                    <span class="text-xs font-medium text-red-600 dark:text-red-400 uppercase tracking-wide">BP</span>
                                </div>
                                <div class="text-lg font-bold text-gray-900 dark:text-white">{{ $vitalSigns['blood_pressure'] }}</div>
                                <div class="text-xs text-gray-600 dark:text-gray-400">mmHg</div>
                            </div>
                            @endif
                            
                            @if(isset($vitalSigns['heart_rate']) && $vitalSigns['heart_rate'])
                            <div class="bg-gradient-to-br from-pink-50 to-pink-100 dark:from-pink-900/20 dark:to-pink-800/20 rounded-lg p-4 border border-pink-200 dark:border-pink-800">
                                <div class="flex items-center justify-between mb-2">
                                    <i class="fas fa-heartbeat text-pink-600 dark:text-pink-400"></i>
                                    <span class="text-xs font-medium text-pink-600 dark:text-pink-400 uppercase tracking-wide">HR</span>
                                </div>
                                <div class="text-lg font-bold text-gray-900 dark:text-white">{{ $vitalSigns['heart_rate'] }}</div>
                                <div class="text-xs text-gray-600 dark:text-gray-400">bpm</div>
                            </div>
                            @endif
                            
                            @if(isset($vitalSigns['temperature']) && $vitalSigns['temperature'])
                            <div class="bg-gradient-to-br from-orange-50 to-orange-100 dark:from-orange-900/20 dark:to-orange-800/20 rounded-lg p-4 border border-orange-200 dark:border-orange-800">
                                <div class="flex items-center justify-between mb-2">
                                    <i class="fas fa-thermometer-half text-orange-600 dark:text-orange-400"></i>
                                    <span class="text-xs font-medium text-orange-600 dark:text-orange-400 uppercase tracking-wide">Temp</span>
                                </div>
                                <div class="text-lg font-bold text-gray-900 dark:text-white">{{ $vitalSigns['temperature'] }}</div>
                                <div class="text-xs text-gray-600 dark:text-gray-400">°F</div>
                            </div>
                            @endif
                            
                            @if(isset($vitalSigns['respiratory_rate']) && $vitalSigns['respiratory_rate'])
                            <div class="bg-gradient-to-br from-blue-50 to-blue-100 dark:from-blue-900/20 dark:to-blue-800/20 rounded-lg p-4 border border-blue-200 dark:border-blue-800">
                                <div class="flex items-center justify-between mb-2">
                                    <i class="fas fa-lungs text-blue-600 dark:text-blue-400"></i>
                                    <span class="text-xs font-medium text-blue-600 dark:text-blue-400 uppercase tracking-wide">RR</span>
                                </div>
                                <div class="text-lg font-bold text-gray-900 dark:text-white">{{ $vitalSigns['respiratory_rate'] }}</div>
                                <div class="text-xs text-gray-600 dark:text-gray-400">/min</div>
                            </div>
                            @endif
                            
                            @if(isset($vitalSigns['oxygen_saturation']) && $vitalSigns['oxygen_saturation'])
                            <div class="bg-gradient-to-br from-cyan-50 to-cyan-100 dark:from-cyan-900/20 dark:to-cyan-800/20 rounded-lg p-4 border border-cyan-200 dark:border-cyan-800">
                                <div class="flex items-center justify-between mb-2">
                                    <i class="fas fa-percentage text-cyan-600 dark:text-cyan-400"></i>
                                    <span class="text-xs font-medium text-cyan-600 dark:text-cyan-400 uppercase tracking-wide">O2 Sat</span>
                                </div>
                                <div class="text-lg font-bold text-gray-900 dark:text-white">{{ $vitalSigns['oxygen_saturation'] }}</div>
                                <div class="text-xs text-gray-600 dark:text-gray-400">%</div>
                            </div>
                            @endif
                            
                            @if(isset($vitalSigns['weight']) && $vitalSigns['weight'])
                            <div class="bg-gradient-to-br from-purple-50 to-purple-100 dark:from-purple-900/20 dark:to-purple-800/20 rounded-lg p-4 border border-purple-200 dark:border-purple-800">
                                <div class="flex items-center justify-between mb-2">
                                    <i class="fas fa-weight text-purple-600 dark:text-purple-400"></i>
                                    <span class="text-xs font-medium text-purple-600 dark:text-purple-400 uppercase tracking-wide">Weight</span>
                                </div>
                                <div class="text-lg font-bold text-gray-900 dark:text-white">{{ $vitalSigns['weight'] }}</div>
                                <div class="text-xs text-gray-600 dark:text-gray-400">kg</div>
                            </div>
                            @endif
                            
                            @if(isset($vitalSigns['height']) && $vitalSigns['height'])
                            <div class="bg-gradient-to-br from-indigo-50 to-indigo-100 dark:from-indigo-900/20 dark:to-indigo-800/20 rounded-lg p-4 border border-indigo-200 dark:border-indigo-800">
                                <div class="flex items-center justify-between mb-2">
                                    <i class="fas fa-ruler-vertical text-indigo-600 dark:text-indigo-400"></i>
                                    <span class="text-xs font-medium text-indigo-600 dark:text-indigo-400 uppercase tracking-wide">Height</span>
                                </div>
                                <div class="text-lg font-bold text-gray-900 dark:text-white">{{ $vitalSigns['height'] }}</div>
                                <div class="text-xs text-gray-600 dark:text-gray-400">cm</div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                @endif

                <!-- Physical Examination -->
                @if($medicalReport->physical_examination)
                <div class="bg-white dark:bg-gray-800 shadow-lg rounded-xl border border-gray-200 dark:border-gray-700">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-indigo-50 to-purple-50 dark:from-gray-700 dark:to-gray-600 rounded-t-xl">
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                            <i class="fas fa-search text-indigo-600 dark:text-indigo-400 mr-2"></i>
                            Physical Examination
                        </h2>
                    </div>
                    <div class="px-6 py-6">
                        <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                            <p class="text-gray-900 dark:text-white whitespace-pre-wrap">{{ $medicalReport->physical_examination }}</p>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Assessment & Diagnosis -->
                @if($medicalReport->assessment_diagnosis)
                <div class="bg-white dark:bg-gray-800 shadow-lg rounded-xl border border-gray-200 dark:border-gray-700">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-emerald-50 to-teal-50 dark:from-gray-700 dark:to-gray-600 rounded-t-xl">
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                            <i class="fas fa-diagnoses text-emerald-600 dark:text-emerald-400 mr-2"></i>
                            Assessment & Diagnosis
                        </h2>
                    </div>
                    <div class="px-6 py-6">
                        <div class="bg-emerald-50 dark:bg-emerald-900/20 rounded-lg p-4 border border-emerald-200 dark:border-emerald-800">
                            <p class="text-gray-900 dark:text-white whitespace-pre-wrap font-medium">{{ $medicalReport->assessment_diagnosis }}</p>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Treatment Plan -->
                @if($medicalReport->treatment_plan)
                <div class="bg-white dark:bg-gray-800 shadow-lg rounded-xl border border-gray-200 dark:border-gray-700">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-cyan-50 to-sky-50 dark:from-gray-700 dark:to-gray-600 rounded-t-xl">
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                            <i class="fas fa-procedures text-cyan-600 dark:text-cyan-400 mr-2"></i>
                            Treatment Plan
                        </h2>
                    </div>
                    <div class="px-6 py-6">
                        <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                            <p class="text-gray-900 dark:text-white whitespace-pre-wrap">{{ $medicalReport->treatment_plan }}</p>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Lab Tests Ordered & Imaging Studies -->
                @if($medicalReport->lab_tests_ordered || $medicalReport->imaging_studies)
                <div class="bg-white dark:bg-gray-800 shadow-lg rounded-xl border border-gray-200 dark:border-gray-700">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-violet-50 to-purple-50 dark:from-gray-700 dark:to-gray-600 rounded-t-xl">
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                            <i class="fas fa-vial text-violet-600 dark:text-violet-400 mr-2"></i>
                            Tests & Studies
                        </h2>
                    </div>
                    <div class="px-6 py-6 space-y-6">
                        @if($medicalReport->lab_tests_ordered)
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-3 uppercase tracking-wide">Lab Tests Ordered</h3>
                            <div class="bg-violet-50 dark:bg-violet-900/20 rounded-lg p-4 border border-violet-200 dark:border-violet-800">
                                <p class="text-gray-900 dark:text-white whitespace-pre-wrap">{{ $medicalReport->lab_tests_ordered }}</p>
                            </div>
                        </div>
                        @endif
                        
                        @if($medicalReport->imaging_studies)
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-3 uppercase tracking-wide">Imaging Studies</h3>
                            <div class="bg-purple-50 dark:bg-purple-900/20 rounded-lg p-4 border border-purple-200 dark:border-purple-800">
                                <p class="text-gray-900 dark:text-white whitespace-pre-wrap">{{ $medicalReport->imaging_studies }}</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
                @endif

                <!-- Follow-up Instructions & Notes -->
                @if($medicalReport->follow_up_instructions || $medicalReport->additional_notes)
                <div class="bg-white dark:bg-gray-800 shadow-lg rounded-xl border border-gray-200 dark:border-gray-700">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-amber-50 to-yellow-50 dark:from-gray-700 dark:to-gray-600 rounded-t-xl">
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                            <i class="fas fa-clipboard-check text-amber-600 dark:text-amber-400 mr-2"></i>
                            Instructions & Notes
                        </h2>
                    </div>
                    <div class="px-6 py-6 space-y-6">
                        @if($medicalReport->follow_up_instructions)
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-3 uppercase tracking-wide">Follow-up Instructions</h3>
                            <div class="bg-amber-50 dark:bg-amber-900/20 rounded-lg p-4 border border-amber-200 dark:border-amber-800">
                                <p class="text-gray-900 dark:text-white whitespace-pre-wrap">{{ $medicalReport->follow_up_instructions }}</p>
                            </div>
                        </div>
                        @endif
                        
                        @if($medicalReport->additional_notes)
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-3 uppercase tracking-wide">Additional Notes</h3>
                            <div class="bg-yellow-50 dark:bg-yellow-900/20 rounded-lg p-4 border border-yellow-200 dark:border-yellow-800">
                                <p class="text-gray-900 dark:text-white whitespace-pre-wrap">{{ $medicalReport->additional_notes }}</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="xl:col-span-1 space-y-6">
                <!-- Quick Stats -->
                <div class="bg-white dark:bg-gray-800 shadow-lg rounded-xl border border-gray-200 dark:border-gray-700">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                            <i class="fas fa-chart-bar text-blue-600 dark:text-blue-400 mr-2"></i>
                            Quick Stats
                        </h3>
                    </div>
                    <div class="px-6 py-6 space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Prescriptions</span>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200">
                                {{ $medicalReport->prescriptions->count() }}
                            </span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Lab Tests</span>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 dark:bg-purple-900 text-purple-800 dark:text-purple-200">
                                {{ $medicalReport->labTestRequests->count() }}
                            </span>
                        </div>
                        @if($medicalReport->medications_prescribed)
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Legacy Medications</span>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200">
                                <i class="fas fa-file-alt text-xs mr-1"></i>
                                Text
                            </span>
                        </div>
                        @endif
                    </div>
                </div>                <!-- Prescriptions -->
                @if($medicalReport->prescriptions->isNotEmpty())
                <div class="bg-white dark:bg-gray-800 shadow-lg rounded-xl border border-gray-200 dark:border-gray-700">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-green-50 to-emerald-50 dark:from-gray-700 dark:to-gray-600 rounded-t-xl flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                            <i class="fas fa-prescription-bottle-alt text-green-600 dark:text-green-400 mr-2"></i>
                            Prescriptions ({{ $medicalReport->prescriptions->count() }})
                        </h3>
                        <a href="{{ route('patient.prescriptions.index', ['medical_report' => $medicalReport->id]) }}" 
                           class="no-print text-sm text-green-600 dark:text-green-400 hover:text-green-500 dark:hover:text-green-300 font-medium transition-colors">
                            View All <i class="fas fa-arrow-right ml-1"></i>
                        </a>
                    </div>
                    <div class="px-6 py-6">
                        <div class="space-y-4">
                            @foreach($medicalReport->prescriptions as $prescription)
                                <div class="bg-gradient-to-r from-green-50 to-emerald-50 dark:from-green-900/20 dark:to-emerald-900/20 border border-green-200 dark:border-green-800 rounded-lg p-5">
                                    <div class="flex items-center justify-between mb-4">
                                        <div class="flex items-center space-x-3">
                                            <div class="w-10 h-10 bg-green-100 dark:bg-green-900 rounded-lg flex items-center justify-center">
                                                <i class="fas fa-pills text-green-600 dark:text-green-400"></i>
                                            </div>
                                            <div>
                                                <h4 class="text-lg font-semibold text-gray-900 dark:text-white">Prescription #{{ $prescription->id }}</h4>
                                                <p class="text-sm text-gray-600 dark:text-gray-400">{{ $prescription->created_at->format('M d, Y') }}</p>
                                            </div>
                                        </div>
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $prescription->status === 'active' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-200' }}">
                                            <i class="fas fa-circle text-xs mr-2 {{ $prescription->status === 'active' ? 'text-green-500' : 'text-gray-500' }}"></i>
                                            {{ ucfirst($prescription->status) }}
                                        </span>
                                    </div>
                                    
                                    @if($prescription->prescriptionMedications->isNotEmpty())
                                        <div class="space-y-3">
                                            <h5 class="text-sm font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wide">Medications</h5>
                                            <div class="grid gap-3">
                                                @foreach($prescription->prescriptionMedications as $prescriptionMedication)
                                                    <div class="bg-white dark:bg-gray-800 rounded-lg p-4 border border-green-200 dark:border-green-700">
                                                        <div class="flex items-start justify-between">
                                                            <div class="flex-1">
                                                                <h6 class="font-semibold text-gray-900 dark:text-white text-lg">{{ $prescriptionMedication->medication->name }}</h6>
                                                                <div class="mt-2 space-y-1">
                                                                    <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                                                                        <i class="fas fa-capsules w-4 h-4 mr-2"></i>
                                                                        <span class="font-medium">Dosage:</span> 
                                                                        <span class="ml-1">{{ $prescriptionMedication->dosage }}</span>
                                                                    </div>
                                                                    <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                                                                        <i class="fas fa-clock w-4 h-4 mr-2"></i>
                                                                        <span class="font-medium">Frequency:</span> 
                                                                        <span class="ml-1">{{ $prescriptionMedication->frequency }}</span>
                                                                    </div>
                                                                    @if($prescriptionMedication->duration)
                                                                    <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                                                                        <i class="fas fa-calendar-alt w-4 h-4 mr-2"></i>
                                                                        <span class="font-medium">Duration:</span> 
                                                                        <span class="ml-1">{{ $prescriptionMedication->duration }}</span>
                                                                    </div>
                                                                    @endif
                                                                    @if($prescriptionMedication->instructions)
                                                                    <div class="flex items-start text-sm text-gray-600 dark:text-gray-400 mt-2">
                                                                        <i class="fas fa-info-circle w-4 h-4 mr-2 mt-0.5"></i>
                                                                        <div>
                                                                            <span class="font-medium">Instructions:</span>
                                                                            <p class="ml-1 text-gray-700 dark:text-gray-300">{{ $prescriptionMedication->instructions }}</p>
                                                                        </div>
                                                                    </div>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                            @if($prescriptionMedication->quantity_prescribed)
                                                            <div class="ml-4 text-right">
                                                                <div class="text-sm text-gray-500 dark:text-gray-400">Quantity</div>
                                                                <div class="text-lg font-bold text-gray-900 dark:text-white">{{ $prescriptionMedication->quantity_prescribed }}</div>
                                                            </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                    
                                    <div class="mt-4 pt-4 border-t border-green-200 dark:border-green-700">
                                        <a href="{{ route('patient.prescriptions.show', $prescription) }}" 
                                           class="no-print inline-flex items-center text-sm text-green-600 dark:text-green-400 hover:text-green-500 dark:hover:text-green-300 font-medium transition-colors">
                                            <i class="fas fa-eye mr-2"></i>
                                            View Full Prescription
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
                <div class="bg-white dark:bg-gray-800 shadow-lg rounded-xl border border-gray-200 dark:border-gray-700">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-purple-50 to-violet-50 dark:from-gray-700 dark:to-gray-600 rounded-t-xl flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                            <i class="fas fa-flask text-purple-600 dark:text-purple-400 mr-2"></i>
                            Lab Test Requests ({{ $medicalReport->labTestRequests->count() }})
                        </h3>
                        <a href="{{ route('patient.lab-tests.index', ['medical_report' => $medicalReport->id]) }}" 
                           class="no-print text-sm text-purple-600 dark:text-purple-400 hover:text-purple-500 dark:hover:text-purple-300 font-medium transition-colors">
                            View All <i class="fas fa-arrow-right ml-1"></i>
                        </a>
                    </div>
                    <div class="px-6 py-6">
                        <div class="space-y-4">
                            @foreach($medicalReport->labTestRequests as $labTest)
                                <div class="bg-gradient-to-r from-purple-50 to-violet-50 dark:from-purple-900/20 dark:to-violet-900/20 border border-purple-200 dark:border-purple-800 rounded-lg p-5">
                                    <div class="flex items-center justify-between mb-4">
                                        <div class="flex items-center space-x-3">
                                            <div class="w-10 h-10 bg-purple-100 dark:bg-purple-900 rounded-lg flex items-center justify-center">
                                                <i class="fas fa-vial text-purple-600 dark:text-purple-400"></i>
                                            </div>
                                            <div>
                                                <h4 class="text-lg font-semibold text-gray-900 dark:text-white">{{ $labTest->test_name }}</h4>
                                                <p class="text-sm text-gray-600 dark:text-gray-400">{{ $labTest->request_number }}</p>
                                            </div>
                                        </div>
                                        <div class="flex items-center space-x-2">
                                            @if($labTest->priority && $labTest->priority !== 'routine')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                @if($labTest->priority === 'stat') bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200
                                                @elseif($labTest->priority === 'urgent') bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-200
                                                @else bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-200
                                                @endif">
                                                <i class="fas fa-exclamation-triangle text-xs mr-1"></i>
                                                {{ ucfirst($labTest->priority) }}
                                            </span>
                                            @endif
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium 
                                                @if($labTest->status === 'pending') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                                                @elseif($labTest->status === 'completed') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                                @elseif($labTest->status === 'in_progress') bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200
                                                @else bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-200
                                                @endif">
                                                <i class="fas fa-circle text-xs mr-2
                                                    @if($labTest->status === 'pending') text-yellow-500
                                                    @elseif($labTest->status === 'completed') text-green-500
                                                    @elseif($labTest->status === 'in_progress') text-blue-500
                                                    @else text-gray-500
                                                    @endif"></i>
                                                {{ ucfirst(str_replace('_', ' ', $labTest->status)) }}
                                            </span>
                                        </div>
                                    </div>
                                    
                                    @if($labTest->test_type || $labTest->clinical_notes || $labTest->instructions)
                                    <div class="bg-white dark:bg-gray-800 rounded-lg p-4 border border-purple-200 dark:border-purple-700">
                                        <div class="space-y-3">
                                            @if($labTest->test_type)
                                            <div class="flex items-center text-sm">
                                                <i class="fas fa-tag w-4 h-4 mr-2 text-purple-600 dark:text-purple-400"></i>
                                                <span class="font-medium text-gray-700 dark:text-gray-300">Type:</span>
                                                <span class="ml-2 text-gray-900 dark:text-white capitalize">{{ $labTest->test_type }}</span>
                                            </div>
                                            @endif
                                            @if($labTest->clinical_notes)
                                            <div class="flex items-start text-sm">
                                                <i class="fas fa-notes-medical w-4 h-4 mr-2 mt-0.5 text-purple-600 dark:text-purple-400"></i>
                                                <div>
                                                    <span class="font-medium text-gray-700 dark:text-gray-300">Clinical Notes:</span>
                                                    <p class="ml-2 text-gray-900 dark:text-white">{{ $labTest->clinical_notes }}</p>
                                                </div>
                                            </div>
                                            @endif
                                            @if($labTest->instructions)
                                            <div class="flex items-start text-sm">
                                                <i class="fas fa-info-circle w-4 h-4 mr-2 mt-0.5 text-purple-600 dark:text-purple-400"></i>
                                                <div>
                                                    <span class="font-medium text-gray-700 dark:text-gray-300">Instructions:</span>
                                                    <p class="ml-2 text-gray-900 dark:text-white">{{ $labTest->instructions }}</p>
                                                </div>
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                    @endif
                                    
                                    <div class="mt-4 pt-4 border-t border-purple-200 dark:border-purple-700">
                                        <a href="{{ route('patient.lab-tests.show', $labTest) }}" 
                                           class="no-print inline-flex items-center text-sm text-purple-600 dark:text-purple-400 hover:text-purple-500 dark:hover:text-purple-300 font-medium transition-colors">
                                            <i class="fas fa-eye mr-2"></i>
                                            View Test Details
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
                    @if($medicalReport->medications_prescribed)
                        <div class="bg-white dark:bg-gray-800 shadow-lg rounded-xl border border-gray-200 dark:border-gray-700">
                            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-gray-700 dark:to-gray-600 rounded-t-xl">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                                    <i class="fas fa-prescription text-blue-600 dark:text-blue-400 mr-2"></i>
                                    Prescribed Medications
                                    <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200">
                                        Legacy Format
                                    </span>
                                </h3>
                            </div>
                            <div class="px-6 py-6">
                                <div class="bg-blue-50 dark:bg-blue-900/20 rounded-lg p-4 border border-blue-200 dark:border-blue-800">
                                    <p class="text-gray-900 dark:text-white whitespace-pre-wrap">{{ $medicalReport->medications_prescribed }}</p>
                                </div>
                            </div>
                        </div>
                    @endif
                @endif
            </div>
        </div>

        <!-- Actions -->
        <div class="no-print flex flex-col sm:flex-row justify-end gap-3 pt-8 border-t border-gray-200 dark:border-gray-700">
            <a href="{{ route('patient.medical-reports.index') }}" 
               class="inline-flex items-center justify-center px-6 py-3 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:focus:ring-offset-gray-800 transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>
                Back to Reports
            </a>
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
            -webkit-print-color-adjust: exact;
        }
        
        .dark\:bg-gray-800,
        .dark\:bg-gray-700 {
            background-color: white !important;
        }
        
        .dark\:text-white,
        .dark\:text-gray-200,
        .dark\:text-gray-300 {
            color: black !important;
        }
        
        .shadow-lg {
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1) !important;
        }
    }
</style>
@endpush
@endsection
