<?php

namespace App\Http\Requests\Patient;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class BookLabAppointmentRequest extends FormRequest
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
     */
    public function rules(): array
    {
        return [
            'lab_test_request_id' => [
                'required',
                'exists:lab_test_requests,id',
                function ($attribute, $value, $fail) {
                    $labTestRequest = \App\Models\LabTestRequest::find($value);
                    if ($labTestRequest && $labTestRequest->patient_id !== auth()->id()) {
                        $fail('You can only book appointments for your own lab test requests.');
                    }
                }
            ],
            'laboratory_id' => [
                'required',
                'exists:laboratories,id',
                function ($attribute, $value, $fail) {
                    $laboratory = \App\Models\Laboratory::find($value);
                    if ($laboratory && !$laboratory->is_available) {
                        $fail('The selected laboratory is not available for appointments.');
                    }
                }
            ],
            'appointment_date' => [
                'required',
                'date',
                'after:today',
                function ($attribute, $value, $fail) {
                    // Check if date is within 30 days
                    $appointmentDate = Carbon::parse($value);
                    $maxDate = Carbon::now()->addDays(30);
                    if ($appointmentDate->gt($maxDate)) {
                        $fail('Appointment date cannot be more than 30 days in advance.');
                    }
                }
            ],
            'start_time' => [
                'required',
                'date_format:H:i',
            ],
            'patient_notes' => 'nullable|string|max:500',
            'special_instructions' => 'nullable|string|max:1000',
        ];
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            // Check if laboratory is available on the selected date
            if ($this->filled(['laboratory_id', 'appointment_date'])) {
                $laboratory = \App\Models\Laboratory::find($this->laboratory_id);
                if ($laboratory && !$laboratory->isAvailableOnDate($this->appointment_date)) {
                    $validator->errors()->add('appointment_date', 'The laboratory is not available on the selected date.');
                }
            }

            // Check for appointment time conflicts
            if ($this->filled(['laboratory_id', 'appointment_date', 'start_time'])) {
                $existingAppointment = \App\Models\LabAppointment::where('laboratory_id', $this->laboratory_id)
                    ->where('appointment_date', $this->appointment_date)
                    ->where('start_time', $this->start_time)
                    ->whereNotIn('status', ['cancelled', 'rejected'])
                    ->exists();

                if ($existingAppointment) {
                    $validator->errors()->add('start_time', 'This time slot is already booked.');
                }
            }
        });
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'lab_test_request_id.required' => 'Lab test request is required.',
            'lab_test_request_id.exists' => 'The selected lab test request does not exist.',
            'laboratory_id.required' => 'Laboratory selection is required.',
            'laboratory_id.exists' => 'The selected laboratory does not exist.',
            'appointment_date.required' => 'Appointment date is required.',
            'appointment_date.after' => 'Appointment date must be in the future.',
            'start_time.required' => 'Appointment time is required.',
            'start_time.date_format' => 'Invalid time format.',
        ];
    }
}
