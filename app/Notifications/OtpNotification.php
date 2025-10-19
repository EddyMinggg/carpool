<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Channels\Messages\SmsMessage;
use App\Channels\Messages\WhatsAppMessage;

use App\Services\SmsTemplateService;

class OtpNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(readonly private string $otp, readonly private string $lang)
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
        return (new SmsMessage())
            ->content(SmsTemplateService::otpVerification($this->otp, language: $this->lang));
    }

    public function toWhatsApp(object $notifiable): WhatsAppMessage
    {
        return (new WhatsAppMessage())
            ->content(
                OTP_SID,
                [
                    '1' => $this->otp,
                ],
            );
    }
}
