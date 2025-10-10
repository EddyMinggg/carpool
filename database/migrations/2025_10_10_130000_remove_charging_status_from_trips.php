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
        // 先將現有的 charging 狀態更新為 departed
        DB::table('trips')->where('trip_status', 'charging')->update(['trip_status' => 'departed']);
        
        Schema::table('trips', function (Blueprint $table) {
            // 簡化狀態流程：awaiting -> departed -> completed
            $table->enum('trip_status', ['awaiting', 'departed', 'completed', 'cancelled'])->default('awaiting')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('trips', function (Blueprint $table) {
            // 恢復原狀態
            $table->enum('trip_status', ['awaiting', 'departed', 'charging', 'completed', 'cancelled'])->default('awaiting')->change();
        });
    }
};