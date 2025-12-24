<?php

namespace App\Jobs\Customer;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Actions\SMS\SendSMS;

class SendCustomerSMS implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    protected $customer;

    public function __construct($customer)
    {
        $this->customer = $customer;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $msg = "Hello " . $this->customer->name . ", ";
        $msg .= "You have been registered as a loyal customer of Manbah Gas. Your customer ID is " . $this->customer->customer_id . ". ";
        $msg .= "Please use this number any time you are requesting for Gas. The USSD for gas request is *268*99#. ";
        $msg .= "Kindly contact us on " . env('COMPANY_CONTACT') . " for any complaints.";

        $phone = $this->customer->contact;

        SendSMS::sendSMS($phone, $msg);
    }
}
