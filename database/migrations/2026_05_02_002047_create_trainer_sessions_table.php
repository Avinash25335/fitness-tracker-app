<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('trainer_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('trainer_id')->constrained()->onDelete('cascade');
            $table->date('session_date');
            $table->time('session_time');
            $table->enum('status', ['booked', 'completed', 'cancelled'])->default('booked');
            $table->timestamps();

            // 🔥 Prevent duplicate bookings at the database level
            $table->unique(['trainer_id', 'session_date', 'session_time']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('trainer_sessions');
    }
};
