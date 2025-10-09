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
        Schema::create('coupon_usages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('coupon_id')->constrained('coupons')->onDelete('cascade');
            $table->string('user_phone', 20); // 使用電話號碼來識別用戶
            $table->foreignId('payment_id')->nullable()->constrained('payments')->onDelete('set null');
            $table->decimal('discount_amount', 8, 2);
            $table->timestamps();
            
            // 防止同一個付款記錄重複使用同一個 coupon
            $table->unique(['coupon_id', 'payment_id']);
            
            // 索引優化查詢性能
            $table->index(['coupon_id', 'user_phone']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coupon_usages');
    }
};
