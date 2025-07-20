<?php

namespace App\Http\Requests\Patient;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class AppointmentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->hasRole('patient');
    }    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        // Default rules for index filtering
        $rules = [
            'status' => [
                'nullable',
                'string',
                Rule::in(['all', 'pending', 'confirmed', 'completed', 'cancelled', 'no_show'])
            ],
            'date' => [
                'nullable',
                'string',
                Rule::in(['all', 'upcoming', 'past', 'today', 'week', 'custom'])
            ],
            'from_date' => [
                'nullable',
                'date',
                'required_if:date,custom'
            ],
            'to_date' => [
                'nullable',
                'date',
                'after_or_equal:from_date',
                'required_if:date,custom'
            ],
            'page' => [
                'nullable',
                'integer',
                'min:1'
            ],
            'per_page' => [
                'nullable',
                'integer',
                'min:1',
                'max:50'
            ]
        ];

        // Add validation rules for cancellation requests
        if ($this->isMethod('patch') && $this->route()->getName() === 'patient.appointments.cancel') {
            $rules = array_merge($rules, [
                'cancellation_reason' => 'required|string|min:10|max:500'
            ]);
        }

        // Add validation rules for rating requests
        if ($this->isMethod('post') && $this->route()->getName() === 'patient.appointments.rate') {
            $rules = array_merge($rules, [
                'rating' => 'required|integer|min:1|max:5',
                'review' => 'nullable|string|max:1000',
            ]);
        }

        return $rules;
    }    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        $messages = [
            'status.in' => 'Invalid appointment status selected.',
            'date.in' => 'Invalid date filter selected.',
            'from_date.required_if' => 'From date is required when using custom date range.',
            'to_date.required_if' => 'To date is required when using custom date range.',
            'to_date.after_or_equal' => 'To date must be after or equal to from date.',
            'page.integer' => 'Page must be a valid number.',
            'page.min' => 'Page must be at least 1.',
            'per_page.integer' => 'Records per page must be a valid number.',
            'per_page.min' => 'Records per page must be at least 1.',
            'per_page.max' => 'Records per page cannot exceed 50.',
        ];

        // Add messages for cancellation and rating
        if ($this->isMethod('patch') && $this->route()->getName() === 'patient.appointments.cancel') {
            $messages = array_merge($messages, [
                'cancellation_reason.required' => 'Please provide a reason for cancelling the appointment.',
                'cancellation_reason.min' => 'Cancellation reason must be at least 10 characters long.',
                'cancellation_reason.max' => 'Cancellation reason cannot exceed 500 characters.',
            ]);
        }

        if ($this->isMethod('post') && $this->route()->getName() === 'patient.appointments.rate') {
            $messages = array_merge($messages, [
                'rating.required' => 'Please provide a rating for this appointment.',
                'rating.integer' => 'Rating must be a valid number.',
                'rating.min' => 'Rating must be at least 1 star.',
                'rating.max' => 'Rating cannot exceed 5 stars.',
                'review.max' => 'Review cannot exceed 1000 characters.',
            ]);
        }

        return $messages;
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Set default date filter to 'upcoming' if not provided or empty
        if (!$this->has('date') || empty($this->get('date'))) {
            $this->merge(['date' => 'upcoming']);
        }

        // Set default status to 'all' if not provided or empty
        if (!$this->has('status') || empty($this->get('status'))) {
            $this->merge(['status' => 'all']);
        }

        // Set default from_date to today if date filter is upcoming and no from_date is set
        if ($this->get('date') === 'upcoming' && !$this->has('from_date')) {
            $this->merge(['from_date' => Carbon::today()->format('Y-m-d')]);
        }

        // Clean up empty values
        $cleanedData = [];
        foreach ($this->all() as $key => $value) {
            if (!is_null($value) && $value !== '') {
                $cleanedData[$key] = $value;
            }
        }
        $this->replace($cleanedData);
    }

    /**
     * Get validated and sanitized query parameters.
     */
    public function getValidatedFilters(): array
    {
        $validated = $this->validated();
        
        return [
            'status' => $validated['status'] ?? 'all',
            'date' => $validated['date'] ?? 'upcoming',
            'from_date' => $validated['from_date'] ?? null,
            'to_date' => $validated['to_date'] ?? null,
            'page' => $validated['page'] ?? 1,
            'per_page' => min($validated['per_page'] ?? 10, 50), // Ensure max 50 per page
        ];
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

            // Validate appointment ownership for show page actions
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
            $config = \App\Models\AppointmentConfig::getActive();
            $validator->errors()->add(
                'appointment',
                'Appointments can only be cancelled at least ' . $config->cancellation_hours_limit . ' hours in advance.'
            );
            return;
        }

        // Check if cancellation is allowed in config
        $config = \App\Models\AppointmentConfig::getActive();
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
}
