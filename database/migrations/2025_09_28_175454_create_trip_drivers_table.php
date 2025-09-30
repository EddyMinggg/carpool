<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('trip_drivers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('trip_id')->constrained('trips', 'id')->cascadeOnDelete();
            $table->foreignId('driver_id')->constrained('users', 'id')->cascadeOnDelete();
            $table->enum('status', ['assigned', 'confirmed', 'completed', 'cancelled'])->default('assigned');
            $table->text('notes')->nullable(); // 司機可以添加備注
            $table->timestamp('assigned_at')->useCurrent();
            $table->timestamp('confirmed_at')->nullable(); // 司機確認接單時間
            $table->timestamps();
            
            // 確保一個行程只能有一個司機
            $table->unique('trip_id');
            // 防止同一司機在同一時間接多個重疊的行程
            $table->index(['driver_id', 'assigned_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trip_drivers');
    }
};
