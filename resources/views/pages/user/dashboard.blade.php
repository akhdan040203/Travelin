@extends('layouts.user')

@section('title', 'Dashboard - Travelin')
@section('page_title', 'Dashboard')

@section('content')
    {{-- Welcome Header --}}
    <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4 mb-8">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-primary-400 to-primary-600 flex items-center justify-center text-white text-xl font-bold shadow-lg shadow-primary-500/30">
                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
            </div>
            <div>
                <h1 class="text-2xl font-bold text-dark-900">Halo, {{ auth()->user()->name }}!</h1>
                <p class="text-dark-400 text-sm">Selamat datang di dashboard kamu</p>
            </div>
        </div>
    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <div class="bg-white rounded-2xl p-5 shadow-sm hover:shadow-md transition-shadow border border-gray-100/80">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center">
                    <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                </div>
            </div>
            <p class="text-2xl font-bold text-dark-900">{{ $stats['total_bookings'] }}</p>
            <p class="text-dark-400 text-xs mt-0.5">Total Booking</p>
        </div>
        <div class="bg-white rounded-2xl p-5 shadow-sm hover:shadow-md transition-shadow border border-gray-100/80">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 rounded-xl bg-amber-50 flex items-center justify-center">
                    <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
            <p class="text-2xl font-bold text-dark-900">{{ $stats['active_bookings'] }}</p>
            <p class="text-dark-400 text-xs mt-0.5">Booking Aktif</p>
        </div>
        <div class="bg-white rounded-2xl p-5 shadow-sm hover:shadow-md transition-shadow border border-gray-100/80">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 rounded-xl bg-emerald-50 flex items-center justify-center">
                    <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 13l4 4L19 7"/></svg>
                </div>
            </div>
            <p class="text-2xl font-bold text-dark-900">{{ $stats['completed_trips'] }}</p>
            <p class="text-dark-400 text-xs mt-0.5">Trip Selesai</p>
        </div>
        <div class="bg-white rounded-2xl p-5 shadow-sm hover:shadow-md transition-shadow border border-gray-100/80">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 rounded-xl bg-primary-50 flex items-center justify-center">
                    <svg class="w-5 h-5 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
            <p class="text-2xl font-bold text-dark-900">Rp {{ number_format($stats['total_spent'], 0, ',', '.') }}</p>
            <p class="text-dark-400 text-xs mt-0.5">Total Pengeluaran</p>
        </div>
    </div>

    {{-- Recent Bookings --}}
    <div class="bg-white rounded-2xl shadow-sm overflow-hidden border border-gray-100/80">
        <div class="flex items-center justify-between p-6 border-b border-gray-100">
            <h2 class="text-lg font-bold text-dark-900">Booking Terbaru</h2>
            <a href="{{ route('user.bookings') }}" class="text-primary-500 text-sm font-semibold hover:text-primary-600">Lihat Semua →</a>
        </div>

        @forelse($bookings as $booking)
            <a href="{{ route('user.bookings.show', $booking->booking_code) }}"
               class="flex items-center gap-4 p-5 border-b border-gray-50 hover:bg-gray-50/50 transition-colors">
                {{-- Date Badge --}}
                <div class="w-14 h-14 bg-primary-50 rounded-xl flex flex-col items-center justify-center flex-shrink-0">
                    <span class="text-lg font-bold text-primary-500">{{ $booking->schedule->departure_date->format('d') }}</span>
                    <span class="text-[10px] font-medium text-primary-500 uppercase">{{ $booking->schedule->departure_date->format('M') }}</span>
                </div>

                {{-- Info --}}
                <div class="flex-1 min-w-0">
                    <h3 class="font-semibold text-dark-900 text-sm truncate">{{ $booking->schedule->destination->name }}</h3>
                    <p class="text-dark-400 text-xs mt-0.5">{{ $booking->booking_code }} · {{ $booking->participants }} orang</p>
                </div>

                {{-- Status & Price --}}
                <div class="text-right flex-shrink-0">
                    @php
                        $statusStyles = [
                            'pending' => 'bg-yellow-100 text-yellow-700',
                            'confirmed' => 'bg-blue-100 text-blue-700',
                            'paid' => 'bg-emerald-100 text-emerald-700',
                            'completed' => 'bg-emerald-100 text-emerald-700',
                            'cancelled' => 'bg-red-100 text-red-700',
                            'refunded' => 'bg-gray-100 text-gray-700',
                        ];
                        $statusLabels = [
                            'pending' => 'Menunggu',
                            'confirmed' => 'Dikonfirmasi',
                            'paid' => 'Dibayar',
                            'completed' => 'Selesai',
                            'cancelled' => 'Dibatalkan',
                            'refunded' => 'Refund',
                        ];
                    @endphp
                    <span class="inline-block px-2.5 py-1 rounded-full text-[10px] font-semibold {{ $statusStyles[$booking->status] ?? 'bg-gray-100 text-gray-700' }}">
                        {{ $statusLabels[$booking->status] ?? $booking->status }}
                    </span>
                    <p class="text-primary-500 font-bold text-sm mt-1">{{ $booking->formatted_total_price }}</p>
                </div>
            </a>
        @empty
            <div class="p-10 text-center">
                <svg class="w-12 h-12 text-dark-200 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                <p class="text-dark-400 text-sm">Belum ada booking</p>
                <a href="{{ route('destinations.index') }}" class="btn-primary text-sm mt-4 inline-block">Mulai Jelajahi</a>
            </div>
        @endforelse
    </div>

    {{-- Promo Banner --}}
    <div class="mt-8 bg-gradient-to-r from-primary-500 to-primary-600 rounded-2xl p-8 flex flex-col md:flex-row items-center justify-between gap-4">
        <div>
            <h3 class="text-xl font-bold text-white mb-1">Promo Spesial! 🎉</h3>
            <p class="text-white/80 text-sm">Diskon hingga 20% untuk booking bulan ini. Jangan sampai terlewat!</p>
        </div>
        <a href="{{ route('destinations.index') }}" class="btn-white text-sm whitespace-nowrap">
            Lihat Promo →
        </a>
    </div>
@endsection
