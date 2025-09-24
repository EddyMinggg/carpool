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
        $trips = Trip::where('planned_departure_time', '<=', Carbon::now()->addHours(9))->get();

        foreach ($trips as $trip) {
            $paidDeposit = Payment::where('trip_id', $trip->id)->where('type', 'deposit')->where('paid', 1)->pluck('user_id');
            $newUserFee = $trip->base_price / ($paidDeposit->count());

            TripJoin::where('trip_id', $trip->id)->whereNotIn('user_id', $paidDeposit->all())->delete();

            TripJoin::where('trip_id', $trip->id)
                ->update(['user_fee' => round($newUserFee, 2)]);
        }
    }
}
