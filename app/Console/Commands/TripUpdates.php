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
        $now = Carbon::now()->addHours(8);

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

        $departedTripsCount = Trip::where('planned_departure_time', '<=', $now)->update([
            'trip_status' => 'departed',
        ]);

        if ($departedTripsCount > 0) {
            $this->info("Marked {$departedTripsCount} trips as departed");
        }
    }
}
