<?php

namespace App\Jobs\Invoice;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Actions\SMS\SendSMS;
use App\Models\Rate;
use Termwind\Components\Raw;

class SendInvoiceSMS implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    protected $invoice;

    public function __construct($invoice)
    {
        $this->invoice = $invoice;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $rate = Rate::latest()->first();
        $dateFormatted = \Carbon\Carbon::parse($this->invoice->created_at)->format('jS, F Y');
        $totalInvoices = $this->invoice->customer->invoices()->sum('amount');
        $invoiceLink = route('customer_invoice.show', $this->invoice->id);

        $totalPayments = $this->invoice->customer->payments()
            ->selectRaw("
                SUM(
                    CASE 
                        WHEN payment_mode = 'momo' AND transaction_status = 'Success' 
                        THEN amount_paid + IFNULL(withholding_tax_amount, 0)
                        WHEN payment_mode != 'momo' 
                        THEN amount_paid + IFNULL(withholding_tax_amount, 0)
                        ELSE 0
                    END
                ) as total
            ")
            ->value('total');

        $balance = $totalInvoices - $totalPayments;

        $msg = "Hello " . $this->invoice->customer->name . ", ";
        $msg .= "You have been invoiced an amount of GHC " . $this->invoice->amount . " for " . $this->invoice->gasRequest->kg . "kg gas supplied ";
        $msg .= "to your " . ($this->invoice->gasRequest->deliveryBranch->name ?? 'N/A') . " branch at a rate of GHC " . $rate->amount . " per KG on the ";
        $msg .= $dateFormatted . ". Your indebtedness stands at GHC " . $balance . ". ";
        $msg .= "View invoice: " . $invoiceLink . ". ";
        $msg .= "Kindly contact us on " . env('COMPANY_CONTACT') . " for any complaints.";

        $phone = $this->invoice->customer->contact;

        SendSMS::sendSMS($phone, $msg);
    }
}
