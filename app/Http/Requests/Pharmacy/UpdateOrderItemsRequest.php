<?php

namespace App\Http\Requests\Pharmacy;

use Illuminate\Foundation\Http\FormRequest;

class UpdateOrderItemsRequest extends FormRequest
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
            'items' => 'required|array|min:1',
            'items.*.id' => 'required|exists:pharmacy_order_items,id',
            'items.*.quantity_dispensed' => 'required|integer|min:0',
            'items.*.unit_price' => 'required|numeric|min:0|max:999999.99',
            'items.*.is_available' => 'required|boolean',
            'items.*.pharmacy_notes' => 'nullable|string|max:500',
            'pharmacy_notes' => 'nullable|string|max:1000',
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
            'items.required' => 'At least one item must be specified.',
            'items.*.id.required' => 'Item ID is required.',
            'items.*.id.exists' => 'Invalid item specified.',
            'items.*.quantity_dispensed.required' => 'Quantity dispensed is required.',
            'items.*.quantity_dispensed.integer' => 'Quantity dispensed must be a whole number.',
            'items.*.quantity_dispensed.min' => 'Quantity dispensed cannot be negative.',
            'items.*.unit_price.required' => 'Unit price is required.',
            'items.*.unit_price.numeric' => 'Unit price must be a valid number.',
            'items.*.unit_price.min' => 'Unit price cannot be negative.',
            'items.*.unit_price.max' => 'Unit price is too high.',
            'items.*.is_available.required' => 'Availability status is required.',
            'items.*.is_available.boolean' => 'Availability must be true or false.',
            'items.*.pharmacy_notes.max' => 'Pharmacy notes cannot exceed 500 characters.',
            'pharmacy_notes.max' => 'General notes cannot exceed 1000 characters.',
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
            'items.*.quantity_dispensed' => 'quantity dispensed',
            'items.*.unit_price' => 'unit price',
            'items.*.is_available' => 'availability',
            'items.*.pharmacy_notes' => 'pharmacy notes',
            'pharmacy_notes' => 'general notes',
        ];
    }
}
