<?php

namespace App\Http\Requests\CashRetirement;

use Illuminate\Foundation\Http\FormRequest;

class CreateCashRetirementRequest extends FormRequest
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
            'bank_name' => ['required', 'string'],
            'branch_name' => ['required', 'string'],
            'amount_retired' => ['required', 'numeric'],
            'account_number' => ['required', 'string'],
            'payment_slip' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048']
        ];
    }
}
