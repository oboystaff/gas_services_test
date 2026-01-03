<?php

namespace App\Http\Controllers\Invoice;

use App\Http\Controllers\Controller;
use App\Http\Requests\Invoice\CreateCreditDebitRequest;
use App\Http\Requests\Invoice\CreateInvoiceRequest;
use App\Http\Requests\Invoice\UpdateInvoiceRequest;
use App\Jobs\Invoice\SendInvoiceSMS;
use App\Models\Customer;
use App\Models\GasRequest;
use App\Models\Invoice;
use App\Models\Rate;
use App\Models\Payment;
use App\Models\InvoiceNote;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;


class InvoiceController extends Controller
{
    public function index(Request $request)
    {
        if (!auth()->user()->can('invoices.view')) {
            abort(403, 'Unauthorized action.');
        }

        $pageTitle = "Invoice Page";

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
            // ->when(!$request->month, function ($query) {
            //     $query->whereMonth('created_at', now()->month)
            //         ->whereYear('created_at', now()->year);
            // })
            ->orderBy('created_at', 'DESC')
            ->get();

        if ($request->display == "receivables") {
            $totalInvoiceAmount = Invoice::query()
                ->when(!empty($request->user()->branch_id), function ($query) use ($request) {
                    $query->where('branch_id', $request->user()->branch_id);
                })
                ->sum('amount');

            $totalPayments = Payment::query()
                ->when(!empty($request->user()->branch_id), function ($query) use ($request) {
                    $query->where('branch_id', $request->user()->branch_id);
                })
                ->where(function ($query) {
                    $query->where(function ($q) {
                        $q->where('payment_mode', 'momo')
                            ->where('transaction_status', 'Success');
                    })
                        ->orWhere(function ($q) {
                            $q->where('payment_mode', '<>', 'momo');
                        });
                })
                ->sum('amount_paid');

            $amount = $totalInvoiceAmount - $totalPayments;
        } else {
            $amount = $invoices->sum('amount');
        }

        $kg = $invoices->sum('kg');

        $total = [
            'kg' => isset($kg) ? number_format($kg, 2) : 0,
            'amount' => isset($amount) ? number_format($amount, 2) : 0
        ];

        return view('invoices.index', compact('invoices', 'total', 'pageTitle'));
    }

    public function create()
    {
        if (!auth()->user()->can('invoices.create')) {
            abort(403, 'Unauthorized action.');
        }

        return redirect()->route('gas-requests.index');
    }

    public function store(CreateInvoiceRequest $request)
    {
        $totalAmount = $request->filled('discount_amount')
            ? ((float)$request->input('amount') - (float)$request->input('discount_amount'))
            : (float)$request->input('amount');
        $data = $request->validated();
        $data['kg'] = $request->input('kg') ?? '0';
        $data['amount'] =   $totalAmount ?? 0;
        $data['created_by'] = $request->user()->id;
        $data['request_id'] = $request->input('request_id') ?? '';
        $data['branch_id'] = $request->input('branch_id') ?? '';
        $data['rate'] = $request->input('rate') ?? '';
        $data['discount'] = $request->input('discount') ?? 0;
        $data['discount_amount'] = $request->input('discount_amount') ?? 0;
        $customer = Customer::where('customer_id', $data['customer_id'])->first();

        if (!empty($customer->due_date)) {
            $currentDate = now();
            $dueDate = $currentDate->copy()->addDays((int) $customer->due_date)->setTimeFrom($currentDate);
            $data['due_date'] = $dueDate ?? null;
        }

        GasRequest::where('id', $data['request_id'])->update(['status' => 'Invoice Raised']);

        $invoice = Invoice::create($data);

        dispatch(new SendInvoiceSMS($invoice));

        return redirect()->route('invoices.index')->with('status', 'Invoice created successfully.');
    }

    public function show(Invoice $invoice)
    {
        $pageTitle = "Invoice Page";

        $pdf = Pdf::loadView('invoices.template', compact('invoice'))
            ->setPaper('A4', 'portrait');

        return $pdf->stream('invoice-' . $invoice->id . '.pdf');
    }

    public function edit(Invoice $invoice)
    {
        $pageTitle = "Invoice Page";

        $rate = Rate::latest()->first()->amount ?? 0;

        return view('invoices.edit', compact('invoice', 'rate', 'pageTitle'));
    }

    public function update(UpdateInvoiceRequest $request, Invoice $invoice)
    {
        $data = $request->validated();
        $totalAmount = $request->filled('discount_amount')
            ? ((float)$request->input('amount') - (float)$request->input('discount_amount'))
            : (float)$request->input('amount');
        $data = $request->validated();
        $data['kg'] = $request->input('kg');
        $data['amount'] = $totalAmount ?? 0;
        $data['discount'] = $request->input('discount') ?? 0;
        $data['discount_amount'] = $request->input('discount_amount') ?? 0;

        $invoice->update($data);

        return redirect()->route('invoices.index')->with('status', 'Invoice updated successfully.');
    }

    public function creditDebit(CreateCreditDebitRequest $request)
    {
        $data = $request->validated();
        $data['created_by'] = $request->user()->id;

        InvoiceNote::create($data);

        return redirect()->route('invoices.index')->with('status', 'Credit & Debit note created successfully.');
    }
}
