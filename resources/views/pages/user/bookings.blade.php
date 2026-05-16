@extends('layouts.user')

@section('title', 'History Pembelian - Travelin')
@section('page_title', 'History Pembelian')

@push('styles')
<style>
/* ── Tracker Mini Layout ───────────────────────────── */
.trk-mini-wrap {
    display: flex;
    align-items: flex-start;
    padding: 1.5rem 0.5rem 1rem;
    position: relative;
}
.trk-mini-step {
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
    min-width: 60px;
    flex: 1;
    z-index: 2;
}
.trk-mini-dot {
    width: 26px;
    height: 26px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    background: #fff;
    transition: all 0.3s;
    border: 2px solid #e5e7eb;
}
.trk-mini-dot.done {
    background: #10b981;
    border-color: #10b981;
    box-shadow: 0 0 0 4px #d1fae5;
}
.trk-mini-dot.active {
    background: var(--color-primary, #ff4d6d);
    border-color: var(--color-primary, #ff4d6d);
    box-shadow: 0 0 0 5px rgba(255, 77, 109, 0.2);
}
.trk-mini-dot.waiting {
    background: #f9fafb;
    border-color: #e5e7eb;
}
.trk-mini-dot.cancel {
    background: #ef4444;
    border-color: #ef4444;
    box-shadow: 0 0 0 4px #fee2e2;
}

.trk-mini-connector {
    position: absolute;
    top: 37px; /* adjusted to center of 26px dot */
    height: 2px;
    background: #e5e7eb;
    z-index: 1;
}
.trk-mini-connector.done {
    background: #10b981;
}

.trk-mini-label {
    font-size: 0.65rem;
    font-weight: 800;
    color: #9ca3af;
    margin-top: 8px;
    line-height: 1;
}
.trk-mini-label.done { color: #059669; }
.trk-mini-label.active { color: var(--color-primary, #ff4d6d); }
.trk-mini-label.cancel { color: #ef4444; }

/* Hide scrollbar */
.trk-mini-wrap::-webkit-scrollbar { display: none; }
.trk-mini-wrap { -ms-overflow-style: none; scrollbar-width: none; overflow-x: auto; }
</style>
@endpush

@section('content')
    <div class="max-w-3xl">
        <div class="mb-8">
            <h2 class="text-xl font-black text-dark-900">History Pesanan</h2>
            <p class="text-dark-400 text-sm font-medium">Lacak perjalananmu secara real-time</p>
        </div>

        @forelse($bookings as $booking)
            @php
                $normalFlow = ['pending', 'paid', 'confirmed', 'ongoing', 'completed'];
                $isCancelled = in_array($booking->status, ['cancelled', 'refunded']);
                $currentIdx = array_search($booking->status, $normalFlow);
                if ($currentIdx === false) $currentIdx = 0;

                $statusLabels = [
                    'pending'   => 'Booking',
                    'paid'      => 'Dibayar',
                    'confirmed' => 'Siap',
                    'ongoing'   => 'Di Trip',
                    'completed' => 'Selesai'
                ];

                $badgeMap = [
                    'pending'   => 'bg-rose-50 text-rose-500',
                    'paid'      => 'bg-emerald-50 text-emerald-600',
                    'confirmed' => 'bg-blue-50 text-blue-600',
                    'ongoing'   => 'bg-indigo-50 text-indigo-600',
                    'completed' => 'bg-emerald-50 text-emerald-600',
                    'cancelled' => 'bg-red-50 text-red-600',
                    'refunded'  => 'bg-gray-100 text-gray-600',
                ];
            @endphp

            <div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 mb-6 overflow-hidden transition-all hover:shadow-xl hover:shadow-black/5">
                <div class="p-6 md:p-8">
                    {{-- Header Row --}}
                    <div class="flex items-start justify-between gap-4">
                        <div class="space-y-1">
                            <span class="text-[10px] font-black uppercase tracking-widest text-primary-500 bg-primary-50 px-3 py-1 rounded-full">
                                {{ $booking->booking_code }}
                            </span>
                            <h3 class="text-xl font-black text-dark-900 pt-2">
                                {{ $booking->schedule->destination->name }}
                            </h3>
                            <div class="flex items-center gap-3 text-xs text-dark-400 font-bold mt-2">
                                <span class="flex items-center gap-1.5">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    {{ $booking->schedule->departure_date->format('d M') }} - {{ $booking->schedule->return_date->format('d M Y') }}
                                </span>
                                <span class="w-1 h-1 rounded-full bg-gray-300"></span>
                                <span class="flex items-center gap-1.5">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                                    {{ $booking->participants }} Peserta
                                </span>
                            </div>
                        </div>

                        <div class="text-right flex flex-col items-end gap-3">
                            <div class="px-4 py-2 rounded-2xl text-[10px] font-black uppercase tracking-wider {{ $badgeMap[$booking->status] ?? 'bg-gray-100' }}">
                                {{ $booking->status_label }}
                            </div>
                            <div>
                                <span class="text-[10px] text-dark-300 font-black uppercase tracking-tighter block">Total Bayar</span>
                                <p class="text-xl font-black text-dark-900">{{ $booking->formatted_total_price }}</p>
                            </div>
                        </div>
                    </div>

                    {{-- Tracker Row --}}
                    <div class="mt-8 pt-6 border-t border-gray-50 relative">
                        @if(!$isCancelled)
                            <div class="trk-mini-wrap">
                                 @foreach($normalFlow as $i => $step)
                                    @php
                                        $dotClass = ($i < $currentIdx) ? 'done' : (($i === $currentIdx) ? 'active' : 'waiting');
                                        $lblClass = ($i < $currentIdx) ? 'done' : (($i === $currentIdx) ? 'active' : '');
                                    @endphp

                                    @if($i > 0)
                                        <div class="trk-mini-connector {{ $i <= $currentIdx ? 'done' : '' }}"
                                             style="left: {{ (100 / count($normalFlow)) * ($i - 1) + (100 / (count($normalFlow)*2)) }}%; width: {{ 100 / count($normalFlow) }}%;">
                                        </div>
                                    @endif

                                    <div class="trk-mini-step">
                                        <div class="trk-mini-dot {{ $dotClass }}">
                                            @if($dotClass === 'done')
                                                <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                            @elseif($dotClass === 'active')
                                                <div class="w-2 h-2 bg-white rounded-full animate-pulse"></div>
                                            @endif
                                        </div>
                                        <span class="trk-mini-label {{ $lblClass }}">{{ $statusLabels[$step] }}</span>
                                    </div>
                                 @endforeach
                            </div>
                        @else
                            <div class="trk-mini-wrap">
                                 <div class="trk-mini-step">
                                     <div class="trk-mini-dot done"><svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg></div>
                                     <span class="trk-mini-label done">Booking</span>
                                 </div>
                                 <div class="trk-mini-connector" style="left: 10%; width: 33%;"></div>
                                 <div class="trk-mini-step">
                                     <div class="trk-mini-dot cancel"><svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"/></svg></div>
                                     <span class="trk-mini-label cancel">{{ $booking->status === 'refunded' ? 'Refunded' : 'Batal' }}</span>
                                 </div>
                                 @for($i=0; $i<3; $i++) <div class="trk-mini-step opacity-10"><div class="trk-mini-dot waiting"></div></div> @endfor
                            </div>
                        @endif
                    </div>

                    {{-- Action Row --}}
                    @if($booking->status === 'pending')
                    <div class="mt-8">
                        <a href="{{ route('user.bookings.pay', $booking->booking_code) }}"
                           class="block w-full bg-primary-500 hover:bg-primary-600 text-white text-xs font-black py-4 rounded-[1.25rem] text-center shadow-xl shadow-primary-500/25 transition-all uppercase tracking-widest">
                            Bayar Sekarang
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        @empty
            <div class="bg-white rounded-[2rem] shadow-sm p-20 text-center border border-gray-100">
                <h3 class="text-xl font-black text-dark-900">Belum ada pesanan</h3>
                <p class="text-dark-400 mt-2 text-sm font-medium">History perjalanan kamu akan muncul di sini.</p>
                <a href="{{ route('destinations.index') }}" class="btn-primary mt-8 inline-block px-12">Cari Destinasi</a>
            </div>
        @endforelse

        <div class="mt-8">{{ $bookings->links() }}</div>
    </div>
@endsection
