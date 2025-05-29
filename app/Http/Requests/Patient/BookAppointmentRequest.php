<?php

namespace App\Http\Requests\Patient;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class BookAppointmentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->hasRole('patient');
    }

    /**
     * Get the validation rules that apply to the request.
     */    public function rules(): array
    {
        return [
            'doctor_id' => ['required', 'exists:doctors,id'],
            'service_id' => ['required', 'exists:services,id'],
            'appointment_date' => [
                'required',
                'date',
                'after:today',
                function ($attribute, $value, $fail) {
                    // Check if the date is not more than 90 days in advance
                    $maxDate = Carbon::now()->addDays(90);
                    if (Carbon::parse($value)->gt($maxDate)) {
                        $fail('Appointments can only be booked up to 90 days in advance.');
                    }
                }
            ],
            'start_time' => [
                'required',
                'date_format:H:i',
                function ($attribute, $value, $fail) {
                    // Validate appointment time is in the future
                    $appointmentDateTime = Carbon::parse($this->appointment_date . ' ' . $value);
                    if ($appointmentDateTime->lte(Carbon::now())) {
                        $fail('Appointment time must be in the future.');
                    }
                }
            ],
            'reason' => ['required', 'string', 'max:500'],
            'symptoms' => ['nullable', 'string', 'max:1000'],
            'notes' => ['nullable', 'string', 'max:500'],
            'priority' => ['required', Rule::in(['low', 'medium', 'high', 'urgent'])],
            'appointment_type' => ['required', Rule::in(['consultation', 'follow_up', 'check_up', 'emergency'])],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'doctor_id.required' => 'Please select a doctor for your appointment.',
            'doctor_id.exists' => 'The selected doctor is not valid.',
            'service_id.required' => 'Please select a service for your appointment.',
            'service_id.exists' => 'The selected service is not valid.',
            'appointment_date.required' => 'Please select an appointment date.',
            'appointment_date.after' => 'Appointment date must be in the future.',
            'start_time.required' => 'Please select an appointment time.',
            'start_time.date_format' => 'Please provide a valid time format (HH:MM).',
            'reason.required' => 'Please provide a reason for your appointment.',
            'reason.max' => 'Reason cannot exceed 500 characters.',
            'symptoms.max' => 'Symptoms description cannot exceed 1000 characters.',
            'notes.max' => 'Additional notes cannot exceed 500 characters.',
            'priority.required' => 'Please select appointment priority.',
            'priority.in' => 'Please select a valid priority level.',
            'appointment_type.required' => 'Please select appointment type.',
            'appointment_type.in' => 'Please select a valid appointment type.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'doctor_id' => 'doctor',
            'service_id' => 'service',
            'appointment_date' => 'appointment date',
            'start_time' => 'start time',
            'reason' => 'reason for visit',
            'symptoms' => 'symptoms',
            'notes' => 'additional notes',
            'priority' => 'priority level',
            'appointment_type' => 'appointment type',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Ensure appointment_date and start_time are properly formatted
        if ($this->appointment_date) {
            $this->merge([
                'appointment_date' => Carbon::parse($this->appointment_date)->format('Y-m-d'),
            ]);
        }

        if ($this->start_time) {
            $this->merge([
                'start_time' => Carbon::parse($this->start_time)->format('H:i'),
            ]);
        }
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            // Additional validation to check slot availability
            if (!$validator->errors()->has('doctor_id') && 
                !$validator->errors()->has('service_id') && 
                !$validator->errors()->has('appointment_date') && 
                !$validator->errors()->has('start_time')) {                $slotService = app(\App\Services\AppointmentSlotService::class);
                $doctor = \App\Models\Doctor::find($this->doctor_id);
                
                if (!$doctor) {
                    $validator->errors()->add('doctor_id', 'Doctor not found.');
                    return;
                }
                $appointmentDateTime = Carbon::parse($this->appointment_date . ' ' . $this->start_time);
                
                $isAvailable = $slotService->isSlotAvailable(
                    $doctor,
                    $appointmentDateTime->toDateTimeString(),
                    $this->service_id
                );

                if (!$isAvailable) {
                    $validator->errors()->add('start_time', 'The selected time slot is not available.');
                }
            }
        });
    }
}
