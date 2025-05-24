<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DoctorRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->user_type === 'admin';
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $doctorId = $this->route('doctor') ? $this->route('doctor')->id : null;
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . ($this->route('doctor') ? $this->route('doctor')->user_id : 'NULL'),
            'password' => $this->isMethod('POST') ? 'required|confirmed|min:8' : 'nullable|confirmed|min:8',
            'phone_number' => 'nullable|string|max:20',
            'gender' => 'nullable|in:male,female,other',
            'address' => 'nullable|string|max:500',
            'date_of_birth' => 'nullable|date|before:today',
            'specialization' => 'required|string|max:255',
            'qualifications' => 'required|string',
            'experience_years' => 'required|integer|min:0|max:50',
            'license_number' => 'required|string|unique:doctors,license_number,' . $doctorId,
            'consultation_fee' => 'required|numeric|min:0',
            'bio' => 'nullable|string|max:1000',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_available' => 'boolean',
            'schedules' => 'array',
            'schedules.*.day_of_week' => 'required|in:monday,tuesday,wednesday,thursday,friday,saturday,sunday',
            'schedules.*.is_available' => 'boolean',
            'services' => 'array',
            'services.*' => 'exists:services,id',
        ];
        // Only require start/end time if is_available is true
        if ($this->has('schedules')) {
            foreach ($this->input('schedules') as $idx => $schedule) {
                if (isset($schedule['is_available']) && $schedule['is_available']) {
                    $rules["schedules.$idx.start_time"] = 'required|date_format:H:i';
                    $rules["schedules.$idx.end_time"] = 'required|date_format:H:i|after:schedules.' . $idx . '.start_time';
                }
            }
        }
        return $rules;
    }
}
