@extends('layouts.app')

@section('title', 'Edit Medical Report - Doctor Dashboard')

@push('styles')
    <style>
        /* Enhanced Quick Actions */

        .section-collapse {
            transition: max-height 0.3s ease-in-out;
            overflow: hidden;
        }

        .auto-suggest {
            position: absolute;
            z-index: 100;
            max-height: 200px;
            overflow-y: auto;
        }

        .patient-search-dropdown {
            max-height: 300px;
            overflow-y: auto;
        }

        .patient-card-preview {
            animation: slideIn 0.3s ease-out;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .form-section-transition {
            transition: all 0.3s ease-in-out;
        }

        .field-status {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            display: inline-block;
            margin-left: 8px;
        }

        .field-complete {
            background-color: #10b981;
        }

        .field-partial {
            background-color: #f59e0b;
        }

        .field-empty {
            background-color: #ef4444;
        }

        .progress-tracker {
            position: sticky;
            top: 20px;
            z-index: 50;
        }

        /* Speech recognition indicator */
        .speech-indicator {
            animation: pulse 1.5s ease-in-out infinite;
        }

        @keyframes pulse {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0.5;
            }
        }

        .vital-input:focus {
            transform: scale(1.02);
            transition: transform 0.2s ease;
        }
    </style>
@endpush

@section('content')
    <div
        class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-purple-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- Form Errors Section -->
            @if ($errors->any())
                <div
                    class="mb-8 bg-red-50 dark:bg-red-900/20 border-l-4 border-red-400 dark:border-red-600 p-6 rounded-xl shadow-lg">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <div class="bg-red-500 p-2 rounded-lg">
                                <i class="fas fa-exclamation-triangle text-white"></i>
                            </div>
                        </div>
                        <div class="ml-4 flex-1">
                            <h3 class="text-lg font-semibold text-red-800 dark:text-red-300 mb-2">
                                <i class="fas fa-times-circle mr-2"></i>Form Submission Errors
                            </h3>
                            <div class="text-red-700 dark:text-red-400">
                                <p class="mb-3">Please correct the following errors and try again:</p>
                                <ul class="list-disc list-inside space-y-1">
                                    @foreach ($errors->all() as $error)
                                        <li class="text-sm">{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                        <div class="flex-shrink-0">
                            <button type="button"
                                onclick="this.parentElement.parentElement.parentElement.style.display='none'"
                                class="text-red-400 hover:text-red-600 dark:text-red-500 dark:hover:text-red-300 transition-colors">
                                <i class="fas fa-times text-lg"></i>
                            </button>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Enhanced Header -->
            <div class="mb-6">
                <div
                    class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-8 border border-gray-100 dark:border-gray-700">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-4">
                            <div class="bg-gradient-to-r from-purple-600 to-indigo-600 p-3 rounded-xl">
                                <i class="fas fa-edit text-white text-2xl"></i>
                            </div>
                            <div>
                                <h1
                                    class="text-4xl font-bold bg-gradient-to-r from-purple-600 to-indigo-600 bg-clip-text text-transparent">
                                    Edit Medical Report
                                </h1>
                                <p class="mt-2 text-gray-600 dark:text-gray-400 text-lg">Update patient consultation for
                                    report #{{ $medicalReport->id }}</p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-3">
                            <button type="button" id="voice-input-btn" onclick="toggleVoiceInput()"
                                class="bg-gradient-to-r from-blue-500 to-purple-600 hover:from-blue-600 hover:to-purple-700 
                                       dark:from-blue-600 dark:to-purple-700 dark:hover:from-blue-700 dark:hover:to-purple-800
                                       text-white px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 
                                       shadow-lg hover:shadow-xl transform hover:-translate-y-1
                                       border border-blue-400 dark:border-blue-500 hover:border-blue-500 dark:hover:border-blue-600">
                                <i class="fas fa-microphone mr-2"></i>Voice Input
                            </button>
                            <button type="button" onclick="showQuickTips()"
                                class="bg-gradient-to-r from-blue-500 to-purple-600 hover:from-blue-600 hover:to-purple-700 
                                       dark:from-blue-600 dark:to-purple-700 dark:hover:from-blue-700 dark:hover:to-purple-800
                                       text-white px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 
                                       shadow-lg hover:shadow-xl transform hover:-translate-y-1
                                       border border-blue-400 dark:border-blue-500 hover:border-blue-500 dark:hover:border-blue-600">
                                <i class="fas fa-lightbulb mr-2"></i>Quick Tips
                            </button>
                            <a href="{{ route('doctor.medical-reports.index') }}"
                                class="bg-gradient-to-r from-blue-500 to-purple-600 hover:from-blue-600 hover:to-purple-700 
                                  dark:from-blue-600 dark:to-purple-700 dark:hover:from-blue-700 dark:hover:to-purple-800
                                  text-white px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 
                                  shadow-lg hover:shadow-xl transform hover:-translate-y-1
                                  border border-blue-400 dark:border-blue-500 hover:border-blue-500 dark:hover:border-blue-600">
                                <i class="fas fa-arrow-left mr-2"></i>Back to Reports
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Progress Tracker -->
            <div class="progress-tracker mb-6">
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-4 border border-gray-200 dark:border-gray-700">
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                            <i class="fas fa-tasks mr-2 text-blue-600"></i>Form Progress
                        </h3>
                        <div class="flex items-center space-x-2">
                            <span id="progress-percentage"
                                class="text-sm font-medium text-gray-600 dark:text-gray-400">0%</span>
                        </div>
                    </div>
                    <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                        <div id="progress-bar"
                            class="bg-gradient-to-r from-blue-600 to-purple-600 h-2 rounded-full transition-all duration-300"
                            style="width: 0%"></div>
                    </div>
                    <div class="flex justify-between mt-3 text-xs text-gray-500 dark:text-gray-400">
                        <span class="flex items-center">
                            <span id="patient-status" class="field-status field-empty"></span>
                            Patient Info
                        </span>
                        <span class="flex items-center">
                            <span id="vitals-status" class="field-status field-empty"></span>
                            Vital Signs
                        </span>
                        <span class="flex items-center">
                            <span id="assessment-status" class="field-status field-empty"></span>
                            Assessment
                        </span>
                        <span class="flex items-center">
                            <span id="diagnosis-status" class="field-status field-empty"></span>
                            Diagnosis
                        </span>
                    </div>
                </div>
            </div>

            <!-- Quick Action Panel -->
            <div class="mb-6">
                <div
                    class="bg-gradient-to-r from-gray-50 to-blue-50 dark:from-gray-800 dark:to-gray-700 rounded-xl p-4 border border-gray-200 dark:border-gray-600">
                    <div class="flex items-center justify-between flex-wrap gap-3">
                        <div class="flex items-center space-x-2">
                            <i class="fas fa-bolt text-yellow-500"></i>
                            <span class="font-medium text-gray-900 dark:text-white">Quick Actions:</span>
                        </div>
                        <div class="flex flex-wrap gap-2">
                            <button type="button" onclick="applyPreset('routine-checkup')"
                                class="bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 dark:from-green-600 dark:to-emerald-700 dark:hover:from-green-700 dark:hover:to-emerald-800 text-white px-3 py-1.5 rounded-lg text-xs font-medium transition-all duration-200 border border-green-400 dark:border-green-500 hover:border-green-500 dark:hover:border-green-600 shadow-md hover:shadow-lg">
                                <i class="fas fa-stethoscope mr-1"></i>Routine Checkup
                            </button>
                            <button type="button" onclick="applyPreset('follow-up')"
                                class="bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 dark:from-green-600 dark:to-emerald-700 dark:hover:from-green-700 dark:hover:to-emerald-800 text-white px-3 py-1.5 rounded-lg text-xs font-medium transition-all duration-200 border border-green-400 dark:border-green-500 hover:border-green-500 dark:hover:border-green-600 shadow-md hover:shadow-lg">
                                <i class="fas fa-calendar-check mr-1"></i>Follow-up
                            </button>
                            <button type="button" onclick="applyPreset('emergency')"
                                class="bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 dark:from-green-600 dark:to-emerald-700 dark:hover:from-green-700 dark:hover:to-emerald-800 text-white px-3 py-1.5 rounded-lg text-xs font-medium transition-all duration-200 border border-green-400 dark:border-green-500 hover:border-green-500 dark:hover:border-green-600 shadow-md hover:shadow-lg">
                                <i class="fas fa-ambulance mr-1"></i>Emergency
                            </button>
                            <button type="button" onclick="fillNormalVitals()"
                                class="bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 dark:from-green-600 dark:to-emerald-700 dark:hover:from-green-700 dark:hover:to-emerald-800 text-white px-3 py-1.5 rounded-lg text-xs font-medium transition-all duration-200 border border-green-400 dark:border-green-500 hover:border-green-500 dark:hover:border-green-600 shadow-md hover:shadow-lg">
                                <i class="fas fa-heartbeat mr-1"></i>Normal Vitals
                            </button>
                            <button type="button" onclick="toggleAllSections()"
                                class="bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 dark:from-green-600 dark:to-emerald-700 dark:hover:from-green-700 dark:hover:to-emerald-800 text-white px-3 py-1.5 rounded-lg text-xs font-medium transition-all duration-200 border border-green-400 dark:border-green-500 hover:border-green-500 dark:hover:border-green-600 shadow-md hover:shadow-lg">
                                <i class="fas fa-expand-alt mr-1"></i>Expand All
                            </button>
                            <button type="button" onclick="clearForm()"
                                class="bg-gradient-to-r from-red-500 to-pink-600 hover:from-red-600 hover:to-pink-700 dark:from-red-600 dark:to-pink-700 dark:hover:from-red-700 dark:hover:to-pink-800 text-white px-3 py-1.5 rounded-lg text-xs font-medium transition-all duration-200 border border-red-400 dark:border-red-500 hover:border-red-500 dark:hover:border-red-600 shadow-md hover:shadow-lg">
                                <i class="fas fa-trash mr-1"></i>Clear All
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Submission Error Logging Section (JavaScript controlled) -->
            <div id="form-errors-section" class="hidden mb-8">
                <div class="bg-red-50 dark:bg-red-900/20 border-l-4 border-red-500 p-6 rounded-r-xl shadow-lg">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <i class="fas fa-exclamation-triangle text-red-500 text-xl"></i>
                        </div>
                        <div class="ml-3 flex-1">
                            <h3 class="text-lg font-medium text-red-800 dark:text-red-200">
                                Form Submission Errors
                            </h3>
                            <div id="form-errors-list" class="mt-2 text-sm text-red-700 dark:text-red-300">
                                <!-- Error messages will be populated here -->
                            </div>
                            <div class="mt-4">
                                <button type="button" onclick="hideFormErrors()"
                                    class="bg-red-100 hover:bg-red-200 dark:bg-red-800 dark:hover:bg-red-700 text-red-800 dark:text-red-200 px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200">
                                    <i class="fas fa-times mr-2"></i>Dismiss
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Enhanced Form -->
            <form method="POST" action="{{ route('doctor.medical-reports.update', $medicalReport->id) }}"
                class="space-y-8" id="medical-report-form">
                @csrf
                @method('PUT')

                <!-- Patient Information (Read-only for edit) -->
                <div
                    class="bg-white dark:bg-gray-800 shadow-xl rounded-2xl border border-gray-100 dark:border-gray-700 form-section-transition">
                    <div
                        class="px-8 py-6 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-blue-50 to-purple-50 dark:from-gray-700 dark:to-gray-600 rounded-t-2xl">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <div class="bg-blue-500 p-2 rounded-lg">
                                    <i class="fas fa-user-md text-white"></i>
                                </div>
                                <h3 class="text-xl font-semibold text-gray-900 dark:text-white">Patient Information</h3>
                                <button type="button" onclick="toggleSection('patient-info')"
                                    class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                                    <i id="patient-info-toggle" class="fas fa-chevron-up"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div id="patient-info-content" class="p-8 space-y-6 section-collapse" style="max-height: auto;">
                        <div
                            class="bg-gradient-to-r from-green-50 to-emerald-50 dark:from-green-900/20 dark:to-emerald-900/20 border-2 border-green-200 dark:border-green-700 rounded-xl p-6">
                            <div class="flex items-center space-x-3 mb-4">
                                <div class="bg-green-500 p-2 rounded-lg">
                                    <i class="fas fa-user-check text-white"></i>
                                </div>
                                <h4 class="text-lg font-semibold text-green-800 dark:text-green-300">Selected Patient</h4>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                                <div class="space-y-1">
                                    <div class="font-medium text-gray-600 dark:text-gray-400">Name:</div>
                                    <div class="text-gray-900 dark:text-white font-semibold">
                                        {{ $medicalReport->patient->name }}</div>
                                </div>
                                <div class="space-y-1">
                                    <div class="font-medium text-gray-600 dark:text-gray-400">Email:</div>
                                    <div class="text-gray-900 dark:text-white">{{ $medicalReport->patient->email }}</div>
                                </div>
                                <div class="space-y-1">
                                    <div class="font-medium text-gray-600 dark:text-gray-400">Patient ID:</div>
                                    <div class="text-gray-900 dark:text-white">#{{ $medicalReport->patient->id }}</div>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="patient_id" value="{{ $medicalReport->patient_id }}">

                        <!-- Title Field -->
                        <div class="mb-6">
                            <label for="title"
                                class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                <i class="fas fa-heading mr-2 text-green-500"></i>Report Title <span
                                    class="text-red-500">*</span>
                            </label>
                            <input type="text" name="title" id="title"
                                value="{{ old('title', $medicalReport->title) }}" required
                                placeholder="e.g., Annual Check-up, Follow-up Visit, Emergency Consultation"
                                oninput="updateFormProgress()"
                                class="w-full border-2 @error('title') border-red-500 dark:border-red-400 @else border-gray-300 dark:border-gray-600 @enderror rounded-xl shadow-sm focus:ring-2 focus:ring-purple-500 focus:border-purple-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white py-3 px-4 transition-all duration-200">
                            @error('title')
                                <div class="mt-2 flex items-center text-red-600 dark:text-red-400 text-sm">
                                    <i class="fas fa-exclamation-circle mr-2"></i>
                                    <span>{{ $message }}</span>
                                </div>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                            <div>
                                <label for="consultation_date"
                                    class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                    <i class="fas fa-calendar mr-2 text-purple-500"></i>Consultation Date
                                </label>
                                <input type="date" name="consultation_date" id="consultation_date"
                                    value="{{ old('consultation_date', $medicalReport->consultation_date->format('Y-m-d')) }}"
                                    required onchange="updateFormProgress()"
                                    class="w-full border-2 @error('consultation_date') border-red-500 dark:border-red-400 @else border-gray-300 dark:border-gray-600 @enderror rounded-xl shadow-sm focus:ring-2 focus:ring-purple-500 focus:border-purple-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white py-3 px-4 transition-all duration-200">
                                @error('consultation_date')
                                    <div class="mt-2 flex items-center text-red-600 dark:text-red-400 text-sm">
                                        <i class="fas fa-exclamation-circle mr-2"></i>
                                        <span>{{ $message }}</span>
                                    </div>
                                @enderror
                            </div>

                            <div>
                                <label for="report_type"
                                    class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                    <i class="fas fa-file-alt mr-2 text-indigo-500"></i>Report Type
                                </label>
                                <select name="report_type" id="report_type" required onchange="updateFormProgress()"
                                    class="w-full border-2 @error('report_type') border-red-500 dark:border-red-400 @else border-gray-300 dark:border-gray-600 @enderror rounded-xl shadow-sm focus:ring-2 focus:ring-purple-500 focus:border-purple-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white py-3 px-4 transition-all duration-200">
                                    <option value="">Select report type</option>
                                    <option value="Consultation"
                                        {{ old('report_type', $medicalReport->report_type) == 'Consultation' ? 'selected' : '' }}>
                                        Initial Consultation</option>
                                    <option value="Follow-up"
                                        {{ old('report_type', $medicalReport->report_type) == 'Follow-up' ? 'selected' : '' }}>
                                        Follow-up Visit</option>
                                    <option value="Diagnosis"
                                        {{ old('report_type', $medicalReport->report_type) == 'Diagnosis' ? 'selected' : '' }}>
                                        Diagnosis Report</option>
                                    <option value="Treatment"
                                        {{ old('report_type', $medicalReport->report_type) == 'Treatment' ? 'selected' : '' }}>
                                        Treatment Report</option>
                                    <option value="Prescription"
                                        {{ old('report_type', $medicalReport->report_type) == 'Prescription' ? 'selected' : '' }}>
                                        Prescription Report</option>
                                    <option value="Emergency"
                                        {{ old('report_type', $medicalReport->report_type) == 'Emergency' ? 'selected' : '' }}>
                                        Emergency Visit</option>
                                    <option value="Other"
                                        {{ old('report_type', $medicalReport->report_type) == 'Other' ? 'selected' : '' }}>
                                        Other</option>
                                </select>
                                @error('report_type')
                                    <div class="mt-2 flex items-center text-red-600 dark:text-red-400 text-sm">
                                        <i class="fas fa-exclamation-circle mr-2"></i>
                                        <span>{{ $message }}</span>
                                    </div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Enhanced Vital Signs -->
                <div
                    class="bg-white dark:bg-gray-800 shadow-xl rounded-2xl border border-gray-100 dark:border-gray-700 form-section-transition">
                    <div
                        class="px-8 py-6 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-green-50 to-emerald-50 dark:from-gray-700 dark:to-gray-600 rounded-t-2xl">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <div class="bg-green-500 p-2 rounded-lg">
                                    <i class="fas fa-heartbeat text-white"></i>
                                </div>
                                <h3 class="text-xl font-semibold text-gray-900 dark:text-white">Vital Signs</h3>
                                <button type="button" onclick="toggleSection('vital-signs')"
                                    class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                                    <i id="vital-signs-toggle" class="fas fa-chevron-up"></i>
                                </button>
                            </div>
                            <div class="flex items-center space-x-2">
                                <button type="button" onclick="fillNormalVitals()"
                                    class="bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 dark:from-green-600 dark:to-emerald-700 dark:hover:from-green-700 dark:hover:to-emerald-800 text-white px-3 py-1.5 rounded-lg text-xs font-medium transition-all duration-200 border border-green-400 dark:border-green-500 hover:border-green-500 dark:hover:border-green-600 shadow-md hover:shadow-lg">
                                    <i class="fas fa-magic mr-1"></i>Normal
                                </button>
                                <button type="button" onclick="fillPediatricVitals()"
                                    class="bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 dark:from-green-600 dark:to-emerald-700 dark:hover:from-green-700 dark:hover:to-emerald-800 text-white px-3 py-1.5 rounded-lg text-xs font-medium transition-all duration-200 border border-green-400 dark:border-green-500 hover:border-green-500 dark:hover:border-green-600 shadow-md hover:shadow-lg">
                                    <i class="fas fa-child mr-1"></i>Pediatric
                                </button>
                                <button type="button" onclick="fillGeriatricVitals()"
                                    class="bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 dark:from-green-600 dark:to-emerald-700 dark:hover:from-green-700 dark:hover:to-emerald-800 text-white px-3 py-1.5 rounded-lg text-xs font-medium transition-all duration-200 border border-green-400 dark:border-green-500 hover:border-green-500 dark:hover:border-green-600 shadow-md hover:shadow-lg">
                                    <i class="fas fa-wheelchair mr-1"></i>Geriatric
                                </button>
                            </div>
                        </div>
                    </div>
                    <div id="vital-signs-content" class="p-8 section-collapse" style="max-height: auto;">
                        <!-- Vital Signs Status Indicator -->
                        <div class="mb-6 bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-3">
                                    <i class="fas fa-chart-line text-green-600 dark:text-green-400"></i>
                                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Vitals
                                        Completion:</span>
                                    <span id="vitals-completion"
                                        class="text-sm font-semibold text-green-600 dark:text-green-400">0/7
                                        Complete</span>
                                </div>
                                <div class="w-32 bg-gray-200 dark:bg-gray-600 rounded-full h-2">
                                    <div id="vitals-progress"
                                        class="bg-green-500 h-2 rounded-full transition-all duration-300"
                                        style="width: 0%"></div>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6" id="vital-signs">
                            <div
                                class="bg-gradient-to-br from-red-50 to-pink-50 dark:from-red-900/20 dark:to-pink-900/20 p-4 rounded-xl border border-red-200 dark:border-red-700 relative">
                                <label class="block text-sm font-semibold text-red-700 dark:text-red-300 mb-2">
                                    <i class="fas fa-tint mr-2"></i>Blood Pressure
                                </label>
                                <input type="text" name="vital_signs[blood_pressure]" placeholder="120/80 mmHg"
                                    value="{{ old('vital_signs.blood_pressure', $medicalReport->vital_signs['blood_pressure'] ?? '') }}"
                                    oninput="updateFormProgress(); updateVitalsCompletion(); calculateBMI();"
                                    class="vital-input w-full border-2 @error('vital_signs.blood_pressure') border-red-500 dark:border-red-400 @else border-red-300 dark:border-red-600 @enderror rounded-lg shadow-sm focus:ring-2 focus:ring-red-500 focus:border-red-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white py-3 px-4">
                                @error('vital_signs.blood_pressure')
                                    <div class="mt-1 text-red-600 dark:text-red-400 text-xs">{{ $message }}</div>
                                @enderror
                            </div>

                            <div
                                class="bg-gradient-to-br from-orange-50 to-yellow-50 dark:from-orange-900/20 dark:to-yellow-900/20 p-4 rounded-xl border border-orange-200 dark:border-orange-700 relative">
                                <label class="block text-sm font-semibold text-orange-700 dark:text-orange-300 mb-2">
                                    <i class="fas fa-thermometer-half mr-2"></i>Temperature
                                </label>
                                <input type="text" name="vital_signs[temperature]" placeholder="98.6°F"
                                    value="{{ old('vital_signs.temperature', $medicalReport->vital_signs['temperature'] ?? '') }}"
                                    oninput="updateFormProgress(); updateVitalsCompletion();"
                                    class="vital-input w-full border-2 @error('vital_signs.temperature') border-red-500 dark:border-red-400 @else border-orange-300 dark:border-orange-600 @enderror rounded-lg shadow-sm focus:ring-2 focus:ring-orange-500 focus:border-orange-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white py-3 px-4">
                                @error('vital_signs.temperature')
                                    <div class="mt-1 text-red-600 dark:text-red-400 text-xs">{{ $message }}</div>
                                @enderror
                            </div>

                            <div
                                class="bg-gradient-to-br from-blue-50 to-cyan-50 dark:from-blue-900/20 dark:to-cyan-900/20 p-4 rounded-xl border border-blue-200 dark:border-blue-700 relative">
                                <label class="block text-sm font-semibold text-blue-700 dark:text-blue-300 mb-2">
                                    <i class="fas fa-heartbeat mr-2"></i>Heart Rate
                                </label>
                                <input type="text" name="vital_signs[heart_rate]" placeholder="70 bpm"
                                    value="{{ old('vital_signs.heart_rate', $medicalReport->vital_signs['heart_rate'] ?? '') }}"
                                    oninput="updateFormProgress(); updateVitalsCompletion();"
                                    class="vital-input w-full border-2 @error('vital_signs.heart_rate') border-red-500 dark:border-red-400 @else border-blue-300 dark:border-blue-600 @enderror rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white py-3 px-4">
                                @error('vital_signs.heart_rate')
                                    <div class="mt-1 text-red-600 dark:text-red-400 text-xs">{{ $message }}</div>
                                @enderror
                            </div>

                            <div
                                class="bg-gradient-to-br from-purple-50 to-indigo-50 dark:from-purple-900/20 dark:to-indigo-900/20 p-4 rounded-xl border border-purple-200 dark:border-purple-700 relative">
                                <label class="block text-sm font-semibold text-purple-700 dark:text-purple-300 mb-2">
                                    <i class="fas fa-weight mr-2"></i>Weight
                                </label>
                                <input type="text" name="vital_signs[weight]" placeholder="70 kg"
                                    value="{{ old('vital_signs.weight', $medicalReport->vital_signs['weight'] ?? '') }}"
                                    oninput="updateFormProgress(); updateVitalsCompletion(); calculateBMI();"
                                    class="vital-input w-full border-2 @error('vital_signs.weight') border-red-500 dark:border-red-400 @else border-purple-300 dark:border-purple-600 @enderror rounded-lg shadow-sm focus:ring-2 focus:ring-purple-500 focus:border-purple-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white py-3 px-4">
                                @error('vital_signs.weight')
                                    <div class="mt-1 text-red-600 dark:text-red-400 text-xs">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div
                                class="bg-gradient-to-br from-teal-50 to-cyan-50 dark:from-teal-900/20 dark:to-cyan-900/20 p-4 rounded-xl border border-teal-200 dark:border-teal-700">
                                <label class="block text-sm font-semibold text-teal-700 dark:text-teal-300 mb-2">
                                    <i class="fas fa-lungs mr-2"></i>Respiratory Rate
                                </label>
                                <input type="text" name="vital_signs[respiratory_rate]" placeholder="16 breaths/min"
                                    value="{{ old('vital_signs.respiratory_rate', $medicalReport->vital_signs['respiratory_rate'] ?? '') }}"
                                    oninput="updateFormProgress(); updateVitalsCompletion();"
                                    class="w-full border-2 @error('vital_signs.respiratory_rate') border-red-500 dark:border-red-400 @else border-teal-300 dark:border-teal-600 @enderror rounded-lg shadow-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white py-3 px-4">
                                @error('vital_signs.respiratory_rate')
                                    <div class="mt-2 flex items-center text-red-600 dark:text-red-400 text-sm">
                                        <i class="fas fa-exclamation-circle mr-2"></i>
                                        <span>{{ $message }}</span>
                                    </div>
                                @enderror
                            </div>
                            <div
                                class="bg-gradient-to-br from-sky-50 to-blue-50 dark:from-sky-900/20 dark:to-blue-900/20 p-4 rounded-xl border border-sky-200 dark:border-sky-700">
                                <label class="block text-sm font-semibold text-sky-700 dark:text-sky-300 mb-2">
                                    <i class="fas fa-percentage mr-2"></i>Oxygen Saturation
                                </label>
                                <input type="text" name="vital_signs[oxygen_saturation]" placeholder="98%"
                                    value="{{ old('vital_signs.oxygen_saturation', $medicalReport->vital_signs['oxygen_saturation'] ?? '') }}"
                                    oninput="updateFormProgress(); updateVitalsCompletion();"
                                    class="w-full border-2 @error('vital_signs.oxygen_saturation') border-red-500 dark:border-red-400 @else border-sky-300 dark:border-sky-600 @enderror rounded-lg shadow-sm focus:ring-2 focus:ring-sky-500 focus:border-sky-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white py-3 px-4">
                                @error('vital_signs.oxygen_saturation')
                                    <div class="mt-2 flex items-center text-red-600 dark:text-red-400 text-sm">
                                        <i class="fas fa-exclamation-circle mr-2"></i>
                                        <span>{{ $message }}</span>
                                    </div>
                                @enderror
                            </div>
                            <div
                                class="bg-gradient-to-br from-emerald-50 to-green-50 dark:from-emerald-900/20 dark:to-green-900/20 p-4 rounded-xl border border-emerald-200 dark:border-emerald-700">
                                <label class="block text-sm font-semibold text-emerald-700 dark:text-emerald-300 mb-2">
                                    <i class="fas fa-ruler-vertical mr-2"></i>Height
                                </label>
                                <input type="text" name="vital_signs[height]" placeholder="170 cm"
                                    value="{{ old('vital_signs.height', $medicalReport->vital_signs['height'] ?? '') }}"
                                    oninput="updateFormProgress(); updateVitalsCompletion(); calculateBMI();"
                                    class="w-full border-2 @error('vital_signs.height') border-red-500 dark:border-red-400 @else border-emerald-300 dark:border-emerald-600 @enderror rounded-lg shadow-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white py-3 px-4">
                                @error('vital_signs.height')
                                    <div class="mt-2 flex items-center text-red-600 dark:text-red-400 text-sm">
                                        <i class="fas fa-exclamation-circle mr-2"></i>
                                        <span>{{ $message }}</span>
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <!-- BMI Calculator Section -->
                        <div id="bmi-section"
                            class="mt-6 bg-gradient-to-r from-indigo-50 to-purple-50 dark:from-indigo-900/20 dark:to-purple-900/20 p-4 rounded-xl border border-indigo-200 dark:border-indigo-700 hidden">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-3">
                                    <i class="fas fa-calculator text-indigo-600 dark:text-indigo-400"></i>
                                    <span class="font-medium text-gray-900 dark:text-white">BMI Calculator</span>
                                </div>
                                <div id="bmi-result" class="text-lg font-semibold text-indigo-600 dark:text-indigo-400">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Enhanced Clinical Assessment -->
                <div
                    class="bg-white dark:bg-gray-800 shadow-xl rounded-2xl border border-gray-100 dark:border-gray-700 form-section-transition">
                    <div
                        class="px-8 py-6 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-indigo-50 to-blue-50 dark:from-gray-700 dark:to-gray-600 rounded-t-2xl">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <div class="bg-indigo-500 p-2 rounded-lg">
                                    <i class="fas fa-stethoscope text-white"></i>
                                </div>
                                <h3 class="text-xl font-semibold text-gray-900 dark:text-white">Clinical Assessment</h3>
                                <button type="button" onclick="toggleSection('clinical-assessment')"
                                    class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                                    <i id="clinical-assessment-toggle" class="fas fa-chevron-up"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div id="clinical-assessment-content" class="p-8 space-y-8 section-collapse"
                        style="max-height: auto;">
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                            <div>
                                <label for="chief_complaint"
                                    class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                    <i class="fas fa-comment-medical mr-2 text-red-500"></i>Chief Complaint
                                </label>
                                <textarea name="chief_complaint" id="chief_complaint" rows="4"
                                    placeholder="Patient's primary concern or reason for visit..." oninput="updateFormProgress()"
                                    class="w-full border-2 @error('chief_complaint') border-red-500 dark:border-red-400 @else border-gray-300 dark:border-gray-600 @enderror rounded-xl shadow-sm focus:ring-2 focus:ring-purple-500 focus:border-purple-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white p-4 resize-none transition-all duration-200">{{ old('chief_complaint', $medicalReport->chief_complaint) }}</textarea>
                                @error('chief_complaint')
                                    <div class="mt-2 flex items-center text-red-600 dark:text-red-400 text-sm">
                                        <i class="fas fa-exclamation-circle mr-2"></i>
                                        <span>{{ $message }}</span>
                                    </div>
                                @enderror
                            </div>

                            <div>
                                <label for="history_of_present_illness"
                                    class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                    <i class="fas fa-history mr-2 text-blue-500"></i>History of Present Illness
                                </label>
                                <textarea name="history_of_present_illness" id="history_of_present_illness" rows="4"
                                    placeholder="Detailed description of the current condition..." oninput="updateFormProgress()"
                                    class="w-full border-2 @error('history_of_present_illness') border-red-500 dark:border-red-400 @else border-gray-300 dark:border-gray-600 @enderror rounded-xl shadow-sm focus:ring-2 focus:ring-purple-500 focus:border-purple-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white p-4 resize-none transition-all duration-200">{{ old('history_of_present_illness', $medicalReport->history_of_present_illness) }}</textarea>
                                @error('history_of_present_illness')
                                    <div class="mt-2 flex items-center text-red-600 dark:text-red-400 text-sm">
                                        <i class="fas fa-exclamation-circle mr-2"></i>
                                        <span>{{ $message }}</span>
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <div>
                            <label for="physical_examination"
                                class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                <i class="fas fa-search mr-2 text-green-500"></i>Physical Examination
                            </label>
                            <textarea name="physical_examination" id="physical_examination" rows="5"
                                placeholder="Detailed findings from physical examination..." oninput="updateFormProgress()"
                                class="w-full border-2 @error('physical_examination') border-red-500 dark:border-red-400 @else border-gray-300 dark:border-gray-600 @enderror rounded-xl shadow-sm focus:ring-2 focus:ring-purple-500 focus:border-purple-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white p-4 resize-none transition-all duration-200">{{ old('physical_examination', $medicalReport->physical_examination) }}</textarea>
                            @error('physical_examination')
                                <div class="mt-2 flex items-center text-red-600 dark:text-red-400 text-sm">
                                    <i class="fas fa-exclamation-circle mr-2"></i>
                                    <span>{{ $message }}</span>
                                </div>
                            @enderror
                        </div>

                        <div class="bg-gray-50 dark:bg-gray-700 p-6 rounded-xl">
                            <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                                <i class="fas fa-clipboard-list mr-2 text-purple-500"></i>Systems Review (Optional)
                            </h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Cardiovascular</label>
                                    <input type="text" name="systems_review[cardiovascular]"
                                        placeholder="Normal, abnormal findings..."
                                        value="{{ old('systems_review.cardiovascular', $medicalReport->systems_review['cardiovascular'] ?? '') }}"
                                        class="w-full border @error('systems_review.cardiovascular') border-red-500 dark:border-red-400 @else border-gray-300 dark:border-gray-600 @enderror rounded-lg px-3 py-2 bg-white dark:bg-gray-600 text-gray-900 dark:text-white">
                                    @error('systems_review.cardiovascular')
                                        <div class="mt-1 text-red-600 dark:text-red-400 text-xs">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div>
                                    <label
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Respiratory</label>
                                    <input type="text" name="systems_review[respiratory]"
                                        placeholder="Normal, abnormal findings..."
                                        value="{{ old('systems_review.respiratory', $medicalReport->systems_review['respiratory'] ?? '') }}"
                                        class="w-full border @error('systems_review.respiratory') border-red-500 dark:border-red-400 @else border-gray-300 dark:border-gray-600 @enderror rounded-lg px-3 py-2 bg-white dark:bg-gray-600 text-gray-900 dark:text-white">
                                    @error('systems_review.respiratory')
                                        <div class="mt-1 text-red-600 dark:text-red-400 text-xs">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div>
                                    <label
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Gastrointestinal</label>
                                    <input type="text" name="systems_review[gastrointestinal]"
                                        placeholder="Normal, abnormal findings..."
                                        value="{{ old('systems_review.gastrointestinal', $medicalReport->systems_review['gastrointestinal'] ?? '') }}"
                                        class="w-full border @error('systems_review.gastrointestinal') border-red-500 dark:border-red-400 @else border-gray-300 dark:border-gray-600 @enderror rounded-lg px-3 py-2 bg-white dark:bg-gray-600 text-gray-900 dark:text-white">
                                    @error('systems_review.gastrointestinal')
                                        <div class="mt-1 text-red-600 dark:text-red-400 text-xs">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div>
                                    <label
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Neurological</label>
                                    <input type="text" name="systems_review[neurological]"
                                        placeholder="Normal, abnormal findings..."
                                        value="{{ old('systems_review.neurological', $medicalReport->systems_review['neurological'] ?? '') }}"
                                        class="w-full border @error('systems_review.neurological') border-red-500 dark:border-red-400 @else border-gray-300 dark:border-gray-600 @enderror rounded-lg px-3 py-2 bg-white dark:bg-gray-600 text-gray-900 dark:text-white">
                                    @error('systems_review.neurological')
                                        <div class="mt-1 text-red-600 dark:text-red-400 text-xs">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Enhanced Diagnosis and Treatment -->
                <div
                    class="bg-white dark:bg-gray-800 shadow-xl rounded-2xl border border-gray-100 dark:border-gray-700 form-section-transition">
                    <div
                        class="px-8 py-6 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-purple-50 to-pink-50 dark:from-gray-700 dark:to-gray-600 rounded-t-2xl">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <div class="bg-purple-500 p-2 rounded-lg">
                                    <i class="fas fa-diagnoses text-white"></i>
                                </div>
                                <h3 class="text-xl font-semibold text-gray-900 dark:text-white">Diagnosis & Treatment</h3>
                                <button type="button" onclick="toggleSection('diagnosis-treatment')"
                                    class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                                    <i id="diagnosis-treatment-toggle" class="fas fa-chevron-up"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div id="diagnosis-treatment-content" class="p-8 space-y-8 section-collapse" style="max-height: auto;">
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                            <div>
                                <label for="assessment_diagnosis"
                                    class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                    <i class="fas fa-microscope mr-2 text-purple-500"></i>Assessment & Diagnosis
                                </label>
                                <textarea name="assessment_diagnosis" id="assessment_diagnosis" rows="5"
                                    placeholder="Clinical diagnosis and assessment..." oninput="updateFormProgress()"
                                    class="w-full border-2 @error('assessment_diagnosis') border-red-500 dark:border-red-400 @else border-gray-300 dark:border-gray-600 @enderror rounded-xl shadow-sm focus:ring-2 focus:ring-purple-500 focus:border-purple-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white p-4 resize-none transition-all duration-200">{{ old('assessment_diagnosis', $medicalReport->assessment_diagnosis) }}</textarea>
                                @error('assessment_diagnosis')
                                    <div class="mt-2 flex items-center text-red-600 dark:text-red-400 text-sm">
                                        <i class="fas fa-exclamation-circle mr-2"></i>
                                        <span>{{ $message }}</span>
                                    </div>
                                @enderror
                            </div>

                            <div>
                                <label for="treatment_plan"
                                    class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                    <i class="fas fa-clipboard-check mr-2 text-green-500"></i>Treatment Plan
                                </label>
                                <textarea name="treatment_plan" id="treatment_plan" rows="5"
                                    placeholder="Recommended treatment and care plan..." oninput="updateFormProgress()"
                                    class="w-full border-2 @error('treatment_plan') border-red-500 dark:border-red-400 @else border-gray-300 dark:border-gray-600 @enderror rounded-xl shadow-sm focus:ring-2 focus:ring-purple-500 focus:border-purple-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white p-4 resize-none transition-all duration-200">{{ old('treatment_plan', $medicalReport->treatment_plan) }}</textarea>
                                @error('treatment_plan')
                                    <div class="mt-2 flex items-center text-red-600 dark:text-red-400 text-sm">
                                        <i class="fas fa-exclamation-circle mr-2"></i>
                                        <span>{{ $message }}</span>
                                    </div>
                                @enderror
                            </div>
                        </div>



                        <!-- Enhanced Prescription Section -->
                        <div
                            class="bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 p-6 rounded-xl border border-blue-200 dark:border-blue-700">
                            <div class="flex items-center justify-between mb-4">
                                <h4 class="text-lg font-semibold text-blue-800 dark:text-blue-300">
                                    <i class="fas fa-prescription-bottle-alt mr-2"></i>Prescription Management
                                </h4>
                                <button type="button" onclick="addPrescriptionMedication()"
                                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200">
                                    <i class="fas fa-plus mr-2"></i>Add Medication
                                </button>
                            </div>

                            <!-- Prescription Medications List -->
                            <div id="prescription-medications" class="space-y-4">
                                {{-- Note: For now, we'll work with simple prescription data structure --}}
                                {{-- In a future update, this should be integrated with the proper Prescription/PrescriptionMedication relationships --}}
                            </div>

                            <!-- Add first medication if none exist -->
                            <div id="no-medications-message" class="text-center py-8 text-gray-500 dark:text-gray-400">
                                <i class="fas fa-pills text-4xl mb-4"></i>
                                <p>No medications added yet. Click "Add Medication" to start prescribing.</p>
                            </div>

                            <!-- Legacy textarea for medical report display -->
                            <input type="hidden" name="medications_prescribed" id="medications_prescribed_hidden"
                                value="{{ old('medications_prescribed', $medicalReport->medications_prescribed) }}">

                            <div class="mt-4 text-sm text-blue-600 dark:text-blue-400">
                                <i class="fas fa-info-circle mr-1"></i>
                                Medications will be saved separately for patient medication management and included in the
                                medical report.
                            </div>
                        </div>

                        <!-- Enhanced Lab Tests Section -->
                        <div
                            class="bg-gradient-to-r from-green-50 to-emerald-50 dark:from-green-900/20 dark:to-emerald-900/20 p-6 rounded-xl border border-green-200 dark:border-green-700">
                            <div class="flex items-center justify-between mb-4">
                                <h4 class="text-lg font-semibold text-green-800 dark:text-green-300">
                                    <i class="fas fa-flask mr-2"></i>Lab Test Requests
                                </h4>
                                <button type="button" onclick="addLabTestRequest()"
                                    class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200">
                                    <i class="fas fa-plus mr-2"></i>Add Lab Test
                                </button>
                            </div>

                            <!-- Lab Test Requests List -->
                            <div id="lab-test-requests" class="space-y-4">
                                {{-- Note: For now, we'll work with simple lab test data structure --}}
                                {{-- In a future update, this should be integrated with the proper LabTestRequest relationships --}}
                            </div>

                            <!-- Add first lab test if none exist -->
                            <div id="no-lab-tests-message" class="text-center py-8 text-gray-500 dark:text-gray-400">
                                <i class="fas fa-vials text-4xl mb-4"></i>
                                <p>No lab tests ordered yet. Click "Add Lab Test" to request tests.</p>
                            </div>

                            <!-- Legacy fields for medical report display -->
                            <input type="hidden" name="lab_tests_ordered" id="lab_tests_ordered_hidden"
                                value="{{ old('lab_tests_ordered', $medicalReport->lab_tests_ordered) }}">
                            <input type="hidden" name="imaging_studies" id="imaging_studies_hidden"
                                value="{{ old('imaging_studies', $medicalReport->imaging_studies) }}">

                            <div class="mt-4 text-sm text-green-600 dark:text-green-400">
                                <i class="fas fa-info-circle mr-1"></i>
                                Lab tests will be saved separately for lab management and included in the medical report.
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Enhanced Follow-up and Notes -->
                <div
                    class="bg-white dark:bg-gray-800 shadow-xl rounded-2xl border border-gray-100 dark:border-gray-700 form-section-transition">
                    <div
                        class="px-8 py-6 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-teal-50 to-green-50 dark:from-gray-700 dark:to-gray-600 rounded-t-2xl">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <div class="bg-teal-500 p-2 rounded-lg">
                                    <i class="fas fa-calendar-check text-white"></i>
                                </div>
                                <h3 class="text-xl font-semibold text-gray-900 dark:text-white">Follow-up & Additional
                                    Notes</h3>
                                <button type="button" onclick="toggleSection('follow-up-notes')"
                                    class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                                    <i id="follow-up-notes-toggle" class="fas fa-chevron-up"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div id="follow-up-notes-content" class="p-8 space-y-8 section-collapse" style="max-height: auto;">
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                            <div>
                                <label for="follow_up_instructions"
                                    class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                    <i class="fas fa-calendar-alt mr-2 text-teal-500"></i>Follow-up Instructions
                                </label>
                                <textarea name="follow_up_instructions" id="follow_up_instructions" rows="4"
                                    placeholder="Instructions for next visit, timeline, etc..."
                                    class="w-full border-2 @error('follow_up_instructions') border-red-500 dark:border-red-400 @else border-gray-300 dark:border-gray-600 @enderror rounded-xl shadow-sm focus:ring-2 focus:ring-purple-500 focus:border-purple-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white p-4 resize-none transition-all duration-200">{{ old('follow_up_instructions', $medicalReport->follow_up_instructions) }}</textarea>
                                @error('follow_up_instructions')
                                    <div class="mt-2 flex items-center text-red-600 dark:text-red-400 text-sm">
                                        <i class="fas fa-exclamation-circle mr-2"></i>
                                        <span>{{ $message }}</span>
                                    </div>
                                @enderror
                            </div>

                            <div>
                                <label for="additional_notes"
                                    class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                    <i class="fas fa-sticky-note mr-2 text-yellow-500"></i>Additional Notes
                                </label>
                                <textarea name="additional_notes" id="additional_notes" rows="4"
                                    placeholder="Any additional observations or notes..."
                                    class="w-full border-2 @error('additional_notes') border-red-500 dark:border-red-400 @else border-gray-300 dark:border-gray-600 @enderror rounded-xl shadow-sm focus:ring-2 focus:ring-purple-500 focus:border-purple-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white p-4 resize-none transition-all duration-200">{{ old('additional_notes', $medicalReport->additional_notes) }}</textarea>
                                @error('additional_notes')
                                    <div class="mt-2 flex items-center text-red-600 dark:text-red-400 text-sm">
                                        <i class="fas fa-exclamation-circle mr-2"></i>
                                        <span>{{ $message }}</span>
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <div
                            class="bg-yellow-50 dark:bg-yellow-900/20 p-6 rounded-xl border border-yellow-200 dark:border-yellow-700">
                            <h4 class="text-lg font-semibold text-yellow-800 dark:text-yellow-300 mb-4">
                                <i class="fas fa-exclamation-triangle mr-2"></i>Priority & Urgency
                            </h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Priority
                                        Level</label>
                                    <select name="priority_level"
                                        class="w-full border @error('priority_level') border-red-500 dark:border-red-400 @else border-gray-300 dark:border-gray-600 @enderror rounded-lg px-3 py-2 bg-white dark:bg-gray-600 text-gray-900 dark:text-white">
                                        <option value="routine"
                                            {{ old('priority_level', $medicalReport->priority_level) == 'routine' ? 'selected' : '' }}>
                                            Routine</option>
                                        <option value="urgent"
                                            {{ old('priority_level', $medicalReport->priority_level) == 'urgent' ? 'selected' : '' }}>
                                            Urgent</option>
                                        <option value="emergency"
                                            {{ old('priority_level', $medicalReport->priority_level) == 'emergency' ? 'selected' : '' }}>
                                            Emergency</option>
                                    </select>
                                    @error('priority_level')
                                        <div class="mt-1 text-red-600 dark:text-red-400 text-xs">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div>
                                    <label
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Follow-up
                                        Required</label>
                                    <select name="follow_up_required"
                                        class="w-full border @error('follow_up_required') border-red-500 dark:border-red-400 @else border-gray-300 dark:border-gray-600 @enderror rounded-lg px-3 py-2 bg-white dark:bg-gray-600 text-gray-900 dark:text-white">
                                        <option value="no"
                                            {{ old('follow_up_required', $medicalReport->follow_up_required) == 'no' ? 'selected' : '' }}>
                                            No Follow-up Needed</option>
                                        <option value="1_week"
                                            {{ old('follow_up_required', $medicalReport->follow_up_required) == '1_week' ? 'selected' : '' }}>
                                            1 Week</option>
                                        <option value="2_weeks"
                                            {{ old('follow_up_required', $medicalReport->follow_up_required) == '2_weeks' ? 'selected' : '' }}>
                                            2 Weeks</option>
                                        <option value="1_month"
                                            {{ old('follow_up_required', $medicalReport->follow_up_required) == '1_month' ? 'selected' : '' }}>
                                            1 Month</option>
                                        <option value="3_months"
                                            {{ old('follow_up_required', $medicalReport->follow_up_required) == '3_months' ? 'selected' : '' }}>
                                            3 Months</option>
                                        <option value="6_months"
                                            {{ old('follow_up_required', $medicalReport->follow_up_required) == '6_months' ? 'selected' : '' }}>
                                            6 Months</option>
                                        <option value="1_year"
                                            {{ old('follow_up_required', $medicalReport->follow_up_required) == '1_year' ? 'selected' : '' }}>
                                            1 Year</option>
                                        <option value="as_needed"
                                            {{ old('follow_up_required', $medicalReport->follow_up_required) == 'as_needed' ? 'selected' : '' }}>
                                            As Needed</option>
                                    </select>
                                    @error('follow_up_required')
                                        <div class="mt-1 text-red-600 dark:text-red-400 text-xs">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Enhanced Form Actions -->
                <div
                    class="bg-white dark:bg-gray-800 shadow-xl rounded-2xl border border-gray-100 dark:border-gray-700 p-8">
                    <div class="flex flex-col lg:flex-row items-center justify-between space-y-4 lg:space-y-0">
                        <div class="flex items-center space-x-4">
                            <div class="flex items-center">
                                <input type="checkbox" name="save_as_template" id="save_as_template"
                                    {{ old('save_as_template', $medicalReport->save_as_template) ? 'checked' : '' }}
                                    class="h-5 w-5 text-purple-600 focus:ring-purple-500 border-gray-300 rounded">
                                <label for="save_as_template"
                                    class="ml-3 block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    <i class="fas fa-bookmark mr-1"></i>Save as template for future use
                                </label>
                            </div>
                            <div class="flex items-center">
                                <input type="checkbox" name="send_notification" id="send_notification"
                                    {{ old('send_notification', $medicalReport->send_notification) ? 'checked' : '' }}
                                    class="h-5 w-5 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                <label for="send_notification"
                                    class="ml-3 block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    <i class="fas fa-bell mr-1"></i>Notify patient
                                </label>
                            </div>
                        </div>
                        <div class="flex items-center space-x-4">
                            <button type="button" onclick="previewReport()"
                                class="bg-gradient-to-r from-gray-500 to-gray-600 hover:from-gray-600 hover:to-gray-700 text-white px-6 py-3 rounded-xl text-sm font-medium transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-1">
                                <i class="fas fa-eye mr-2"></i>Preview Report
                            </button>
                            <button type="submit" name="status" value="draft"
                                class="bg-gradient-to-r from-orange-500 to-red-600 hover:from-orange-600 hover:to-red-700 text-white px-6 py-3 rounded-xl text-sm font-medium transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-1">
                                <i class="fas fa-save mr-2"></i>Save as Draft
                            </button>
                            <button type="submit" name="status" value="completed"
                                class="bg-gradient-to-r from-blue-500 to-indigo-600 hover:from-blue-600 hover:to-indigo-700 text-white px-8 py-3 rounded-xl text-sm font-semibold transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-1">
                                <i class="fas fa-check-circle mr-2"></i>Update Report
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Common Medications Datalist for Auto-completion -->
    <datalist id="medications-datalist">
        <option value="Ibuprofen">
        <option value="Acetaminophen">
        <option value="Aspirin">
        <option value="Amoxicillin">
        <option value="Ciprofloxacin">
        <option value="Azithromycin">
        <option value="Doxycycline">
        <option value="Prednisone">
        <option value="Metformin">
        <option value="Lisinopril">
        <option value="Atorvastatin">
        <option value="Simvastatin">
        <option value="Metoprolol">
        <option value="Amlodipine">
        <option value="Hydrochlorothiazide">
        <option value="Losartan">
        <option value="Omeprazole">
        <option value="Pantoprazole">
        <option value="Ranitidine">
        <option value="Famotidine">
        <option value="Albuterol">
        <option value="Fluticasone">
        <option value="Budesonide">
        <option value="Sertraline">
        <option value="Fluoxetine">
        <option value="Escitalopram">
        <option value="Lorazepam">
        <option value="Alprazolam">
        <option value="Zolpidem">
        <option value="Trazodone">
        <option value="Gabapentin">
        <option value="Pregabalin">
        <option value="Tramadol">
        <option value="Codeine">
        <option value="Morphine">
        <option value="Fentanyl">
        <option value="Warfarin">
        <option value="Clopidogrel">
        <option value="Rivaroxaban">
        <option value="Insulin">
        <option value="Glipizide">
        <option value="Glyburide">
        <option value="Levothyroxine">
        <option value="Synthroid">
        <option value="Furosemide">
        <option value="Spironolactone">
        <option value="Digoxin">
        <option value="Carvedilol">
        <option value="Propranolol">
        <option value="Diltiazem">
        <option value="Verapamil">
        <option value="Montelukast">
        <option value="Cetirizine">
        <option value="Loratadine">
        <option value="Diphenhydramine">
        <option value="Pseudoephedrine">
        <option value="Guaifenesin">
        <option value="Dextromethorphan">
        <option value="Phenylephrine">
        <option value="Naproxen">
        <option value="Diclofenac">
        <option value="Meloxicam">
        <option value="Celecoxib">
        <option value="Cyclobenzaprine">
        <option value="Baclofen">
        <option value="Methocarbamol">
        <option value="Tizanidine">
        <option value="Clonazepam">
        <option value="Diazepam">
        <option value="Temazepam">
        <option value="Hydroxyzine">
        <option value="Buspirone">
        <option value="Bupropion">
        <option value="Venlafaxine">
        <option value="Duloxetine">
        <option value="Mirtazapine">
        <option value="Trazodone">
        <option value="Quetiapine">
        <option value="Aripiprazole">
        <option value="Risperidone">
        <option value="Olanzapine">
        <option value="Haloperidol">
        <option value="Lithium">
        <option value="Valproic Acid">
        <option value="Carbamazepine">
        <option value="Phenytoin">
        <option value="Levetiracetam">
        <option value="Topiramate">
        <option value="Lamotrigine">
    </datalist>

    <!-- Quick Tips Modal -->
    <div id="quick-tips-modal"
        class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl max-w-2xl w-full max-h-[80vh] overflow-y-auto">
            <div
                class="px-8 py-6 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-blue-50 to-purple-50 dark:from-gray-700 dark:to-gray-600 rounded-t-2xl">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="bg-blue-500 p-2 rounded-lg">
                            <i class="fas fa-lightbulb text-white"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white">Quick Tips for Medical Reports</h3>
                    </div>
                    <button onclick="hideQuickTips()"
                        class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
            </div>
            <div class="p-8">
                <div class="space-y-6">
                    <div class="flex items-start space-x-3">
                        <div class="bg-blue-100 dark:bg-blue-800 p-2 rounded-lg">
                            <i class="fas fa-magic text-blue-600 dark:text-blue-300"></i>
                        </div>
                        <div>
                            <h4 class="font-semibold text-gray-900 dark:text-white">Quick Templates</h4>
                            <p class="text-gray-600 dark:text-gray-400 text-sm">Use template buttons to auto-fill common
                                sections like normal vitals, routine checkups, or physical exam findings.</p>
                        </div>
                    </div>
                    <div class="flex items-start space-x-3">
                        <div class="bg-purple-100 dark:bg-purple-800 p-2 rounded-lg">
                            <i class="fas fa-keyboard text-purple-600 dark:text-purple-300"></i>
                        </div>
                        <div>
                            <h4 class="font-semibold text-gray-900 dark:text-white">Keyboard Shortcuts</h4>
                            <p class="text-gray-600 dark:text-gray-400 text-sm">Ctrl+S to save as draft, Ctrl+Enter to
                                complete report, Ctrl+P to preview.</p>
                        </div>
                    </div>
                    <div class="flex items-start space-x-3">
                        <div class="bg-yellow-100 dark:bg-yellow-800 p-2 rounded-lg">
                            <i class="fas fa-save text-yellow-600 dark:text-yellow-300"></i>
                        </div>
                        <div>
                            <h4 class="font-semibold text-gray-900 dark:text-white">Auto-Save</h4>
                            <p class="text-gray-600 dark:text-gray-400 text-sm">Your progress is automatically saved every
                                30 seconds to prevent data loss.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            let autoSaveInterval;

            document.addEventListener('DOMContentLoaded', function() {
                setupAutoSave();
                setupKeyboardShortcuts();
                setupFormErrorHandling();
                // Initialize prescription and lab test management
                initializePrescriptionManagement();
                initializeLabTestManagement();
                updateFormProgress();

                // Add input listeners for all form fields to trigger progress updates
                const formInputs = document.querySelectorAll('input, textarea, select');
                formInputs.forEach(input => {
                    input.addEventListener('input', updateFormProgress);
                    input.addEventListener('change', updateFormProgress);

                    // Add BMI calculation for height and weight fields
                    if (input.name === 'vital_signs[height]' || input.name === 'vital_signs[weight]') {
                        input.addEventListener('input', calculateBMI);
                        input.addEventListener('change', calculateBMI);
                    }

                    // Add listeners for prescription and lab test fields
                    if (input.name && input.name.includes('prescriptions[')) {
                        input.addEventListener('input', updatePrescriptionSummary);
                        input.addEventListener('change', updatePrescriptionSummary);
                    }
                    if (input.name && input.name.includes('lab_tests[')) {
                        input.addEventListener('input', updateLabTestSummary);
                        input.addEventListener('change', updateLabTestSummary);
                    }
                });

                // Initial summary generation for existing data
                setTimeout(() => {
                    // Parse existing medications_prescribed field and populate form
                    populateExistingMedications();
                    // Parse existing lab_tests_ordered field and populate form
                    populateExistingLabTests();

                    updatePrescriptionSummary();
                    updateLabTestSummary();
                }, 100);

                // Patient search is not initialized here as patient is fixed for edit.
                // If patient selection needs to be changeable on edit, uncomment initializePatientSearch()
                // and ensure the patient search HTML section is present.
            });

            function setupFormErrorHandling() {
                const form = document.querySelector('form');
                if (!form) return;

                const formInputs = form.querySelectorAll('input, textarea, select');
                formInputs.forEach(input => {
                    input.addEventListener('input', function() {
                        this.classList.remove('border-red-500', 'dark:border-red-400');
                        const errorSection = document.getElementById('form-errors-section');
                        if (!errorSection.classList.contains('hidden')) {
                            setTimeout(() => {
                                hideFormErrors();
                            }, 2000);
                        }
                    });

                    input.addEventListener('focus', function() {
                        this.classList.remove('border-red-500', 'dark:border-red-400');
                    });
                });
            }

            function insertTemplate(templateType) {
                const templates = {
                    normal_vitals: {
                        'vital_signs[blood_pressure]': '120/80 mmHg',
                        'vital_signs[temperature]': '98.6°F (37°C)',
                        'vital_signs[heart_rate]': '72 bpm',
                        'vital_signs[weight]': '70 kg',
                        'vital_signs[respiratory_rate]': '16 breaths/min',
                        'vital_signs[oxygen_saturation]': '98%',
                        'vital_signs[height]': '170 cm'
                    },
                    routine_checkup: {
                        'chief_complaint': 'Routine health checkup and wellness visit',
                        'physical_examination': 'General appearance: Alert and oriented, appears well\nVital signs: Within normal limits\nHEENT: Normocephalic, atraumatic. PERRLA. No lymphadenopathy\nCardiovascular: Regular rate and rhythm, no murmurs\nRespiratory: Clear to auscultation bilaterally, no wheezes or rales\nAbdomen: Soft, non-tender, non-distended, bowel sounds present\nExtremities: No edema, normal range of motion',
                        'assessment_diagnosis': 'Routine health maintenance visit\nNo acute concerns identified\nOverall health status: Good',
                        'treatment_plan': 'Continue current lifestyle. Follow up in 1 year or as needed.',
                        'follow_up_instructions': 'Return in 1 year for routine checkup, or sooner if any new concerns arise.'
                    },
                    follow_up: {
                        'chief_complaint': 'Follow-up visit regarding [Previous Condition/Concern]',
                        'history_of_present_illness': 'Patient returns for scheduled follow-up. Reports [Improvement/No Change/Worsening] of symptoms. Currently [Taking/Not Taking] prescribed medications.',
                        'assessment_diagnosis': 'Status post [Previous Diagnosis]. Condition is [Improved/Stable/Worsening].',
                        'treatment_plan': 'Continue current treatment plan / Adjust medication as follows: [Details] / Refer to specialist if no improvement.',
                        'follow_up_instructions': 'Return in [Timeframe] for further evaluation.'
                    },
                    emergency: {
                        'report_type': 'Emergency',
                        'priority_level': 'emergency',
                        'chief_complaint': '[Describe emergency situation, e.g., Acute chest pain, Difficulty breathing, Trauma]',
                        'history_of_present_illness': 'Onset: [Time]. Severity: [Scale 1-10]. Associated symptoms: [Symptoms]. Events leading to presentation: [Details].',
                        'physical_examination': '[Focused emergency physical exam findings]',
                        'assessment_diagnosis': '[Provisional diagnosis based on emergency presentation]',
                        'treatment_plan': '[Immediate interventions, e.g., Oxygen administered, IV access established, Medications given]. Plan for admission / transfer / further urgent investigation.'
                    },
                    physical_exam: {
                        'physical_examination': 'General: Well-developed, well-nourished, in no acute distress.\nSkin: Warm, dry, intact. No rashes or lesions.\nHEENT: Head normocephalic, atraumatic. Eyes: PERRLA, EOMI. Ears: TMs clear. Nose: No discharge. Throat: Clear.\nNeck: Supple, no lymphadenopathy, no thyromegaly.\nChest/Lungs: Clear to auscultation bilaterally. No wheezes, rales, or rhonchi.\nHeart: Regular rate and rhythm. S1, S2 normal. No murmurs, gallops, or rubs.\nAbdomen: Soft, non-tender, non-distended. Bowel sounds normal. No hepatosplenomegaly.\nExtremities: No cyanosis, clubbing, or edema. Full range of motion. Pulses 2+ throughout.\nNeurological: Alert and oriented x3. Cranial nerves II-XII intact. Motor strength 5/5 throughout. Sensation intact. Reflexes 2+ symmetric.'
                    }
                };

                const currentTemplate = templates[templateType];
                if (!currentTemplate) {
                    showNotification(`Template "${templateType}" not found.`, 'error');
                    return;
                }

                for (const fieldName in currentTemplate) {
                    const field = document.querySelector(`[name="${fieldName}"]`);
                    if (field) {
                        field.value = currentTemplate[fieldName];
                        // Trigger input event for any listeners
                        field.dispatchEvent(new Event('input'));
                    } else {
                        console.warn(`Field "${fieldName}" not found for template insertion.`);
                    }
                }
                showNotification(`Template "${templateType}" applied.`, 'success');
            }

            function clearAllFields() {
                const form = document.querySelector('form');
                form.querySelectorAll('input[type="text"], input[type="date"], textarea').forEach(input => {
                    if (input.name !== '_token' && input.name !== '_method' && input.name !==
                        'patient_id') { // Don't clear hidden fields or patient_id
                        input.value = '';
                    }
                });
                form.querySelectorAll('select').forEach(select => {
                    if (select.name !== 'patient_id') { // Don't clear patient_id select
                        select.selectedIndex = 0;
                    }
                });
                form.querySelectorAll('input[type="checkbox"]').forEach(checkbox => checkbox.checked = false);
                showNotification('All fields cleared.', 'info');
            }

            // Functions to populate existing data from medical report relationships
            function populateExistingMedications() {
                const prescriptionsData = @json($medicalReport->prescriptions ?? []);
                console.log('Existing Prescriptions Data:', prescriptionsData);
                
                if (!prescriptionsData || prescriptionsData.length === 0) return;
              
                // Iterate through each prescription
                prescriptionsData.forEach((prescription, prescriptionIndex) => {
                    if (prescription.prescription_medications && prescription.prescription_medications.length > 0) {
                        // Iterate through each medication in the prescription
                        prescription.prescription_medications.forEach((prescriptionMedication, medIndex) => {
                            const medication = prescriptionMedication.medication;
                            const medicationName = medication ? (medication.name || medication.generic_name || medication.brand_name) : 'Unknown Medication';
                            
                            addExistingMedication(
                                medicationName,
                                prescriptionMedication.dosage || '',
                                prescriptionMedication.frequency || '',
                                prescriptionMedication.duration || '',
                                prescriptionMedication.instructions || '',
                                prescriptionMedication.quantity_prescribed || '',
                                '0' // refills - you might want to get this from prescription.refills_allowed
                            );
                        });
                    }
                });
            }

            function populateExistingLabTests() {
                const labTestsData = @json($medicalReport->labTestRequests ?? []);
                console.log('Existing Lab Tests Data:', labTestsData);
                
                if (!labTestsData || labTestsData.length === 0) return;

                // Iterate through each lab test request
                labTestsData.forEach((labTest, index) => {
                    
                    addExistingLabTest(
                        labTest.test_name || '',
                        labTest.test_type || 'other',
                        labTest.priority || 'routine',
                        labTest.preferred_date.split('T')[0] || '',
                        labTest.clinical_notes || ''
                    );
                });
            }

            // Helper function to safely escape HTML values
            function escapeHtml(unsafe) {
                if (!unsafe) return '';
                // Convert to string first to ensure .replace() method is available
                return String(unsafe)
                    .replace(/&/g, "&amp;")
                    .replace(/</g, "&lt;")
                    .replace(/>/g, "&gt;")
                    .replace(/"/g, "&quot;")
                    .replace(/'/g, "&#039;");
            }

            function addExistingMedication(medicationName, dosage, frequency, duration, instructions = '', quantity = '',
                refills = '0') {
                prescriptionMedicationCount++;

                const container = document.getElementById('prescription-medications');
                const noMessage = document.getElementById('no-medications-message');

                // Escape all values for safe HTML insertion
                const safeMedicationName = escapeHtml(medicationName);
                const safeDosage = escapeHtml(dosage);
                const safeFrequency = escapeHtml(frequency);
                const safeDuration = escapeHtml(duration);
                const safeInstructions = escapeHtml(instructions);
                const safeQuantity = escapeHtml(quantity);
                const safeRefills = escapeHtml(refills);

                const medicationHtml = `
        <div class="prescription-medication border border-blue-200 dark:border-blue-600 rounded-lg p-4 bg-white dark:bg-gray-700" id="prescription-${prescriptionMedicationCount}">
            <div class="flex justify-between items-start mb-4">
                <h5 class="text-md font-semibold text-blue-800 dark:text-blue-300">
                    <i class="fas fa-pills mr-2"></i>Medication ${prescriptionMedicationCount}
                </h5>
                <button type="button" onclick="removePrescriptionMedication(${prescriptionMedicationCount})" 
                    class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300">
                    <i class="fas fa-trash-alt"></i>
                </button>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Medication Name *</label>
                    <input type="text" name="prescriptions[${prescriptionMedicationCount}][medication_name]" 
                        placeholder="Search medication..." list="medications-datalist" value="${safeMedicationName}"
                        class="w-full border border-gray-300 dark:border-gray-600 rounded-md px-3 py-2 bg-white dark:bg-gray-600 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Dosage *</label>
                    <input type="text" name="prescriptions[${prescriptionMedicationCount}][dosage]" 
                        placeholder="e.g., 500mg, 2 tablets" value="${safeDosage}"
                        class="w-full border border-gray-300 dark:border-gray-600 rounded-md px-3 py-2 bg-white dark:bg-gray-600 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Frequency *</label>
                    <select name="prescriptions[${prescriptionMedicationCount}][frequency]" 
                        class="w-full border border-gray-300 dark:border-gray-600 rounded-md px-3 py-2 bg-white dark:bg-gray-600 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                        <option value="">Select frequency</option>
                        <option value="once daily" ${safeFrequency === 'once daily' ? 'selected' : ''}>Once daily</option>
                        <option value="twice daily" ${safeFrequency === 'twice daily' ? 'selected' : ''}>Twice daily</option>
                        <option value="three times daily" ${safeFrequency === 'three times daily' ? 'selected' : ''}>Three times daily</option>
                        <option value="four times daily" ${safeFrequency === 'four times daily' ? 'selected' : ''}>Four times daily</option>
                        <option value="every 4 hours" ${safeFrequency === 'every 4 hours' ? 'selected' : ''}>Every 4 hours</option>
                        <option value="every 6 hours" ${safeFrequency === 'every 6 hours' ? 'selected' : ''}>Every 6 hours</option>
                        <option value="every 8 hours" ${safeFrequency === 'every 8 hours' ? 'selected' : ''}>Every 8 hours</option>
                        <option value="every 12 hours" ${safeFrequency === 'every 12 hours' ? 'selected' : ''}>Every 12 hours</option>
                        <option value="as needed" ${safeFrequency === 'as needed' ? 'selected' : ''}>As needed</option>
                        <option value="weekly" ${safeFrequency === 'weekly' ? 'selected' : ''}>Weekly</option>
                        <option value="monthly" ${safeFrequency === 'monthly' ? 'selected' : ''}>Monthly</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Duration *</label>
                    <input type="text" name="prescriptions[${prescriptionMedicationCount}][duration]" 
                        placeholder="e.g., 7 days, 2 weeks, 1 month" value="${safeDuration}"
                        class="w-full border border-gray-300 dark:border-gray-600 rounded-md px-3 py-2 bg-white dark:bg-gray-600 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Special Instructions</label>
                    <textarea name="prescriptions[${prescriptionMedicationCount}][instructions]" rows="2" 
                        placeholder="Special instructions, warnings, or notes..."
                        class="w-full border border-gray-300 dark:border-gray-600 rounded-md px-3 py-2 bg-white dark:bg-gray-600 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500">${safeInstructions}</textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Quantity</label>
                    <input type="number" name="prescriptions[${prescriptionMedicationCount}][quantity]" min="1" 
                        placeholder="Number of units" value="${safeQuantity}"
                        class="w-full border border-gray-300 dark:border-gray-600 rounded-md px-3 py-2 bg-white dark:bg-gray-600 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Refills</label>
                    <input type="number" name="prescriptions[${prescriptionMedicationCount}][refills]" min="0" max="5" value="${safeRefills}"
                        class="w-full border border-gray-300 dark:border-gray-600 rounded-md px-3 py-2 bg-white dark:bg-gray-600 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
            </div>
        </div>
    `;

                container.insertAdjacentHTML('beforeend', medicationHtml);
                noMessage.classList.add('hidden');
            }

            function addExistingLabTest(testName, testType, priority = 'routine', preferredDate = '', clinicalNotes = '') {
                labTestRequestCount++;

                const container = document.getElementById('lab-test-requests');
                const noMessage = document.getElementById('no-lab-tests-message');

                const labTestHtml = `
        <div class="lab-test-request border border-green-200 dark:border-green-600 rounded-lg p-4 bg-white dark:bg-gray-700" id="lab-test-${labTestRequestCount}">
            <div class="flex justify-between items-start mb-4">
                <h5 class="text-md font-semibold text-green-800 dark:text-green-300">
                    <i class="fas fa-vials mr-2"></i>Lab Test ${labTestRequestCount}
                </h5>
                <button type="button" onclick="removeLabTestRequest(${labTestRequestCount})" 
                    class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300">
                    <i class="fas fa-trash-alt"></i>
                </button>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Test Name *</label>
                    <input type="text" name="lab_tests[${labTestRequestCount}][test_name]" 
                        placeholder="e.g., Complete Blood Count" value="${testName}"
                        class="w-full border border-gray-300 dark:border-gray-600 rounded-md px-3 py-2 bg-white dark:bg-gray-600 text-gray-900 dark:text-white focus:ring-2 focus:ring-green-500 focus:border-green-500" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Test Type *</label>
                    <select name="lab_tests[${labTestRequestCount}][test_type]" 
                        class="w-full border border-gray-300 dark:border-gray-600 rounded-md px-3 py-2 bg-white dark:bg-gray-600 text-gray-900 dark:text-white focus:ring-2 focus:ring-green-500 focus:border-green-500" required>
                        <option value="">Select type</option>
                        <option value="blood" ${testType === 'blood' ? 'selected' : ''}>Blood Test</option>
                        <option value="urine" ${testType === 'urine' ? 'selected' : ''}>Urine Test</option>
                        <option value="stool" ${testType === 'stool' ? 'selected' : ''}>Stool Test</option>
                        <option value="imaging" ${testType === 'imaging' ? 'selected' : ''}>Imaging Study</option>
                        <option value="biopsy" ${testType === 'biopsy' ? 'selected' : ''}>Biopsy</option>
                        <option value="culture" ${testType === 'culture' ? 'selected' : ''}>Culture</option>
                        <option value="serology" ${testType === 'serology' ? 'selected' : ''}>Serology</option>
                        <option value="molecular" ${testType === 'molecular' ? 'selected' : ''}>Molecular Test</option>
                        <option value="other" ${testType === 'other' ? 'selected' : ''}>Other</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Priority</label>
                    <select name="lab_tests[${labTestRequestCount}][priority]" 
                        class="w-full border border-gray-300 dark:border-gray-600 rounded-md px-3 py-2 bg-white dark:bg-gray-600 text-gray-900 dark:text-white focus:ring-2 focus:ring-green-500 focus:border-green-500">
                        <option value="routine" ${priority === 'routine' ? 'selected' : ''}>Routine</option>
                        <option value="urgent" ${priority === 'urgent' ? 'selected' : ''}>Urgent</option>
                        <option value="stat" ${priority === 'stat' ? 'selected' : ''}>STAT (Immediate)</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Preferred Date</label>
                    <input type="date" name="lab_tests[${labTestRequestCount}][preferred_date]" 
                        min="${new Date().toISOString().split('T')[0]}" value="${preferredDate}"
                        class="w-full border border-gray-300 dark:border-gray-600 rounded-md px-3 py-2 bg-white dark:bg-gray-600 text-gray-900 dark:text-white focus:ring-2 focus:ring-green-500 focus:border-green-500">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Clinical Notes</label>
                    <textarea name="lab_tests[${labTestRequestCount}][clinical_notes]" rows="2" 
                        placeholder="Clinical indication, specific requirements, or notes for the laboratory..."
                        class="w-full border border-gray-300 dark:border-gray-600 rounded-md px-3 py-2 bg-white dark:bg-gray-600 text-gray-900 dark:text-white focus:ring-2 focus:ring-green-500 focus:border-green-500">${clinicalNotes}</textarea>
                </div>
            </div>
        </div>
    `;

                container.insertAdjacentHTML('beforeend', labTestHtml);
                noMessage.classList.add('hidden');
            }

            // Prescription Management Functions
            let prescriptionMedicationCount = {{ $medicalReport->prescriptions->sum(function($prescription) { return $prescription->prescriptionMedications->count(); }) }};
            let labTestRequestCount = {{ $medicalReport->labTestRequests ? $medicalReport->labTestRequests->count() : 0 }};

            function initializePrescriptionManagement() {
                // Ensure counter starts from existing items count to avoid ID conflicts
                const existingMedications = document.querySelectorAll('.prescription-medication');
                if (existingMedications.length > 0) {
                    prescriptionMedicationCount = existingMedications.length;
                }
                updatePrescriptionDisplay();
                updatePrescriptionSummary();
            }

            function addPrescriptionMedication() {
                prescriptionMedicationCount++;

                // Ensure unique ID by checking for existing elements
                while (document.getElementById(`prescription-${prescriptionMedicationCount}`)) {
                    prescriptionMedicationCount++;
                }

                const container = document.getElementById('prescription-medications');
                const noMessage = document.getElementById('no-medications-message');

                const medicationHtml = `
        <div class="prescription-medication border border-blue-200 dark:border-blue-600 rounded-lg p-4 bg-white dark:bg-gray-700" id="prescription-${prescriptionMedicationCount}">
            <div class="flex justify-between items-start mb-4">
                <h5 class="text-md font-semibold text-blue-800 dark:text-blue-300">
                    <i class="fas fa-pills mr-2"></i>Medication ${prescriptionMedicationCount}
                </h5>
                <button type="button" onclick="removePrescriptionMedication(${prescriptionMedicationCount})" 
                    class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300">
                    <i class="fas fa-trash-alt"></i>
                </button>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Medication Name *</label>
                    <input type="text" name="prescriptions[${prescriptionMedicationCount}][medication_name]" 
                        placeholder="Search medication..." list="medications-datalist"
                        class="w-full border border-gray-300 dark:border-gray-600 rounded-md px-3 py-2 bg-white dark:bg-gray-600 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Dosage *</label>
                    <input type="text" name="prescriptions[${prescriptionMedicationCount}][dosage]" 
                        placeholder="e.g., 500mg, 2 tablets"
                        class="w-full border border-gray-300 dark:border-gray-600 rounded-md px-3 py-2 bg-white dark:bg-gray-600 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Frequency *</label>
                    <select name="prescriptions[${prescriptionMedicationCount}][frequency]" 
                        class="w-full border border-gray-300 dark:border-gray-600 rounded-md px-3 py-2 bg-white dark:bg-gray-600 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                        <option value="">Select frequency</option>
                        <option value="once daily">Once daily</option>
                        <option value="twice daily">Twice daily</option>
                        <option value="three times daily">Three times daily</option>
                        <option value="four times daily">Four times daily</option>
                        <option value="every 4 hours">Every 4 hours</option>
                        <option value="every 6 hours">Every 6 hours</option>
                        <option value="every 8 hours">Every 8 hours</option>
                        <option value="every 12 hours">Every 12 hours</option>
                        <option value="as needed">As needed</option>
                        <option value="weekly">Weekly</option>
                        <option value="monthly">Monthly</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Duration *</label>
                    <input type="text" name="prescriptions[${prescriptionMedicationCount}][duration]" 
                        placeholder="e.g., 7 days, 2 weeks, 1 month"
                        class="w-full border border-gray-300 dark:border-gray-600 rounded-md px-3 py-2 bg-white dark:bg-gray-600 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Special Instructions</label>
                    <textarea name="prescriptions[${prescriptionMedicationCount}][instructions]" rows="2" 
                        placeholder="Special instructions, warnings, or notes..."
                        class="w-full border border-gray-300 dark:border-gray-600 rounded-md px-3 py-2 bg-white dark:bg-gray-600 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500"></textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Quantity</label>
                    <input type="number" name="prescriptions[${prescriptionMedicationCount}][quantity]" min="1" 
                        placeholder="Number of units"
                        class="w-full border border-gray-300 dark:border-gray-600 rounded-md px-3 py-2 bg-white dark:bg-gray-600 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Refills</label>
                    <input type="number" name="prescriptions[${prescriptionMedicationCount}][refills]" min="0" max="5" value="0"
                        class="w-full border border-gray-300 dark:border-gray-600 rounded-md px-3 py-2 bg-white dark:bg-gray-600 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
            </div>
        </div>
    `;

                container.insertAdjacentHTML('beforeend', medicationHtml);
                noMessage.classList.add('hidden');
                updatePrescriptionSummary();
            }

            function removePrescriptionMedication(id) {
                const element = document.getElementById(`prescription-${id}`);
                if (element) {
                    element.remove();
                    updatePrescriptionDisplay();
                    updatePrescriptionSummary();
                }
            }

            function updatePrescriptionDisplay() {
                const container = document.getElementById('prescription-medications');
                const noMessage = document.getElementById('no-medications-message');

                if (container.children.length === 0) {
                    noMessage.classList.remove('hidden');
                } else {
                    noMessage.classList.add('hidden');
                }
            }

            function updatePrescriptionSummary() {
                const medications = document.querySelectorAll('.prescription-medication');
                let summary = '';

                medications.forEach((med, index) => {
                    const name = med.querySelector('[name*="[medication_name]"]')?.value || '';
                    const dosage = med.querySelector('[name*="[dosage]"]')?.value || '';
                    const frequency = med.querySelector('[name*="[frequency]"]')?.value || '';
                    const duration = med.querySelector('[name*="[duration]"]')?.value || '';

                    if (name || dosage || frequency || duration) {
                        summary += `${index + 1}. ${name} - ${dosage} - ${frequency} for ${duration}\n`;
                    }
                });

                const hiddenField = document.getElementById('medications_prescribed_hidden');
                if (hiddenField) {
                    hiddenField.value = summary;
                }
            }

            // Lab Test Management Functions
            function initializeLabTestManagement() {
                // Ensure counter starts from existing items count to avoid ID conflicts
                const existingLabTests = document.querySelectorAll('.lab-test-request');
                if (existingLabTests.length > 0) {
                    labTestRequestCount = existingLabTests.length;
                }
                updateLabTestDisplay();
                updateLabTestSummary();
            }

            function addLabTestRequest() {
                labTestRequestCount++;

                // Ensure unique ID by checking for existing elements
                while (document.getElementById(`lab-test-${labTestRequestCount}`)) {
                    labTestRequestCount++;
                }

                const container = document.getElementById('lab-test-requests');
                const noMessage = document.getElementById('no-lab-tests-message');

                const labTestHtml = `
        <div class="lab-test-request border border-green-200 dark:border-green-600 rounded-lg p-4 bg-white dark:bg-gray-700" id="lab-test-${labTestRequestCount}">
            <div class="flex justify-between items-start mb-4">
                <h5 class="text-md font-semibold text-green-800 dark:text-green-300">
                    <i class="fas fa-vials mr-2"></i>Lab Test ${labTestRequestCount}
                </h5>
                <button type="button" onclick="removeLabTestRequest(${labTestRequestCount})" 
                    class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300">
                    <i class="fas fa-trash-alt"></i>
                </button>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Test Name *</label>
                    <input type="text" name="lab_tests[${labTestRequestCount}][test_name]" 
                        placeholder="e.g., Complete Blood Count"
                        class="w-full border border-gray-300 dark:border-gray-600 rounded-md px-3 py-2 bg-white dark:bg-gray-600 text-gray-900 dark:text-white focus:ring-2 focus:ring-green-500 focus:border-green-500" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Test Type *</label>
                    <select name="lab_tests[${labTestRequestCount}][test_type]" 
                        class="w-full border border-gray-300 dark:border-gray-600 rounded-md px-3 py-2 bg-white dark:bg-gray-600 text-gray-900 dark:text-white focus:ring-2 focus:ring-green-500 focus:border-green-500" required>
                        <option value="">Select type</option>
                        <option value="blood">Blood Test</option>
                        <option value="urine">Urine Test</option>
                        <option value="stool">Stool Test</option>
                        <option value="imaging">Imaging Study</option>
                        <option value="biopsy">Biopsy</option>
                        <option value="culture">Culture</option>
                        <option value="serology">Serology</option>
                        <option value="molecular">Molecular Test</option>
                        <option value="other">Other</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Priority</label>
                    <select name="lab_tests[${labTestRequestCount}][priority]" 
                        class="w-full border border-gray-300 dark:border-gray-600 rounded-md px-3 py-2 bg-white dark:bg-gray-600 text-gray-900 dark:text-white focus:ring-2 focus:ring-green-500 focus:border-green-500">
                        <option value="routine">Routine</option>
                        <option value="urgent">Urgent</option>
                        <option value="stat">STAT (Immediate)</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Preferred Date</label>
                    <input type="date" name="lab_tests[${labTestRequestCount}][preferred_date]" 
                        min="${new Date().toISOString().split('T')[0]}"
                        class="w-full border border-gray-300 dark:border-gray-600 rounded-md px-3 py-2 bg-white dark:bg-gray-600 text-gray-900 dark:text-white focus:ring-2 focus:ring-green-500 focus:border-green-500">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Clinical Notes</label>
                    <textarea name="lab_tests[${labTestRequestCount}][clinical_notes]" rows="2" 
                        placeholder="Clinical indication, specific requirements, or notes for the laboratory..."
                        class="w-full border border-gray-300 dark:border-gray-600 rounded-md px-3 py-2 bg-white dark:bg-gray-600 text-gray-900 dark:text-white focus:ring-2 focus:ring-green-500 focus:border-green-500"></textarea>
                </div>
            </div>
        </div>
    `;

                container.insertAdjacentHTML('beforeend', labTestHtml);
                noMessage.classList.add('hidden');
                updateLabTestSummary();
            }

            function removeLabTestRequest(id) {
                const element = document.getElementById(`lab-test-${id}`);
                if (element) {
                    element.remove();
                    updateLabTestDisplay();
                    updateLabTestSummary();
                }
            }

            function updateLabTestDisplay() {
                const container = document.getElementById('lab-test-requests');
                const noMessage = document.getElementById('no-lab-tests-message');

                if (container.children.length === 0) {
                    noMessage.classList.remove('hidden');
                } else {
                    noMessage.classList.add('hidden');
                }
            }

            function updateLabTestSummary() {
                const labTests = document.querySelectorAll('.lab-test-request');
                let summary = '';

                labTests.forEach((test, index) => {
                    const name = test.querySelector('[name*="[test_name]"]')?.value || '';
                    const type = test.querySelector('[name*="[test_type]"]')?.value || '';
                    const priority = test.querySelector('[name*="[priority]"]')?.value || '';

                    if (name || type) {
                        summary +=
                            `${index + 1}. ${name} (${type})${priority !== 'routine' ? ' - ' + priority.toUpperCase() : ''}\n`;
                    }
                });

                const hiddenField = document.getElementById('lab_tests_ordered_hidden');
                if (hiddenField) {
                    hiddenField.value = summary;
                }
            }

            // Update summaries when form fields change
            document.addEventListener('input', function(e) {
                if (e.target.name && e.target.name.includes('prescriptions[')) {
                    updatePrescriptionSummary();
                }
                if (e.target.name && e.target.name.includes('lab_tests[')) {
                    updateLabTestSummary();
                }
                updateFormProgress();
            });

            document.addEventListener('change', function(e) {
                if (e.target.name && e.target.name.includes('prescriptions[')) {
                    updatePrescriptionSummary();
                }
                if (e.target.name && e.target.name.includes('lab_tests[')) {
                    updateLabTestSummary();
                }
                updateFormProgress();
            });

            // Enhanced Form Functions
            function updateFormProgress() {
                const requiredFields = [
                    'patient_id', 'title', 'consultation_date', 'report_type',
                    'vital_signs[blood_pressure]', 'vital_signs[temperature]',
                    'vital_signs[heart_rate]', 'vital_signs[weight]'
                ];

                let completed = 0;
                requiredFields.forEach(field => {
                    const element = document.querySelector(`[name="${field}"]`);
                    if (element && element.value.trim() !== '') {
                        completed++;
                    }
                });

                const percentage = Math.round((completed / requiredFields.length) * 100);
                const progressPercentage = document.getElementById('progress-percentage');
                const progressBar = document.getElementById('progress-bar');

                if (progressPercentage) {
                    progressPercentage.textContent = percentage + '%';
                }

                if (progressBar) {
                    progressBar.style.width = percentage + '%';
                }

                // Update section status
                updateSectionStatus('patient', ['patient_id', 'title', 'consultation_date', 'report_type']);
                updateSectionStatus('vitals', [
                    'vital_signs[blood_pressure]', 'vital_signs[temperature]',
                    'vital_signs[heart_rate]', 'vital_signs[weight]',
                    'vital_signs[respiratory_rate]', 'vital_signs[oxygen_saturation]',
                    'vital_signs[height]'
                ]);
                updateSectionStatus('assessment', ['chief_complaint', 'physical_examination']);
                updateSectionStatus('diagnosis', ['assessment_diagnosis', 'treatment_plan']);

                // Update vitals completion specifically
                updateVitalsCompletion();
            }

            function updateVitalsCompletion() {
                const vitalFields = [
                    'vital_signs[blood_pressure]', 'vital_signs[temperature]',
                    'vital_signs[heart_rate]', 'vital_signs[weight]',
                    'vital_signs[respiratory_rate]', 'vital_signs[oxygen_saturation]',
                    'vital_signs[height]'
                ];

                let completed = 0;
                vitalFields.forEach(field => {
                    const element = document.querySelector(`[name="${field}"]`);
                    if (element && element.value.trim() !== '') {
                        completed++;
                    }
                });

                const vitalsCompletion = document.getElementById('vitals-completion');
                const vitalsProgress = document.getElementById('vitals-progress');

                if (vitalsCompletion) {
                    vitalsCompletion.textContent = `${completed}/${vitalFields.length} Complete`;
                }

                if (vitalsProgress) {
                    const percentage = Math.round((completed / vitalFields.length) * 100);
                    vitalsProgress.style.width = percentage + '%';
                }
            }

            function updateSectionStatus(section, fields) {
                let completed = 0;
                fields.forEach(field => {
                    const element = document.querySelector(`[name="${field}"]`);
                    if (element && element.value.trim() !== '') {
                        completed++;
                    }
                });

                const statusElement = document.getElementById(`${section}-status`);
                if (statusElement) {
                    statusElement.className = 'field-status ';
                    if (completed === fields.length) {
                        statusElement.className += 'field-complete';
                    } else if (completed > 0) {
                        statusElement.className += 'field-partial';
                    } else {
                        statusElement.className += 'field-empty';
                    }
                }
            }

            // Quick Actions
            function applyPreset(presetType) {
                switch (presetType) {
                    case 'routine-checkup':
                        document.querySelector('[name="title"]').value = 'Routine Health Check-up';
                        document.querySelector('[name="report_type"]').value = 'Consultation';
                        document.querySelector('[name="chief_complaint"]').value =
                            'Routine health examination and wellness check';
                        fillNormalVitals();
                        break;
                    case 'follow-up':
                        document.querySelector('[name="title"]').value = 'Follow-up Visit';
                        document.querySelector('[name="report_type"]').value = 'Follow-up';
                        document.querySelector('[name="chief_complaint"]').value = 'Follow-up visit for ongoing condition';
                        break;
                    case 'emergency':
                        document.querySelector('[name="title"]').value = 'Emergency Consultation';
                        document.querySelector('[name="report_type"]').value = 'Emergency';
                        document.querySelector('[name="chief_complaint"]').value =
                            'Urgent medical condition requiring immediate attention';
                        break;
                }
                updateFormProgress();
                showNotification(`${presetType.replace('-', ' ')} preset applied`, 'success');
            }

            function fillNormalVitals() {
                document.querySelector('[name="vital_signs[blood_pressure]"]').value = '120/80';
                document.querySelector('[name="vital_signs[temperature]"]').value = '98.6';
                document.querySelector('[name="vital_signs[heart_rate]"]').value = '72';
                document.querySelector('[name="vital_signs[respiratory_rate]"]').value = '16';
                document.querySelector('[name="vital_signs[oxygen_saturation]"]').value = '98';
                document.querySelector('[name="vital_signs[height]"]').value = '170';
                document.querySelector('[name="vital_signs[weight]"]').value = '70';
                calculateBMI();
                updateFormProgress();
                showNotification('Normal vital signs filled', 'success');
            }

            function calculateBMI() {
                const heightField = document.querySelector('[name="vital_signs[height]"]');
                const weightField = document.querySelector('[name="vital_signs[weight]"]');
                const bmiValue = document.getElementById('bmi-value');
                const bmiCategory = document.getElementById('bmi-category');

                if (!heightField || !weightField || !bmiValue || !bmiCategory) return;

                const height = parseFloat(heightField.value);
                const weight = parseFloat(weightField.value);

                if (height > 0 && weight > 0) {
                    // Convert height to meters if in cm
                    const heightInMeters = height > 3 ? height / 100 : height;
                    const bmi = weight / (heightInMeters * heightInMeters);

                    bmiValue.textContent = bmi.toFixed(1);

                    if (bmi < 18.5) {
                        bmiCategory.textContent = 'Underweight';
                        bmiCategory.className = 'text-sm text-blue-600 dark:text-blue-400';
                    } else if (bmi < 25) {
                        bmiCategory.textContent = 'Normal';
                        bmiCategory.className = 'text-sm text-green-600 dark:text-green-400';
                    } else if (bmi < 30) {
                        bmiCategory.textContent = 'Overweight';
                        bmiCategory.className = 'text-sm text-yellow-600 dark:text-yellow-400';
                    } else {
                        bmiCategory.textContent = 'Obese';
                        bmiCategory.className = 'text-sm text-red-600 dark:text-red-400';
                    }
                } else {
                    bmiValue.textContent = '--';
                    bmiCategory.textContent = '--';
                    bmiCategory.className = 'text-sm text-gray-600 dark:text-gray-400';
                }
            }

            // Section Toggle Functions
            function toggleSection(sectionId) {
                const content = document.getElementById(`${sectionId}-content`);
                const toggle = document.getElementById(`${sectionId}-toggle`);

                if (content && toggle) {
                    if (content.style.maxHeight && content.style.maxHeight !== 'auto') {
                        content.style.maxHeight = 'auto';
                        toggle.classList.remove('fa-chevron-down');
                        toggle.classList.add('fa-chevron-up');
                    } else {
                        content.style.maxHeight = '0px';
                        toggle.classList.remove('fa-chevron-up');
                        toggle.classList.add('fa-chevron-down');
                    }
                }
            }

            function setupAutoSave() {
                const form = document.querySelector('form');
                if (!form) return;

                autoSaveInterval = setInterval(() => {
                    // This is a placeholder for actual auto-save logic (e.g., to localStorage or backend)
                    // For now, it just shows a notification.
                    // console.log('Form data auto-saved (simulated).');
                    // showNotification('Progress auto-saved!', 'info', 2000); // Short notification
                }, 30000); // Auto-save every 30 seconds
            }

            function setupKeyboardShortcuts() {
                document.addEventListener('keydown', function(event) {
                    if (event.ctrlKey) {
                        switch (event.key) {
                            case 's': // Ctrl+S
                                event.preventDefault();
                                document.querySelector('button[name="status"][value="draft"]').click();
                                break;
                            case 'Enter': // Ctrl+Enter
                                event.preventDefault();
                                document.querySelector('button[name="status"][value="completed"]').click();
                                break;
                            case 'p': // Ctrl+P
                                event.preventDefault();
                                previewReport();
                                break;
                        }
                    }
                });
            }

            function previewReport() {
                // This function would ideally gather form data and show a formatted preview.
                // For now, it's a placeholder.
                showNotification('Report preview functionality is under development.', 'info');
                // Example: Gather data and open a new window or modal with the preview
                // const formData = new FormData(document.querySelector('form'));
                // let previewContent = '<h1>Report Preview</h1>';
                // formData.forEach((value, key) => { previewContent += `<p><strong>${key}:</strong> ${value}</p>`; });
                // const previewWindow = window.open('', '_blank');
                // previewWindow.document.write(previewContent);
                // previewWindow.document.close();
            }

            // Quick Tips Modal
            function showQuickTips() {
                document.getElementById('quick-tips-modal').classList.remove('hidden');
            }

            function hideQuickTips() {
                document.getElementById('quick-tips-modal').classList.add('hidden');
            }

            // Form Error Display
            function showFormErrors(errors) {
                const errorSection = document.getElementById('form-errors-section');
                const errorList = document.getElementById('form-errors-list');

                errorList.innerHTML = ''; // Clear previous errors
                if (typeof errors === 'object' && errors !== null) {
                    Object.values(errors).forEach(errorMessages => {
                        errorMessages.forEach(message => {
                            const li = document.createElement('li');
                            li.textContent = message;
                            errorList.appendChild(li);
                        });
                    });
                } else if (typeof errors === 'string') {
                    const li = document.createElement('li');
                    li.textContent = errors;
                    errorList.appendChild(li);
                } else {
                    const li = document.createElement('li');
                    li.textContent = 'An unknown error occurred.';
                    errorList.appendChild(li);
                }
                errorSection.classList.remove('hidden');
                errorSection.scrollIntoView({
                    behavior: 'smooth'
                });
            }

            function hideFormErrors() {
                document.getElementById('form-errors-section').classList.add('hidden');
            }

            // Utility for notifications (toast-like messages)
            function showNotification(message, type = 'info', duration = 3000) {
                const container = document.createElement('div');
                container.className =
                    `fixed top-5 right-5 p-4 rounded-lg shadow-xl text-white z-[100] transition-all duration-300 transform translate-x-full`;

                let bgColor, iconClass;
                switch (type) {
                    case 'success':
                        bgColor = 'bg-green-500';
                        iconClass = 'fas fa-check-circle';
                        break;
                    case 'error':
                        bgColor = 'bg-red-500';
                        iconClass = 'fas fa-times-circle';
                        break;
                    case 'warning':
                        bgColor = 'bg-yellow-500';
                        iconClass = 'fas fa-exclamation-triangle';
                        break;
                    default:
                        bgColor = 'bg-blue-500';
                        iconClass = 'fas fa-info-circle';
                        break;
                }
                container.classList.add(bgColor);

                container.innerHTML = `
        <div class="flex items-center">
            <i class="${iconClass} mr-3 text-xl"></i>
            <span>${message}</span>
        </div>
    `;

                document.body.appendChild(container);

                // Animate in
                setTimeout(() => {
                    container.classList.remove('translate-x-full');
                    container.classList.add('translate-x-0');
                }, 100);

                // Animate out and remove
                setTimeout(() => {
                    container.classList.add('translate-x-full');
                    container.classList.remove('translate-x-0');
                    setTimeout(() => {
                        container.remove();
                    }, 300);
                }, duration);
            }

            // Enhanced Form Functions
            function updateFormProgress() {
                const requiredFields = [
                    'title', 'consultation_date', 'report_type',
                    'vital_signs[blood_pressure]', 'vital_signs[temperature]',
                    'vital_signs[heart_rate]', 'vital_signs[weight]'
                ];

                let completed = 0;
                requiredFields.forEach(field => {
                    const element = document.querySelector(`[name="${field}"]`);
                    if (element && element.value.trim() !== '') {
                        completed++;
                    }
                });

                const percentage = Math.round((completed / requiredFields.length) * 100);
                const progressPercentage = document.getElementById('progress-percentage');
                const progressBar = document.getElementById('progress-bar');

                if (progressPercentage) {
                    progressPercentage.textContent = percentage + '%';
                }

                if (progressBar) {
                    progressBar.style.width = percentage + '%';
                }

                // Update section status
                updateSectionStatus('patient', ['title', 'consultation_date', 'report_type']);
                updateSectionStatus('vitals', [
                    'vital_signs[blood_pressure]', 'vital_signs[temperature]',
                    'vital_signs[heart_rate]', 'vital_signs[weight]',
                    'vital_signs[respiratory_rate]', 'vital_signs[oxygen_saturation]',
                    'vital_signs[height]'
                ]);
                updateSectionStatus('assessment', ['chief_complaint', 'physical_examination']);
                updateSectionStatus('diagnosis', ['assessment_diagnosis', 'treatment_plan']);

                // Update vitals completion specifically
                updateVitalsCompletion();
            }

            function updateVitalsCompletion() {
                const vitalFields = [
                    'vital_signs[blood_pressure]', 'vital_signs[temperature]',
                    'vital_signs[heart_rate]', 'vital_signs[weight]',
                    'vital_signs[respiratory_rate]', 'vital_signs[oxygen_saturation]',
                    'vital_signs[height]'
                ];

                let completed = 0;
                vitalFields.forEach(field => {
                    const element = document.querySelector(`[name="${field}"]`);
                    if (element && element.value.trim() !== '') {
                        completed++;
                    }
                });

                const vitalsCompletion = document.getElementById('vitals-completion');
                const vitalsProgress = document.getElementById('vitals-progress');

                if (vitalsCompletion) {
                    vitalsCompletion.textContent = `${completed}/${vitalFields.length} Complete`;
                }

                if (vitalsProgress) {
                    const percentage = Math.round((completed / vitalFields.length) * 100);
                    vitalsProgress.style.width = percentage + '%';
                }
            }

            function updateSectionStatus(section, fields) {
                let completed = 0;
                fields.forEach(field => {
                    const element = document.querySelector(`[name="${field}"]`);
                    if (element && element.value.trim() !== '') {
                        completed++;
                    }
                });

                const statusElement = document.getElementById(`${section}-status`);
                if (statusElement) {
                    statusElement.className = 'field-status ';
                    if (completed === fields.length) {
                        statusElement.className += 'field-complete';
                    } else if (completed > 0) {
                        statusElement.className += 'field-partial';
                    } else {
                        statusElement.className += 'field-empty';
                    }
                }
            }

            // Quick Actions
            function applyPreset(presetType) {
                switch (presetType) {
                    case 'routine-checkup':
                        document.querySelector('[name="chief_complaint"]').value =
                            'Routine health examination and wellness check';
                        fillNormalVitals();
                        break;
                    case 'follow-up':
                        document.querySelector('[name="chief_complaint"]').value =
                            'Follow-up appointment for previous consultation';
                        break;
                    case 'emergency':
                        document.querySelector('[name="chief_complaint"]').value =
                            'Emergency consultation - urgent medical concern';
                        break;
                }
                updateFormProgress();
                showNotification(`Applied ${presetType.replace('-', ' ')} preset`, 'success');
            }

            function fillNormalVitals() {
                document.querySelector('[name="vital_signs[blood_pressure]"]').value = '120/80';
                document.querySelector('[name="vital_signs[temperature]"]').value = '98.6°F';
                document.querySelector('[name="vital_signs[heart_rate]"]').value = '72';
                document.querySelector('[name="vital_signs[respiratory_rate]"]').value = '16';
                document.querySelector('[name="vital_signs[oxygen_saturation]"]').value = '98%';
                updateFormProgress();
                updateVitalsCompletion();
                calculateBMI();
                showNotification('Normal vital signs applied', 'success');
            }

            function fillPediatricVitals() {
                document.querySelector('[name="vital_signs[blood_pressure]"]').value = '95/60';
                document.querySelector('[name="vital_signs[temperature]"]').value = '98.2°F';
                document.querySelector('[name="vital_signs[heart_rate]"]').value = '100';
                document.querySelector('[name="vital_signs[respiratory_rate]"]').value = '24';
                document.querySelector('[name="vital_signs[oxygen_saturation]"]').value = '99%';
                updateFormProgress();
                updateVitalsCompletion();
                calculateBMI();
                showNotification('Pediatric vital signs applied', 'success');
            }

            function fillGeriatricVitals() {
                document.querySelector('[name="vital_signs[blood_pressure]"]').value = '130/85';
                document.querySelector('[name="vital_signs[temperature]"]').value = '98.4°F';
                document.querySelector('[name="vital_signs[heart_rate]"]').value = '68';
                document.querySelector('[name="vital_signs[respiratory_rate]"]').value = '14';
                document.querySelector('[name="vital_signs[oxygen_saturation]"]').value = '96%';
                updateFormProgress();
                updateVitalsCompletion();
                calculateBMI();
                showNotification('Geriatric vital signs applied', 'success');
            }

            function calculateBMI() {
                const heightField = document.querySelector('[name="vital_signs[height]"]');
                const weightField = document.querySelector('[name="vital_signs[weight]"]');
                const bmiSection = document.getElementById('bmi-section');
                const bmiResult = document.getElementById('bmi-result');

                if (heightField && weightField && heightField.value && weightField.value) {
                    const height = parseFloat(heightField.value);
                    const weight = parseFloat(weightField.value);

                    if (height > 0 && weight > 0) {
                        const heightInMeters = height / 100;
                        const bmi = weight / (heightInMeters * heightInMeters);

                        let category = '';
                        let color = '';

                        if (bmi < 18.5) {
                            category = 'Underweight';
                            color = 'text-blue-600 dark:text-blue-400';
                        } else if (bmi < 25) {
                            category = 'Normal';
                            color = 'text-green-600 dark:text-green-400';
                        } else if (bmi < 30) {
                            category = 'Overweight';
                            color = 'text-yellow-600 dark:text-yellow-400';
                        } else {
                            category = 'Obese';
                            color = 'text-red-600 dark:text-red-400';
                        }

                        if (bmiResult) {
                            bmiResult.innerHTML = `<span class="${color}">${bmi.toFixed(1)} - ${category}</span>`;
                        }

                        if (bmiSection) {
                            bmiSection.classList.remove('hidden');
                        }
                    }
                }
            }

            function clearForm() {
                if (confirm('Are you sure you want to clear all form data? This action cannot be undone.')) {
                    document.getElementById('medical-report-form').reset();
                    updateFormProgress();
                    updateVitalsCompletion();
                    document.getElementById('bmi-section').classList.add('hidden');
                    showNotification('Form cleared successfully', 'info');
                }
            }

 

            function toggleAllSections() {
                const sections = ['patient-info', 'vital-signs', 'clinical-assessment', 'diagnosis-treatment',
                    'follow-up-notes'];
                sections.forEach(section => {
                    const content = document.getElementById(`${section}-content`);
                    if (content) {
                        content.style.maxHeight = '1000px';
                        const toggle = document.getElementById(`${section}-toggle`);
                        if (toggle) toggle.className = 'fas fa-chevron-up';
                    }
                });
            }

            // Voice Input
            let recognition;
            let currentVoiceField;

            function toggleVoiceInput() {
                if (!('webkitSpeechRecognition' in window) && !('SpeechRecognition' in window)) {
                    showNotification('Speech recognition not supported in this browser', 'error');
                    return;
                }

                if (!recognition) {
                    const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
                    recognition = new SpeechRecognition();
                    recognition.continuous = true;
                    recognition.interimResults = true;
                    recognition.lang = 'en-US';

                    recognition.onstart = () => {
                        document.getElementById('voice-input-btn').innerHTML = '<i class="fas fa-stop mr-2"></i>Stop Voice';
                        document.getElementById('voice-input-btn').classList.add('speech-indicator');
                        showNotification('Voice input started. Click on any text field and speak.', 'info');
                    };

                    recognition.onend = () => {
                        document.getElementById('voice-input-btn').innerHTML =
                            '<i class="fas fa-microphone mr-2"></i>Voice Input';
                        document.getElementById('voice-input-btn').classList.remove('speech-indicator');
                    };

                    recognition.onresult = (event) => {
                        let finalTranscript = '';
                        for (let i = event.resultIndex; i < event.results.length; i++) {
                            if (event.results[i].isFinal) {
                                finalTranscript += event.results[i][0].transcript;
                            }
                        }

                        if (finalTranscript && document.activeElement &&
                            (document.activeElement.tagName === 'INPUT' || document.activeElement.tagName === 'TEXTAREA')) {
                            document.activeElement.value += finalTranscript;
                            updateFormProgress();
                        }
                    };

                    recognition.onerror = (event) => {
                        showNotification('Voice recognition error: ' + event.error, 'error');
                    };
                }

                if (recognition && recognition.state !== 'listening') {
                    recognition.start();
                } else if (recognition) {
                    recognition.stop();
                }
            }

            // Quick Tips Modal
            function showQuickTips() {
                document.getElementById('quick-tips-modal').classList.remove('hidden');
            }

            function hideQuickTips() {
                document.getElementById('quick-tips-modal').classList.add('hidden');
            }

            // Event Listeners
            document.addEventListener('input', function(e) {
                if (e.target.name && e.target.name.includes('vital_signs[')) {
                    calculateBMI();
                    updateVitalsCompletion();
                }
                updateFormProgress();
            });

            document.addEventListener('change', function(e) {
                updateFormProgress();
            });

            // Keyboard shortcuts
            document.addEventListener('keydown', function(e) {
                // Ctrl+S to save
                if (e.ctrlKey && e.key === 's') {
                    e.preventDefault();
                    document.getElementById('medical-report-form').submit();
                }

                // Alt+V for voice input
                if (e.altKey && e.key === 'v') {
                    e.preventDefault();
                    currentVoiceField = document.activeElement;
                    toggleVoiceInput();
                }

                // Alt+T for tips
                if (e.altKey && e.key === 't') {
                    e.preventDefault();
                    showQuickTips();
                }
            });

            // Initialize page
            document.addEventListener('DOMContentLoaded', function() {
                // Add event listeners to all form fields for real-time progress tracking
                const allInputs = document.querySelectorAll('input, select, textarea');
                allInputs.forEach(input => {
                    if (input.name) {
                        input.addEventListener('input', updateFormProgress);
                        input.addEventListener('change', updateFormProgress);
                    }
                });

                // Initial progress calculation
                updateFormProgress();
                updateVitalsCompletion();
                calculateBMI();
            });

            // Additional Quick Action Functions
            function toggleAllSections() {
                const sections = ['patient-info', 'vital-signs', 'clinical-assessment', 'diagnosis-treatment',
                    'follow-up-notes'];
                const firstSection = document.getElementById('patient-info-content');
                const isCollapsed = firstSection && firstSection.style.maxHeight === '0px';

                sections.forEach(sectionId => {
                    const content = document.getElementById(`${sectionId}-content`);
                    const toggle = document.getElementById(`${sectionId}-toggle`);

                    if (content && toggle) {
                        if (isCollapsed) {
                            content.style.maxHeight = '1000px';
                            toggle.classList.remove('fa-chevron-down');
                            toggle.classList.add('fa-chevron-up');
                        } else {
                            content.style.maxHeight = '0px';
                            toggle.classList.remove('fa-chevron-up');
                            toggle.classList.add('fa-chevron-down');
                        }
                    }
                });

                showNotification(isCollapsed ? 'All sections expanded' : 'All sections collapsed', 'info');
            }

            function clearForm() {
                if (confirm('Are you sure you want to clear all form fields? This action cannot be undone.')) {
                    clearAllFields();
                }
            }

            // Voice Input Functions (placeholder)
            function toggleVoiceInput() {
                showNotification('Voice input feature is under development', 'info');
            }

            function stopVoiceInput() {
                const voiceModal = document.getElementById('voice-modal');
                if (voiceModal) {
                    voiceModal.classList.add('hidden');
                }
            }

            // Preset functions for vital signs
            function fillPediatricVitals() {
                const fields = {
                    'vital_signs[blood_pressure]': '100/60',
                    'vital_signs[temperature]': '98.2',
                    'vital_signs[heart_rate]': '110',
                    'vital_signs[respiratory_rate]': '22',
                    'vital_signs[oxygen_saturation]': '99'
                };

                Object.entries(fields).forEach(([name, value]) => {
                    const field = document.querySelector(`[name="${name}"]`);
                    if (field) field.value = value;
                });

                calculateBMI();
                updateFormProgress();
                showNotification('Pediatric vital signs filled', 'success');
            }

            function fillGeriatricVitals() {
                const fields = {
                    'vital_signs[blood_pressure]': '140/80',
                    'vital_signs[temperature]': '98.0',
                    'vital_signs[heart_rate]': '68',
                    'vital_signs[respiratory_rate]': '14',
                    'vital_signs[oxygen_saturation]': '96'
                };

                Object.entries(fields).forEach(([name, value]) => {
                    const field = document.querySelector(`[name="${name}"]`);
                    if (field) field.value = value;
                });

                calculateBMI();
                updateFormProgress();
                showNotification('Geriatric vital signs filled', 'success');
            }
        </script>
    @endpush

@endsection
