<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Payment;
use App\Models\Trip;
use App\Models\TripJoin;
use App\Models\User;

class CreateTestPaymentCommand extends Command
{
    protected $signature = 'create:test-payment 
                           {--trip-id= : Specific trip ID to use}
                           {--phone= : Phone number for new member}';

    protected $description = 'Create a test payment that can be confirmed via web interface';

    public function handle()
    {
        $tripId = $this->option('trip-id');
        $phone = $this->option('phone') ?? '+85299887766';

        // Get available trips
        if (!$tripId) {
            $trips = Trip::with('payments')->get();
            
            if ($trips->isEmpty()) {
                $this->error('No trips found.');
                return 1;
            }

            $this->info('Available trips:');
            foreach ($trips as $trip) {
                $confirmedPayments = $trip->payments->where('paid', true)->count();
                $pendingPayments = $trip->payments->where('paid', false)->count();
                
                $this->line("ID: {$trip->id} - {$trip->pickup_location} → {$trip->dropoff_location}");
                $this->line("  Confirmed: {$confirmedPayments} | Pending: {$pendingPayments}");
            }

            $tripId = $this->ask('Enter trip ID to create test payment for');
        }

        $trip = Trip::find($tripId);
        if (!$trip) {
            $this->error("Trip with ID {$tripId} not found.");
            return 1;
        }

        // Check if phone already has payment for this trip
        $existingPayment = Payment::where('trip_id', $tripId)
                                 ->where('user_phone', $phone)
                                 ->first();

        if ($existingPayment) {
            $this->warn("Payment already exists for {$phone} on this trip (ID: {$existingPayment->id})");
            
            if ($existingPayment->paid) {
                $this->info("This payment is already confirmed. Use trip notification test instead:");
                $this->line("php artisan test:trip-notification --payment-id={$existingPayment->id} --yes");
                return 0;
            } else {
                $this->info("This payment is pending confirmation. You can confirm it via web interface:");
                $this->line("http://localhost:8000/admin/payment-confirmation/payment/{$existingPayment->id}");
                return 0;
            }
        }

        // Create new payment
        $payment = Payment::create([
            'trip_id' => $tripId,
            'user_phone' => $phone,
            'amount' => $trip->price_per_person,
            'type' => 'individual',
            'paid' => false, // This needs to be confirmed
            'group_size' => 1,
            'reference_code' => 'PENDING' . time() // Temporary reference code
        ]);

        // Create TripJoin record
        TripJoin::create([
            'trip_id' => $tripId,
            'user_phone' => $phone,
            'payment_confirmation' => false // Will be updated when payment confirmed
        ]);

        $this->info("✅ Test payment created successfully!");
        $this->line("Payment ID: {$payment->id}");
        $this->line("Trip: {$trip->pickup_location} → {$trip->dropoff_location}");
        $this->line("Phone: {$phone}");
        $this->line("Amount: HK\${$payment->amount}");
        $this->line("");
        $this->info("🌐 Now you can confirm this payment via web interface:");
        $this->line("http://localhost:8000/admin/payment-confirmation/payment/{$payment->id}");
        $this->line("");
        $this->info("📱 When confirmed, existing trip members will receive WhatsApp notifications!");

        // Show existing members who will receive notifications
        $existingMembers = TripJoin::where('trip_id', $tripId)
                                  ->where('payment_confirmation', true)
                                  ->where('user_phone', '!=', $phone)
                                  ->get();

        if ($existingMembers->isNotEmpty()) {
            $this->line("");
            $this->info("📋 Existing confirmed members who will receive notifications:");
            foreach ($existingMembers as $member) {
                $this->line("  • {$member->user_phone}");
            }
        } else {
            $this->warn("No existing confirmed members found. No notifications will be sent.");
        }

        return 0;
    }
}