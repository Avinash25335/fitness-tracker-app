<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrainerAvailability extends Model
{
    use HasFactory;

    protected $table = 'trainer_availability';

    protected $fillable = [
        'trainer_id',
        'day',
        'slot',
    ];

    public function trainer()
    {
        return $this->belongsTo(Trainer::class);
    }
}
