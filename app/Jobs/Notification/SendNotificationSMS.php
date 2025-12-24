<?php

namespace App\Jobs\Notification;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Actions\SMS\SendSMS;
use App\Models\Notification;


class SendNotificationSMS implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    protected $notification;

    public function __construct($notification)
    {
        $this->notification = $notification;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $activeMembers = Notification::where('status', 'Active')->get();

        foreach ($activeMembers as $member) {
            $msg = "Hello " . $member->name . ",\n";
            $msg .= "There is a request from " . $this->customer->customer_name . ", kindly attend to it.";

            $phone = $member->phone;

            SendSMS::sendSMS($phone, $msg);
        }
    }
}
