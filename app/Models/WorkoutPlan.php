<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkoutPlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'level',
        'duration_weeks',
    ];

    public function logs()
    {
        return $this->hasMany(WorkoutLog::class);
    }

    public function exercises()
    {
        return $this->belongsToMany(Exercise::class, 'workout_exercise')->withPivot('order')->withTimestamps();
    }

    public function userWorkouts()
    {
        return $this->hasMany(UserWorkout::class);
    }
}
