<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Exercise extends Model
{
    protected $fillable = [
        'name',
        'instructions',
        'target_muscles',
        'form_cues',
        'mistakes',
        'muscle_group',
        'body_part',
        'sets',
        'reps',
        'duration_seconds',
        'media_url',
    ];

    public function workoutPlans()
    {
        return $this->belongsToMany(WorkoutPlan::class, 'workout_exercise')
                    ->withPivot('order')
                    ->withTimestamps();
    }

    public function userProgress()
    {
        return $this->hasMany(UserExerciseProgress::class);
    }
}
