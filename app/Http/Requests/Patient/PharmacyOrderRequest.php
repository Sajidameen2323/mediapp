<?php

namespace App\Http\Requests\Patient;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PharmacyOrderRequest extends FormRequest
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
            'pharmacy_id' => [
                'required',
                'exists:pharmacies,id'
            ],
            'delivery_method' => [
                'required',
                'string',
                Rule::in(['pickup', 'delivery'])
            ],
            'delivery_address' => [
                'required_if:delivery_method,delivery',
                'nullable',
                'string',
                'max:500'
            ],
            'notes' => [
                'nullable',
                'string',
                'max:1000'
            ]
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'pharmacy_id.required' => 'Please select a pharmacy.',
            'pharmacy_id.exists' => 'The selected pharmacy is not available.',
            'delivery_method.required' => 'Please select a delivery method.',
            'delivery_method.in' => 'Please select either pickup or delivery.',
            'delivery_address.required_if' => 'Delivery address is required when delivery method is selected.',
            'delivery_address.max' => 'Delivery address cannot exceed 500 characters.',
            'notes.max' => 'Notes cannot exceed 1000 characters.'
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'pharmacy_id' => 'pharmacy',
            'delivery_method' => 'delivery method',
            'delivery_address' => 'delivery address',
            'notes' => 'notes'
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Clean up delivery address if pickup is selected
        if ($this->delivery_method === 'pickup') {
            $this->merge([
                'delivery_address' => null
            ]);
        }
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            // Additional validation logic can be added here if needed
            
            // Check if selected pharmacy is available
            if ($this->pharmacy_id) {
                $pharmacy = \App\Models\Pharmacy::find($this->pharmacy_id);
                if ($pharmacy && !$pharmacy->is_available) {
                    $validator->errors()->add('pharmacy_id', 'The selected pharmacy is currently not available.');
                }
            }
        });
    }
}
