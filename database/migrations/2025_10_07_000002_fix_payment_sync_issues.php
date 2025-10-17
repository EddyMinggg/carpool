<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1. 删除重复的group_member_payment记录
        DB::table('payments')->where('type', 'group_member_payment')->delete();
        
        // 2. 同步已付款的payments与trip_joins
        DB::statement("
            UPDATE trip_joins 
            SET payment_confirmed = 1 
            WHERE EXISTS (
                SELECT 1 FROM payments 
                WHERE payments.trip_id = trip_joins.trip_id 
                AND payments.user_phone = trip_joins.user_phone 
                AND payments.paid = 1
            )
        ");
    }

    public function down(): void
    {
        // 可以选择重置payment_confirmation状态
        // DB::table('trip_joins')->update(['payment_confirmation' => 0]);
    }
};