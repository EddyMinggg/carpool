<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('trip_joins', function (Blueprint $table) {
            // 联合主键（1个用户在1个行程中只能参与1次）
            $table->foreignId('trip_id')->constrained('trips', 'trip_id')
                  ->cascadeOnDelete()
                  ->comment('行程ID（关联trips表）');
            $table->foreignId('user_id')->constrained('users', 'id')
                  ->cascadeOnDelete()
                  ->comment('用户ID（关联users表）');
            
            $table->enum('join_role', ['creator', 'passenger'])->comment('参与角色（创建者/普通乘客）');
            $table->timestamp('join_time')->useCurrent()->comment('加入时间');
            $table->decimal('user_fee', 8, 2)->comment('用户应付费用');
            
            $table->primary(['trip_id', 'user_id']); // 联合主键
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('trip_joins');
    }
};
