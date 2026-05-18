<?php

namespace App\Http\Controllers;

use App\Models\WorkoutPlan;
use App\Models\DietPlan;
use App\Models\Trainer;
use App\Models\BlogPost;

class HomeController extends Controller
{
    public function index()
    {
        $featuredWorkouts = WorkoutPlan::take(3)->get();
        $featuredDiets    = DietPlan::take(3)->get();
        $featuredTrainers = Trainer::with('user')->take(3)->get();
        $latestPosts      = BlogPost::with('author')->latest('published_at')->take(3)->get();

        return view('home', compact(
            'featuredWorkouts',
            'featuredDiets',
            'featuredTrainers',
            'latestPosts'
        ));
    }
}
