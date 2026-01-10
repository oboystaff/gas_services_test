<?php

namespace App\Http\Controllers\API\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\Customer\CreateCustomerRequest;
use App\Http\Requests\API\Customer\UpdateCustomerRequest;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Community;
use App\Models\InvoiceNote;
use Illuminate\Http\Request;
use App\Jobs\Customer\SendCustomerSMS;
use App\Models\GasRequest;
use Illuminate\Support\Facades\DB;


class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $data = Customer::orderBy('created_at', 'DESC')
            ->when(!empty($request->user()->branch_id), function ($query) use ($request) {
                $query->where('branch_id', $request->user()->branch_id);
            })
            ->with(['branch'])
            ->get();

        return response()->json([
            'message' => 'Get all customers',
            'data' => $data
        ]);
    }

    public function show($id)
    {
        $customer = Customer::query()
            ->with(['branch'])
            ->where('customer_id', $id)
            ->first();

        if (empty($customer)) {
            return response()->json([
                'message' => 'Customer not found'
            ], 422);
        }

        return response()->json([
            'message' => 'Get particular customer',
            'data' => $customer
        ]);
    }

    public function store(CreateCustomerRequest $request)
    {
        $data = $request->validated();
        $data['created_by'] = $request->user()->id;

        $customer = Customer::create($data);

        if ($customer) {
            $userData = [
                'name' => $customer->name ?? 'N/A',
                'email' => $customer->customer_id . '@manbahgh.com',
                'phone' => $customer->contact ?? 'N/A',
                'password' => Hash::make('manbah123456'),
                'status' => 'Active',
                'branch_id' => $customer->branch_id,
                'customer_id' => $customer->customer_id,
                'created_by' => $request->user()->id ?? null
            ];

            User::create($userData);
        }

        dispatch(new SendCustomerSMS($customer));

        return response()->json([
            'message' => 'Customer created successfully',
            'data' => $customer
        ]);
    }

    public function update(UpdateCustomerRequest $request, $id)
    {
        $customer = Customer::query()
            ->with(['branch'])
            ->where('customer_id', $id)
            ->first();

        if (empty($customer)) {
            return response()->json([
                'message' => 'Customer not found'
            ], 422);
        }

        $customer->update($request->validated());

        return response()->json([
            'message' => 'Customer updated successfully'
        ]);
    }

    public function balance($id)
    {
        $totalInvoice = Invoice::where('customer_id', $id)->sum('amount');
        $totalPayment = Payment::where('customer_id', $id)
            ->sum(DB::raw("
                CASE
                    WHEN payment_mode = 'momo' AND transaction_status = 'Success'
                        THEN amount_paid + IFNULL(withholding_tax_amount, 0)
                    WHEN payment_mode != 'momo'
                        THEN amount_paid + IFNULL(withholding_tax_amount, 0)
                    ELSE 0
                END
            "));

        $creditNotes = InvoiceNote::where('customer_id', $id)
            ->where('note_type', 'credit')
            ->sum('amount');

        $debitNotes = InvoiceNote::where('customer_id', $id)
            ->where('note_type', 'debit')
            ->sum('amount');

        $customer = Customer::where('customer_id', $id)->first();

        if (empty($customer)) {
            return response()->json([
                'message' => 'Customer not found, try again'
            ], 422);
        }

        $communityNames = Community::whereIn('id', $customer->community_id)->pluck('name')->implode(', ');
        $customer['community'] = isset($communityNames) ? $communityNames : 'N/A';
        unset($customer['community_id']);

        $balance = $totalInvoice - $totalPayment - $creditNotes + $debitNotes;

        return response()->json([
            'message' => 'Customer balance',
            'data' => $customer ?? null,
            'balance' => isset($balance) ? number_format($balance, 2) : 0
        ]);
    }

    public function customerRequest($id)
    {
        $gasRequests = GasRequest::where('customer_id', $id)
            ->with(['customer', 'driverAssigned', 'deliveryBranch'])
            ->get()
            ->map(function ($request) {

                if ($request->customer && !empty($request->customer->community_id)) {
                    $communityNames = Community::whereIn(
                        'id',
                        $request->customer->community_id
                    )->pluck('name')->implode(', ');
                } else {
                    $communityNames = 'N/A';
                }

                $request->community = $communityNames;

                return $request;
            });

        return response()->json([
            'message' => 'Customer gas request(s)',
            'data' => $gasRequests
        ]);
    }

    public function statement($id)
    {
        $customer = Customer::where('customer_id', $id)->first();

        if (empty($customer)) {
            return response()->json([
                'message' => 'Customer data not found'
            ], 422);
        }

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
                'type'        => 'Invoice',
                'date'        => $invoice->created_at,
                'amount'      => (float) $invoice->amount,
                'effect'      => '+',
                'reference'   => $invoice->invoice_no,
                'description' => 'Invoice No: ' . $invoice->invoice_no,
            ]);
        }

        foreach ($payments as $payment) {
            $statement->push([
                'type'        => 'Payment',
                'date'        => $payment->created_at,
                'amount'      => (float) $payment->amount_paid,
                'effect'      => '-',
                'reference'   => $payment->payment_id,
                'description' => 'Payment No: ' . $payment->payment_id,
            ]);

            if (
                !empty($payment->withholding_tax) && $payment->withholding_tax > 0 &&
                !empty($payment->withholding_tax_amount) && $payment->withholding_tax_amount > 0
            ) {
                $statement->push([
                    'type'        => 'Withholding Tax',
                    'date'        => $payment->created_at,
                    'amount'      => (float) $payment->withholding_tax_amount,
                    'effect'      => '-',
                    'reference'   => $payment->payment_id,
                    'description' => 'Withholding Tax (' . $payment->withholding_tax . '%)',
                ]);
            }
        }

        foreach ($notes as $note) {
            $isCredit = strtolower($note->note_type) === 'credit';

            $statement->push([
                'type'        => ucfirst($note->note_type) . ' Note',
                'date'        => $note->created_at,
                'amount'      => (float) $note->amount,
                'effect'      => $isCredit ? '-' : '+',
                'reference'   => $note->invoice_no,
                'description' => ucfirst($note->note_type) . ' Note: ' . $note->invoice_no,
            ]);
        }

        $statement = $statement->sortBy('date')->values();

        $totalInvoiced = (float) $invoices->sum('amount');
        $totalPaid     = (float) $payments->sum('amount_paid')
            + (float) $payments->sum('withholding_tax_amount');

        $creditNotes = (float) $notes->where('note_type', 'credit')->sum('amount');
        $debitNotes  = (float) $notes->where('note_type', 'debit')->sum('amount');

        $balance = $totalInvoiced - $totalPaid - $creditNotes + $debitNotes;

        return response()->json([
            'message' => 'Customer statement retrieved successfully',
            'data' => [
                'customer' => [
                    'customer_id' => $customer->customer_id,
                    'name'        => $customer->name,
                    'contact'     => $customer->contact ?? null,
                ],
                'summary' => [
                    'total_invoiced' => number_format($totalInvoiced, 2, '.', ''),
                    'total_paid'     => number_format($totalPaid, 2, '.', ''),
                    'credit_notes'   => number_format($creditNotes, 2, '.', ''),
                    'debit_notes'    => number_format($debitNotes, 2, '.', ''),
                    'balance'        => number_format($balance, 2, '.', ''),
                ],
                'statement' => $statement,
            ]
        ]);
    }

    public function receipt(Request $request, $id, $from_date, $to_date)
    {
        $customer = Customer::where('customer_id', $id)->first();

        if (empty($customer)) {
            return response()->json([
                'message' => 'Customer data not found'
            ], 422);
        }

        $invoices = Invoice::orderBy('created_at', 'DESC')
            ->with(['customer', 'gasRequest'])
            ->where('customer_id', $customer->customer_id)
            ->when(!empty($request->user()->branch_id), function ($query) use ($request) {
                $query->where('branch_id', $request->user()->branch_id);
            })
            ->when(!empty($from_date) && !empty($to_date), function ($query) use ($from_date, $to_date) {
                $query->whereBetween('created_at', [
                    \Carbon\Carbon::parse($from_date)->startOfDay(),
                    \Carbon\Carbon::parse($to_date)->endOfDay()
                ]);
            })
            ->get();

        if (!empty($from_date) && !empty($to_date) && $invoices->isEmpty()) {
            return response()->json([
                'message' => 'No invoices found for this customer within the specified date range'
            ], 422);
        }

        if ($invoices->isEmpty()) {
            return response()->json([
                'message' => 'No invoices found for this customer'
            ], 422);
        }

        $totalInvoice = $invoices->sum('amount');

        return response()->json([
            'message' => 'Customer receipt retrieved successfully',
            'data' => $invoices,
            'total_invoiced' => $totalInvoice
        ]);
    }
}
