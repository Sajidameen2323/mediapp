<?php

namespace App\Http\Requests\Doctor;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMedicalReportRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->hasRole('doctor');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'patient_id' => 'required|exists:users,id',
            'report_type' => 'required|string',
            'consultation_date' => 'required|date',
            'chief_complaint' => 'required|string',
            'history_of_present_illness' => 'nullable|string',
            'physical_examination' => 'nullable|string',
            'vital_signs' => 'nullable|array',
            'assessment_diagnosis' => 'required|string',
            'treatment_plan' => 'required|string',
            'medications_prescribed' => 'nullable|string',
            'follow_up_instructions' => 'nullable|string',
            'additional_notes' => 'nullable|string',
            'lab_tests_ordered' => 'nullable|string',
            'imaging_studies' => 'nullable|string',
            'priority_level' => 'nullable|string|in:routine,urgent,emergency',
            'follow_up_required' => 'nullable|string',
            'status' => 'nullable|string|in:draft,completed',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'patient_id.required' => 'Please select a patient.',
            'patient_id.exists' => 'Selected patient does not exist.',
            'report_type.required' => 'Report type is required.',
            'consultation_date.required' => 'Consultation date is required.',
            'chief_complaint.required' => 'Chief complaint is required.',
            'assessment_diagnosis.required' => 'Assessment/Diagnosis is required.',
            'treatment_plan.required' => 'Treatment plan is required.',
            'priority_level.in' => 'Priority level must be routine, urgent, or emergency.',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Handle vital signs array
        $vitalSigns = [];

        // Collect vital signs from individual fields
        $vitalFields = [
            'blood_pressure',
            'heart_rate',
            'temperature',
            'respiratory_rate',
            'oxygen_saturation',
            'weight',
            'height'
        ];

        foreach ($vitalFields as $field) {
            $value = $this->input("vital_signs.{$field}");
            if ($value) {
                $vitalSigns[$field] = $value;
            }
        }

        // If we have vital signs, merge them as an array (not JSON string)
        if (!empty($vitalSigns)) {
            $this->merge(['vital_signs' => $vitalSigns]);
        }
    }
}
