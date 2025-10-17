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
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['email_verified_at']);
        });

        // Give the moving column a temporary name:
        Schema::table('users', function ($table) {
            $table->renameColumn('phone_verified_at', 'phone_verified_at_old');
        });

        // Add a new column with the regular name:
        Schema::table('users', function (Blueprint $table) {
            $table->timestamp('phone_verified_at')->nullable()->after('phone');
        });

        // Copy the data across to the new column:
        DB::table('users')->update([
            'phone_verified_at' => DB::raw('phone_verified_at_old')
        ]);

        // Remove the old column:
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('phone_verified_at_old');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->timestamp('email_verified_at')->nullable()->after('email');
        });
    }
};
