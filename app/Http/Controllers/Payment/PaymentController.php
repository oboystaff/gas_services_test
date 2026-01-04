<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Http\Requests\Payment\CreatePaymentRequest;
use App\Jobs\Payment\SendPaymentSMS;
use App\Models\Invoice;
use App\Models\Payment;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;


class PaymentController extends Controller
{
    public function index(Request $request)
    {
        if (!auth()->user()->can('payments.view')) {
            abort(403, 'Unauthorized action.');
        }

        $pageTitle = "Payment Page";

        $payments = Payment::orderBy('created_at', 'DESC')
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
            ->where(function ($q) {
                $q->where(function ($q1) {
                    $q1->where('payment_mode', 'momo')
                        ->where('transaction_status', 'Success');
                })
                    ->orWhere('payment_mode', '!=', 'momo');
            })
            ->get();

        $amount = $payments->sum("amount");
        $amount_paid = $payments->sum("amount_paid");
        $outstanding = $payments->sum("outstanding");
        $withholding_amount = $payments->sum("withholding_tax_amount");

        $total = [
            'amount' => isset($amount) ? number_format($amount, 2) : 0,
            'amount_paid' => isset($amount_paid) ? number_format($amount_paid, 2) : 0,
            'outstanding' => isset($outstanding) ? number_format($outstanding, 2) : 0,
            'withholding_tax_amount' => isset($withholding_amount) ? number_format($withholding_amount, 2) : 0
        ];

        return view('payments.index', compact('payments', 'total', 'pageTitle'));
    }

    public function create(Invoice $invoice)
    {
        $pageTitle = "Payment Page";

        return view('payments.create', compact('pageTitle', 'invoice'));
    }

    public function store(CreatePaymentRequest $request)
    {
        $data = $request->validated();
        $data['created_by'] = $request->user()->id;
        $data['outstanding'] = 0;
        $data['branch_id'] = $request->input('branch_id') ?? '';
        $data['payment_source'] = "WEB";
        $data['transaction_status'] = "Success";
        $data['withholding_tax'] = $request->input('withholding_tax') ?? 0;
        $data['withholding_tax_amount'] = $request->input('withholding_tax_amount') ?? 0;
        $data['reference'] = $request->input('reference') ?? null;

        $payment = Payment::create($data);

        if (!empty($data['invoice_no'])) {

            $totalPayment = Payment::where('invoice_no', $data['invoice_no'])
                ->selectRaw('SUM(COALESCE(amount_paid,0) + COALESCE(withholding_tax_amount,0)) as total')
                ->value('total');

            if ((float) $totalPayment >= (float) $data['amount']) {
                Invoice::where('invoice_no',  $data['invoice_no'])->update(['due_date' => null]);
            }
        }

        dispatch(new SendPaymentSMS($payment));

        return redirect()->route('payments.index')->with('status', 'Payment created successfully.');
    }

    public function show(Payment $payment)
    {
        $pageTitle = "Payment Page";

        return view('payments.show', compact('payment', 'pageTitle'));
    }

    public function edit() {}

    public function update() {}

    public function generateReceipt(Payment $payment)
    {
        $receiptData = [
            'receipt_no' => $payment->payment_id,
            'customer_name' => $payment->customer->name ?? '',
            'customer_id' => $payment->customer_id ?? 'N/A',
            'phone_no' => $payment->customer->contact ?? 'N/A',
            'delivery_branch' => $payment->invoice->gasRequest->deliveryBranch->name ?? 'N/A',
            'payment_date' => Carbon::parse($payment->created_at)->format('d-M-Y H:i'),
            'description' => 'Gas Cylinder Refill',
            'amount' => $payment->amount,
            'payment_mode' => strtoupper($payment->payment_mode),
            'reference' => $payment->payment_id . $payment->payment_id . $payment->customer_id,
            'paid_by' => $payment->customer->name ?? 'N/A',
            'generated_date' => now()->format('d/m/Y, H:i')
        ];

        $pdf = Pdf::loadView('payments.receipt', $receiptData);

        $pdf->setPaper('a4', 'portrait');

        return $pdf->stream('receipt_' . $receiptData['receipt_no'] . '.pdf');
    }
}
