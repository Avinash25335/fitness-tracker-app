<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExerciseLog extends Model
{
    protected $fillable = [
        'session_id',
        'exercise_id',
        'sets_completed',
        'reps_completed',
        'weight',
        'completed',
    ];

    protected $casts = [
        'completed' => 'boolean',
    ];

    public function session()
    {
        return $this->belongsTo(WorkoutSession::class, 'session_id');
    }

    public function exercise()
    {
        return $this->belongsTo(Exercise::class);
    }
}
