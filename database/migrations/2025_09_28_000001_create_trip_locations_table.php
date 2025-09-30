<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('trip_locations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('trip_id')->constrained()->onDelete('cascade');
            $table->foreignId('driver_id')->constrained('users')->onDelete('cascade');
            $table->decimal('latitude', 10, 8);
            $table->decimal('longitude', 11, 8);
            $table->decimal('accuracy', 8, 2)->nullable(); // GPS 準確度 (米)
            $table->decimal('speed', 8, 2)->nullable(); // 速度 (km/h)
            $table->integer('heading')->nullable(); // 方向 (0-359度)
            $table->timestamp('recorded_at');
            $table->timestamps();
            
            // 索引優化
            $table->index(['trip_id', 'recorded_at']);
            $table->index(['driver_id', 'recorded_at']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('trip_locations');
    }
};
