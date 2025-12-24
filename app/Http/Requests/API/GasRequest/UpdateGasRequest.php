<?php

namespace App\Http\Requests\API\GasRequest;

use Illuminate\Foundation\Http\FormRequest;

class UpdateGasRequest extends FormRequest
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
            'quantity_type' => ['required', 'string', 'in:KG,GHS'],
            'kg' => ['required', 'numeric'],
            'amount' => ['required', 'numeric'],
            'attachment' => ['required', 'file', 'mimes:jpeg,jpg,png,gif'],
            'rep_name' => ['required', 'string'],
            'rep_contact' => ['required', 'regex:/^[0-9]{10}$/']
        ];
    }
}
