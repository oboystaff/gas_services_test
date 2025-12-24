<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Branch;
use App\Models\Invoice;
use Illuminate\Support\Facades\DB;


class ReceivablesReportController extends Controller
{
    public function index(Request $request)
    {
        try {
            if (!auth()->user()->can('reports.view')) {
                abort(403, 'Unauthorized action.');
            }

            $pageTitle = "Invoice Report Page";

            $branches = Branch::orderBy('name', 'ASC')
                ->when(!empty($request->user()->branch_id), function ($query) use ($request) {
                    $query->where('id', $request->user()->branch_id);
                })
                ->get();


            if (request()->ajax()) {

                if ($request->report_type == 1) {
                    $data = Invoice::select(
                        'invoices.*',
                        'summary.total_invoiced',
                        DB::raw('IFNULL(summary.total_paid, 0) as total_paid'),
                        DB::raw('(summary.total_invoiced - IFNULL(summary.total_paid, 0)) as receivable')
                    )
                        ->join(DB::raw('(
                            SELECT 
                                customer_id, 
                                SUM(amount) as total_invoiced,
                                COALESCE((
                                    SELECT SUM(
                                        COALESCE(amount_paid, 0) + COALESCE(withholding_tax_amount, 0)
                                    )
                                    FROM payments
                                    WHERE payments.customer_id = invoices.customer_id
                                ), 0) as total_paid,
                                MAX(created_at) as latest_invoice_date
                            FROM invoices
                            GROUP BY customer_id
                        ) as summary'), function ($join) {
                            $join->on('invoices.customer_id', '=', 'summary.customer_id')
                                ->on('invoices.created_at', '=', 'summary.latest_invoice_date');
                        })
                        ->when(($request->filled('from_date') && $request->filled('to_date')), function ($query) use ($request) {
                            $query->whereBetween(
                                'invoices.created_at',
                                [$request->from_date . ' 00:00:00', $request->to_date . ' 23:59:59']
                            );
                        })
                        ->when(!empty($request->user()->branch_id), function ($query) use ($request) {
                            $query->where('invoices.branch_id', $request->user()->branch_id);
                        })
                        ->when($request->filled('branch_id'), function ($query) use ($request) {
                            $query->where('invoices.branch_id', $request->branch_id);
                        })
                        ->havingRaw('summary.total_invoiced > COALESCE((
                            SELECT SUM(
                                COALESCE(amount_paid, 0) + COALESCE(withholding_tax_amount, 0)
                            )
                            FROM payments
                            WHERE payments.customer_id = invoices.customer_id
                        ), 0)')
                        ->get();


                    return datatables()->of($data)
                        ->addIndexColumn()
                        ->editColumn('created_by', function (Invoice $invoice) {
                            return $invoice->createdBy->name ?? '';
                        })
                        ->editColumn('branch', function (Invoice $invoice) {
                            return $invoice->branch->name ?? 'N/A';
                        })
                        ->editColumn('community', function (Invoice $invoice) {
                            return $invoice->customer->community->name ?? 'N/A';
                        })
                        ->editColumn('total_invoiced', function (Invoice $invoice) {
                            return number_format($invoice->total_invoiced, 2) ?? '0.00';
                        })
                        ->editColumn('total_paid', function (Invoice $invoice) {
                            return number_format($invoice->total_paid, 2) ?? '0.00';
                        })
                        ->editColumn('receivable', function (Invoice $invoice) {
                            return number_format($invoice->receivable, 2) ?? '0.00';
                        })
                        ->editColumn('name', function (Invoice $invoice) {
                            return $invoice->customer->name ?? 'N/A';
                        })
                        ->editColumn('created_at', function (Invoice $invoice) {
                            return $invoice->created_at;
                        })
                        ->make(true);
                } else {
                }
            }

            return view('reports.receivables-report', compact('branches', 'pageTitle'));
        } catch (\Exception $ex) {
            return $ex->getMessage();
        }
    }
}
