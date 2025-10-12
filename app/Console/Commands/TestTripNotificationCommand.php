<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Payment;
use App\Models\Trip;
use App\Models\TripJoin;
use App\Services\TripNotificationService;

class TestTripNotificationCommand extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'test:trip-notification
                          {--payment-id= : Test with specific payment ID}
                          {--create-demo : Create demo data for testing}
                          {--yes : Auto-confirm notifications}';

    /**
     * The console command description.
     */
    protected $description = 'Test WhatsApp notifications for trip member changes';

    private $notificationService;

    public function __construct()
    {
        parent::__construct();
        $this->notificationService = new TripNotificationService();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if ($this->option('create-demo')) {
            return $this->createDemoData();
        }

        if ($paymentId = $this->option('payment-id')) {
            return $this->testWithPayment($paymentId);
        }

        // Show available payments
        $this->showAvailablePayments();
        
        $paymentId = $this->ask('Enter payment ID to test with (or type "demo" to create demo data)');
        
        if (strtolower($paymentId) === 'demo') {
            return $this->createDemoData();
        }

        return $this->testWithPayment($paymentId);
    }

    private function testWithPayment($paymentId)
    {
        $payment = Payment::with('trip')->find($paymentId);
        
        if (!$payment) {
            $this->error("Payment with ID {$paymentId} not found.");
            return 1;
        }

        $this->info("Testing WhatsApp notification for payment:");
        $this->line("Payment ID: {$payment->id}");
        $this->line("Trip: {$payment->trip->pickup_location} → {$payment->trip->dropoff_location}");
        $this->line("User Phone: {$payment->user_phone}");
        $this->line("Amount: HK\${$payment->amount}");

        // Show current trip members
        $this->showTripMembers($payment->trip);

        if (!$this->option('yes') && !$this->confirm('Send WhatsApp notifications to existing members?')) {
            $this->info('Test cancelled.');
            return 0;
        }

        try {
            $this->info('Sending notifications...');
            $this->notificationService->notifyNewMemberJoined($payment);
            $this->info('✅ Notifications sent successfully!');
            
            $this->line('');
            $this->info('📱 Check the WhatsApp accounts of existing confirmed members to see the notifications.');
            
        } catch (\Exception $e) {
            $this->error("❌ Failed to send notifications: {$e->getMessage()}");
            return 1;
        }

        return 0;
    }

    private function showAvailablePayments()
    {
        $this->info('Available payments:');
        $this->line('');

        $payments = Payment::with(['trip'])
            ->where('paid', true)
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        if ($payments->isEmpty()) {
            $this->warn('No paid payments found.');
            return;
        }

        $headers = ['ID', 'Trip Route', 'Phone', 'Amount', 'Created'];
        $rows = [];

        foreach ($payments as $payment) {
            $rows[] = [
                $payment->id,
                $payment->trip->pickup_location . ' → ' . $payment->trip->dropoff_location,
                $payment->user_phone,
                "HK\${$payment->amount}",
                $payment->created_at->format('m-d H:i')
            ];
        }

        $this->table($headers, $rows);
    }

    private function showTripMembers(Trip $trip)
    {
        $this->line('');
        $this->info('Current trip members:');
        
        $members = TripJoin::where('trip_id', $trip->id)->get();
        
        if ($members->isEmpty()) {
            $this->warn('No members found for this trip.');
            return;
        }

        $headers = ['Phone', 'Status', 'Joined At'];
        $rows = [];

        foreach ($members as $member) {
            $status = $member->payment_confirmation ? '✅ Confirmed' : '⏳ Pending';
            $rows[] = [
                $member->user_phone,
                $status,
                $member->created_at->format('m-d H:i')
            ];
        }

        $this->table($headers, $rows);
        
        $confirmedCount = $members->where('payment_confirmation', true)->count();
        $this->line("Confirmed members: {$confirmedCount}/{$trip->max_people}");
    }

    private function createDemoData()
    {
        $this->info('Creating demo data for testing...');

        // Create a test trip
        $trip = Trip::create([
            'creator_id' => 1, // Use admin user ID
            'pickup_location' => '測試起點',
            'dropoff_location' => '測試終點',
            'planned_departure_time' => now()->addDays(1),
            'max_people' => 4, // Use correct field name
            'min_passengers' => 1,
            'price_per_person' => 280,
            'four_person_discount' => 30,
            'type' => 'normal', // Use correct enum value: 'golden', 'normal', 'fixed'
            'trip_status' => 'awaiting', // Use correct enum value
            'invitation_code' => 'DM' . substr(time(), -6) // Keep it within 8 characters
        ]);

        // Create existing confirmed members
        $existingMembers = [
            '+85251234567',
            '+85259876543'
        ];

        foreach ($existingMembers as $phone) {
            TripJoin::create([
                'trip_id' => $trip->id,
                'user_phone' => $phone,
                'payment_confirmation' => true,
                'created_at' => now()->subHours(2)
            ]);
        }

        // Create a new payment (simulating new member joining)
        $payment = Payment::create([
            'trip_id' => $trip->id,
            'user_phone' => '+85267890123',
            'amount' => 280,
            'type' => 'individual',
            'passengers' => 1,
            'paid' => true,
            'reference_code' => 'DEMO' . time()
        ]);

        // Create TripJoin for the new member
        TripJoin::create([
            'trip_id' => $trip->id,
            'user_phone' => $payment->user_phone,
            'payment_confirmation' => true
        ]);

        $this->info("✅ Demo data created!");
        $this->line("Trip ID: {$trip->id}");
        $this->line("Payment ID: {$payment->id}");
        $this->line("Existing members: " . implode(', ', $existingMembers));
        $this->line("New member: {$payment->user_phone}");
        
        if ($this->confirm('Test with this demo data now?')) {
            return $this->testWithPayment($payment->id);
        }

        return 0;
    }
}