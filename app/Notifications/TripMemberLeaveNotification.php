<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use App\Channels\SmsChannel;
use App\Channels\WhatsAppChannel;
use App\Channels\Messages\SmsMessage;
use App\Channels\Messages\WhatsAppMessage;
use App\Models\Trip;
use App\Models\TripJoin;
use App\Services\SmsTemplateService;

define('LEAVE_SID', 'HXcabbd56f3b677a67aaaf9a9067730741');

class TripMemberLeaveNotification extends Notification implements ShouldQueue
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

        // Map channel names to actual channel classes
        return match ($notifiable->notification_channel) {
            'sms' => [SmsChannel::class],
            'whatsapp' => [WhatsAppChannel::class], // Keep as string if you have WhatsApp channel
            default => [SmsChannel::class],
        };
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
    public function toWhatsApp(object $notifiable): WhatsAppMessage
    {
        $leftUserDisplayName = $this->leftUserName ?? $this->leftUserPhone;

        $allTripJoinsCount = TripJoin::where('trip_id', $this->trip->id)->whereNot('has_left', 1)->count();

        return (new WhatsAppMessage())
            ->content(
                LEAVE_SID,
                [
                    '1' => (string)$this->trip->dropoff_location,
                    '2' => (string)$this->trip->planned_departure_time,
                    '3' => (string)$leftUserDisplayName,
                    '4' => (string)$allTripJoinsCount,
                    '5' => (string)$this->trip->max_people,
                    '6' => (string)$this->trip->price_per_person
                ],
            );
    }
}
