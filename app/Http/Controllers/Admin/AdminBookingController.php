<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;

class AdminBookingController extends Controller
{
    public function index(Request $request)
    {
        $query = Booking::with(['user', 'schedule.destination'])->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('booking_code', 'like', "%{$search}%")
                  ->orWhere('contact_name', 'like', "%{$search}%")
                  ->orWhereHas('user', fn($q2) => $q2->where('name', 'like', "%{$search}%"));
            });
        }

        $bookings = $query->paginate(15)->withQueryString();

        return view('admin.bookings.index', compact('bookings'));
    }

    public function show(Booking $booking)
    {
        $booking->load(['user', 'schedule.destination', 'review']);
        return view('admin.bookings.show', compact('booking'));
    }

    public function updateStatus(Request $request, Booking $booking)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,confirmed,paid,completed,cancelled,refunded',
        ]);

        $booking->update([
            'status' => $validated['status'],
            'confirmed_at' => $validated['status'] === 'confirmed' ? now() : $booking->confirmed_at,
            'paid_at' => $validated['status'] === 'paid' ? now() : $booking->paid_at,
            'cancelled_at' => $validated['status'] === 'cancelled' ? now() : $booking->cancelled_at,
        ]);

        return back()->with('success', 'Status booking berhasil diperbarui!');
    }
}
