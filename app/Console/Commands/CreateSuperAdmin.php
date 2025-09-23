<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class CreateSuperAdmin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:super-admin {username} {email} {password}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new super admin user';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $username = $this->argument('username');
        $email = $this->argument('email');
        $password = $this->argument('password');

        // Check if user already exists
        if (User::where('email', $email)->exists()) {
            $this->error("User with email {$email} already exists!");
            return 1;
        }

        if (User::where('username', $username)->exists()) {
            $this->error("User with username {$username} already exists!");
            return 1;
        }

        // Create super admin
        $user = User::create([
            'username' => $username,
            'email' => $email,
            'password' => Hash::make($password),
            'is_admin' => User::ROLE_SUPER_ADMIN,
            'email_verified_at' => now(),
        ]);

        $this->info("Super Admin created successfully!");
        $this->info("Username: {$username}");
        $this->info("Email: {$email}");
        $this->info("You can now login with these credentials.");

        return 0;
    }
}
