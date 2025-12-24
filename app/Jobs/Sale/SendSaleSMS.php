<?php

namespace App\Jobs\Sale;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Actions\SMS\SendSMS;


class SendSaleSMS implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    protected $sale;

    public function __construct($sale)
    {
        $this->sale = $sale;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $msg = "Hello " . $this->sale->name . ", ";
        $msg .= "You purchased Ghc " . $this->sale->amount . " worth of Gas. Thank you for doing business with mighty gas ";
        $msg .= "Contact us on " . env('COMPANY_CONTACT') . " for any complaints.";

        $phone = $this->sale->contact;

        SendSMS::sendSMS($phone, $msg);
    }
}
