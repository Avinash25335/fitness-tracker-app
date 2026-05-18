<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\TrainerAvailability;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    /**
     * Get available slots for a trainer on a specific date,
     * filtered by their predefined availability and existing bookings.
     */
    public function availableSlots($trainerId, Request $request)
    {
        $date = $request->date;
        $day = date('l', strtotime($date));

        // Get slots defined for this trainer on this day of the week
        $slots = TrainerAvailability::where('trainer_id', $trainerId)
            ->where('day', $day)
            ->pluck('slot')
            ->toArray();

        // Get already booked slots for this specific date
        $booked = Booking::where('trainer_id', $trainerId)
            ->where('date', $date)
            ->where('status', '!=', 'cancelled')
            ->pluck('slot')
            ->toArray();

        // Difference gives us actual available slots
        $available = array_values(array_diff($slots, $booked));

        // If booking for today, filter out slots that have already passed
        if ($date === now()->toDateString()) {
            $currentTime = now();
            $available = array_values(array_filter($available, function($slot) use ($currentTime) {
                return \Carbon\Carbon::parse($slot)->gt($currentTime);
            }));
        }

        return response()->json($available);
    }

    /**
     * Store a new trainer booking with conflict prevention
     */
    public function store(Request $request)
    {
        $request->validate([
            'trainer_id' => 'required|exists:trainers,id',
            'date' => 'required|date|after_or_equal:today',
            'slot' => 'required'
        ]);

        try {
            $booking = Booking::create([
                'user_id' => Auth::id(),
                'trainer_id' => $request->trainer_id,
                'date' => $request->date,
                'slot' => $request->slot,
                'status' => 'confirmed'
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Session booked successfully!',
                'data' => $booking
            ]);

        } catch (\Exception $e) {
            // This catches the unique constraint violation or any other creation error
            return response()->json([
                'success' => false,
                'message' => 'This slot was just taken! Please choose another.'
            ], 422);
        }
    }

    /**
     * Get user's bookings with trainer details
     */
    public function myBookings()
    {
        return Booking::with('trainer.user')
            ->where('user_id', Auth::id())
            ->orderBy('date', 'desc')
            ->latest()
            ->get();
    }

    /**
     * Cancel a booking securely
     */
    public function cancel($id)
    {
        $booking = Booking::findOrFail($id);

        if ($booking->user_id !== Auth::id()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized.'], 403);
        }

        $booking->status = 'cancelled';
        $booking->save();

        return response()->json(['success' => true, 'message' => 'Booking cancelled.']);
    }
}
