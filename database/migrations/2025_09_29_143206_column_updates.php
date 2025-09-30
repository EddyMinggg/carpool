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
        Schema::table('trips', function (Blueprint $table) {
            $table->renameColumn('pickup_location', 'start_location');
            $table->dropColumn('actual_departure_time');
            $table->enum('trip_status', [
                'awaiting',
                'departed',
                'charging',
                'completed',
                'cancelled'
            ])->default('awaiting')->change();
        });

        Schema::table('trip_joins', function (Blueprint $table) {
            $table->dropColumn('vote_info');
            $table->dropColumn('join_role');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('trips', function (Blueprint $table) {
            $table->renameColumn('start_location', 'pickup_location');
            $table->timestamp('actual_departure_time')->nullable()->after('planned_departure_time');
            $table->enum('trip_status', [
                'awaiting',
                'departed',
                'completed',
                'cancelled'
            ])->default('awaiting')->change();
        });

        Schema::table('trip_joins', function (Blueprint $table) {
            $table->json('vote_info')->nullable();
            $table->enum('join_role', ['creator', 'normal']);
        });
    }
};
