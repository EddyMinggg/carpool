<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Sync existing paid payments with trip_joins payment_confirmation
        // This ensures data consistency for records created before this fix
        
        DB::statement("
            UPDATE trip_joins 
            SET payment_confirmed = true 
            WHERE EXISTS (
                SELECT 1 FROM payments 
                WHERE payments.trip_id = trip_joins.trip_id 
                AND payments.user_phone = trip_joins.user_phone 
                AND payments.paid = true
            )
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Optionally reset all payment_confirmation to false
        // DB::table('trip_joins')->update(['payment_confirmation' => false]);
    }
};