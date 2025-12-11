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
        Schema::table('profile', function (Blueprint $table) {
            // Add foreign key constraint to users
            if (!Schema::hasColumn('profile', 'user_id')) {
                $table->unsignedBigInteger('user_id')->after('id');
            }
            
            // Change contactnum to varchar if it's integer
            try {
                $table->string('contactnum', 100)->change();
            } catch (\Exception $e) {
                // Column might already be varchar
            }
            
            // Add avatar_path if it doesn't exist
            if (!Schema::hasColumn('profile', 'avatar_path')) {
                $table->string('avatar_path')->nullable()->after('gender');
            }
        });

        // Add foreign key constraint
        Schema::table('profile', function (Blueprint $table) {
            if (!Schema::hasColumn('profile', 'user_id')) {
                return; // user_id already exists
            }
            try {
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            } catch (\Exception $e) {
                // Foreign key might already exist
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('profile', function (Blueprint $table) {
            // Drop foreign key if exists
            try {
                $table->dropForeign(['user_id']);
            } catch (\Exception $e) {
                // Foreign key doesn't exist
            }
            
            // Drop columns if they exist
            if (Schema::hasColumn('profile', 'avatar_path')) {
                $table->dropColumn('avatar_path');
            }
        });
    }
};
