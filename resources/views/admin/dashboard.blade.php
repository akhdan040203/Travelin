@extends('admin.layouts.app')
@section('page_title', 'Dashboard')

@section('content')
{{-- Stats Grid --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
        <div class="flex items-center gap-3 mb-3">
            <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center">
            </div>
            <div>
                <p class="text-2xl font-bold text-dark-900">{{ $stats['total_users'] }}</p>
                <p class="text-dark-400 text-xs">Total User</p>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
        <div class="flex items-center gap-3 mb-3">
            <div class="w-10 h-10 rounded-xl bg-emerald-50 flex items-center justify-center">
            </div>
            <div>
                <p class="text-2xl font-bold text-dark-900">{{ $stats['total_destinations'] }}</p>
                <p class="text-dark-400 text-xs">Destinasi</p>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
        <div class="flex items-center gap-3 mb-3">
            <div class="w-10 h-10 rounded-xl bg-amber-50 flex items-center justify-center">
            </div>
            <div>
                <p class="text-2xl font-bold text-dark-900">{{ $stats['total_bookings'] }}</p>
                <p class="text-dark-400 text-xs">Total Booking</p>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
        <div class="flex items-center gap-3 mb-3">
            <div class="w-10 h-10 rounded-xl bg-primary-50 flex items-center justify-center">
            </div>
            <div>
                <p class="text-2xl font-bold text-dark-900">Rp {{ number_format($stats['total_revenue'], 0, ',', '.') }}</p>
                <p class="text-dark-400 text-xs">Revenue</p>
            </div>
        </div>
    </div>
</div>

{{-- Quick Stats --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
    <div class="bg-yellow-50 border border-yellow-100 rounded-xl p-4 text-center">
        <p class="text-2xl font-bold text-yellow-600">{{ $stats['pending_bookings'] }}</p>
        <p class="text-yellow-600 text-xs">Pending Booking</p>
    </div>
    <div class="bg-purple-50 border border-purple-100 rounded-xl p-4 text-center">
        <p class="text-2xl font-bold text-purple-600">{{ $stats['pending_reviews'] }}</p>
        <p class="text-purple-600 text-xs">Pending Review</p>
    </div>
    <div class="bg-red-50 border border-red-100 rounded-xl p-4 text-center">
        <p class="text-2xl font-bold text-red-600">{{ $stats['unread_contacts'] }}</p>
        <p class="text-red-600 text-xs">Pesan Belum Dibaca</p>
    </div>
    <div class="bg-blue-50 border border-blue-100 rounded-xl p-4 text-center">
        <p class="text-2xl font-bold text-blue-600">{{ $stats['active_schedules'] }}</p>
        <p class="text-blue-600 text-xs">Jadwal Aktif</p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    {{-- Recent Bookings --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="flex items-center justify-between p-5 border-b border-gray-100">
            <h2 class="font-bold text-dark-900">Booking Terbaru</h2>
            <a href="{{ route('admin.bookings.index') }}" class="text-primary-500 text-sm font-semibold">Semua →</a>
        </div>
        @foreach($recentBookings as $booking)
        <div class="flex items-center gap-3 p-4 border-b border-gray-50 last:border-0">
            <div class="flex-1 min-w-0">
                <p class="font-semibold text-dark-900 text-sm truncate">{{ $booking->schedule->destination->name ?? '-' }}</p>
                <p class="text-dark-400 text-xs">{{ $booking->booking_code }} · {{ $booking->user->name ?? '-' }}</p>
            </div>
            @php
                $colors = ['pending'=>'bg-yellow-100 text-yellow-700','paid'=>'bg-emerald-100 text-emerald-700','confirmed'=>'bg-blue-100 text-blue-700','ongoing'=>'bg-indigo-100 text-indigo-700','completed'=>'bg-emerald-100 text-emerald-700','cancelled'=>'bg-red-100 text-red-700','refunded'=>'bg-gray-100 text-gray-700'];
            @endphp
            <span class="px-2 py-1 rounded-full text-[10px] font-semibold {{ $colors[$booking->status] ?? 'bg-gray-100' }}">{{ $booking->status_label }}</span>
        </div>
        @endforeach
        @if($recentBookings->isEmpty())
        <p class="p-6 text-center text-dark-400 text-sm">Belum ada booking</p>
        @endif
    </div>

    {{-- Recent Contacts --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="flex items-center justify-between p-5 border-b border-gray-100">
            <h2 class="font-bold text-dark-900">Pesan Masuk</h2>
        </div>
        @foreach($recentContacts as $contact)
        <div class="flex items-center gap-3 p-4 border-b border-gray-50 last:border-0">
            <div class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center text-sm font-bold text-dark-400">
                {{ strtoupper(substr($contact->name, 0, 1)) }}
            </div>
            <div class="flex-1 min-w-0">
                <p class="font-semibold text-dark-900 text-sm truncate">{{ $contact->subject }}</p>
                <p class="text-dark-400 text-xs">{{ $contact->name }} · {{ $contact->created_at->diffForHumans() }}</p>
            </div>
            @if(!$contact->is_read)
            <span class="w-2 h-2 rounded-full bg-primary-500"></span>
            @endif
        </div>
        @endforeach
        @if($recentContacts->isEmpty())
        <p class="p-6 text-center text-dark-400 text-sm">Belum ada pesan</p>
        @endif
    </div>
</div>
@endsection
