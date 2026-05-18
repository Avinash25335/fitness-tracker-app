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
        Schema::table('workout_sessions', function (Blueprint $table) {
            $table->timestamp('started_at')->nullable()->after('day_number');
            $table->integer('duration')->default(0)->after('completed_at'); // in seconds
            $table->integer('calories_burned')->default(0)->after('duration');
        });
    }

    public function down(): void
    {
        Schema::table('workout_sessions', function (Blueprint $table) {
            $table->dropColumn(['started_at', 'duration', 'calories_burned']);
        });
    }
};
