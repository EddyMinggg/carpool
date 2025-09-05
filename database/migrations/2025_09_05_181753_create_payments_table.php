<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id('payment_id')->comment('支付ID');
            $table->foreignId('trip_id')->constrained('trips', 'trip_id')
                  ->cascadeOnDelete()
                  ->comment('行程ID（关联trips表）');
            $table->foreignId('user_id')->constrained('users', 'id')
                  ->cascadeOnDelete()
                  ->comment('用户ID（关联users表）');
            $table->decimal('payment_amount', 8, 2)->comment('支付金额');
            $table->enum('payment_status', ['unpaid', 'paid', 'refunded'])->default('unpaid')->comment('支付状态');
            $table->string('payment_method', 50)->nullable()->comment('支付方式（如转数快/PayMe）');
            $table->timestamp('payment_time')->nullable()->comment('支付时间');
            
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('payments');
    }
};
