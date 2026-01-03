<?php

namespace App\Http\Controllers\DueDate;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Invoice;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;


class DueDateController extends Controller
{
    public function index(Request $request)
    {
        if (!auth()->user()->can('invoices.view')) {
            abort(403, 'Unauthorized action.');
        }

        $pageTitle = "Invoices Due Page";

        $invoices = Invoice::orderBy('created_at', 'DESC')
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
            ->when($request->display == "receivables", function ($query) {
                $query->whereRaw('amount > IFNULL((SELECT SUM(amount_paid) FROM payments WHERE payments.invoice_no = invoices.invoice_no), 0)');
            })
            ->when($request->month, function ($query) use ($request) {
                $query->whereMonth('created_at', $request->month)
                    ->whereYear('created_at', $request->year ?? now()->year);
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
                    ->havingRaw(
                        'SUM(payments.amount_paid + IFNULL(payments.withholding_tax_amount, 0)) >= invoices.amount'
                    );
            })
            ->orderBy('created_at', 'DESC')
            ->get();

        $amount = $invoices->sum('amount');
        $kg = $invoices->sum('kg');

        $total = [
            'kg' => isset($kg) ? number_format($kg, 2) : 0,
            'amount' => isset($amount) ? number_format($amount, 2) : 0
        ];

        return view('due_dates.index', compact('invoices', 'total', 'pageTitle'));
    }
}
