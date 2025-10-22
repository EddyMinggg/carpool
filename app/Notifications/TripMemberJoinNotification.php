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
use App\Models\User;
use App\Services\SmsTemplateService;

define('JOIN_SID_1', 'HXa689cd39ecf18afd93a3c927250a1ce4');
define('JOIN_SID_2', 'HX73addfa73d78dcb205f052cf2dfdf8f7');
define('JOIN_SID_3', 'HX5d2e698585bac2a95df5af2090de6521');
define('JOIN_SID_4', 'HXf09d7f7676f3346144c957137c8eae6c');

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
    public function toWhatsApp(object $notifiable): WhatsAppMessage
    {
        $allTripJoins = TripJoin::where('trip_id', $this->trip->id)->whereNot('has_left', 1);
        $allTripJoinsCount = $allTripJoins->count();
        $latestTimestamp = $allTripJoins->max('created_at');
        $latestRecords = $allTripJoins->where('created_at', $latestTimestamp)->get();
        $latestRecordsCount = $latestRecords->count();

        $latestUser = null;
        if ($latestRecordsCount < 2 && $latestRecords->isNotEmpty()) {
            $latestUser = User::where('phone', $latestRecords->first()->user_phone)->first();
        }

        if ($this->trip->type == 'golden') {
            return (new WhatsAppMessage())
                ->content(
                    JOIN_SID_1,
                    [
                        '1' => (string)$this->trip->dropoff_location,
                        '2' => (string)$this->trip->planned_departure_time,
                        '3' => (string)$latestRecordsCount,
                        '4' => (string)$allTripJoinsCount,
                        '5' => (string)$this->trip->max_people,
                        '6' => (string)$this->trip->price_per_person
                    ],
                );
        } else {
            $sid = match ($allTripJoinsCount) {
                2 => JOIN_SID_2,
                3 => JOIN_SID_3,
                4 => JOIN_SID_4,
                default => JOIN_SID_2
            };
            $discountedPrice = $this->trip->price_per_person - $this->trip->four_person_discount;
            return (new WhatsAppMessage())
                ->content(
                    $sid,
                    [
                        '1' => (string)$this->trip->dropoff_location,
                        '2' => (string)$this->trip->planned_departure_time,
                        '3' => (string)$latestRecordsCount,
                        '4' => (string)$allTripJoinsCount,
                        '5' => (string)$this->trip->max_people,
                        '6' => $allTripJoinsCount == 4 ? $discountedPrice : (string)$this->trip->price_per_person,
                        '7' => (string)$this->trip->four_person_discount
                    ],
                );
        }
    }
}
