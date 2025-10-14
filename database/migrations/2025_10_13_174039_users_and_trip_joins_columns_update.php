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
        Schema::table('users', function (Blueprint $table) {
            $table->enum('notification_channel', ['whatsapp', 'sms'])->default('sms')->after('phone_verified_at');
        });

        Schema::table('trip_joins', function (Blueprint $table) {
            $table->boolean('has_left')->default(false)->after('payment_confirmed');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('notification_channel');
        });

        Schema::table('trip_joins', function (Blueprint $table) {
            $table->dropColumn('left');
        });
    }
};
