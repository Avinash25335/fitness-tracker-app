<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user()->load('profile');
    });

    Route::get('/workouts', function () {
        return \App\Models\WorkoutPlan::with('exercises')->get();
    });

    Route::get('/diets', function () {
        return \App\Models\DietPlan::with('meals')->get();
    });

    // Blog API
    Route::get('/articles', [\App\Http\Controllers\Api\BlogController::class, 'index']);
    Route::get('/articles/{slug}', [\App\Http\Controllers\Api\BlogController::class, 'show']);
});
