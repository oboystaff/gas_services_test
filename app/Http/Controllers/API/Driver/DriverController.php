<?php

namespace App\Http\Controllers\API\Driver;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\GasRequest\UpdateGasRequest;
use App\Models\Driver;
use App\Models\GasRequest;
use App\Models\Rate;
use App\Jobs\Delivery\SendDeliverySMS;


class DriverController extends Controller
{
    public function index()
    {
        $data = Driver::orderBy('created_at', 'DESC')->get();

        return response()->json([
            'message' => 'Get all drivers',
            'data' => $data
        ]);
    }

    public function show($id)
    {
        $driver = Driver::query()->where('id', $id)->first();

        if (empty($driver)) {
            return response()->json([
                'message' => 'Driver not found'
            ], 422);
        }

        $driverRequests = GasRequest::where('driver_assigned', $driver->id)
            ->with(['driverAssigned', 'deliveryBranch'])
            ->where('status', 'Driver Assigned')
            ->get();

        if (count($driverRequests) == 0) {
            return response()->json([
                'message' => 'Driver has no assigned request'
            ], 422);
        }

        return response()->json([
            'message' => 'Get driver gas requests',
            'data' => $driverRequests
        ]);
    }

    public function markDone(UpdateGasRequest $request, $id)
    {
        $rate = Rate::latest()->first()->amount ?? 0;
        $kg = 0;
        $amount = 0;

        $gasRequest = GasRequest::where('id', $id)
            ->where('status', 'Driver Assigned')
            ->first();

        if (empty($gasRequest)) {
            return response()->json([
                'message' => 'Gas request not found or request has marked as done'
            ], 422);
        }

        $validated = $request->validated();

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

        if ($request->hasFile('attachment')) {
            $image = $request->file('attachment');
            $image_name = GasRequest::generateAttachmentName();
            $image_name = $image_name . '.' . $image->getClientOriginalExtension();
            $attachmentDestinationPath = storage_path('app/public/images/attachment');
            $image->move($attachmentDestinationPath, $image_name);
        }

        $data = [
            'kg' => $kg,
            'amount' => $amount,
            'status' => 'Gas Delivered',
            'attachment' => $image_name ?? null,
            'rep_name' => $validated['rep_name'],
            'rep_contact' => $validated['rep_contact']
        ];

        $gasRequest->update($data);

        dispatch(new SendDeliverySMS($gasRequest));

        return response()->json([
            'message' => 'Gas request mark as done successfully'
        ]);
    }
}
