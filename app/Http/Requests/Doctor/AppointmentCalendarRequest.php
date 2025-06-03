<?php

namespace App\Http\Requests\Doctor;

use Illuminate\Foundation\Http\FormRequest;

class AppointmentCalendarRequest extends FormRequest
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
     */
    public function rules(): array
    {
        return [
            'format' => 'sometimes|string|in:json',
            'year' => 'sometimes|integer|min:2020|max:2030',
            'month' => 'sometimes|integer|min:1|max:12',
            'page' => 'sometimes|integer|min:1',
            'per_page' => 'sometimes|integer|min:1|max:100',
            'status' => 'sometimes|string|in:pending,confirmed,completed,cancelled,no_show',
            'search' => 'sometimes|string|max:255',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'format.in' => 'Format must be json.',
            'year.integer' => 'Year must be a valid integer.',
            'year.min' => 'Year must be at least 2020.',
            'year.max' => 'Year cannot be greater than 2030.',
            'month.integer' => 'Month must be a valid integer.',
            'month.min' => 'Month must be at least 1.',
            'month.max' => 'Month cannot be greater than 12.',
            'status.in' => 'Status must be one of: pending, confirmed, completed, cancelled, no_show.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'per_page' => 'items per page',
        ];
    }
}
