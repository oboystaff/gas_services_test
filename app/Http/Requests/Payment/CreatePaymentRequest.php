<?php

namespace App\Http\Requests\Payment;

use Illuminate\Foundation\Http\FormRequest;

class CreatePaymentRequest extends FormRequest
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
            'invoice_no' => ['nullable', 'string', 'exists:invoices,invoice_no'],
            'customer_id' => ['required', 'string', 'exists:customers,customer_id'],
            'amount' => ['nullable', 'numeric'],
            'amount_paid' => ['required', 'numeric'],
            'payment_mode' => ['required', 'string', 'in:cash,cheque,bank transfer'],
            'cheque_no' => ['required_if:payment_mode,cheque', 'nullable', 'string',],
            'bank_name' => ['required_if:payment_mode,cheque,bank transfer', 'nullable', 'string',],
            'reference' => ['nullable', 'unique:payments,reference']
        ];
    }
}
