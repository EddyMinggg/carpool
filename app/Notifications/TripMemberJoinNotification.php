<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use App\Channels\Messages\SmsMessage;
use App\Channels\Messages\WhatsAppMessage;
use App\Models\Trip;
use App\Services\SmsTemplateService;

class TripMemberJoinNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(readonly private Trip $trip)
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return [$notifiable->notification_channel];
    }

    public function toSms(object $notifiable): SmsMessage
    {
        if ($this->trip->type == 'golden') {
            return (new SmsMessage())
                ->content(SmsTemplateService::goldenTimeJoinMessage($this->trip));
        } else {
            return (new SmsMessage())
                ->content(SmsTemplateService::regularTimeJoinMessage($this->trip));
        }
    }
    // public function toWhatsApp(object $notifiable): WhatsAppMessage
    // {
    //     if ($this->trip->type == 'golden') {
    //         return (new WhatsAppMessage())
    //             ->content(SmsTemplateService::goldenTimeJoinMessage($this->trip));
    //     } else {
    //         return (new WhatsAppMessage())
    //             ->content(SmsTemplateService::regularTimeJoinMessage($this->trip));
    //     }
    // }
}
