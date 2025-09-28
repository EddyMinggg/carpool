<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Notifications\FastVerifyEmail;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class TestFastEmailCommand extends Command
{
    protected $signature = 'mail:test-fast {email}';
    protected $description = 'Test fast email delivery';

    public function handle()
    {
        $email = $this->argument('email');
        
        $this->info("Testing fast email delivery to: {$email}");
        $startTime = microtime(true);

        // Test with a dummy user
        $user = User::where('email', $email)->first();
        if (!$user) {
            $this->error("User with email {$email} not found");
            return 1;
        }

        try {
            // Send fast verification email
            $user->sendEmailVerificationNotification();
            
            $endTime = microtime(true);
            $duration = round(($endTime - $startTime) * 1000, 2);
            
            $this->info("✅ Email sent successfully in {$duration}ms");
            return 0;
        } catch (\Exception $e) {
            $this->error("❌ Email failed: " . $e->getMessage());
            return 1;
        }
    }
}
