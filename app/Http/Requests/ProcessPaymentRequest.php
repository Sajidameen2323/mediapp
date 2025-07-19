<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\AppointmentConfig;

class ProcessPaymentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $config = AppointmentConfig::getActive();
        
        return [
            'amount' => ['required', 'numeric', 'min:0.01'],
            'payment_method' => ['required', 'string', 'in:' . implode(',', $config->accepted_payment_methods)],
            'appointment_id' => ['sometimes', 'integer', 'exists:appointments,id'],
            'card_number' => ['required_if:payment_method,card', 'nullable', 'string', 'size:16'],
            'card_expiry' => ['required_if:payment_method,card', 'nullable', 'string', 'size:5'],
            'card_cvc' => ['required_if:payment_method,card', 'nullable', 'string', 'size:3'],
            'cardholder_name' => ['required_if:payment_method,card', 'nullable', 'string', 'max:255'],
        ];
    }
    
    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'payment_method.in' => 'The selected payment method is not available.',
            'card_number.required_if' => 'The card number field is required when payment method is card.',
            'card_expiry.required_if' => 'The card expiry field is required when payment method is card.',
            'card_cvc.required_if' => 'The card CVC field is required when payment method is card.',
            'cardholder_name.required_if' => 'The cardholder name field is required when payment method is card.',
        ];
    }
}
