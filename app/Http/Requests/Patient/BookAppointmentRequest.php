<?php

namespace App\Http\Requests\Patient;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Carbon\Carbon;
use App\Models\AppointmentConfig;
use App\Models\Doctor;
use App\Models\Appointment;

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
     */
    public function rules(): array
    {
        $config = AppointmentConfig::getActive();
        $maxDaysAhead = $config->max_booking_days_ahead;
        $minHoursAhead = $config->min_booking_hours_ahead;
        
        return [
            'doctor_id' => ['required', 'exists:doctors,id'],
            'service_id' => ['required', 'exists:services,id'],
            'appointment_date' => [
                'required',
                'date',
                'after_or_equal:today',
                function ($attribute, $value, $fail) use ($maxDaysAhead) {
                    // Check if the date is not more than the configured max days in advance
                    $maxDate = Carbon::now()->addDays($maxDaysAhead);
                    if (Carbon::parse($value)->gt($maxDate)) {
                        $fail("Appointments can only be booked up to {$maxDaysAhead} days in advance.");
                    }
                }
            ],
            'start_time' => [
                'required',
                'date_format:H:i',
                function ($attribute, $value, $fail) use ($minHoursAhead) {
                    // Validate appointment time is in the future and meets minimum hours ahead requirement
                    $appointmentDateTime = Carbon::parse($this->appointment_date . ' ' . $value);
                    $minBookingTime = Carbon::now()->addHours($minHoursAhead);
                    
                    if ($appointmentDateTime->lt($minBookingTime)) {
                        $fail("Appointment must be scheduled at least {$minHoursAhead} hours in advance.");
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
        $config = AppointmentConfig::getActive();
        
        return [
            'doctor_id.required' => 'Please select a doctor for your appointment.',
            'doctor_id.exists' => 'The selected doctor is not valid.',
            'service_id.required' => 'Please select a service for your appointment.',
            'service_id.exists' => 'The selected service is not valid.',
            'appointment_date.required' => 'Please select an appointment date.',
            'appointment_date.after_or_equal' => 'Appointment date cannot be in the past.',
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
                !$validator->errors()->has('start_time')) {
                
                $slotService = app(\App\Services\AppointmentSlotService::class);
                try {
                    // Explicitly cast doctor_id to integer
                    $doctorId = (int)$this->doctor_id;
                    $doctor = Doctor::findOrFail($doctorId);
                } catch (\Exception $e) {
                    $validator->errors()->add('doctor_id', 'Doctor not found.');
                    return;
                }
                
                $appointmentDateTime = Carbon::parse($this->appointment_date . ' ' . $this->start_time);
                
                // Get the appointment configuration
                $config = AppointmentConfig::getActive();
                
                // Check if the appointment is already booked
                $isAvailable = $slotService->isSlotAvailable(
                    $doctor,
                    $appointmentDateTime->toDateTimeString(),
                    (int) $this->service_id
                );

                if (!$isAvailable) {
                    $validator->errors()->add('start_time', 'The selected time slot is not available.');
                }
                
                // Check for maximum appointments per patient per day
                $patientId = auth()->id();
                $appointmentDate = Carbon::parse($this->appointment_date)->format('Y-m-d');
                
                $dailyAppointmentCount = Appointment::where('patient_id', $patientId)
                    ->whereDate('appointment_date', $appointmentDate)
                    ->whereNotIn('status', ['cancelled', 'no_show'])
                    ->count();
                
                if ($dailyAppointmentCount >= $config->max_appointments_per_patient_per_day) {
                    $validator->errors()->add('appointment_date', 
                        "You can only book a maximum of {$config->max_appointments_per_patient_per_day} appointments per day.");
                }
                
                // Check for maximum appointments per doctor per day
                $doctorDailyAppointmentCount = Appointment::where('doctor_id', $this->doctor_id)
                    ->whereDate('appointment_date', $appointmentDate)
                    ->whereNotIn('status', ['cancelled', 'no_show'])
                    ->count();
                
                if ($doctorDailyAppointmentCount >= $config->max_appointments_per_doctor_per_day) {
                    $validator->errors()->add('doctor_id', 
                        "The selected doctor has reached their maximum appointment limit for this day.");
                }
            }
        });
    }
}
