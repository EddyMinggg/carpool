<?php

namespace App\Http\Controllers;

use Aws\Laravel\AwsFacade as AWS;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use App\Notifications\OtpNotification;
use App\Models\User;

class SmsController extends Controller
{
    // TODO: add remaining SMS templates
    public function send(Request $request)
    {
        $user = User::find($request->user()->id);
    }
}
