@extends('layouts.app')

@section('title', 'Create Medical Report - Doctor Dashboard')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Create Medical Report</h1>
                <p class="mt-2 text-gray-600 dark:text-gray-400">Document patient consultation and treatment</p>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('doctor.medical-reports') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors duration-200">
                    <i class="fas fa-arrow-left mr-2"></i>Back to Reports
                </a>
            </div>
        </div>
    </div>

    <!-- Form -->
    <form method="POST" action="{{ route('doctor.reports.store') }}" class="space-y-8">
        @csrf
        
        <!-- Patient and Basic Information -->
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">Patient Information</h3>
            </div>
            <div class="p-6 space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="patient_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Patient</label>
                        <select name="patient_id" id="patient_id" required class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-purple-500 focus:border-purple-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                            <option value="">Select a patient</option>
                            @foreach($patients as $patient)
                                <option value="{{ $patient->id }}">{{ $patient->name }} ({{ $patient->email }})</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div>
                        <label for="consultation_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Consultation Date</label>
                        <input type="date" name="consultation_date" id="consultation_date" value="{{ today()->format('Y-m-d') }}" required class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-purple-500 focus:border-purple-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                    </div>
                </div>
                
                <div>
                    <label for="report_type" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Report Type</label>
                    <select name="report_type" id="report_type" required class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-purple-500 focus:border-purple-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                        <option value="">Select report type</option>
                        <option value="Consultation">Initial Consultation</option>
                        <option value="Follow-up">Follow-up Visit</option>
                        <option value="Diagnosis">Diagnosis Report</option>
                        <option value="Treatment">Treatment Report</option>
                        <option value="Prescription">Prescription Report</option>
                        <option value="Other">Other</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Vital Signs -->
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">Vital Signs</h3>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4" id="vital-signs">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Blood Pressure</label>
                        <input type="text" name="vital_signs[blood_pressure]" placeholder="120/80 mmHg" class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-purple-500 focus:border-purple-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Temperature</label>
                        <input type="text" name="vital_signs[temperature]" placeholder="98.6°F" class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-purple-500 focus:border-purple-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Heart Rate</label>
                        <input type="text" name="vital_signs[heart_rate]" placeholder="70 bpm" class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-purple-500 focus:border-purple-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Weight</label>
                        <input type="text" name="vital_signs[weight]" placeholder="70 kg" class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-purple-500 focus:border-purple-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                    </div>
                </div>
            </div>
        </div>

        <!-- Clinical Assessment -->
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">Clinical Assessment</h3>
            </div>
            <div class="p-6 space-y-6">
                <div>
                    <label for="chief_complaint" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Chief Complaint</label>
                    <textarea name="chief_complaint" id="chief_complaint" rows="3" placeholder="Patient's primary concern or reason for visit..." class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-purple-500 focus:border-purple-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white"></textarea>
                </div>
                
                <div>
                    <label for="history_of_present_illness" class="block text-sm font-medium text-gray-700 dark:text-gray-300">History of Present Illness</label>
                    <textarea name="history_of_present_illness" id="history_of_present_illness" rows="4" placeholder="Detailed description of the current condition..." class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-purple-500 focus:border-purple-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white"></textarea>
                </div>
                
                <div>
                    <label for="physical_examination" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Physical Examination</label>
                    <textarea name="physical_examination" id="physical_examination" rows="4" placeholder="Findings from physical examination..." class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-purple-500 focus:border-purple-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white"></textarea>
                </div>
            </div>
        </div>

        <!-- Diagnosis and Treatment -->
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">Diagnosis & Treatment</h3>
            </div>
            <div class="p-6 space-y-6">
                <div>
                    <label for="assessment_diagnosis" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Assessment & Diagnosis</label>
                    <textarea name="assessment_diagnosis" id="assessment_diagnosis" rows="4" placeholder="Clinical diagnosis and assessment..." class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-purple-500 focus:border-purple-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white"></textarea>
                </div>
                
                <div>
                    <label for="treatment_plan" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Treatment Plan</label>
                    <textarea name="treatment_plan" id="treatment_plan" rows="4" placeholder="Recommended treatment and care plan..." class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-purple-500 focus:border-purple-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white"></textarea>
                </div>
                
                <div>
                    <label for="medications_prescribed" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Medications Prescribed</label>
                    <textarea name="medications_prescribed" id="medications_prescribed" rows="3" placeholder="List of prescribed medications with dosage and instructions..." class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-purple-500 focus:border-purple-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white"></textarea>
                </div>
            </div>
        </div>

        <!-- Follow-up and Notes -->
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">Follow-up & Additional Notes</h3>
            </div>
            <div class="p-6 space-y-6">
                <div>
                    <label for="follow_up_instructions" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Follow-up Instructions</label>
                    <textarea name="follow_up_instructions" id="follow_up_instructions" rows="3" placeholder="Instructions for next visit, timeline, etc..." class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-purple-500 focus:border-purple-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white"></textarea>
                </div>
                
                <div>
                    <label for="additional_notes" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Additional Notes</label>
                    <textarea name="additional_notes" id="additional_notes" rows="3" placeholder="Any additional observations or notes..." class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-purple-500 focus:border-purple-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white"></textarea>
                </div>
            </div>
        </div>

        <!-- Form Actions -->
        <div class="flex items-center justify-between bg-white dark:bg-gray-800 px-6 py-4 rounded-lg shadow">
            <div class="flex items-center">
                <input type="checkbox" name="save_as_template" id="save_as_template" class="h-4 w-4 text-purple-600 focus:ring-purple-500 border-gray-300 rounded">
                <label for="save_as_template" class="ml-2 block text-sm text-gray-700 dark:text-gray-300">
                    Save as template for future use
                </label>
            </div>
            
            <div class="flex items-center space-x-4">
                <button type="submit" name="status" value="draft" class="bg-orange-600 hover:bg-orange-700 text-white px-6 py-2 rounded-md text-sm font-medium transition-colors duration-200">
                    <i class="fas fa-save mr-2"></i>Save as Draft
                </button>
                <button type="submit" name="status" value="completed" class="bg-purple-600 hover:bg-purple-700 text-white px-6 py-2 rounded-md text-sm font-medium transition-colors duration-200">
                    <i class="fas fa-check mr-2"></i>Complete Report
                </button>
            </div>
        </div>
    </form>

    <!-- Quick Insert Templates -->
    <div class="mt-8">
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">Quick Insert Templates</h3>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <button type="button" class="text-left p-3 border border-gray-200 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200" onclick="insertTemplate('normal_vitals')">
                        <div class="font-medium text-gray-900 dark:text-white">Normal Vitals</div>
                        <div class="text-sm text-gray-500 dark:text-gray-400">Insert standard vital signs</div>
                    </button>
                    <button type="button" class="text-left p-3 border border-gray-200 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200" onclick="insertTemplate('routine_checkup')">
                        <div class="font-medium text-gray-900 dark:text-white">Routine Checkup</div>
                        <div class="text-sm text-gray-500 dark:text-gray-400">Standard checkup template</div>
                    </button>
                    <button type="button" class="text-left p-3 border border-gray-200 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200" onclick="insertTemplate('follow_up')">
                        <div class="font-medium text-gray-900 dark:text-white">Follow-up Visit</div>
                        <div class="text-sm text-gray-500 dark:text-gray-400">Follow-up consultation template</div>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function insertTemplate(templateType) {
    const templates = {
        normal_vitals: {
            'vital_signs[blood_pressure]': '120/80 mmHg',
            'vital_signs[temperature]': '98.6°F (37°C)',
            'vital_signs[heart_rate]': '72 bpm',
            'vital_signs[weight]': 'kg'
        },
        routine_checkup: {
            'chief_complaint': 'Routine health checkup',
            'physical_examination': 'General appearance: Alert and oriented\nVital signs: Within normal limits\nHEENT: Normal\nCardiovascular: Regular rate and rhythm\nRespiratory: Clear to auscultation bilaterally\nAbdomen: Soft, non-tender',
            'assessment_diagnosis': 'Routine health maintenance\nNo acute concerns identified',
            'treatment_plan': 'Continue current lifestyle\nRoutine follow-up as needed',
            'follow_up_instructions': 'Return for routine checkup in 1 year\nContact if any concerns arise'
        },
        follow_up: {
            'chief_complaint': 'Follow-up visit for previously diagnosed condition',
            'history_of_present_illness': 'Patient returns for follow-up of previously diagnosed condition. Reports compliance with treatment plan.',
            'assessment_diagnosis': 'Condition stable\nGood response to treatment',
            'follow_up_instructions': 'Continue current treatment\nReturn in [specify timeframe] for follow-up'
        }
    };
    
    const template = templates[templateType];
    if (template) {
        Object.keys(template).forEach(fieldName => {
            const field = document.querySelector(`[name="${fieldName}"]`);
            if (field) {
                field.value = template[fieldName];
            }
        });
    }
}
</script>
@endsection
