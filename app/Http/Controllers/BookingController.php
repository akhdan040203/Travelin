<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;

class BookingController extends Controller
{

    public function create(int $scheduleId)
    {
        $schedule = \App\Models\Schedule::with('destination')
            ->where('status', 'open')
            ->findOrFail($scheduleId);

        if (!$schedule->is_available) {
            return redirect()->route('destinations.show', $schedule->destination->slug)
                ->with('error', 'Jadwal ini sudah tidak tersedia.');
        }

        return view('pages.booking', compact('schedule'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'schedule_id' => 'required|exists:schedules,id',
            'participants' => 'required|integer|min:1|max:10',
            'contact_name' => 'required|string|max:255',
            'contact_phone' => 'required|string|max:20',
            'contact_email' => 'required|email|max:255',
            'special_requests' => 'nullable|string|max:1000',
            'payment_method' => 'required|in:bank_transfer,e_wallet,credit_card',
        ]);

        $schedule = \App\Models\Schedule::with('destination')->findOrFail($validated['schedule_id']);

        // Check availability
        if (!$schedule->is_available) {
            return back()->with('error', 'Maaf, jadwal ini sudah tidak tersedia.');
        }

        if ($schedule->available_slots < $validated['participants']) {
            return back()->with('error', 'Maaf, kuota tidak mencukupi. Sisa ' . $schedule->available_slots . ' kursi.');
        }

        $pricePerPerson = $schedule->effective_price;
        $totalPrice = $pricePerPerson * $validated['participants'];

        $booking = Booking::create([
            'user_id' => auth()->id(),
            'schedule_id' => $validated['schedule_id'],
            'participants' => $validated['participants'],
            'price_per_person' => $pricePerPerson,
            'total_price' => $totalPrice,
            'contact_name' => $validated['contact_name'],
            'contact_phone' => $validated['contact_phone'],
            'contact_email' => $validated['contact_email'],
            'special_requests' => $validated['special_requests'] ?? null,
            'payment_method' => $validated['payment_method'],
            'status' => 'pending',
        ]);

        // Update booked count
        $schedule->increment('booked', $validated['participants']);

        return redirect()->route('user.bookings.show', $booking->booking_code)
            ->with('success', 'Booking berhasil! Silakan lakukan pembayaran.');
    }

    public function show(string $bookingCode)
    {
        $booking = Booking::with(['schedule.destination', 'review'])
            ->where('booking_code', $bookingCode)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        return view('pages.user.booking-detail', compact('booking'));
    }
}
