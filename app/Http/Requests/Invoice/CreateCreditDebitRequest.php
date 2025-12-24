<?php

namespace App\Http\Requests\Invoice;

use Illuminate\Foundation\Http\FormRequest;

class CreateCreditDebitRequest extends FormRequest
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
            'invoice_no' => ['required', 'exists:invoices,invoice_no'],
            'customer_id' => ['required', 'exists:invoices,customer_id'],
            'note_type' => ['required', 'string'],
            'amount' => ['required', 'numeric'],
            'reason' => ['required', 'string']
        ];
    }
}
