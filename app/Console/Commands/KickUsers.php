<?php

namespace App\Console\Commands;

use App\Models\Payment;
use App\Models\Trip;
use App\Models\TripJoin;
use Carbon\Carbon;
use Illuminate\Console\Command;

class KickUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:kick-users';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Kick users without payment an hour prior to departure';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $now = Carbon::now()->addHours(8);
        $this->info("KickUsers command started at: {$now}");

        $departedTripsCount = Trip::where('planned_departure_time', '<=', $now)->update([
            'trip_status' => 'departed',
        ]);

        if ($departedTripsCount > 0) {
            $this->info("Marked {$departedTripsCount} trips as departed");
        }

        $chargingTripCount = Trip::where('type', 'normal')
            ->where('planned_departure_time', '<=', $now->addDay())
            ->update([
                'trip_status' => 'charging',
            ]);

        if ($chargingTripCount > 0) {
            $this->info("Marked {$chargingTripCount} trips as charging remaining payment");
        }

        $cancelledTrips = Trip::where('type', 'normal')
            ->where('planned_departure_time', '<=', $now->addDays(2))
            ->get();

        $cancelledTripIds = $cancelledTrips->filter(function ($trip) {
            return $trip->joins()->count() < $trip->min_passengers;
        })->pluck('id');

        $cancelledTripsCount = Trip::whereIn('id', $cancelledTripIds)->update(['trip_status' => 'cancelled']);

        if ($cancelledTripsCount > 0) {
            $this->info("Marked {$cancelledTripsCount} trips as cancelled");
        }

        $awaitingTrips = Trip::where('trip_status', 'awaiting')->get();
        $this->info("Found {$awaitingTrips->count()} awaiting trips");

        foreach ($awaitingTrips as $trip) {
            $this->info("Checking trip {$trip->id}...");

            // 找出30分鐘前創建但仍未付訂金的付款記錄
            $paymentDeadline = Carbon::now()->subMinutes(30);
            $this->info("Payment deadline: {$paymentDeadline}");

            $unpaidDeposits = Payment::where('trip_id', $trip->id)
                ->where('type', 'deposit')
                ->where('paid', 0)
                ->where('created_at', '<', $paymentDeadline)
                ->get();

            $this->info("Trip {$trip->id}: Found {$unpaidDeposits->count()} unpaid deposits past deadline");

            // Debug: 顯示所有該行程的付款記錄
            // $allPayments = Payment::where('trip_id', $trip->id)->get();
            // foreach ($allPayments as $payment) {
            //     $this->line("  Payment ID {$payment->id}: User {$payment->user_id}, Type: {$payment->type}, Paid: {$payment->paid}, Created: {$payment->created_at}");
            // }

            if ($unpaidDeposits->count() > 0) {
                $this->warn("Processing trip {$trip->id}: Found {$unpaidDeposits->count()} unpaid deposits to kick");

                // 記錄被踢出的用戶（用於日誌）
                foreach ($unpaidDeposits as $payment) {
                    $this->error("Kicking user {$payment->user_id} from trip {$trip->id} - Payment created: {$payment->created_at}, Deadline was: {$paymentDeadline}");
                }

                // 踢出未付款用戶
                $userIdsToKick = $unpaidDeposits->pluck('user_id')->toArray();

                $deletedJoins = TripJoin::where('trip_id', $trip->id)
                    ->whereIn('user_id', $userIdsToKick)
                    ->delete();

                $this->info("Deleted {$deletedJoins} trip joins");

                // 同時標記相關付款記錄為已取消
                $updatedPayments = Payment::where('trip_id', $trip->id)
                    ->whereIn('user_id', $userIdsToKick)
                    ->update(['paid' => -1]); // -1 表示已取消

                $this->info("Updated {$updatedPayments} payment records to cancelled status");

                // 重新計算剩餘用戶的費用
                $currentPeople = $trip->joins()->count();
                if ($currentPeople > 0) {
                    $totalCost = $trip->base_price;
                    $newUserFee = $totalCost / $currentPeople;

                    $updatedJoins = TripJoin::where('trip_id', $trip->id)
                        ->update(['user_fee' => round($newUserFee, 2)]);

                    $this->info("Updated user fee for trip {$trip->id} to {$newUserFee} for {$currentPeople} remaining users ({$updatedJoins} joins updated)");
                } else {
                    $this->warn("Trip {$trip->id} has no remaining users after cleanup");
                }
            } else {
                $this->line("Trip {$trip->id}: No unpaid deposits past deadline found");
            }
        }

        $this->info('User cleanup completed successfully');
    }
}
