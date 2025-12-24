<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Branch;
use App\Models\Payment;


class PaymentReportController extends Controller
{
    public function index(Request $request)
    {
        try {
            if (!auth()->user()->can('reports.view')) {
                abort(403, 'Unauthorized action.');
            }

            $pageTitle = "Payment Report Page";

            $branches = Branch::orderBy('name', 'ASC')
                ->when(!empty($request->user()->branch_id), function ($query) use ($request) {
                    $query->where('id', $request->user()->branch_id);
                })
                ->get();


            if (request()->ajax()) {

                if ($request->report_type == 1) {
                    $data = Payment::orderBy('created_at', 'DESC')
                        ->when(($request->filled('from_date') && $request->filled('to_date')), function ($query) use ($request) {
                            $query->whereBetween('created_at', [$request->from_date . ' 00:00:00', $request->to_date . ' 23:59:59']);
                        })
                        ->when(!empty($request->user()->branch_id), function ($query) use ($request) {
                            $query->where('branch_id', $request->user()->branch_id);
                        })
                        ->when($request->filled('branch_id'), function ($query) use ($request) {
                            $query->where('branch_id', $request->branch_id);
                        })
                        ->when($request->filled('payment_mode'), function ($query) use ($request) {
                            $query->where('payment_mode', $request->payment_mode);
                        })
                        ->get();

                    return datatables()->of($data)
                        ->addIndexColumn()
                        ->editColumn('created_by', function (Payment $payment) {
                            return $payment->createdBy->name ?? '';
                        })
                        ->editColumn('invoice_no', function (Payment $payment) {
                            return $payment->invoice_no ?? 'N/A';
                        })
                        ->editColumn('amount', function (Payment $payment) {
                            return number_format($payment->amount, 2) ?? '0.00';
                        })
                        ->editColumn('amount_paid', function (Payment $payment) {
                            return number_format($payment->amount_paid, 2) ?? '0.00';
                        })
                        ->editColumn('withholding_tax_amount', function (Payment $payment) {
                            return number_format($payment->withholding_tax_amount, 2) ?? '0.00';
                        })
                        ->editColumn('name', function (Payment $payment) {
                            return $payment->customer->name ?? 'N/A';
                        })
                        ->editColumn('created_at', function (Payment $payment) {
                            return $payment->created_at;
                        })
                        ->make(true);
                } else {
                }
            }

            return view('reports.payment-report', compact('branches', 'pageTitle'));
        } catch (\Exception $ex) {
            return $ex->getMessage();
        }
    }
}
