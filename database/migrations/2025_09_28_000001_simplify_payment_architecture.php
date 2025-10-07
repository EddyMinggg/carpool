<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Step 1: Identify and handle child payments
        $childPayments = DB::table('payments')
            ->whereNotNull('parent_payment_id')
            ->get();

        Log::info('Found child payments to process', ['count' => $childPayments->count()]);

        // Step 2: For each child payment, ensure the parent payment covers the group
        foreach ($childPayments as $childPayment) {
            $parentPayment = DB::table('payments')
                ->where('id', $childPayment->parent_payment_id)
                ->first();

            if ($parentPayment) {
                // Update parent payment to reflect group booking if not already set
                if ($parentPayment->type !== 'group_full_payment') {
                    DB::table('payments')
                        ->where('id', $parentPayment->id)
                        ->update([
                            'type' => 'group_full_payment',
                            'group_size' => $parentPayment->group_size ?: 1,
                            'updated_at' => now(),
                        ]);
                }

                // Update any trip_joins that were associated with the child payment
                DB::table('trip_joins')
                    ->where('trip_id', $childPayment->trip_id)
                    ->where('user_phone', $childPayment->user_phone)
                    ->update([
                        'payment_confirmation' => $parentPayment->paid ? true : false,
                        'updated_at' => now(),
                    ]);

                Log::info('Processed child payment', [
                    'child_payment_id' => $childPayment->id,
                    'parent_payment_id' => $parentPayment->id,
                    'user_phone' => $childPayment->user_phone,
                ]);
            }
        }

        // Step 3: Delete child payment records as they're now redundant
        $deletedCount = DB::table('payments')
            ->whereNotNull('parent_payment_id')
            ->delete();

        Log::info('Deleted child payment records', ['count' => $deletedCount]);

        // Step 4: Remove the parent_payment_id column
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn('parent_payment_id');
        });

        Log::info('Removed parent_payment_id column from payments table');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Add back the parent_payment_id column
        Schema::table('payments', function (Blueprint $table) {
            $table->unsignedBigInteger('parent_payment_id')->nullable()->after('id');
            $table->foreign('parent_payment_id')->references('id')->on('payments')->onDelete('cascade');
        });

        Log::info('Restored parent_payment_id column to payments table');
    }
};