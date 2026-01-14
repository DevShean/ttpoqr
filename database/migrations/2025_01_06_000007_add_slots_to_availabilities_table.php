<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('availabilities', function (Blueprint $table) {
            $table->unsignedSmallInteger('slots')->nullable()->default(null)->comment('Number of available slots for this date');
        });
    }

    public function down(): void
    {
        Schema::table('availabilities', function (Blueprint $table) {
            $table->dropColumn('slots');
        });
    }
};
