<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('trips', function (Blueprint $table) {
            $table->id('id');
            $table->foreignId('creator_id')->constrained('users', 'id')
                ->cascadeOnDelete();
            $table->string('pickup_location', 100)->nullable();
            $table->string('dropoff_location', 100);
            $table->timestamp('planned_departure_time')->nullable();
            $table->integer('max_people');
            $table->integer('base_price');
            $table->enum('trip_status', [
                'awaiting',
                'departed',
                'completed',
                'cancelled'
            ])->default('awaiting');
            $table->softDeletes();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable()->useCurrentOnUpdate();
        });
    }

    public function down()
    {
        Schema::dropIfExists('trips');
    }
};
