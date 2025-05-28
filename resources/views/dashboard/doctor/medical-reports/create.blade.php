@extends('layouts.app')

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
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .form-section-transition {
        transition: all 0.3s ease-in-out;
    }
    .vital-input:focus {
        transform: scale(1.02);
        transition: transform 0.2s ease;
    }
</style>
@endpush

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-purple-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Enhanced Header -->
        <div class="mb-10">
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-8 border border-gray-100 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <div class="bg-gradient-to-r from-blue-500 to-purple-600 p-3 rounded-xl">
                            <i class="fas fa-file-medical text-white text-2xl"></i>
                        </div>
                        <div>
                            <h1 class="text-4xl font-bold bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">
                                Create Medical Report
                            </h1>
                            <p class="mt-2 text-gray-600 dark:text-gray-400 text-lg">Document patient consultation and treatment with comprehensive details</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-3">
                        <button type="button" onclick="showQuickTips()" class="bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white px-6 py-3 rounded-xl text-sm font-medium transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-1">
                            <i class="fas fa-lightbulb mr-2"></i>Quick Tips
                        </button>
                        <a href="{{ route('doctor.medical-reports.index') }}" class="bg-gradient-to-r from-gray-500 to-gray-600 hover:from-gray-600 hover:to-gray-700 text-white px-6 py-3 rounded-xl text-sm font-medium transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-1">
                            <i class="fas fa-arrow-left mr-2"></i>Back to Reports
                        </a>
                    </div>
                </div>
            </div>
        </div>        <!-- Enhanced Form -->
        <form method="POST" action="{{ route('doctor.medical-reports.store') }}" class="space-y-8">
            @csrf
            
            <!-- Patient Quick Lookup & Basic Information -->
            <div class="bg-white dark:bg-gray-800 shadow-xl rounded-2xl border border-gray-100 dark:border-gray-700 form-section-transition">
                <div class="px-8 py-6 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-blue-50 to-purple-50 dark:from-gray-700 dark:to-gray-600 rounded-t-2xl">
                    <div class="flex items-center space-x-3">
                        <div class="bg-blue-500 p-2 rounded-lg">
                            <i class="fas fa-user-md text-white"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white">Patient Information & Quick Lookup</h3>
                    </div>
                </div>
                <div class="p-8 space-y-8">
                    <!-- Enhanced Patient Quick Search -->
                    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-gray-700 dark:to-gray-600 p-6 rounded-xl border border-blue-200 dark:border-gray-600">
                        <div class="flex items-center space-x-3 mb-4">
                            <i class="fas fa-search text-blue-600 dark:text-blue-400"></i>
                            <h4 class="text-lg font-medium text-gray-900 dark:text-white">Quick Patient Search</h4>
                        </div>
                        <div class="relative">
                            <input type="text" id="patient-search" placeholder="Search by name, email, phone, or patient ID..." 
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
                        <div id="patient-search-results" class="hidden absolute z-50 w-full mt-2 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-600 rounded-xl shadow-2xl patient-search-dropdown">
                            <!-- Results will be populated here -->
                        </div>
                    </div>

                    <!-- Selected Patient Preview Card -->
                    <div id="selected-patient-card" class="hidden patient-card-preview">
                        <div class="bg-gradient-to-r from-green-50 to-emerald-50 dark:from-green-900/20 dark:to-emerald-900/20 border-2 border-green-200 dark:border-green-700 rounded-xl p-6">
                            <div class="flex items-center justify-between mb-4">
                                <div class="flex items-center space-x-3">
                                    <div class="bg-green-500 p-2 rounded-lg">
                                        <i class="fas fa-user-check text-white"></i>
                                    </div>
                                    <h4 class="text-lg font-semibold text-green-800 dark:text-green-300">Selected Patient</h4>
                                </div>
                                <button type="button" onclick="clearSelectedPatient()" class="text-red-500 hover:text-red-700 p-2 rounded-lg hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                            <div id="patient-preview-content" class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                                <!-- Patient details will be populated here -->
                            </div>
                        </div>
                    </div>

                    <!-- Traditional Patient Selection (Fallback) -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        <div>
                            <label for="patient_id" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                <i class="fas fa-user mr-2 text-blue-500"></i>Select Patient
                            </label>
                            <select name="patient_id" id="patient_id" required class="w-full border-2 border-gray-300 dark:border-gray-600 rounded-xl shadow-sm focus:ring-2 focus:ring-purple-500 focus:border-purple-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white py-3 px-4 transition-all duration-200">
                                <option value="">Choose from dropdown...</option>
                                @foreach($patients as $patient)
                                    <option value="{{ $patient->id }}" data-patient='@json($patient)'>
                                        {{ $patient->name }} ({{ $patient->email }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div>
                            <label for="consultation_date" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                <i class="fas fa-calendar mr-2 text-purple-500"></i>Consultation Date
                            </label>
                            <input type="date" name="consultation_date" id="consultation_date" value="{{ today()->format('Y-m-d') }}" required 
                                class="w-full border-2 border-gray-300 dark:border-gray-600 rounded-xl shadow-sm focus:ring-2 focus:ring-purple-500 focus:border-purple-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white py-3 px-4 transition-all duration-200">
                        </div>
                    </div>
                    
                    <div>
                        <label for="report_type" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                            <i class="fas fa-file-alt mr-2 text-indigo-500"></i>Report Type
                        </label>
                        <select name="report_type" id="report_type" required class="w-full border-2 border-gray-300 dark:border-gray-600 rounded-xl shadow-sm focus:ring-2 focus:ring-purple-500 focus:border-purple-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white py-3 px-4 transition-all duration-200">
                            <option value="">Select report type</option>
                            <option value="Consultation">
                                <i class="fas fa-stethoscope"></i> Initial Consultation
                            </option>
                            <option value="Follow-up">Follow-up Visit</option>
                            <option value="Diagnosis">Diagnosis Report</option>
                            <option value="Treatment">Treatment Report</option>
                            <option value="Prescription">Prescription Report</option>
                            <option value="Emergency">Emergency Visit</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                </div>
            </div>            <!-- Enhanced Vital Signs -->
            <div class="bg-white dark:bg-gray-800 shadow-xl rounded-2xl border border-gray-100 dark:border-gray-700 form-section-transition">
                <div class="px-8 py-6 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-green-50 to-emerald-50 dark:from-gray-700 dark:to-gray-600 rounded-t-2xl">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="bg-green-500 p-2 rounded-lg">
                                <i class="fas fa-heartbeat text-white"></i>
                            </div>
                            <h3 class="text-xl font-semibold text-gray-900 dark:text-white">Vital Signs</h3>
                        </div>
                        <button type="button" onclick="insertTemplate('normal_vitals')" class="bg-green-100 hover:bg-green-200 dark:bg-green-800 dark:hover:bg-green-700 text-green-700 dark:text-green-300 px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                            <i class="fas fa-magic mr-2"></i>Auto-fill Normal
                        </button>
                    </div>
                </div>
                <div class="p-8">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6" id="vital-signs">
                        <div class="bg-gradient-to-br from-red-50 to-pink-50 dark:from-red-900/20 dark:to-pink-900/20 p-4 rounded-xl border border-red-200 dark:border-red-700">
                            <label class="block text-sm font-semibold text-red-700 dark:text-red-300 mb-2">
                                <i class="fas fa-tint mr-2"></i>Blood Pressure
                            </label>
                            <input type="text" name="vital_signs[blood_pressure]" placeholder="120/80 mmHg" 
                                class="vital-input w-full border-2 border-red-300 dark:border-red-600 rounded-lg shadow-sm focus:ring-2 focus:ring-red-500 focus:border-red-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white py-3 px-4">
                        </div>
                        <div class="bg-gradient-to-br from-orange-50 to-yellow-50 dark:from-orange-900/20 dark:to-yellow-900/20 p-4 rounded-xl border border-orange-200 dark:border-orange-700">
                            <label class="block text-sm font-semibold text-orange-700 dark:text-orange-300 mb-2">
                                <i class="fas fa-thermometer-half mr-2"></i>Temperature
                            </label>
                            <input type="text" name="vital_signs[temperature]" placeholder="98.6°F" 
                                class="vital-input w-full border-2 border-orange-300 dark:border-orange-600 rounded-lg shadow-sm focus:ring-2 focus:ring-orange-500 focus:border-orange-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white py-3 px-4">
                        </div>
                        <div class="bg-gradient-to-br from-blue-50 to-cyan-50 dark:from-blue-900/20 dark:to-cyan-900/20 p-4 rounded-xl border border-blue-200 dark:border-blue-700">
                            <label class="block text-sm font-semibold text-blue-700 dark:text-blue-300 mb-2">
                                <i class="fas fa-heartbeat mr-2"></i>Heart Rate
                            </label>
                            <input type="text" name="vital_signs[heart_rate]" placeholder="70 bpm" 
                                class="vital-input w-full border-2 border-blue-300 dark:border-blue-600 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white py-3 px-4">
                        </div>
                        <div class="bg-gradient-to-br from-purple-50 to-indigo-50 dark:from-purple-900/20 dark:to-indigo-900/20 p-4 rounded-xl border border-purple-200 dark:border-purple-700">
                            <label class="block text-sm font-semibold text-purple-700 dark:text-purple-300 mb-2">
                                <i class="fas fa-weight mr-2"></i>Weight
                            </label>
                            <input type="text" name="vital_signs[weight]" placeholder="70 kg" 
                                class="vital-input w-full border-2 border-purple-300 dark:border-purple-600 rounded-lg shadow-sm focus:ring-2 focus:ring-purple-500 focus:border-purple-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white py-3 px-4">
                        </div>
                    </div>
                    
                    <!-- Additional Vital Signs -->
                    <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                <i class="fas fa-lungs mr-2 text-teal-500"></i>Respiratory Rate
                            </label>
                            <input type="text" name="vital_signs[respiratory_rate]" placeholder="16 breaths/min" 
                                class="w-full border-2 border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white py-3 px-4">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                <i class="fas fa-percentage mr-2 text-blue-500"></i>Oxygen Saturation
                            </label>
                            <input type="text" name="vital_signs[oxygen_saturation]" placeholder="98%" 
                                class="w-full border-2 border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white py-3 px-4">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                <i class="fas fa-ruler-vertical mr-2 text-green-500"></i>Height
                            </label>
                            <input type="text" name="vital_signs[height]" placeholder="170 cm" 
                                class="w-full border-2 border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white py-3 px-4">
                        </div>
                    </div>
                </div>
            </div>            <!-- Enhanced Clinical Assessment -->
            <div class="bg-white dark:bg-gray-800 shadow-xl rounded-2xl border border-gray-100 dark:border-gray-700 form-section-transition">
                <div class="px-8 py-6 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-indigo-50 to-blue-50 dark:from-gray-700 dark:to-gray-600 rounded-t-2xl">
                    <div class="flex items-center space-x-3">
                        <div class="bg-indigo-500 p-2 rounded-lg">
                            <i class="fas fa-stethoscope text-white"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white">Clinical Assessment</h3>
                    </div>
                </div>
                <div class="p-8 space-y-8">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        <div>
                            <label for="chief_complaint" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                <i class="fas fa-comment-medical mr-2 text-red-500"></i>Chief Complaint
                            </label>
                            <textarea name="chief_complaint" id="chief_complaint" rows="4" 
                                placeholder="Patient's primary concern or reason for visit..." 
                                class="w-full border-2 border-gray-300 dark:border-gray-600 rounded-xl shadow-sm focus:ring-2 focus:ring-purple-500 focus:border-purple-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white p-4 resize-none transition-all duration-200"></textarea>
                        </div>
                        
                        <div>
                            <label for="history_of_present_illness" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                <i class="fas fa-history mr-2 text-blue-500"></i>History of Present Illness
                            </label>
                            <textarea name="history_of_present_illness" id="history_of_present_illness" rows="4" 
                                placeholder="Detailed description of the current condition..." 
                                class="w-full border-2 border-gray-300 dark:border-gray-600 rounded-xl shadow-sm focus:ring-2 focus:ring-purple-500 focus:border-purple-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white p-4 resize-none transition-all duration-200"></textarea>
                        </div>
                    </div>
                    
                    <div>
                        <label for="physical_examination" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                            <i class="fas fa-search mr-2 text-green-500"></i>Physical Examination
                        </label>
                        <textarea name="physical_examination" id="physical_examination" rows="5" 
                            placeholder="Detailed findings from physical examination..." 
                            class="w-full border-2 border-gray-300 dark:border-gray-600 rounded-xl shadow-sm focus:ring-2 focus:ring-purple-500 focus:border-purple-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white p-4 resize-none transition-all duration-200"></textarea>
                    </div>

                    <!-- Systems Review -->
                    <div class="bg-gray-50 dark:bg-gray-700 p-6 rounded-xl">
                        <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                            <i class="fas fa-clipboard-list mr-2 text-purple-500"></i>Systems Review (Optional)
                        </h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Cardiovascular</label>
                                <input type="text" name="systems_review[cardiovascular]" placeholder="Normal, abnormal findings..." 
                                    class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 bg-white dark:bg-gray-600 text-gray-900 dark:text-white">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Respiratory</label>
                                <input type="text" name="systems_review[respiratory]" placeholder="Normal, abnormal findings..." 
                                    class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 bg-white dark:bg-gray-600 text-gray-900 dark:text-white">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Gastrointestinal</label>
                                <input type="text" name="systems_review[gastrointestinal]" placeholder="Normal, abnormal findings..." 
                                    class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 bg-white dark:bg-gray-600 text-gray-900 dark:text-white">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Neurological</label>
                                <input type="text" name="systems_review[neurological]" placeholder="Normal, abnormal findings..." 
                                    class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 bg-white dark:bg-gray-600 text-gray-900 dark:text-white">
                            </div>
                        </div>
                    </div>
                </div>
            </div>            <!-- Enhanced Diagnosis and Treatment -->
            <div class="bg-white dark:bg-gray-800 shadow-xl rounded-2xl border border-gray-100 dark:border-gray-700 form-section-transition">
                <div class="px-8 py-6 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-purple-50 to-pink-50 dark:from-gray-700 dark:to-gray-600 rounded-t-2xl">
                    <div class="flex items-center space-x-3">
                        <div class="bg-purple-500 p-2 rounded-lg">
                            <i class="fas fa-diagnoses text-white"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white">Diagnosis & Treatment</h3>
                    </div>
                </div>
                <div class="p-8 space-y-8">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        <div>
                            <label for="assessment_diagnosis" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                <i class="fas fa-microscope mr-2 text-purple-500"></i>Assessment & Diagnosis
                            </label>
                            <textarea name="assessment_diagnosis" id="assessment_diagnosis" rows="5" 
                                placeholder="Clinical diagnosis and assessment..." 
                                class="w-full border-2 border-gray-300 dark:border-gray-600 rounded-xl shadow-sm focus:ring-2 focus:ring-purple-500 focus:border-purple-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white p-4 resize-none transition-all duration-200"></textarea>
                        </div>
                        
                        <div>
                            <label for="treatment_plan" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                <i class="fas fa-clipboard-check mr-2 text-green-500"></i>Treatment Plan
                            </label>
                            <textarea name="treatment_plan" id="treatment_plan" rows="5" 
                                placeholder="Recommended treatment and care plan..." 
                                class="w-full border-2 border-gray-300 dark:border-gray-600 rounded-xl shadow-sm focus:ring-2 focus:ring-purple-500 focus:border-purple-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white p-4 resize-none transition-all duration-200"></textarea>
                        </div>
                    </div>
                    
                    <div>
                        <label for="medications_prescribed" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                            <i class="fas fa-pills mr-2 text-blue-500"></i>Medications Prescribed
                        </label>
                        <textarea name="medications_prescribed" id="medications_prescribed" rows="4" 
                            placeholder="List of prescribed medications with dosage and instructions..." 
                            class="w-full border-2 border-gray-300 dark:border-gray-600 rounded-xl shadow-sm focus:ring-2 focus:ring-purple-500 focus:border-purple-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white p-4 resize-none transition-all duration-200"></textarea>
                        <div class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                            <i class="fas fa-info-circle mr-1"></i>
                            Format: Medication name - Dosage - Frequency - Duration
                        </div>
                    </div>

                    <!-- Lab Tests and Investigations -->
                    <div class="bg-blue-50 dark:bg-blue-900/20 p-6 rounded-xl border border-blue-200 dark:border-blue-700">
                        <h4 class="text-lg font-semibold text-blue-800 dark:text-blue-300 mb-4">
                            <i class="fas fa-flask mr-2"></i>Lab Tests & Investigations
                        </h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tests Ordered</label>
                                <textarea name="lab_tests_ordered" rows="3" 
                                    placeholder="List any lab tests or investigations ordered..." 
                                    class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 bg-white dark:bg-gray-600 text-gray-900 dark:text-white"></textarea>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Imaging Studies</label>
                                <textarea name="imaging_studies" rows="3" 
                                    placeholder="X-rays, CT scans, MRI, etc..." 
                                    class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 bg-white dark:bg-gray-600 text-gray-900 dark:text-white"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Enhanced Follow-up and Notes -->
            <div class="bg-white dark:bg-gray-800 shadow-xl rounded-2xl border border-gray-100 dark:border-gray-700 form-section-transition">
                <div class="px-8 py-6 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-teal-50 to-green-50 dark:from-gray-700 dark:to-gray-600 rounded-t-2xl">
                    <div class="flex items-center space-x-3">
                        <div class="bg-teal-500 p-2 rounded-lg">
                            <i class="fas fa-calendar-check text-white"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white">Follow-up & Additional Notes</h3>
                    </div>
                </div>
                <div class="p-8 space-y-8">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        <div>
                            <label for="follow_up_instructions" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                <i class="fas fa-calendar-alt mr-2 text-teal-500"></i>Follow-up Instructions
                            </label>
                            <textarea name="follow_up_instructions" id="follow_up_instructions" rows="4" 
                                placeholder="Instructions for next visit, timeline, etc..." 
                                class="w-full border-2 border-gray-300 dark:border-gray-600 rounded-xl shadow-sm focus:ring-2 focus:ring-purple-500 focus:border-purple-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white p-4 resize-none transition-all duration-200"></textarea>
                        </div>
                        
                        <div>
                            <label for="additional_notes" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                <i class="fas fa-sticky-note mr-2 text-yellow-500"></i>Additional Notes
                            </label>
                            <textarea name="additional_notes" id="additional_notes" rows="4" 
                                placeholder="Any additional observations or notes..." 
                                class="w-full border-2 border-gray-300 dark:border-gray-600 rounded-xl shadow-sm focus:ring-2 focus:ring-purple-500 focus:border-purple-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white p-4 resize-none transition-all duration-200"></textarea>
                        </div>
                    </div>

                    <!-- Priority and Urgency -->
                    <div class="bg-yellow-50 dark:bg-yellow-900/20 p-6 rounded-xl border border-yellow-200 dark:border-yellow-700">
                        <h4 class="text-lg font-semibold text-yellow-800 dark:text-yellow-300 mb-4">
                            <i class="fas fa-exclamation-triangle mr-2"></i>Priority & Urgency
                        </h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Priority Level</label>
                                <select name="priority_level" class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 bg-white dark:bg-gray-600 text-gray-900 dark:text-white">
                                    <option value="routine">Routine</option>
                                    <option value="urgent">Urgent</option>
                                    <option value="emergency">Emergency</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Follow-up Required</label>
                                <select name="follow_up_required" class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 bg-white dark:bg-gray-600 text-gray-900 dark:text-white">
                                    <option value="no">No Follow-up Needed</option>
                                    <option value="1_week">1 Week</option>
                                    <option value="2_weeks">2 Weeks</option>
                                    <option value="1_month">1 Month</option>
                                    <option value="3_months">3 Months</option>
                                    <option value="6_months">6 Months</option>
                                    <option value="1_year">1 Year</option>
                                    <option value="as_needed">As Needed</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Enhanced Form Actions -->
            <div class="bg-white dark:bg-gray-800 shadow-xl rounded-2xl border border-gray-100 dark:border-gray-700 p-8">
                <div class="flex flex-col lg:flex-row items-center justify-between space-y-4 lg:space-y-0">
                    <div class="flex items-center space-x-4">
                        <div class="flex items-center">
                            <input type="checkbox" name="save_as_template" id="save_as_template" class="h-5 w-5 text-purple-600 focus:ring-purple-500 border-gray-300 rounded">
                            <label for="save_as_template" class="ml-3 block text-sm font-medium text-gray-700 dark:text-gray-300">
                                <i class="fas fa-bookmark mr-1"></i>Save as template for future use
                            </label>
                        </div>
                        <div class="flex items-center">
                            <input type="checkbox" name="send_notification" id="send_notification" class="h-5 w-5 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            <label for="send_notification" class="ml-3 block text-sm font-medium text-gray-700 dark:text-gray-300">
                                <i class="fas fa-bell mr-1"></i>Notify patient
                            </label>
                        </div>
                    </div>
                    
                    <div class="flex items-center space-x-4">
                        <button type="button" onclick="previewReport()" class="bg-gradient-to-r from-indigo-500 to-purple-600 hover:from-indigo-600 hover:to-purple-700 text-white px-6 py-3 rounded-xl text-sm font-medium transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-1">
                            <i class="fas fa-eye mr-2"></i>Preview Report
                        </button>
                        <button type="submit" name="status" value="draft" class="bg-gradient-to-r from-orange-500 to-red-600 hover:from-orange-600 hover:to-red-700 text-white px-6 py-3 rounded-xl text-sm font-medium transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-1">
                            <i class="fas fa-save mr-2"></i>Save as Draft
                        </button>
                        <button type="submit" name="status" value="completed" class="bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white px-8 py-3 rounded-xl text-sm font-semibold transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-1">
                            <i class="fas fa-check-circle mr-2"></i>Complete Report
                        </button>
                    </div>
                </div>
            </div>
        </form>        <!-- Enhanced Quick Insert Templates -->
        <div class="bg-white dark:bg-gray-800 shadow-xl rounded-2xl border border-gray-100 dark:border-gray-700 form-section-transition">
            <div class="px-8 py-6 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-cyan-50 to-teal-50 dark:from-gray-700 dark:to-gray-600 rounded-t-2xl">
                <div class="flex items-center space-x-3">
                    <div class="bg-cyan-500 p-2 rounded-lg">
                        <i class="fas fa-magic text-white"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white">Quick Insert Templates</h3>
                    <span class="bg-cyan-100 dark:bg-cyan-800 text-cyan-800 dark:text-cyan-200 px-3 py-1 rounded-full text-xs font-medium">
                        Time Saver
                    </span>
                </div>
            </div>
            <div class="p-8">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <button type="button" class="group text-left p-6 border-2 border-green-200 dark:border-green-700 rounded-xl hover:bg-green-50 dark:hover:bg-green-900/20 hover:border-green-300 dark:hover:border-green-600 transition-all duration-200 transform hover:-translate-y-1 hover:shadow-lg" onclick="insertTemplate('normal_vitals')">
                        <div class="flex items-center space-x-3 mb-3">
                            <div class="bg-green-500 p-2 rounded-lg group-hover:bg-green-600 transition-colors">
                                <i class="fas fa-heartbeat text-white"></i>
                            </div>
                            <div class="font-semibold text-gray-900 dark:text-white">Normal Vitals</div>
                        </div>
                        <div class="text-sm text-gray-600 dark:text-gray-400">Insert standard vital signs values</div>
                    </button>
                    
                    <button type="button" class="group text-left p-6 border-2 border-blue-200 dark:border-blue-700 rounded-xl hover:bg-blue-50 dark:hover:bg-blue-900/20 hover:border-blue-300 dark:hover:border-blue-600 transition-all duration-200 transform hover:-translate-y-1 hover:shadow-lg" onclick="insertTemplate('routine_checkup')">
                        <div class="flex items-center space-x-3 mb-3">
                            <div class="bg-blue-500 p-2 rounded-lg group-hover:bg-blue-600 transition-colors">
                                <i class="fas fa-clipboard-check text-white"></i>
                            </div>
                            <div class="font-semibold text-gray-900 dark:text-white">Routine Checkup</div>
                        </div>
                        <div class="text-sm text-gray-600 dark:text-gray-400">Standard checkup template with common findings</div>
                    </button>
                    
                    <button type="button" class="group text-left p-6 border-2 border-purple-200 dark:border-purple-700 rounded-xl hover:bg-purple-50 dark:hover:bg-purple-900/20 hover:border-purple-300 dark:hover:border-purple-600 transition-all duration-200 transform hover:-translate-y-1 hover:shadow-lg" onclick="insertTemplate('follow_up')">
                        <div class="flex items-center space-x-3 mb-3">
                            <div class="bg-purple-500 p-2 rounded-lg group-hover:bg-purple-600 transition-colors">
                                <i class="fas fa-calendar-check text-white"></i>
                            </div>
                            <div class="font-semibold text-gray-900 dark:text-white">Follow-up Visit</div>
                        </div>
                        <div class="text-sm text-gray-600 dark:text-gray-400">Follow-up consultation template</div>
                    </button>
                    
                    <button type="button" class="group text-left p-6 border-2 border-red-200 dark:border-red-700 rounded-xl hover:bg-red-50 dark:hover:bg-red-900/20 hover:border-red-300 dark:hover:border-red-600 transition-all duration-200 transform hover:-translate-y-1 hover:shadow-lg" onclick="insertTemplate('emergency')">
                        <div class="flex items-center space-x-3 mb-3">
                            <div class="bg-red-500 p-2 rounded-lg group-hover:bg-red-600 transition-colors">
                                <i class="fas fa-ambulance text-white"></i>
                            </div>
                            <div class="font-semibold text-gray-900 dark:text-white">Emergency Visit</div>
                        </div>
                        <div class="text-sm text-gray-600 dark:text-gray-400">Emergency consultation template</div>
                    </button>
                    
                    <button type="button" class="group text-left p-6 border-2 border-yellow-200 dark:border-yellow-700 rounded-xl hover:bg-yellow-50 dark:hover:bg-yellow-900/20 hover:border-yellow-300 dark:hover:border-yellow-600 transition-all duration-200 transform hover:-translate-y-1 hover:shadow-lg" onclick="insertTemplate('physical_exam')">
                        <div class="flex items-center space-x-3 mb-3">
                            <div class="bg-yellow-500 p-2 rounded-lg group-hover:bg-yellow-600 transition-colors">
                                <i class="fas fa-search text-white"></i>
                            </div>
                            <div class="font-semibold text-gray-900 dark:text-white">Normal Physical Exam</div>
                        </div>
                        <div class="text-sm text-gray-600 dark:text-gray-400">Standard physical examination findings</div>
                    </button>
                    
                    <button type="button" class="group text-left p-6 border-2 border-indigo-200 dark:border-indigo-700 rounded-xl hover:bg-indigo-50 dark:hover:bg-indigo-900/20 hover:border-indigo-300 dark:hover:border-indigo-600 transition-all duration-200 transform hover:-translate-y-1 hover:shadow-lg" onclick="clearAllFields()">
                        <div class="flex items-center space-x-3 mb-3">
                            <div class="bg-indigo-500 p-2 rounded-lg group-hover:bg-indigo-600 transition-colors">
                                <i class="fas fa-eraser text-white"></i>
                            </div>
                            <div class="font-semibold text-gray-900 dark:text-white">Clear All Fields</div>
                        </div>
                        <div class="text-sm text-gray-600 dark:text-gray-400">Reset the form to start fresh</div>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Quick Tips Modal -->
<div id="quick-tips-modal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl max-w-2xl w-full max-h-[80vh] overflow-y-auto">
        <div class="px-8 py-6 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-blue-50 to-purple-50 dark:from-gray-700 dark:to-gray-600 rounded-t-2xl">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div class="bg-blue-500 p-2 rounded-lg">
                        <i class="fas fa-lightbulb text-white"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white">Quick Tips for Medical Reports</h3>
                </div>
                <button onclick="hideQuickTips()" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
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
                        <p class="text-gray-600 dark:text-gray-400 text-sm">Use the search box to quickly find patients by name, email, phone, or ID. Click on a result to auto-fill patient information.</p>
                    </div>
                </div>
                <div class="flex items-start space-x-3">
                    <div class="bg-blue-100 dark:bg-blue-800 p-2 rounded-lg">
                        <i class="fas fa-magic text-blue-600 dark:text-blue-300"></i>
                    </div>
                    <div>
                        <h4 class="font-semibold text-gray-900 dark:text-white">Quick Templates</h4>
                        <p class="text-gray-600 dark:text-gray-400 text-sm">Use template buttons to auto-fill common sections like normal vitals, routine checkups, or physical exam findings.</p>
                    </div>
                </div>
                <div class="flex items-start space-x-3">
                    <div class="bg-purple-100 dark:bg-purple-800 p-2 rounded-lg">
                        <i class="fas fa-keyboard text-purple-600 dark:text-purple-300"></i>
                    </div>
                    <div>
                        <h4 class="font-semibold text-gray-900 dark:text-white">Keyboard Shortcuts</h4>
                        <p class="text-gray-600 dark:text-gray-400 text-sm">Ctrl+S to save as draft, Ctrl+Enter to complete report, Ctrl+P to preview.</p>
                    </div>
                </div>
                <div class="flex items-start space-x-3">
                    <div class="bg-yellow-100 dark:bg-yellow-800 p-2 rounded-lg">
                        <i class="fas fa-save text-yellow-600 dark:text-yellow-300"></i>
                    </div>
                    <div>
                        <h4 class="font-semibold text-gray-900 dark:text-white">Auto-Save</h4>
                        <p class="text-gray-600 dark:text-gray-400 text-sm">Your progress is automatically saved every 30 seconds to prevent data loss.</p>
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
let autoSaveInterval;

document.addEventListener('DOMContentLoaded', function() {
    initializePatientSearch();
    setupAutoSave();
    setupKeyboardShortcuts();
    setupFormValidation();
});

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
        const patientData = JSON.parse(decodeURIComponent(encodedPatientData));
        
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
                field.classList.add('bg-green-50', 'dark:bg-green-900/20', 'border-green-300', 'dark:border-green-600');
                setTimeout(() => {
                    field.classList.remove('bg-green-50', 'dark:bg-green-900/20', 'border-green-300', 'dark:border-green-600');
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

// Auto-save functionality
function setupAutoSave() {
    autoSaveInterval = setInterval(() => {
        autoSaveForm();
    }, 30000); // Auto-save every 30 seconds
}

function autoSaveForm() {
    const formData = new FormData(document.querySelector('form'));
    const data = Object.fromEntries(formData.entries());
    
    // Store in localStorage
    localStorage.setItem('medicalReportDraft', JSON.stringify(data));
    
    // Show subtle notification
    showNotification('Draft auto-saved', 'info', 2000);
}

// Keyboard shortcuts
function setupKeyboardShortcuts() {
    document.addEventListener('keydown', function(e) {
        if (e.ctrlKey || e.metaKey) {
            switch(e.key) {
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

// Form validation
function setupFormValidation() {
    const form = document.querySelector('form');
    form.addEventListener('submit', function(e) {
        if (!validateForm()) {
            e.preventDefault();
            return false;
        }
    });
}

function validateForm() {
    const requiredFields = [
        { name: 'patient_id', label: 'Patient' },
        { name: 'consultation_date', label: 'Consultation Date' },
        { name: 'report_type', label: 'Report Type' }
    ];
    
    let isValid = true;
    let firstErrorField = null;
    
    requiredFields.forEach(field => {
        const element = document.querySelector(`[name="${field.name}"]`);
        if (!element || !element.value.trim()) {
            isValid = false;
            if (!firstErrorField) firstErrorField = element;
            
            // Add error styling
            element.classList.add('border-red-500', 'dark:border-red-400');
            
            setTimeout(() => {
                element.classList.remove('border-red-500', 'dark:border-red-400');
            }, 3000);
        }
    });
    
    if (!isValid) {
        showNotification('Please fill in all required fields', 'error');
        if (firstErrorField) {
            firstErrorField.focus();
            firstErrorField.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
    }
    
    return isValid;
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
    notification.className = `fixed top-4 right-4 ${colors[type]} text-white px-6 py-3 rounded-lg shadow-lg z-50 transform translate-x-full transition-transform duration-300`;
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

// Load saved draft on page load
window.addEventListener('load', function() {
    const savedDraft = localStorage.getItem('medicalReportDraft');
    if (savedDraft) {
        try {
            const data = JSON.parse(savedDraft);
            Object.keys(data).forEach(key => {
                const field = document.querySelector(`[name="${key}"]`);
                if (field && data[key]) {
                    field.value = data[key];
                }
            });
            showNotification('Draft loaded from auto-save', 'info');
        } catch (e) {
            console.error('Error loading draft:', e);
        }
    }
});
</script>
@endpush
@endsection
