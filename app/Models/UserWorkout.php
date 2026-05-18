<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserWorkout extends Model
{
    protected $fillable = [
        'user_id',
        'workout_plan_id',
        'status',
        'progress',
        'started_at',
        'completed_at',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function workoutPlan()
    {
        return $this->belongsTo(WorkoutPlan::class);
    }

    public function exerciseProgress()
    {
        return $this->hasMany(UserExerciseProgress::class);
    }
}
