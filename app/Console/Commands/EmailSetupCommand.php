<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class EmailSetupCommand extends Command
{
    protected $signature = 'email:setup {provider?}';
    protected $description = 'Interactive email SMTP configuration setup';

    public function handle()
    {
        $this->info('📧 Email SMTP Configuration Setup');
        $this->line('');

        $provider = $this->argument('provider') ?: $this->choice(
            'Choose email provider:',
            ['mailtrap', 'gmail', 'outlook', 'sendgrid', 'custom'],
            0
        );

        switch ($provider) {
            case 'mailtrap':
                $this->setupMailtrap();
                break;
            case 'gmail':
                $this->setupGmail();
                break;
            case 'outlook':
                $this->setupOutlook();
                break;
            case 'sendgrid':
                $this->setupSendgrid();
                break;
            case 'custom':
                $this->setupCustom();
                break;
        }
    }

    protected function setupMailtrap()
    {
        $this->info('🔧 Setting up Mailtrap (Development/Testing)');
        $this->line('');
        
        $this->warn('First, you need to:');
        $this->line('1. Go to https://mailtrap.io/');
        $this->line('2. Sign up for free account');
        $this->line('3. Create an inbox');
        $this->line('4. Get SMTP credentials');
        $this->line('');

        if (!$this->confirm('Have you completed the above steps?')) {
            $this->info('Please complete the setup at https://mailtrap.io/ first');
            return;
        }

        $username = $this->ask('Enter Mailtrap Username:');
        $password = $this->secret('Enter Mailtrap Password:');

        if ($username && $password) {
            $this->updateEnvFile([
                'MAIL_MAILER' => 'smtp',
                'MAIL_HOST' => 'sandbox.smtp.mailtrap.io',
                'MAIL_PORT' => '2525',
                'MAIL_USERNAME' => $username,
                'MAIL_PASSWORD' => $password,
                'MAIL_ENCRYPTION' => 'null',
            ]);

            $this->info('✅ Mailtrap configuration saved!');
            $this->testConfiguration();
        }
    }

    protected function setupGmail()
    {
        $this->info('🔧 Setting up Gmail SMTP');
        $this->line('');
        
        $this->warn('Important: You need an App Password, not your regular Gmail password!');
        $this->line('');
        $this->line('Steps to get App Password:');
        $this->line('1. Enable 2-Factor Authentication on your Gmail');
        $this->line('2. Go to Google Account > Security > App passwords');
        $this->line('3. Generate password for "Mail"');
        $this->line('4. Use that 16-character password below');
        $this->line('');

        $email = $this->ask('Enter your Gmail address:');
        $password = $this->secret('Enter App Password (16 characters):');

        if ($email && $password) {
            $this->updateEnvFile([
                'MAIL_MAILER' => 'smtp',
                'MAIL_HOST' => 'smtp.gmail.com',
                'MAIL_PORT' => '587',
                'MAIL_USERNAME' => $email,
                'MAIL_PASSWORD' => $password,
                'MAIL_ENCRYPTION' => 'tls',
                'MAIL_FROM_ADDRESS' => $email,
            ]);

            $this->info('✅ Gmail configuration saved!');
            $this->testConfiguration();
        }
    }

    protected function setupOutlook()
    {
        $this->info('🔧 Setting up Outlook/Hotmail SMTP');
        $this->line('');

        $email = $this->ask('Enter your Outlook/Hotmail address:');
        $password = $this->secret('Enter your password:');

        if ($email && $password) {
            $this->updateEnvFile([
                'MAIL_MAILER' => 'smtp',
                'MAIL_HOST' => 'smtp-mail.outlook.com',
                'MAIL_PORT' => '587',
                'MAIL_USERNAME' => $email,
                'MAIL_PASSWORD' => $password,
                'MAIL_ENCRYPTION' => 'tls',
                'MAIL_FROM_ADDRESS' => $email,
            ]);

            $this->info('✅ Outlook configuration saved!');
            $this->testConfiguration();
        }
    }

    protected function setupSendgrid()
    {
        $this->info('🔧 Setting up SendGrid SMTP');
        $this->line('');
        
        $this->warn('You need a SendGrid API key:');
        $this->line('1. Sign up at https://sendgrid.com/');
        $this->line('2. Go to Settings > API Keys');
        $this->line('3. Create new API key with Mail Send permissions');
        $this->line('');

        $apiKey = $this->secret('Enter SendGrid API Key:');
        $fromEmail = $this->ask('Enter From Email Address:', 'noreply@snowpins.com');

        if ($apiKey) {
            $this->updateEnvFile([
                'MAIL_MAILER' => 'smtp',
                'MAIL_HOST' => 'smtp.sendgrid.net',
                'MAIL_PORT' => '587',
                'MAIL_USERNAME' => 'apikey',
                'MAIL_PASSWORD' => $apiKey,
                'MAIL_ENCRYPTION' => 'tls',
                'MAIL_FROM_ADDRESS' => $fromEmail,
            ]);

            $this->info('✅ SendGrid configuration saved!');
            $this->testConfiguration();
        }
    }

    protected function setupCustom()
    {
        $this->info('🔧 Setting up Custom SMTP');
        $this->line('');

        $host = $this->ask('SMTP Host:');
        $port = $this->ask('SMTP Port:', '587');
        $username = $this->ask('Username:');
        $password = $this->secret('Password:');
        $encryption = $this->choice('Encryption:', ['tls', 'ssl', 'none'], 0);
        $fromEmail = $this->ask('From Email:', 'noreply@snowpins.com');

        if ($host && $username && $password) {
            $this->updateEnvFile([
                'MAIL_MAILER' => 'smtp',
                'MAIL_HOST' => $host,
                'MAIL_PORT' => $port,
                'MAIL_USERNAME' => $username,
                'MAIL_PASSWORD' => $password,
                'MAIL_ENCRYPTION' => $encryption === 'none' ? 'null' : $encryption,
                'MAIL_FROM_ADDRESS' => $fromEmail,
            ]);

            $this->info('✅ Custom SMTP configuration saved!');
            $this->testConfiguration();
        }
    }

    protected function updateEnvFile($config)
    {
        $envPath = base_path('.env');
        $envContent = File::get($envPath);

        foreach ($config as $key => $value) {
            $pattern = "/^{$key}=.*$/m";
            $replacement = "{$key}={$value}";
            
            if (preg_match($pattern, $envContent)) {
                $envContent = preg_replace($pattern, $replacement, $envContent);
            } else {
                $envContent .= "\n{$replacement}";
            }
        }

        File::put($envPath, $envContent);
        
        // Clear config cache
        $this->call('config:clear');
    }

    protected function testConfiguration()
    {
        $this->line('');
        if ($this->confirm('Test email configuration now?')) {
            $testEmail = $this->ask('Enter test email address:');
            if ($testEmail) {
                $this->call('email:test', [
                    '--to' => $testEmail,
                    '--type' => 'simple'
                ]);
            }
        }
    }
}