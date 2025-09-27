<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class TestEmailVerificationCommand extends Command
{
    protected $signature = 'test:email-verification {email}';
    protected $description = 'Test email verification by creating a user and sending verification email';

    public function handle()
    {
        $email = $this->argument('email');
        
        $this->info("🧪 Testing email verification for: {$email}");
        $this->line('');

        try {
            // Check if user already exists
            $existingUser = User::where('email', $email)->first();
            if ($existingUser) {
                $this->warn("User with email {$email} already exists. Deleting for fresh test...");
                $existingUser->delete();
            }

            // Create a new user
            $user = User::create([
                'name' => 'Test User',
                'email' => $email,
                'username' => 'testuser_' . Str::random(5),
                'password' => Hash::make('password123'),
                'phone' => '+852' . rand(10000000, 99999999),
            ]);

            $this->info("✅ User created successfully:");
            $this->line("   ID: {$user->id}");
            $this->line("   Name: {$user->name}");
            $this->line("   Email: {$user->email}");
            $this->line("   Username: {$user->username}");
            $this->line('');

            // Send email verification
            $this->info("📧 Sending email verification...");
            
            $user->sendEmailVerificationNotification();
            
            $this->info("✅ Email verification sent successfully!");
            $this->line('');
            
            $this->info("📋 Next steps:");
            $this->line("1. Check your email inbox: {$email}");
            $this->line("2. Look for verification email from Carpool Platform");
            $this->line("3. Click the verification link in the email");
            $this->line('');
            
            $this->info("🔗 Or test manually at:");
            $this->line("   http://localhost:8000/verify-email");
            $this->line('');
            
            $this->info("🔧 Manual verification (development only):");
            $this->line("   POST to http://localhost:8000/manual-verify-email");
            
        } catch (\Exception $e) {
            $this->error("❌ Failed to test email verification");
            $this->error("Error: {$e->getMessage()}");
            
            $this->line('');
            $this->warn("🔧 Troubleshooting:");
            $this->line("1. Check mail configuration in .env");
            $this->line("2. Verify SMTP credentials");
            $this->line("3. Check Laravel logs: storage/logs/laravel.log");
        }
    }
}