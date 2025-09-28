<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\VerifyEmail as BaseVerifyEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Notifications\Messages\MailMessage;

class FastVerifyEmail extends BaseVerifyEmail implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct()
    {
        // 設置為高優先級隊列
        $this->onQueue('high');
        
        // 如果配置了同步發送，則不使用隊列
        if (config('fast-mail.force_sync_for_verification', true)) {
            // 移除 ShouldQueue 的行為，強制同步發送
            $this->connection = 'sync';
        }
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable): MailMessage
    {
        $verificationUrl = $this->verificationUrl($notifiable);

        return (new MailMessage)
            ->subject('快速驗證您的郵箱地址')
            ->greeting('您好！')
            ->line('請點擊下面的按鈕來驗證您的郵箱地址。')
            ->action('驗證郵箱', $verificationUrl)
            ->line('如果您沒有創建帳戶，則無需採取進一步的操作。')
            ->line('此驗證鏈接將在 60 分鐘後過期。')
            ->salutation('謝謝，' . config('app.name') . ' 團隊');
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray($notifiable): array
    {
        return [
            'verification_url' => $this->verificationUrl($notifiable),
            'sent_at' => now()->toISOString(),
        ];
    }

    /**
     * Determine which queues should be used for each notification channel.
     */
    public function viaQueues(): array
    {
        return [
            'mail' => 'high', // 使用高優先級隊列
            'database' => 'default',
        ];
    }
}
