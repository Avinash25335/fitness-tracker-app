<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('diet_plans', function (Blueprint $table) {
            $table->integer('protein')->default(30)->after('daily_calories');
            $table->integer('carbs')->default(40)->after('protein');
            $table->integer('fats')->default(30)->after('carbs');
        });
    }

    public function down(): void
    {
        Schema::table('diet_plans', function (Blueprint $table) {
            $table->dropColumn(['protein', 'carbs', 'fats']);
        });
    }
};
