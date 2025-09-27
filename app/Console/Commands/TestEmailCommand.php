<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Models\User;

class TestEmailCommand extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'email:test {--to=} {--type=verification}';

    /**
     * The console command description.
     */
    protected $description = 'Test email sending functionality';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $to = $this->option('to') ?: $this->ask('Enter email address to send test to:');
        $type = $this->option('type');

        if (!$to) {
            $this->error('Email address is required');
            return 1;
        }

        $this->info('📧 Testing email configuration...');
        $this->line('');

        // Show current mail configuration
        $this->displayMailConfig();

        if (!$this->confirm('Send test email with above configuration?')) {
            $this->info('Test cancelled.');
            return 0;
        }

        $this->info("📤 Sending test email to: {$to}");

        try {
            switch ($type) {
                case 'verification':
                    $this->sendVerificationTest($to);
                    break;
                case 'simple':
                    $this->sendSimpleTest($to);
                    break;
                case 'welcome':
                    $this->sendWelcomeTest($to);
                    break;
                default:
                    $this->sendSimpleTest($to);
            }

            $this->info('✅ Test email sent successfully!');
            $this->line('');
            $this->info('📋 Next steps:');
            $this->line('1. Check your email inbox (and spam folder)');
            $this->line('2. If using Mailtrap, check your Mailtrap inbox');
            $this->line('3. If email not received, check SMTP credentials');
            
        } catch (\Exception $e) {
            $this->error('❌ Failed to send test email');
            $this->error("Error: {$e->getMessage()}");
            $this->line('');
            $this->warn('🔧 Troubleshooting tips:');
            $this->line('1. Verify SMTP credentials in .env file');
            $this->line('2. Check firewall/network settings');
            $this->line('3. Ensure mail server allows connections');
            $this->line('4. Try php artisan config:clear');
            return 1;
        }

        return 0;
    }

    protected function displayMailConfig()
    {
        $this->info('📋 Current Mail Configuration:');
        $this->line('');
        
        $config = [
            'Mailer' => config('mail.default'),
            'Host' => config('mail.mailers.smtp.host'),
            'Port' => config('mail.mailers.smtp.port'),
            'Username' => config('mail.mailers.smtp.username'),
            'Encryption' => config('mail.mailers.smtp.transport') === 'smtp' ? 'TLS/SSL' : 'None',
            'From Address' => config('mail.from.address'),
            'From Name' => config('mail.from.name'),
        ];

        foreach ($config as $key => $value) {
            $displayValue = $key === 'Username' && $value ? 
                substr($value, 0, 3) . '***' . substr($value, -4) : 
                ($value ?: 'Not set');
            $this->line("  <fg=cyan>{$key}:</fg=cyan> {$displayValue}");
        }
        $this->line('');
    }

    protected function sendSimpleTest($to)
    {
        Mail::raw(
            "🎉 Email test successful!\n\n" .
            "This is a test email from your Carpool Platform.\n\n" .
            "Configuration details:\n" .
            "- Sent at: " . now()->format('Y-m-d H:i:s') . "\n" .
            "- Mail driver: " . config('mail.default') . "\n" .
            "- From: " . config('mail.from.address') . "\n\n" .
            "If you received this email, your SMTP configuration is working correctly! 🚀",
            function ($message) use ($to) {
                $message->to($to)
                        ->subject('✅ Carpool Platform - Email Test Successful');
            }
        );
    }

    protected function sendVerificationTest($to)
    {
        // Create a temporary user for testing
        $testUser = new User([
            'name' => 'Test User',
            'email' => $to,
            'username' => 'testuser',
        ]);

        // Send verification email
        $testUser->sendEmailVerificationNotification();
    }

    protected function sendWelcomeTest($to)
    {
        $welcomeContent = view('emails.welcome-test', [
            'userName' => 'Test User',
            'appName' => config('app.name', 'Carpool Platform'),
            'appUrl' => config('app.url'),
        ])->render();

        Mail::html($welcomeContent, function ($message) use ($to) {
            $message->to($to)
                    ->subject('🎉 Welcome to Carpool Platform!');
        });
    }
}