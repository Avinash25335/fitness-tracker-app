<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DietPlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'daily_calories',
        'protein',
        'carbs',
        'fats',
        'goal',
        'meals_json',
    ];

    protected function casts(): array
    {
        return [
            'meals_json' => 'array',
        ];
    }

    public function meals()
    {
        return $this->hasMany(Meal::class);
    }
}
