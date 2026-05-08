@extends('layouts.main')

@section('title', 'Detail Booking - Travelin')

@section('content')
<section class="min-h-screen bg-gray-50 pt-32 pb-16">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="rounded-3xl bg-white p-6 md:p-8 shadow-xl shadow-black/5 border border-gray-100">
            <p class="text-xs font-black uppercase tracking-[0.25em] text-primary-500">{{ $booking->booking_code }}</p>
            <h1 class="mt-2 text-3xl font-black text-dark-900">{{ $booking->schedule->destination->name }}</h1>
            <p class="mt-2 text-sm text-dark-400">{{ $booking->schedule->destination->location }}</p>

            <div class="my-6 h-px bg-gray-100"></div>

            <div class="grid gap-4 md:grid-cols-2">
                <div class="rounded-2xl bg-gray-50 p-4">
                    <span class="text-xs text-dark-400">Nama</span>
                    <p class="mt-1 font-black text-dark-900">{{ $booking->contact_name }}</p>
                </div>
                <div class="rounded-2xl bg-gray-50 p-4">
                    <span class="text-xs text-dark-400">No HP</span>
                    <p class="mt-1 font-black text-dark-900">{{ $booking->contact_phone }}</p>
                </div>
                <div class="rounded-2xl bg-gray-50 p-4">
                    <span class="text-xs text-dark-400">Peserta</span>
                    <p class="mt-1 font-black text-dark-900">{{ $booking->participants }} orang</p>
                </div>
                <div class="rounded-2xl bg-gray-50 p-4">
                    <span class="text-xs text-dark-400">Status</span>
                    <p class="mt-1 font-black text-primary-500">{{ strtoupper($booking->status) }}</p>
                </div>
            </div>

            <div class="mt-6 flex items-center justify-between rounded-2xl bg-primary-50 p-5">
                <span class="font-bold text-dark-500">Total Pembayaran</span>
                <span class="text-xl font-black text-primary-500">{{ $booking->formatted_total_price }}</span>
            </div>

            @if($booking->status === 'pending')
                <a href="{{ route('user.bookings.pay', $booking->booking_code) }}" class="mt-5 inline-flex h-13 w-full items-center justify-center rounded-2xl bg-primary-500 px-6 py-4 text-sm font-black text-white shadow-lg shadow-primary-500/25 hover:bg-primary-600 transition">
                    Bayar Ulang
                </a>
            @endif
        </div>
    </div>
</section>
@endsection
