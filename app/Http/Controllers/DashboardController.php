<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\RecommendationService;

class DashboardController extends Controller
{
    protected RecommendationService $recommendationService;

    public function __construct(RecommendationService $recommendationService)
    {
        $this->recommendationService = $recommendationService;
    }

    public function index()
    {
        $user = Auth::user()->load('profile');

        $profile = $user->profile;

        // ── BUG 1 FIX: Filter workout logs to CURRENT MONTH only for the stats card ──
        $workoutLogsThisMonth = $user->workoutLogs()
            ->whereMonth('date', now()->month)
            ->whereYear('date',  now()->year)
            ->count();

        // ── BUG 2 FIX: Eager-load workoutPlan so Recent Activity can show plan titles ──
        $recentWorkoutLogs = $user->workoutLogs()
            ->with('workoutPlan')
            ->orderBy('date', 'desc')
            ->take(5)
            ->get();

        // All progress logs (for the weight chart — oldest first)
        $progressLogs = $user->progressLogs()
            ->orderBy('log_date', 'asc')
            ->get();

        $recommendations = $this->recommendationService->getRecommendations($user);

        // ── Smart Reminders ──────────────────────────────────────────────────
        $upcomingSessions = \App\Models\TrainerSession::where('user_id', $user->id)
            ->where('status', 'booked')
            ->where('session_date', '>=', now()->toDateString())
            ->orderBy('session_date', 'asc')
            ->orderBy('time_slot', 'asc')
            ->take(3)
            ->get();

        $mealReminder = null;
        if ($profile && $profile->diet_plan_id) {
            $mealReminder = "Check your Diet Hub! Don't forget to log your " . 
                (now()->hour < 12 ? 'Breakfast' : (now()->hour < 17 ? 'Lunch' : 'Dinner'));
        }

        // --- Interactive System Additions ---
        $activePrograms = $user->userWorkouts()
            ->with('workoutPlan')
            ->where('status', 'in_progress')
            ->get();

        $completedProgramsCount = $user->userWorkouts()
            ->where('status', 'completed')
            ->count();

        // Data for weekly activity chart
        $weeklyActivity = $user->workoutLogs()
            ->where('date', '>=', now()->subDays(7))
            ->orderBy('date', 'asc')
            ->get()
            ->groupBy(function($log) {
                return \Carbon\Carbon::parse($log->date)->format('D');
            })->map->count();

        return view('dashboard.index', [
            'user'                   => $user,
            'profile'                => $profile,
            'workoutLogsThisMonth'   => $workoutLogsThisMonth,
            'recentWorkoutLogs'      => $recentWorkoutLogs,
            'progressLogs'           => $progressLogs,
            'recommendations'        => $recommendations,
            'activePrograms'         => $activePrograms,
            'completedProgramsCount' => $completedProgramsCount,
            'weeklyActivity'         => $weeklyActivity,
            'upcomingSessions'       => $upcomingSessions,
            'mealReminder'           => $mealReminder,
        ]);
    }

    public function updateProfile(Request $request)
    {
        $request->validate([
            'weight'          => 'nullable|numeric|min:10|max:500',
            'height'          => 'nullable|numeric|min:50|max:300',
            'goal'            => 'nullable|in:weight_loss,muscle_gain,maintenance',
            'workout_plan_id' => 'nullable|exists:workout_plans,id',
            'diet_plan_id'    => 'nullable|exists:diet_plans,id',
        ]);

        $user = Auth::user();

        $bmi = null;
        $weight = $request->weight ?? optional($user->profile)->weight;
        $height = $request->height ?? optional($user->profile)->height;

        if ($weight && $height && $height > 0) {
            $heightMeters = $height / 100;
            $bmi = round($weight / ($heightMeters * $heightMeters), 2);
        }

        // Only update fields that were actually submitted
        $profileData = array_filter([
            'weight' => $weight,
            'height' => $height,
            'goal'   => $request->goal,
            'bmi'    => $bmi,
        ], fn($v) => $v !== null && $v !== '');

        $user->profile()->updateOrCreate(
            ['user_id' => $user->id],
            $profileData
        );

        return back()->with('success', 'Profile updated successfully!');
    }
}
