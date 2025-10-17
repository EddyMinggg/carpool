<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // First, add temporary enum values to allow data migration
        Schema::table('payments', function (Blueprint $table) {
            $table->enum('type', ['deposit', 'remaining', 'full_payment', 'group_full_payment', 'group_member_payment', 'individual', 'group'])->default('full_payment')->change();
        });
        
        // Update existing data to use new type values
        DB::statement("UPDATE payments SET type = 'individual' WHERE type IN ('deposit', 'remaining', 'full_payment')");
        DB::statement("UPDATE payments SET type = 'group' WHERE type IN ('group_full_payment', 'group_member_payment')");
        
        // Finally, simplify to only the new enum values
        Schema::table('payments', function (Blueprint $table) {
            $table->enum('type', ['individual', 'group'])->default('individual')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            // 恢復到之前的複雜類型
            $table->enum('type', ['deposit', 'remaining', 'full_payment', 'group_full_payment', 'group_member_payment'])->default('full_payment')->change();
        });
        
        // Restore original data (best effort)
        DB::statement("UPDATE payments SET type = 'full_payment' WHERE type = 'individual'");
        DB::statement("UPDATE payments SET type = 'group_full_payment' WHERE type = 'group'");
    }
};