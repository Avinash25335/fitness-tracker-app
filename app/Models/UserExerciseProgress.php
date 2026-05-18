<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserExerciseProgress extends Model
{
    protected $table = 'user_exercise_progress';

    protected $fillable = [
        'user_id',
        'exercise_id',
        'user_workout_id',
        'completed',
        'completed_at',
    ];

    protected $casts = [
        'completed_at' => 'datetime',
        'completed' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function exercise()
    {
        return $this->belongsTo(Exercise::class);
    }

    public function userWorkout()
    {
        return $this->belongsTo(UserWorkout::class);
    }
}
