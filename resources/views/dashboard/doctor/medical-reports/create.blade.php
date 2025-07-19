@extends('layouts.doctor')

@section('title', 'Create Medical Report - Doctor Dashboard')

@push('styles')
    <style>
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

        .vital-input:focus {
            transform: scale(1.02);
            transition: transform 0.2s ease;
        }

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
            @apply border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-800 rounded-lg shadow-xl;
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
            animation: voiceWave 1.5s infinite;
        }

        @keyframes voiceWave {

            0%,
            100% {
                transform: scaleY(1);
            }

            50% {
                transform: scaleY(1.5);
            }
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
                            <div class="bg-gradient-to-r from-blue-600 to-purple-600 p-3 rounded-xl">
                                <i class="fas fa-file-medical text-white text-2xl"></i>
                            </div>
                            <div>
                                <h1 class="text-4xl font-bold bg-gray-300 bg-clip-text text-transparent">
                                    Create Medical Report
                                </h1>
                                <p class="mt-2 text-gray-600 dark:text-gray-400 text-lg">Document patient consultation with
                                    enhanced quick actions</p>
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
                        <div id="progress-bar" class="bg-gray-300 h-2 rounded-full transition-all duration-300"
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

            <!-- Form Submission Error Logging Section -->
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
            <form method="POST" action="{{ route('doctor.medical-reports.store') }}" class="space-y-8"
                id="medical-report-form">
                @csrf

                <!-- Patient Quick Lookup & Basic Information -->
                <div
                    class="bg-white dark:bg-gray-800 shadow-xl rounded-2xl border border-gray-100 dark:border-gray-700 form-section-transition">
                    <div
                        class="px-8 py-6 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-blue-50 to-purple-50 dark:from-gray-700 dark:to-gray-600 rounded-t-2xl">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <div class="bg-blue-500 p-2 rounded-lg">
                                    <i class="fas fa-user-md text-white"></i>
                                </div>
                                <h3 class="text-xl font-semibold text-gray-900 dark:text-white">Patient Information & Quick
                                    Lookup</h3>
                                <button type="button" onclick="toggleSection('patient-info')"
                                    class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                                    <i id="patient-info-toggle" class="fas fa-chevron-up"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div id="patient-info-content" class="p-8 space-y-8 section-collapse" style="max-height: auto;">
                        <!-- Enhanced Patient Quick Search -->
                        <div
                            class="bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-gray-700 dark:to-gray-600 p-6 rounded-xl border border-blue-200 dark:border-gray-600">
                            <div class="flex items-center space-x-3 mb-4">
                                <i class="fas fa-search text-blue-600 dark:text-blue-400"></i>
                                <h4 class="text-lg font-medium text-gray-900 dark:text-white">Quick Patient Search</h4>
                            </div>
                            <div class="relative">
                                <input type="text" id="patient-search"
                                    placeholder="Search by name, email, phone, or patient ID..."
                                    class="w-full pl-12 pr-4 py-4 border-2 border-blue-300 dark:border-gray-600 rounded-xl shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white text-lg transition-all duration-200">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <i class="fas fa-search text-blue-500 text-lg"></i>
                                </div>
                                <div class="absolute inset-y-0 right-0 pr-4 flex items-center">
                                    <div id="search-loading" class="hidden">
                                        <i class="fas fa-spinner fa-spin text-blue-500"></i>
                                    </div>
                                </div>
                            </div>

                            <!-- Search Results Dropdown -->
                            <div id="patient-search-results"
                                class="hidden absolute z-50 w-full mt-2 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-600 rounded-xl shadow-2xl patient-search-dropdown">
                                <!-- Results will be populated here -->
                            </div>
                        </div>

                        <!-- Selected Patient Preview Card -->
                        <div id="selected-patient-card" class="hidden patient-card-preview">
                            <div
                                class="bg-gradient-to-r from-green-50 to-emerald-50 dark:from-green-900/20 dark:to-emerald-900/20 border-2 border-green-200 dark:border-green-700 rounded-xl p-6">
                                <div class="flex items-center justify-between mb-4">
                                    <div class="flex items-center space-x-3">
                                        <div class="bg-green-500 p-2 rounded-lg">
                                            <i class="fas fa-user-check text-white"></i>
                                        </div>
                                        <h4 class="text-lg font-semibold text-green-800 dark:text-green-300">Selected
                                            Patient</h4>
                                    </div>
                                    <button type="button" onclick="clearSelectedPatient()"
                                        class="text-red-500 hover:text-red-700 p-2 rounded-lg hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                                <div id="patient-preview-content" class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                                    <!-- Patient details will be populated here -->
                                </div>
                            </div>
                        </div> <!-- Traditional Patient Selection (Fallback) -->
                        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                            <div>
                                <label for="patient_id"
                                    class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                    <i class="fas fa-user mr-2 text-blue-500"></i>Select Patient
                                </label>
                                <select name="patient_id" id="patient_id" required onchange="updateFormProgress()"
                                    class="w-full border-2 @error('patient_id') border-red-500 dark:border-red-400 @else border-gray-300 dark:border-gray-600 @enderror rounded-xl shadow-sm focus:ring-2 focus:ring-purple-500 focus:border-purple-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white py-3 px-4 transition-all duration-200">
                                    <option value="">Choose from dropdown...</option>
                                    @foreach ($patients as $patient)
                                        <option value="{{ $patient->id }}" data-patient='@json($patient)'
                                            {{ old('patient_id') == $patient->id ? 'selected' : '' }}>
                                            {{ $patient->name }} ({{ $patient->email }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('patient_id')
                                    <div class="mt-2 flex items-center text-red-600 dark:text-red-400 text-sm">
                                        <i class="fas fa-exclamation-circle mr-2"></i>
                                        <span>{{ $message }}</span>
                                    </div>
                                @enderror
                            </div>

                            <div>
                                <label for="title"
                                    class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                    <i class="fas fa-heading mr-2 text-green-500"></i>Report Title <span
                                        class="text-red-500">*</span>
                                </label>
                                <input type="text" name="title" id="title" value="{{ old('title') }}" required
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

                            <div>
                                <label for="consultation_date"
                                    class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                    <i class="fas fa-calendar mr-2 text-purple-500"></i>Consultation Date
                                </label>
                                <input type="date" name="consultation_date" id="consultation_date"
                                    value="{{ old('consultation_date') }}" required onchange="updateFormProgress()"
                                    class="w-full border-2 @error('consultation_date') border-red-500 dark:border-red-400 @else border-gray-300 dark:border-gray-600 @enderror rounded-xl shadow-sm focus:ring-2 focus:ring-purple-500 focus:border-purple-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white py-3 px-4 transition-all duration-200">
                                @error('consultation_date')
                                    <div class="mt-2 flex items-center text-red-600 dark:text-red-400 text-sm">
                                        <i class="fas fa-exclamation-circle mr-2"></i>
                                        <span>{{ $message }}</span>
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <div>
                            <label for="report_type"
                                class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                <i class="fas fa-file-alt mr-2 text-indigo-500"></i>Report Type
                            </label>
                            <select name="report_type" id="report_type" required onchange="updateFormProgress()"
                                class="w-full border-2 @error('report_type') border-red-500 dark:border-red-400 @else border-gray-300 dark:border-gray-600 @enderror rounded-xl shadow-sm focus:ring-2 focus:ring-purple-500 focus:border-purple-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white py-3 px-4 transition-all duration-200">
                                <option value="">Select report type</option>
                                <option value="Consultation" {{ old('report_type') == 'Consultation' ? 'selected' : '' }}>
                                    <i class="fas fa-stethoscope"></i> Initial Consultation
                                </option>
                                <option value="Follow-up" {{ old('report_type') == 'Follow-up' ? 'selected' : '' }}>
                                    Follow-up Visit</option>
                                <option value="Diagnosis" {{ old('report_type') == 'Diagnosis' ? 'selected' : '' }}>
                                    Diagnosis Report</option>
                                <option value="Treatment" {{ old('report_type') == 'Treatment' ? 'selected' : '' }}>
                                    Treatment Report</option>
                                <option value="Prescription" {{ old('report_type') == 'Prescription' ? 'selected' : '' }}>
                                    Prescription Report</option>
                                <option value="Emergency" {{ old('report_type') == 'Emergency' ? 'selected' : '' }}>
                                    Emergency Visit</option>
                                <option value="Other" {{ old('report_type') == 'Other' ? 'selected' : '' }}>Other
                                </option>
                            </select>
                            @error('report_type')
                                <div class="mt-2 flex items-center text-red-600 dark:text-red-400 text-sm">
                                    <i class="fas fa-exclamation-circle mr-2"></i>
                                    <span>{{ $message }}</span>
                                </div>
                            @enderror
                        </div>
                    </div>
                </div> <!-- Enhanced Vital Signs -->
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
                                    <i class="fas fa-magic mr-2"></i>Normal Values
                                </button>
                                <button type="button" onclick="fillPediatricVitals()"
                                    class="bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 dark:from-green-600 dark:to-emerald-700 dark:hover:from-green-700 dark:hover:to-emerald-800 text-white px-3 py-1.5 rounded-lg text-xs font-medium transition-all duration-200 border border-green-400 dark:border-green-500 hover:border-green-500 dark:hover:border-green-600 shadow-md hover:shadow-lg">
                                    <i class="fas fa-child mr-2"></i>Pediatric
                                </button>
                                <button type="button" onclick="fillGeriatricVitals()"
                                    class="bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 dark:from-green-600 dark:to-emerald-700 dark:hover:from-green-700 dark:hover:to-emerald-800 text-white px-3 py-1.5 rounded-lg text-xs font-medium transition-all duration-200 border border-green-400 dark:border-green-500 hover:border-green-500 dark:hover:border-green-600 shadow-md hover:shadow-lg">
                                    <i class="fas fa-user-clock mr-2"></i>Geriatric
                                </button>
                                <button type="button" onclick="startVitalCapture()"
                                    class="bg-gradient-to-r from-blue-500 to-cyan-600 hover:from-blue-600 hover:to-cyan-700 dark:from-blue-600 dark:to-cyan-700 dark:hover:from-blue-700 dark:hover:to-cyan-800 text-white px-3 py-1.5 rounded-lg text-xs font-medium transition-all duration-200 border border-blue-400 dark:border-blue-500 hover:border-blue-500 dark:hover:border-blue-600 shadow-md hover:shadow-lg">
                                    <i class="fas fa-camera mr-1"></i>Quick Capture
                                </button>
                            </div>
                        </div>
                    </div>
                    <div id="vital-signs-content" class="p-8 section-collapse" style="max-height: auto;">
                        <!-- Vital Signs Status Indicator -->
                        <div class="mb-6 bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Completion
                                    Status:</span>
                                <div class="flex items-center space-x-4">
                                    <span id="vitals-completion" class="text-sm text-gray-600 dark:text-gray-400">0/7
                                        Complete</span>
                                    <div class="w-24 bg-gray-200 dark:bg-gray-600 rounded-full h-2">
                                        <div id="vitals-progress"
                                            class="bg-green-500 h-2 rounded-full transition-all duration-300"
                                            style="width: 0%"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6" id="vital-signs">
                            <div
                                class="bg-gradient-to-br from-red-50 to-pink-50 dark:from-red-900/20 dark:to-pink-900/20 p-4 rounded-xl border border-red-200 dark:border-red-700 relative">
                                <label class="block text-sm font-semibold text-red-700 dark:text-red-300 mb-2">
                                    <i class="fas fa-tint mr-2"></i>Blood Pressure
                                    <span class="text-xs text-gray-500 dark:text-gray-400">(mmHg)</span>
                                </label>
                                <div class="relative">
                                    <input type="text" name="vital_signs[blood_pressure]" placeholder="120/80"
                                        value="{{ old('vital_signs.blood_pressure') }}"
                                        onchange="validateVitalRange(this, 'bp')" oninput="updateFormProgress()"
                                        class="vital-input w-full border-2 @error('vital_signs.blood_pressure') border-red-500 dark:border-red-400 @else border-red-300 dark:border-red-600 @enderror rounded-lg shadow-sm focus:ring-2 focus:ring-red-500 focus:border-red-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white py-3 px-4">
                                    <button type="button" onclick="showVitalReference('bp')"
                                        class="absolute right-2 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                        <i class="fas fa-info-circle text-sm"></i>
                                    </button>
                                </div>
                                <div id="bp-status" class="mt-2 text-xs text-gray-500 dark:text-gray-400"></div>
                                @error('vital_signs.blood_pressure')
                                    <div class="mt-1 text-red-600 dark:text-red-400 text-xs">{{ $message }}</div>
                                @enderror
                            </div>

                            <div
                                class="bg-gradient-to-br from-orange-50 to-yellow-50 dark:from-orange-900/20 dark:to-yellow-900/20 p-4 rounded-xl border border-orange-200 dark:border-orange-700 relative">
                                <label class="block text-sm font-semibold text-orange-700 dark:text-orange-300 mb-2">
                                    <i class="fas fa-thermometer-half mr-2"></i>Temperature
                                    <span class="text-xs text-gray-500 dark:text-gray-400">(°F/°C)</span>
                                </label>
                                <div class="relative">
                                    <input type="text" name="vital_signs[temperature]" placeholder="98.6°F"
                                        value="{{ old('vital_signs.temperature') }}"
                                        onchange="validateVitalRange(this, 'temp')" oninput="updateFormProgress()"
                                        class="vital-input w-full border-2 @error('vital_signs.temperature') border-red-500 dark:border-red-400 @else border-orange-300 dark:border-orange-600 @enderror rounded-lg shadow-sm focus:ring-2 focus:ring-orange-500 focus:border-orange-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white py-3 px-4">
                                    <button type="button" onclick="convertTemperature(this)"
                                        class="absolute right-2 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                        <i class="fas fa-exchange-alt text-sm"></i>
                                    </button>
                                </div>
                                <div id="temp-status" class="mt-2 text-xs text-gray-500 dark:text-gray-400"></div>
                                @error('vital_signs.temperature')
                                    <div class="mt-1 text-red-600 dark:text-red-400 text-xs">{{ $message }}</div>
                                @enderror
                            </div>

                            <div
                                class="bg-gradient-to-br from-blue-50 to-cyan-50 dark:from-blue-900/20 dark:to-cyan-900/20 p-4 rounded-xl border border-blue-200 dark:border-blue-700 relative">
                                <label class="block text-sm font-semibold text-blue-700 dark:text-blue-300 mb-2">
                                    <i class="fas fa-heartbeat mr-2"></i>Heart Rate
                                    <span class="text-xs text-gray-500 dark:text-gray-400">(bpm)</span>
                                </label>
                                <div class="relative">
                                    <input type="text" name="vital_signs[heart_rate]" placeholder="70"
                                        value="{{ old('vital_signs.heart_rate') }}"
                                        onchange="validateVitalRange(this, 'hr')" oninput="updateFormProgress()"
                                        class="vital-input w-full border-2 @error('vital_signs.heart_rate') border-red-500 dark:border-red-400 @else border-blue-300 dark:border-blue-600 @enderror rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white py-3 px-4">
                                    <button type="button" onclick="calculateTargetHR()"
                                        class="absolute right-2 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                        <i class="fas fa-calculator text-sm"></i>
                                    </button>
                                </div>
                                <div id="hr-status" class="mt-2 text-xs text-gray-500 dark:text-gray-400"></div>
                                @error('vital_signs.heart_rate')
                                    <div class="mt-1 text-red-600 dark:text-red-400 text-xs">{{ $message }}</div>
                                @enderror
                            </div>

                            <div
                                class="bg-gradient-to-br from-purple-50 to-indigo-50 dark:from-purple-900/20 dark:to-indigo-900/20 p-4 rounded-xl border border-purple-200 dark:border-purple-700 relative">
                                <label class="block text-sm font-semibold text-purple-700 dark:text-purple-300 mb-2">
                                    <i class="fas fa-weight mr-2"></i>Weight
                                    <span class="text-xs text-gray-500 dark:text-gray-400">(kg/lbs)</span>
                                </label>
                                <div class="relative">
                                    <input type="text" name="vital_signs[weight]" placeholder="70 kg"
                                        value="{{ old('vital_signs.weight') }}" onchange="calculateBMI()"
                                        oninput="updateFormProgress()"
                                        class="vital-input w-full border-2 @error('vital_signs.weight') border-red-500 dark:border-red-400 @else border-purple-300 dark:border-purple-600 @enderror rounded-lg shadow-sm focus:ring-2 focus:ring-purple-500 focus:border-purple-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white py-3 px-4">
                                    <button type="button" onclick="convertWeight(this)"
                                        class="absolute right-2 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                        <i class="fas fa-exchange-alt text-sm"></i>
                                    </button>
                                </div>
                                <div id="weight-status" class="mt-2 text-xs text-gray-500 dark:text-gray-400"></div>
                                @error('vital_signs.weight')
                                    <div class="mt-1 text-red-600 dark:text-red-400 text-xs">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Additional Vital Signs -->
                        <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div class="relative">
                                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                    <i class="fas fa-lungs mr-2 text-teal-500"></i>Respiratory Rate
                                    <span class="text-xs text-gray-500 dark:text-gray-400">(breaths/min)</span>
                                </label>
                                <div class="relative">
                                    <input type="text" name="vital_signs[respiratory_rate]" placeholder="16"
                                        value="{{ old('vital_signs.respiratory_rate') }}"
                                        onchange="validateVitalRange(this, 'rr')" oninput="updateFormProgress()"
                                        class="w-full border-2 @error('vital_signs.respiratory_rate') border-red-500 dark:border-red-400 @else border-gray-300 dark:border-gray-600 @enderror rounded-lg shadow-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white py-3 px-4">
                                    <button type="button" onclick="startRRCounter()"
                                        class="absolute right-2 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                        <i class="fas fa-stopwatch text-sm"></i>
                                    </button>
                                </div>
                                <div id="rr-status" class="mt-2 text-xs text-gray-500 dark:text-gray-400"></div>
                                @error('vital_signs.respiratory_rate')
                                    <div class="mt-2 flex items-center text-red-600 dark:text-red-400 text-sm">
                                        <i class="fas fa-exclamation-circle mr-2"></i>
                                        <span>{{ $message }}</span>
                                    </div>
                                @enderror
                            </div>

                            <div class="relative">
                                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                    <i class="fas fa-percentage mr-2 text-blue-500"></i>Oxygen Saturation
                                    <span class="text-xs text-gray-500 dark:text-gray-400">(SpO2 %)</span>
                                </label>
                                <div class="relative">
                                    <input type="text" name="vital_signs[oxygen_saturation]" placeholder="98"
                                        value="{{ old('vital_signs.oxygen_saturation') }}"
                                        onchange="validateVitalRange(this, 'spo2')" oninput="updateFormProgress()"
                                        class="w-full border-2 @error('vital_signs.oxygen_saturation') border-red-500 dark:border-red-400 @else border-gray-300 dark:border-gray-600 @enderror rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white py-3 px-4">
                                    <span
                                        class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 text-sm">%</span>
                                </div>
                                <div id="spo2-status" class="mt-2 text-xs text-gray-500 dark:text-gray-400"></div>
                                @error('vital_signs.oxygen_saturation')
                                    <div class="mt-2 flex items-center text-red-600 dark:text-red-400 text-sm">
                                        <i class="fas fa-exclamation-circle mr-2"></i>
                                        <span>{{ $message }}</span>
                                    </div>
                                @enderror
                            </div>

                            <div class="relative">
                                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                    <i class="fas fa-ruler-vertical mr-2 text-green-500"></i>Height
                                    <span class="text-xs text-gray-500 dark:text-gray-400">(cm/ft)</span>
                                </label>
                                <div class="relative">
                                    <input type="text" name="vital_signs[height]" placeholder="170 cm"
                                        value="{{ old('vital_signs.height') }}" onchange="calculateBMI()"
                                        oninput="updateFormProgress()"
                                        class="w-full border-2 @error('vital_signs.height') border-red-500 dark:border-red-400 @else border-gray-300 dark:border-gray-600 @enderror rounded-lg shadow-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white py-3 px-4">
                                    <button type="button" onclick="convertHeight(this)"
                                        class="absolute right-2 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                        <i class="fas fa-exchange-alt text-sm"></i>
                                    </button>
                                </div>
                                <div id="height-status" class="mt-2 text-xs text-gray-500 dark:text-gray-400"></div>
                                @error('vital_signs.height')
                                    <div class="mt-2 flex items-center text-red-600 dark:text-red-400 text-sm">
                                        <i class="fas fa-exclamation-circle mr-2"></i>
                                        <span>{{ $message }}</span>
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <!-- BMI Calculator -->
                        <div id="bmi-calculator"
                            class="mt-6 bg-gradient-to-r from-indigo-50 to-purple-50 dark:from-indigo-900/20 dark:to-purple-900/20 p-4 rounded-xl border border-indigo-200 dark:border-indigo-700">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-3">
                                    <i class="fas fa-calculator text-indigo-600 dark:text-indigo-400"></i>
                                    <span class="font-medium text-gray-900 dark:text-white">BMI Calculator</span>
                                </div>
                                <div class="flex items-center space-x-4">
                                    <span id="bmi-value"
                                        class="text-lg font-bold text-indigo-600 dark:text-indigo-400">--</span>
                                    <span id="bmi-category" class="text-sm text-gray-600 dark:text-gray-400">--</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> <!-- Enhanced Clinical Assessment -->
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
                                    placeholder="Patient's primary concern or reason for visit..." onchange="updateFormProgress()"
                                    class="w-full border-2 @error('chief_complaint') border-red-500 dark:border-red-400 @else border-gray-300 dark:border-gray-600 @enderror rounded-xl shadow-sm focus:ring-2 focus:ring-purple-500 focus:border-purple-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white p-4 resize-none transition-all duration-200">{{ old('chief_complaint') }}</textarea>
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
                                    placeholder="Detailed description of the current condition..."
                                    class="w-full border-2 @error('history_of_present_illness') border-red-500 dark:border-red-400 @else border-gray-300 dark:border-gray-600 @enderror rounded-xl shadow-sm focus:ring-2 focus:ring-purple-500 focus:border-purple-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white p-4 resize-none transition-all duration-200">{{ old('history_of_present_illness') }}</textarea>
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
                                placeholder="Detailed findings from physical examination..." onchange="updateFormProgress()"
                                class="w-full border-2 @error('physical_examination') border-red-500 dark:border-red-400 @else border-gray-300 dark:border-gray-600 @enderror rounded-xl shadow-sm focus:ring-2 focus:ring-purple-500 focus:border-purple-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white p-4 resize-none transition-all duration-200">{{ old('physical_examination') }}</textarea>
                            @error('physical_examination')
                                <div class="mt-2 flex items-center text-red-600 dark:text-red-400 text-sm">
                                    <i class="fas fa-exclamation-circle mr-2"></i>
                                    <span>{{ $message }}</span>
                                </div>
                            @enderror
                        </div>

                        <!-- Systems Review -->
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
                                        value="{{ old('systems_review.cardiovascular') }}"
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
                                        value="{{ old('systems_review.respiratory') }}"
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
                                        value="{{ old('systems_review.gastrointestinal') }}"
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
                                        value="{{ old('systems_review.neurological') }}"
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
                    <div id="diagnosis-treatment-content" class="p-8 space-y-8 section-collapse">
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                            <div>
                                <label for="assessment_diagnosis"
                                    class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                    <i class="fas fa-microscope mr-2 text-purple-500"></i>Assessment & Diagnosis
                                </label>
                                <textarea name="assessment_diagnosis" id="assessment_diagnosis" rows="5"
                                    placeholder="Clinical diagnosis and assessment..." onchange="updateFormProgress()"
                                    class="w-full border-2 @error('assessment_diagnosis') border-red-500 dark:border-red-400 @else border-gray-300 dark:border-gray-600 @enderror rounded-xl shadow-sm focus:ring-2 focus:ring-purple-500 focus:border-purple-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white p-4 resize-none transition-all duration-200">{{ old('assessment_diagnosis') }}</textarea>
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
                                    placeholder="Recommended treatment and care plan..." onchange="updateFormProgress()"
                                    class="w-full border-2 @error('treatment_plan') border-red-500 dark:border-red-400 @else border-gray-300 dark:border-gray-600 @enderror rounded-xl shadow-sm focus:ring-2 focus:ring-purple-500 focus:border-purple-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white p-4 resize-none transition-all duration-200">{{ old('treatment_plan') }}</textarea>
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
                                <!-- Dynamic medication entries will be added here -->
                            </div>

                            <!-- Add first medication if none exist -->
                            <div id="no-medications-message" class="text-center py-8 text-gray-500 dark:text-gray-400">
                                <i class="fas fa-pills text-4xl mb-4"></i>
                                <p>No medications added yet. Click "Add Medication" to start prescribing.</p>
                            </div>

                            <!-- Legacy textarea for medical report display -->
                            <input type="hidden" name="medications_prescribed" id="medications_prescribed_hidden">

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
                                <!-- Dynamic lab test entries will be added here -->
                            </div>

                            <!-- Add first lab test if none exist -->
                            <div id="no-lab-tests-message" class="text-center py-8 text-gray-500 dark:text-gray-400">
                                <i class="fas fa-vials text-4xl mb-4"></i>
                                <p>No lab tests ordered yet. Click "Add Lab Test" to request tests.</p>
                            </div>

                            <!-- Legacy fields for medical report display -->
                            <input type="hidden" name="lab_tests_ordered" id="lab_tests_ordered_hidden">
                            <input type="hidden" name="imaging_studies" id="imaging_studies_hidden">

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
                                    class="w-full border-2 @error('follow_up_instructions') border-red-500 dark:border-red-400 @else border-gray-300 dark:border-gray-600 @enderror rounded-xl shadow-sm focus:ring-2 focus:ring-purple-500 focus:border-purple-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white p-4 resize-none transition-all duration-200">{{ old('follow_up_instructions') }}</textarea>
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
                                    class="w-full border-2 @error('additional_notes') border-red-500 dark:border-red-400 @else border-gray-300 dark:border-gray-600 @enderror rounded-xl shadow-sm focus:ring-2 focus:ring-purple-500 focus:border-purple-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white p-4 resize-none transition-all duration-200">{{ old('additional_notes') }}</textarea>
                                @error('additional_notes')
                                    <div class="mt-2 flex items-center text-red-600 dark:text-red-400 text-sm">
                                        <i class="fas fa-exclamation-circle mr-2"></i>
                                        <span>{{ $message }}</span>
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <!-- Priority and Urgency -->
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
                                            {{ old('priority_level') == 'routine' ? 'selected' : '' }}>Routine</option>
                                        <option value="urgent" {{ old('priority_level') == 'urgent' ? 'selected' : '' }}>
                                            Urgent</option>
                                        <option value="emergency"
                                            {{ old('priority_level') == 'emergency' ? 'selected' : '' }}>Emergency</option>
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
                                        <option value="no" {{ old('follow_up_required') == 'no' ? 'selected' : '' }}>
                                            No Follow-up Needed</option>
                                        <option value="1_week"
                                            {{ old('follow_up_required') == '1_week' ? 'selected' : '' }}>1 Week</option>
                                        <option value="2_weeks"
                                            {{ old('follow_up_required') == '2_weeks' ? 'selected' : '' }}>2 Weeks</option>
                                        <option value="1_month"
                                            {{ old('follow_up_required') == '1_month' ? 'selected' : '' }}>1 Month</option>
                                        <option value="3_months"
                                            {{ old('follow_up_required') == '3_months' ? 'selected' : '' }}>3 Months
                                        </option>
                                        <option value="6_months"
                                            {{ old('follow_up_required') == '6_months' ? 'selected' : '' }}>6 Months
                                        </option>
                                        <option value="1_year"
                                            {{ old('follow_up_required') == '1_year' ? 'selected' : '' }}>1 Year</option>
                                        <option value="as_needed"
                                            {{ old('follow_up_required') == 'as_needed' ? 'selected' : '' }}>As Needed
                                        </option>
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
                                    {{ old('save_as_template') ? 'checked' : '' }}
                                    class="h-5 w-5 text-purple-600 focus:ring-purple-500 border-gray-300 rounded">
                                <label for="save_as_template"
                                    class="ml-3 block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    <i class="fas fa-bookmark mr-1"></i>Save as template for future use
                                </label>
                            </div>
                            <div class="flex items-center">
                                <input type="checkbox" name="send_notification" id="send_notification"
                                    {{ old('send_notification') ? 'checked' : '' }}
                                    class="h-5 w-5 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                <label for="send_notification"
                                    class="ml-3 block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    <i class="fas fa-bell mr-1"></i>Notify patient
                                </label>
                            </div>
                        </div>
                        <div class="flex items-center space-x-4">
                            <button type="button" onclick="previewReport()"
                                class="bg-gray-900 dark:bg-gray-700 text-white px-6 py-3 rounded-xl text-sm font-medium transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-1">
                                <i class="fas fa-eye mr-2"></i>Preview Report
                            </button>
                            <button type="submit" name="status" value="draft"
                                class="bg-gray-900 dark:bg-gray-700 text-white px-6 py-3 rounded-xl text-sm font-medium transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-1">
                                <i class="fas fa-save mr-2"></i>Save as Draft
                            </button>
                            <button type="submit" name="status" value="completed"
                                class="bg-gray-900 dark:bg-gray-700 text-white px-8 py-3 rounded-xl text-sm font-semibold transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-1">
                                <i class="fas fa-check-circle mr-2"></i>Complete Report
                            </button>
                        </div>
                    </div>
                </div>
            </form>

            <!-- Quick Tips Modal -->
            <div id="quick-tips-modal"
                class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
                <div class="bg-white dark:bg-gray-800 rounded-xl max-w-2xl max-h-96 overflow-y-auto p-6 shadow-2xl">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white">
                            <i class="fas fa-lightbulb mr-2 text-yellow-500"></i>Quick Tips
                        </h3>
                        <button onclick="closeQuickTips()"
                            class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    <div class="space-y-3 text-sm text-gray-700 dark:text-gray-300">
                        <div class="flex items-start space-x-2">
                            <i class="fas fa-keyboard text-blue-500 mt-1"></i>
                            <span><strong>Keyboard Shortcuts:</strong> Ctrl+S to save, Tab to navigate fields</span>
                        </div>
                        <div class="flex items-start space-x-2">
                            <i class="fas fa-magic text-green-500 mt-1"></i>
                            <span><strong>Quick Presets:</strong> Use preset buttons for common scenarios</span>
                        </div>
                        <div class="flex items-start space-x-2">
                            <i class="fas fa-microphone text-purple-500 mt-1"></i>
                            <span><strong>Voice Input:</strong> Click voice button for hands-free input</span>
                        </div>
                        <div class="flex items-start space-x-2">
                            <i class="fas fa-calculator text-orange-500 mt-1"></i>
                            <span><strong>Auto-calculations:</strong> BMI and vital ranges are calculated
                                automatically</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Voice Input Modal -->
            <div id="voice-modal"
                class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
                <div class="bg-white dark:bg-gray-800 rounded-xl max-w-md w-full p-6 shadow-2xl">
                    <div class="text-center">
                        <div class="speech-indicator mb-4">
                            <i class="fas fa-microphone text-6xl text-red-500"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Voice Input Active</h3>
                        <p class="text-gray-600 dark:text-gray-400 mb-4">Speak your input clearly...</p>
                        <div class="flex justify-center space-x-4">
                            <button onclick="stopVoiceInput()"
                                class="bg-gradient-to-r from-red-500 to-pink-600 hover:from-red-600 hover:to-pink-700 dark:from-red-600 dark:to-pink-700 dark:hover:from-red-700 dark:hover:to-pink-800 text-white px-4 py-2 rounded-lg transition-all duration-200 border border-red-400 dark:border-red-500 hover:border-red-500 dark:hover:border-red-600 shadow-md hover:shadow-lg">
                                <i class="fas fa-stop mr-2"></i>Stop
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

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
                        <div class="bg-green-100 dark:bg-green-800 p-2 rounded-lg">
                            <i class="fas fa-search text-green-600 dark:text-green-300"></i>
                        </div>
                        <div>
                            <h4 class="font-semibold text-gray-900 dark:text-white">Patient Quick Search</h4>
                            <p class="text-gray-600 dark:text-gray-400 text-sm">Use the search box to quickly find patients
                                by name, email, phone, or ID. Click on a result to auto-fill patient information.</p>
                        </div>
                    </div>
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
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            // Enhanced Patient Search and Form Management
            let searchTimeout;
            let selectedPatient = null;

            document.addEventListener('DOMContentLoaded', function() {
                initializePatientSearch();
                setupKeyboardShortcuts();
                setupFormErrorHandling();
            });

            // Setup form error handling
            function setupFormErrorHandling() {
                const form = document.querySelector('form');
                if (!form) return;

                // Clear errors when user starts interacting with form
                const formInputs = form.querySelectorAll('input, textarea, select');
                formInputs.forEach(input => {
                    input.addEventListener('input', function() {
                        // Clear field-specific error styling
                        this.classList.remove('border-red-500', 'dark:border-red-400');

                        // Hide form errors section after user starts fixing issues
                        const errorSection = document.getElementById('form-errors-section');
                        if (!errorSection.classList.contains('hidden')) {
                            setTimeout(() => {
                                hideFormErrors();
                            }, 2000); // Hide after 2 seconds of user activity
                        }
                    });

                    input.addEventListener('focus', function() {
                        // Clear field-specific error styling when focused
                        this.classList.remove('border-red-500', 'dark:border-red-400');
                    });
                });
            }

            // Patient Search Functionality
            function initializePatientSearch() {
                const searchInput = document.getElementById('patient-search');
                const resultsDiv = document.getElementById('patient-search-results');
                const patientSelect = document.getElementById('patient_id');

                if (!searchInput) return;

                searchInput.addEventListener('input', function() {
                    clearTimeout(searchTimeout);
                    const query = this.value.trim();

                    if (query.length < 2) {
                        resultsDiv.classList.add('hidden');
                        return;
                    }

                    document.getElementById('search-loading').classList.remove('hidden');

                    searchTimeout = setTimeout(() => {
                        searchPatients(query);
                    }, 300);
                });

                // Handle traditional select change
                patientSelect.addEventListener('change', function() {
                    if (this.value) {
                        const option = this.querySelector(`option[value="${this.value}"]`);
                        if (option) {
                            try {
                                const patientData = JSON.parse(option.getAttribute('data-patient'));
                                showSelectedPatient(patientData);
                            } catch (e) {
                                console.error('Error parsing patient data:', e);
                            }
                        }
                    }
                });

                // Hide results when clicking outside
                document.addEventListener('click', function(e) {
                    if (!searchInput.contains(e.target) && !resultsDiv.contains(e.target)) {
                        resultsDiv.classList.add('hidden');
                    }
                });
            }

            function searchPatients(query) {
                const patients = @json($patients ?? []);

                const filteredPatients = patients.filter(patient => {
                    const searchFields = [
                        patient.name?.toLowerCase() || '',
                        patient.email?.toLowerCase() || '',
                        patient.phone?.toLowerCase() || '',
                        patient.id?.toString() || ''
                    ];

                    return searchFields.some(field =>
                        field.includes(query.toLowerCase())
                    );
                });

                displaySearchResults(filteredPatients, query);
                document.getElementById('search-loading').classList.add('hidden');
            }

            function displaySearchResults(patients, query) {
                const resultsDiv = document.getElementById('patient-search-results');

                if (patients.length === 0) {
                    resultsDiv.innerHTML = `
            <div class="p-4 text-center text-gray-500 dark:text-gray-400">
                <i class="fas fa-search text-2xl mb-2"></i>
                <p>No patients found for "${query}"</p>
            </div>
        `;
                } else {
                    resultsDiv.innerHTML = patients.map(patient => `
            <div class="patient-result p-4 hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer border-b border-gray-100 dark:border-gray-600 last:border-b-0" 
                 onclick="selectPatient(${patient.id}, '${encodeURIComponent(JSON.stringify(patient))}')">
                <div class="flex items-center space-x-3">
                    <div class="bg-blue-500 p-2 rounded-full">
                        <i class="fas fa-user text-white text-sm"></i>
                    </div>
                    <div class="flex-1">
                        <div class="font-semibold text-gray-900 dark:text-white">${patient.name}</div>
                        <div class="text-sm text-gray-600 dark:text-gray-400">${patient.email}</div>
                        ${patient.phone ? `<div class="text-sm text-gray-500 dark:text-gray-500">${patient.phone}</div>` : ''}
                    </div>
                    <div class="text-xs text-gray-400 dark:text-gray-500">ID: ${patient.id}</div>
                </div>
            </div>
        `).join('');
                }

                resultsDiv.classList.remove('hidden');
            }

            function selectPatient(patientId, encodedPatientData) {
                try {
                    let patientData;

                    // Handle direct patient object (for preselection) vs encoded data (from search)
                    if (typeof patientId === 'object' && patientId !== null) {
                        // Direct patient object passed (from preselection)
                        patientData = patientId;
                        patientId = patientData.id;
                    } else {
                        // Encoded patient data from search results
                        patientData = JSON.parse(decodeURIComponent(encodedPatientData));
                    }

                    // Update form fields
                    document.getElementById('patient_id').value = patientId;
                    document.getElementById('patient-search').value = patientData.name;

                    // Show patient preview
                    showSelectedPatient(patientData);

                    // Hide search results
                    document.getElementById('patient-search-results').classList.add('hidden');

                    selectedPatient = patientData;

                    // Show success message
                    showNotification('Patient selected successfully!', 'success');

                } catch (error) {
                    console.error('Error selecting patient:', error);
                    showNotification('Error selecting patient', 'error');
                }
            }

            function showSelectedPatient(patient) {
                const card = document.getElementById('selected-patient-card');
                const content = document.getElementById('patient-preview-content');

                content.innerHTML = `
        <div class="space-y-2">
            <div class="font-semibold text-green-800 dark:text-green-300">Name</div>
            <div class="text-green-700 dark:text-green-400">${patient.name}</div>
        </div>
        <div class="space-y-2">
            <div class="font-semibold text-green-800 dark:text-green-300">Email</div>
            <div class="text-green-700 dark:text-green-400">${patient.email}</div>
        </div>
        <div class="space-y-2">
            <div class="font-semibold text-green-800 dark:text-green-300">Patient ID</div>
            <div class="text-green-700 dark:text-green-400">#${patient.id}</div>
        </div>
    `;

                card.classList.remove('hidden');
            }

            function clearSelectedPatient() {
                document.getElementById('selected-patient-card').classList.add('hidden');
                document.getElementById('patient_id').value = '';
                document.getElementById('patient-search').value = '';
                selectedPatient = null;
                showNotification('Patient selection cleared', 'info');
            }

            // Enhanced Template System
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
                        'treatment_plan': 'Continue current healthy lifestyle\nMaintain regular exercise routine\nBalanced diet and adequate hydration',
                        'follow_up_instructions': 'Return for routine checkup in 12 months\nContact office if any health concerns arise\nContinue preventive care as recommended'
                    },
                    follow_up: {
                        'chief_complaint': 'Follow-up visit for previously diagnosed condition',
                        'history_of_present_illness': 'Patient returns for scheduled follow-up of previously diagnosed condition. Reports good compliance with treatment plan. Symptoms have been stable/improving since last visit.',
                        'assessment_diagnosis': 'Previously diagnosed condition - stable\nGood response to current treatment regimen\nNo new concerning symptoms',
                        'treatment_plan': 'Continue current treatment plan\nMonitor symptoms and response to therapy',
                        'follow_up_instructions': 'Continue current medications as prescribed\nReturn for follow-up in [specify timeframe]\nContact if symptoms worsen or new concerns arise'
                    },
                    emergency: {
                        'chief_complaint': 'Emergency presentation with acute symptoms',
                        'history_of_present_illness': 'Patient presents to emergency department with acute onset of symptoms requiring immediate evaluation and treatment.',
                        'physical_examination': 'Patient appears acutely ill/in distress\nVital signs: [Document specific findings]\nFocused examination reveals: [Document pertinent findings]',
                        'assessment_diagnosis': 'Acute condition requiring immediate intervention\n[Specify primary diagnosis]\n[List differential diagnoses]',
                        'treatment_plan': 'Immediate stabilization and treatment\n[Specify interventions]\nClose monitoring required',
                        'follow_up_instructions': 'Hospital admission recommended vs. close outpatient follow-up\nReturn immediately if symptoms worsen\nSpecific discharge instructions provided',
                        'priority_level': 'emergency'
                    },
                    physical_exam: {
                        'physical_examination': 'Constitutional: Alert, oriented, well-appearing, no acute distress\nHEENT: Normocephalic, atraumatic. PERRLA 3mm→2mm. EOMI. TMs clear. Throat non-erythematous\nNeck: Supple, no lymphadenopathy, no thyromegaly\nCardiovascular: Regular rate and rhythm, no murmurs, rubs, or gallops\nRespiratory: Clear to auscultation bilaterally, good air movement, no wheezes, rales, or rhonchi\nAbdomen: Soft, non-tender, non-distended, bowel sounds present in all quadrants\nExtremities: No clubbing, cyanosis, or edema. Pulses 2+ throughout\nSkin: Warm, dry, intact, no rashes or lesions\nNeurological: Alert and oriented x3, speech clear, gait steady'
                    }
                };

                const template = templates[templateType];
                if (template) {
                    Object.keys(template).forEach(fieldName => {
                        const field = document.querySelector(`[name="${fieldName}"]`);
                        if (field) {
                            field.value = template[fieldName];
                            // Add visual feedback
                            field.classList.add('bg-green-50', 'dark:bg-green-900/20', 'border-green-300',
                                'dark:border-green-600');
                            setTimeout(() => {
                                field.classList.remove('bg-green-50', 'dark:bg-green-900/20',
                                    'border-green-300', 'dark:border-green-600');
                            }, 2000);
                        }
                    });

                    showNotification(`${templateType.replace('_', ' ').toUpperCase()} template applied!`, 'success');
                }
            }

            function clearAllFields() {
                if (confirm('Are you sure you want to clear all form fields? This action cannot be undone.')) {
                    const form = document.querySelector('form');
                    const inputs = form.querySelectorAll('input[type="text"], input[type="date"], textarea, select');

                    inputs.forEach(input => {
                        if (input.type === 'checkbox') {
                            input.checked = false;
                        } else {
                            input.value = '';
                        }
                    });

                    clearSelectedPatient();
                    showNotification('All fields cleared', 'info');
                }
            }

            // Keyboard shortcuts
            function setupKeyboardShortcuts() {
                document.addEventListener('keydown', function(e) {
                    if (e.ctrlKey || e.metaKey) {
                        switch (e.key) {
                            case 's':
                                e.preventDefault();
                                document.querySelector('button[name="status"][value="draft"]').click();
                                break;
                            case 'Enter':
                                e.preventDefault();
                                document.querySelector('button[name="status"][value="completed"]').click();
                                break;
                            case 'p':
                                e.preventDefault();
                                previewReport();
                                break;
                        }
                    }
                });
            }

            // Preview functionality
            function previewReport() {
                const formData = new FormData(document.querySelector('form'));
                // Open preview in new window/modal
                showNotification('Preview functionality coming soon!', 'info');
            }

            // Modal functions
            function showQuickTips() {
                document.getElementById('quick-tips-modal').classList.remove('hidden');
            }

            function hideQuickTips() {
                document.getElementById('quick-tips-modal').classList.add('hidden');
            }

            // Notification system
            function showNotification(message, type = 'info', duration = 3000) {
                const colors = {
                    success: 'bg-green-500',
                    error: 'bg-red-500',
                    info: 'bg-blue-500',
                    warning: 'bg-yellow-500'
                };

                const notification = document.createElement('div');
                notification.className =
                    `fixed top-4 right-4 ${colors[type]} text-white px-6 py-3 rounded-lg shadow-lg z-50 transform translate-x-full transition-transform duration-300`;
                notification.innerHTML = `
        <div class="flex items-center space-x-2">
            <i class="fas fa-${type === 'success' ? 'check' : type === 'error' ? 'exclamation-triangle' : 'info-circle'}"></i>
            <span>${message}</span>
        </div>
    `;

                document.body.appendChild(notification);

                setTimeout(() => {
                    notification.classList.remove('translate-x-full');
                }, 100);

                setTimeout(() => {
                    notification.classList.add('translate-x-full');
                    setTimeout(() => {
                        document.body.removeChild(notification);
                    }, 300);
                }, duration);
            }

            // Load page functionality
            window.addEventListener('load', function() {
                // Pre-select patient if provided via URL parameter
                @if ($selectedPatient)
                    const preSelectedPatient = @json($selectedPatient);
                    selectPatient(preSelectedPatient);
                    showNotification('Patient pre-selected from appointment', 'success');
                @endif

                // Initialize prescription and lab test management
                initializePrescriptionManagement();
                initializeLabTestManagement();
            });

            // Prescription Management Functions
            let prescriptionMedicationCount = 0;
            let labTestRequestCount = 0;

            function initializePrescriptionManagement() {
                updatePrescriptionDisplay();
            }

            function addPrescriptionMedication() {
                prescriptionMedicationCount++;
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
                    const name = med.querySelector('[name*="[medication_name]"]').value;
                    const dosage = med.querySelector('[name*="[dosage]"]').value;
                    const frequency = med.querySelector('[name*="[frequency]"]').value;
                    const duration = med.querySelector('[name*="[duration]"]').value;

                    if (name || dosage || frequency || duration) {
                        summary += `${index + 1}. ${name} - ${dosage} - ${frequency} for ${duration}\n`;
                    }
                });

                document.getElementById('medications_prescribed_hidden').value = summary;
            }

            // Lab Test Management Functions
            function initializeLabTestManagement() {
                updateLabTestDisplay();
            }

            function addLabTestRequest() {
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
                    const name = test.querySelector('[name*="[test_name]"]').value;
                    const type = test.querySelector('[name*="[test_type]"]').value;
                    const priority = test.querySelector('[name*="[priority]"]').value;

                    if (name || type) {
                        summary +=
                            `${index + 1}. ${name} (${type})${priority !== 'routine' ? ' - ' + priority.toUpperCase() : ''}\n`;
                    }
                });

                document.getElementById('lab_tests_ordered_hidden').value = summary;
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
                document.querySelector('[name="vital_signs[height]"]').value = '170 cm';
                document.querySelector('[name="vital_signs[weight]"]').value = '70 kg';
                updateFormProgress();
                calculateBMI();
                showNotification('Normal vital signs filled', 'success');
            }

            function fillPediatricVitals() {
                document.querySelector('[name="vital_signs[blood_pressure]"]').value = '100/60';
                document.querySelector('[name="vital_signs[temperature]"]').value = '98.2';
                document.querySelector('[name="vital_signs[heart_rate]"]').value = '100';
                document.querySelector('[name="vital_signs[respiratory_rate]"]').value = '24';
                document.querySelector('[name="vital_signs[oxygen_saturation]"]').value = '99';
                document.querySelector('[name="vital_signs[height]"]').value = '140 cm';
                document.querySelector('[name="vital_signs[weight]"]').value = '40 kg';
                updateFormProgress();
                calculateBMI();
                showNotification('Pediatric vital signs filled', 'success');
            }

            function fillGeriatricVitals() {
                document.querySelector('[name="vital_signs[blood_pressure]"]').value = '130/80';
                document.querySelector('[name="vital_signs[temperature]"]').value = '98.0';
                document.querySelector('[name="vital_signs[heart_rate]"]').value = '65';
                document.querySelector('[name="vital_signs[respiratory_rate]"]').value = '14';
                document.querySelector('[name="vital_signs[oxygen_saturation]"]').value = '97';
                document.querySelector('[name="vital_signs[height]"]').value = '165 cm';
                document.querySelector('[name="vital_signs[weight]"]').value = '75 kg';
                updateFormProgress();
                calculateBMI();
                showNotification('Geriatric vital signs filled', 'success');
            }

            // Vital Signs Validation and Helpers
            function validateVitalRange(input, type) {
                const value = input.value.trim();
                const statusElement = document.getElementById(`${type}-status`);

                if (!value) {
                    statusElement.textContent = '';
                    return;
                }

                let message = '';
                let isNormal = true;

                switch (type) {
                    case 'bp':
                        const bpMatch = value.match(/(\d+)\/(\d+)/);
                        if (bpMatch) {
                            const systolic = parseInt(bpMatch[1]);
                            const diastolic = parseInt(bpMatch[2]);
                            if (systolic < 90 || systolic > 140 || diastolic < 60 || diastolic > 90) {
                                message = 'Outside normal range (90-140/60-90)';
                                isNormal = false;
                            } else {
                                message = 'Normal range';
                            }
                        }
                        break;
                    case 'temp':
                        const temp = parseFloat(value);
                        if (temp < 97 || temp > 99.5) {
                            message = 'Outside normal range (97-99.5°F)';
                            isNormal = false;
                        } else {
                            message = 'Normal range';
                        }
                        break;
                    case 'hr':
                        const hr = parseInt(value);
                        if (hr < 60 || hr > 100) {
                            message = 'Outside normal range (60-100 bpm)';
                            isNormal = false;
                        } else {
                            message = 'Normal range';
                        }
                        break;
                    case 'rr':
                        const rr = parseInt(value);
                        if (rr < 12 || rr > 20) {
                            message = 'Outside normal range (12-20/min)';
                            isNormal = false;
                        } else {
                            message = 'Normal range';
                        }
                        break;
                    case 'spo2':
                        const spo2 = parseInt(value);
                        if (spo2 < 95) {
                            message = 'Below normal (95-100%)';
                            isNormal = false;
                        } else {
                            message = 'Normal range';
                        }
                        break;
                }

                statusElement.textContent = message;
                statusElement.className = isNormal ? 'mt-2 text-xs text-green-600 dark:text-green-400' :
                    'mt-2 text-xs text-red-600 dark:text-red-400';
            }

            function calculateBMI() {
                const weightInput = document.querySelector('[name="vital_signs[weight]"]');
                const heightInput = document.querySelector('[name="vital_signs[height]"]');

                if (!weightInput.value || !heightInput.value) return;

                const weight = parseFloat(weightInput.value);
                const height = parseFloat(heightInput.value);

                if (weight > 0 && height > 0) {
                    const heightM = height / 100; // Convert cm to meters
                    const bmi = weight / (heightM * heightM);

                    document.getElementById('bmi-value').textContent = bmi.toFixed(1);

                    let category = '';
                    if (bmi < 18.5) category = 'Underweight';
                    else if (bmi < 25) category = 'Normal';
                    else if (bmi < 30) category = 'Overweight';
                    else category = 'Obese';

                    document.getElementById('bmi-category').textContent = category;
                }
            }

            // Section Toggle
            function toggleSection(sectionId) {
                const content = document.getElementById(`${sectionId}-content`);
                const toggle = document.getElementById(`${sectionId}-toggle`);

                if (content.style.maxHeight === '0px' || content.style.maxHeight === '') {
                    content.style.maxHeight = '1000px';
                    toggle.className = 'fas fa-chevron-up';
                } else {
                    content.style.maxHeight = '0px';
                    toggle.className = 'fas fa-chevron-down';
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

                const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
                recognition = new SpeechRecognition();

                recognition.continuous = true;
                recognition.interimResults = true;
                recognition.lang = 'en-US';

                document.getElementById('voice-modal').classList.remove('hidden');

                recognition.onresult = function(event) {
                    let finalTranscript = '';
                    for (let i = event.resultIndex; i < event.results.length; i++) {
                        if (event.results[i].isFinal) {
                            finalTranscript += event.results[i][0].transcript;
                        }
                    }

                    if (finalTranscript && currentVoiceField) {
                        currentVoiceField.value += finalTranscript + ' ';
                    }
                };

                recognition.start();
            }

            function stopVoiceInput() {
                if (recognition) {
                    recognition.stop();
                }
                document.getElementById('voice-modal').classList.add('hidden');
            }

            // Quick Tips
            function showQuickTips() {
                document.getElementById('quick-tips-modal').classList.remove('hidden');
            }

            function closeQuickTips() {
                document.getElementById('quick-tips-modal').classList.add('hidden');
            }

            // Clear form
            function clearForm() {
                if (confirm('Are you sure you want to clear all fields?')) {
                    document.getElementById('medical-report-form').reset();
                    updateFormProgress();
                    showNotification('Form cleared', 'info');
                }
            }

            // Notification system
            function showNotification(message, type = 'info') {
                const notification = document.createElement('div');
                notification.className =
                    `fixed top-4 right-4 z-50 px-6 py-3 rounded-lg shadow-lg transition-all duration-300 transform translate-x-full`;

                switch (type) {
                    case 'success':
                        notification.className += ' bg-green-500 text-white';
                        break;
                    case 'error':
                        notification.className += ' bg-red-500 text-white';
                        break;
                    case 'warning':
                        notification.className += ' bg-yellow-500 text-black';
                        break;
                    default:
                        notification.className += ' bg-blue-500 text-white';
                }

                notification.innerHTML = `<i class="fas fa-info-circle mr-2"></i>${message}`;
                document.body.appendChild(notification);

                setTimeout(() => {
                    notification.classList.remove('translate-x-full');
                }, 100);

                setTimeout(() => {
                    notification.classList.add('translate-x-full');
                    setTimeout(() => {
                        document.body.removeChild(notification);
                    }, 300);
                }, 3000);
            }

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
            });
        </script>

        <!-- Medications Datalist for autocomplete -->
        <datalist id="medications-datalist">
            @foreach (\App\Models\Medication::active()->get() as $medication)
                <option value="{{ $medication->full_name }}" data-id="{{ $medication->id }}">
                    {{ $medication->generic_name ? $medication->generic_name . ' - ' : '' }}{{ $medication->brand_name }}
                </option>
            @endforeach
        </datalist>
    @endpush
@endsection
