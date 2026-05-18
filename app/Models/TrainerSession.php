<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrainerSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'trainer_id',
        'session_date',
        'session_time',
        'status'
    ];

    /**
     * Get the trainer associated with the session.
     */
    public function trainer()
    {
        return $this->belongsTo(Trainer::class);
    }

    /**
     * Get the user who booked the session.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
