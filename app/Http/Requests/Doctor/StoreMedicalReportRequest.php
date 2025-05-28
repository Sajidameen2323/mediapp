<?php

namespace App\Http\Requests\Doctor;

use Illuminate\Foundation\Http\FormRequest;

class StoreMedicalReportRequest extends FormRequest
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
            'appointment_id' => 'nullable|exists:appointments,id',
            'consultation_date' => 'required|date',
            'chief_complaint' => 'required|string',
            'history_of_present_illness' => 'nullable|string',
            'past_medical_history' => 'nullable|string',
            'medications' => 'nullable|string',
            'allergies' => 'nullable|string',
            'social_history' => 'nullable|string',
            'family_history' => 'nullable|string',
            'physical_examination' => 'nullable|string',
            'vital_signs' => 'nullable|json',
            'diagnosis' => 'required|string',
            'treatment_plan' => 'required|string',
            'medications_prescribed' => 'nullable|string',
            'follow_up_instructions' => 'nullable|string',
            'follow_up_date' => 'nullable|date|after:consultation_date',
            'notes' => 'nullable|string',
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
            'consultation_date.required' => 'Consultation date is required.',
            'chief_complaint.required' => 'Chief complaint is required.',
            'diagnosis.required' => 'Diagnosis is required.',
            'treatment_plan.required' => 'Treatment plan is required.',
            'follow_up_date.after' => 'Follow-up date must be after consultation date.',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        if ($this->has('vital_signs')) {
            $vitalSigns = [];
            if ($this->input('blood_pressure')) {
                $vitalSigns['blood_pressure'] = $this->input('blood_pressure');
            }
            if ($this->input('heart_rate')) {
                $vitalSigns['heart_rate'] = $this->input('heart_rate');
            }
            if ($this->input('temperature')) {
                $vitalSigns['temperature'] = $this->input('temperature');
            }
            if ($this->input('respiratory_rate')) {
                $vitalSigns['respiratory_rate'] = $this->input('respiratory_rate');
            }
            if ($this->input('oxygen_saturation')) {
                $vitalSigns['oxygen_saturation'] = $this->input('oxygen_saturation');
            }
            if ($this->input('weight')) {
                $vitalSigns['weight'] = $this->input('weight');
            }
            if ($this->input('height')) {
                $vitalSigns['height'] = $this->input('height');
            }
            
            $this->merge(['vital_signs' => json_encode($vitalSigns)]);
        }
    }
}
