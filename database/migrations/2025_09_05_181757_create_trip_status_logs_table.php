<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('trip_status_logs', function (Blueprint $table) {
            $table->id('log_id');
            $table->foreignId('trip_id')->constrained('trips', 'id')
                  ->cascadeOnDelete();
            $table->string('old_status', 20);
            $table->string('new_status', 20);
            $table->foreignId('operate_user_id')->constrained('users', 'id')
                  ->nullable();
            $table->timestamp('operate_time')->useCurrent();
            $table->text('remark');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('trip_status_logs');
    }
};
    