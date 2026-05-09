<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
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

    public function repay(Request $request, $bookingCode)
    {
        $booking = $request->user()
            ->bookings()
            ->with('schedule.destination')
            ->where('booking_code', $bookingCode)
            ->firstOrFail();

        if ($booking->status !== 'pending') {
            return redirect()
                ->route('user.bookings.show', $booking->booking_code)
                ->with('success', 'Booking ini tidak perlu dibayar ulang.');
        }

        $snapToken = $this->createMidtransSnapToken($booking, true);

        return view('pages.bookings.payment', compact('booking', 'snapToken'));
    }

    public function markPaidFromSnap(Request $request, $bookingCode)
    {
        $booking = $request->user()
            ->bookings()
            ->where('booking_code', $bookingCode)
            ->firstOrFail();

        if ($booking->status === 'pending') {
            $booking->update([
                'status' => 'paid',
                'paid_at' => $booking->paid_at ?? now(),
            ]);
        }

        return response()->json(['message' => 'OK']);
    }

    public function midtransNotification(Request $request)
    {
        $payload = $request->all();
        $serverKey = trim((string) config('services.midtrans.server_key', env('MIDTRANS_SERVER_KEY')));
        $orderId = $payload['order_id'] ?? null;
        $signature = $payload['signature_key'] ?? null;

        if (! $orderId) {
            return response()->json(['message' => 'Missing order_id'], 422);
        }

        if ($signature) {
            $expectedSignature = hash('sha512', $orderId . ($payload['status_code'] ?? '') . ($payload['gross_amount'] ?? '') . $serverKey);

            if (! hash_equals($expectedSignature, $signature)) {
                Log::warning('Invalid Midtrans notification signature', ['order_id' => $orderId]);

                return response()->json(['message' => 'Invalid signature'], 403);
            }
        }

        $booking = Booking::where('booking_code', $orderId)->first();

        if (! $booking && str_contains($orderId, '-PAY-')) {
            $bookingCode = Str::before($orderId, '-PAY-');
            $booking = Booking::where('booking_code', $bookingCode)->first();
        }

        if (! $booking) {
            Log::warning('Midtrans notification booking not found', ['order_id' => $orderId]);

            return response()->json(['message' => 'Booking not found'], 404);
        }

        $transactionStatus = $payload['transaction_status'] ?? null;
        $fraudStatus = $payload['fraud_status'] ?? null;
        $paymentMethod = $this->mapMidtransPaymentMethod($payload['payment_type'] ?? null);
        $updates = [];

        if ($paymentMethod) {
            $updates['payment_method'] = $paymentMethod;
        }

        if ($transactionStatus === 'capture') {
            if ($fraudStatus === 'accept') {
                $updates['status'] = 'paid';
                $updates['paid_at'] = $booking->paid_at ?? now();
            }
        } elseif ($transactionStatus === 'settlement') {
            $updates['status'] = 'paid';
            $updates['paid_at'] = $booking->paid_at ?? now();
        } elseif ($transactionStatus === 'pending') {
            if (! in_array($booking->status, ['paid', 'completed'], true)) {
                $updates['status'] = 'pending';
            }
        } elseif (in_array($transactionStatus, ['deny', 'cancel', 'expire'], true)) {
            if (! in_array($booking->status, ['paid', 'completed'], true)) {
                $updates['status'] = 'cancelled';
                $updates['cancelled_at'] = $booking->cancelled_at ?? now();
                $updates['cancel_reason'] = 'Midtrans status: ' . $transactionStatus;
            }
        } elseif (in_array($transactionStatus, ['refund', 'partial_refund'], true)) {
            $updates['status'] = 'refunded';
        }

        if ($updates) {
            $booking->update($updates);
        }

        Log::info('Midtrans notification processed', [
            'booking_code' => $booking->booking_code,
            'transaction_status' => $transactionStatus,
            'booking_status' => $booking->fresh()->status,
        ]);

        return response()->json(['message' => 'OK']);
    }

    private function createMidtransSnapToken(Booking $booking, bool $refreshOrderId = false): ?string
    {
        if (! class_exists(\Midtrans\Config::class) || ! class_exists(\Midtrans\Snap::class)) {
            return null;
        }

        $serverKey = trim((string) config('services.midtrans.server_key', env('MIDTRANS_SERVER_KEY')));
        $isProduction = filter_var(
            config('services.midtrans.is_production', env('MIDTRANS_IS_PRODUCTION', false)),
            FILTER_VALIDATE_BOOLEAN
        );

        \Midtrans\Config::$serverKey = $serverKey;
        \Midtrans\Config::$isProduction = $isProduction;
        \Midtrans\Config::$isSanitized = true;
        \Midtrans\Config::$is3ds = true;

        if (! $serverKey) {
            return null;
        }

        $orderId = $refreshOrderId
            ? $booking->booking_code . '-PAY-' . now()->format('His')
            : $booking->booking_code;

        try {
            return \Midtrans\Snap::getSnapToken([
                'transaction_details' => [
                    'order_id' => $orderId,
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
        } catch (\Throwable $e) {
            Log::warning('Midtrans Snap token failed', [
                'booking_code' => $booking->booking_code,
                'order_id' => $orderId,
                'message' => $e->getMessage(),
            ]);

            return null;
        }
    }

    private function mapMidtransPaymentMethod(?string $paymentType): ?string
    {
        return match ($paymentType) {
            'bank_transfer', 'echannel', 'permata' => 'bank_transfer',
            'credit_card' => 'credit_card',
            'gopay', 'shopeepay', 'qris', 'cstore', 'akulaku', 'kredivo' => 'e_wallet',
            default => null,
        };
    }
}
