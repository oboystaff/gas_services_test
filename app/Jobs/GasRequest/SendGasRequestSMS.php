<?php

namespace App\Jobs\GasRequest;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Actions\SMS\SendSMS;
use App\Models\Notification;


class SendGasRequestSMS implements ShouldQueue
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
        $msg = "Hello " . $this->gasRequest->name . ", ";
        $msg .= "Your request for gas delivery services has been received. Our agents will contact you shortly. ";
        $msg .= "Contact us on " . env('COMPANY_CONTACT') . " for any complaints.";

        $phone = $this->gasRequest->contact;

        SendSMS::sendSMS($phone, $msg);

        $recipients = Notification::where('status', 'Active')->get();

        foreach ($recipients as $recipient) {
            $msg  = "Hello {$recipient->name}, ";
            $msg .= "there is a Gas request from {$this->gasRequest->customer->name}. ";
            $msg .= "Please attend to it.";

            $phone = $recipient->phone;

            if (!empty($phone)) {
                SendSMS::sendSMS($phone, $msg);
            }
        }
    }
}
