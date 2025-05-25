<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class HealthProfileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->user_type === 'patient';
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'blood_type' => 'nullable|string|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
            'height' => 'nullable|numeric|min:30|max:300',
            'weight' => 'nullable|numeric|min:2|max:300',
            'allergies' => 'nullable|string|max:1000',
            'medications' => 'nullable|string|max:1000',
            'medical_conditions' => 'nullable|string|max:1000',
            'emergency_contact_name' => 'nullable|string|max:255',
            'emergency_contact_phone' => 'nullable|string|max:20',
            'emergency_contact_relationship' => 'nullable|string|max:100',
            'insurance_provider' => 'nullable|string|max:255',
            'insurance_policy_number' => 'nullable|string|max:100',
            'family_history' => 'nullable|string|max:1000',
            'lifestyle_notes' => 'nullable|string|max:1000',
            'dietary_restrictions' => 'nullable|string|max:1000',
            'is_smoker' => 'boolean',
            'is_alcohol_consumer' => 'boolean',
            'exercise_frequency' => 'nullable|string|in:never,rarely,weekly,daily',
            'additional_notes' => 'nullable|string|max:1000',
        ];
    }

    /**
     * Get custom validation messages.
     */
    public function messages(): array
    {
        return [
            'blood_type.in' => 'Please select a valid blood type.',
            'height.min' => 'Height must be at least 30 cm.',
            'height.max' => 'Height cannot exceed 300 cm.',
            'weight.min' => 'Weight must be at least 2 kg.',
            'weight.max' => 'Weight cannot exceed 300 kg.',
            'exercise_frequency.in' => 'Please select a valid exercise frequency.',
        ];
    }
}
