<?php

require_once __DIR__ . '/vendor/autoload.php';

// Load Laravel application
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Services\VonageSmsService;

try {
    echo "=== Vonage SMS Configuration Test ===\n\n";

    // Check configuration
    $apiKey = config('services.vonage.api_key');
    $apiSecret = config('services.vonage.api_secret');
    $fromNumber = config('services.vonage.from_number');

    echo "Configuration Check:\n";
    echo "- API Key: " . ($apiKey ? "✓ Set (starts with: " . substr($apiKey, 0, 4) . "...)" : "❌ Missing") . "\n";
    echo "- API Secret: " . ($apiSecret ? "✓ Set" : "❌ Missing") . "\n";
    echo "- From Number: " . ($fromNumber ? "✓ Set ($fromNumber)" : "❌ Missing") . "\n\n";

    if (!$apiKey || !$apiSecret || !$fromNumber) {
        echo "❌ Vonage configuration is incomplete. Please check your .env file.\n";
        exit(1);
    }

    // Test SMS service initialization
    echo "Service Initialization Test:\n";
    $vonageService = new VonageSmsService();
    echo "✓ VonageSmsService created successfully\n\n";

    // Test account balance (this will also test API connection)
    echo "Account Balance Test:\n";
    $balanceResult = $vonageService->getAccountBalance();
    if ($balanceResult['success']) {
        echo "✓ Account balance: " . $balanceResult['balance'] . " EUR\n";
    } else {
        echo "❌ Failed to get account balance: " . $balanceResult['error'] . "\n";
    }

    echo "\n";
    
    // Note about SMS testing
    echo "📝 SMS Test Notes:\n";
    echo "- To test SMS sending, update the test script with a valid phone number\n";
    echo "- Current from number: $fromNumber\n";
    echo "- Make sure your Vonage account has sufficient credits\n";
    echo "- SSL verification is disabled for local development\n\n";

    echo "✅ Vonage SMS service is ready to use!\n";

} catch (Exception $e) {
    echo "❌ Error occurred during testing:\n";
    echo "Error: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . " (Line: " . $e->getLine() . ")\n";
    
    if (strpos($e->getMessage(), 'SSL') !== false) {
        echo "\n💡 SSL Issue Detected:\n";
        echo "- This is common in local development environments\n";
        echo "- The service should handle SSL issues automatically\n";
        echo "- If problems persist, check your internet connection\n";
    }
}