<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id('user_id')->comment('User unique ID');
            $table->string('username', 50)->unique()->comment('Display name / username');
            $table->string('email')->unique()->comment('Email for login (required by Breeze)');
            $table->timestamp('email_verified_at')->nullable()->comment('Email verification timestamp');
            $table->string('password')->comment('Hashed password');
            $table->string('phone', 20)->nullable()->comment('Phone number');
            $table->tinyInteger('role')->default(0)->comment('Role: 0=user, 1=admin');
            $table->rememberToken()->comment('Remember login token');
            $table->softDeletes()->comment('Soft delete timestamp');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('users');
    }
};
