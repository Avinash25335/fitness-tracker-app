<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserPlan extends Model
{
    protected $fillable = [
        'user_id',
        'plan_id',
        'start_date',
        'current_day',
        'is_completed',
    ];

    protected $casts = [
        'start_date' => 'date',
        'is_completed' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function plan()
    {
        return $this->belongsTo(WorkoutPlan::class, 'plan_id');
    }

    public function sessions()
    {
        return $this->hasMany(WorkoutSession::class);
    }
}
