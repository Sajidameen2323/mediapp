<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateLaboratoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->can('admin-access');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $laboratoryId = $this->route('laboratory')->id ?? null;
        $userId = $this->route('laboratory')->user_id ?? null;

        return [
            // User Account Information
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($userId)],
            'password' => 'nullable|confirmed|min:8',
            'phone_number' => 'nullable|string|max:20',
            
            // Laboratory Information
            'lab_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'address' => 'required|string|max:500',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'postal_code' => 'required|string|max:20',
            'country' => 'required|string|max:100',
            'website' => 'nullable|url|max:255',
            'accreditation' => 'nullable|string|max:255',
            'emergency_contact' => 'nullable|string|max:20',
            'license_number' => ['required', 'string', Rule::unique('laboratories', 'license_number')->ignore($laboratoryId)],
            'services_offered' => 'nullable|array',
            'services_offered.*' => 'string',
            
            // Working Hours
            'opening_time' => 'required|date_format:H:i',
            'closing_time' => 'required|date_format:H:i|after:opening_time',
            'working_days' => 'required|array|min:1',
            'working_days.*' => 'string|in:monday,tuesday,wednesday,thursday,friday,saturday,sunday',
            
            // Financial Information
            'consultation_fee' => 'required|numeric|min:0',
            
            // Contact Information
            'contact_person_name' => 'nullable|string|max:255',
            'contact_person_phone' => 'nullable|string|max:20',
            
            // Equipment & Services
            'equipment_details' => 'nullable|string',
            'home_service_available' => 'boolean',
            'home_service_fee' => 'required_if:home_service_available,true|nullable|numeric|min:0',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'The contact person name is required.',
            'email.required' => 'The email address is required.',
            'email.unique' => 'This email address is already registered.',
            'password.confirmed' => 'The password confirmation does not match.',
            'password.min' => 'The password must be at least 8 characters.',
            'lab_name.required' => 'The laboratory name is required.',
            'description.string' => 'The description must be a string.',
            'address.required' => 'The address is required.',
            'city.required' => 'The city is required.',
            'state.required' => 'The state is required.',
            'postal_code.required' => 'The postal code is required.',
            'country.required' => 'The country is required.',
            'website.url' => 'The website must be a valid URL.',
            'accreditation.string' => 'The accreditation must be a string.',
            'emergency_contact.string' => 'The emergency contact must be a string.',
            'license_number.required' => 'The license number is required.',
            'license_number.unique' => 'This license number is already registered.',
            'services_offered.array' => 'The services offered field must be an array.',
            'opening_time.required' => 'The opening time is required.',
            'closing_time.required' => 'The closing time is required.',
            'closing_time.after' => 'The closing time must be after the opening time.',
            'working_days.required' => 'At least one working day must be selected.',
            'working_days.min' => 'At least one working day must be selected.',
            'consultation_fee.required' => 'The consultation fee is required.',
            'consultation_fee.numeric' => 'The consultation fee must be a number.',
            'consultation_fee.min' => 'The consultation fee must be at least 0.',
            'home_service_fee.required_if' => 'The home service fee is required when home service is available.',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Convert services_offered array to string for storage
        if ($this->has('services_offered') && is_array($this->services_offered)) {
            $this->merge([
                'services_offered_string' => implode(', ', $this->services_offered)
            ]);
        }

        // Ensure boolean fields are properly handled
        $this->merge([
            'home_service_available' => $this->boolean('home_service_available'),
        ]);
    }
}
