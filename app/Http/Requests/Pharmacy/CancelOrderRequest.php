<?php

namespace App\Http\Requests\Pharmacy;

use Illuminate\Foundation\Http\FormRequest;

class CancelOrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user() && 
               $this->user()->user_type === 'pharmacist' && 
               $this->user()->pharmacy;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'cancellation_reason' => 'required|string|min:10|max:500',
        ];
    }

    /**
     * Get custom error messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'cancellation_reason.required' => 'Please provide a reason for cancelling this order.',
            'cancellation_reason.min' => 'Cancellation reason must be at least 10 characters.',
            'cancellation_reason.max' => 'Cancellation reason cannot exceed 500 characters.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'cancellation_reason' => 'cancellation reason',
        ];
    }
}
