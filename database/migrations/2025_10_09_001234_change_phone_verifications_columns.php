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
        Schema::table('phone_verifications', function (Blueprint $table) {
            $table->dropColumn(['user_data']);

            $table->dropColumn(['expires_at']);
            $table->timestamp('expires_at')->nullable()->after('otp_code');

            $table->timestamp('created_at')->useCurrent()->change();
            $table->timestamp('updated_at')->nullable()->useCurrentOnUpdate()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('phone_verifications', function (Blueprint $table) {
            $table->json('user_data')->nullable(); // Store registration data temporarily
        });
    }
};
