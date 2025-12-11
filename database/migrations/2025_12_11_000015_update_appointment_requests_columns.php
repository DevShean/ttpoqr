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
        Schema::table('appointment_requests', function (Blueprint $table) {
            // Update status enum to include 'rejected'
            if (!Schema::hasColumn('appointment_requests', 'rejection_reason')) {
                $table->text('rejection_reason')->nullable()->after('status');
            }
            
            if (!Schema::hasColumn('appointment_requests', 'rejected_at')) {
                $table->timestamp('rejected_at')->nullable()->after('rejection_reason');
            }
            
            if (!Schema::hasColumn('appointment_requests', 'is_archived')) {
                $table->boolean('is_archived')->default(false)->after('updated_at');
            }
            
            if (!Schema::hasColumn('appointment_requests', 'appointment_time')) {
                $table->time('appointment_time')->nullable()->after('availability_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('appointment_requests', function (Blueprint $table) {
            if (Schema::hasColumn('appointment_requests', 'rejection_reason')) {
                $table->dropColumn('rejection_reason');
            }
            if (Schema::hasColumn('appointment_requests', 'rejected_at')) {
                $table->dropColumn('rejected_at');
            }
            if (Schema::hasColumn('appointment_requests', 'is_archived')) {
                $table->dropColumn('is_archived');
            }
            if (Schema::hasColumn('appointment_requests', 'appointment_time')) {
                $table->dropColumn('appointment_time');
            }
        });
    }
};
