<?php

namespace App\Http\Requests\API\Payment;

use Illuminate\Foundation\Http\FormRequest;

class MakePaymentRequest extends FormRequest
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
            'amount' => ['required', 'numeric'],
            'payment_mode' => ['required', 'string', 'in:momo'],
            'payment_source' => ['required', 'string', 'in:USSD'],
            'momo_number' => ['required', 'string'],
            'transaction_id' => ['required', 'string'],
            'transaction_status' => ['required', 'string', 'in:Success,Failed']
        ];
    }
}
