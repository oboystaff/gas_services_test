<?php

namespace App\Http\Requests\API\USSD;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Customer;


class ExistingCustomerRequest extends FormRequest
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
            'quantity_type' => ['nullable', 'string', 'in:KG,GHS'],
            'kg' => ['nullable', 'numeric'],
            'amount' => ['nullable', 'numeric'],
            'delivery_branch' => ['required', 'numeric'],
            'request_contact' => ['required', 'string']
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $customerId = $this->input('customer_id');
            $deliveryBranch = $this->input('delivery_branch');

            if ($customerId) {
                $customer = Customer::select('community_id')->where('customer_id', $customerId)->first();

                if ($customer) {
                    // Convert community_id to array if stored as JSON
                    $allowedCommunities = is_array($customer->community_id)
                        ? $customer->community_id
                        : json_decode($customer->community_id, true);

                    if (!is_array($allowedCommunities)) {
                        $allowedCommunities = [$customer->community_id];
                    }

                    // Now check if delivery_branch is in allowed communities
                    if (!in_array($deliveryBranch, $allowedCommunities)) {
                        $validator->errors()->add(
                            'delivery_branch',
                            'The selected delivery branch is invalid for this customer.'
                        );
                    }
                }
            }
        });
    }
}
