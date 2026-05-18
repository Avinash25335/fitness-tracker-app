<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('exercises', function (Blueprint $table) {
            $table->text('instructions')->nullable()->after('name');
            $table->string('target_muscles')->nullable()->after('instructions');
            $table->string('form_cues')->nullable()->after('target_muscles');
        });
    }

    public function down(): void
    {
        Schema::table('exercises', function (Blueprint $table) {
            $table->dropColumn(['instructions', 'target_muscles', 'form_cues']);
        });
    }
};
