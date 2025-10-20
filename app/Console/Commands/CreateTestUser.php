<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class CreateTestUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:test-user {phone} {--username=} {--email=} {--password=password123}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new test user (regular user role)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $phone = $this->argument('phone');
        $username = $this->option('username') ?? 'Test User ' . substr($phone, -4);
        $email = $this->option('email') ?? 'test' . substr($phone, -4) . '@example.com';
        $password = $this->option('password');

        // Check if user already exists
        if (User::where('phone', $phone)->exists()) {
            $this->error("User with phone {$phone} already exists!");
            return 1;
        }

        if (User::where('email', $email)->exists()) {
            $this->error("User with email {$email} already exists!");
            return 1;
        }

        // Create test user
        $user = User::create([
            'username' => $username,
            'email' => $email,
            'phone' => $phone,
            'password' => Hash::make($password),
            'user_role' => User::ROLE_USER, // Regular user
            'notification_channel' => 'sms', // Default to SMS
            'phone_verified_at' => now(), // Auto-verify for testing
            'active' => true,
        ]);

        $this->info("Test User created successfully!");
        $this->line("━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━");
        $this->info("📱 Phone: {$phone}");
        $this->info("👤 Username: {$username}");
        $this->info("📧 Email: {$email}");
        $this->info("🔑 Password: {$password}");
        $this->info("✅ Phone Verified: Yes (auto-verified for testing)");
        $this->info("📢 Notification: SMS");
        $this->line("━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━");
        $this->comment("You can now login with phone/email and password.");

        return 0;
    }
}
