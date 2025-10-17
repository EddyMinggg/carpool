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
        Schema::table('payments', function (Blueprint $table) {
            // 擴展 type 欄位以支持團體預訂類型
            $table->enum('type', ['deposit', 'remaining', 'full_payment', 'group_full_payment', 'group_member_payment'])->default('full_payment')->change();
            
            // 添加團體預訂相關欄位
            $table->integer('group_size')->nullable()->after('type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            // 恢復原始 type 欄位
            $table->enum('type', ['deposit', 'remaining'])->default('deposit')->change();
            
            // 移除添加的欄位
            $table->dropForeign(['parent_payment_id']);
            $table->dropColumn(['group_size', 'parent_payment_id']);
        });
    }
};
