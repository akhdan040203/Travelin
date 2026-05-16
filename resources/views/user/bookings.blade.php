@extends('layouts.user')

@section('title', 'History Pembelian - Travelin')
@section('page_title', 'History Pembelian')

@section('content')
    <div class="mb-6">
        <p class="text-dark-400 text-sm italic font-medium">Lacak status pesanan dan history perjalanan kamu</p>
    </div>

    @forelse($bookings as $booking)
        @php
            // Sequence 4 Tahap: Booking -> Waiting -> Perjalanan -> Selesai
            $flow = ['pending', 'confirmed', 'ongoing', 'completed'];
            // Jika status 'paid' (sudah bayar tapi belum dikonfirmasi admin), kita anggap masih di posisi 'Booking' menuju 'Waiting'
            $isCancelled = in_array($booking->status, ['cancelled', 'refunded']);
            
            // Logika index aktif
            if ($booking->status === 'paid') {
                $currentIdx = 0.5; // Berada di antara Booking dan Waiting
            } else {
                $currentIdx = array_search($booking->status, $flow);
                if ($currentIdx === false) $currentIdx = 0;
            }

            $statusLabels = [
                'pending'   => 'Booking',
                'confirmed' => 'Waiting',
                'ongoing'   => 'Perjalanan',
                'completed' => 'Selesai'
            ];
            
            $statusStyles = [
                'pending'   => 'bg-rose-50 text-rose-600',
                'paid'      => 'bg-blue-50 text-blue-600',
                'confirmed' => 'bg-emerald-50 text-emerald-700',
                'ongoing'   => 'bg-indigo-50 text-indigo-700',
                'completed' => 'bg-emerald-50 text-emerald-700',
                'cancelled' => 'bg-red-50 text-red-700',
                'refunded'  => 'bg-gray-100 text-gray-700',
            ];
        @endphp

        <div class="bg-white rounded-2xl shadow-sm mb-5 overflow-hidden border border-gray-100/80 transition-all hover:shadow-md">
            <div class="p-5 md:p-6">
                {{-- Header Card --}}
                <div class="flex flex-col md:flex-row justify-between gap-4">
                    <div class="flex items-start gap-4">
                        <div class="w-14 h-14 bg-primary-50 rounded-xl flex flex-col items-center justify-center flex-shrink-0">
                            <span class="text-lg font-bold text-primary-500">{{ $booking->schedule->departure_date->format('d') }}</span>
                            <span class="text-[10px] font-medium text-primary-500 uppercase">{{ $booking->schedule->departure_date->format('M') }}</span>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-primary-500 uppercase tracking-wider mb-1">{{ $booking->booking_code }}</p>
                            <h3 class="font-bold text-dark-900 text-lg leading-tight">{{ $booking->schedule->destination->name }}</h3>
                            <div class="flex items-center gap-2 mt-1">
                                <span class="text-dark-400 text-xs font-medium">{{ $booking->schedule->departure_date->format('d M') }} - {{ $booking->schedule->return_date->format('d M Y') }}</span>
                                <span class="text-gray-300">•</span>
                                <span class="text-dark-400 text-xs font-medium">{{ $booking->participants }} Peserta</span>
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-col items-end gap-2 text-right">
                        <span class="inline-block px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wide {{ $statusStyles[$booking->status] ?? 'bg-gray-100 text-gray-700' }}">
                            {{ $booking->status_label }}
                        </span>
                        <div class="mt-1">
                             <span class="text-[9px] text-dark-300 font-bold uppercase block tracking-tighter">Total Bayar</span>
                             <p class="text-xl font-black text-dark-900 leading-none mt-1">{{ $booking->formatted_total_price }}</p>
                        </div>
                    </div>
                </div>

                {{-- Tracker 4 Tahap --}}
                <div class="mt-8 pt-6 border-t border-gray-50">
                    <div class="relative flex justify-between">
                        {{-- Background Line --}}
                        <div class="absolute top-3 left-0 w-full h-0.5 bg-gray-100">
                             @if(!$isCancelled)
                                <div class="absolute top-0 left-0 h-full bg-primary-500 transition-all duration-700" 
                                     style="width: {{ ($currentIdx / (count($flow) - 1)) * 100 }}%"></div>
                             @endif
                        </div>

                        {{-- Steps --}}
                        @if(!$isCancelled)
                            @foreach($flow as $i => $step)
                                <div class="relative flex flex-col items-center flex-1 z-10">
                                    {{-- Dot --}}
                                    <div class="w-7 h-7 rounded-full flex items-center justify-center transition-all duration-300 border-2
                                        {{ $i < $currentIdx ? 'bg-emerald-500 border-emerald-500 text-white' : ($i <= $currentIdx ? 'bg-primary-500 border-primary-500 text-white shadow-lg shadow-primary-500/30' : 'bg-white border-gray-100 text-gray-300') }}">
                                        @if($i < $currentIdx)
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                        @elseif($i == $currentIdx)
                                            <div class="w-1.5 h-1.5 bg-white rounded-full animate-pulse"></div>
                                        @else
                                            <div class="w-1.5 h-1.5 bg-gray-200 rounded-full"></div>
                                        @endif
                                    </div>
                                    <span class="text-[10px] font-black mt-2.5 uppercase tracking-tighter {{ $i <= $currentIdx ? 'text-dark-900' : 'text-gray-400' }}">
                                        {{ $statusLabels[$step] }}
                                    </span>
                                </div>
                            @endforeach
                        @else
                            {{-- Cancelled State --}}
                            <div class="relative flex flex-col items-center flex-1 z-10">
                                <div class="w-7 h-7 bg-emerald-500 border-2 border-emerald-500 text-white rounded-full flex items-center justify-center">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                </div>
                                <span class="text-[10px] font-black mt-2.5 uppercase text-emerald-600">Booking</span>
                            </div>
                            <div class="relative flex flex-col items-center flex-1 z-10">
                                <div class="w-7 h-7 bg-red-500 border-2 border-red-500 text-white rounded-full flex items-center justify-center shadow-lg shadow-red-500/30">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"/></svg>
                                </div>
                                <span class="text-[10px] font-black mt-2.5 uppercase text-red-600">{{ $booking->status === 'refunded' ? 'Refunded' : 'Batal' }}</span>
                            </div>
                            <div class="flex-1"></div>
                            <div class="flex-1"></div>
                        @endif
                    </div>
                </div>

                {{-- Action Button --}}
                @if($booking->status === 'pending')
                    <div class="mt-8">
                        <a href="{{ route('user.bookings.pay', $booking->booking_code) }}"
                           class="block w-full bg-primary-500 hover:bg-primary-600 text-white text-xs font-black py-4 rounded-xl text-center shadow-lg shadow-primary-500/20 transition-all uppercase tracking-widest">
                            Bayar Sekarang
                        </a>
                    </div>
                @endif
            </div>
        </div>
    @empty
        <div class="bg-white rounded-2xl shadow-sm p-16 text-center border border-gray-100">
            <h3 class="text-xl font-bold text-dark-900">Belum ada booking</h3>
            <p class="text-dark-400 mt-2">Mulai jelajahi destinasi impianmu!</p>
            <a href="{{ route('destinations.index') }}" class="btn-primary mt-6 inline-block">Jelajahi Destinasi</a>
        </div>
    @endforelse

    <div class="mt-8">{{ $bookings->links() }}</div>
@endsection
