<?php

namespace App\Http\Controllers;

use App\Models\WorkoutPlan;
use Illuminate\Http\Request;

class AdminWorkoutController extends Controller
{
    public function index()
    {
        $workouts = WorkoutPlan::latest()->get();
        return view('admin.workouts.index', compact('workouts'));
    }

    public function create()
    {
        return view('admin.workouts.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'level' => 'required|in:beginner,intermediate,advanced',
            'duration_weeks' => 'required|integer',
        ]);

        WorkoutPlan::create($validated);

        return redirect()->route('admin.workouts.index')->with('success', 'Workout plan created.');
    }

    public function edit(WorkoutPlan $workout)
    {
        return view('admin.workouts.edit', compact('workout'));
    }

    public function update(Request $request, WorkoutPlan $workout)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'level' => 'required|in:beginner,intermediate,advanced',
            'duration_weeks' => 'required|integer',
        ]);

        $workout->update($validated);

        return redirect()->route('admin.workouts.index')->with('success', 'Workout plan updated.');
    }

    public function destroy(WorkoutPlan $workout)
    {
        $workout->delete();
        return back()->with('success', 'Workout plan deleted.');
    }
}
