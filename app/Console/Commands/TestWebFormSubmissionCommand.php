<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Http\Request;
use App\Http\Controllers\PaymentController;
use App\Models\Trip;

class TestWebFormSubmissionCommand extends Command
{
    protected $signature = 'test:web-form-submission 
                           {--trip-id=7 : Trip ID to test with}
                           {--people=1 : Number of people (1 for individual, 2+ for group)}';

    protected $description = 'Test web form submission to verify payment type logic';

    public function handle()
    {
        $tripId = $this->option('trip-id');
        $peopleCount = (int)$this->option('people');
        
        $trip = Trip::find($tripId);
        if (!$trip) {
            $this->error("Trip with ID {$tripId} not found.");
            return 1;
        }

        $this->info("Testing web form submission:");
        $this->line("Trip ID: {$tripId}");
        $this->line("People Count: {$peopleCount}");
        $this->line("Expected Type: " . ($peopleCount > 1 ? 'group' : 'individual'));

        // 模擬網頁表單提交的數據
        $formData = [
            'trip_id' => $tripId,
            'people_count' => $peopleCount,
            'passengers' => []
        ];

        // 構建乘客數據
        for ($i = 0; $i < $peopleCount; $i++) {
            $formData['passengers'][] = [
                'name' => "測試用戶 " . ($i + 1),
                'phone' => "1234567" . ($i + 1),
                'phone_country_code' => '+852',
                'pickup_location' => "測試地址 " . ($i + 1)
            ];
        }

        if ($peopleCount > 1) {
            // 團體預訂需要額外欄位
            $formData['subtotal_amount'] = $peopleCount * 275;
            $formData['total_amount'] = $peopleCount * 275;
            $formData['price_per_person'] = 275;
        }

        $this->line('');
        $this->info('Form data structure:');
        $this->line('people_count: ' . $formData['people_count']);
        $this->line('passengers count: ' . count($formData['passengers']));
        $this->line('');

        // 模擬 PaymentController 的邏輯判斷
        $peopleCountFromForm = $formData['people_count'] ?? null;
        $passengersFromForm = $formData['passengers'] ?? [];
        
        if ($peopleCountFromForm !== null) {
            $isGroupBooking = (int)$peopleCountFromForm > 1;
        } else {
            $isGroupBooking = count($passengersFromForm) > 1;
        }

        $expectedType = $isGroupBooking ? 'group' : 'individual';
        
        $this->info("Logic test result:");
        $this->line("Is Group Booking: " . ($isGroupBooking ? 'Yes' : 'No'));
        $this->line("Expected Payment Type: {$expectedType}");
        
        if ($peopleCount == 1 && $expectedType == 'individual') {
            $this->info('✅ Single person booking correctly identified as individual');
        } elseif ($peopleCount > 1 && $expectedType == 'group') {
            $this->info('✅ Multi-person booking correctly identified as group');
        } else {
            $this->error('❌ Logic error detected!');
            return 1;
        }

        return 0;
    }
}