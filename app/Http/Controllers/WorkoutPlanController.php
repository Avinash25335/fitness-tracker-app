<?php

namespace App\Http\Controllers;

use App\Models\WorkoutPlan;
use App\Models\WorkoutLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WorkoutPlanController extends Controller
{
    public function index()
    {
        $workouts = WorkoutPlan::withCount('exercises')
            ->orderBy('level')
            ->get();

        $loggedPlanIds = collect();
        $planProgress = [];
        $activePlanId = null;

        if (Auth::check()) {
            $userPlans = \App\Models\UserPlan::where('user_id', Auth::id())
                ->where('is_completed', false)
                ->get();
                
            $loggedPlanIds = $userPlans->pluck('plan_id');
            
            foreach ($userPlans as $up) {
                // Progress is (completed_sessions / total_sessions) * 100
                $total = $up->sessions()->count();
                $completed = $up->sessions()->where('completed', true)->count();
                $planProgress[$up->plan_id] = $total > 0 ? round(($completed / $total) * 100) : 0;
            }

            $activePlanId = \App\Models\UserPlan::where('user_id', Auth::id())
                ->where('is_completed', false)
                ->latest()
                ->value('plan_id');
        }

        return view('workouts.index', compact('workouts', 'loggedPlanIds', 'activePlanId', 'planProgress'));
    }

    public function show(WorkoutPlan $workout)
    {
        $workout->load(['exercises' => function ($q) {
            $q->orderBy('workout_exercise.order');
        }]);

        $activeUserPlan = null;
        $completedExerciseIds = collect();
        
        if (Auth::check()) {
            $activeUserPlan = \App\Models\UserPlan::where('user_id', Auth::id())
                ->where('plan_id', $workout->id)
                ->where('is_completed', false)
                ->first();
            
            if ($activeUserPlan) {
                // Find the session for the current day of the plan
                $currentSession = $activeUserPlan->sessions()
                    ->where('day_number', $activeUserPlan->current_day)
                    ->first();
                
                if ($currentSession) {
                    $completedExerciseIds = $currentSession->exerciseLogs()->pluck('exercise_id');
                }
            }
        }

        return view('workouts.show', compact('workout', 'activeUserPlan', 'completedExerciseIds'));
    }
}
