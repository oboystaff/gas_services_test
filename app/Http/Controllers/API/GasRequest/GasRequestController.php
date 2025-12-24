<?php

namespace App\Http\Controllers\API\GasRequest;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\GasRequest\CreateGasRequest;
use App\Http\Requests\API\GasRequest\ExistingCustomerRequest;
use App\Http\Requests\API\GasRequest\NonExistingCustomerRequest;
use App\Models\Customer;
use App\Models\GasRequest;
use App\Models\Rate;
use Illuminate\Http\Request;
use App\Jobs\GasRequest\SendGasRequestSMS;


class GasRequestController extends Controller
{
    public function index(Request $request)
    {
        $data = GasRequest::orderBy('created_at', 'DESC')
            ->when(!empty($request->user()->branch_id), function ($query) use ($request) {
                $query->where('branch_id', $request->user()->branch_id);
            })
            ->with(['branch', 'customer', 'driverAssigned'])
            ->get();

        return response()->json([
            'message' => 'Get all gas requests',
            'data' => $data
        ]);
    }

    public function show($id)
    {
        $gasRequest = GasRequest::query()
            ->with(['branch', 'customer', 'driverAssigned'])
            ->where('id', $id)
            ->first();

        if (empty($gasRequest)) {
            return response()->json([
                'message' => 'Customer gas request not found'
            ], 422);
        }

        return response()->json([
            'message' => 'Get particular customer gas request',
            'data' => $gasRequest
        ]);
    }

    public function store(CreateGasRequest $request)
    {
        $customerType = $request->input('customer_type');
        $rate = Rate::latest('created_at')->first()->amount ?? 0;
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
                'kg' => $kg ?? '',
                'amount' => $amount ?? '',
                'community_id' => $customer->community_id ?? '',
                'branch_id' => $customer->branch_id ?? '',
                'created_by' => $request->user()->id
            ];
        } elseif ($customerType === 'non_existing_customer') {
            $validated = app(NonExistingCustomerRequest::class)->validated();

            if ($request->input('quantity_type') === 'KG') {
                $kg = $validated['kg'];
                $amount = $validated['kg'] * $rate;
            } else if ($request->input('quantity_type') === 'GHS') {
                $kg = $validated['amount'] / $rate;
                $amount = $validated['amount'];
            } else {
                return response()->json([
                    'message' => 'Invalid quantity type'
                ], 422);
            }

            $data = [
                'name' => $validated['name'],
                'contact' => $validated['contact'],
                'kg' => $kg,
                'amount' => $amount,
                'community_id' => $validated['community_id'],
                'branch_id' => $validated['branch_id'],
                'created_by' => $request->user()->id
            ];
        } else {
            return response()->json(['error' => 'Invalid customer type'], 400);
        }

        $gasRequest = GasRequest::create($data);

        dispatch(new SendGasRequestSMS($gasRequest));

        return response()->json([
            'message' => 'Gas request created successfully',
            'data' => $gasRequest
        ]);
    }
}
