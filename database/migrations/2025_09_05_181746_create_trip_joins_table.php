<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('trip_joins', function (Blueprint $table) {
            $table->id('id');
            // 联合主键：确保一个用户在一个行程中只能参与一次

            $table->foreignId('trip_id')->constrained('trips', 'id')
                ->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users', 'id')
                ->cascadeOnDelete();

            $table->unique(['trip_id', 'user_id']);
            
            // 外鍵約束 - 確認支付的管理員
            $table->foreign('confirmed_by')->references('id')->on('users')->onDelete('set null');

            // 参与信息
            $table->enum('join_role', ['creator', 'normal']);
            $table->timestamp('join_time')->useCurrent();
            $table->decimal('user_fee', 8, 2)->nullable();
            // 新增：上車地點（文字地址）
            $table->string('pickup_location', 100)->nullable();
            
            // 支付確認相關字段
            $table->string('reference_code')->nullable();
            $table->boolean('payment_confirmed')->default(false);
            $table->timestamp('payment_confirmed_at')->nullable();
            $table->unsignedBigInteger('confirmed_by')->nullable();

            // 投票信息（JSON格式，未投票为null）
            $table->json('vote_info')->nullable();

            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable()->useCurrentOnUpdate();
        });
    }

    public function down()
    {
        Schema::dropIfExists('trip_joins');
    }
};
