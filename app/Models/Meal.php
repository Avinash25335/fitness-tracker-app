<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Meal extends Model
{
    protected $fillable = [
        'diet_plan_id',
        'meal_type',
        'name',
        'description',
        'calories',
    ];

    public function dietPlan()
    {
        return $this->belongsTo(DietPlan::class);
    }
}
