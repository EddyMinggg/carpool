<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Ensure users.phone has unique constraint (required for trip_joins relationship)
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'phone')) {
                $table->string('phone', 20)->unique()->after('email');
            } else {
                // Add unique constraint if it doesn't exist
                try {
                    $table->unique('phone');
                } catch (\Exception $e) {
                    // Unique constraint might already exist, ignore error
                }
            }
        });

        Schema::create('trip_joins', function (Blueprint $table) {
            $table->id('id');
            
            $table->foreignId('trip_id')->constrained('trips', 'id')
                ->cascadeOnDelete();
            
            // Use phone instead of user_id for identification
            $table->string('user_phone', 20)->nullable();
            
            // Ensure one phone number per trip
            $table->unique(['trip_id', 'user_phone']);

            // 参与信息
            $table->timestamp('join_time')->useCurrent();
            $table->decimal('user_fee', 8, 2)->nullable();
            // 新增：上車地點（文字地址）
            $table->string('pickup_location', 100)->nullable();
            // Payment confirmation
            $table->boolean('payment_confirmation')->default(false);

            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable()->useCurrentOnUpdate();
        });
    }

    public function down()
    {
        Schema::dropIfExists('trip_joins');
        
        Schema::table('users', function (Blueprint $table) {
            // Remove unique constraint on phone if we added it
            try {
                $table->dropUnique(['phone']);
            } catch (\Exception $e) {
                // Constraint might not exist or be named differently
            }
        });
    }
};
