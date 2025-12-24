<?php

namespace App\Http\Controllers\GasRequest;

use App\Http\Controllers\Controller;
use App\Http\Requests\GasRequest\AssignAgentRequest;
use App\Http\Requests\GasRequest\CreateNonCustomerGasRequest;
use App\Http\Requests\GasRequest\MarkAsDoneRequest;
use App\Jobs\Delivery\SendDeliverySMS;
use App\Jobs\Driver\SendAssignmentSMS;
use App\Jobs\GasRequest\SendGasRequestSMS;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Branch;
use App\Models\Community;
use App\Models\GasRequest;
use App\Models\Rate;
use App\Models\Customer;
use App\Models\Driver;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;


class GasRequestController extends Controller
{
    public function index(Request $request)
    {
        if (!auth()->user()->can('gas-requests.view')) {
            abort(403, 'Unauthorized action.');
        }

        $pageTitle = "Gas Request Page";

        $gasRequests = GasRequest::orderBy('created_at', 'DESC')
            ->when(!empty($request->user()->branch_id), function ($query) use ($request) {
                $query->where('branch_id', $request->user()->branch_id);
            })
            ->when($request->display == "daily", function ($query) {
                $query->where('status', 'Pending')
                    ->whereDate('created_at', Carbon::today());
            })
            ->when($request->display == "weekly", function ($query) {
                $query->where('status', 'Pending')
                    ->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
            })
            ->when($request->display == "monthly", function ($query) {
                $query->where('status', 'Pending')
                    ->whereMonth('created_at', Carbon::now()->month)
                    ->whereYear('created_at', Carbon::now()->year);
            })
            ->when($request->display == "total_pending", function ($query) {
                $query->where('status', 'Pending');
            })
            ->when($request->display == "completed", function ($query) {
                $query->where('status', 'Gas Delivered');
            })
            // ->when(!$request->display == "completed", function ($query) {
            //     $query->where('status', 'Gas Delivered');
            // })
            ->where('status', '!=', 'Invoice Raised')
            ->get();

        $kg = $gasRequests->sum('kg');
        $amount = $gasRequests->sum('amount');

        $total = [
            'kg' => isset($kg) ? number_format($kg, 2) : 0,
            'amount' => isset($amount) ? number_format($amount, 2) : 0
        ];

        return view('gas-requests.index', compact('gasRequests', 'total', 'pageTitle'));
    }

    public function create(Request $request)
    {
        if (!auth()->user()->can('gas-requests.create')) {
            abort(403, 'Unauthorized action.');
        }

        $pageTitle = "Gas Request Page";

        $communities = Community::orderBy('created_at', 'DESC')->get();

        $branches = Branch::orderBy('created_at', 'DESC')
            ->when(!empty($request->user()->branch_id), function ($query) use ($request) {
                $query->where('id', $request->user()->branch_id);
            })
            ->get();

        $customers = Customer::orderBy('created_at', 'DESC')
            ->when(!empty($request->user()->branch_id), function ($query) use ($request) {
                $query->where('branch_id', $request->user()->branch_id);
            })
            ->select('customer_id', 'name')
            ->get()
            ->map(function ($customer) {
                return [
                    'id' => $customer->customer_id,
                    'name' => $customer->name . " - " . $customer->customer_id,
                ];
            });

        $rate = Rate::latest('created_at')->first()->amount ?? 0;

        return view('gas-requests.create', compact('communities', 'branches', 'rate', 'customers', 'pageTitle'));
    }

    public function store(CreateNonCustomerGasRequest $request)
    {
        $data = $request->validated();
        $data['kg'] = $request->input('kg');
        $data['amount'] = $request->input('amount');
        $data['created_by'] = $request->user()->id;
        $customer = Customer::where('customer_id', $data['customer_id'])->first();

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
            return redirect()->route('gas-requests.index')->with('error', 'You have exceeded your threshold, contact the admin.');
        }

        $gasRequest = GasRequest::create($data);

        dispatch(new SendGasRequestSMS($gasRequest));

        return redirect()->route('gas-requests.index')->with('status', 'Gas request created successfully.');
    }

    public function assignAgent(GasRequest $gasRequest)
    {
        if (!auth()->user()->can('gas-requests.update')) {
            abort(403, 'Unauthorized action.');
        }

        $pageTitle = "Gas Request Page";

        $drivers = Driver::orderBy('created_at', 'DESC')->get();

        return view('gas-requests.assign-agent', compact('gasRequest', 'drivers', 'pageTitle'));
    }

    public function assignAgentStore(AssignAgentRequest $request, GasRequest $gasRequest)
    {
        $data = $request->validated();
        $data['status'] = 'Driver Assigned';
        $data['assigned_by'] = $request->user()->id;
        $driver = Driver::where('id', $data['driver_assigned'])->first();

        $gasRequest->update($data);
        dispatch(new SendAssignmentSMS($driver, $gasRequest));

        return redirect()->route('gas-requests.index')->with('status', 'Delivery driver assigned successfully.');
    }

    public function markDone(GasRequest $gasRequest)
    {
        $pageTitle = "Gas Request Page";

        $rate = Rate::latest()->first()->amount ?? 0;

        return view('gas-requests.edit', compact('gasRequest', 'rate', 'pageTitle'));
    }

    public function markDoneStore(MarkAsDoneRequest $request, GasRequest $gasRequest)
    {
        if ($request->hasFile('attachment')) {
            $image = $request->file('attachment');
            $image_name = GasRequest::generateAttachmentName();
            $image_name = $image_name . '.' . $image->getClientOriginalExtension();
            $attachmentDestinationPath = storage_path('app/public/images/attachment');
            $image->move($attachmentDestinationPath, $image_name);
        }

        $data = $request->validated();
        $data['kg'] = $request->input('kg');
        $data['amount'] = $request->input('amount');
        $data['status'] = 'Gas Delivered';
        $data['attachment'] = $image_name ?? null;

        $gasRequest->update($data);

        dispatch(new SendDeliverySMS($gasRequest));

        return redirect()->route('gas-requests.index')->with('status', 'Customer request mark as done successfully.');
    }

    public function raiseInvoice(GasRequest $gasRequest)
    {
        $pageTitle = "Invoice Page";

        $rate = Rate::latest()->first()->amount ?? 0;

        return view('invoices.create', compact('gasRequest', 'rate', 'pageTitle'));
    }

    public function fetchCustomer(Request $request)
    {
        $customer = Customer::where('customer_id', $request->input('customer_id'))->first();

        return response()->json([
            'message' => $customer
        ]);
    }

    public function editRequest(GasRequest $gasRequest)
    {
        $pageTitle = "Gas Request Page";

        return view('gas-requests.edit-request', compact('gasRequest', 'pageTitle'));
    }

    public function updateRequest(Request $request, GasRequest $gasRequest)
    {
        $data = [
            'delivery_branch' => $request->delivery_branch,
        ];

        $gasRequest->update($data);

        return redirect()->route('gas-requests.index')->with('status', 'Gas request delivery branch updated successfully.');
    }

    public function getApproveRequest(GasRequest $gasRequest)
    {
        $pageTitle = "Gas Request Page";

        return view('gas-requests.show', compact('gasRequest', 'pageTitle'));
    }

    public function approveRequest(Request $request, GasRequest $gasRequest)
    {
        $approveData = [
            'status' => 'Request Approved',
            'approved_by' => $request->user()->id ?? ''
        ];

        $gasRequest->update($approveData);

        return redirect()->route('gas-requests.index')->with('status', 'Gas request approved successfully.');
    }

    public function getReverseRequest(GasRequest $gasRequest)
    {
        $pageTitle = "Gas Request Page";

        return view('gas-requests.reverse', compact('gasRequest', 'pageTitle'));
    }

    public function reverseRequest(GasRequest $gasRequest)
    {
        $reverseData = [
            'status' => 'Pending',
            'driver_assigned' => null
        ];

        $gasRequest->update($reverseData);

        return redirect()->route('gas-requests.index')->with('status', 'Gas request reversed successfully.');
    }
}
