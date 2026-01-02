<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Branch;
use App\Models\Invoice;

class InvoiceReportController extends Controller
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

            $totalInvoice = Invoice::query()
                ->when(!empty($request->user()->branch_id), function ($query) use ($request) {
                    $query->where('branch_id', $request->user()->branch_id);
                })
                ->sum('amount');

            if (request()->ajax()) {

                if ($request->report_type == 1) {
                    $data = Invoice::orderBy('created_at', 'DESC')
                        ->when(($request->filled('from_date') && $request->filled('to_date')), function ($query) use ($request) {
                            $query->whereBetween('created_at', [$request->from_date . ' 00:00:00', $request->to_date . ' 23:59:59']);
                        })
                        ->when(!empty($request->user()->branch_id), function ($query) use ($request) {
                            $query->where('branch_id', $request->user()->branch_id);
                        })
                        ->when($request->filled('branch_id'), function ($query) use ($request) {
                            $query->where('branch_id', $request->branch_id);
                        })
                        ->get();

                    $totalInvoice = (clone $data)->sum('amount');
                    $totalKG = (clone $data)->sum('kg');

                    return datatables()->of($data)
                        ->addIndexColumn()
                        ->editColumn('created_by', function (Invoice $invoice) {
                            return $invoice->createdBy->name ?? '';
                        })
                        ->editColumn('branch', function (Invoice $invoice) {
                            return $invoice->branch->name ?? 'N/A';
                        })
                        ->editColumn('name', function (Invoice $invoice) {
                            return $invoice->customer->name ?? 'N/A';
                        })
                        ->editColumn('amount', function (Invoice $invoice) {
                            return number_format($invoice->amount, 2) ?? '0.00';
                        })
                        ->editColumn('discount_amount', function (Invoice $invoice) {
                            return number_format($invoice->discount_amount, 2) ?? '0.00';
                        })
                        ->editColumn('driver', function (Invoice $invoice) {
                            return $invoice->gasRequest->driverAssigned->name ?? 'N/A';
                        })
                        ->editColumn('vehicle', function (Invoice $invoice) {
                            return $invoice->gasRequest->driverAssigned->vehicle->vehicle_number ?? 'N/A';
                        })
                        ->editColumn('created_at', function (Invoice $invoice) {
                            return $invoice->created_at;
                        })
                        ->with([
                            'totalInvoice' => number_format($totalInvoice, 2),
                            'totalKG' => number_format($totalKG, 2)
                        ])
                        ->make(true);
                } else {
                }
            }

            return view('reports.invoice-report', compact('branches', 'pageTitle'));
        } catch (\Exception $ex) {
            return $ex->getMessage();
        }
    }
}
