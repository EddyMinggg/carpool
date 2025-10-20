<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use App\Channels\SmsChannel;
use App\Channels\Messages\SmsMessage;
use App\Channels\Messages\WhatsAppMessage;
use App\Models\Trip;
use App\Services\SmsTemplateService;

class TripMemberLeaveNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        readonly private Trip $trip,
        readonly private string $leftUserPhone,
        readonly private ?string $leftUserName = null
    ) {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        // Check if notifiable has notification_channel property (User model)
        // Otherwise use SmsChannel as default for anonymous notifiable
        if (property_exists($notifiable, 'notification_channel') && $notifiable->notification_channel) {
            // Map channel names to actual channel classes
            return match($notifiable->notification_channel) {
                'sms' => [SmsChannel::class],
                'whatsapp' => ['whatsapp'], // Keep as string if you have WhatsApp channel
                default => [SmsChannel::class],
            };
        }
        
        return [SmsChannel::class];
    }

    public function toSms(object $notifiable): SmsMessage
    {
        if ($this->trip->type == 'golden') {
            return (new SmsMessage())
                ->content(SmsTemplateService::goldenTimeLeaveMessage($this->trip, $this->leftUserPhone, $this->leftUserName));
        } else {
            return (new SmsMessage())
                ->content(SmsTemplateService::regularTimeLeaveMessage($this->trip, $this->leftUserPhone, $this->leftUserName));
        }
    }
    // public function toWhatsApp(object $notifiable): WhatsAppMessage
    // {
    //     if ($this->trip->type == 'golden') {
    //         return (new WhatsAppMessage())
    //             ->content(SmsTemplateService::goldenTimeLeaveMessage($this->trip));
    //     } else {
    //         return (new WhatsAppMessage())
    //             ->content(SmsTemplateService::regularTimeLeaveMessage($this->trip));
    //     }
    // }
}
