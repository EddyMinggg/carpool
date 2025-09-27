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
        Schema::create('phone_verifications', function (Blueprint $table) {
            $table->id();
            $table->string('phone', 20); // Full phone number with country code
            $table->string('otp_code', 6); // 6-digit verification code
            $table->timestamp('expires_at'); // OTP expiry time (5 minutes from creation)
            $table->boolean('is_verified')->default(false);
            $table->string('ip_address', 45)->nullable(); // Store IP for security
            $table->integer('attempts')->default(0); // Track verification attempts
            $table->json('user_data')->nullable(); // Store registration data temporarily
            $table->timestamps();
            
            // Indexes for performance
            $table->index(['phone', 'otp_code']);
            $table->index(['phone', 'is_verified']);
            $table->index('expires_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('phone_verifications');
    }
};