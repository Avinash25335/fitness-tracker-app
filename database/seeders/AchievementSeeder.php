<?php

namespace Database\Seeders;

use App\Models\Achievement;
use Illuminate\Database\Seeder;

class AchievementSeeder extends Seeder
{
    public function run(): void
    {
        $achievements = [
            [
                'title' => 'First Step',
                'condition' => 'workouts_1',
                'icon' => '👟'
            ],
            [
                'title' => '7 Day Warrior',
                'condition' => 'streak_7',
                'icon' => '🔥'
            ],
            [
                'title' => 'Consistency King',
                'condition' => 'workouts_10',
                'icon' => '👑'
            ],
            [
                'title' => 'Calorie Crusher',
                'condition' => 'calories_1000',
                'icon' => '🔥'
            ],
            [
                'title' => 'Elite Athlete',
                'condition' => 'elite_athlete',
                'icon' => '🏅'
            ],
        ];

        foreach ($achievements as $a) {
            Achievement::updateOrCreate(['condition' => $a['condition']], $a);
        }
    }
}
