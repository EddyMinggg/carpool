<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\SmsTemplateService;
use App\Services\NotificationService;
use App\Services\OtpService;

class SmsTemplateCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sms:template {action} {--template=} {--phone=} {--test} {--language=en}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Manage SMS templates and send test messages';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $action = $this->argument('action');

        switch ($action) {
            case 'list':
                $this->listTemplates();
                break;
            case 'preview':
                $this->previewTemplate();
                break;
            case 'test':
                $this->testTemplate();
                break;
            case 'validate':
                $this->validateAllTemplates();
                break;
            case 'stats':
                $this->showStats();
                break;
            case 'connection':
                $this->testConnection();
                break;
            default:
                $this->error("Unknown action: {$action}");
                $this->showHelp();
        }
    }

    protected function listTemplates()
    {
        $this->info('📱 Available SMS Templates:');
        $this->line('');

        $templates = [
            'otp' => 'OTP Verification Code',
            'welcome' => 'Welcome Message for New Users',
            'trip_confirmation' => 'Trip Booking Confirmation',
            'trip_reminder' => 'Trip Pickup Reminder (1 hour before)',
            'driver_assigned' => 'Driver Assignment Notification',
            'driver_enroute' => 'Driver En Route Notification',
            'driver_arrived' => 'Driver Arrival Notification',
            'payment_confirmation' => 'Payment Successful Confirmation',
            'trip_cancellation' => 'Trip Cancellation Notice',
            'trip_completed' => 'Trip Completion Notification',
            'emergency_alert' => 'Emergency Alert for Safety',
            'password_reset' => 'Password Reset Code',
            'ride_share_invitation' => 'Ride Share Invitation'
        ];

        foreach ($templates as $key => $description) {
            $this->line("• <fg=green>{$key}</fg=green>: {$description}");
        }

        $this->line('');
        $this->info('Usage: php artisan sms:template preview --template=otp');
    }

    protected function previewTemplate()
    {
        $template = $this->option('template');
        $language = $this->option('language');

        if (!$template) {
            $template = $this->choice('Select template to preview:', [
                'otp', 'welcome', 'trip_confirmation', 'driver_assigned', 
                'payment_confirmation', 'emergency_alert'
            ]);
        }

        $this->info("📱 Template Preview: {$template} ({$language})");
        $this->line('');

        $message = match($template) {
            'otp' => SmsTemplateService::otpVerification('123456', 5, $language),
            'welcome' => SmsTemplateService::welcome('John Doe', $language),
            'trip_confirmation' => SmsTemplateService::tripConfirmation('T12345', 'Central Station', '2025-09-27 15:30'),
            'driver_assigned' => SmsTemplateService::driverAssigned('T12345', 'Wong Tai Man', 'Toyota Camry', 'ABC123', '+852 9876 5432'),
            'payment_confirmation' => SmsTemplateService::paymentConfirmation('T12345', 150.00),
            'emergency_alert' => SmsTemplateService::emergencyAlert('T12345'),
            'trip_reminder' => SmsTemplateService::tripReminder('T12345', 'Airport', '15:00', 'Central MTR Exit A'),
            'driver_enroute' => SmsTemplateService::driverEnRoute('Wong Tai Man', 10, 'T12345'),
            'driver_arrived' => SmsTemplateService::driverArrived('Wong Tai Man', 'Toyota Camry', 'ABC123'),
            'trip_completed' => SmsTemplateService::tripCompleted('T12345', '5'),
            'trip_cancellation' => SmsTemplateService::tripCancellation('T12345', 'Weather conditions'),
            'password_reset' => SmsTemplateService::passwordReset('654321'),
            default => 'Template not found'
        };

        if ($message === 'Template not found') {
            $this->error('Template not found!');
            return;
        }

        $validation = SmsTemplateService::validateSmsLength($message);

        $this->line("Content: {$message}");
        $this->line('');
        $this->info("📊 Statistics:");
        $this->line("Length: {$validation['length']} characters");
        $this->line("SMS Parts: {$validation['parts']}");
        $this->line("Single SMS: " . ($validation['is_single'] ? 'Yes ✅' : 'No ⚠️'));
        
        if ($validation['warning']) {
            $this->warn($validation['warning']);
        }
    }

    protected function testTemplate()
    {
        $phone = $this->option('phone');
        $template = $this->option('template');
        $test = $this->option('test');

        if (!$phone) {
            $phone = $this->ask('Enter phone number to send test SMS (format: +852XXXXXXXX):');
        }

        if (!$template) {
            $template = $this->choice('Select template to test:', [
                'otp', 'welcome', 'trip_confirmation'
            ]);
        }

        if (!$phone) {
            $this->error('Phone number is required for testing.');
            return;
        }

        $this->info("🧪 Testing SMS Template: {$template}");
        $this->info("📱 Sending to: {$phone}");

        if (!$test && !$this->confirm('Are you sure you want to send a real SMS? This will cost money.')) {
            $this->info('Test cancelled.');
            return;
        }

        $notificationService = app(NotificationService::class);

        try {
            $message = match($template) {
                'otp' => SmsTemplateService::otpVerification('123456', 5),
                'welcome' => SmsTemplateService::welcome('Test User'),
                'trip_confirmation' => SmsTemplateService::tripConfirmation('TEST123', 'Test Destination', now()->addHours(2)->format('Y-m-d H:i')),
                default => 'Test message from Carpool SMS system'
            };

            if ($test) {
                $this->info("TEST MODE - Message would be: {$message}");
                $this->info('✅ Test completed (no SMS sent)');
                return;
            }

            $result = $notificationService->sendSms($phone, $message, [
                'type' => 'test_message',
                'template' => $template
            ]);

            if ($result['success']) {
                $this->info('✅ SMS sent successfully!');
                $this->line("Message ID: {$result['message_id']}");
                $this->line("SMS Parts: {$result['sms_parts']}");
            } else {
                $this->error('❌ Failed to send SMS');
                $this->error("Error: {$result['error']}");
            }

        } catch (\Exception $e) {
            $this->error('❌ Error occurred while sending SMS');
            $this->error("Details: {$e->getMessage()}");
        }
    }

    protected function validateAllTemplates()
    {
        $this->info('🔍 Validating all SMS templates...');
        $this->line('');

        $templates = [
            'OTP' => SmsTemplateService::otpVerification('123456', 5),
            'Welcome' => SmsTemplateService::welcome('Test User'),
            'Trip Confirmation' => SmsTemplateService::tripConfirmation('T123', 'Destination', 'Time'),
            'Driver Assignment' => SmsTemplateService::driverAssigned('T123', 'Driver', 'Car', 'Plate', 'Phone'),
            'Payment' => SmsTemplateService::paymentConfirmation('T123', 150.00),
            'Emergency' => SmsTemplateService::emergencyAlert('T123'),
        ];

        $issues = [];
        $totalParts = 0;

        foreach ($templates as $name => $message) {
            $validation = SmsTemplateService::validateSmsLength($message);
            $totalParts += $validation['parts'];
            
            $status = $validation['is_single'] ? '✅' : '⚠️';
            $this->line("{$status} {$name}: {$validation['length']} chars ({$validation['parts']} parts)");
            
            if (!$validation['is_single']) {
                $issues[] = $name;
            }
        }

        $this->line('');
        $this->info("📊 Summary:");
        $this->line("Total templates: " . count($templates));
        $this->line("Single SMS: " . (count($templates) - count($issues)));
        $this->line("Multi-part SMS: " . count($issues));
        $this->line("Total SMS parts if all sent: {$totalParts}");

        if (!empty($issues)) {
            $this->line('');
            $this->warn("⚠️  Templates that exceed 160 characters:");
            foreach ($issues as $issue) {
                $this->line("   • {$issue}");
            }
        } else {
            $this->info('✅ All templates are single SMS messages!');
        }
    }

    protected function showStats()
    {
        $notificationService = app(NotificationService::class);
        $stats = $notificationService->getSmsStats(30);

        $this->info('📊 SMS Statistics (Last 30 Days):');
        $this->line('');
        $this->line("Total SMS Sent: {$stats['total_sent']}");
        $this->line("Successful: {$stats['successful']}");
        $this->line("Failed: {$stats['failed']}");
        $this->line("Success Rate: {$stats['success_rate']}%");
    }

    protected function testConnection()
    {
        $this->info('🔗 Testing SMS service connection...');
        
        $notificationService = app(NotificationService::class);
        $result = $notificationService->testConnection();

        if ($result['success']) {
            $this->info('✅ SMS service connection successful');
            $this->line("Service: {$result['service']}");
        } else {
            $this->error('❌ SMS service connection failed');
            $this->error("Error: {$result['error']}");
        }
    }

    protected function showHelp()
    {
        $this->line('');
        $this->info('📱 SMS Template Management Commands:');
        $this->line('');
        $this->line('Available actions:');
        $this->line('  list       - List all available templates');
        $this->line('  preview    - Preview template content');
        $this->line('  test       - Send test SMS (requires --phone)');
        $this->line('  validate   - Validate all template lengths');
        $this->line('  stats      - Show SMS usage statistics');
        $this->line('  connection - Test SMS service connectivity');
        $this->line('');
        $this->line('Options:');
        $this->line('  --template=  Template name for preview/test');
        $this->line('  --phone=     Phone number for test SMS');
        $this->line('  --test       Test mode (no actual SMS sent)');
        $this->line('  --language=  Language for multilingual templates');
        $this->line('');
        $this->line('Examples:');
        $this->line('  php artisan sms:template list');
        $this->line('  php artisan sms:template preview --template=otp');
        $this->line('  php artisan sms:template test --template=welcome --phone=+85212345678 --test');
        $this->line('  php artisan sms:template validate');
    }
}