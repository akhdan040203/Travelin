@extends('layouts.user')

@section('title', 'History Pembelian - Travelin')
@section('page_title', 'History Pembelian')

@section('content')
    <div class="mb-6">
        <p class="text-dark-400 text-sm">Semua booking perjalanan kamu</p>
    </div>

    @forelse($bookings as $booking)
        <a href="{{ route('user.bookings.show', $booking->booking_code) }}"
           class="block bg-white rounded-2xl shadow-sm mb-4 overflow-hidden hover:shadow-md transition-shadow border border-gray-100/80">
            <div class="flex flex-col md:flex-row">
                {{-- Left: Destination Info --}}
                <div class="p-5 flex-1">
                    <div class="flex items-start gap-4">
                        <div class="w-14 h-14 bg-primary-50 rounded-xl flex flex-col items-center justify-center flex-shrink-0">
                            <span class="text-lg font-bold text-primary-500">{{ $booking->schedule->departure_date->format('d') }}</span>
                            <span class="text-[10px] font-medium text-primary-500 uppercase">{{ $booking->schedule->departure_date->format('M') }}</span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h3 class="font-bold text-dark-900 truncate">{{ $booking->schedule->destination->name }}</h3>
                            <p class="text-dark-400 text-sm mt-1">{{ $booking->booking_code }}</p>
                            <div class="flex flex-wrap gap-x-4 gap-y-1 mt-2 text-xs text-dark-400">
                                <span>{{ $booking->schedule->departure_date->format('d M') }} - {{ $booking->schedule->return_date->format('d M Y') }}</span>
                                <span>{{ $booking->participants }} orang</span>
                                <span>{{ $booking->schedule->destination->location }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Right: Status & Price --}}
                <div class="p-5 flex items-center gap-6 md:border-l border-t md:border-t-0 border-gray-100 bg-gray-50/50">
                    <div class="text-right">
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
                                'pending' => 'Menunggu Bayar',
                                'confirmed' => 'Dikonfirmasi',
                                'paid' => 'Sudah Dibayar',
                                'completed' => 'Selesai',
                                'cancelled' => 'Dibatalkan',
                                'refunded' => 'Refund',
                            ];
                        @endphp
                        <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold {{ $statusStyles[$booking->status] ?? 'bg-gray-100 text-gray-700' }}">
                            {{ $statusLabels[$booking->status] ?? $booking->status }}
                        </span>
                        <p class="text-primary-500 font-bold text-lg mt-2">{{ $booking->formatted_total_price }}</p>
                        <p class="text-dark-400 text-xs">{{ $booking->created_at->diffForHumans() }}</p>
                    </div>
                    <svg class="w-5 h-5 text-dark-300 hidden md:block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </div>
            </div>
        </a>
    @empty
        <div class="bg-white rounded-2xl shadow-sm p-16 text-center border border-gray-100/80">
            <svg class="w-16 h-16 text-dark-200 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
            <h3 class="text-xl font-bold text-dark-900">Belum ada booking</h3>
            <p class="text-dark-400 mt-2">Mulai jelajahi destinasi impianmu!</p>
            <a href="{{ route('destinations.index') }}" class="btn-primary mt-6 inline-block">Jelajahi Destinasi</a>
        </div>
    @endforelse

    <div class="mt-8">{{ $bookings->links() }}</div>
@endsection
