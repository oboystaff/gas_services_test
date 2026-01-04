<?php

namespace App\Jobs\Payment;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Actions\SMS\SendSMS;
use App\Models\InvoiceNote;


class SendPaymentSMS implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    protected $payment;

    public function __construct($payment)
    {
        $this->payment = $payment;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $customer = $this->payment->customer;
        $totalInvoices = $customer->invoices()->sum('amount');
        $paymentLink = route('customer_payment.receipt', $this->payment->id);

        $totalPayments = $customer->payments()
            ->selectRaw("
                SUM(
                    CASE 
                        WHEN payment_mode = 'momo' 
                            AND transaction_status = 'Success'
                        THEN amount_paid + COALESCE(withholding_tax_amount, 0)

                        WHEN payment_mode != 'momo'
                        THEN amount_paid + COALESCE(withholding_tax_amount, 0)

                        ELSE 0
                    END
                ) as total
            ")
            ->value('total');

        $creditNotes = InvoiceNote::where('customer_id', $this->payment->customer->customer_id)
            ->where('note_type', 'credit')
            ->sum('amount');

        $debitNotes = InvoiceNote::where('customer_id', $this->payment->customer->customer_id)
            ->where('note_type', 'debit')
            ->sum('amount');

        $balance = $totalInvoices - $totalPayments -  $creditNotes + $debitNotes;

        $msg = "Hello " . $customer->name . ", ";
        $msg .= "Manbah Gas has credited your account(" . $customer->customer_id . ") with GHC " . number_format($this->payment->amount_paid, 2) . ". ";
        $msg .= "Your current balance is GHC " . number_format($balance, 2) . ". ";
        $msg .= "View payment receipt: " . $paymentLink . ". ";
        $msg .= "Kindly contact us on " . env('COMPANY_CONTACT') . " for any complaints.";

        $phone = $customer->contact;

        SendSMS::sendSMS($phone, $msg);
    }
}
