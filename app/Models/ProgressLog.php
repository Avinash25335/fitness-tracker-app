<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProgressLog extends Model
{
    protected $fillable = [
        'user_id',
        'weight',
        'transformation_image',
        'log_date',
        'notes',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
