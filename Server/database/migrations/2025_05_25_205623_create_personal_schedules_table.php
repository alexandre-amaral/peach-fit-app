<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
    * Run the migrations.
    */
    
    public function up()
    {
        Schema::create('personal_schedules', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('personal_id');
            $table->dateTime('datetime');
            $table->enum('status', ['free', 'blocked', 'taken', 'canceled'])->default('free');
            $table->unsignedBigInteger('training_session_id')->nullable();
            $table->timestamps();

            $table->foreign('personal_id')->references('id')->on('personal_trainers')->onDelete('cascade');
            $table->foreign('training_session_id')->references('id')->on('training_sessions')->onDelete('set null');

            $table->unique(['personal_id', 'datetime']);
        });
    }

    /**
    * Reverse the migrations.
    */
    public function down()
    {
        Schema::dropIfExists('personal_schedules');
    }
};
