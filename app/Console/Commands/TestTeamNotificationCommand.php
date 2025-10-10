<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\NotificationService;
use App\Services\SmsTemplateService;
use App\Models\Trip;
use App\Models\User;
use App\Models\TripJoin;

class TestTeamNotificationCommand extends Command
{
    protected $signature = 'test:team-notification 
                           {--trip-id= : Trip ID to test with}
                           {--phone= : Phone number for new member}
                           {--dry-run : Only show what would be sent}';

    protected $description = 'Test team notification system in SANDBOX mode';

    public function handle()
    {
        $this->info('🧪 Team Notification Test (SANDBOX Mode)');
        $this->line('');

        $tripId = $this->option('trip-id');
        $phone = $this->option('phone') ?: '+85212345678';
        $isDryRun = $this->option('dry-run');

        // Find or create test data
        if ($tripId) {
            $trip = Trip::find($tripId);
            if (!$trip) {
                $this->error("Trip with ID {$tripId} not found!");
                return;
            }
        } else {
            $trip = Trip::with('joins.user')->first();
            if (!$trip) {
                $this->error('No trips found in database!');
                return;
            }
        }

        $this->info("Testing with Trip: {$trip->id} - {$trip->dropoff_location}");
        $this->line("Trip Type: " . $trip->getTypeDisplayName());
        
        // Get existing members
        $existingMembers = $trip->joins()->with('user')->get()
            ->filter(fn($join) => $join->user !== null)
            ->pluck('user');

        $this->line("Current team size: " . count($existingMembers) . " members");
        
        if ($existingMembers->isEmpty()) {
            $this->warn('No existing members found. Creating mock notification...');
            
            // Show what would be sent for first member
            $this->showMockNotification($trip, $phone, []);
            return;
        }

        // Show existing members
        foreach ($existingMembers as $member) {
            $this->line("  - {$member->username} ({$member->phone})");
        }

        $this->line('');
        
        // Create mock new member
        $newMember = new User([
            'phone' => $phone,
            'username' => 'Test User'
        ]);

        if ($isDryRun) {
            $this->showMockNotification($trip, $phone, $existingMembers->toArray());
        } else {
            $this->sendTestNotification($trip, $newMember, $existingMembers->toArray());
        }
    }

    private function showMockNotification(Trip $trip, string $phone, array $existingMembers)
    {
        $currentCount = count($existingMembers) + 1;
        $maxPeople = $trip->max_people;
        $destination = $trip->dropoff_location;
        $maskedPhone = $this->maskPhone($phone);

        $basePrice = number_format((float) $trip->price_per_person, 0);
        $discountPrice = null;

        if (!$trip->isGoldenHour() && $currentCount >= 4 && $trip->four_person_discount > 0) {
            $effectivePrice = $trip->getEffectivePricePerPerson($currentCount);
            $discountPrice = number_format($effectivePrice, 0);
        }

        if ($trip->isGoldenHour()) {
            $message = SmsTemplateService::teamJoinGoldenHour(
                $maskedPhone, $currentCount, $maxPeople, $destination, $basePrice
            );
        } else {
            $message = SmsTemplateService::teamJoinRegularHour(
                $maskedPhone, $currentCount, $maxPeople, $destination, $basePrice, $discountPrice
            );
        }

        $this->info('📱 Message that would be sent:');
        $this->line('');
        $this->comment($message);
        $this->line('');

        $validation = SmsTemplateService::validateSmsLength($message);
        $this->info("📊 Statistics:");
        $this->line("Length: {$validation['length']} characters");
        $this->line("SMS Parts: {$validation['parts']}");
        $this->line("Single SMS: " . ($validation['is_single'] ? 'Yes ✅' : 'No ⚠️'));
        
        $this->line('');
        $this->info("Would notify " . count($existingMembers) . " existing members");
    }

    private function sendTestNotification(Trip $trip, User $newMember, array $existingMembers)
    {
        $this->info('🚀 Sending test notification...');
        
        try {
            $notificationService = app(NotificationService::class);
            $result = $notificationService->sendTeamJoinNotification($trip, $newMember, $existingMembers);

            $this->line('');
            $this->info('📊 Notification Results:');
            $this->line("✅ Successful: {$result['success']}");
            $this->line("❌ Failed: {$result['failed']}");
            $this->line("📤 Total sent: {$result['sent']}");

            if (!empty($result['details'])) {
                $this->line('');
                $this->info('📋 Details:');
                foreach ($result['details'] as $detail) {
                    $status = $detail['success'] ? '✅' : '❌';
                    $phone = $detail['phone'] ?? 'N/A';
                    $error = $detail['error'] ?? '';
                    $this->line("  {$status} {$phone} {$error}");
                }
            }

        } catch (\Exception $e) {
            $this->error('❌ Test failed: ' . $e->getMessage());
        }
    }

    private function maskPhone(string $phone): string
    {
        if (strlen($phone) <= 4) {
            return $phone;
        }
        return str_repeat('*', strlen($phone) - 4) . substr($phone, -4);
    }
}