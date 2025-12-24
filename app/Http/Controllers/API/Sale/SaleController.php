<?php

namespace App\Http\Controllers\API\Sale;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\Sale\CreateSaleRequest;
use App\Http\Requests\API\Sale\ExistingCustomerRequest;
use App\Http\Requests\API\Sale\NonExistingCustomerRequest;
use App\Models\Sale;
use App\Models\Rate;
use App\Models\Customer;
use Illuminate\Http\Request;
use App\Jobs\Sale\SendSaleSMS;


class SaleController extends Controller
{
    public function index(Request $request)
    {
        $data = Sale::orderBy('created_at', 'DESC')
            ->when(!empty($request->user()->branch_id), function ($query) use ($request) {
                $query->where('branch_id', $request->user()->branch_id);
            })
            ->with(['branch', 'community', 'customer'])
            ->get();

        return response()->json([
            'message' => 'Get all gas sales',
            'data' => $data
        ]);
    }

    public function show($id)
    {
        $sale = Sale::query()
            ->with(['branch', 'community', 'customer'])
            ->where('id', $id)
            ->first();

        if (empty($sale)) {
            return response()->json([
                'message' => 'Customer gas sale not found'
            ], 422);
        }

        return response()->json([
            'message' => 'Get particular customer gas sale',
            'data' => $sale
        ]);
    }

    public function store(CreateSaleRequest $request)
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
                'customer_id' => $validated['customer_id'],
                'name' => $customer->name,
                'contact' => $customer->contact,
                'kg' => $kg,
                'amount' => $amount,
                'service_charge' => $validated['service_charge'] ?? null,
                'community_id' => $customer->community_id,
                'branch_id' => $customer->branch_id,
                'created_by' => $request->user()->id,
                'cid' => $validated['cid']
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
                'name' => $validated['name'] ?? null,
                'contact' => $validated['contact'] ?? null,
                'kg' => $kg,
                'amount' => $amount,
                'service_charge' => $validated['service_charge'] ?? null,
                'community_id' => $validated['community_id'] ?? null,
                'branch_id' => $validated['branch_id'] ?? null,
                'created_by' => $request->user()->id,
                'cid' => $validated['cid']
            ];
        } else {
            return response()->json(['error' => 'Invalid customer type'], 400);
        }

        if (empty($request->input('branch_id'))) {
            $data['branch_id'] = $request->user()->branch_id;
        }

        $sale = Sale::create($data);

        //dispatch(new SendSaleSMS($sale));

        return response()->json([
            'message' => 'Gas sale created successfully',
            'data' => $sale
        ]);
    }
}
