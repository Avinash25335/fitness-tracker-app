<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Table for tracking user's active workout programs
        Schema::create('user_workouts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('workout_plan_id')->constrained()->cascadeOnDelete();
            $table->string('status')->default('in_progress'); // in_progress, completed
            $table->integer('progress')->default(0); // 0-100%
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });

        // Table for tracking completion of specific exercises within a workout
        Schema::create('user_exercise_progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('exercise_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_workout_id')->constrained()->cascadeOnDelete();
            $table->boolean('completed')->default(true);
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();

            // Ensure a user can only complete an exercise once for a specific workout instance
            $table->unique(['user_id', 'exercise_id', 'user_workout_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_exercise_progress');
        Schema::dropIfExists('user_workouts');
    }
};
