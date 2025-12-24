<?php

namespace App\Actions\SMS;

class SendSMS
{
    public static function sendSMS($phone, $msg)
    {
        try {
            $apiKey = "ZkGgjHIDdjeuoLK2Nixh7RKqo";
            $senderID = "MANBAH GAS";

            $url = "https://apps.mnotify.net/smsapi?" . http_build_query([
                'key' => $apiKey,
                'to' => $phone,
                'msg' => $msg,
                'sender_id' => $senderID
            ]);

            return self::fireSMS($url);
        } catch (\Exception $ex) {
            \Log::error("SMS Error: " . $ex->getMessage());

            return response()->json([
                'message' => 'Unable to deliver SMS: ' . $ex->getMessage(),
            ], 422);
        }
    }

    public static function fireSMS($url)
    {
        $result = file_get_contents($url);
        \Log::info("SMS Response: " . json_encode($result));
        return json_decode($result, true);
    }
}
