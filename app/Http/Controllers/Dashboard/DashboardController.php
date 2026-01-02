<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\CashRetirement;
use App\Models\Customer;
use App\Models\GasRequest;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Sale;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;


class DashboardController extends Controller
{
    public function operational(Request $request)
    {
        if (!auth()->user()->can('dashboards.operational')) {
            abort(403, 'Unauthorized action.');
        }

        $pageTitle = "Dashboard";

        $dailySales = Invoice::query()
            ->when(!empty($request->user()->branch_id), function ($query) use ($request) {
                $query->where('branch_id', $request->user()->branch_id);
            })
            ->whereDate('created_at', Carbon::today())
            ->sum('amount');

        $weeklySales = Invoice::query()
            ->when(!empty($request->user()->branch_id), function ($query) use ($request) {
                $query->where('branch_id', $request->user()->branch_id);
            })
            ->whereBetween('created_at', [
                Carbon::now()->startOfWeek(Carbon::SUNDAY),
                Carbon::now()->endOfWeek(Carbon::SATURDAY)
            ])
            ->sum('amount');

        $monthlySales = Invoice::query()
            ->when(!empty($request->user()->branch_id), function ($query) use ($request) {
                $query->where('branch_id', $request->user()->branch_id);
            })
            ->whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->sum('amount');

        $dailyReceipts = Payment::query()
            ->when(!empty($request->user()->branch_id), function ($query) use ($request) {
                $query->where('branch_id', $request->user()->branch_id);
            })
            ->whereDate('created_at', Carbon::today())
            ->sum('amount_paid');

        $weeklyReceipts = Payment::query()
            ->when(!empty($request->user()->branch_id), function ($query) use ($request) {
                $query->where('branch_id', $request->user()->branch_id);
            })
            ->whereBetween('created_at', [
                Carbon::now()->startOfWeek(Carbon::SUNDAY),
                Carbon::now()->endOfWeek(Carbon::SATURDAY)
            ])
            ->sum('amount_paid');

        $monthlyReceipts = Payment::query()
            ->when(!empty($request->user()->branch_id), function ($query) use ($request) {
                $query->where('branch_id', $request->user()->branch_id);
            })
            ->whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->sum('amount_paid');

        $dailyPendingRequest = GasRequest::where('status', 'Pending')
            ->when(!empty($request->user()->branch_id), function ($query) use ($request) {
                $query->where('branch_id', $request->user()->branch_id);
            })
            ->whereDate('created_at', Carbon::today())
            ->count();

        $weeklyPendingRequest = GasRequest::where('status', 'Pending')
            ->when(!empty($request->user()->branch_id), function ($query) use ($request) {
                $query->where('branch_id', $request->user()->branch_id);
            })
            ->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
            ->count();

        $monthlyPendingRequest = GasRequest::where('status', 'Pending')
            ->when(!empty($request->user()->branch_id), function ($query) use ($request) {
                $query->where('branch_id', $request->user()->branch_id);
            })
            ->whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->count();

        $totalPendingRequest = GasRequest::where('status', 'Pending')
            ->when(!empty($request->user()->branch_id), function ($query) use ($request) {
                $query->where('branch_id', $request->user()->branch_id);
            })
            ->count();

        $totalCustomers = Customer::query()
            ->when(!empty($request->user()->branch_id), function ($query) use ($request) {
                $query->where('branch_id', $request->user()->branch_id);
            })
            ->count();

        $totalMonthlyRetirement = CashRetirement::query()
            ->when(!empty($request->user()->branch_id), function ($query) use ($request) {
                $query->where('branch_id', $request->user()->branch_id);
            })
            ->whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->sum('amount_retired');

        $completedDeliveries = GasRequest::where('status', 'Gas Delivered')
            ->when(!empty($request->user()->branch_id), function ($query) use ($request) {
                $query->where('branch_id', $request->user()->branch_id);
            })
            ->count();


        $year = now()->year;
        $invoiceData = DB::table('invoices')
            ->selectRaw('MONTH(created_at) as month, SUM(amount) as total')
            ->whereYear('created_at', $year)
            ->groupByRaw('MONTH(created_at)')
            ->pluck('total', 'month');

        $paymentData = DB::table('payments')
            ->selectRaw('MONTH(created_at) as month, SUM(amount_paid + IFNULL(withholding_tax_amount,0)) as total')
            ->whereYear('created_at', $year)
            ->groupByRaw('MONTH(created_at)')
            ->pluck('total', 'month');

        $months = collect(range(1, 12))->map(function ($month) use ($invoiceData, $paymentData) {
            return (object)[
                'invoice' => $invoiceData->get($month, 0),
                'payment' => $paymentData->get($month, 0),
            ];
        });

        $debtors = DB::table('customers')
            ->joinSub(
                DB::table('invoices')
                    ->select(
                        'customer_id',
                        DB::raw('SUM(amount) as total_invoiced')
                    )
                    ->groupBy('customer_id'),
                'inv',
                'customers.customer_id',
                '=',
                'inv.customer_id'
            )
            ->leftJoinSub(
                DB::table('payments')
                    ->select(
                        'customer_id',
                        DB::raw('SUM(COALESCE(amount_paid,0) + COALESCE(withholding_tax_amount,0)) as total_paid')
                    )
                    ->groupBy('customer_id'),
                'pay',
                'customers.customer_id',
                '=',
                'pay.customer_id'
            )
            ->select(
                'customers.customer_id',
                'customers.name',
                DB::raw('(inv.total_invoiced - COALESCE(pay.total_paid,0)) as balance')
            )
            ->whereRaw('(inv.total_invoiced - COALESCE(pay.total_paid,0)) > 0')
            ->orderByDesc('balance')
            ->limit(10)
            ->get();

        $totalReceivables = DB::table('customers')
            ->joinSub(
                DB::table('invoices')
                    ->select(
                        'customer_id',
                        DB::raw('SUM(amount) as total_invoiced')
                    )
                    ->groupBy('customer_id'),
                'inv',
                'customers.customer_id',
                '=',
                'inv.customer_id'
            )
            ->leftJoinSub(
                DB::table('payments')
                    ->select(
                        'customer_id',
                        DB::raw('SUM(COALESCE(amount_paid,0) + COALESCE(withholding_tax_amount,0)) as total_paid')
                    )
                    ->groupBy('customer_id'),
                'pay',
                'customers.customer_id',
                '=',
                'pay.customer_id'
            )
            ->selectRaw('SUM(inv.total_invoiced - COALESCE(pay.total_paid,0)) as receivables')
            ->value('receivables');

        $totalDueDates = Invoice::orderBy('created_at', 'DESC')
            ->when(!empty($request->user()->branch_id), function ($query) use ($request) {
                $query->where('branch_id', $request->user()->branch_id);
            })
            ->whereNotNull('due_date')
            ->whereDate('due_date', '<=', Carbon::today())
            ->count();

        $total = [
            'dailySales' => isset($dailySales) ? number_format($dailySales, 2) : 0,
            'weeklySales' => isset($weeklySales) ? number_format($weeklySales, 2) : 0,
            'monthlySales' => isset($monthlySales) ? number_format($monthlySales, 2) : 0,
            'dailyReceipts' => isset($dailyReceipts) ? number_format($dailyReceipts, 2) : 0,
            'weeklyReceipts' => isset($weeklyReceipts) ? number_format($weeklyReceipts, 2) : 0,
            'monthlyReceipts' => isset($monthlyReceipts) ? number_format($monthlyReceipts, 2) : 0,
            'dailyPendingRequest' => isset($dailyPendingRequest) ? $dailyPendingRequest : 0,
            'weeklyPendingRequest' => isset($weeklyPendingRequest) ? $weeklyPendingRequest : 0,
            'monthlyPendingRequest' => isset($monthlyPendingRequest) ? $monthlyPendingRequest : 0,
            'totalCustomers' => isset($totalCustomers) ? $totalCustomers : 0,
            'totalMonthlyRetirement' => isset($totalMonthlyRetirement) ? number_format($totalMonthlyRetirement, 2) : 0,
            'totalCashAtHand' => isset($totalCashAtHand) ? number_format($totalCashAtHand, 2) : 0,
            'totalPendingRequest' => isset($totalPendingRequest) ? $totalPendingRequest : 0,
            'receivables' => isset($totalReceivables) ? number_format($totalReceivables, 2) : 0,
            'completedDeliveries' => isset($completedDeliveries) ? $completedDeliveries : 0,
            'totalDueDates' => isset($totalDueDates) ? $totalDueDates : 0
        ];

        return view('dashboard.operational', compact('total', 'months', 'debtors', 'pageTitle'));
    }

    public function saleSummary(Request $request)
    {
        $pageTitle = "Sale Summary";

        $summary = DB::table('sales as s')
            ->when(!empty($request->user()->branch_id), function ($query) use ($request) {
                $query->where('s.branch_id', $request->user()->branch_id);
            })
            ->join('users as u', 's.created_by', '=', 'u.id')
            ->select('u.id', 'u.name', DB::raw('SUM(s.amount) as total_sold'))
            ->groupBy('u.id', 'u.name')
            ->get();

        return view('dashboard.sale-summary', compact('summary', 'pageTitle'));
    }

    public function debtors()
    {
        $debtors = DB::table('customers')
            ->joinSub(
                DB::table('invoices')
                    ->select(
                        'customer_id',
                        DB::raw('SUM(amount) as total_invoiced')
                    )
                    ->groupBy('customer_id'),
                'inv',
                'customers.customer_id',
                '=',
                'inv.customer_id'
            )
            ->leftJoinSub(
                DB::table('payments')
                    ->select(
                        'customer_id',
                        DB::raw('SUM(COALESCE(amount_paid,0) + COALESCE(withholding_tax_amount,0)) as total_paid')
                    )
                    ->groupBy('customer_id'),
                'pay',
                'customers.customer_id',
                '=',
                'pay.customer_id'
            )
            ->select(
                'customers.customer_id',
                'customers.name',
                DB::raw('(inv.total_invoiced - COALESCE(pay.total_paid,0)) as balance')
            )
            ->whereRaw('(inv.total_invoiced - COALESCE(pay.total_paid,0)) > 0')
            ->orderByDesc('balance')
            ->get();

        $pageTitle = "Receivables Page";

        $total = [
            'receivables' => isset($debtors) ? number_format($debtors->sum('balance'), 2) : '0.00'
        ];

        return view('dashboard.debtors', compact('debtors', 'total', 'pageTitle'));
    }
}
