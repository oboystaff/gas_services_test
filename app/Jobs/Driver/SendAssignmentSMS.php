<?php

namespace App\Jobs\Driver;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Actions\SMS\SendSMS;


class SendAssignmentSMS implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    protected $driver;
    protected $gasRequest;


    public function __construct($driver, $gasRequest)
    {
        $this->driver = $driver;
        $this->gasRequest = $gasRequest;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $msg = "Hello " . $this->driver->name . ", ";
        $msg .= "You have been assigned a delivery. Thank you.";

        $phone = $this->driver->phone;

        SendSMS::sendSMS($phone, $msg);

        if ($this->gasRequest->customer) {
            $customerName   = $this->gasRequest->customer->name;
            $customerPhone  = $this->gasRequest->customer->contact;
            $deliveryBranch = $this->gasRequest->deliveryBranch->name ?? 'N/A';

            $customerMsg = "Hello {$customerName}, ";
            $customerMsg .= "A driver has been assigned to deliver Gas to your ";
            $customerMsg .= "{$deliveryBranch} branch within 24 hours. Thank you.";

            SendSMS::sendSMS($customerPhone, $customerMsg);
        }
    }
}
