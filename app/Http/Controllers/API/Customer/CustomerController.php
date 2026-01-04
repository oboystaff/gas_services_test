<?php

namespace App\Http\Controllers\API\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\Customer\CreateCustomerRequest;
use App\Http\Requests\API\Customer\UpdateCustomerRequest;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Community;
use App\Models\InvoiceNote;
use Illuminate\Http\Request;
use App\Jobs\Customer\SendCustomerSMS;
use App\Models\GasRequest;
use Illuminate\Support\Facades\DB;


class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $data = Customer::orderBy('created_at', 'DESC')
            ->when(!empty($request->user()->branch_id), function ($query) use ($request) {
                $query->where('branch_id', $request->user()->branch_id);
            })
            ->with(['branch'])
            ->get();

        return response()->json([
            'message' => 'Get all customers',
            'data' => $data
        ]);
    }

    public function show($id)
    {
        $customer = Customer::query()
            ->with(['branch'])
            ->where('customer_id', $id)
            ->first();

        if (empty($customer)) {
            return response()->json([
                'message' => 'Customer not found'
            ], 422);
        }

        return response()->json([
            'message' => 'Get particular customer',
            'data' => $customer
        ]);
    }

    public function store(CreateCustomerRequest $request)
    {
        $data = $request->validated();
        $data['created_by'] = $request->user()->id;

        $customer = Customer::create($data);

        if ($customer) {
            $userData = [
                'name' => $customer->name ?? 'N/A',
                'email' => $customer->customer_id . '@manbahgh.com',
                'phone' => $customer->contact ?? 'N/A',
                'password' => Hash::make('manbah123456'),
                'status' => 'Active',
                'branch_id' => $customer->branch_id,
                'customer_id' => $customer->customer_id,
                'created_by' => $request->user()->id ?? null
            ];

            User::create($userData);
        }

        dispatch(new SendCustomerSMS($customer));

        return response()->json([
            'message' => 'Customer created successfully',
            'data' => $customer
        ]);
    }

    public function update(UpdateCustomerRequest $request, $id)
    {
        $customer = Customer::query()
            ->with(['branch'])
            ->where('customer_id', $id)
            ->first();

        if (empty($customer)) {
            return response()->json([
                'message' => 'Customer not found'
            ], 422);
        }

        $customer->update($request->validated());

        return response()->json([
            'message' => 'Customer updated successfully'
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

        $creditNotes = InvoiceNote::where('customer_id', $id)
            ->where('note_type', 'credit')
            ->sum('amount');

        $debitNotes = InvoiceNote::where('customer_id', $id)
            ->where('note_type', 'debit')
            ->sum('amount');

        $customer = Customer::where('customer_id', $id)->first();

        if (empty($customer)) {
            return response()->json([
                'message' => 'Customer not found, try again'
            ], 422);
        }

        $communityNames = Community::whereIn('id', $customer->community_id)->pluck('name')->implode(', ');
        $customer['community'] = isset($communityNames) ? $communityNames : 'N/A';
        unset($customer['community_id']);

        $balance = $totalInvoice - $totalPayment - $creditNotes + $debitNotes;

        return response()->json([
            'message' => 'Customer balance',
            'data' => $customer ?? null,
            'balance' => isset($balance) ? number_format($balance, 2) : 0
        ]);
    }

    public function customerRequest($id)
    {
        $gasRequests = GasRequest::where('customer_id', $id)
            ->with(['customer', 'driverAssigned', 'deliveryBranch'])
            ->get()
            ->map(function ($request) {

                if ($request->customer && !empty($request->customer->community_id)) {
                    $communityNames = Community::whereIn(
                        'id',
                        $request->customer->community_id
                    )->pluck('name')->implode(', ');
                } else {
                    $communityNames = 'N/A';
                }

                $request->community = $communityNames;

                return $request;
            });

        return response()->json([
            'message' => 'Customer gas request(s)',
            'data' => $gasRequests
        ]);
    }
}
