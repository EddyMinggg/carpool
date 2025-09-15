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
            $table->foreignId('trip_id')->constrained('trips', 'id')
                  ->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users', 'id')
                  ->cascadeOnDelete();
            $table->primary(['trip_id', 'user_id']);

            // 参与信息
            $table->enum('join_role', ['creator', 'normal']);
            $table->timestamp('join_time')->useCurrent();
            $table->decimal('user_fee', 8, 2)->nullable();
            // 新增：上車地點（文字地址）
            $table->string('pickup_location', 100)->nullable();

            // 投票信息（JSON格式，未投票为null）
            $table->json('vote_info')->nullable();

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('trip_joins');
    }
};
    