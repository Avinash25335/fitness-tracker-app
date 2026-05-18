<?php

namespace App\Http\Controllers;

use App\Models\Trainer;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TrainerController extends Controller
{
    /**
     * Display all trainers and user's bookings
     */
    public function index()
    {
        $trainers = Trainer::with('user')->get();
        $myBookings = Booking::with('trainer.user')
            ->where('user_id', Auth::id())
            ->orderBy('date', 'desc')
            ->orderBy('time_slot', 'desc')
            ->get();
            
        return view('trainers.index', compact('trainers', 'myBookings'));
    }

    /**
     * Handle Trainer Booking with Conflict Prevention
     */
    public function book(Request $request)
    {
        $request->validate([
            'trainer_id' => 'required|exists:trainers,id',
            'date' => 'required|date|after_or_equal:today',
            'slot' => 'required',
        ]);

        // Check if the slot is already taken for this trainer
        $exists = Booking::where('trainer_id', $request->trainer_id)
            ->where('date', $request->date)
            ->where('slot', $request->slot)
            ->where('status', '!=', 'cancelled')
            ->exists();

        if ($exists) {
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'This slot is already booked. Please choose another time.'], 422);
            }
            return back()->with('error', 'This slot is already booked.');
        }

        try {
            $booking = Booking::create([
                'user_id' => Auth::id(),
                'trainer_id' => $request->trainer_id,
                'date' => $request->date,
                'slot' => $request->slot,
                'status' => 'confirmed',
            ]);
        } catch (\Exception $e) {
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Booking failed: ' . $e->getMessage()], 500);
            }
            return back()->with('error', 'Booking failed.');
        }

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true, 
                'message' => 'Session booked successfully!',
                'booking' => $booking->load('trainer.user')
            ]);
        }

        return back()->with('success', 'Session booked successfully!');
    }

    /**
     * Reschedule an existing booking
     */
    public function reschedule(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);

        if ($booking->user_id !== Auth::id()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized.'], 403);
        }

        $request->validate([
            'date' => 'required|date|after_or_equal:today',
            'slot' => 'required',
        ]);

        // Check for conflicts in the new slot
        $conflict = Booking::where('trainer_id', $booking->trainer_id)
            ->where('date', $request->date)
            ->where('slot', $request->slot)
            ->where('status', '!=', 'cancelled')
            ->where('id', '!=', $id)
            ->exists();

        if ($conflict) {
            return response()->json(['success' => false, 'message' => 'The new slot is already taken.'], 422);
        }

        $booking->update([
            'date' => $request->date,
            'slot' => $request->slot,
            'status' => 'confirmed' // Reactivate if it was different
        ]);

        return response()->json(['success' => true, 'message' => 'Session rescheduled successfully!']);
    }

    /**
     * Cancel a booking securely
     */
    public function cancel($id)
    {
        $booking = Booking::findOrFail($id);

        if ($booking->user_id !== Auth::id()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized action.'], 403);
        }

        $booking->status = 'cancelled';
        $booking->save();

        return response()->json(['success' => true, 'message' => 'Booking cancelled successfully.']);
    }

    /**
     * Get available slots for a trainer on a specific date
     */
    public function availableSlots($trainerId, Request $request)
    {
        $date = $request->date ?? now()->toDateString();
        
        // Dynamic working hours based on trainer specialization or defaults
        $trainer = Trainer::find($trainerId);
        $allSlots = ["09:00", "10:00", "11:00", "12:00", "14:00", "15:00", "16:00", "17:00"];

        // Simulate random trainer-specific "Unavailable Slots" (Marketplace behavior)
        // In a real app, this would check TrainerAvailability model
        $unavailable = [];
        srand(crc32($trainerId . $date)); // Consistent for same trainer/date
        if (rand(1, 10) > 7) $unavailable[] = $allSlots[array_rand($allSlots)];

        $booked = Booking::where('trainer_id', $trainerId)
            ->where('date', $date)
            ->where('status', '!=', 'cancelled')
            ->pluck('slot')
            ->toArray();

        $excluded = array_merge($booked, $unavailable);
        $available = array_values(array_diff($allSlots, $excluded));

        return response()->json($available);
    }

    /**
     * Get user's bookings via API
     */
    public function myBookings()
    {
        $bookings = Booking::with('trainer.user')
            ->where('user_id', Auth::id())
            ->orderBy('date', 'desc')
            ->orderBy('slot', 'desc')
            ->get();
            
        return response()->json($bookings);
    }
}
