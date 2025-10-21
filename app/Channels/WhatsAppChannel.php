<?php

namespace App\Channels;

use Exception;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;
use Twilio\Rest\Client;

class WhatsAppChannel
{
    public function send($notifiable, Notification $notification)
    {
        $message = $notification->toWhatsApp($notifiable);


        $to = $notifiable->routeNotificationFor('WhatsApp');
        $from = config('sms.twilio.whatsapp_number');


        $twilio = new Client(config('sms.twilio.sid'), config('sms.twilio.token'));


        $res = $twilio->messages->create(
            'whatsapp:' . $to,
            [
                "contentSid" => $message->contentSid,
                "contentVariables" => json_encode($message->contentVariables),
                "from" => $from,
                "messagingServiceSid" => 'MG13148a16d023d276031825468a753a60'
            ]
        );

        return $res;
    }
}
