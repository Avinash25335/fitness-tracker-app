<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\UserProfile;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $profile = $user->profile ?? new UserProfile();
        return view('profile.index', compact('user', 'profile'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'weight' => 'required|numeric|min:20',
            'height' => 'required|numeric|min:50',
            'age' => 'required|integer|min:10',
            'gender' => 'required|in:male,female',
            'goal' => 'required|in:weight_loss,muscle_gain,maintenance',
            'activity_level' => 'required|in:sedentary,light,moderate,active,extra_active'
        ]);

        // Update User
        $user->update([
            'name' => $request->name,
            'age' => $request->age,
            'gender' => $request->gender
        ]);

        // Update or Create Profile
        $bmi = round($request->weight / (($request->height / 100) ** 2), 1);
        
        $user->profile()->updateOrCreate(
            ['user_id' => $user->id],
            [
                'weight' => $request->weight,
                'height' => $request->height,
                'goal' => $request->goal,
                'bmi' => $bmi,
                'activity_level' => $request->activity_level
            ]
        );

        return redirect()->back()->with('success', 'Profile updated successfully! BMI: ' . $bmi);
    }

    public function destroy(Request $request)
    {
        $user = Auth::user();
        
        // Clean up relationships manually to prevent orphans
        if ($user->profile) {
            $user->profile->delete();
        }
        if ($user->trainer) {
            // Optional: Handle trainer sessions if necessary
            $user->trainer->delete();
        }
        
        Auth::logout();
        $user->delete();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/')->with('success', 'Your account has been successfully deleted.');
    }
}
