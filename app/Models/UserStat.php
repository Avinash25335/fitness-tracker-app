<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserStat extends Model
{
    protected $fillable = [
        'user_id',
        'streak',
        'total_workouts',
        'xp',
        'level',
    ];

    /**
     * Calculate level based on XP
     * Formula: Level = floor(sqrt(XP / 100)) + 1
     */
    public function getCalculatedLevelAttribute()
    {
        return floor(sqrt($this->xp / 100)) + 1;
    }

    public function syncLevel()
    {
        $newLevel = $this->calculated_level;
        if ($this->level != $newLevel) {
            $this->level = $newLevel;
            $this->save();
            return true; // Level up!
        }
        return false;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
