<?php

namespace App\Http\Requests\Patient;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\AppointmentConfig;

class AppointmentShowRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check() && 
               auth()->user()->tokenCan('patient-access');
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $rules = [];

        // Validate cancellation request
        if ($this->isMethod('patch') && $this->route()->getName() === 'patient.appointments.cancel') {
            $rules['cancellation_reason'] = 'required|string|min:10|max:500';
        }

        // Validate rating request
        if ($this->isMethod('post') && $this->route()->getName() === 'patient.appointments.rate') {
            $rules = [
                'rating' => 'required|integer|min:1|max:5',
                'review' => 'nullable|string|max:1000',
            ];
        }

        return $rules;
    }

    /**
     * Get custom validation messages.
     */
    public function messages(): array
    {
        return [
            'cancellation_reason.required' => 'Please provide a reason for cancelling the appointment.',
            'cancellation_reason.min' => 'Cancellation reason must be at least 10 characters long.',
            'cancellation_reason.max' => 'Cancellation reason cannot exceed 500 characters.',
            'rating.required' => 'Please provide a rating for this appointment.',
            'rating.integer' => 'Rating must be a valid number.',
            'rating.min' => 'Rating must be at least 1 star.',
            'rating.max' => 'Rating cannot exceed 5 stars.',
            'review.max' => 'Review cannot exceed 1000 characters.',
        ];
    }

    /**
     * Get validated data with additional processing.
     */
    public function validated($key = null, $default = null)
    {
        $validated = parent::validated($key, $default);

        // Additional processing for cancellation
        if ($this->isMethod('patch') && isset($validated['cancellation_reason'])) {
            $validated['cancellation_reason'] = trim($validated['cancellation_reason']);
        }

        // Additional processing for rating
        if ($this->isMethod('post') && isset($validated['review'])) {
            $validated['review'] = trim($validated['review']);
        }

        return $validated;
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $appointment = $this->route('appointment');
            
            if (!$appointment) {
                return;
            }

            // Validate appointment ownership
            if ($appointment->patient_id !== auth()->id()) {
                $validator->errors()->add('appointment', 'Unauthorized access to appointment.');
                return;
            }

            // Validate cancellation request
            if ($this->isMethod('patch') && $this->route()->getName() === 'patient.appointments.cancel') {
                $this->validateCancellation($validator, $appointment);
            }

            // Validate rating request
            if ($this->isMethod('post') && $this->route()->getName() === 'patient.appointments.rate') {
                $this->validateRating($validator, $appointment);
            }
        });
    }

    /**
     * Validate appointment cancellation.
     */
    protected function validateCancellation($validator, $appointment)
    {
        // Check if appointment can be cancelled
        if (in_array($appointment->status, ['cancelled', 'completed'])) {
            $validator->errors()->add('appointment', 'This appointment cannot be cancelled.');
            return;
        }

        // Check cancellation policy
        if (!$appointment->canBeCancelled()) {
            $config = AppointmentConfig::getActive();
            $validator->errors()->add(
                'appointment',
                'Appointments can only be cancelled at least ' . $config->cancellation_hours_limit . ' hours in advance.'
            );
            return;
        }

        // Check if cancellation is allowed in config
        $config = AppointmentConfig::getActive();
        if (!$config->allow_cancellation) {
            $validator->errors()->add('appointment', 'Appointment cancellation is not allowed.');
        }
    }    /**
     * Validate appointment rating.
     */
    protected function validateRating($validator, $appointment)
    {
        // Use the new canBeRated method which includes all necessary checks
        if (!$appointment->canBeRated()) {
            $validator->errors()->add('appointment', 'This appointment cannot be rated at this time.');
            return;
        }

        // Check if already rated using the new system
        if ($appointment->isRated()) {
            $validator->errors()->add('appointment', 'This appointment has already been rated.');
            return;
        }
    }

    /**
     * Handle a failed validation attempt.
     */
    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        if ($this->expectsJson()) {
            parent::failedValidation($validator);
        }

        // For non-AJAX requests, redirect back with errors
        throw new \Illuminate\Validation\ValidationException($validator, redirect()->back()->withErrors($validator)->withInput());
    }
}
