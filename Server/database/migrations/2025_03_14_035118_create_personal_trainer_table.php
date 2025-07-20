<?php

use App\Models\IbgeCity;
use App\Models\IbgeState;
use App\Models\User;
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
        Schema::create('personal_trainers', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class)->unique();
            $table->string('cpf')->unique();
            $table->string('speciality');
            $table->string('tel');
            $table->enum('gender', ['male', 'female', 'other'])->default('other');
            $table->decimal('hourly_rate')->default(0);
            $table->integer('state_id');
            $table->foreignIdFor(IbgeState::class);
            $table->foreignIdFor(IbgeCity::class);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('personal_trainers');
    }
};
