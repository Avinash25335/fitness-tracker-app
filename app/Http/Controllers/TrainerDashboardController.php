<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\TrainerSession;

class TrainerDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Ensure user is authenticated
        if (!$user) {
            return redirect('/login');
        }

        // Check if user is a trainer
        if ($user->role !== 'trainer') {
            return redirect('/dashboard')->with('error', 'Only trainers can access this page.');
        }

        // Get or create trainer profile if it doesn't exist
        $trainer = $user->trainer;
        if (!$trainer) {
            try {
                \App\Models\Trainer::create([
                    'user_id' => $user->id,
                    'bio' => 'Passionate trainer ready to help clients achieve real results.',
                    'specialization' => 'General Fitness',
                    'hourly_rate' => 50.00,
                    'experience' => 1,
                    'students' => 0,
                ]);
                $trainer = $user->fresh()->trainer;
            } catch (\Exception $e) {
                // If creation fails, continue with null trainer
                $trainer = null;
            }
        }

        // Safe data initialization
        $upcomingSessions = collect();
        $totalStudents = $trainer->students ?? 0;
        $sessionsThisMonth = 0;
        $revenue = 0;
        $recentBookings = collect();
        $weeklyActivity = collect();

        // Try to get sessions if trainer exists
        if ($trainer) {
            try {
                $upcomingSessions = TrainerSession::where('trainer_id', $trainer->id)
                    ->where('status', 'booked')
                    ->where('session_date', '>=', now()->toDateString())
                    ->with('user')
                    ->orderBy('session_date', 'asc')
                    ->orderBy('session_time', 'asc')
                    ->take(10)
                    ->get();

                $sessionsThisMonth = TrainerSession::where('trainer_id', $trainer->id)
                    ->whereMonth('session_date', now()->month)
                    ->whereYear('session_date', now()->year)
                    ->count();

                $recentBookings = TrainerSession::where('trainer_id', $trainer->id)
                    ->with('user')
                    ->orderBy('created_at', 'desc')
                    ->take(5)
                    ->get();
            } catch (\Exception $e) {
                // If queries fail, continue with empty collections
            }
        }

        return view('dashboard.trainer', [
            'user' => $user,
            'trainer' => $trainer,
            'upcomingSessions' => $upcomingSessions,
            'totalStudents' => $totalStudents,
            'sessionsThisMonth' => $sessionsThisMonth,
            'revenue' => $revenue,
            'recentBookings' => $recentBookings,
            'weeklyActivity' => $weeklyActivity,
        ]);
    }

    public function updateCost(Request $request)
    {
        $user = Auth::user();

        if (!$user || $user->role !== 'trainer' || !$user->trainer) {
            return redirect('/dashboard')->with('error', 'Unauthorized access.');
        }

        $request->validate([
            'hourly_rate' => 'required|numeric|min:10|max:500'
        ]);

        $user->trainer->update([
            'hourly_rate' => $request->hourly_rate
        ]);

        return back()->with('success', 'Cost per session updated successfully! ✅');
    }
}

