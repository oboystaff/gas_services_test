<?php

namespace App\Http\Controllers\CashRetirement;

use App\Http\Controllers\Controller;
use App\Http\Requests\CashRetirement\CreateCashRetirementRequest;
use App\Models\CashRetirement;
use App\Models\Sale;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;


class CashRetirementController extends Controller
{
    public function index(Request $request)
    {
        if (!auth()->user()->can('cash-retirements.view')) {
            abort(403, 'Unauthorized action.');
        }

        $pageTitle = "Cash Retirement Page";

        $currentDate = Carbon::now()->toDateString();
        $sales = DB::table('sales')
            ->when(!empty($request->user()->branch_id), function ($query) use ($request) {
                $query->where('branch_id', $request->user()->branch_id);
            })
            ->when($request->display == "monthly", function ($query) {
                $query->whereMonth('created_at', Carbon::now()->month)
                    ->whereYear('created_at', Carbon::now()->year);
            })
            ->select(
                'branch_id',
                DB::raw('DATE(created_at) as sales_date'),
                DB::raw('SUM(amount) as total_sales')
            )
            ->groupBy('branch_id', DB::raw('DATE(created_at)'));

        $cashRetirements = DB::table('cash_retirements')
            ->when(!empty($request->user()->branch_id), function ($query) use ($request) {
                $query->where('branch_id', $request->user()->branch_id);
            })
            ->when($request->display == "monthly", function ($query) {
                $query->whereMonth('created_at', Carbon::now()->month)
                    ->whereYear('created_at', Carbon::now()->year);
            })
            ->select(
                'branch_id',
                DB::raw('DATE(sales_date) as cash_retirements_date'),
                DB::raw('SUM(amount_retired) as total_retired'),
                DB::raw('MAX(date_retired) as date_retired')
            )
            ->groupBy('branch_id', DB::raw('DATE(sales_date)'));

        $results = DB::query()
            ->fromSub($sales, 'sales')
            ->leftJoinSub($cashRetirements, 'cash_retirements', function ($join) {
                $join->on('sales.branch_id', '=', 'cash_retirements.branch_id')
                    ->on('sales.sales_date', '=', 'cash_retirements.cash_retirements_date');
            })
            ->leftJoin('branches', 'sales.branch_id', '=', 'branches.id')
            ->leftJoin('users', 'sales.branch_id', '=', 'users.branch_id')
            ->leftJoin('roles', function ($join) {
                $join->on('users.user_role', '=', 'roles.id')
                    ->where('roles.name', 'LIKE', '%Branch Manager%');
            })
            ->select(
                'sales.branch_id',
                'branches.name as branch_name',
                'sales.sales_date',
                'sales.total_sales',
                'cash_retirements.total_retired',
                'cash_retirements.date_retired',
                DB::raw('MAX(CASE WHEN roles.name LIKE "%Branch Manager%" THEN users.name END) as branch_manager')
            )
            ->groupBy(
                'sales.branch_id',
                'branches.name',
                'sales.sales_date',
                'cash_retirements.total_retired',
                'cash_retirements.date_retired'
            )
            ->orderBy('sales.branch_id')
            ->orderBy('sales.sales_date')
            ->get();

        $totalSales = $results->sum('total_sales');
        $totalRetired = $results->sum('total_retired');

        $total = [
            'sales_total' => isset($totalSales) ? number_format($totalSales, 2) : 0,
            'retired_total' => isset($totalRetired) ? number_format($totalRetired, 2) : 0
        ];

        return view('cash-retirements.index', compact('results', 'total', 'pageTitle'));
    }

    public function create(Request $request)
    {
        if (!auth()->user()->can('cash-retirements.create')) {
            abort(403, 'Unauthorized action.');
        }

        $pageTitle = "Cash Retirement Page";

        $salesDate = $request->query('sales_date') ?? now()->toDateString();

        return view('cash-retirements.create', compact('salesDate', 'pageTitle'));
    }

    public function store(CreateCashRetirementRequest $request)
    {
        if ($request->hasFile('payment_slip')) {
            $file = $request->file('payment_slip');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('payment_slips', $fileName, 'public');
        }

        $data = $request->validated();
        $data['sales_date'] = $request->input('sales_date');
        $data['date_retired'] = now()->toDateString();
        $data['branch_id'] = $request->user()->branch_id;
        $data['retired_by'] = $request->user()->id;
        $data['payment_slip'] = $fileName ?? null;

        CashRetirement::create($data);

        return redirect()->route('cash-retirements.index')->with('status', 'Cash retired successfully.');
    }

    public function show(CashRetirement $cashRetirement)
    {
        $pageTitle = "Cash Retirement Page";

        return view('cash-retirements.show', compact('cashRetirement', 'pageTitle'));
    }

    public function retiredCash(Request $request)
    {
        if (!auth()->user()->can('cash-retirements.view')) {
            abort(403, 'Unauthorized action.');
        }

        $pageTitle = "Cash Retirement Page";

        $sales_date = $request->query('sales_date') ?? Carbon::now()->toDateString();
        $cashRetirements = CashRetirement::orderBy('created_at', 'DESC')
            ->when(!empty($request->user()->branch_id), function ($query) use ($request) {
                $query->where('branch_id', $request->user()->branch_id);
            })
            ->whereDate('sales_date', $sales_date)
            ->get();

        $amount_retired = $cashRetirements->sum('amount_retired');

        $total = [
            'amount_retired' => isset($amount_retired) ? number_format($amount_retired, 2) : 0
        ];

        return view('cash-retirements.retired-cash', compact('cashRetirements', 'total', 'pageTitle'));
    }
}
