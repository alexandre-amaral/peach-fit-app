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
        Schema::create('training_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained('customers')->onDelete('cascade'); 
            $table->foreignId('personal_id')->constrained('personal_trainers')->onDelete('cascade'); 
            $table->string('status')->default('pending'); 
            $table->timestamp('proposed_datetime')->nullable();
            $table->timestamp('confirmed_datetime')->nullable(); 
            $table->string('location')->nullable(); 
            $table->string('payment_status')->default('pending'); 
            $table->decimal('duration', 8, 2)->nullable();
            $table->decimal('total_price', 10, 2);
            $table->timestamps();

        });
      
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('training_sessions');
    }
};
