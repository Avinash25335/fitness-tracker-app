<?php

namespace App\Http\Controllers;

use App\Models\ProgressLog;
use App\Models\WorkoutSession;
use App\Models\ExerciseLog;
use App\Services\AICoachService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ProgressController extends Controller
{
    public function index()
    {
        $user       = Auth::user();
        $profile    = $user->profile;
        $stats      = $user->stat;
        $progressLogs = $user->progressLogs()->orderBy('log_date', 'asc')->get();

        // ── Basic Weight Stats ─────────────────────────────────────────────────
        $currentWeight  = $profile->weight ?? 0;
        $goalWeight     = $profile->goal_weight ?? 0;
        $startingWeight = $progressLogs->first()->weight ?? $currentWeight;
        $totalLogs      = $progressLogs->count();
        $totalChange    = round($currentWeight - $startingWeight, 1);

        // Weight trend (difference between last two logs)
        $weightTrend = 0;
        if ($progressLogs->count() >= 2) {
            $last    = $progressLogs->last()->weight;
            $prev    = $progressLogs->slice(-2, 1)->first()->weight ?? $last;
            $weightTrend = round($last - $prev, 1);
        }

        // Progress Percentage
        $progressPercent = 0;
        if ($startingWeight > 0 && $goalWeight > 0 && $startingWeight !== $goalWeight) {
            $pct = (($currentWeight - $startingWeight) / ($goalWeight - $startingWeight)) * 100;
            $progressPercent = max(0, min(100, round($pct)));
        }

        // ── Streak ─────────────────────────────────────────────────────────────
        $streak   = 0;
        $today    = now()->startOfDay();
        
        // Group by unique date to prevent multiple logs on the same day breaking the streak
        $uniqueLogDates = $user->progressLogs()
            ->orderBy('log_date', 'desc')
            ->get()
            ->map(fn($log) => Carbon::parse($log->log_date)->startOfDay()->format('Y-m-d'))
            ->unique()
            ->values();

        if ($uniqueLogDates->isNotEmpty()) {
            $firstLogDate = Carbon::parse($uniqueLogDates->first());
            $expectedDate = $today->copy();
            
            if ($firstLogDate->equalTo($today)) {
                $streak++;
                $expectedDate->subDay();
                $uniqueLogDates->shift();
            } elseif ($firstLogDate->equalTo($today->copy()->subDay())) {
                $streak++;
                $expectedDate->subDays(2);
                $uniqueLogDates->shift();
            } else {
                $uniqueLogDates = collect();
            }

            foreach ($uniqueLogDates as $dateStr) {
                if (Carbon::parse($dateStr)->equalTo($expectedDate)) {
                    $streak++;
                    $expectedDate->subDay();
                } else {
                    break;
                }
            }
        }

        // ── Workout Sessions ───────────────────────────────────────────────────
        $allSessions = WorkoutSession::whereHas('userPlan', fn($q) => $q->where('user_id', $user->id))
            ->where('completed', true)
            ->orderBy('completed_at')
            ->get();

        $workoutsThisWeek = $allSessions->filter(
            fn($s) => Carbon::parse($s->completed_at)->isCurrentWeek()
        )->count();

        $workoutsLastWeek = $allSessions->filter(
            fn($s) => Carbon::parse($s->completed_at)->isoWeek() === now()->subWeek()->isoWeek()
                   && Carbon::parse($s->completed_at)->year === now()->subWeek()->year
        )->count();

        $workoutsThisMonth = $allSessions->filter(
            fn($s) => Carbon::parse($s->completed_at)->isCurrentMonth()
        )->count();

        $totalCaloriesBurned = $allSessions->sum('calories_burned');

        // ── Days Since Last Workout ────────────────────────────────────────────
        $lastSession = $allSessions->last();
        $daysSinceLastWorkout = $lastSession
            ? (int)round(Carbon::parse($lastSession->completed_at)->diffInDays(now()))
            : 999;

        // ── Missed Muscle Groups (this week) ──────────────────────────────────
        $masterGroups = ['Chest', 'Back', 'Legs', 'Shoulders', 'Arms', 'Core'];
        $sessionsThisWeek = $allSessions->filter(
            fn($s) => Carbon::parse($s->completed_at)->isCurrentWeek()
        );
        $trainedGroups = [];
        foreach ($sessionsThisWeek as $ws) {
            foreach ($ws->exerciseLogs()->with('exercise')->get() as $log) {
                $muscle = $log->exercise->muscle_group ?? null;
                if ($muscle) $trainedGroups[] = ucfirst(strtolower($muscle));
            }
        }
        $trainedGroups = array_unique($trainedGroups);
        $missedMuscleGroups = array_values(array_filter($masterGroups, fn($g) => !in_array($g, $trainedGroups)));

        // Weekly workout counts for chart (last 8 weeks)
        $weeklyWorkouts = [];
        $weeklyCalories = [];
        $weekLabels     = [];
        for ($i = 7; $i >= 0; $i--) {
            $weekStart = now()->subWeeks($i)->startOfWeek();
            $weekEnd   = now()->subWeeks($i)->endOfWeek();
            $weekSessions = $allSessions->filter(
                fn($s) => Carbon::parse($s->completed_at)->between($weekStart, $weekEnd)
            );
            $weeklyWorkouts[] = $weekSessions->count();
            $weeklyCalories[] = $weekSessions->sum('calories_burned');
            $weekLabels[]     = 'W' . $weekStart->isoWeek();
        }

        // ── Strength / PR Tracking ─────────────────────────────────────────────
        $exerciseLogs = ExerciseLog::whereHas('session', function($q) use ($user) {
            $q->whereHas('userPlan', fn($q2) => $q2->where('user_id', $user->id));
        })
        ->where('completed', true)
        ->whereNotNull('weight')
        ->with('exercise', 'session')
        ->get();

        // Group by exercise name, build weekly PR data
        $prData = [];
        $prsBrokenThisWeek = 0;

        $exerciseLogs->groupBy(fn($l) => $l->exercise->name ?? 'Unknown')->each(function($logs, $name) use (&$prData, &$prsBrokenThisWeek) {
            $sorted = $logs->sortBy(fn($l) => $l->session->completed_at);
            $maxWeight = 0;
            $history   = [];
            foreach ($sorted as $log) {
                if ($log->weight > $maxWeight) {
                    $maxWeight = $log->weight;
                    $date = Carbon::parse($log->session->completed_at)->format('M d');
                    $history[] = ['date' => $date, 'weight' => $maxWeight, 'is_pr' => true];

                    if (Carbon::parse($log->session->completed_at)->isCurrentWeek()) {
                        $prsBrokenThisWeek++;
                    }
                }
            }
            if (!empty($history)) {
                $prData[$name] = [
                    'history'        => $history,
                    'starting_weight' => $history[0]['weight'],
                    'current_pr'     => $maxWeight,
                    'improvement'    => $history[0]['weight'] > 0
                        ? round((($maxWeight - $history[0]['weight']) / $history[0]['weight']) * 100, 1)
                        : 0,
                ];
            }
        });

        // ── AI Coach ──────────────────────────────────────────────────────────
        $coach   = new AICoachService();
        $aiInsights = $coach->analyze([
            'workouts_this_week'             => $workoutsThisWeek,
            'workouts_last_week'             => $workoutsLastWeek,
            'streak'                         => $streak,
            'total_workouts'                 => $stats->total_workouts ?? 0,
            'goal'                           => $profile->goal ?? null,
            'weight_trend'                   => $weightTrend,
            'avg_calories_per_week'          => count($weeklyCalories) > 0 ? round(array_sum($weeklyCalories) / max(1, count(array_filter($weeklyCalories)))) : 0,
            'prs_broken_this_week'           => $prsBrokenThisWeek,
            'missed_muscle_groups'           => $missedMuscleGroups,
            'days_since_last_workout'        => $daysSinceLastWorkout,
            'total_workout_duration_this_week' => $sessionsThisWeek->sum('duration') / 60, // minutes
        ]);

        // ── DEFENSIVE DEFINITION ──
        $insight = 'Keep logging to unlock insights.';

        // ── Gamification System [REFRESH_FORCE_V3] ──────────────────────────
        $gamification = new \App\Services\GamificationService();
        $leaderboard  = $gamification->getLeaderboard();
        $achievements = $user->achievements;
        $allAchievements = \App\Models\Achievement::all();
        
        $currentLevel = $user->stat->level ?? 1;
        $currentXp = $user->stat->xp ?? 0;
        
        $baseLevelXp = pow($currentLevel - 1, 2) * 100;
        $nextLevelXp = pow($currentLevel, 2) * 100;
        
        $xpIntoLevel = max(0, $currentXp - $baseLevelXp);
        $xpNeededForLevel = max(1, $nextLevelXp - $baseLevelXp);
        
        $progressToNextLevel = ($xpIntoLevel / $xpNeededForLevel) * 100;

        // Update insight if AI data is available
        if (isset($aiInsights[0]['message'])) {
            $insight = $aiInsights[0]['message'];
        }

        return view('progress.index')
            ->with('user', $user)
            ->with('profile', $profile)
            ->with('progressLogs', $progressLogs)
            ->with('currentWeight', $currentWeight)
            ->with('goalWeight', $goalWeight)
            ->with('startingWeight', $startingWeight)
            ->with('totalLogs', $totalLogs)
            ->with('totalChange', $totalChange)
            ->with('progressPercent', $progressPercent)
            ->with('streak', $streak)
            ->with('insight', $insight)
            ->with('workoutsThisWeek', $workoutsThisWeek)
            ->with('workoutsThisMonth', $workoutsThisMonth)
            ->with('totalCaloriesBurned', $totalCaloriesBurned)
            ->with('weeklyWorkouts', $weeklyWorkouts)
            ->with('weeklyCalories', $weeklyCalories)
            ->with('weekLabels', $weekLabels)
            ->with('prData', $prData)
            ->with('aiInsights', $aiInsights)
            ->with('leaderboard', $leaderboard)
            ->with('achievements', $achievements)
            ->with('allAchievements', $allAchievements)
            ->with('nextLevelXp', $nextLevelXp)
            ->with('progressToNextLevel', $progressToNextLevel);
    }

    public function store(Request $request)
    {
        $request->validate([
            'weight'   => 'required|numeric|min:10|max:500',
            'log_date' => 'required|date|before_or_equal:today',
            'image'    => 'nullable|image|max:4096',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('transformations', 'public');
        }

        ProgressLog::updateOrCreate(
            ['user_id' => Auth::id(), 'log_date' => $request->log_date],
            [
                'weight'               => $request->weight,
                'transformation_image' => $imagePath ?? ProgressLog::where('user_id', Auth::id())
                                            ->where('log_date', $request->log_date)
                                            ->value('transformation_image'),
            ]
        );

        $profile = Auth::user()->profile;
        if ($profile && $profile->height > 0) {
            $h = $profile->height / 100;
            $profile->update(['weight' => $request->weight, 'bmi' => round($request->weight / ($h * $h), 2)]);
        }

        return back()->with('success', 'Progress entry saved!');
    }

    public function updateGoal(Request $request)
    {
        $request->validate(['goal_weight' => 'required|numeric|min:10|max:500']);

        $profile = Auth::user()->profile;
        if ($profile) {
            $profile->update(['goal_weight' => $request->goal_weight]);
        }

        return back()->with('success', 'Target weight updated! 🎯');
    }
}
