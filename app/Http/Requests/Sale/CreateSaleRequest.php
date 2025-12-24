<?php

namespace App\Http\Requests\Sale;

use Illuminate\Foundation\Http\FormRequest;

class CreateSaleRequest extends FormRequest
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
            'customer_id' => ['nullable', 'string', 'exists:customers,customer_id'],
            'name' => ['nullable', 'string'],
            'contact' => ['nullable', 'string'],
            'community_id' => ['nullable', 'string', 'exists:communities,id'],
            'branch_id' => ['nullable', 'string', 'exists:branches,id'],
            'quantity_type' => ['required', 'string'],
            'value1' => ['required', 'numeric'],
            'value2' => ['required', 'numeric'],
            'service_charge' => ['nullable', 'numeric']
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
