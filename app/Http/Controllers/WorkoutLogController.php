<?php

namespace App\Http\Controllers;

use App\Models\WorkoutLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WorkoutLogController extends Controller
{
    /**
     * Store a completed workout log entry.
     */
    public function store(Request $request)
    {
        $request->validate([
            'workout_plan_id' => 'required|exists:workout_plans,id',
            'date'            => 'required|date',
            'status'          => 'required|in:completed,missed',
        ]);

        // Check if already logged for this plan+date combo
        $existing = WorkoutLog::where('user_id',         Auth::id())
            ->where('workout_plan_id', $request->workout_plan_id)
            ->where('date',            $request->date)
            ->first();

        // ── BUG 5 FIX: Differentiate "logged" vs "already logged today" ──
        if ($existing) {
            return back()->with('info', 'You already logged this workout today. Great consistency! 🔥');
        }

        WorkoutLog::create([
            'user_id'         => Auth::id(),
            'workout_plan_id' => $request->workout_plan_id,
            'date'            => $request->date,
            'status'          => $request->status,
            'notes'           => $request->notes ?? null,
        ]);

        return back()->with('success', 'Workout marked as completed! Keep it up 💪');
    }
}
