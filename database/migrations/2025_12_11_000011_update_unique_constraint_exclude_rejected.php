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
        // Use raw SQL to handle the constraint removal
        // First disable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        
        // Drop the unique constraint
        DB::statement('ALTER TABLE appointment_requests DROP INDEX appointment_requests_user_id_availability_id_unique');
        
        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        
        Schema::table('appointment_requests', function (Blueprint $table) {
            $table->unique(['user_id', 'availability_id']);
        });
        
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
};
