<?php

namespace App\Services;

use Vonage\Client;
use Vonage\Client\Credentials\Basic;
use Vonage\SMS\Message\SMS;
use Illuminate\Support\Facades\Log;
use Exception;

class VonageSmsService
{
    private $client;
    private $fromNumber;

    public function __construct()
    {
        $apiKey = config('services.vonage.api_key');
        $apiSecret = config('services.vonage.api_secret');
        $this->fromNumber = config('services.vonage.from_number');

        if (!$apiKey || !$apiSecret) {
            throw new Exception('Vonage API credentials are not configured properly');
        }

        $credentials = new Basic($apiKey, $apiSecret);
        
        // Create HTTP client with SSL options for development
        if (app()->environment(['local', 'development'])) {
            // Create Guzzle HTTP client with SSL verification disabled
            $httpClient = new \GuzzleHttp\Client([
                'verify' => false,  // Disable SSL verification for local development
                'timeout' => 30,    // Set timeout
                'http_errors' => false  // Don't throw exceptions on HTTP errors
            ]);
            
            $this->client = new Client($credentials, [], $httpClient);
            
            Log::info('Vonage client created with SSL verification disabled for local development');
        } else {
            // Production environment with normal SSL verification
            $this->client = new Client($credentials);
        }
    }

    /**
     * Send SMS message
     *
     * @param string $to Phone number to send SMS to (in international format)
     * @param string $message SMS message content
     * @return array Response with success status and message details
     */
    public function sendSms(string $to, string $message): array
    {
        try {
            // Ensure phone number is in international format
            $to = $this->formatPhoneNumber($to);

            // Create SMS message
            $smsMessage = new SMS($to, $this->fromNumber, $message);

            // Send SMS via Vonage
            $response = $this->client->sms()->send($smsMessage);
            
            // Get the first message from response
            $responseMessage = $response->current();
            
            if ($responseMessage->getStatus() == 0) {
                Log::info('SMS sent successfully via Vonage', [
                    'to' => $to,
                    'message_id' => $responseMessage->getMessageId(),
                    'status' => $responseMessage->getStatus()
                ]);

                return [
                    'success' => true,
                    'message_id' => $responseMessage->getMessageId(),
                    'status' => $responseMessage->getStatus(),
                    'message' => 'SMS sent successfully'
                ];
            } else {
                // Handle different status codes
                $statusMessages = [
                    1 => 'Throttled',
                    2 => 'Missing params',
                    3 => 'Invalid params',
                    4 => 'Invalid credentials',
                    5 => 'Internal error',
                    6 => 'Invalid message',
                    7 => 'Number barred',
                    8 => 'Partner account barred',
                    9 => 'Partner quota violation',
                    11 => 'Account not enabled for REST',
                    12 => 'Message too long',
                    13 => 'Communication failed',
                    14 => 'Invalid signature',
                    15 => 'Invalid sender address',
                    22 => 'Invalid network code',
                    23 => 'Invalid callback URL',
                    29 => 'Non-Whitelisted Destination',
                    34 => 'Invalid TTL'
                ];
                
                $errorText = $statusMessages[$responseMessage->getStatus()] ?? 'Unknown error';
                
                Log::error('Failed to send SMS via Vonage', [
                    'to' => $to,
                    'status' => $responseMessage->getStatus(),
                    'error_text' => $errorText
                ]);

                return [
                    'success' => false,
                    'status' => $responseMessage->getStatus(),
                    'error' => $errorText,
                    'message' => 'Failed to send SMS: ' . $errorText
                ];
            }
        } catch (Exception $e) {
            Log::error('Exception occurred while sending SMS via Vonage', [
                'to' => $to,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage(),
                'message' => 'Exception occurred while sending SMS'
            ];
        }
    }

    /**
     * Format phone number to international format
     *
     * @param string $phoneNumber Phone number to format
     * @return string Formatted phone number
     */
    private function formatPhoneNumber(string $phoneNumber): string
    {
        // Remove any spaces, dashes, or parentheses
        $phoneNumber = preg_replace('/[\s\-\(\)]/', '', $phoneNumber);
        
        // If number doesn't start with +, assume it needs country code
        if (!str_starts_with($phoneNumber, '+')) {
            // If it looks like a Hong Kong number (8 digits starting with specific patterns)
            if (preg_match('/^[2-9]\d{7}$/', $phoneNumber)) {
                $phoneNumber = '+852' . $phoneNumber;
            }
            // If it looks like a mainland China number (11 digits starting with 1)
            elseif (preg_match('/^1[3-9]\d{9}$/', $phoneNumber)) {
                $phoneNumber = '+86' . $phoneNumber;
            }
            // Otherwise, assume Hong Kong by default
            else {
                $phoneNumber = '+852' . $phoneNumber;
            }
        }

        return $phoneNumber;
    }

    /**
     * Check SMS delivery status
     *
     * @param string $messageId Message ID from previous SMS send
     * @return array Delivery status information
     */
    public function getDeliveryStatus(string $messageId): array
    {
        try {
            // Note: Vonage API doesn't have a direct method to check delivery status
            // You would need to implement webhook endpoints to receive delivery receipts
            // For now, return a basic response
            return [
                'success' => true,
                'message_id' => $messageId,
                'message' => 'Delivery status check not implemented. Use webhooks for delivery receipts.'
            ];
        } catch (Exception $e) {
            Log::error('Exception occurred while checking SMS delivery status', [
                'message_id' => $messageId,
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage(),
                'message' => 'Failed to check delivery status'
            ];
        }
    }

    /**
     * Get account balance
     *
     * @return array Account balance information
     */
    public function getAccountBalance(): array
    {
        try {
            $balance = $this->client->account()->getBalance();
            
            return [
                'success' => true,
                'balance' => $balance->getBalance(),
                'currency' => 'EUR', // Vonage balance is typically in EUR
                'message' => 'Account balance retrieved successfully'
            ];
        } catch (Exception $e) {
            Log::error('Exception occurred while getting account balance', [
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage(),
                'message' => 'Failed to get account balance'
            ];
        }
    }
}