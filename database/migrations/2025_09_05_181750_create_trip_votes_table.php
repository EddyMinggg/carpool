<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('trip_votes', function (Blueprint $table) {
            $table->id('vote_id')->comment('投票ID');
            $table->foreignId('trip_id')->constrained('trips', 'trip_id')
                  ->cascadeOnDelete()
                  ->comment('行程ID（关联trips表）');
            $table->foreignId('user_id')->constrained('users', 'id')
                  ->cascadeOnDelete()
                  ->comment('用户ID（关联users表）');
            $table->enum('vote_result', ['agree', 'disagree'])->comment('投票结果（同意/不同意）');
            $table->timestamp('vote_time')->useCurrent()->comment('投票时间');
            $table->text('vote_comment')->nullable()->comment('投票备注');
            
            // 确保1个用户在1个行程中只能投1次票
            $table->unique(['trip_id', 'user_id']);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('trip_votes');
    }
};
