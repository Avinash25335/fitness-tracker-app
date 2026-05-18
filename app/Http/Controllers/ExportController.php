<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserStat;
use App\Models\WorkoutLog;
use App\Models\Booking;
use App\Models\UserPlan;
use Illuminate\Support\Facades\Auth;

class ExportController extends Controller
{
    public function progress()
    {
        $user = Auth::user();
        $stat = $user->stat;
        $achievements = $user->achievements;
        $logs = $user->progressLogs()->latest()->take(30)->get();

        return view('exports.progress', compact('user', 'stat', 'achievements', 'logs'));
    }

    public function workouts()
    {
        $user = Auth::user();
        $sessions = $user->workoutLogs()->with('workoutPlan')->latest()->get();

        return view('exports.workouts', compact('user', 'sessions'));
    }

    public function diet()
    {
        $user = Auth::user();
        $profile = $user->profile;

        if (!$profile) {
            return redirect()->route('dashboard')->with('error', 'Please complete your profile first to generate a nutrition strategy.');
        }

        $activeUserPlan = $user->userPlans()->where('is_completed', false)->with('plan')->first();
        
        // Calculate Nutritional Intelligence for the report
        $bmr = ($user->gender === 'male') 
            ? 10 * $profile->weight + 6.25 * $profile->height - 5 * $user->age + 5
            : 10 * $profile->weight + 6.25 * $profile->height - 5 * $user->age - 161;
            
        $tdee = round($bmr * 1.55); // Moderate activity default
        $targetCals = match($profile->goal) {
            'weight_loss' => $tdee - 500,
            'muscle_gain' => $tdee + 300,
            default => $tdee
        };

        return view('exports.diet', compact('user', 'profile', 'activeUserPlan', 'targetCals', 'tdee'));
    }

    public function invoice($id)
    {
        $booking = Booking::with(['trainer.user', 'user'])->findOrFail($id);
        
        // Security check
        if ($booking->user_id !== Auth::id()) {
            abort(403);
        }

        return view('exports.invoice', compact('booking'));
    }
}
