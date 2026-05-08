@extends('layouts.main')

@section('title', 'History Pembelian - Travelin')

@section('content')
<section class="min-h-screen bg-gray-50 pt-32 pb-16">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-8">
            <h1 class="mt-2 text-3xl md:text-5xl font-black text-dark-900">History Pembelian</h1>
            <p class="mt-3 text-sm text-dark-400">Lihat semua booking dan status perjalanan yang pernah kamu pesan.</p>
        </div>

        <div class="space-y-4">
            @forelse($bookings ?? [] as $booking)
                <div class="rounded-3xl bg-white p-5 shadow-lg shadow-black/5 border border-gray-100 transition-all">
                    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                        <div class="min-w-0">
                            <p class="text-xs font-bold uppercase tracking-widest text-primary-500">{{ $booking->booking_code }}</p>
                            <h2 class="mt-1 text-lg font-black text-dark-900 truncate">{{ $booking->schedule->destination->name ?? 'Destinasi' }}</h2>
                            <p class="mt-1 text-sm text-dark-400">{{ optional($booking->schedule->departure_date)->format('d M Y') }} - {{ optional($booking->schedule->return_date)->format('d M Y') }}</p>
                        </div>
                        <div class="flex items-center justify-between gap-4 md:justify-end">
                            <div class="text-left md:text-right">
                                <p class="text-xs text-dark-300">Total</p>
                                <p class="font-black text-dark-900">{{ $booking->formatted_total_price ?? '-' }}</p>
                            </div>
                            <span class="rounded-full bg-primary-50 px-4 py-2 text-xs font-black uppercase text-primary-600">{{ $booking->status }}</span>
                        </div>
                    </div>
                    <div class="mt-5 flex flex-col gap-3 sm:flex-row">
                        <a href="{{ route('user.bookings.show', $booking->booking_code) }}" class="inline-flex h-11 flex-1 items-center justify-center rounded-2xl bg-gray-50 px-5 text-xs font-black text-dark-900 hover:bg-gray-100 transition">
                            Detail
                        </a>
                        @if($booking->status === 'pending')
                            <a href="{{ route('user.bookings.pay', $booking->booking_code) }}" class="inline-flex h-11 flex-1 items-center justify-center rounded-2xl bg-primary-500 px-5 text-xs font-black text-white shadow-lg shadow-primary-500/20 hover:bg-primary-600 transition">
                                Bayar Ulang
                            </a>
                        @endif
                    </div>
                </div>
            @empty
                <div class="rounded-3xl bg-white p-10 text-center shadow-lg shadow-black/5 border border-gray-100">
                    <h2 class="text-xl font-black text-dark-900">Belum ada pembelian</h2>
                    <p class="mt-2 text-sm text-dark-400">Booking trip kamu akan muncul di sini setelah melakukan pembelian.</p>
                    <a href="{{ route('destinations.index') }}" class="mt-6 inline-flex h-12 items-center justify-center rounded-full bg-primary-500 px-6 text-sm font-black text-white shadow-lg shadow-primary-500/25 hover:bg-primary-600 transition">
                        Cari Destinasi
                    </a>
                </div>
            @endforelse
        </div>

        @if(isset($bookings) && method_exists($bookings, 'links'))
            <div class="mt-8">{{ $bookings->links() }}</div>
        @endif
    </div>
</section>
@endsection
