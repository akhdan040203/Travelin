<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BookingController extends Controller
{
    public function create($scheduleId)
    {
        $schedule = Schedule::with('destination')
            ->where('status', 'open')
            ->findOrFail($scheduleId);

        $schedules = Schedule::with('destination')
            ->where('destination_id', $schedule->destination_id)
            ->where('status', 'open')
            ->where('departure_date', '>=', now())
            ->orderBy('departure_date')
            ->get();

        return view('pages.bookings.create', compact('schedule', 'schedules'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'schedule_id' => ['required', 'exists:schedules,id'],
            'contact_name' => ['required', 'string', 'max:120'],
            'contact_phone' => ['required', 'string', 'max:30'],
            'participants' => ['required', 'integer', 'min:1', 'max:50'],
            'special_requests' => ['nullable', 'string', 'max:1000'],
        ]);

        $schedule = Schedule::with('destination')->findOrFail($data['schedule_id']);
        $participants = (int) $data['participants'];
        $price = (int) ($schedule->price ?? $schedule->destination->price);

        if (isset($schedule->available_slots) && $participants > $schedule->available_slots) {
            return back()
                ->withInput()
                ->withErrors(['participants' => 'Jumlah peserta melebihi slot tersedia.']);
        }

        $booking = Booking::create([
            'user_id' => $request->user()->id,
            'schedule_id' => $schedule->id,
            'booking_code' => 'TG-' . strtoupper(Str::random(8)),
            'contact_name' => $data['contact_name'],
            'contact_email' => $request->user()->email,
            'contact_phone' => $data['contact_phone'],
            'participants' => $participants,
            'price_per_person' => $price,
            'total_price' => $price * $participants,
            'payment_method' => 'e_wallet',
            'status' => 'pending',
            'special_requests' => $data['special_requests'] ?? null,
        ]);

        $snapToken = $this->createMidtransSnapToken($booking);

        return view('pages.bookings.payment', compact('booking', 'snapToken'));
    }

    public function show(Request $request, $bookingCode)
    {
        $booking = $request->user()
            ->bookings()
            ->with('schedule.destination')
            ->where('booking_code', $bookingCode)
            ->firstOrFail();

        return view('pages.bookings.show', compact('booking'));
    }

    private function createMidtransSnapToken(Booking $booking): ?string
    {
        if (! class_exists(\Midtrans\Config::class) || ! class_exists(\Midtrans\Snap::class)) {
            return null;
        }

        \Midtrans\Config::$serverKey = config('services.midtrans.server_key', env('MIDTRANS_SERVER_KEY'));
        \Midtrans\Config::$isProduction = (bool) config('services.midtrans.is_production', env('MIDTRANS_IS_PRODUCTION', false));
        \Midtrans\Config::$isSanitized = true;
        \Midtrans\Config::$is3ds = true;

        if (! \Midtrans\Config::$serverKey) {
            return null;
        }

        return \Midtrans\Snap::getSnapToken([
            'transaction_details' => [
                'order_id' => $booking->booking_code,
                'gross_amount' => (int) $booking->total_price,
            ],
            'customer_details' => [
                'first_name' => $booking->contact_name,
                'email' => $booking->contact_email,
                'phone' => $booking->contact_phone,
            ],
            'item_details' => [[
                'id' => (string) $booking->schedule_id,
                'price' => (int) $booking->price_per_person,
                'quantity' => (int) $booking->participants,
                'name' => Str::limit($booking->schedule->destination->name ?? 'Open Trip', 45, ''),
            ]],
        ]);
    }
}
