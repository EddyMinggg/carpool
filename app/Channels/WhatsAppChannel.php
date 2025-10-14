<?php

namespace App\Channels;

use Illuminate\Notifications\Notification;
use Twilio\Rest\Client;

class WhatsAppChannel
{
    public function send($notifiable, Notification $notification)
    {
        $message = $notification->toWhatsApp($notifiable);


        $to = $notifiable->routeNotificationFor('WhatsApp');
        $from = config('sms.twilio.whatsapp_number');


        $twilio = new Client(config('sms.twilio.sid'), config('sms.twilio.token'));


        return $twilio->messages->create('whatsapp:' . $to, [
            "from" => $from,
            "body" => $message->content
        ]);
    }
}
