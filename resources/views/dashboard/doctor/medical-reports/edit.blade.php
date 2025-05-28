@extends('layouts.app')

@section('title', 'Edit Medical Report - Doctor Dashboard')

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
        <!-- Form Errors Section -->
        @if ($errors->any())
            <div class="mb-8 bg-red-50 dark:bg-red-900/20 border-l-4 border-red-400 dark:border-red-600 p-6 rounded-xl shadow-lg">
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
                        <button type="button" onclick="this.parentElement.parentElement.parentElement.style.display='none'" class="text-red-400 hover:text-red-600 dark:text-red-500 dark:hover:text-red-300 transition-colors">
                            <i class="fas fa-times text-lg"></i>
                        </button>
                    </div>
                </div>
            </div>
        @endif

        <!-- Enhanced Header -->
        <div class="mb-10">
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-8 border border-gray-100 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <div class="bg-gradient-to-r from-purple-500 to-indigo-600 p-3 rounded-xl">
                            <i class="fas fa-edit text-white text-2xl"></i>
                        </div>
                        <div>
                            <h1 class="text-4xl font-bold bg-gradient-to-r from-purple-600 to-indigo-600 bg-clip-text text-transparent">
                                Edit Medical Report
                            </h1>
                            <p class="mt-2 text-gray-600 dark:text-gray-400 text-lg">Update patient consultation and treatment details for report #{{ $medicalReport->id }}</p>
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
            </div>        </div>

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
        <form method="POST" action="{{ route('doctor.medical-reports.update', $medicalReport->id) }}" class="space-y-8">
            @csrf
            @method('PUT')
            
            <!-- Patient Information (Read-only for edit) -->
            <div class="bg-white dark:bg-gray-800 shadow-xl rounded-2xl border border-gray-100 dark:border-gray-700 form-section-transition">
                <div class="px-8 py-6 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-blue-50 to-purple-50 dark:from-gray-700 dark:to-gray-600 rounded-t-2xl">
                    <div class="flex items-center space-x-3">
                        <div class="bg-blue-500 p-2 rounded-lg">
                            <i class="fas fa-user-md text-white"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white">Patient Information</h3>
                    </div>
                </div>
                <div class="p-8 space-y-6">
                    <div class="bg-gradient-to-r from-green-50 to-emerald-50 dark:from-green-900/20 dark:to-emerald-900/20 border-2 border-green-200 dark:border-green-700 rounded-xl p-6">
                        <div class="flex items-center space-x-3 mb-4">
                            <div class="bg-green-500 p-2 rounded-lg">
                                <i class="fas fa-user-check text-white"></i>
                            </div>
                            <h4 class="text-lg font-semibold text-green-800 dark:text-green-300">Selected Patient</h4>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                            <div class="space-y-1">
                                <div class="font-medium text-gray-600 dark:text-gray-400">Name:</div>
                                <div class="text-gray-900 dark:text-white font-semibold">{{ $medicalReport->patient->name }}</div>
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

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        <div>
                            <label for="consultation_date" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                <i class="fas fa-calendar mr-2 text-purple-500"></i>Consultation Date
                            </label>
                            <input type="date" name="consultation_date" id="consultation_date" 
                                value="{{ old('consultation_date', $medicalReport->consultation_date->format('Y-m-d')) }}" required 
                                class="w-full border-2 @error('consultation_date') border-red-500 dark:border-red-400 @else border-gray-300 dark:border-gray-600 @enderror rounded-xl shadow-sm focus:ring-2 focus:ring-purple-500 focus:border-purple-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white py-3 px-4 transition-all duration-200">
                            @error('consultation_date')
                                <div class="mt-2 flex items-center text-red-600 dark:text-red-400 text-sm">
                                    <i class="fas fa-exclamation-circle mr-2"></i>
                                    <span>{{ $message }}</span>
                                </div>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="report_type" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                <i class="fas fa-file-alt mr-2 text-indigo-500"></i>Report Type
                            </label>
                            <select name="report_type" id="report_type" required 
                                class="w-full border-2 @error('report_type') border-red-500 dark:border-red-400 @else border-gray-300 dark:border-gray-600 @enderror rounded-xl shadow-sm focus:ring-2 focus:ring-purple-500 focus:border-purple-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white py-3 px-4 transition-all duration-200">
                                <option value="">Select report type</option>
                                <option value="Consultation" {{ old('report_type', $medicalReport->report_type) == 'Consultation' ? 'selected' : '' }}>Initial Consultation</option>
                                <option value="Follow-up" {{ old('report_type', $medicalReport->report_type) == 'Follow-up' ? 'selected' : '' }}>Follow-up Visit</option>
                                <option value="Diagnosis" {{ old('report_type', $medicalReport->report_type) == 'Diagnosis' ? 'selected' : '' }}>Diagnosis Report</option>
                                <option value="Treatment" {{ old('report_type', $medicalReport->report_type) == 'Treatment' ? 'selected' : '' }}>Treatment Report</option>
                                <option value="Prescription" {{ old('report_type', $medicalReport->report_type) == 'Prescription' ? 'selected' : '' }}>Prescription Report</option>
                                <option value="Emergency" {{ old('report_type', $medicalReport->report_type) == 'Emergency' ? 'selected' : '' }}>Emergency Visit</option>
                                <option value="Other" {{ old('report_type', $medicalReport->report_type) == 'Other' ? 'selected' : '' }}>Other</option>
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
                                value="{{ old('vital_signs.blood_pressure', $medicalReport->vital_signs['blood_pressure'] ?? '') }}"
                                class="vital-input w-full border-2 @error('vital_signs.blood_pressure') border-red-500 dark:border-red-400 @else border-red-300 dark:border-red-600 @enderror rounded-lg shadow-sm focus:ring-2 focus:ring-red-500 focus:border-red-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white py-3 px-4">
                            @error('vital_signs.blood_pressure')
                                <div class="mt-1 text-red-600 dark:text-red-400 text-xs">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="bg-gradient-to-br from-orange-50 to-yellow-50 dark:from-orange-900/20 dark:to-yellow-900/20 p-4 rounded-xl border border-orange-200 dark:border-orange-700">
                            <label class="block text-sm font-semibold text-orange-700 dark:text-orange-300 mb-2">
                                <i class="fas fa-thermometer-half mr-2"></i>Temperature
                            </label>
                            <input type="text" name="vital_signs[temperature]" placeholder="98.6°F" 
                                value="{{ old('vital_signs.temperature', $medicalReport->vital_signs['temperature'] ?? '') }}"
                                class="vital-input w-full border-2 @error('vital_signs.temperature') border-red-500 dark:border-red-400 @else border-orange-300 dark:border-orange-600 @enderror rounded-lg shadow-sm focus:ring-2 focus:ring-orange-500 focus:border-orange-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white py-3 px-4">
                            @error('vital_signs.temperature')
                                <div class="mt-1 text-red-600 dark:text-red-400 text-xs">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="bg-gradient-to-br from-blue-50 to-cyan-50 dark:from-blue-900/20 dark:to-cyan-900/20 p-4 rounded-xl border border-blue-200 dark:border-blue-700">
                            <label class="block text-sm font-semibold text-blue-700 dark:text-blue-300 mb-2">
                                <i class="fas fa-heartbeat mr-2"></i>Heart Rate
                            </label>
                            <input type="text" name="vital_signs[heart_rate]" placeholder="70 bpm" 
                                value="{{ old('vital_signs.heart_rate', $medicalReport->vital_signs['heart_rate'] ?? '') }}"
                                class="vital-input w-full border-2 @error('vital_signs.heart_rate') border-red-500 dark:border-red-400 @else border-blue-300 dark:border-blue-600 @enderror rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white py-3 px-4">
                            @error('vital_signs.heart_rate')
                                <div class="mt-1 text-red-600 dark:text-red-400 text-xs">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="bg-gradient-to-br from-purple-50 to-indigo-50 dark:from-purple-900/20 dark:to-indigo-900/20 p-4 rounded-xl border border-purple-200 dark:border-purple-700">
                            <label class="block text-sm font-semibold text-purple-700 dark:text-purple-300 mb-2">
                                <i class="fas fa-weight mr-2"></i>Weight
                            </label>
                            <input type="text" name="vital_signs[weight]" placeholder="70 kg" 
                                value="{{ old('vital_signs.weight', $medicalReport->vital_signs['weight'] ?? '') }}"
                                class="vital-input w-full border-2 @error('vital_signs.weight') border-red-500 dark:border-red-400 @else border-purple-300 dark:border-purple-600 @enderror rounded-lg shadow-sm focus:ring-2 focus:ring-purple-500 focus:border-purple-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white py-3 px-4">
                            @error('vital_signs.weight')
                                <div class="mt-1 text-red-600 dark:text-red-400 text-xs">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                <i class="fas fa-lungs mr-2 text-teal-500"></i>Respiratory Rate
                            </label>
                            <input type="text" name="vital_signs[respiratory_rate]" placeholder="16 breaths/min" 
                                value="{{ old('vital_signs.respiratory_rate', $medicalReport->vital_signs['respiratory_rate'] ?? '') }}"
                                class="w-full border-2 @error('vital_signs.respiratory_rate') border-red-500 dark:border-red-400 @else border-gray-300 dark:border-gray-600 @enderror rounded-lg shadow-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white py-3 px-4">
                            @error('vital_signs.respiratory_rate')
                                <div class="mt-2 flex items-center text-red-600 dark:text-red-400 text-sm">
                                    <i class="fas fa-exclamation-circle mr-2"></i>
                                    <span>{{ $message }}</span>
                                </div>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                <i class="fas fa-percentage mr-2 text-blue-500"></i>Oxygen Saturation
                            </label>
                            <input type="text" name="vital_signs[oxygen_saturation]" placeholder="98%" 
                                value="{{ old('vital_signs.oxygen_saturation', $medicalReport->vital_signs['oxygen_saturation'] ?? '') }}"
                                class="w-full border-2 @error('vital_signs.oxygen_saturation') border-red-500 dark:border-red-400 @else border-gray-300 dark:border-gray-600 @enderror rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white py-3 px-4">
                            @error('vital_signs.oxygen_saturation')
                                <div class="mt-2 flex items-center text-red-600 dark:text-red-400 text-sm">
                                    <i class="fas fa-exclamation-circle mr-2"></i>
                                    <span>{{ $message }}</span>
                                </div>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                <i class="fas fa-ruler-vertical mr-2 text-green-500"></i>Height
                            </label>
                            <input type="text" name="vital_signs[height]" placeholder="170 cm" 
                                value="{{ old('vital_signs.height', $medicalReport->vital_signs['height'] ?? '') }}"
                                class="w-full border-2 @error('vital_signs.height') border-red-500 dark:border-red-400 @else border-gray-300 dark:border-gray-600 @enderror rounded-lg shadow-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white py-3 px-4">
                            @error('vital_signs.height')
                                <div class="mt-2 flex items-center text-red-600 dark:text-red-400 text-sm">
                                    <i class="fas fa-exclamation-circle mr-2"></i>
                                    <span>{{ $message }}</span>
                                </div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Enhanced Clinical Assessment -->
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
                                class="w-full border-2 @error('chief_complaint') border-red-500 dark:border-red-400 @else border-gray-300 dark:border-gray-600 @enderror rounded-xl shadow-sm focus:ring-2 focus:ring-purple-500 focus:border-purple-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white p-4 resize-none transition-all duration-200">{{ old('chief_complaint', $medicalReport->chief_complaint) }}</textarea>
                            @error('chief_complaint')
                                <div class="mt-2 flex items-center text-red-600 dark:text-red-400 text-sm">
                                    <i class="fas fa-exclamation-circle mr-2"></i>
                                    <span>{{ $message }}</span>
                                </div>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="history_of_present_illness" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                <i class="fas fa-history mr-2 text-blue-500"></i>History of Present Illness
                            </label>
                            <textarea name="history_of_present_illness" id="history_of_present_illness" rows="4" 
                                placeholder="Detailed description of the current condition..." 
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
                        <label for="physical_examination" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                            <i class="fas fa-search mr-2 text-green-500"></i>Physical Examination
                        </label>
                        <textarea name="physical_examination" id="physical_examination" rows="5" 
                            placeholder="Detailed findings from physical examination..." 
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
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Cardiovascular</label>
                                <input type="text" name="systems_review[cardiovascular]" placeholder="Normal, abnormal findings..." 
                                    value="{{ old('systems_review.cardiovascular', $medicalReport->systems_review['cardiovascular'] ?? '') }}"
                                    class="w-full border @error('systems_review.cardiovascular') border-red-500 dark:border-red-400 @else border-gray-300 dark:border-gray-600 @enderror rounded-lg px-3 py-2 bg-white dark:bg-gray-600 text-gray-900 dark:text-white">
                                @error('systems_review.cardiovascular')
                                    <div class="mt-1 text-red-600 dark:text-red-400 text-xs">{{ $message }}</div>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Respiratory</label>
                                <input type="text" name="systems_review[respiratory]" placeholder="Normal, abnormal findings..." 
                                    value="{{ old('systems_review.respiratory', $medicalReport->systems_review['respiratory'] ?? '') }}"
                                    class="w-full border @error('systems_review.respiratory') border-red-500 dark:border-red-400 @else border-gray-300 dark:border-gray-600 @enderror rounded-lg px-3 py-2 bg-white dark:bg-gray-600 text-gray-900 dark:text-white">
                                @error('systems_review.respiratory')
                                    <div class="mt-1 text-red-600 dark:text-red-400 text-xs">{{ $message }}</div>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Gastrointestinal</label>
                                <input type="text" name="systems_review[gastrointestinal]" placeholder="Normal, abnormal findings..." 
                                    value="{{ old('systems_review.gastrointestinal', $medicalReport->systems_review['gastrointestinal'] ?? '') }}"
                                    class="w-full border @error('systems_review.gastrointestinal') border-red-500 dark:border-red-400 @else border-gray-300 dark:border-gray-600 @enderror rounded-lg px-3 py-2 bg-white dark:bg-gray-600 text-gray-900 dark:text-white">
                                @error('systems_review.gastrointestinal')
                                    <div class="mt-1 text-red-600 dark:text-red-400 text-xs">{{ $message }}</div>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Neurological</label>
                                <input type="text" name="systems_review[neurological]" placeholder="Normal, abnormal findings..." 
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
                                class="w-full border-2 @error('assessment_diagnosis') border-red-500 dark:border-red-400 @else border-gray-300 dark:border-gray-600 @enderror rounded-xl shadow-sm focus:ring-2 focus:ring-purple-500 focus:border-purple-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white p-4 resize-none transition-all duration-200">{{ old('assessment_diagnosis', $medicalReport->assessment_diagnosis) }}</textarea>
                            @error('assessment_diagnosis')
                                <div class="mt-2 flex items-center text-red-600 dark:text-red-400 text-sm">
                                    <i class="fas fa-exclamation-circle mr-2"></i>
                                    <span>{{ $message }}</span>
                                </div>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="treatment_plan" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                <i class="fas fa-clipboard-check mr-2 text-green-500"></i>Treatment Plan
                            </label>
                            <textarea name="treatment_plan" id="treatment_plan" rows="5" 
                                placeholder="Recommended treatment and care plan..." 
                                class="w-full border-2 @error('treatment_plan') border-red-500 dark:border-red-400 @else border-gray-300 dark:border-gray-600 @enderror rounded-xl shadow-sm focus:ring-2 focus:ring-purple-500 focus:border-purple-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white p-4 resize-none transition-all duration-200">{{ old('treatment_plan', $medicalReport->treatment_plan) }}</textarea>
                            @error('treatment_plan')
                                <div class="mt-2 flex items-center text-red-600 dark:text-red-400 text-sm">
                                    <i class="fas fa-exclamation-circle mr-2"></i>
                                    <span>{{ $message }}</span>
                                </div>
                            @enderror
                        </div>
                    </div>
                    
                    <div>
                        <label for="medications_prescribed" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                            <i class="fas fa-pills mr-2 text-blue-500"></i>Medications Prescribed
                        </label>
                        <textarea name="medications_prescribed" id="medications_prescribed" rows="4" 
                            placeholder="List of prescribed medications with dosage and instructions..." 
                            class="w-full border-2 @error('medications_prescribed') border-red-500 dark:border-red-400 @else border-gray-300 dark:border-gray-600 @enderror rounded-xl shadow-sm focus:ring-2 focus:ring-purple-500 focus:border-purple-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white p-4 resize-none transition-all duration-200">{{ old('medications_prescribed', $medicalReport->medications_prescribed) }}</textarea>
                        @error('medications_prescribed')
                            <div class="mt-2 flex items-center text-red-600 dark:text-red-400 text-sm">
                                <i class="fas fa-exclamation-circle mr-2"></i>
                                <span>{{ $message }}</span>
                            </div>
                        @enderror
                        <div class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                            <i class="fas fa-info-circle mr-1"></i>
                            Format: Medication name - Dosage - Frequency - Duration
                        </div>
                    </div>

                    <div class="bg-blue-50 dark:bg-blue-900/20 p-6 rounded-xl border border-blue-200 dark:border-blue-700">
                        <h4 class="text-lg font-semibold text-blue-800 dark:text-blue-300 mb-4">
                            <i class="fas fa-flask mr-2"></i>Lab Tests & Investigations
                        </h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tests Ordered</label>
                                <textarea name="lab_tests_ordered" rows="3" 
                                    placeholder="List any lab tests or investigations ordered..." 
                                    class="w-full border @error('lab_tests_ordered') border-red-500 dark:border-red-400 @else border-gray-300 dark:border-gray-600 @enderror rounded-lg px-3 py-2 bg-white dark:bg-gray-600 text-gray-900 dark:text-white">{{ old('lab_tests_ordered', $medicalReport->lab_tests_ordered) }}</textarea>
                                @error('lab_tests_ordered')
                                    <div class="mt-1 text-red-600 dark:text-red-400 text-xs">{{ $message }}</div>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Imaging Studies</label>
                                <textarea name="imaging_studies" rows="3" 
                                    placeholder="X-rays, CT scans, MRI, etc..." 
                                    class="w-full border @error('imaging_studies') border-red-500 dark:border-red-400 @else border-gray-300 dark:border-gray-600 @enderror rounded-lg px-3 py-2 bg-white dark:bg-gray-600 text-gray-900 dark:text-white">{{ old('imaging_studies', $medicalReport->imaging_studies) }}</textarea>
                                @error('imaging_studies')
                                    <div class="mt-1 text-red-600 dark:text-red-400 text-xs">{{ $message }}</div>
                                @enderror
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
                                class="w-full border-2 @error('follow_up_instructions') border-red-500 dark:border-red-400 @else border-gray-300 dark:border-gray-600 @enderror rounded-xl shadow-sm focus:ring-2 focus:ring-purple-500 focus:border-purple-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white p-4 resize-none transition-all duration-200">{{ old('follow_up_instructions', $medicalReport->follow_up_instructions) }}</textarea>
                            @error('follow_up_instructions')
                                <div class="mt-2 flex items-center text-red-600 dark:text-red-400 text-sm">
                                    <i class="fas fa-exclamation-circle mr-2"></i>
                                    <span>{{ $message }}</span>
                                </div>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="additional_notes" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
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

                    <div class="bg-yellow-50 dark:bg-yellow-900/20 p-6 rounded-xl border border-yellow-200 dark:border-yellow-700">
                        <h4 class="text-lg font-semibold text-yellow-800 dark:text-yellow-300 mb-4">
                            <i class="fas fa-exclamation-triangle mr-2"></i>Priority & Urgency
                        </h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Priority Level</label>
                                <select name="priority_level" 
                                    class="w-full border @error('priority_level') border-red-500 dark:border-red-400 @else border-gray-300 dark:border-gray-600 @enderror rounded-lg px-3 py-2 bg-white dark:bg-gray-600 text-gray-900 dark:text-white">
                                    <option value="routine" {{ old('priority_level', $medicalReport->priority_level) == 'routine' ? 'selected' : '' }}>Routine</option>
                                    <option value="urgent" {{ old('priority_level', $medicalReport->priority_level) == 'urgent' ? 'selected' : '' }}>Urgent</option>
                                    <option value="emergency" {{ old('priority_level', $medicalReport->priority_level) == 'emergency' ? 'selected' : '' }}>Emergency</option>
                                </select>
                                @error('priority_level')
                                    <div class="mt-1 text-red-600 dark:text-red-400 text-xs">{{ $message }}</div>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Follow-up Required</label>
                                <select name="follow_up_required" 
                                    class="w-full border @error('follow_up_required') border-red-500 dark:border-red-400 @else border-gray-300 dark:border-gray-600 @enderror rounded-lg px-3 py-2 bg-white dark:bg-gray-600 text-gray-900 dark:text-white">
                                    <option value="no" {{ old('follow_up_required', $medicalReport->follow_up_required) == 'no' ? 'selected' : '' }}>No Follow-up Needed</option>
                                    <option value="1_week" {{ old('follow_up_required', $medicalReport->follow_up_required) == '1_week' ? 'selected' : '' }}>1 Week</option>
                                    <option value="2_weeks" {{ old('follow_up_required', $medicalReport->follow_up_required) == '2_weeks' ? 'selected' : '' }}>2 Weeks</option>
                                    <option value="1_month" {{ old('follow_up_required', $medicalReport->follow_up_required) == '1_month' ? 'selected' : '' }}>1 Month</option>
                                    <option value="3_months" {{ old('follow_up_required', $medicalReport->follow_up_required) == '3_months' ? 'selected' : '' }}>3 Months</option>
                                    <option value="6_months" {{ old('follow_up_required', $medicalReport->follow_up_required) == '6_months' ? 'selected' : '' }}>6 Months</option>
                                    <option value="1_year" {{ old('follow_up_required', $medicalReport->follow_up_required) == '1_year' ? 'selected' : '' }}>1 Year</option>
                                    <option value="as_needed" {{ old('follow_up_required', $medicalReport->follow_up_required) == 'as_needed' ? 'selected' : '' }}>As Needed</option>
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
            <div class="bg-white dark:bg-gray-800 shadow-xl rounded-2xl border border-gray-100 dark:border-gray-700 p-8">
                <div class="flex flex-col lg:flex-row items-center justify-between space-y-4 lg:space-y-0">
                    <div class="flex items-center space-x-4">
                        <div class="flex items-center">
                            <input type="checkbox" name="save_as_template" id="save_as_template" 
                                {{ old('save_as_template', $medicalReport->save_as_template) ? 'checked' : '' }}
                                class="h-5 w-5 text-purple-600 focus:ring-purple-500 border-gray-300 rounded">
                            <label for="save_as_template" class="ml-3 block text-sm font-medium text-gray-700 dark:text-gray-300">
                                <i class="fas fa-bookmark mr-1"></i>Save as template for future use
                            </label>
                        </div>
                        <div class="flex items-center">
                            <input type="checkbox" name="send_notification" id="send_notification" 
                                {{ old('send_notification', $medicalReport->send_notification) ? 'checked' : '' }}
                                class="h-5 w-5 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            <label for="send_notification" class="ml-3 block text-sm font-medium text-gray-700 dark:text-gray-300">
                                <i class="fas fa-bell mr-1"></i>Notify patient
                            </label>
                        </div>
                    </div>
                    <div class="flex items-center space-x-4">
                        <button type="button" onclick="previewReport()" class="bg-gradient-to-r from-gray-500 to-gray-600 hover:from-gray-600 hover:to-gray-700 text-white px-6 py-3 rounded-xl text-sm font-medium transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-1">
                            <i class="fas fa-eye mr-2"></i>Preview Report
                        </button>
                        <button type="submit" name="status" value="draft" class="bg-gradient-to-r from-orange-500 to-red-600 hover:from-orange-600 hover:to-red-700 text-white px-6 py-3 rounded-xl text-sm font-medium transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-1">
                            <i class="fas fa-save mr-2"></i>Save as Draft
                        </button>
                        <button type="submit" name="status" value="completed" class="bg-gradient-to-r from-blue-500 to-indigo-600 hover:from-blue-600 hover:to-indigo-700 text-white px-8 py-3 rounded-xl text-sm font-semibold transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-1">
                            <i class="fas fa-check-circle mr-2"></i>Update Report
                        </button>
                    </div>
                </div>
            </div>
        </form>

        <!-- Enhanced Quick Insert Templates -->
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
let autoSaveInterval;

document.addEventListener('DOMContentLoaded', function() {
    setupAutoSave();
    setupKeyboardShortcuts();
    setupFormErrorHandling();
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
        if (input.name !== '_token' && input.name !== '_method' && input.name !== 'patient_id') { // Don't clear hidden fields or patient_id
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
    errorSection.scrollIntoView({ behavior: 'smooth' });
}

function hideFormErrors() {
    document.getElementById('form-errors-section').classList.add('hidden');
}

// Utility for notifications (toast-like messages)
function showNotification(message, type = 'info', duration = 3000) {
    const container = document.createElement('div');
    container.className = `fixed top-5 right-5 p-4 rounded-lg shadow-xl text-white z-[100] transition-all duration-300 transform translate-x-full`;
    
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

</script>
@endpush

@endsection
