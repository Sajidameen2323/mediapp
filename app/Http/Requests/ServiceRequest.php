<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ServiceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->user_type === 'admin';
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $serviceId = $this->route('service') ? $this->route('service')->id : null;
        
        return [
            'name' => 'required|string|max:255|unique:services,name,' . $serviceId,
            'description' => 'nullable|string|max:1000',
            'price' => 'nullable|numeric|min:0',
            'duration_minutes' => 'nullable|integer|min:1|max:480',
            'category' => 'nullable|string|max:255',
            'is_active' => 'boolean',
        ];
    }
}
