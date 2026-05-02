@extends('layouts.user')

@section('title', 'Booking #' . $booking->booking_code . ' - Travelin')
@section('page_title', 'Detail Booking')

@section('content')
    <div class="max-w-4xl">
        {{-- Header --}}
        <div class="flex items-center justify-between mb-8">
            <div>
                <a href="{{ route('user.bookings') }}" class="text-primary-500 text-sm font-semibold hover:text-primary-600 mb-2 inline-block">← Kembali</a>
                <h1 class="text-2xl font-bold text-dark-900">Detail Booking</h1>
            </div>
            @php
                $statusStyles = [
                    'pending' => 'bg-yellow-100 text-yellow-700 border-yellow-200',
                    'confirmed' => 'bg-blue-100 text-blue-700 border-blue-200',
                    'paid' => 'bg-emerald-100 text-emerald-700 border-emerald-200',
                    'completed' => 'bg-emerald-100 text-emerald-700 border-emerald-200',
                    'cancelled' => 'bg-red-100 text-red-700 border-red-200',
                    'refunded' => 'bg-gray-100 text-gray-700 border-gray-200',
                ];
                $statusLabels = [
                    'pending' => 'Menunggu Pembayaran',
                    'confirmed' => 'Dikonfirmasi',
                    'paid' => 'Sudah Dibayar',
                    'completed' => 'Selesai',
                    'cancelled' => 'Dibatalkan',
                    'refunded' => 'Refund',
                ];
            @endphp
            <span class="px-4 py-2 rounded-full text-sm font-semibold border {{ $statusStyles[$booking->status] ?? 'bg-gray-100' }}">
                {{ $statusLabels[$booking->status] ?? $booking->status }}
            </span>
        </div>

        @if(session('success'))
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl p-4 mb-6 flex items-center gap-3">
                {{ session('success') }}
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Main Info --}}
            <div class="lg:col-span-2 space-y-6">
                {{-- Booking Code --}}
                <div class="bg-white rounded-2xl shadow-sm p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="font-bold text-dark-900">Informasi Booking</h2>
                        <span class="text-dark-400 text-sm">{{ $booking->created_at->format('d M Y, H:i') }}</span>
                    </div>
                    <div class="bg-primary-50 rounded-xl p-4 text-center mb-4">
                        <span class="text-dark-400 text-xs block">Kode Booking</span>
                        <span class="text-2xl font-bold text-primary-500 tracking-wider">{{ $booking->booking_code }}</span>
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
                            @php
                                $paymentLabels = ['bank_transfer' => 'Transfer Bank', 'e_wallet' => 'E-Wallet', 'credit_card' => 'Kartu Kredit'];
                            @endphp
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
                <div class="bg-white rounded-2xl shadow-sm p-6">
                    <h2 class="font-bold text-dark-900 mb-4">Detail Perjalanan</h2>
                    <div class="flex items-start gap-4">
                        <div class="w-20 h-20 bg-gradient-to-br from-primary-400 to-primary-600 rounded-xl flex-shrink-0 overflow-hidden">
                            @if($booking->schedule->destination->featured_image)
                                <img src="{{ asset('storage/' . $booking->schedule->destination->featured_image) }}" class="w-full h-full object-cover">
                            @endif
                        </div>
                        <div>
                            <h3 class="font-bold text-dark-900">{{ $booking->schedule->destination->name }}</h3>
                            <p class="text-dark-400 text-sm mt-1">{{ $booking->schedule->destination->location }}</p>
                            <div class="flex gap-4 mt-2 text-xs text-dark-400">
                                <span>{{ $booking->schedule->departure_date->format('d M Y') }} - {{ $booking->schedule->return_date->format('d M Y') }}</span>
                            </div>
                            @if($booking->schedule->meeting_point)
                                <p class="text-dark-400 text-xs mt-1">Meeting point: {{ $booking->schedule->meeting_point }}</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- Sidebar: Price Summary --}}
            <div>
                <div class="bg-white rounded-2xl shadow-sm p-6 sticky top-28">
                    <h2 class="font-bold text-dark-900 mb-4">Ringkasan Pembayaran</h2>
                    <div class="space-y-3">
                        <div class="flex justify-between text-sm">
                            <span class="text-dark-400">Harga per orang</span>
                            <span class="text-dark-900">Rp {{ number_format($booking->price_per_person, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-dark-400">Jumlah peserta</span>
                            <span class="text-dark-900">× {{ $booking->participants }}</span>
                        </div>
                        <hr class="border-gray-100">
                        <div class="flex justify-between">
                            <span class="font-bold text-dark-900">Total</span>
                            <span class="text-xl font-bold text-primary-500">{{ $booking->formatted_total_price }}</span>
                        </div>
                    </div>

                    @if($booking->status === 'pending')
                        <div class="mt-6 p-4 bg-yellow-50 rounded-xl text-center">
                            <p class="text-yellow-700 text-sm font-medium mb-2">Menunggu pembayaran</p>
                            <p class="text-yellow-600 text-xs">Silakan lakukan pembayaran sesuai metode yang dipilih</p>
                        </div>
                    @elseif($booking->status === 'paid' || $booking->status === 'confirmed')
                        <div class="mt-6 p-4 bg-emerald-50 rounded-xl text-center">
                            <p class="text-emerald-700 text-sm font-medium">Pembayaran diterima</p>
                            <p class="text-emerald-600 text-xs mt-1">Siap untuk berangkat!</p>
                        </div>
                    @endif

                    <a href="{{ route('destinations.show', $booking->schedule->destination->slug) }}"
                       class="btn-outline w-full text-center mt-4 block text-sm">
                        Lihat Destinasi
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
