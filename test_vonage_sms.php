<?php

use App\Services\VonageSmsService;
use App\Services\OtpService;

require_once __DIR__ . '/vendor/autoload.php';

// Load Laravel application
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Test SMS sending
try {
    echo "=== Vonage SMS & OTP Service Test ===\n\n";

    // Test 1: Basic SMS sending
    echo "Test 1: Basic SMS Sending\n";
    $vonageSmsService = new VonageSmsService();
    
    // Replace with your test phone number (in international format)
    $testPhoneNumber = '+85212345678'; // Update this with your actual phone number
    $testMessage = 'Hello! This is a test message from your Carpool application via Vonage.';
    
    echo "Sending SMS to: $testPhoneNumber\n";
    echo "Message: $testMessage\n";
    
    $result = $vonageSmsService->sendSms($testPhoneNumber, $testMessage);
    
    if ($result['success']) {
        echo "✅ SMS sent successfully!\n";
        echo "Message ID: " . ($result['message_id'] ?? 'N/A') . "\n";
    } else {
        echo "❌ SMS failed to send:\n";
        echo "Error: " . ($result['error'] ?? 'Unknown error') . "\n";
    }
    
    echo "\n" . str_repeat('-', 50) . "\n\n";
    
    // Test 2: OTP Service
    echo "Test 2: OTP Service\n";
    $otpService = new OtpService($vonageSmsService);
    
    // Test data (similar to user registration)
    $userData = [
        'username' => 'TestUser',
        'email' => 'test@example.com',
        'phone_country_code' => '+852',
        'phone' => '12345678'
    ];
    
    $fullPhoneNumber = $userData['phone_country_code'] . $userData['phone'];
    
    echo "Sending OTP to: $fullPhoneNumber\n";
    
    $otpResult = $otpService->sendOtp($fullPhoneNumber, $userData, '127.0.0.1');
    
    if ($otpResult['success']) {
        echo "✅ OTP sent successfully!\n";
        echo "Message: " . $otpResult['message'] . "\n";
        echo "Expires in: " . $otpResult['expires_in'] . " seconds\n";
        if (isset($otpResult['message_id'])) {
            echo "SMS Message ID: " . $otpResult['message_id'] . "\n";
        }
    } else {
        echo "❌ OTP failed to send:\n";
        echo "Error: " . ($otpResult['error'] ?? 'Unknown error') . "\n";
    }
    
    echo "\n" . str_repeat('-', 50) . "\n\n";
    
    // Test 3: Account balance (if available)
    echo "Test 3: Check Vonage Account Balance\n";
    $balanceResult = $vonageSmsService->getAccountBalance();
    
    if ($balanceResult['success']) {
        echo "✅ Account balance retrieved:\n";
        echo "Balance: " . $balanceResult['balance'] . " " . $balanceResult['currency'] . "\n";
    } else {
        echo "❌ Failed to get account balance:\n";
        echo "Error: " . ($balanceResult['error'] ?? 'Unknown error') . "\n";
    }
    
    echo "\n" . str_repeat('=', 50) . "\n";
    echo "Test completed!\n";
    echo "Note: Make sure to:\n";
    echo "1. Update VONAGE_API_KEY in .env file\n";
    echo "2. Update VONAGE_API_SECRET in .env file\n";
    echo "3. Update VONAGE_FROM_NUMBER in .env file\n";
    echo "4. Update \$testPhoneNumber in this script to your real phone number for testing\n";
    
} catch (Exception $e) {
    echo "❌ Exception occurred during testing:\n";
    echo "Error: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . " (Line: " . $e->getLine() . ")\n";
    echo "Trace:\n" . $e->getTraceAsString() . "\n";
}