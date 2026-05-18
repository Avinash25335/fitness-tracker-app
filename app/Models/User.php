<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, \Laravel\Sanctum\HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'age',
        'gender',
    ];

    public function profile()
    {
        return $this->hasOne(UserProfile::class);
    }

    public function workoutLogs()
    {
        return $this->hasMany(WorkoutLog::class);
    }

    public function trainer()
    {
        return $this->hasOne(Trainer::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function blogPosts()
    {
        return $this->hasMany(BlogPost::class, 'user_id');
    }

    public function progressLogs()
    {
        return $this->hasMany(ProgressLog::class);
    }

    public function userWorkouts()
    {
        return $this->hasMany(UserWorkout::class);
    }

    public function userExerciseProgress()
    {
        return $this->hasMany(UserExerciseProgress::class);
    }

    public function userPlans()
    {
        return $this->hasMany(UserPlan::class);
    }

    public function achievements()
    {
        return $this->belongsToMany(Achievement::class, 'user_achievements')
                    ->withTimestamps();
    }

    public function stat()
    {
        return $this->hasOne(UserStat::class);
    }



    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
