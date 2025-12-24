<?php

namespace App\Http\Controllers\API\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\Customer\CreateCustomerRequest;
use App\Http\Requests\API\Customer\UpdateCustomerRequest;
use App\Models\Customer;
use Illuminate\Http\Request;
use App\Jobs\Customer\SendCustomerSMS;

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
}
