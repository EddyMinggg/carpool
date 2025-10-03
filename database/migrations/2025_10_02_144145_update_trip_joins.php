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
        // Adding unique key contraint for the new foreign key
        Schema::table('users', function (Blueprint $table) {
            $table->unique('phone');
        });

        Schema::table('trip_joins', function (Blueprint $table) {
            // Drop foreign key constraints in order to drop the unique key
            $table->dropForeign('trip_joins_user_id_foreign');
            $table->dropForeign('trip_joins_trip_id_foreign');

            // Drop unique key constraints in order to drop `user_id`
            $table->dropUnique(['trip_id', 'user_id']);

            $table->dropColumn(['user_id']);

            // Restore `trip_id` foreign key and create new unique key with `phone`
            $table->foreign('trip_id')->references('id')->on('trips')->onDelete('cascade');
            $table->string('user_phone', 20)->nullable()->after('trip_id');
            $table->unique(['trip_id', 'user_phone']);
        });

        // Adding invitation code for trip
        Schema::table('trips', function (Blueprint $table) {
            $table->string('invitation_code', 8)->unique()->after('type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique('users_phone_unique');
        });

        Schema::table('trip_joins', function (Blueprint $table) {
            // Drop foreign key constraints in order to drop the unique key
            $table->dropForeign(['trip_id']);

            // Drop unique key constraint
            $table->dropUnique(['trip_id', 'user_phone']);

            // Drop phone column
            $table->dropColumn('user_phone');

            // Restore user_id column and recreate original constraints
            $table->unsignedBigInteger('user_id')->after('trip_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('trip_id')->references('id')->on('trips')->onDelete('cascade');
            $table->unique(['trip_id', 'user_id']);
        });

        Schema::table('trips', function (Blueprint $table) {
            $table->dropColumn('invitation_code');
        });
    }
};
