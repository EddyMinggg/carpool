<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('trip_status_logs', function (Blueprint $table) {
            $table->id('log_id')->comment('日志ID');
            $table->foreignId('trip_id')->constrained('trips', 'trip_id')
                  ->cascadeOnDelete()
                  ->comment('行程ID（关联trips表）');
            $table->string('old_status', 20)->comment('变更前状态');
            $table->string('new_status', 20)->comment('变更后状态');
            $table->foreignId('operate_user_id')->constrained('users', 'id')
                  ->nullable()
                  ->comment('操作人ID（关联users表，系统操作可为null）');
            $table->timestamp('operate_time')->useCurrent()->comment('操作时间');
            $table->text('remark')->comment('变更原因');
            
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('trip_status_logs');
    }
};
