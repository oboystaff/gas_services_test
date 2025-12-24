<?php

namespace App\Http\Requests\API\Sale;

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
            'name' => ['nullable', 'string'],
            'contact' => ['nullable', 'string'],
            'quantity_type' => ['required', 'string'],
            'kg' => ['required', 'numeric'],
            'amount' => ['required', 'numeric'],
            'community_id' => ['nullable', 'string', 'exists:communities,id'],
            'branch_id' => ['nullable', 'string', 'exists:branches,id'],
            'service_charge' => ['nullable', 'numeric'],
            'cid' => ['required', 'string', 'unique:sales,cid']
        ];
    }
}
