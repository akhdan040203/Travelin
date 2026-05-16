@extends('layouts.main')

@section('title', 'Detail Booking - Travelin')

@push('styles')
<style>
/* ── Status Tracker ────────────────────────────────── */
.trk-wrap {
    display: flex;
    align-items: flex-start;
    position: relative;
}
.trk-connector {
    flex: 1;
    height: 3px;
    margin-top: 18px;
    border-radius: 2px;
    transition: background 0.4s;
}
.trk-step {
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
    min-width: 72px;
    max-width: 90px;
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
.trk-dot.done   { background: #10b981; box-shadow: 0 0 0 4px #d1fae5; }
.trk-dot.active { background: var(--color-primary,#ff4d6d); box-shadow: 0 0 0 4px rgba(255,77,109,0.18); animation: pulse-dot 2s ease-in-out infinite; }
.trk-dot.cancel { background: #ef4444; box-shadow: 0 0 0 4px #fee2e2; }
.trk-dot.waiting{ background: #e5e7eb; border: 2px dashed #9ca3af; }
@keyframes pulse-dot {
    0%,100%{ box-shadow: 0 0 0 4px rgba(255,77,109,0.18); }
    50%    { box-shadow: 0 0 0 8px rgba(255,77,109,0.08); }
}
.trk-label      { font-size:.65rem; font-weight:600; color:#6b7280; margin-top:6px; line-height:1.25; }
.trk-label.done { color:#059669; }
.trk-label.active{ color:var(--color-primary,#ff4d6d); }
.trk-label.cancel{ color:#ef4444; }
.trk-time       { font-size:.6rem; color:#9ca3af; margin-top:2px; line-height:1.2; }
.trk-connector.done   { background:#10b981; }
.trk-connector.waiting{ background:#e5e7eb; }
</style>
@endpush

@section('content')
<section class="min-h-screen bg-gray-50 pt-32 pb-16">
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

    {{-- Back --}}
    <a href="{{ route('user.bookings') }}"
       class="inline-flex items-center gap-1 text-primary-500 text-sm font-semibold hover:text-primary-600">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
        Riwayat Booking
    </a>

    @php
        $normalFlow = ['pending','paid','confirmed','ongoing','completed'];
        $isCancelled = in_array($booking->status, ['cancelled','refunded']);
        $currentIdx = array_search($booking->status, $normalFlow);
        if ($currentIdx === false) $currentIdx = 0;

        $stepMeta = [
            'pending'   => ['label'=>'Booking Dibuat','icon'=>'<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>','time'=>$booking->created_at],
            'paid'      => ['label'=>'Pembayaran','icon'=>'<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>','time'=>$booking->paid_at],
            'confirmed' => ['label'=>'Dikonfirmasi','icon'=>'<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>','time'=>$booking->confirmed_at],
            'ongoing'   => ['label'=>'Trip Berjalan','icon'=>'<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>','time'=>null],
            'completed' => ['label'=>'Selesai','icon'=>'<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>','time'=>null],
        ];
    @endphp

    {{-- ── TRACKER CARD ── --}}
    <div class="rounded-2xl bg-white shadow-lg shadow-black/5 border border-gray-100 p-6">

        {{-- Header --}}
        <div class="flex items-center justify-between mb-5">
            <div>
                <p class="text-xs text-dark-400 mb-0.5">Kode Booking</p>
                <p class="font-bold text-primary-500 font-mono tracking-wider text-lg">{{ $booking->booking_code }}</p>
            </div>
            @php
                $badgeMap = ['pending'=>'bg-yellow-100 text-yellow-700 border-yellow-200','paid'=>'bg-emerald-100 text-emerald-700 border-emerald-200','confirmed'=>'bg-blue-100 text-blue-700 border-blue-200','ongoing'=>'bg-indigo-100 text-indigo-700 border-indigo-200','completed'=>'bg-emerald-100 text-emerald-700 border-emerald-200','cancelled'=>'bg-red-100 text-red-700 border-red-200','refunded'=>'bg-gray-100 text-gray-700 border-gray-200'];
            @endphp
            <span class="px-3 py-1.5 rounded-full text-xs font-bold border {{ $badgeMap[$booking->status] ?? 'bg-gray-100' }}">
                {{ $booking->status_label }}
            </span>
        </div>

        @if(!$isCancelled)
        {{-- Normal tracker --}}
        <div class="trk-wrap overflow-x-auto pb-2">
            @foreach($normalFlow as $i => $step)
            @php
                $meta = $stepMeta[$step];
                if ($i < $currentIdx)       { $dc='done';    $lc='done'; }
                elseif ($i === $currentIdx) { $dc='active';  $lc='active'; }
                else                        { $dc='waiting'; $lc=''; }
                $connClass = ($i > 0 && $i <= $currentIdx) ? 'done' : 'waiting';
            @endphp
            @if($i > 0)<div class="trk-connector {{ $connClass }}"></div>@endif
            <div class="trk-step">
                <div class="trk-dot {{ $dc }}">
                    @if($dc==='done')
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                    @elseif($dc==='active')
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">{!! $meta['icon'] !!}</svg>
                    @else
                        <span class="text-xs font-bold text-gray-400">{{ $i+1 }}</span>
                    @endif
                </div>
                <p class="trk-label {{ $lc }}">{{ $meta['label'] }}</p>
                @if($meta['time'])<p class="trk-time">{{ \Carbon\Carbon::parse($meta['time'])->format('d M, H:i') }}</p>
                @elseif($dc!=='waiting')<p class="trk-time">—</p>@endif
            </div>
            @endforeach
        </div>

        {{-- Status message --}}
        <div class="mt-5 rounded-xl p-4
            @if($booking->status==='pending') bg-yellow-50 border border-yellow-100
            @elseif(in_array($booking->status,['paid','confirmed'])) bg-blue-50 border border-blue-100
            @elseif($booking->status==='ongoing') bg-indigo-50 border border-indigo-100
            @elseif($booking->status==='completed') bg-emerald-50 border border-emerald-100
            @else bg-gray-50 border border-gray-100 @endif">
            <p class="text-sm font-bold
                @if($booking->status==='pending') text-yellow-700
                @elseif(in_array($booking->status,['paid','confirmed'])) text-blue-700
                @elseif($booking->status==='ongoing') text-indigo-700
                @elseif($booking->status==='completed') text-emerald-700
                @else text-dark-700 @endif">
                @if($booking->status==='pending') ⏳ Menunggu Pembayaran
                @elseif($booking->status==='paid') ✅ Pembayaran Diterima — Menunggu Konfirmasi Admin
                @elseif($booking->status==='confirmed') 🎉 Booking Dikonfirmasi — Persiapkan Perjalananmu!
                @elseif($booking->status==='ongoing') ✈️ Trip Sedang Berjalan — Selamat Menikmati!
                @elseif($booking->status==='completed') 🌟 Trip Selesai — Terima Kasih Sudah Bersama Travelin!
                @endif
            </p>

            @if($booking->status === 'pending')
            <div class="mt-3 pt-3 border-t border-yellow-200">
                <a href="{{ route('user.bookings.pay', $booking->booking_code) }}"
                   class="inline-flex items-center gap-2 bg-primary-500 hover:bg-primary-600 text-white text-sm font-bold px-5 py-2.5 rounded-xl transition shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                    </svg>
                    Bayar Sekarang
                </a>
            </div>
            @endif
        </div>

        @else
        {{-- Cancelled tracker --}}
        <div class="trk-wrap">
            <div class="trk-step">
                <div class="trk-dot done"><svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg></div>
                <p class="trk-label done">Booking Dibuat</p>
                <p class="trk-time">{{ $booking->created_at->format('d M, H:i') }}</p>
            </div>
            <div class="trk-connector done"></div>
            <div class="trk-step">
                <div class="trk-dot cancel"><svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg></div>
                <p class="trk-label cancel">{{ $booking->status==='refunded' ? 'Direfund' : 'Dibatalkan' }}</p>
                <p class="trk-time">{{ $booking->cancelled_at ? $booking->cancelled_at->format('d M, H:i') : '—' }}</p>
            </div>
        </div>
        <div class="mt-5 bg-red-50 border border-red-100 rounded-xl p-4 text-sm text-red-700">
            <p class="font-bold">Booking Dibatalkan</p>
            @if($booking->cancel_reason)<p class="text-xs mt-1 text-red-500">{{ $booking->cancel_reason }}</p>@endif
        </div>
        @endif

    </div>

    {{-- ── INFO CARD ── --}}
    <div class="rounded-2xl bg-white shadow-lg shadow-black/5 border border-gray-100 p-6 space-y-5">

        {{-- Destination --}}
        <div class="flex items-start gap-4">
            <div class="w-16 h-16 rounded-xl bg-gradient-to-br from-primary-400 to-primary-600 flex-shrink-0 overflow-hidden">
                @if($booking->schedule->destination->featured_image)
                    <img src="{{ asset('storage/'.$booking->schedule->destination->featured_image) }}" class="w-full h-full object-cover">
                @endif
            </div>
            <div>
                <h1 class="font-black text-dark-900 text-lg">{{ $booking->schedule->destination->name }}</h1>
                <p class="text-dark-400 text-sm">{{ $booking->schedule->destination->location }}</p>
                <p class="text-dark-400 text-xs mt-1">
                    {{ $booking->schedule->departure_date->format('d M Y') }} →
                    {{ $booking->schedule->return_date->format('d M Y') }}
                </p>
            </div>
        </div>

        <div class="h-px bg-gray-100"></div>

        {{-- Details grid --}}
        <div class="grid grid-cols-2 gap-4">
            <div class="rounded-xl bg-gray-50 p-4">
                <span class="text-xs text-dark-400">Nama</span>
                <p class="mt-1 font-bold text-dark-900 text-sm">{{ $booking->contact_name }}</p>
            </div>
            <div class="rounded-xl bg-gray-50 p-4">
                <span class="text-xs text-dark-400">No HP</span>
                <p class="mt-1 font-bold text-dark-900 text-sm">{{ $booking->contact_phone }}</p>
            </div>
            <div class="rounded-xl bg-gray-50 p-4">
                <span class="text-xs text-dark-400">Peserta</span>
                <p class="mt-1 font-bold text-dark-900 text-sm">{{ $booking->participants }} orang</p>
            </div>
            <div class="rounded-xl bg-gray-50 p-4">
                <span class="text-xs text-dark-400">Metode Bayar</span>
                @php $pl=['bank_transfer'=>'Transfer Bank','e_wallet'=>'E-Wallet','credit_card'=>'Kartu Kredit']; @endphp
                <p class="mt-1 font-bold text-dark-900 text-sm">{{ $pl[$booking->payment_method] ?? $booking->payment_method }}</p>
            </div>
        </div>

        {{-- Total --}}
        <div class="flex items-center justify-between rounded-xl bg-primary-50 p-4">
            <span class="font-bold text-dark-700 text-sm">Total Pembayaran</span>
            <span class="text-xl font-black text-primary-500">{{ $booking->formatted_total_price }}</span>
        </div>

    </div>

</div>
</section>
@endsection
