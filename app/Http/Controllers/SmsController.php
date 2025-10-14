<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Notification;
use App\Notifications\TripMemberJoinNotification;
use App\Models\Trip;

class SmsController extends Controller
{
    // TODO: add remaining SMS templates
    public function send(Request $request)
    {
        Notification::route('Sms', '+85255421867')
            ->notify(new TripMemberJoinNotification(Trip::find(8)));
    }
}
