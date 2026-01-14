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
        Schema::create('admin_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('admin_id');
            $table->string('action')->comment('e.g., approved_appointment, rejected_appointment, scheduled_availability');
            $table->string('action_type')->comment('approved, rejected, scheduled, created, updated, deleted, archived');
            $table->text('description')->nullable();
            $table->string('related_model')->nullable()->comment('Model name affected (AppointmentRequest, Availability, etc.)');
            $table->unsignedBigInteger('related_id')->nullable()->comment('ID of affected record');
            $table->string('ip_address')->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamps();

            $table->foreign('admin_id')->references('id')->on('users')->onDelete('cascade');
            $table->index('admin_id');
            $table->index('action_type');
            $table->index('created_at');
            $table->index('related_model');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admin_logs');
    }
};
