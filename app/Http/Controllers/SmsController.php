<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Notification;
use App\Notifications\TripMemberJoinNotification;
use App\Models\Trip;
use App\Models\User;
use App\Services\OtpService;

class SmsController extends Controller
{
    public function send(Request $request)
    {
        $res = (new OtpService(User::find(9)))->sendOtp();
        dd($res, $res['success']);
    }
}
