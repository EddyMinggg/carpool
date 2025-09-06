<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id('user_id')->comment('用户唯一ID'); // 自定义主键名（与之前设计一致）
            $table->string('username', 50)->unique()->comment('登录账号（手机号/邮箱）');
            $table->string('password')->comment('加密密码');
            $table->string('phone', 20)->nullable()->comment('手机号');
            $table->tinyInteger('role')->default(0)->comment('角色：0=普通用户，1=管理员'); // 合并角色字段
            $table->rememberToken()->comment('记住登录状态的令牌');
            $table->softDeletes()->comment('软删除时间戳'); // 支持软删除
            $table->timestamps(); // 默认包含 created_at 和 updated_at
        });
    }

    public function down()
    {
        Schema::dropIfExists('users');
    }
};
