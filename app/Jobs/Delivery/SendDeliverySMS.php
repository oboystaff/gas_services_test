<?php

namespace App\Jobs\Delivery;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Actions\SMS\SendSMS;
use App\Models\Rate;

class SendDeliverySMS implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    protected $gasRequest;

    public function __construct($gasRequest)
    {
        $this->gasRequest = $gasRequest;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $rate = Rate::latest()->first();
        $dateFormatted = \Carbon\Carbon::parse($this->gasRequest->created_at)->format('jS, F Y');

        $msg = "Hello " . $this->gasRequest->name . ", ";
        $msg .= "Manbah Gas has delivered " . $this->gasRequest->kg . "kg of Gas to your " . $this->gasRequest->deliveryBranch->name . " branch ";
        $msg .= "at a rate of GHC " . $rate->amount . " per KG on the " . $dateFormatted . ", supervised by " . $this->gasRequest->rep_name . ". ";
        $msg .= "Contact us on " . env('COMPANY_CONTACT') . " for any complaints.";

        $phone = $this->gasRequest->contact;

        SendSMS::sendSMS($phone, $msg);
    }
}
