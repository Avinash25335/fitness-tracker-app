<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Achievement extends Model
{
    protected $fillable = ['title', 'condition', 'icon'];

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_achievements')
                    ->withTimestamps();
    }
}
