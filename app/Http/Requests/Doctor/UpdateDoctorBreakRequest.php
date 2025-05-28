<?php

namespace App\Http\Requests\Doctor;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDoctorBreakRequest extends FormRequest
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
            'day_of_week' => 'required|string|in:monday,tuesday,wednesday,thursday,friday,saturday,sunday',
            'type' => 'required|string|in:lunch,personal,meeting,other',
            'is_recurring' => 'boolean',
            'recurring_days' => 'array|required_if:is_recurring,true',
            'recurring_days.*' => 'integer|between:0,6',
            'notes' => 'nullable|string|max:500',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */    public function messages(): array
    {
        return [
            'title.required' => 'Break title is required.',
            'title.string' => 'Break title must be a string.',
            'title.max' => 'Break title may not be greater than 255 characters.',
            'start_time.required' => 'Start time is required.',
            'start_time.date_format' => 'Start time must be in H:i format.',
            'end_time.required' => 'End time is required.',
            'end_time.date_format' => 'End time must be in H:i format.',
            'end_time.after' => 'End time must be after start time.',
            'day_of_week.required' => 'Day of week is required.',
            'day_of_week.in' => 'Invalid day of week selected.',
            'type.required' => 'Break type is required.',
            'type.in' => 'Invalid break type selected.',
            'recurring_days.required_if' => 'Please select at least one day for recurring breaks.',
            'notes.max' => 'Notes may not be greater than 500 characters.',
        ];
    }
}
