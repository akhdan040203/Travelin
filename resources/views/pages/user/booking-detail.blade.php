@extends('layouts.user')

@section('title', 'Booking #' . $booking->booking_code . ' - Travelin')
@section('page_title', 'Detail Booking')

@push('styles')
<style>
/* ── Status Tracker ────────────────────────────────── */
.trk-wrap {
    display: flex;
    align-items: flex-start;
    position: relative;
}
/* connector line between steps */
.trk-connector {
    flex: 1;
    height: 3px;
    margin-top: 18px; /* center against dot (18px = half of 36px dot) */
    border-radius: 2px;
    transition: background 0.4s;
}
/* each step column */
.trk-step {
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
    min-width: 80px;
    max-width: 96px;
}
.trk-dot {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    transition: transform 0.25s, box-shadow 0.25s;
}
.trk-dot.done {
    background: #10b981;      /* emerald-500 */
    box-shadow: 0 0 0 4px #d1fae5;
}
.trk-dot.active {
    background: var(--color-primary, #ff4d6d);
    box-shadow: 0 0 0 4px rgba(255,77,109,0.18);
    animation: pulse-dot 2s ease-in-out infinite;
}
.trk-dot.cancel {
    background: #ef4444;
    box-shadow: 0 0 0 4px #fee2e2;
}
.trk-dot.waiting {
    background: #e5e7eb;      /* gray-200 */
    border: 2px dashed #9ca3af;
}
@keyframes pulse-dot {
    0%, 100% { box-shadow: 0 0 0 4px rgba(255,77,109,0.18); }
    50%       { box-shadow: 0 0 0 8px rgba(255,77,109,0.08); }
}
.trk-label {
    font-size: 0.7rem;
    font-weight: 600;
    color: #6b7280;
    margin-top: 6px;
    line-height: 1.25;
}
.trk-label.done   { color: #059669; }
.trk-label.active { color: var(--color-primary, #ff4d6d); }
.trk-label.cancel { color: #ef4444; }
.trk-time {
    font-size: 0.625rem;
    color: #9ca3af;
    margin-top: 2px;
    line-height: 1.2;
}

/* connector colors */
.trk-connector.done    { background: #10b981; }
.trk-connector.waiting { background: #e5e7eb; }
</style>
@endpush

@section('content')
<div class="max-w-4xl">

    {{-- ── Header ────────────────────────────────────── --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <a href="{{ route('user.bookings') }}"
               class="text-primary-500 text-sm font-semibold hover:text-primary-600 mb-1 inline-flex items-center gap-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Kembali
            </a>
            <h1 class="text-2xl font-bold text-dark-900">Detail Booking</h1>
        </div>
    </div>

    @if(session('success'))
    <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl p-4 mb-6 flex items-center gap-3 text-sm">
        <svg class="w-5 h-5 flex-shrink-0 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        {{ session('success') }}
    </div>
    @endif

    {{-- ══════════════════════════════════════════════════
         STATUS TRACKER — Traveloka-style
    ══════════════════════════════════════════════════ --}}
    @php
        /*
         * Step order for NORMAL flow:
         *   pending → paid → confirmed → ongoing → completed
         *
         * CANCELLED / REFUNDED are shown as a special "red" branch.
         */
        $normalFlow  = ['pending', 'paid', 'confirmed', 'ongoing', 'completed'];
        $isCancelled = in_array($booking->status, ['cancelled', 'refunded']);

        $stepMeta = [
            'pending'   => [
                'label' => 'Booking Dibuat',
                'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>',
                'time'  => $booking->created_at,
            ],
            'paid'      => [
                'label' => 'Pembayaran',
                'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>',
                'time'  => $booking->paid_at,
            ],
            'confirmed' => [
                'label' => 'Dikonfirmasi',
                'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>',
                'time'  => $booking->confirmed_at,
            ],
            'ongoing'   => [
                'label' => 'Trip Berjalan',
                'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064"/>',
                'time'  => null,
            ],
            'completed' => [
                'label' => 'Selesai',
                'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>',
                'time'  => null,
            ],
        ];

        // Find current index in normal flow
        $currentIdx = array_search($booking->status, $normalFlow);
        if ($currentIdx === false) $currentIdx = 0; // fallback
    @endphp

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-6">

        {{-- top row: kode + status badge --}}
        <div class="flex items-center justify-between mb-5">
            <div>
                <p class="text-xs text-dark-400 mb-0.5">Kode Booking</p>
                <p class="font-bold text-primary-500 font-mono tracking-wider text-lg">{{ $booking->booking_code }}</p>
            </div>
            @php
                $badgeMap = [
                    'pending'   => 'bg-yellow-100 text-yellow-700 border-yellow-200',
                    'paid'      => 'bg-emerald-100 text-emerald-700 border-emerald-200',
                    'confirmed' => 'bg-blue-100 text-blue-700 border-blue-200',
                    'ongoing'   => 'bg-indigo-100 text-indigo-700 border-indigo-200',
                    'completed' => 'bg-emerald-100 text-emerald-700 border-emerald-200',
                    'cancelled' => 'bg-red-100 text-red-700 border-red-200',
                    'refunded'  => 'bg-gray-100 text-gray-700 border-gray-200',
                ];
            @endphp
            <span class="px-3 py-1.5 rounded-full text-xs font-bold border {{ $badgeMap[$booking->status] ?? 'bg-gray-100' }}">
                {{ $booking->status_label }}
            </span>
        </div>

        @if(!$isCancelled)
        {{-- ─────── NORMAL FLOW TRACKER ─────── --}}
        <div class="trk-wrap overflow-x-auto pb-2">
            @foreach($normalFlow as $i => $step)
                @php
                    $meta = $stepMeta[$step];

                    if ($i < $currentIdx) {
                        $dotClass   = 'done';
                        $lblClass   = 'done';
                    } elseif ($i === $currentIdx) {
                        $dotClass   = 'active';
                        $lblClass   = 'active';
                    } else {
                        $dotClass   = 'waiting';
                        $lblClass   = '';
                    }

                    // connector BEFORE this step
                    $connClass = ($i > 0 && $i <= $currentIdx) ? 'done' : 'waiting';
                @endphp

                {{-- connector line (before first step: none) --}}
                @if($i > 0)
                    <div class="trk-connector {{ $connClass }}"></div>
                @endif

                <div class="trk-step">
                    <div class="trk-dot {{ $dotClass }}">
                        @if($dotClass === 'done')
                            {{-- checkmark --}}
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                            </svg>
                        @elseif($dotClass === 'active')
                            {{-- step icon --}}
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                {!! $meta['icon'] !!}
                            </svg>
                        @else
                            {{-- waiting: just number --}}
                            <span class="text-xs font-bold text-gray-400">{{ $i + 1 }}</span>
                        @endif
                    </div>
                    <p class="trk-label {{ $lblClass }}">{{ $meta['label'] }}</p>
                    @if($meta['time'])
                        <p class="trk-time">{{ \Carbon\Carbon::parse($meta['time'])->format('d M, H:i') }}</p>
                    @elseif($dotClass === 'done' || $dotClass === 'active')
                        <p class="trk-time">—</p>
                    @endif
                </div>
            @endforeach
        </div>

        {{-- Short description of current step --}}
        <div class="mt-5 rounded-xl p-4
            @if($booking->status==='pending') bg-yellow-50 border border-yellow-100
            @elseif(in_array($booking->status,['paid','confirmed'])) bg-blue-50 border border-blue-100
            @elseif($booking->status==='ongoing') bg-indigo-50 border border-indigo-100
            @elseif($booking->status==='completed') bg-emerald-50 border border-emerald-100
            @else bg-gray-50 border border-gray-100 @endif">
            <div class="flex items-start gap-3">
                <div class="mt-0.5">
                    @if($booking->status==='pending')
                        <svg class="w-5 h-5 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    @elseif(in_array($booking->status,['paid','confirmed']))
                        <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    @elseif($booking->status==='ongoing')
                        <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                    @elseif($booking->status==='completed')
                        <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                        </svg>
                    @endif
                </div>
                <div>
                    <p class="text-sm font-bold
                        @if($booking->status==='pending') text-yellow-700
                        @elseif(in_array($booking->status,['paid','confirmed'])) text-blue-700
                        @elseif($booking->status==='ongoing') text-indigo-700
                        @elseif($booking->status==='completed') text-emerald-700
                        @else text-dark-700 @endif">
                        @if($booking->status==='pending') Menunggu Pembayaran
                        @elseif($booking->status==='paid') Pembayaran Diterima — Menunggu Konfirmasi Admin
                        @elseif($booking->status==='confirmed') Booking Dikonfirmasi — Persiapkan Perjalananmu!
                        @elseif($booking->status==='ongoing') Trip Sedang Berjalan — Selamat Menikmati!
                        @elseif($booking->status==='completed') Trip Selesai — Terima Kasih Sudah Bersama Travelin!
                        @endif
                    </p>
                    <p class="text-xs mt-0.5
                        @if($booking->status==='pending') text-yellow-600
                        @elseif(in_array($booking->status,['paid','confirmed'])) text-blue-500
                        @elseif($booking->status==='ongoing') text-indigo-500
                        @elseif($booking->status==='completed') text-emerald-600
                        @else text-dark-400 @endif">
                        @if($booking->status==='pending') Segera lakukan pembayaran sebelum booking dibatalkan otomatis.
                        @elseif($booking->status==='paid') Tim kami sedang memverifikasi pembayaranmu.
                        @elseif($booking->status==='confirmed') Siap-siap berangkat! Meeting point: {{ $booking->schedule->meeting_point ?? '-' }}
                        @elseif($booking->status==='ongoing') Keberangkatan {{ $booking->schedule->departure_date->format('d M Y') }} — Kembali {{ $booking->schedule->return_date->format('d M Y') }}
                        @elseif($booking->status==='completed') Jangan lupa tulis review buat traveler lain! 🌟
                        @endif
                    </p>
                </div>
            </div>

            {{-- Bayar Ulang CTA --}}
            @if($booking->status === 'pending')
            <div class="mt-3 pt-3 border-t border-yellow-200">
                <a href="{{ route('user.bookings.pay', $booking->booking_code) }}"
                   class="inline-flex items-center gap-2 bg-primary-500 hover:bg-primary-600 text-white text-sm font-bold px-5 py-2.5 rounded-xl transition shadow-sm shadow-primary-300">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                    </svg>
                    Bayar Sekarang
                </a>
            </div>
            @endif
        </div>

        @else
        {{-- ─────── CANCELLED / REFUNDED TRACKER ─────── --}}
        <div class="trk-wrap">
            {{-- Step 1: Booking --}}
            <div class="trk-step">
                <div class="trk-dot done">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
                <p class="trk-label done">Booking Dibuat</p>
                <p class="trk-time">{{ $booking->created_at->format('d M, H:i') }}</p>
            </div>

            <div class="trk-connector done"></div>

            {{-- Step 2: Cancelled --}}
            <div class="trk-step">
                <div class="trk-dot cancel">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </div>
                <p class="trk-label cancel">{{ $booking->status === 'refunded' ? 'Direfund' : 'Dibatalkan' }}</p>
                <p class="trk-time">{{ $booking->cancelled_at ? $booking->cancelled_at->format('d M, H:i') : '—' }}</p>
            </div>
        </div>

        <div class="mt-5 bg-red-50 border border-red-100 rounded-xl p-4 flex items-start gap-3">
            <svg class="w-5 h-5 text-red-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <div>
                <p class="text-sm font-bold text-red-700">
                    Booking {{ $booking->status === 'refunded' ? 'Direfund' : 'Dibatalkan' }}
                </p>
                @if($booking->cancel_reason)
                    <p class="text-xs text-red-500 mt-0.5">Alasan: {{ $booking->cancel_reason }}</p>
                @endif
                <p class="text-xs text-red-400 mt-1">Hubungi tim kami jika ada pertanyaan lebih lanjut.</p>
            </div>
        </div>
        @endif

    </div>
    {{-- end tracker card --}}

    {{-- ── DETAIL GRID ────────────────────────────────── --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Main Info --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- Booking Info --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="font-bold text-dark-900">Informasi Pemesan</h2>
                    <span class="text-dark-400 text-xs">{{ $booking->created_at->format('d M Y, H:i') }}</span>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <span class="text-dark-400 text-xs block mb-1">Nama Kontak</span>
                        <span class="font-semibold text-dark-900 text-sm">{{ $booking->contact_name }}</span>
                    </div>
                    <div>
                        <span class="text-dark-400 text-xs block mb-1">Telepon</span>
                        <span class="font-semibold text-dark-900 text-sm">{{ $booking->contact_phone }}</span>
                    </div>
                    <div>
                        <span class="text-dark-400 text-xs block mb-1">Email</span>
                        <span class="font-semibold text-dark-900 text-sm">{{ $booking->contact_email }}</span>
                    </div>
                    <div>
                        <span class="text-dark-400 text-xs block mb-1">Metode Pembayaran</span>
                        @php $paymentLabels = ['bank_transfer'=>'Transfer Bank','e_wallet'=>'E-Wallet','credit_card'=>'Kartu Kredit']; @endphp
                        <span class="font-semibold text-dark-900 text-sm">{{ $paymentLabels[$booking->payment_method] ?? $booking->payment_method }}</span>
                    </div>
                </div>
                @if($booking->special_requests)
                <div class="mt-4 pt-4 border-t border-gray-100">
                    <span class="text-dark-400 text-xs block mb-1">Permintaan Khusus</span>
                    <p class="text-dark-600 text-sm">{{ $booking->special_requests }}</p>
                </div>
                @endif
            </div>

            {{-- Destination Info --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h2 class="font-bold text-dark-900 mb-4">Detail Perjalanan</h2>
                <div class="flex items-start gap-4">
                    <div class="w-20 h-20 bg-gradient-to-br from-primary-400 to-primary-600 rounded-xl flex-shrink-0 overflow-hidden">
                        @if($booking->schedule->destination->featured_image)
                            <img src="{{ asset('storage/' . $booking->schedule->destination->featured_image) }}"
                                 class="w-full h-full object-cover">
                        @endif
                    </div>
                    <div class="flex-1">
                        <h3 class="font-bold text-dark-900">{{ $booking->schedule->destination->name }}</h3>
                        <p class="text-dark-400 text-sm mt-1">{{ $booking->schedule->destination->location }}</p>
                        <div class="mt-3 flex flex-wrap gap-2">
                            <span class="inline-flex items-center gap-1 text-xs bg-gray-100 text-dark-600 px-3 py-1 rounded-full font-medium">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                {{ $booking->schedule->departure_date->format('d M Y') }}
                            </span>
                            <span class="inline-flex items-center gap-1 text-xs bg-gray-100 text-dark-600 px-3 py-1 rounded-full font-medium">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                                </svg>
                                {{ $booking->participants }} Peserta
                            </span>
                            @if($booking->schedule->meeting_point)
                            <span class="inline-flex items-center gap-1 text-xs bg-primary-50 text-primary-600 px-3 py-1 rounded-full font-medium">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                {{ $booking->schedule->meeting_point }}
                            </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

        </div>

        {{-- Sidebar: Ringkasan --}}
        <div>
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 sticky top-28">
                <h2 class="font-bold text-dark-900 mb-4">Ringkasan Pembayaran</h2>
                <div class="space-y-3 text-sm">
                    <div class="flex justify-between">
                        <span class="text-dark-400">Harga per orang</span>
                        <span class="text-dark-900">Rp {{ number_format($booking->price_per_person, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-dark-400">Jumlah peserta</span>
                        <span class="text-dark-900">× {{ $booking->participants }}</span>
                    </div>
                    <hr class="border-gray-100">
                    <div class="flex justify-between items-center">
                        <span class="font-bold text-dark-900">Total</span>
                        <span class="text-xl font-bold text-primary-500">{{ $booking->formatted_total_price }}</span>
                    </div>
                </div>

                @if($booking->paid_at)
                <div class="mt-4 flex items-center gap-2 text-xs text-emerald-600 bg-emerald-50 rounded-xl p-3">
                    <svg class="w-4 h-4 flex-shrink-0 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Dibayar {{ $booking->paid_at->format('d M Y, H:i') }}
                </div>
                @endif

                <a href="{{ route('destinations.show', $booking->schedule->destination->slug) }}"
                   class="btn-outline w-full text-center mt-4 block text-sm">
                    Lihat Destinasi
                </a>
            </div>
        </div>

    </div>
    {{-- end detail grid --}}

</div>
@endsection
