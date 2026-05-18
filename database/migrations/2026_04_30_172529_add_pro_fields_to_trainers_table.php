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
        Schema::table('trainers', function (Blueprint $table) {
            if (!Schema::hasColumn('trainers', 'experience')) {
                $table->integer('experience')->default(1)->after('specialization');
            }
            if (!Schema::hasColumn('trainers', 'rating')) {
                $table->decimal('rating', 3, 1)->default(4.5)->after('experience');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('trainers', function (Blueprint $table) {
            if (Schema::hasColumn('trainers', 'experience')) {
                $table->dropColumn('experience');
            }
            if (Schema::hasColumn('trainers', 'rating')) {
                $table->dropColumn('rating');
            }
        });
    }
};
