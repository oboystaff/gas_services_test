<?php

namespace App\Http\Controllers\Sale;

use App\Http\Controllers\Controller;
use App\Http\Requests\Sale\CreateSaleRequest;
use App\Jobs\Sale\SendSaleSMS;
use App\Models\Sale;
use App\Models\Community;
use App\Models\Branch;
use App\Models\Customer;
use App\Models\Rate;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;


class SaleController extends Controller
{
    public function index(Request $request)
    {
        if (!auth()->user()->can('sales.view')) {
            abort(403, 'Unauthorized action.');
        }

        $pageTitle = "Gas Sale Page";

        $sales = Sale::orderBy('created_at', 'DESC')
            ->when(!empty($request->user()->branch_id), function ($query) use ($request) {
                $query->where('branch_id', $request->user()->branch_id);
            })
            ->when($request->display == "daily", function ($query) {
                $query->whereDate('created_at', Carbon::today());
            })
            ->when($request->display == "weekly", function ($query) {
                $query->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
            })
            ->when($request->display == "monthly", function ($query) {
                $query->whereMonth('created_at', Carbon::now()->month)
                    ->whereYear('created_at', Carbon::now()->year);
            })
            ->get();

        $kg = $sales->sum('kg');
        $amount = $sales->sum('amount');
        $serviceCharge = $sales->sum('service_charge');
        $transactionID = $request->query('transaction_id') ?? '';

        $summary = DB::table('sales as s')
            ->when(!empty($request->user()->branch_id), function ($query) use ($request) {
                $query->where('s.branch_id', $request->user()->branch_id);
            })
            ->when($request->display == "daily", function ($query) {
                $query->whereDate('s.created_at', Carbon::today());
            })
            ->when($request->display == "weekly", function ($query) {
                $query->whereBetween('s.created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
            })
            ->when($request->display == "monthly", function ($query) {
                $query->whereMonth('s.created_at', Carbon::now()->month)
                    ->whereYear('s.created_at', Carbon::now()->year);
            })
            ->join('users as u', 's.created_by', '=', 'u.id')
            ->select('u.id', 'u.name', DB::raw('SUM(s.amount) as total_sold'))
            ->groupBy('u.id', 'u.name')
            ->get();

        $totalSum = $summary->sum('total_sold');

        $total = [
            'kg' => isset($kg) ? number_format($kg, 2) : 0,
            'amount' => isset($amount) ? number_format($amount, 2) : 0,
            'service_charge' => isset($serviceCharge) ? number_format($serviceCharge, 2) : 0,
            'total_sum' => isset($totalSum) ? number_format($totalSum, 2) : 0
        ];

        return view('sales.index', compact('sales', 'total', 'pageTitle', 'transactionID', 'summary'));
    }

    public function create(Request $request)
    {
        if (!auth()->user()->can('sales.create')) {
            abort(403, 'Unauthorized action.');
        }

        $pageTitle = "Gas Sale Page";

        $communities = Community::orderBy('created_at', 'DESC')->get();

        $branches = Branch::orderBy('created_at', 'DESC')
            ->when(!empty($request->user()->branch_id), function ($query) use ($request) {
                $query->where('id', $request->user()->branch_id);
            })
            ->get();

        $rate = Rate::latest()->first()->amount ?? 0;

        $customers = Customer::orderBy('created_at', 'DESC')
            ->when(!empty($request->user()->branch_id), function ($query) use ($request) {
                $query->where('branch_id', $request->user()->branch_id);
            })
            ->pluck('customer_id')
            ->toArray();

        return view('sales.create', compact('branches', 'communities', 'customers', 'rate', 'pageTitle'));
    }

    public function store(CreateSaleRequest $request)
    {
        $data = $request->validated();
        $data['created_by'] = $request->user()->id;
        $data['kg'] = $request->input('kg');
        $data['amount'] = $request->input('amount');

        if (empty($request->input('branch_id'))) {
            $data['branch_id'] = $request->user()->branch_id;
        }

        $sale = Sale::create($data);

        //dispatch(new SendSaleSMS($sale));

        return redirect()->route('sales.index', ['id' => $sale->id])->with('status', 'Gas sale created successfully.');
    }

    public function show(Sale $sale)
    {
        $pageTitle = "Gas Sale Page";

        return view('sales.show', compact('sale', 'pageTitle'));
    }

    public function edit() {}

    public function update() {}

    public function fetchCustomer(Request $request)
    {
        $customer = Customer::where('customer_id', $request->input('customer_id'))->first();

        return response()->json([
            'message' => $customer
        ]);
    }

    public function printReceipt(Sale $sale)
    {
        return view('sales.print', compact('sale'));
    }
}
