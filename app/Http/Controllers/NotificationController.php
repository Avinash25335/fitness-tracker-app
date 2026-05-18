<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Get unread notifications and mark them as read immediately 
     * (so they don't pop up again)
     */
    public function getLatest()
    {
        $user = Auth::user();
        if (!$user) return response()->json([]);

        $unread = $user->unreadNotifications;
        $user->unreadNotifications->markAsRead();

        return response()->json($unread);
    }
}
