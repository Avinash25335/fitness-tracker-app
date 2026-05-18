<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('user_profiles', function (Blueprint $table) {
            $table->foreignId('following_diet_id')->nullable()->constrained('diet_plans')->nullOnDelete();
            $table->timestamp('following_diet_started_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('user_profiles', function (Blueprint $table) {
            $table->dropForeign(['following_diet_id']);
            $table->dropColumn(['following_diet_id', 'following_diet_started_at']);
        });
    }
};
