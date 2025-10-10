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
        // 先更新現有數據，然後修改列結構
        DB::table('trip_drivers')->where('status', 'completed')->update(['status' => 'confirmed']);
        DB::table('trip_drivers')->where('status', 'cancelled')->delete(); // 刪除已取消的分配記錄
        
        Schema::table('trip_drivers', function (Blueprint $table) {
            // 簡化狀態，移除與 trips 表重複的狀態
            $table->enum('status', ['assigned', 'confirmed'])->default('assigned')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('trip_drivers', function (Blueprint $table) {
            // 恢復原狀態
            $table->enum('status', ['assigned', 'confirmed', 'completed', 'cancelled'])->default('assigned')->change();
        });
    }
};