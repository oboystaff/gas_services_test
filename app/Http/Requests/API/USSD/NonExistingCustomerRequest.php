<?php

namespace App\Http\Requests\API\USSD;

use Illuminate\Foundation\Http\FormRequest;

class NonExistingCustomerRequest extends FormRequest
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
        return [
            'name' => ['required', 'string'],
            'contact' => ['required', 'string'],
            'quantity_type' => ['required', 'string'],
            'kg' => ['required', 'numeric'],
            'amount' => ['required', 'numeric'],
            'community' => ['required', 'string'],
            'branch_id' => ['nullable', 'string', 'exists:branches,id']
        ];
    }
}
