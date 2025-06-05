<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Carbon\Carbon;

class RescheduleAppointmentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user() && $this->user()->hasRole('admin');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'appointment_date' => 'required|date|after_or_equal:today',
            'appointment_time' => 'required|date_format:H:i',
            'reschedule_reason' => 'required|string|max:500|min:5',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'appointment_date.required' => 'Please select a new appointment date.',
            'appointment_date.after_or_equal' => 'Appointment date cannot be in the past.',
            'appointment_time.required' => 'Please select a new appointment time.',
            'appointment_time.date_format' => 'Please provide time in HH:MM format.',
            'reschedule_reason.required' => 'Please provide a reason for rescheduling.',
            'reschedule_reason.min' => 'Reschedule reason must be at least 5 characters.',
            'reschedule_reason.max' => 'Reschedule reason cannot exceed 500 characters.',
        ];
    }

    /**
     * Configure the validator instance.
     *
     * @param  \Illuminate\Validation\Validator  $validator
     * @return void
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if ($this->appointment_date && $this->appointment_time) {
                $appointmentDateTime = Carbon::parse($this->appointment_date . ' ' . $this->appointment_time);
                
                // Check if the appointment is at least 1 hour from now
                if ($appointmentDateTime->lessThan(now()->addHour())) {
                    $validator->errors()->add(
                        'appointment_time', 
                        'Appointment must be scheduled at least 1 hour in advance.'
                    );
                }
            }
        });
    }
}
