<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkoutSession extends Model
{
    protected $fillable = [
        'user_plan_id',
        'day_number',
        'started_at',
        'completed',
        'completed_at',
        'duration',
        'calories_burned',
        'is_paused',
        'paused_at',
        'total_paused_seconds',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'completed' => 'boolean',
        'completed_at' => 'datetime',
        'duration' => 'integer',
        'calories_burned' => 'integer',
        'is_paused' => 'boolean',
        'paused_at' => 'datetime',
        'total_paused_seconds' => 'integer',
    ];

    public function userPlan()
    {
        return $this->belongsTo(UserPlan::class);
    }

    public function exerciseLogs()
    {
        return $this->hasMany(ExerciseLog::class, 'session_id');
    }
}
