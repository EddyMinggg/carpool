<?php

namespace App\Console\Commands;

use App\Models\Payment;
use App\Models\Trip;
use App\Models\TripJoin;
use Carbon\Carbon;
use Illuminate\Console\Command;

class TripUpdates extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:trip-updates';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update Trip status for cancelled and departed trips';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $now = Carbon::now();

        // Process trips that are awaiting status
        $awaitingTrips = Trip::where('trip_status', Trip::STATUS_AWAITING)
            ->get();

        $goldenCancelled = 0;
        $normalCancelled = 0;
        $normalPending = 0;
        $departed = 0;

        foreach ($awaitingTrips as $trip) {
            $deadlineReached = $this->hasDeadlinePassed($trip, $now);
            
            if (!$deadlineReached) {
                continue; // Skip trips that haven't reached deadline yet
            }

            // Get confirmed participants (active joins with payment confirmed)
            $confirmedCount = $trip->activeJoins()
                ->where('payment_confirmed', 1)
                ->count();

            if ($trip->type === Trip::TYPE_GOLDEN) {
                // Golden type: 黃金時段
                // Deadline: 1 hour before departure
                // Cancel if no confirmed participants
                if ($confirmedCount === 0) {
                    $trip->update(['trip_status' => Trip::STATUS_CANCELLED]);
                    $goldenCancelled++;
                    $this->info("Golden Trip #{$trip->id}: Cancelled (no confirmed participants)");
                } else {
                    // Has participants, mark as departed if departure time reached
                    if ($now->greaterThanOrEqualTo($trip->planned_departure_time)) {
                        $trip->update(['trip_status' => Trip::STATUS_DEPARTED]);
                        $departed++;
                        $this->info("Golden Trip #{$trip->id}: Departed with {$confirmedCount} passenger(s)");
                    }
                }
            } elseif ($trip->type === Trip::TYPE_NORMAL) {
                // Normal type: 非黃金時段
                // Deadline: 48 hours before departure
                // Min 3 passengers required
                
                if ($confirmedCount === 0 || $confirmedCount === 1) {
                    // 0 or 1 person: Cancel trip
                    $trip->update(['trip_status' => Trip::STATUS_CANCELLED]);
                    $normalCancelled++;
                    $this->info("Normal Trip #{$trip->id}: Cancelled ({$confirmedCount} passenger - below minimum)");
                } elseif ($confirmedCount === 2) {
                    // 2 people: Keep awaiting status for admin to handle manually
                    // Admin will contact these 2 users to negotiate +$100 each
                    $normalPending++;
                    $this->warn("Normal Trip #{$trip->id}: Pending admin review (2 passengers - needs negotiation)");
                    // Don't change status - leave as 'awaiting' for admin action
                } else {
                    // 3+ people: Confirm trip, mark as departed if departure time reached
                    if ($now->greaterThanOrEqualTo($trip->planned_departure_time)) {
                        $trip->update(['trip_status' => Trip::STATUS_DEPARTED]);
                        $departed++;
                        $this->info("Normal Trip #{$trip->id}: Departed with {$confirmedCount} passengers");
                    }
                }
            }
        }

        // Summary
        $this->newLine();
        $this->info("=== Trip Status Update Summary ===");
        $this->info("Golden trips cancelled: {$goldenCancelled}");
        $this->info("Normal trips cancelled: {$normalCancelled}");
        $this->info("Normal trips pending admin review (2 passengers): {$normalPending}");
        $this->info("Trips marked as departed: {$departed}");
        $this->newLine();

        return Command::SUCCESS;
    }

    /**
     * Check if trip deadline has passed
     */
    private function hasDeadlinePassed(Trip $trip, Carbon $now): bool
    {
        $departureTime = Carbon::parse($trip->planned_departure_time);

        if ($trip->type === Trip::TYPE_GOLDEN) {
            // Golden: deadline is 1 hour before departure
            $deadline = $departureTime->copy()->subHour();
            return $now->greaterThanOrEqualTo($deadline);
        } elseif ($trip->type === Trip::TYPE_NORMAL) {
            // Normal: deadline is 48 hours before departure
            $deadline = $departureTime->copy()->subHours(48);
            return $now->greaterThanOrEqualTo($deadline);
        }

        return false;
    }
}
