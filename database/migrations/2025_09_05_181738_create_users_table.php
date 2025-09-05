<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // 扩展Laravel默认的users表
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone', 20)->unique()->after('email')->comment('用户手机号');
            $table->timestamp('register_time')->useCurrent()->after('password')->comment('注册时间');
            $table->softDeletes()->comment('软删除标记');
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['phone', 'register_time', 'deleted_at']);
        });
    }
};
