<?php

namespace App\Http\Requests\Doctor;

use Illuminate\Foundation\Http\FormRequest;

class StoreDoctorBreakRequest extends FormRequest
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
     */    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'type' => 'required|string|in:lunch,personal,meeting,other',
            'day_of_week' => 'nullable|string|in:monday,tuesday,wednesday,thursday,friday,saturday,sunday',
            'selected_days' => 'nullable|array',
            'selected_days.*' => 'string|in:monday,tuesday,wednesday,thursday,friday,saturday,sunday',
            'specific_date' => 'nullable|date|after_or_equal:today',
            'is_recurring' => 'boolean',
            'notes' => 'nullable|string|max:500',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */    public function messages(): array
    {
        return [
            'title.required' => 'Break title is required.',
            'start_time.required' => 'Start time is required.',
            'end_time.after' => 'End time must be after start time.',
            'type.required' => 'Break type is required.',
            'type.in' => 'Invalid break type selected.',
            'selected_days.*.in' => 'Invalid day selected.',
            'specific_date.after_or_equal' => 'Break date cannot be in the past.',
        ];
    }
}
