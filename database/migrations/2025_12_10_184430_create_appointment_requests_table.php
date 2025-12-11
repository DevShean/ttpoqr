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
        Schema::create('appointment_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('availability_id')->constrained('availabilities')->cascadeOnDelete();
            $table->enum('purpose', [
                'Travel Permit',
                'NBI Certificate',
                'Submit Clearance',
                'Conferencing',
                'Application on Parole',
                'Application on Probation'
            ]);
            $table->time('appointment_time')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('rejection_reason')->nullable();
            $table->timestamp('rejected_at')->nullable();
            $table->timestamps();
            $table->boolean('is_archived')->default(false);

            $table->unique(['user_id', 'availability_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointment_requests');
    }
};
