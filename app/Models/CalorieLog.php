<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CalorieLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'meal_description',
        'calories',
        'protein',
        'carbs',
        'fats',
        'logged_at'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
