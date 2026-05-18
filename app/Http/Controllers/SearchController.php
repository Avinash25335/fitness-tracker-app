<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WorkoutPlan;
use App\Models\DietPlan;
use App\Models\BlogPost;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->input('q');

        if (!$query) {
            return redirect()->back();
        }

        $workouts = WorkoutPlan::where('title', 'like', "%{$query}%")
            ->orWhere('description', 'like', "%{$query}%")
            ->get();

        $diets = DietPlan::where('title', 'like', "%{$query}%")
            ->orWhere('description', 'like', "%{$query}%")
            ->get();

        $posts = BlogPost::where(function($q) use ($query) {
                $q->where('title', 'like', "%{$query}%")
                  ->orWhere('content', 'like', "%{$query}%");
            })
            ->whereNotNull('published_at')
            ->get();

        $trainers = \App\Models\Trainer::where('name', 'like', "%{$query}%")
            ->orWhere('bio', 'like', "%{$query}%")
            ->orWhere('specialization', 'like', "%{$query}%")
            ->get();

        return view('search.results', compact('query', 'workouts', 'diets', 'posts', 'trainers'));
    }
}
