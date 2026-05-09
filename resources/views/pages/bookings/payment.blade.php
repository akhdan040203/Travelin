@extends('layouts.main')

@section('title', 'Pembayaran Booking - Travelin')

@section('content')
<section class="min-h-screen bg-gray-50 pt-32 pb-16">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="rounded-3xl bg-white p-6 md:p-8 shadow-xl shadow-black/5 border border-gray-100 text-center">
            <p class="text-xs font-black uppercase tracking-[0.25em] text-primary-500">Payment</p>
            <h1 class="mt-2 text-3xl font-black text-dark-900">Lanjut Pembayaran</h1>
            <p class="mt-3 text-sm text-dark-400">Kode booking kamu: <span class="font-black text-dark-900">{{ $booking->booking_code }}</span></p>

            <div class="my-6 rounded-2xl bg-gray-50 p-5 text-left">
                <div class="flex justify-between gap-4 text-sm">
                    <span class="text-dark-400">Destinasi</span>
                    <span class="font-black text-dark-900">{{ $booking->schedule->destination->name }}</span>
                </div>
                <div class="mt-3 flex justify-between gap-4 text-sm">
                    <span class="text-dark-400">Peserta</span>
                    <span class="font-black text-dark-900">{{ $booking->participants }} orang</span>
                </div>
                <div class="mt-3 flex justify-between gap-4 text-sm">
                    <span class="text-dark-400">Total</span>
                    <span class="font-black text-primary-500">{{ $booking->formatted_total_price }}</span>
                </div>
            </div>

            @if($snapToken)
                <button id="pay-button" class="inline-flex h-13 w-full items-center justify-center rounded-2xl bg-primary-500 px-6 py-4 text-sm font-black text-white shadow-lg shadow-primary-500/25 hover:bg-primary-600 transition">
                    Bayar dengan Midtrans
                </button>
            @else
                <div class="rounded-2xl bg-yellow-50 p-4 text-sm font-semibold text-yellow-700">
                    Midtrans belum bisa membuat token pembayaran. Pastikan Sandbox Server Key dan Client Key benar, lalu bersihkan cache config.
                </div>
                <a href="{{ route('user.bookings.show', $booking->booking_code) }}" class="mt-4 inline-flex h-12 w-full items-center justify-center rounded-2xl bg-dark-900 px-6 text-sm font-black text-white">
                    Lihat Booking
                </a>
            @endif
        </div>
    </div>
</section>
@endsection

@if($snapToken)
@push('scripts')
@php
    $midtransIsProduction = filter_var(config('services.midtrans.is_production', env('MIDTRANS_IS_PRODUCTION', false)), FILTER_VALIDATE_BOOLEAN);
    $midtransSnapUrl = $midtransIsProduction ? 'https://app.midtrans.com/snap/snap.js' : 'https://app.sandbox.midtrans.com/snap/snap.js';
    $midtransClientKey = trim((string) config('services.midtrans.client_key', env('MIDTRANS_CLIENT_KEY')));
@endphp
<script src="{{ $midtransSnapUrl }}" data-client-key="{{ $midtransClientKey }}"></script>
<script>
    async function markBookingPaid() {
        await fetch('{{ route('user.bookings.markPaid', $booking->booking_code) }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        });
    }

    document.getElementById('pay-button')?.addEventListener('click', function () {
        window.snap.pay('{{ $snapToken }}', {
            onSuccess: async function () {
                await markBookingPaid();
                window.location.href = '{{ route('user.bookings.show', $booking->booking_code) }}';
            },
            onPending: function () {
                window.location.href = '{{ route('user.bookings.show', $booking->booking_code) }}';
            },
            onError: function () {
                window.location.href = '{{ route('user.bookings.show', $booking->booking_code) }}';
            }
        });
    });
</script>
@endpush
@endif
