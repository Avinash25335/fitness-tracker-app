<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('trainers', function (Blueprint $table) {
            $table->integer('experience')->nullable();
        });

        Schema::table('exercises', function (Blueprint $table) {
            $table->string('body_part')->nullable();
        });

        Schema::table('diet_plans', function (Blueprint $table) {
            $table->json('meals_json')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('trainers', function (Blueprint $table) {
            $table->dropColumn('experience');
        });

        Schema::table('exercises', function (Blueprint $table) {
            $table->dropColumn('body_part');
        });

        Schema::table('diet_plans', function (Blueprint $table) {
            $table->dropColumn('meals_json');
        });
    }
};
