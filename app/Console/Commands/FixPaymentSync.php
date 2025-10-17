<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Payment;
use App\Models\TripJoin;

class FixPaymentSync extends Command
{
    protected $signature = 'fix:payment-sync';
    protected $description = 'Fix payment and trip_joins synchronization issues';

    public function handle()
    {
        $this->info('Starting payment sync fix...');
        
        // 1. Remove duplicate group_member_payment records
        $this->info('Removing duplicate group_member_payment records...');
        $duplicateCount = Payment::where('type', 'group_member_payment')->count();
        if ($duplicateCount > 0) {
            Payment::where('type', 'group_member_payment')->delete();
            $this->info("Removed {$duplicateCount} duplicate payment records.");
        }
        
        // 2. Sync paid payments with trip_joins
        $this->info('Syncing paid payments with trip_joins...');
        $paidPayments = Payment::where('paid', true)->get();
        $synced = 0;
        
        foreach ($paidPayments as $payment) {
            // Update main user
            $updated = TripJoin::where('trip_id', $payment->trip_id)
                ->where('user_phone', $payment->user_phone)
                ->where('payment_confirmation', false)
                ->update(['payment_confirmation' => true]);
            
            if ($updated > 0) {
                $synced++;
                $this->info("Synced payment #{$payment->id} for user {$payment->user_phone}");
            }
            
            // Update child payments if exists
            if ($payment->childPayments()->exists()) {
                foreach ($payment->childPayments as $childPayment) {
                    $childUpdated = TripJoin::where('trip_id', $childPayment->trip_id)
                        ->where('user_phone', $childPayment->user_phone)
                        ->where('payment_confirmation', false)
                        ->update(['payment_confirmation' => true]);
                    
                    if ($childUpdated > 0) {
                        $synced++;
                        $this->info("Synced child payment #{$childPayment->id} for user {$childPayment->user_phone}");
                    }
                }
            }
        }
        
        $this->info("Synced {$synced} trip_join records.");
        
        // 3. Show final statistics
        $this->showStatistics();
        
        $this->info('Payment sync fix completed!');
    }
    
    private function showStatistics()
    {
        $paidPayments = Payment::where('paid', true)->count();
        $confirmedTripJoins = TripJoin::where('payment_confirmation', true)->count();
        $totalPayments = Payment::count();
        $totalTripJoins = TripJoin::count();
        
        $this->table(
            ['Metric', 'Count'],
            [
                ['Total Payments', $totalPayments],
                ['Paid Payments', $paidPayments],
                ['Total Trip Joins', $totalTripJoins],
                ['Confirmed Trip Joins', $confirmedTripJoins],
            ]
        );
        
        if ($paidPayments > $confirmedTripJoins) {
            $this->warn('There are still some inconsistencies!');
        } else {
            $this->info('All payments are properly synchronized!');
        }
    }
}