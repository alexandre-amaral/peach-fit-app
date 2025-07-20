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
         Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->morphs('payable');
            $table->decimal('amount', 10, 2);
            $table->string('currency', 3);
            $table->decimal('fee_amount', 10, 2)->nullable();
            $table->string('description')->nullable();
            $table->string('paypal_order_id')->nullable()->unique();
            $table->string('paypal_capture_id')->nullable()->unique();
            $table->string('paypal_payment_id')->nullable()->unique(); 
            $table->string('status');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};