<?php

namespace App\Http\Requests\GasRequest;

use Illuminate\Foundation\Http\FormRequest;

class CreateNonCustomerGasRequest extends FormRequest
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
            'name' => ['required', 'string'],
            'contact' => ['required', 'string'],
            'quantity_type' => ['nullable', 'string'],
            'value1' => ['nullable', 'numeric'],
            'value2' => ['nullable', 'numeric'],
            'community_id' => ['required', 'string', 'exists:communities,id'],
            'branch_id' => ['required', 'string', 'exists:branches,id']
        ];
    }

    public function messages(): array
    {
        return [
            'value1.required' => 'The gas quantity (KG)/gas amount (GHS) field is required.',
            'value1.numeric' => 'The gas quantity (KG)/gas amount (GHS) field must be a valid number.',
            'value2.required' => 'The gas quantity (KG)/gas amount (GHS) field is required.',
            'value2.numeric' => 'The gas quantity (KG)/gas amount (GHS) field must be a valid number.',
        ];
    }
}
