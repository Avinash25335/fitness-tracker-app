<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\WorkoutPlan;
use App\Models\Booking;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        $usersCount = User::count();
        $workoutsCount = WorkoutPlan::count();
        $bookingsCount = Booking::count();
        $recentBookings = Booking::with(['user', 'trainer'])->latest()->take(5)->get();

        return view('admin.dashboard', compact('usersCount', 'workoutsCount', 'bookingsCount', 'recentBookings'));
    }

    public function users()
    {
        $users = User::latest()->paginate(10);
        return view('admin.users', compact('users'));
    }

    public function destroyUser(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot delete yourself!');
        }
        $user->delete();
        return back()->with('success', 'User deleted successfully.');
    }
}
