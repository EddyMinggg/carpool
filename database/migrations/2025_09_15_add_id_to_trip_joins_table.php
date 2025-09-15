<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('trip_joins', function (Blueprint $table) {
            // 先刪除複合主鍵
            $table->dropPrimary(['trip_id', 'user_id']);
            
            // 添加自增ID作為主鍵
            $table->id()->first();
            
            // 添加唯一約束來保證一個用戶只能加入一個行程一次
            $table->unique(['trip_id', 'user_id']);
        });
    }

    public function down()
    {
        Schema::table('trip_joins', function (Blueprint $table) {
            $table->dropUnique(['trip_id', 'user_id']);
            $table->dropColumn('id');
            $table->primary(['trip_id', 'user_id']);
        });
    }
};
