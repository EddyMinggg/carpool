<?php

namespace App\Channels;

use Illuminate\Notifications\Notification;
use Twilio\Rest\Client;

class SmsChannel
{
    public function send($notifiable, Notification $notification)
    {
        $message = $notification->toSms($notifiable);


        $to = $notifiable->routeNotificationFor('Sms');
        $from = config('sms.twilio.phone_number');


        $twilio = new Client(config('sms.twilio.sid'), config('sms.twilio.token'));


        return $twilio->messages->create($to, [
            "from" => $from,
            "body" => $message->content
        ]);
    }
}
