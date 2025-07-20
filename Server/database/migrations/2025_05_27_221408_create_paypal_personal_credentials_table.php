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
        Schema::create('personal_paypal_credentials', function (Blueprint $table) {
            $table->id();
            $table->foreignId('personal_id')->constrained('personal_trainers')->onDelete('cascade');
            $table->text('payment_email');
            $table->timestamps();

            $table->unique('personal_id'); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('paypal_personal_credentials');
    }
};
