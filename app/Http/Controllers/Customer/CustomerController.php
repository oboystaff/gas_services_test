<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\CreateCustomerRequest;
use App\Http\Requests\Customer\UpdateCustomerRequest;
use App\Http\Requests\Invoice\CreateCreditDebitRequest;
use App\Http\Requests\GasRequest\CreateGasRequest;
use App\Jobs\Customer\SendCustomerSMS;
use App\Jobs\GasRequest\SendGasRequestSMS;
use App\Models\Branch;
use App\Models\Community;
use App\Models\Customer;
use App\Models\GasRequest;
use App\Models\Rate;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\InvoiceNote;
use App\Models\RecoveryOfficer;
use App\Models\RecoveryOfficerAssignment;
use App\Models\User;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;


class CustomerController extends Controller
{
    public function index(Request $request)
    {
        if (!auth()->user()->can('customers.view')) {
            abort(403, 'Unauthorized action.');
        }

        $pageTitle = "Customer Page";

        $customers = Customer::orderBy('created_at', 'DESC')
            ->with(['invoices:id,customer_id,invoice_no'])
            ->when(!empty($request->user()->branch_id), function ($query) use ($request) {
                $query->where('branch_id', $request->user()->branch_id);
            })
            ->get();

        return view('customers.index', compact('customers', 'pageTitle'));
    }

    public function create(Request $request)
    {
        if (!auth()->user()->can('customers.create')) {
            abort(403, 'Unauthorized action.');
        }

        $pageTitle = "Customer Page";

        $communities = Community::orderBy('created_at', 'DESC')->get();

        $branches = Branch::orderBy('created_at', 'DESC')
            ->when(!empty($request->user()->branch_id), function ($query) use ($request) {
                $query->where('id', $request->user()->branch_id);
            })
            ->get();

        $recoveryOfficers = RecoveryOfficer::orderBy('name', 'ASC')->get();

        return view('customers.create', compact('communities', 'branches', 'recoveryOfficers', 'pageTitle'));
    }

    public function store(CreateCustomerRequest $request)
    {
        $data = $request->validated();
        $data['created_by'] = $request->user()->id;

        $customer = Customer::create($data);

        if ($customer) {
            $assignmentData = [
                'customer_id' => $customer->customer_id,
                'recovery_officer_id' => $data['recovery_officer_id'],
                'created_by' => $request->user()->id
            ];

            RecoveryOfficerAssignment::create($assignmentData);
        }

        dispatch(new SendCustomerSMS($customer));

        return redirect()->route('customers.index')->with('status', 'Customer created successfully.');
    }

    public function show(Customer $customer)
    {
        $pageTitle = "Customer Page";

        return view('customers.show', compact('customer', 'pageTitle'));
    }

    public function edit(Request $request, Customer $customer)
    {
        if (!auth()->user()->can('customers.update')) {
            abort(403, 'Unauthorized action.');
        }

        $pageTitle = "Customer Page";

        $communities = Community::orderBy('created_at', 'DESC')->get();

        $branches = Branch::orderBy('created_at', 'DESC')
            ->when(!empty($request->user()->branch_id), function ($query) use ($request) {
                $query->where('id', $request->user()->branch_id);
            })
            ->get();

        $customer->recovery_officer_id = RecoveryOfficerAssignment::where('customer_id', $customer->customer_id)
            ->value('recovery_officer_id');

        $recoveryOfficers = RecoveryOfficer::orderBy('name', 'ASC')->get();

        return view('customers.edit', compact('customer', 'communities', 'branches', 'recoveryOfficers', 'pageTitle'));
    }

    public function update(UpdateCustomerRequest $request, Customer $customer)
    {
        $data = $request->validated();
        $data['threshold'] = $request->has('threshold') ? $request->input('threshold') : 'N';

        $customer->update($data);

        RecoveryOfficerAssignment::updateOrCreate(
            [
                'customer_id' => $customer->customer_id,
            ],
            [
                'recovery_officer_id' => $data['recovery_officer_id'],
                'created_by' => $request->user()->id,
            ]
        );

        return redirect()->route('customers.index')->with('status', 'Customer updated successfully.');
    }

    public function gasRequest(Customer $customer)
    {
        if (!auth()->user()->can('gas-requests.create')) {
            abort(403, 'Unauthorized action.');
        }

        $pageTitle = "Customer Gas Request Page";
        $rate = Rate::latest('created_at')->first()->amount ?? 0;
        $branches = Branch::orderBy('created_at', 'DESC')->get();

        return view('customers.request', compact('customer', 'rate', 'branches', 'pageTitle'));
    }

    public function gasRequestStore(CreateGasRequest $request, Customer $customer)
    {
        $data = $request->validated();
        $data['name'] = $customer->name ?? '';
        $data['contact'] = $customer->contact ?? '';
        $data['kg'] = $request->input('kg') ?? 0;
        $data['amount'] = $request->input('amount') ?? 0;
        $data['created_by'] = $request->user()->id ?? '';
        $data['community_id'] = $customer->community_id ?? '';
        $data['branch_id'] = $customer->branch_id ?? '';
        $data['delivery_branch'] = $request->input('delivery_branch');

        $totalInvoice = Invoice::where('customer_id', $data['customer_id'])->sum('amount');
        $totalPayment = Payment::where('customer_id', $data['customer_id'])
            ->sum(DB::raw("
                CASE
                    WHEN payment_mode = 'momo' AND transaction_status = 'Success' THEN amount_paid
                    WHEN payment_mode != 'momo' THEN amount_paid
                    ELSE 0
                END
            "));

        $balance = $totalInvoice - $totalPayment;

        if ($customer->threshold == 'Y' && bccomp($balance, $customer->threshold_amount, 2) >= 0) {
            return redirect()->route('customers.index')->with('error', 'You have exceeded your threshold, contact the admin.');
        }

        $assignedGasRequest = GasRequest::where('customer_id', $data['customer_id'])
            ->where('status', 'Driver Assigned')
            ->where('delivery_branch', $data['delivery_branch'])
            ->latest('created_at')
            ->first();

        if (!empty($assignedGasRequest)) {
            $delivery_branch = $assignedGasRequest->deliveryBranch->name ?? 'N/A';
            return redirect()->route('customers.index')->with('error', 'There is already pending gas request for this branch(' . $delivery_branch . '), try again later.');
        }

        $gasRequest = GasRequest::create($data);

        dispatch(new SendGasRequestSMS($gasRequest));

        return redirect()->route('gas-requests.index')->with('status', 'Gas request created successfully.');
    }

    public function statement(Customer $customer)
    {
        $pageTitle = "Customer Statement Page";

        $invoices = Invoice::where('customer_id', $customer->customer_id)->get();
        $payments = Payment::where('customer_id', $customer->customer_id)
            ->where(function ($q) {
                $q->where(function ($q1) {
                    $q1->where('payment_mode', 'momo')
                        ->where('transaction_status', 'Success');
                })
                    ->orWhere('payment_mode', '!=', 'momo');
            })
            ->get();

        $notes = InvoiceNote::where('customer_id', $customer->customer_id)->get();

        $statement = collect();

        foreach ($invoices as $invoice) {
            $statement->push([
                'type' => 'Invoice',
                'date' => $invoice->created_at,
                'amount' => $invoice->amount,
                'effect' => '+',
                'description' => 'Invoice No: ' . $invoice->invoice_no,
            ]);
        }

        foreach ($payments as $payment) {
            $statement->push([
                'type' => 'Payment',
                'date' => $payment->created_at,
                'amount' => $payment->amount_paid,
                'effect' => '-',
                'description' => 'Payment No: ' . $payment->payment_id,
            ]);

            if (
                !empty($payment->withholding_tax) && $payment->withholding_tax > 0
                && !empty($payment->withholding_tax_amount) && $payment->withholding_tax_amount > 0
            ) {

                $statement->push([
                    'type' => 'Withholding Tax',
                    'date' => $payment->created_at,
                    'amount' => $payment->withholding_tax_amount,
                    'effect' => '-',
                    'description' => 'Withholding Tax (' . $payment->withholding_tax . '%) - Payment No: ' . $payment->payment_id,
                ]);
            }
        }

        foreach ($notes as $note) {
            $isCredit = strtolower($note->note_type) === 'credit';

            $statement->push([
                'type' => ucfirst($note->note_type) . ' Note',
                'date' => $note->created_at,
                'amount' => $note->amount,
                'effect' => $isCredit ? '-' : '+',
                'description' => ucfirst($note->note_type) . ' Note: ' . $note->invoice_no,
            ]);
        }

        $statement = $statement->sortBy('date')->values();

        $total_invoiced = $invoices->sum('amount');
        $total_paid = $payments->sum('amount_paid');
        $total_paid += $payments->sum('withholding_tax_amount');

        $credit_notes = $notes->where('note_type', 'credit')->sum('amount');
        $debit_notes  = $notes->where('note_type', 'debit')->sum('amount');

        $balance = $total_invoiced - $total_paid - $credit_notes + $debit_notes;

        return view('customers.statement', compact(
            'customer',
            'statement',
            'total_invoiced',
            'total_paid',
            'credit_notes',
            'debit_notes',
            'balance',
            'pageTitle'
        ));
    }

    public function downloadStatementPDF(Customer $customer)
    {
        $pageTitle = "Customer Statement Page";

        $invoices = Invoice::where('customer_id', $customer->customer_id)->get();
        $payments = Payment::where('customer_id', $customer->customer_id)
            ->where(function ($q) {
                $q->where(function ($q1) {
                    $q1->where('payment_mode', 'momo')
                        ->where('transaction_status', 'Success');
                })
                    ->orWhere('payment_mode', '!=', 'momo');
            })
            ->get();

        $notes = InvoiceNote::where('customer_id', $customer->customer_id)->get();

        $statement = collect();

        foreach ($invoices as $invoice) {
            $statement->push([
                'type' => 'Invoice',
                'date' => $invoice->created_at,
                'amount' => $invoice->amount ?? $invoice->amount ?? 0,
                'effect' => '+',
                'description' => 'Invoice No: ' . $invoice->invoice_no,
            ]);
        }

        foreach ($payments as $payment) {
            $statement->push([
                'type' => 'Payment',
                'date' => $payment->created_at,
                'amount' => $payment->amount_paid ?? $payment->amount_paid ?? 0,
                'effect' => '-',
                'description' => 'Payment No: ' . $payment->payment_id,
            ]);

            if (
                !empty($payment->withholding_tax) && $payment->withholding_tax > 0
                && !empty($payment->withholding_tax_amount) && $payment->withholding_tax_amount > 0
            ) {

                $statement->push([
                    'type' => 'Withholding Tax',
                    'date' => $payment->created_at,
                    'amount' => $payment->withholding_tax_amount,
                    'effect' => '-',
                    'description' => 'Withholding Tax (' . $payment->withholding_tax . '%) - Payment No: ' . $payment->payment_id,
                ]);
            }
        }

        foreach ($notes as $note) {
            $isCredit = strtolower($note->note_type) === 'credit';

            $statement->push([
                'type' => ucfirst($note->note_type) . ' Note',
                'date' => $note->created_at,
                'amount' => $note->amount,
                'effect' => $isCredit ? '-' : '+',
                'description' => ucfirst($note->note_type) . ' Note: ' . $note->invoice_no,
            ]);
        }

        $statement = $statement->sortBy('date');

        $total_invoiced = $invoices->sum('amount');
        $total_paid = $payments->sum('amount_paid');
        $total_paid += $payments->sum('withholding_tax_amount');
        $credit_notes = $notes->where('note_type', 'credit')->sum('amount');
        $debit_notes  = $notes->where('note_type', 'debit')->sum('amount');
        $balance = $total_invoiced - $total_paid - $credit_notes + $debit_notes;

        $pdf = Pdf::loadView('customers.statement-pdf', compact(
            'customer',
            'statement',
            'total_invoiced',
            'total_paid',
            'balance'
        ));

        return $pdf->stream('customer_statement.pdf');
    }

    public function makePayment(Customer $customer)
    {
        $pageTitle = "Customer Page";

        return view('customers.make-payment', compact('customer', 'pageTitle'));
    }

    public function creditDebit(CreateCreditDebitRequest $request)
    {
        $data = $request->validated();
        $data['created_by'] = $request->user()->id;

        InvoiceNote::create($data);

        return redirect()->route('customers.index')->with('status', 'Credit & Debit note created successfully.');
    }
}
