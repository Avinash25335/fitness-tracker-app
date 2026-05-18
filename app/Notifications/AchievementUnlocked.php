<?php

namespace App\Notifications;

use App\Models\Achievement;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class AchievementUnlocked extends Notification
{
    use Queueable;

    public $achievement;

    public function __construct(Achievement $achievement)
    {
        $this->achievement = $achievement;
    }

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toArray($notifiable): array
    {
        return [
            'type' => 'achievement',
            'title' => 'Achievement Unlocked! 🏆',
            'message' => "You've earned: {$this->achievement->title}",
            'icon' => $this->achievement->icon,
            'achievement_id' => $this->achievement->id
        ];
    }
}
