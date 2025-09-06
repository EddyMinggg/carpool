<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Hash;

return new class extends Migration
{
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id('user_id')->comment('用户唯一标识');
            $table->string('username', 50)->unique()->comment('登录账号（手机号/邮箱）');
            $table->string('password')->comment('加密存储的密码');
            $table->string('phone', 20)->unique()->comment('用户手机号（用于联系）');
            $table->string('name', 50)->nullable()->comment('用户昵称');
            $table->timestamp('register_time')->useCurrent()->comment('注册时间');
            $table->softDeletes()->comment('软删除时间戳');
            $table->string('email', 319)->unique();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('users');
    }
};
