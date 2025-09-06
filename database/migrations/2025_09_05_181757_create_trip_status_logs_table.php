<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('trip_status_logs', function (Blueprint $table) {
            $table->id('log_id')->comment('日志唯一标识');
            $table->foreignId('trip_id')->constrained('trips', 'trip_id')
                  ->cascadeOnDelete()
                  ->comment('行程ID（关联行程表）');
            $table->string('old_status', 20)->comment('变更前状态');
            $table->string('new_status', 20)->comment('变更后状态');
            $table->foreignId('operate_user_id')->constrained('users', 'user_id')
                  ->nullable()
                  ->comment('操作人ID（关联用户表，系统操作可为null）');
            $table->timestamp('operate_time')->useCurrent()->comment('状态变更时间');
            $table->text('remark')->comment('状态变更原因（如：投票通过2/3同意）');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('trip_status_logs');
    }
};
    