<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Payment;
use App\Models\TripJoin;

class CheckPaymentSync extends Command
{
    protected $signature = 'check:payment-sync';
    protected $description = 'Check if payments and trip_joins are properly synchronized';

    public function handle()
    {
        $paidPayments = Payment::where('paid', true)->count();
        $confirmedTripJoins = TripJoin::where('payment_confirmation', true)->count();
        
        $this->info("Paid payments: {$paidPayments}");
        $this->info("Confirmed trip joins: {$confirmedTripJoins}");
        
        // Check for inconsistencies
        $inconsistentRecords = Payment::where('paid', true)
            ->whereDoesntHave('trip.joins', function($query) {
                $query->where('payment_confirmation', true);
            })->count();
            
        if ($inconsistentRecords > 0) {
            $this->warn("Found {$inconsistentRecords} inconsistent records!");
        } else {
            $this->info("All records are synchronized properly!");
        }
    }
}