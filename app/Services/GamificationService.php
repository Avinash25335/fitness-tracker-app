<?php

namespace App\Services;

use App\Models\User;
use App\Models\Achievement;
use App\Models\UserStat;
use Illuminate\Support\Facades\Notification;
use App\Notifications\AchievementUnlocked;

class GamificationService
{
    /**
     * Award XP to user and check for level ups/achievements
     */
    public function awardXp(User $user, int $amount, string $reason = 'Workout Completed')
    {
        $stats = UserStat::firstOrCreate(['user_id' => $user->id]);
        $stats->xp += $amount;
        
        $leveledUp = $stats->syncLevel();
        $stats->save();

        $newAchievements = $this->checkAchievements($user);

        return [
            'xp_gained' => $amount,
            'new_total_xp' => $stats->xp,
            'leveled_up' => $leveledUp,
            'new_level' => $stats->level,
            'new_achievements' => $newAchievements
        ];
    }

    /**
     * Check if user qualified for any new achievements
     */
    public function checkAchievements(User $user)
    {
        $stats = $user->stat;
        $allAchievements = Achievement::all();
        $unlocked = [];

        foreach ($allAchievements as $achievement) {
            // Skip if already unlocked
            if ($user->achievements()->where('achievement_id', $achievement->id)->exists()) {
                continue;
            }

            $qualified = false;
            $condition = $achievement->condition;

            // Simple condition parser
            if (str_starts_with($condition, 'streak_')) {
                $required = (int) str_replace('streak_', '', $condition);
                if ($stats->streak >= $required) $qualified = true;
            } 
            elseif (str_starts_with($condition, 'workouts_')) {
                $required = (int) str_replace('workouts_', '', $condition);
                if ($stats->total_workouts >= $required) $qualified = true;
            }
            elseif (str_starts_with($condition, 'calories_')) {
                $required = (int) str_replace('calories_', '', $condition);
                $totalCalories = $user->workoutLogs()->sum('calories_burned');
                if ($totalCalories >= $required) $qualified = true;
            }
            elseif ($condition === 'elite_athlete' && $stats->level >= 10) {
                $qualified = true;
            }

            if ($qualified) {
                $user->achievements()->attach($achievement->id);
                $unlocked[] = $achievement;
                
                // Trigger Notification
                $user->notify(new AchievementUnlocked($achievement));
            }
        }

        return $unlocked;
    }

    /**
     * Get Leaderboard data
     */
    public function getLeaderboard()
    {
        return UserStat::with('user')
            ->orderBy('xp', 'desc')
            ->limit(10)
            ->get();
    }
}
