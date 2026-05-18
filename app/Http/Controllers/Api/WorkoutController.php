<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\WorkoutPlan;
use App\Models\UserPlan;
use App\Models\WorkoutSession;
use App\Models\ExerciseLog;
use App\Models\UserStat;
use App\Models\Exercise;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WorkoutController extends Controller
{
    /**
     * Start Plan
     */
    public function startPlan($planId)
    {
        $userPlan = UserPlan::updateOrCreate(
            ['user_id' => Auth::id(), 'plan_id' => $planId, 'is_completed' => false],
            ['start_date' => now(), 'current_day' => 1]
        );

        if ($userPlan->sessions()->count() === 0) {
            for ($i = 1; $i <= 30; $i++) {
                $session = WorkoutSession::create([
                    'user_plan_id' => $userPlan->id,
                    'day_number' => $i,
                    'completed' => false
                ]);
                
                // Initialize started_at for Day 1
                if ($i === 1) {
                    $session->update(['started_at' => now()]);
                }
            }
        } else {
            // If plan exists but first session has no start time, set it
            $currentSession = $userPlan->sessions()->where('day_number', $userPlan->current_day)->first();
            if ($currentSession && !$currentSession->started_at) {
                $currentSession->update(['started_at' => now()]);
            }
        }

        return response()->json(['message' => 'Plan started', 'user_plan_id' => $userPlan->id]);
    }

    /**
     * Complete Exercise
     */
    public function completeExercise(Request $req)
    {
        $req->validate([
            'session_id' => 'required|exists:workout_sessions,id',
            'exercise_id' => 'required|exists:exercises,id',
            'sets' => 'nullable|integer',
            'reps' => 'nullable|integer',
            'weight' => 'nullable|numeric',
            'rpe' => 'nullable|integer|min:1|max:10'
        ]);

        $log = ExerciseLog::updateOrCreate(
            ['session_id' => $req->session_id, 'exercise_id' => $req->exercise_id],
            [
                'completed' => true, 
                'sets_completed' => $req->sets ?? 0, 
                'reps_completed' => $req->reps ?? 0, 
                'weight' => $req->weight,
                'rpe' => $req->rpe ?? 7
            ]
        );

        // 🧠 AI FATIGUE & INTELLIGENCE ENGINE
        $exercise = Exercise::find($req->exercise_id);
        $rpe = $req->rpe ?? 7;
        $coachingTip = "Perfect intensity! Keep it up.";
        
        if ($rpe >= 9 && $req->reps < $exercise->reps) {
            $coachingTip = "Fatigue detected. You are pushing your limits! Consider a 10% weight deload next time to ensure recovery. 🛡️";
        } elseif ($req->reps > $exercise->reps && $rpe <= 7) {
            $coachingTip = "Absolute beast mode! You conquered that set easily. Increase weight by 2.5kg-5kg next session. 📈";
        } elseif ($rpe >= 10) {
            $coachingTip = "Maximum exertion reached. Take an extra 30s rest before the next set. 💧";
        }

        $gamification = new \App\Services\GamificationService();
        $gamification->awardXp(Auth::user(), 10, 'Exercise Completed');

        return response()->json([
            'success' => true, 
            'log' => $log,
            'coaching_tip' => $coachingTip
        ]);
    }

    /**
     * Complete Session
     */
    public function completeSession($sessionId)
    {
        $session = WorkoutSession::findOrFail($sessionId);
        if (!$session->completed) {
            $now = now();
            
            // Handle active pause if finishing while paused
            $totalPaused = $session->total_paused_seconds;
            if ($session->is_paused && $session->paused_at) {
                $totalPaused += $now->diffInSeconds($session->paused_at);
            }

            $rawDuration = $session->started_at ? $now->diffInSeconds($session->started_at) : 0;
            $duration = max(0, $rawDuration - $totalPaused);
            
            // Calorie Calculation (Realistic Upgrade)
            $user = Auth::user();
            $weight = $user->profile->weight ?? 70; // fallback to 70kg
            $calories = round(($duration / 60) * (0.035 * $weight));

            $session->completed = true;
            $session->completed_at = $now;
            $session->duration = $duration;
            $session->calories_burned = $calories;
            $session->is_paused = false; 
            $session->save();

            $stats = UserStat::firstOrCreate(['user_id' => $user->id]);
            $stats->increment('streak');
            $stats->increment('total_workouts');

            $gamification = new \App\Services\GamificationService();
            $result = $gamification->awardXp($user, 50, 'Session Completed');

            $userPlan = $session->userPlan;
            $userPlan->increment('current_day');
            
            return response()->json([
                'message' => 'Session completed',
                'duration' => $session->duration,
                'calories' => $session->calories_burned,
                'exercises_completed' => $session->exerciseLogs()->where('completed', true)->count(),
                'xp_gained' => 50,
                'streak' => $stats->streak,
                'leveled_up' => $result['leveled_up'],
                'new_level' => $result['new_level']
            ]);
        }

        return response()->json(['message' => 'Session already completed']);
    }

    /**
     * Pause Session
     */
    public function pauseSession($sessionId)
    {
        $session = WorkoutSession::findOrFail($sessionId);
        if (!$session->completed && !$session->is_paused) {
            $session->update([
                'is_paused' => true,
                'paused_at' => now()
            ]);
        }
        return response()->json($session);
    }

    /**
     * Resume Session
     */
    public function resumeSession($sessionId)
    {
        $session = WorkoutSession::findOrFail($sessionId);
        if (!$session->completed && $session->is_paused && $session->paused_at) {
            $diff = now()->diffInSeconds($session->paused_at);
            $session->update([
                'is_paused' => false,
                'total_paused_seconds' => $session->total_paused_seconds + $diff,
                'paused_at' => null
            ]);
        }
        return response()->json($session);
    }

    // [Removing addXP internal method as requested]

    /**
     * Advanced Analytics (Real Dashboard)
     */
    public function analytics()
    {
        $user = Auth::user();
        $sessions = WorkoutSession::whereHas('userPlan', function($q) use ($user) {
            $q->where('user_id', $user->id);
        })->where('completed', true)->get();

        $stats = $user->stat;

        return response()->json([
            'total_workouts' => $sessions->count(),
            'total_time' => $sessions->count() * 45, // Estimated 45 mins per workout
            'avg_per_week' => round($sessions->where('completed_at', '>=', now()->subDays(30))->count() / 4, 1),
            'streak' => $stats->streak ?? 0,
            'level' => $stats->level ?? 1
        ]);
    }

    /**
     * Gamification System (Achievements)
     */
    public function checkAchievements()
    {
        $user = Auth::user();
        $stats = UserStat::firstOrCreate(['user_id' => $user->id]);

        $achievements = [];

        if ($stats->streak >= 3) $achievements[] = "🔥 3 Day Streak";
        if ($stats->streak >= 7) $achievements[] = "🛡️ Consistent Week";
        if ($stats->total_workouts >= 10) $achievements[] = "💪 10 Workouts";
        if ($stats->level >= 5) $achievements[] = "⭐ Elite Rank";

        return response()->json($achievements);
    }

    /**
     * Adaptive AI Engine
     */
    public function adaptivePlan()
    {
        $user = Auth::user();
        $sessions = WorkoutSession::whereHas('userPlan', function($q) use ($user) { $q->where('user_id', $user->id); })->get();
        $total = $sessions->count();
        $completed = $sessions->where('completed', true)->count();
        $rate = $total > 0 ? ($completed / $total) : 0;

        if ($rate > 0.8) { $level = 'advanced'; $message = "Legendary consistency detected! Elite level recommended. 🔥"; }
        elseif ($rate > 0.4) { $level = 'intermediate'; $message = "Great progress! Ready for Intermediate. 💪"; }
        else { $level = 'beginner'; $message = "Stick to Beginner plans to build a solid foundation. 🚀"; }

        return response()->json(['level' => $level, 'completion_rate' => round($rate * 100), 'message' => $message]);
    }

    /**
     * Calendar Data
     */
    public function calendarData()
    {
        $sessions = WorkoutSession::whereHas('userPlan', function($q) { $q->where('user_id', Auth::id()); })
            ->where('completed', true)->get(['completed_at']);
        return response()->json($sessions);
    }

    /**
     * Get Progress
     */
    public function getProgress($planId)
    {
        $userPlan = UserPlan::where('plan_id', $planId)->where('user_id', Auth::id())->first();
        if (!$userPlan) return response()->json(['total' => 30, 'completed' => 0, 'percent' => 0, 'current_day' => 1]);
        $total = WorkoutSession::where('user_plan_id', $userPlan->id)->count();
        $completed = WorkoutSession::where('user_plan_id', $userPlan->id)->where('completed', true)->count();
        $currentSession = WorkoutSession::where('user_plan_id', $userPlan->id)->where('day_number', $userPlan->current_day)->first();
        $stats = Auth::user()->stat;
        // 📈 HISTORICAL PERFORMANCE (Progressive Overload)
        $historicalStats = [];
        $exercises = $userPlan->plan->exercises;
        foreach ($exercises as $ex) {
            $lastLog = ExerciseLog::where('exercise_id', $ex->id)
                ->whereHas('session', function($q) use ($userPlan) {
                    $q->where('user_plan_id', $userPlan->id)->where('completed', true);
                })
                ->orderBy('created_at', 'desc')
                ->first();
            
            if ($lastLog) {
                $historicalStats[$ex->id] = [
                    'weight' => $lastLog->weight,
                    'reps' => $lastLog->reps_completed,
                    'sets' => $lastLog->sets_completed,
                    'date' => $lastLog->created_at->format('M d')
                ];
            }
        }

        return response()->json([
            'total' => $total, 
            'completed' => $completed, 
            'percent' => $total ? round(($completed / $total) * 100) : 0,
            'current_day' => $userPlan->current_day, 
            'session_id' => $currentSession ? $currentSession->id : null,
            'started_at' => $currentSession && $currentSession->started_at ? $currentSession->started_at->timestamp * 1000 : null,
            'is_paused' => $currentSession ? $currentSession->is_paused : false,
            'paused_at' => $currentSession && $currentSession->paused_at ? $currentSession->paused_at->timestamp * 1000 : null,
            'total_paused' => $currentSession ? $currentSession->total_paused_seconds : 0,
            'completed_exercises' => $currentSession ? $currentSession->exerciseLogs()->pluck('exercise_id') : [],
            'historical_stats' => $historicalStats,
            'streak' => $stats->streak ?? 0, 'xp' => $stats->xp ?? 0, 'level' => $stats->level ?? 1
        ]);
    }

    /**
     * AI Recommendation
     */
    public function getRecommendation()
    {
        $user = Auth::user();
        $stats = UserStat::firstOrCreate(['user_id' => $user->id]);
        if ($stats->streak < 3) return response()->json(['type' => 'motivation', 'message' => 'Consistency is key. Start today! 💪']);
        if ($stats->total_workouts > 20) return response()->json(['type' => 'upgrade', 'message' => 'Legendary progress! Try Intermediate. 🔥']);
        return response()->json(['type' => 'default', 'message' => 'Stay focused. Your future self will thank you! 🚀']);
    }

    /**
     * Weekly Analytics
     */
    public function weeklyStats()
    {
        $user = Auth::user();
        $data = WorkoutSession::whereHas('userPlan', function($q) use ($user) { $q->where('user_id', $user->id); })
            ->where('completed', true)->where('completed_at', '>=', now()->subDays(7))
            ->selectRaw('DATE(completed_at) as date, count(*) as total')
            ->groupBy('date')->orderBy('date', 'asc')->get();
        return response()->json($data);
    }
}
