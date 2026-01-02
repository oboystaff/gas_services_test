<?php

namespace App\Http\Requests\Customer;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCustomerRequest extends FormRequest
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
            'secondary_contact' => ['nullable', 'string'],
            'community_id' => ['required', 'array'],
            'community_id.*' => ['required', 'exists:communities,id'],
            'branch_id' => ['required', 'string', 'exists:branches,id'],
            'threshold_amount' => ['nullable', 'numeric', 'required_if:threshold,Y'],
            'due_date' => ['nullable', 'numeric']
        ];
    }
}
