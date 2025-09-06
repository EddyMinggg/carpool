<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('trip_joins', function (Blueprint $table) {
            // 联合主键：确保一个用户在一个行程中只能参与一次
            $table->foreignId('trip_id')->constrained('trips', 'trip_id')
                  ->cascadeOnDelete()
                  ->comment('行程ID（关联行程表）');
            $table->foreignId('user_id')->constrained('users', 'user_id')
                  ->cascadeOnDelete()
                  ->comment('用户ID（关联用户表）');
            $table->primary(['trip_id', 'user_id']);

            // 参与信息
            $table->enum('join_role', ['creator', 'normal'])->comment('参与角色：creator=创建者，normal=普通拼友');
            $table->timestamp('join_time')->useCurrent()->comment('加入行程时间');
            $table->decimal('user_fee', 8, 2)->comment('用户应付费用（元）');

            // 投票信息（JSON格式，未投票为null）
            $table->json('vote_info')->nullable()->comment('投票信息：{"vote_result":"agree/disagree", "vote_time":"时间戳", "vote_comment":"备注"}');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('trip_joins');
    }
};
    