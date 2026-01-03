<?php

namespace App\Http\Controllers\API\USSD;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\Payment\MakePaymentRequest;
use App\Http\Requests\API\USSD\ConverterRequest;
use App\Http\Requests\API\USSD\CreateGasRequest;
use App\Http\Requests\API\USSD\ExistingCustomerRequest;
use App\Http\Requests\API\USSD\GHSRequest;
use App\Http\Requests\API\USSD\KGRequest;
use App\Http\Requests\API\USSD\NonExistingCustomerRequest;
use App\Models\User;
use App\Models\Customer;
use App\Models\Rate;
use App\Models\GasRequest;
use App\Jobs\GasRequest\SendGasRequestSMS;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Community;
use Illuminate\Support\Facades\DB;
use App\Jobs\Payment\SendPaymentSMS;



class USSDController extends Controller
{
    public function generateToken()
    {
        $user = User::find(1);

        if (empty($user)) {
            return response()->json([
                'message' => 'User with this account does not exist'
            ], 422);
        }

        $user->tokens()->where('name', 'Static Token')->delete();
        $token = $user->createToken('Static Token')->plainTextToken;

        return response()->json([
            'message' => 'Token generated successfully',
            'token' => $token
        ]);
    }

    public function store(CreateGasRequest $request)
    {
        $customerType = $request->input('customer_type');
        $rate = Rate::latest()->first()->amount ?? 0;
        $kg = 0;
        $amount = 0;
        $data = [];

        if (empty($rate)) {
            return response()->json([
                'message' => 'No rate set yet, kindly contact the admin'
            ], 422);
        }

        if ($customerType === 'existing_customer') {
            $validated = app(ExistingCustomerRequest::class)->validated();

            $customer = Customer::where('customer_id', $validated['customer_id'])->first();

            if (empty($customer)) {
                return response()->json([
                    'message' => 'Customer not found'
                ], 422);
            }

            // if ($request->input('quantity_type') === 'KG') {
            //     $kg = $validated['kg'];
            //     $amount = $validated['kg'] * $rate;
            // } else if ($request->input('quantity_type') === 'GHS') {
            //     $kg = $validated['amount'] / $rate;
            //     $amount = $validated['amount'];
            // } else {
            //     return response()->json([
            //         'message' => 'Invalid quantity type'
            //     ], 422);
            // }

            $data = [
                'customer_id' => $validated['customer_id'],
                'name' => $customer->name ?? '',
                'contact' => $customer->contact ?? '',
                'kg' => $kg ?? 0,
                'amount' => $amount ?? 0,
                'community_id' => $customer->community_id ?? '',
                'branch_id' => $customer->branch_id ?? '',
                'delivery_branch' => $validated['delivery_branch'],
                'request_contact' => $validated['request_contact'],
                'created_by' => $request->user()->id
            ];
        } else {
            return response()->json(['error' => 'Invalid customer type'], 400);
        }

        $totalInvoice = Invoice::where('customer_id', $data['customer_id'])->sum('amount');
        $totalPayment = Payment::where('customer_id', $data['customer_id'])
            ->sum(DB::raw("
                CASE
                    WHEN payment_mode = 'momo' AND transaction_status = 'Success' THEN amount_paid
                    WHEN payment_mode != 'momo' THEN amount_paid
                    ELSE 0
                END
            "));

        $balance = $totalInvoice - $totalPayment;

        if ($customer->threshold == 'Y' && bccomp($balance, $customer->threshold_amount, 2) >= 0) {
            return response()->json([
                'message' => 'You have exceeded your threshold, contact the admin.'
            ], 422);
        }

        $assignedGasRequest = GasRequest::where('customer_id', $data['customer_id'])
            ->where('status', 'Driver Assigned')
            ->where('delivery_branch', $data['delivery_branch'])
            ->latest('created_at')
            ->first();

        if (!empty($assignedGasRequest)) {
            $delivery_branch = $assignedGasRequest->deliveryBranch->name ?? 'N/A';

            return response()->json([
                'message' => 'There is already pending gas request for this branch(' . $delivery_branch . '), try again later.'
            ]);
        }

        $gasRequest = GasRequest::create($data);

        dispatch(new SendGasRequestSMS($gasRequest));

        return response()->json([
            'message' => 'Gas request created successfully',
            'data' => $gasRequest
        ]);
    }

    public function convert(ConverterRequest $request)
    {
        $quantityType = $request->input('quantity_type');
        $rate = Rate::latest('created_at')->first()->amount ?? 0;
        $kg = 0;
        $amount = 0;

        if (empty($rate)) {
            return response()->json([
                'message' => 'No rate set yet, kindly contact the admin'
            ], 422);
        }

        if ($quantityType === "KG") {
            $validated = app(KGRequest::class)->validated();

            $kg = $validated['KG'];
            $amount = $kg * $rate;
        } else if ($quantityType === "GHS") {
            $validated = app(GHSRequest::class)->validated();

            $kg = $validated['GHS'] / $rate;
            $amount = $validated['GHS'];
        } else {
            return response()->json(['error' => 'Invalid quantity type'], 400);
        }

        $data = [
            'kg' => isset($kg) ? number_format($kg, 2) : 0,
            'amount' => isset($amount) ? number_format($amount, 2) : 0,
            'rate' => $rate
        ];

        return response()->json([
            'message' => 'Quantity converted successfully',
            'data' => $data
        ]);
    }

    public function balance($id)
    {
        $totalInvoice = Invoice::where('customer_id', $id)->sum('amount');
        $totalPayment = Payment::where('customer_id', $id)
            ->sum(DB::raw("
                CASE
                    WHEN payment_mode = 'momo' AND transaction_status = 'Success'
                        THEN amount_paid + IFNULL(withholding_tax_amount, 0)
                    WHEN payment_mode != 'momo'
                        THEN amount_paid + IFNULL(withholding_tax_amount, 0)
                    ELSE 0
                END
            "));

        $balance = $totalInvoice - $totalPayment;

        return response()->json([
            'message' => 'Customer balance',
            'data' => isset($balance) ? number_format($balance, 2) : 0
        ]);
    }

    public function checkCustomer($id)
    {
        $customer = Customer::where('customer_id', $id)->first();

        if (empty($customer)) {
            return response()->json([
                'message' => 'Customer not found, try again'
            ], 422);
        }

        $communityNames = Community::whereIn('id', $customer->community_id)->pluck('name')->implode(', ');
        $customer['community'] = isset($communityNames) ? $communityNames : 'N/A';

        return response()->json([
            'message' => 'Get particular customer',
            'data' => $customer
        ]);
    }

    public function makePayment(MakePaymentRequest $request)
    {
        $customer = Customer::where('customer_id', $request->input('customer_id'))->first();

        if (empty($customer)) {
            return response()->json([
                'message' => 'Customer not found, try again'
            ], 422);
        }

        $data = $request->validated();
        $data['amount_paid'] = $data['amount'];
        $data['outstanding'] = 0;
        $data['branch_id'] = $customer->branch_id ?? '';
        $data['created_by'] = $request->user()->id ?? '';

        $payment = Payment::create($data);

        dispatch(new SendPaymentSMS($payment));

        return response()->json([
            'message' => 'Payment made successfully'
        ]);
    }

    public function fetchDeliveryBranch($id)
    {
        $customer = Customer::where('customer_id', $id)->first();

        if (empty($customer)) {
            return response()->json([
                'message' => 'Customer not found, try again'
            ], 422);
        }

        $communityIds = $customer->community_id;

        if (is_string($communityIds)) {
            $decoded = json_decode($communityIds, true);
            $communityIds = is_array($decoded) ? $decoded : [$communityIds];
        } elseif (is_int($communityIds)) {
            $communityIds = [$communityIds];
        }

        $communities = Community::whereIn('id', $communityIds)
            ->select('id', 'name')
            ->get();

        return response()->json([
            'message' => 'Get customer communities',
            'data' => $communities
        ]);
    }
}
