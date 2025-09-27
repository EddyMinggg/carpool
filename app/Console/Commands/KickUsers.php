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

        Trip::where('planned_departure_time', '<=', $now)->update([
            'trip_status' => 'departed',
        ]);

        $awaitingTrips = Trip::where('trip_status', 'awaiting')->get();

        foreach ($awaitingTrips as $trip) {
            $unpaidDeposit = Payment::where('trip_id', $trip->id)
                ->where('type', 'deposit')
                ->where('paid', 0)
                ->where('created_at', '<', $now->subMinutes(30))
                ->pluck('user_id');

            $joined = TripJoin::where('trip_id', $trip->id);
            $joined->whereIn('user_id', $unpaidDeposit->all())->delete();

            $currentPeople = $trip->joins()->count();
            $totalCost = $trip->base_price;
            $newUserFee = $totalCost / $currentPeople;

            $joined->update(['user_fee' => round($newUserFee, 2)]);
        }
    }
}
