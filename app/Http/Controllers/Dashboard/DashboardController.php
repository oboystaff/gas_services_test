<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\CashRetirement;
use App\Models\Customer;
use App\Models\GasRequest;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\RecoveryOfficer;
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
            ->get()
            ->sum(function ($payment) {
                if ($payment->payment_mode === 'momo') {
                    return $payment->transaction_status === 'Success'
                        ? $payment->amount_paid + ($payment->withholding_tax_amount ?? 0)
                        : 0;
                }

                return $payment->amount_paid + ($payment->withholding_tax_amount ?? 0);
            });

        $weeklyReceipts = Payment::query()
            ->when(!empty($request->user()->branch_id), function ($query) use ($request) {
                $query->where('branch_id', $request->user()->branch_id);
            })
            ->whereBetween('created_at', [
                Carbon::now()->startOfWeek(Carbon::SUNDAY),
                Carbon::now()->endOfWeek(Carbon::SATURDAY)
            ])
            ->get()
            ->sum(function ($payment) {
                if ($payment->payment_mode === 'momo') {
                    return $payment->transaction_status === 'Success'
                        ? $payment->amount_paid + ($payment->withholding_tax_amount ?? 0)
                        : 0;
                }

                return $payment->amount_paid + ($payment->withholding_tax_amount ?? 0);
            });

        $monthlyReceipts = Payment::query()
            ->when(!empty($request->user()->branch_id), function ($query) use ($request) {
                $query->where('branch_id', $request->user()->branch_id);
            })
            ->whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->get()
            ->sum(function ($payment) {
                if ($payment->payment_mode === 'momo') {
                    return $payment->transaction_status === 'Success'
                        ? $payment->amount_paid + ($payment->withholding_tax_amount ?? 0)
                        : 0;
                }

                return $payment->amount_paid + ($payment->withholding_tax_amount ?? 0);
            });

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
            ->selectRaw("
                MONTH(created_at) as month,
                SUM(
                    CASE
                        WHEN payment_mode = 'momo'
                            AND transaction_status = 'Success'
                            THEN amount_paid + IFNULL(withholding_tax_amount, 0)
                        WHEN payment_mode != 'momo'
                            THEN amount_paid + IFNULL(withholding_tax_amount, 0)
                        ELSE 0
                    END
                ) as total
            ")
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
            ->leftJoinSub(
                DB::table('invoice_notes')
                    ->select(
                        'customer_id',
                        DB::raw("
                            SUM(
                                CASE
                                    WHEN note_type = 'debit' THEN amount
                                    WHEN note_type = 'credit' THEN -amount
                                    ELSE 0
                                END
                            ) as net_notes_adjustment
                        ")
                    )
                    ->groupBy('customer_id'),
                'notes',
                'customers.customer_id',
                '=',
                'notes.customer_id'
            )
            ->select(
                'customers.customer_id',
                'customers.name',
                DB::raw('(inv.total_invoiced - COALESCE(pay.total_paid,0) + COALESCE(notes.net_notes_adjustment,0)) as balance')
            )
            ->whereRaw('(inv.total_invoiced - COALESCE(pay.total_paid,0) + COALESCE(notes.net_notes_adjustment,0)) > 0')
            ->orderByDesc('balance')
            ->limit(10)
            ->get();

        $totalReceivables = DB::table('customers')
            ->joinSub(
                DB::table('invoices')
                    ->select(
                        'customer_id',
                        DB::raw('ROUND(SUM(amount), 2) as total_invoiced')
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
                        DB::raw("
                    ROUND(SUM(
                        CASE
                            WHEN payment_mode = 'momo'
                                AND transaction_status = 'Success'
                                THEN amount_paid + IFNULL(withholding_tax_amount, 0)
                            WHEN payment_mode != 'momo'
                                THEN amount_paid + IFNULL(withholding_tax_amount, 0)
                            ELSE 0
                        END
                    ), 2) as total_paid
                ")
                    )
                    ->groupBy('customer_id'),
                'pay',
                'customers.customer_id',
                '=',
                'pay.customer_id'
            )
            ->leftJoinSub(
                DB::table('invoice_notes')
                    ->select(
                        'customer_id',
                        DB::raw("
                    ROUND(SUM(
                        CASE
                            WHEN note_type = 'debit' THEN amount
                            WHEN note_type = 'credit' THEN -amount
                            ELSE 0
                        END
                    ), 2) as net_notes_adjustment
                ")
                    )
                    ->groupBy('customer_id'),
                'notes',
                'customers.customer_id',
                '=',
                'notes.customer_id'
            )
            ->whereRaw('ROUND(inv.total_invoiced - COALESCE(pay.total_paid, 0) + COALESCE(notes.net_notes_adjustment, 0), 2) > 0')
            ->selectRaw('ROUND(SUM(inv.total_invoiced - COALESCE(pay.total_paid, 0) + COALESCE(notes.net_notes_adjustment, 0)), 2) as receivables')
            ->value('receivables');

        $totalDueDates = Invoice::orderBy('created_at', 'DESC')
            ->when(!empty($request->user()->branch_id), function ($query) use ($request) {
                $query->where('branch_id', $request->user()->branch_id);
            })
            ->whereHas('customer', function ($q) {
                $q->whereRaw(
                    "DATE_ADD(invoices.created_at, INTERVAL customers.due_date DAY) <= ?",
                    [now()]
                );
            })
            ->whereNotExists(function ($sub) {
                $sub->select(DB::raw(1))
                    ->from('payments')
                    ->whereColumn('payments.invoice_no', 'invoices.invoice_no')
                    ->groupBy('payments.invoice_no')
                    ->havingRaw("
                        SUM(
                            CASE
                                WHEN payments.payment_mode = 'momo'
                                    AND payments.transaction_status = 'Success'
                                    THEN payments.amount_paid + IFNULL(payments.withholding_tax_amount, 0)
                                WHEN payments.payment_mode != 'momo'
                                    THEN payments.amount_paid + IFNULL(payments.withholding_tax_amount, 0)
                                ELSE 0
                            END
                        ) >= invoices.amount
                    ");
            })
            ->count();

        $totalRecoveryOfficers = RecoveryOfficer::count();

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
            'totalDueDates' => isset($totalDueDates) ? $totalDueDates : 0,
            'totalRecoveryOfficers' => isset($totalRecoveryOfficers) ? $totalRecoveryOfficers : 0
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
        $pageTitle = "Receivables Page";

        $baseQuery = DB::table('customers')
            ->joinSub(
                DB::table('invoices')
                    ->select('customer_id', DB::raw('ROUND(SUM(amount), 2) as total_invoiced'))
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
                        DB::raw("ROUND(SUM(
                    CASE
                        WHEN payment_mode = 'momo' AND transaction_status = 'Success'
                            THEN amount_paid + IFNULL(withholding_tax_amount, 0)
                        WHEN payment_mode != 'momo'
                            THEN amount_paid + IFNULL(withholding_tax_amount, 0)
                        ELSE 0
                    END
                ), 2) as total_paid")
                    )
                    ->groupBy('customer_id'),
                'pay',
                'customers.customer_id',
                '=',
                'pay.customer_id'
            )
            ->leftJoinSub(
                DB::table('invoice_notes')
                    ->select(
                        'customer_id',
                        DB::raw("ROUND(SUM(
                    CASE
                        WHEN note_type = 'debit' THEN amount
                        WHEN note_type = 'credit' THEN -amount
                        ELSE 0
                    END
                ), 2) as net_notes_adjustment")
                    )
                    ->groupBy('customer_id'),
                'notes',
                'customers.customer_id',
                '=',
                'notes.customer_id'
            )
            ->select(
                'customers.customer_id',
                'customers.name',
                DB::raw('ROUND(inv.total_invoiced - COALESCE(pay.total_paid, 0) + COALESCE(notes.net_notes_adjustment, 0), 2) as balance')
            );

        $debtors = DB::table(DB::raw("({$baseQuery->toSql()}) as debtor_base"))
            ->mergeBindings($baseQuery)
            ->leftJoin('recovery_officer_assignments as roa', 'roa.customer_id', '=', 'debtor_base.customer_id')
            ->leftJoin('recovery_officers as ro', 'ro.id', '=', 'roa.recovery_officer_id')
            ->select(
                'debtor_base.customer_id',
                'debtor_base.name',
                'debtor_base.balance',
                DB::raw('COALESCE(ro.name, "N/A") as officer_name')
            )
            ->where('debtor_base.balance', '>', 0)
            ->orderByDesc('debtor_base.balance')
            ->get();

        $total = ['receivables' => isset($debtors) ? number_format($debtors->sum('balance'), 2) : '0.00'];

        return view('dashboard.debtors', compact('debtors', 'total', 'pageTitle'));
    }

    public function recoveryOfficerPerformance(Request $request)
    {
        $pageTitle = "Recovery Officer Performance Page";

        $today = Carbon::today();

        $invoiceDebts = DB::table('recovery_officer_assignments as roa')
            ->join('customers as c', 'c.customer_id', '=', 'roa.customer_id')
            ->join('invoices as i', 'i.customer_id', '=', 'c.customer_id')
            ->leftJoin('payments as p', 'p.invoice_no', '=', 'i.invoice_no')
            ->whereRaw(
                "DATE_ADD(i.created_at, INTERVAL c.due_date DAY) <= ?",
                [$today]
            )
            ->groupBy(
                'roa.recovery_officer_id',
                'i.invoice_no',
                'i.amount'
            )
            ->havingRaw("
                SUM(
                    CASE
                        WHEN p.payment_mode = 'momo'
                            AND p.transaction_status = 'Success'
                            THEN p.amount_paid + IFNULL(p.withholding_tax_amount, 0)
                        WHEN p.payment_mode != 'momo'
                            THEN p.amount_paid + IFNULL(p.withholding_tax_amount, 0)
                        ELSE 0
                    END
                ) < i.amount
            ")
            ->select(
                'roa.recovery_officer_id',
                DB::raw('i.amount AS invoice_amount'),
                DB::raw('
                    SUM(
                        CASE
                            WHEN p.payment_mode = "momo"
                                AND p.transaction_status = "Success"
                                THEN p.amount_paid + IFNULL(p.withholding_tax_amount, 0)
                            WHEN p.payment_mode != "momo"
                                THEN p.amount_paid + IFNULL(p.withholding_tax_amount, 0)
                            ELSE 0
                        END
                    ) AS total_paid
                ')
            );

        $debtsPerOfficer = DB::query()
            ->fromSub($invoiceDebts, 'd')
            ->select(
                'recovery_officer_id',
                DB::raw('SUM(invoice_amount) AS total_debt')
            )
            ->groupBy('recovery_officer_id');

        $results = DB::table('recovery_officers as ro')
            ->leftJoinSub($debtsPerOfficer, 'debts', function ($join) {
                $join->on('ro.id', '=', 'debts.recovery_officer_id');
            })
            ->select(
                'ro.id',
                'ro.name',
                DB::raw('COALESCE(debts.total_debt, 0) AS total_debt')
            )
            ->get();

        $grandTotalDebt = $results->sum('total_debt');

        $results = $results->map(function ($row) use ($grandTotalDebt) {
            $row->percentage = $grandTotalDebt > 0
                ? round(($row->total_debt / $grandTotalDebt) * 100, 2)
                : 0;

            return $row;
        });

        return view('dashboard.recovery-officer-performance', compact('results', 'grandTotalDebt', 'pageTitle'));
    }

    public function recoveryOfficerDebtDetails($recoveryOfficerId)
    {
        $pageTitle = "Recovery Officer Debt Details Page";

        $today = Carbon::today();

        $recoveryOfficer = DB::table('recovery_officers')
            ->where('id', $recoveryOfficerId)
            ->first();

        $invoices = DB::table('recovery_officer_assignments as roa')
            ->join('customers as c', 'c.customer_id', '=', 'roa.customer_id')
            ->join('invoices as i', 'i.customer_id', '=', 'c.customer_id')
            ->leftJoin('payments as p', 'p.invoice_no', '=', 'i.invoice_no')
            ->where('roa.recovery_officer_id', $recoveryOfficerId)
            ->whereRaw(
                "DATE_ADD(i.created_at, INTERVAL c.due_date DAY) <= ?",
                [$today]
            )
            ->groupBy(
                'i.id',
                'i.invoice_no',
                'i.amount',
                'i.created_at',
                'c.name'
            )
            ->havingRaw("
                SUM(
                    CASE
                        WHEN p.payment_mode = 'momo'
                            AND p.transaction_status = 'Success'
                            THEN p.amount_paid + IFNULL(p.withholding_tax_amount, 0)
                        WHEN p.payment_mode != 'momo'
                            THEN p.amount_paid + IFNULL(p.withholding_tax_amount, 0)
                        ELSE 0
                    END
                ) < i.amount
            ")
            ->select(
                'i.invoice_no',
                'c.name as customer_name',
                'i.amount',
                'i.created_at',
                DB::raw('
                i.amount -
                SUM(
                    CASE
                        WHEN p.payment_mode = "momo"
                            AND p.transaction_status = "Success"
                            THEN p.amount_paid + IFNULL(p.withholding_tax_amount, 0)
                        WHEN p.payment_mode != "momo"
                            THEN p.amount_paid + IFNULL(p.withholding_tax_amount, 0)
                        ELSE 0
                    END
                ) AS outstanding_balance
            ')
            )
            ->get();

        $totalAmount = $invoices->sum('amount');
        $totalBalance = $invoices->sum('outstanding_balance');

        $total = [
            'totalAmount' => isset($totalAmount) ? number_format($totalAmount, 2) : 0,
            'totalBalance' => isset($totalBalance) ? number_format($totalBalance, 2) : 0
        ];

        return view('dashboard.recovery-officer-debt-details', compact('recoveryOfficer', 'invoices', 'total', 'pageTitle'));
    }
}
