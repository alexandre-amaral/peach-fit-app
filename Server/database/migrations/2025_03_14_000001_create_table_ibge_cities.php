<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('ibge_cities', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ibge_state_id');
            $table->bigInteger('ibge_id')->unique();
            $table->string('name');
            $table->timestamps();
            $table->foreign('ibge_state_id')->references('ibge_id')->on('ibge_states');
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ibge_cities');
    }
};
