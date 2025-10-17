<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use App\Channels\SmsChannel;
use App\Channels\Messages\SmsMessage;
use App\Models\Trip;
use App\Services\SmsTemplateService;

class TripMemberJoinNotification extends Notification
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
        return [SmsChannel::class];
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
}
