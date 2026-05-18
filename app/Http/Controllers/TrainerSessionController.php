<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TrainerSession;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TrainerSessionController extends Controller
{
    /**
     * Book a new session with a trainer.
     */
    public function book(Request $request)
    {
        $request->validate([
            'trainer_id' => 'required|exists:trainers,id',
            'date' => 'required|date|after_or_equal:today',
            'time' => 'required'
        ]);

        try {
            DB::beginTransaction();

            // Check if slot already booked
            $exists = TrainerSession::where('trainer_id', $request->trainer_id)
                ->where('session_date', $request->date)
                ->where('session_time', $request->time)
                ->where('status', '!=', 'cancelled')
                ->exists();

            if ($exists) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'This slot is already booked ❌'
                ], 409);
            }

            // Create booking
            $session = TrainerSession::create([
                'user_id' => Auth::id(),
                'trainer_id' => $request->trainer_id,
                'session_date' => $request->date,
                'session_time' => $request->time,
                'status' => 'booked'
            ]);

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Session booked successfully ✅',
                'data' => $session
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'error', 'message' => 'Booking failed: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Reschedule an existing session.
     */
    public function reschedule(Request $request, $id)
    {
        $session = TrainerSession::findOrFail($id);

        if ($session->user_id !== Auth::id()) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'date' => 'required|date|after_or_equal:today',
            'time' => 'required'
        ]);

        // Conflict check
        $conflict = TrainerSession::where('trainer_id', $session->trainer_id)
            ->where('session_date', $request->date)
            ->where('session_time', $request->time)
            ->where('status', '!=', 'cancelled')
            ->where('id', '!=', $id)
            ->exists();

        if ($conflict) {
            return response()->json(['status' => 'error', 'message' => 'The new slot is already taken.'], 409);
        }

        $session->update([
            'session_date' => $request->date,
            'session_time' => $request->time,
            'status' => 'booked'
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Session rescheduled successfully ✅'
        ]);
    }

    /**
     * Get booked and unavailable slots for a trainer.
     */
    public function getBookedSlots($trainerId, $date)
    {
        // 1. Get real booked slots
        $bookedTimes = TrainerSession::where('trainer_id', $trainerId)
            ->where('session_date', $date)
            ->where('status', '!=', 'cancelled')
            ->pluck('session_time')
            ->map(function($time) {
                return substr($time, 0, 5); // HH:MM
            })->toArray();

        // 2. Simulate random "Unavailable Slots" (Marketplace behavior)
        // Trainer might have personal appointments or lunch breaks
        $standardSlots = ["09:00", "10:00", "11:00", "12:00", "14:00", "15:00", "16:00", "17:00"];
        $unavailable = [];
        srand(crc32($trainerId . $date)); // Seeded randomness
        if (rand(1, 10) > 7) $unavailable[] = $standardSlots[array_rand($standardSlots)];

        return response()->json(array_unique(array_merge($bookedTimes, $unavailable)));
    }

    /**
     * Cancel a booked session.
     */
    public function cancel($id)
    {
        try {
            $session = TrainerSession::where('id', $id)
                ->where('user_id', Auth::id())
                ->firstOrFail();

            // Set to cancelled instead of deleting to keep history
            $session->update(['status' => 'cancelled']);

            return response()->json([
                'status' => 'success',
                'message' => 'Session cancelled successfully ❌'
            ]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Cancellation failed'], 500);
        }
    }

    /**
     * Get user's bookings.
     */
    public function myBookings()
    {
        $bookings = TrainerSession::with('trainer.user')
            ->where('user_id', Auth::id())
            ->orderBy('session_date', 'desc')
            ->orderBy('session_time', 'desc')
            ->get();

        return response()->json($bookings);
    }
}
