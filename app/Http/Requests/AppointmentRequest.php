<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\AppointmentConfig;
use Carbon\Carbon;

class AppointmentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $config = AppointmentConfig::getActive();
        $maxBookingDate = now()->addDays($config->max_booking_days_ahead)->toDateString();
        $minBookingDateTime = now()->addHours($config->min_booking_hours_ahead);
        
        return [
            'doctor_id' => 'required|exists:doctors,id',
            'service_id' => 'required|exists:services,id',
            'appointment_date' => [
                'required',
                'date',
                'after_or_equal:today',
                "before_or_equal:$maxBookingDate"
            ],
            'start_time' => 'required|date_format:H:i',
            'patient_name' => 'required|string|max:255',
            'patient_phone' => 'required|string|max:20',
            'patient_email' => 'nullable|email|max:255',
            'patient_notes' => 'nullable|string|max:500',
            'special_instructions' => 'nullable|string|max:1000',
            'booking_source' => 'required|string|in:online,phone,walk_in,admin',
        ];
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $config = AppointmentConfig::getActive();
            
            // Check minimum booking time
            if ($this->appointment_date && $this->start_time) {
                $appointmentDateTime = Carbon::parse($this->appointment_date . ' ' . $this->start_time);
                $minBookingDateTime = now()->addHours($config->min_booking_hours_ahead);
                
                if ($appointmentDateTime->lt($minBookingDateTime)) {
                    $validator->errors()->add('start_time', 
                        "Appointment must be booked at least {$config->min_booking_hours_ahead} hours in advance.");
                }
            }
        });
    }

    /**
     * Get custom error messages.
     */
    public function messages(): array
    {
        return [
            'doctor_id.required' => 'Please select a doctor.',
            'doctor_id.exists' => 'Selected doctor is not available.',
            'service_id.required' => 'Please select a service.',
            'service_id.exists' => 'Selected service is not available.',
            'appointment_date.required' => 'Please select an appointment date.',
            'appointment_date.after_or_equal' => 'Appointment date cannot be in the past.',
            'start_time.required' => 'Please select an appointment time.',
            'patient_name.required' => 'Patient name is required.',
            'patient_phone.required' => 'Patient phone number is required.',
        ];
    }
}
