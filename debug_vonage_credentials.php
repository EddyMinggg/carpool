<?php

require_once __DIR__ . '/vendor/autoload.php';

// Load Laravel application
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== Debug Vonage Credentials ===\n\n";

// Debug configuration values
echo "Raw .env values:\n";
echo "VONAGE_API_KEY from env(): " . env('VONAGE_API_KEY') . "\n";
echo "VONAGE_API_SECRET from env(): " . env('VONAGE_API_SECRET') . "\n";
echo "VONAGE_FROM_NUMBER from env(): " . env('VONAGE_FROM_NUMBER') . "\n\n";

echo "Processed config values:\n";
echo "API Key: " . config('services.vonage.api_key') . "\n";
echo "API Secret: " . config('services.vonage.api_secret') . "\n";
echo "From Number: " . config('services.vonage.from_number') . "\n\n";

// Check for common issues
$apiKey = config('services.vonage.api_key');
$apiSecret = config('services.vonage.api_secret');

echo "Validation:\n";
echo "- API Key length: " . strlen($apiKey) . " characters\n";
echo "- API Secret length: " . strlen($apiSecret) . " characters\n";
echo "- API Key format: " . (ctype_alnum($apiKey) ? "✓ Alphanumeric" : "❌ Contains special chars") . "\n";
echo "- API Secret contains $: " . (strpos($apiSecret, '$') !== false ? "Yes" : "No") . "\n\n";

echo "💡 Troubleshooting Tips:\n";
echo "1. Vonage API keys are typically 8 characters, alphanumeric\n";
echo "2. API secrets may contain special characters and should be quoted in .env\n";
echo "3. Make sure credentials are copied exactly from Vonage dashboard\n";
echo "4. Check if the account is active and has credits\n";
echo "5. Verify the API key/secret pair is for the correct Vonage application\n";