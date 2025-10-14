<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use App\Console\Commands\TripUpdates;
use Illuminate\Support\Facades\Schedule;

Schedule::command('app:trip-updates')->everyFiveMinutes();
