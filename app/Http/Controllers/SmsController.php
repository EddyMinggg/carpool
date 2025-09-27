<?php

namespace App\Http\Controllers;

use Aws\Laravel\AwsFacade as AWS;
use Illuminate\Http\Request;

class SmsController extends Controller
{
    public function send($phone_number)
    {
        $sms = AWS::createClient('sns');

        $sms->publish([
            'Message' => 'Hello, This is just a test Message',
            'PhoneNumber' => $phone_number,
            'MessageAttributes' => [
                'AWS.SNS.SMS.SMSType' => [
                    'DataType' => 'String',
                    'StringValue' => 'Transactional',
                ],
            ],
        ]);
        
        return;
    }
}
