<?php

namespace App\Jobs\OTP;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Actions\SMS\SendSMS;
use App\Models\OTP;


class SendPasswordChangeOTPSMS implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    protected $user;

    public function __construct($user)
    {
        $this->user = $user;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $otp = mt_rand(100000, 999999);

        $data = [
            'user_id' => $this->user->id,
            'code' => $otp
        ];

        OTP::where('user_id', $data['user_id'])->delete();

        $otpData = OTP::create($data);

        $msg = "Dear " . $this->user->name . ", ";
        $msg .= "Your password reset/change OTP code is " . $otpData->code . ".";

        $phone = $this->user->phone;

        SendSMS::sendSMS($phone, $msg);
    }
}
