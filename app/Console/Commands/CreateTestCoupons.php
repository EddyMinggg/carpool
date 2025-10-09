<?php

namespace App\Console\Commands;

use App\Models\Coupon;
use Illuminate\Console\Command;
use Carbon\Carbon;

class CreateTestCoupons extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'coupon:create-test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create test coupons for development';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $coupons = [
            [
                'code' => 'SAVE20',
                'discount_amount' => 20.00,
                'valid_from' => Carbon::now(),
                'valid_to' => Carbon::now()->addDays(30),
                'enabled' => true,
                'usage_limit' => 100,
                'used_count' => 0,
            ],
            [
                'code' => 'NEWUSER50',
                'discount_amount' => 50.00,
                'valid_from' => Carbon::now(),
                'valid_to' => Carbon::now()->addDays(60),
                'enabled' => true,
                'usage_limit' => 50,
                'used_count' => 0,
            ],
            [
                'code' => 'WEEKEND30',
                'discount_amount' => 30.00,
                'valid_from' => Carbon::now(),
                'valid_to' => Carbon::now()->addDays(7),
                'enabled' => true,
                'usage_limit' => 20,
                'used_count' => 0,
            ],
        ];

        foreach ($coupons as $couponData) {
            Coupon::updateOrCreate(
                ['code' => $couponData['code']],
                $couponData
            );
        }

        $this->info('Test coupons created successfully!');
        $this->info('Available coupons: SAVE20 (-HK$20), NEWUSER50 (-HK$50), WEEKEND30 (-HK$30)');
    }
}
