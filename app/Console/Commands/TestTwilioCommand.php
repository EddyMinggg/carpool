<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\TwilioService;

class TestTwilioCommand extends Command
{
    protected $signature = 'twilio:test {phone?} {--sms} {--whatsapp} {--config}';
    protected $description = 'Test Twilio SMS and WhatsApp functionality';

    public function handle()
    {
        $twilioService = new TwilioService();
        
        if ($this->option('config')) {
            $this->testConfiguration($twilioService);
            return;
        }

        $phone = $this->argument('phone');
        if (!$phone) {
            $phone = $this->ask('Enter phone number (with country code, e.g., +85257220308)');
        }

        if (!$phone) {
            $this->error('Phone number is required');
            return 1;
        }

        if ($this->option('sms')) {
            $this->testSms($twilioService, $phone);
        } elseif ($this->option('whatsapp')) {
            $this->testWhatsApp($twilioService, $phone);
        } else {
            $this->testOtp($twilioService, $phone);
        }
    }

    private function testConfiguration(TwilioService $twilioService)
    {
        $this->info('🔧 Testing Twilio Configuration...');
        
        $result = $twilioService->testConfiguration();
        
        if ($result['success']) {
            $this->info('✅ Twilio configuration is valid');
            $this->line("Account SID: {$result['account_sid']}");
            $this->line("Friendly Name: {$result['friendly_name']}");
            $this->line("Status: {$result['status']}");
        } else {
            $this->error('❌ Twilio configuration failed');
            $this->error("Error: {$result['error']}");
        }
    }

    private function testSms(TwilioService $twilioService, string $phone)
    {
        $this->info("📱 Testing SMS to {$phone}...");
        
        $testCode = str_pad(random_int(100000, 999999), 6, '0', STR_PAD_LEFT);
        $result = $twilioService->sendOtpSms($phone, $testCode);
        
        if ($result['success']) {
            $this->info('✅ SMS sent successfully');
            $this->line("Message ID: {$result['message_id']}");
            $this->line("Status: {$result['status']}");
            $this->line("Test OTP Code: {$testCode}");
        } else {
            $this->error('❌ SMS failed');
            $this->error("Error: {$result['error']}");
        }
    }

    private function testWhatsApp(TwilioService $twilioService, string $phone)
    {
        $this->info("💬 Testing WhatsApp to {$phone}...");
        
        $testCode = str_pad(random_int(100000, 999999), 6, '0', STR_PAD_LEFT);
        $result = $twilioService->sendOtpWhatsApp($phone, $testCode);
        
        if ($result['success']) {
            $this->info('✅ WhatsApp message sent successfully');
            $this->line("Message ID: {$result['message_id']}");
            $this->line("Status: {$result['status']}");
            $this->line("Test OTP Code: {$testCode}");
        } else {
            $this->error('❌ WhatsApp message failed');
            $this->error("Error: {$result['error']}");
        }
    }

    private function testOtp(TwilioService $twilioService, string $phone)
    {
        $this->info("🔐 Testing OTP delivery to {$phone}...");
        $this->line('Will try WhatsApp first, then fallback to SMS if needed');
        
        $testCode = str_pad(random_int(100000, 999999), 6, '0', STR_PAD_LEFT);
        
        // Try WhatsApp first
        $this->line('📱 Trying WhatsApp...');
        $whatsappResult = $twilioService->sendOtpWhatsApp($phone, $testCode);
        
        if ($whatsappResult['success']) {
            $this->info('✅ WhatsApp OTP sent successfully');
            $this->line("Message ID: {$whatsappResult['message_id']}");
            $this->line("Test OTP Code: {$testCode}");
            return;
        }
        
        $this->warn('⚠️  WhatsApp failed, trying SMS...');
        $this->line("WhatsApp Error: {$whatsappResult['error']}");
        
        // Fallback to SMS
        $smsResult = $twilioService->sendOtpSms($phone, $testCode);
        
        if ($smsResult['success']) {
            $this->info('✅ SMS OTP sent successfully');
            $this->line("Message ID: {$smsResult['message_id']}");
            $this->line("Test OTP Code: {$testCode}");
        } else {
            $this->error('❌ Both WhatsApp and SMS failed');
            $this->error("SMS Error: {$smsResult['error']}");
        }
    }
}