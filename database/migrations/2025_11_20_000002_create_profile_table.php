<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('profile', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->string('fname', 100);
            $table->string('mname', 100);
            $table->string('lname', 100);
            $table->string('contactnum', 100);
            $table->string('address')->nullable();
            $table->string('city', 100)->nullable();
            $table->string('state', 100)->nullable();
            $table->string('zip', 20)->nullable();
            $table->enum('civil_status', ['Single','Married','Widowed','Annulled','Legally Separated'])->nullable();
            $table->enum('gender', ['Male','Female','Other'])->nullable();
            $table->string('avatar_path')->nullable();
            $table->timestamp('profile_created')->useCurrent();

            $table->index('user_id', 'login_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('profile');
    }
};
