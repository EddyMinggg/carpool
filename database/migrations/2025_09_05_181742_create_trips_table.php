<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('trips', function (Blueprint $table) {
            $table->id('trip_id')->comment('行程唯一标识');
            $table->foreignId('creator_id')->constrained('users', 'user_id')
                  ->cascadeOnDelete()
                  ->comment('行程创建者ID（关联用户表）');
            $table->string('start_place', 100)->comment('出发地点');
            $table->string('end_place', 100)->comment('目的地点');
            $table->timestamp('plan_departure_time')->comment('计划发车时间');
            $table->timestamp('actual_departure_time')->nullable()->comment('实际发车时间');
            $table->integer('max_people')->default(4)->comment('最大拼车人数（默认4人）');
            $table->boolean('is_private')->default(false)->comment('是否独享：true=独享，false=可拼单');
            $table->enum('trip_status', [
                'pending',    // 待拼中
                'voting',     // 投票中
                'departed',   // 已发车
                'completed',  // 已完成
                'cancelled'   // 已取消
            ])->default('pending')->comment('行程状态');
            $table->softDeletes()->comment('软删除时间戳');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('trips');
    }
};
    