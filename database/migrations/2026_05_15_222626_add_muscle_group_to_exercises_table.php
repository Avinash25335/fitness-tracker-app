<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('exercises', function (Blueprint $table) {
            $table->string('muscle_group')->nullable()->after('name');
        });

        // Seed muscle groups based on exercise name matching
        $mapping = [
            'chest'       => ['bench', 'press', 'fly', 'flye', 'push', 'dip', 'pec'],
            'back'        => ['row', 'deadlift', 'pull', 'lat', 'pulldown', 'chin', 'shrug', 'rdl'],
            'legs'        => ['squat', 'lunge', 'leg', 'calf', 'hamstring', 'quad', 'glute', 'hip thrust', 'step'],
            'shoulders'   => ['shoulder', 'overhead', 'ohp', 'lateral', 'front raise', 'upright', 'arnold'],
            'arms'        => ['curl', 'tricep', 'bicep', 'hammer', 'extension', 'kickback', 'preacher'],
            'core'        => ['plank', 'crunch', 'ab', 'sit up', 'russian', 'leg raise', 'oblique', 'cable twist'],
        ];

        $exercises = DB::table('exercises')->get();
        foreach ($exercises as $ex) {
            $lower = strtolower($ex->name);
            $group = null;
            foreach ($mapping as $muscle => $keywords) {
                foreach ($keywords as $kw) {
                    if (str_contains($lower, $kw)) {
                        $group = ucfirst($muscle);
                        break 2;
                    }
                }
            }
            if ($group) {
                DB::table('exercises')->where('id', $ex->id)->update(['muscle_group' => $group]);
            }
        }
    }

    public function down(): void
    {
        Schema::table('exercises', function (Blueprint $table) {
            $table->dropColumn('muscle_group');
        });
    }
};
