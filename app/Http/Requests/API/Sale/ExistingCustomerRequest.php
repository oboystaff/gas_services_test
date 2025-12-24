<?php

namespace App\Http\Requests\API\Sale;

use Illuminate\Foundation\Http\FormRequest;

class ExistingCustomerRequest extends FormRequest
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
            'customer_id' => ['required', 'string', 'exists:customers,customer_id'],
            'quantity_type' => ['required', 'string', 'in:KG,GHS'],
            'kg' => ['required', 'numeric'],
            'amount' => ['required', 'numeric'],
            'service_charge' => ['nullable', 'numeric'],
            'cid' => ['required', 'string', 'unique:sales,cid']
        ];
    }
}
