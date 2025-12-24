<?php

namespace App\Http\Requests\GasRequest;

use Illuminate\Foundation\Http\FormRequest;

class MarkAsDoneRequest extends FormRequest
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
            'quantity_type' => ['required', 'string'],
            'value1' => ['required', 'numeric'],
            'value2' => ['required', 'numeric'],
            'rep_name' => ['required', 'string'],
            'rep_contact' => ['required', 'regex:/^[0-9]{10}$/']
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
