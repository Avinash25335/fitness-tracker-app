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
        // 1. user_plans
        Schema::create('user_plans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('plan_id')->constrained('workout_plans')->cascadeOnDelete();
            $table->date('start_date');
            $table->integer('current_day')->default(1);
            $table->boolean('is_completed')->default(false);
            $table->timestamps();
        });

        // 2. workout_sessions
        Schema::create('workout_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_plan_id')->constrained('user_plans')->cascadeOnDelete();
            $table->integer('day_number');
            $table->boolean('completed')->default(false);
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });

        // 3. exercise_logs
        Schema::create('exercise_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('session_id')->constrained('workout_sessions')->cascadeOnDelete();
            $table->foreignId('exercise_id')->constrained()->cascadeOnDelete();
            $table->integer('sets_completed')->default(0);
            $table->integer('reps_completed')->default(0);
            $table->float('weight')->nullable();
            $table->boolean('completed')->default(false);
            $table->timestamps();
        });

        // 4. user_stats
        Schema::create('user_stats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->integer('streak')->default(0);
            $table->integer('total_workouts')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_stats');
        Schema::dropIfExists('exercise_logs');
        Schema::dropIfExists('workout_sessions');
        Schema::dropIfExists('user_plans');
    }
};
