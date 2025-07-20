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
       
        Schema::create('payout_personals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('personal_id')->constrained('personal_trainers')->onDelete('cascade');
            $table->string('paypal_batch_id')->unique()->nullable(); 
            $table->string('status'); 
            $table->decimal('total_amount', 10, 2);
            $table->string('currency', 3)->default('BRL');
            $table->json('items_sent');
            $table->json('paypal_response')->nullable(); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payouts_personal');
    }
};
