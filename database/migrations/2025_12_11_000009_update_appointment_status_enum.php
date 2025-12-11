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
        // Modify the status column to include 'rejected' instead of 'denied'
        Schema::table('appointment_requests', function (Blueprint $table) {
            $table->enum('status', ['pending', 'approved', 'rejected'])->change()->default('pending');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('appointment_requests', function (Blueprint $table) {
            $table->enum('status', ['pending', 'approved', 'denied'])->change()->default('pending');
        });
    }
};
