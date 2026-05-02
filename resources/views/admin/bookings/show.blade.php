@extends('admin.layouts.app')
@section('page_title', 'Detail Booking #' . $booking->booking_code)

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.bookings.index') }}" class="text-primary-500 text-sm font-semibold hover:text-primary-600">← Kembali</a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    {{-- Main Info --}}
    <div class="lg:col-span-2 space-y-6">
        {{-- Booking Info --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="font-bold text-dark-900">Informasi Booking</h2>
                @php
                    $colors = ['pending'=>'bg-yellow-100 text-yellow-700 border-yellow-200','confirmed'=>'bg-blue-100 text-blue-700 border-blue-200','paid'=>'bg-emerald-100 text-emerald-700 border-emerald-200','completed'=>'bg-emerald-100 text-emerald-700 border-emerald-200','cancelled'=>'bg-red-100 text-red-700 border-red-200','refunded'=>'bg-gray-100 text-gray-700 border-gray-200'];
                @endphp
                <span class="px-3 py-1.5 rounded-full text-xs font-semibold border {{ $colors[$booking->status] ?? 'bg-gray-100' }}">{{ ucfirst($booking->status) }}</span>
            </div>
            <div class="bg-primary-50 rounded-xl p-4 text-center mb-4">
                <span class="text-dark-400 text-xs block">Kode Booking</span>
                <span class="text-xl font-bold text-primary-500 tracking-wider font-mono">{{ $booking->booking_code }}</span>
            </div>
            <div class="grid grid-cols-2 gap-4 text-sm">
                <div><span class="text-dark-400 text-xs block mb-1">Nama Kontak</span><span class="font-semibold text-dark-900">{{ $booking->contact_name }}</span></div>
                <div><span class="text-dark-400 text-xs block mb-1">Email</span><span class="font-semibold text-dark-900">{{ $booking->contact_email }}</span></div>
                <div><span class="text-dark-400 text-xs block mb-1">Telepon</span><span class="font-semibold text-dark-900">{{ $booking->contact_phone }}</span></div>
                <div><span class="text-dark-400 text-xs block mb-1">Pembayaran</span><span class="font-semibold text-dark-900">{{ ucfirst(str_replace('_',' ',$booking->payment_method)) }}</span></div>
                <div><span class="text-dark-400 text-xs block mb-1">User</span><span class="font-semibold text-dark-900">{{ $booking->user->name ?? '-' }} ({{ $booking->user->email ?? '-' }})</span></div>
                <div><span class="text-dark-400 text-xs block mb-1">Tanggal Booking</span><span class="font-semibold text-dark-900">{{ $booking->created_at->format('d M Y, H:i') }}</span></div>
            </div>
            @if($booking->special_requests)
            <div class="mt-4 pt-4 border-t border-gray-100">
                <span class="text-dark-400 text-xs block mb-1">Permintaan Khusus</span>
                <p class="text-dark-600 text-sm">{{ $booking->special_requests }}</p>
            </div>
            @endif
        </div>

        {{-- Trip Detail --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h2 class="font-bold text-dark-900 mb-4">Detail Perjalanan</h2>
            <div class="flex items-start gap-4">
                <div class="w-16 h-16 rounded-xl overflow-hidden bg-gray-100 flex-shrink-0">
                    @if($booking->schedule->destination->featured_image)
                        <img src="{{ asset('storage/' . $booking->schedule->destination->featured_image) }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full bg-gradient-to-br from-primary-400 to-primary-600"></div>
                    @endif
                </div>
                <div>
                    <h3 class="font-bold text-dark-900">{{ $booking->schedule->destination->name }}</h3>
                    <p class="text-dark-400 text-sm">{{ $booking->schedule->destination->location }}</p>
                    <p class="text-dark-400 text-xs mt-1">{{ $booking->schedule->departure_date->format('d M Y') }} - {{ $booking->schedule->return_date->format('d M Y') }}</p>
                    @if($booking->schedule->meeting_point)
                    <p class="text-dark-400 text-xs mt-0.5">{{ $booking->schedule->meeting_point }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Sidebar --}}
    <div class="space-y-6">
        {{-- Price Summary --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h2 class="font-bold text-dark-900 mb-4">Ringkasan</h2>
            <div class="space-y-3 text-sm">
                <div class="flex justify-between"><span class="text-dark-400">Harga/orang</span><span>Rp {{ number_format($booking->price_per_person, 0, ',', '.') }}</span></div>
                <div class="flex justify-between"><span class="text-dark-400">Peserta</span><span>× {{ $booking->participants }}</span></div>
                <hr class="border-gray-100">
                <div class="flex justify-between"><span class="font-bold text-dark-900">Total</span><span class="text-lg font-bold text-primary-500">{{ $booking->formatted_total_price }}</span></div>
            </div>
        </div>

        {{-- Update Status --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h2 class="font-bold text-dark-900 mb-4">Ubah Status</h2>
            <form action="{{ route('admin.bookings.updateStatus', $booking) }}" method="POST" class="space-y-3">
                @csrf @method('PATCH')
                <select name="status" class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm bg-white focus:border-primary-500">
                    @foreach(['pending'=>'Pending','confirmed'=>'Dikonfirmasi','paid'=>'Sudah Dibayar','completed'=>'Selesai','cancelled'=>'Dibatalkan','refunded'=>'Refund'] as $val => $label)
                        <option value="{{ $val }}" {{ $booking->status === $val ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                <button type="submit" class="btn-primary w-full text-sm flex items-center justify-center gap-2">
                    Update Status
                </button>
            </form>
        </div>

        {{-- Timeline --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h2 class="font-bold text-dark-900 mb-4">Timeline</h2>
            <div class="space-y-3 text-xs">
                <div class="flex items-center gap-3">
                    <div class="w-2 h-2 rounded-full bg-primary-500"></div>
                    <div><p class="font-medium text-dark-900">Dibuat</p><p class="text-dark-400">{{ $booking->created_at->format('d M Y, H:i') }}</p></div>
                </div>
                @if($booking->paid_at)
                <div class="flex items-center gap-3">
                    <div class="w-2 h-2 rounded-full bg-emerald-500"></div>
                    <div><p class="font-medium text-dark-900">Dibayar</p><p class="text-dark-400">{{ $booking->paid_at->format('d M Y, H:i') }}</p></div>
                </div>
                @endif
                @if($booking->confirmed_at)
                <div class="flex items-center gap-3">
                    <div class="w-2 h-2 rounded-full bg-blue-500"></div>
                    <div><p class="font-medium text-dark-900">Dikonfirmasi</p><p class="text-dark-400">{{ $booking->confirmed_at->format('d M Y, H:i') }}</p></div>
                </div>
                @endif
                @if($booking->cancelled_at)
                <div class="flex items-center gap-3">
                    <div class="w-2 h-2 rounded-full bg-red-500"></div>
                    <div><p class="font-medium text-dark-900">Dibatalkan</p><p class="text-dark-400">{{ $booking->cancelled_at->format('d M Y, H:i') }}</p></div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
