<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('trip_joins', function (Blueprint $table) {
            $table->string('reference_code')->nullable()->after('pickup_location');
            $table->boolean('payment_confirmed')->default(false)->after('reference_code');
            $table->timestamp('payment_confirmed_at')->nullable()->after('payment_confirmed');
            $table->unsignedBigInteger('confirmed_by')->nullable()->after('payment_confirmed_at');
            
            // Add foreign key for admin who confirmed the payment
            $table->foreign('confirmed_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('trip_joins', function (Blueprint $table) {
            $table->dropForeign(['confirmed_by']);
            $table->dropColumn(['reference_code', 'payment_confirmed', 'payment_confirmed_at', 'confirmed_by']);
        });
    }
};
