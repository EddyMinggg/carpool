<?php

use Illuminate\Support\Facades\Config;

return [
    'twilio' => [
        'sid' => env('TWILIO_ACCOUNT_SID'),
        'token' => env('TWILIO_AUTH_TOKEN'),
        'friendly_name' => 'Snowpins',
        'phone_number' => env('TWILIO_PHONE_NUMBER'),
        'to_number' => env('TWILIO_TO_NUMBER'),
        'whatsapp_number' => env('TWILIO_WHATSAPP_NUMBER')
    ],
];
