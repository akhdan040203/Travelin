@extends('admin.layouts.app')
@section('page_title', 'Kelola Booking')

@section('content')
{{-- Filters --}}
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 mb-6">
    <form action="{{ route('admin.bookings.index') }}" method="GET" class="flex flex-wrap gap-3">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari kode/nama..."
               class="flex-1 min-w-[200px] px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20">
        <select name="status" class="px-4 py-2.5 rounded-xl border border-gray-200 text-sm bg-white focus:border-primary-500">
            <option value="">Semua Status</option>
            @foreach(['pending'=>'Menunggu Pembayaran','paid'=>'Menunggu Konfirmasi Admin','confirmed'=>'Waiting Keberangkatan','ongoing'=>'Trip Berjalan','completed'=>'Selesai','cancelled'=>'Dibatalkan','refunded'=>'Refund'] as $val => $label)
                <option value="{{ $val }}" {{ request('status') == $val ? 'selected' : '' }}>{{ $label }}</option>
            @endforeach
        </select>
        <button type="submit" class="px-5 py-2.5 bg-dark-900 text-white rounded-xl text-sm font-medium hover:bg-dark-800">Filter</button>
    </form>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50 border-b border-gray-100">
                <tr>
                    <th class="text-left px-5 py-3.5 text-xs font-semibold text-dark-500 uppercase">Kode</th>
                    <th class="text-left px-5 py-3.5 text-xs font-semibold text-dark-500 uppercase">User / Kontak</th>
                    <th class="text-left px-5 py-3.5 text-xs font-semibold text-dark-500 uppercase">Destinasi</th>
                    <th class="text-center px-5 py-3.5 text-xs font-semibold text-dark-500 uppercase">Peserta</th>
                    <th class="text-left px-5 py-3.5 text-xs font-semibold text-dark-500 uppercase">Total</th>
                    <th class="text-center px-5 py-3.5 text-xs font-semibold text-dark-500 uppercase">Status</th>
                    <th class="text-center px-5 py-3.5 text-xs font-semibold text-dark-500 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($bookings as $booking)
                @php
                    $colors = ['pending'=>'bg-yellow-100 text-yellow-700','paid'=>'bg-emerald-100 text-emerald-700','confirmed'=>'bg-blue-100 text-blue-700','ongoing'=>'bg-indigo-100 text-indigo-700','completed'=>'bg-emerald-100 text-emerald-700','cancelled'=>'bg-red-100 text-red-700','refunded'=>'bg-gray-100 text-gray-700'];
                @endphp
                <tr class="hover:bg-gray-50/50">
                    <td class="px-5 py-4">
                        <span class="text-sm font-mono font-semibold text-primary-500">{{ $booking->booking_code }}</span>
                        <p class="text-dark-400 text-xs">{{ $booking->created_at->format('d M Y') }}</p>
                    </td>
                    <td class="px-5 py-4">
                        <p class="text-sm font-medium text-dark-900">{{ $booking->contact_name }}</p>
                        <p class="text-xs text-dark-400">{{ $booking->user->email ?? '-' }}</p>
                    </td>
                    <td class="px-5 py-4">
                        <p class="text-sm text-dark-900 truncate max-w-[200px]">{{ $booking->schedule->destination->name ?? '-' }}</p>
                        <p class="text-xs text-dark-400">{{ $booking->schedule->departure_date->format('d M Y') ?? '-' }}</p>
                    </td>
                    <td class="px-5 py-4 text-center text-sm font-medium text-dark-900">{{ $booking->participants }}</td>
                    <td class="px-5 py-4 text-sm font-bold text-dark-900">{{ $booking->formatted_total_price }}</td>
                    <td class="px-5 py-4">
                        <form action="{{ route('admin.bookings.updateStatus', $booking) }}" method="POST" class="flex items-center justify-center">
                            @csrf
                            @method('PATCH')
                            <select name="status"
                                    onchange="this.disabled = true; this.form.submit();"
                                    class="w-56 rounded-xl border border-gray-200 bg-white px-3 py-2 text-xs font-semibold text-dark-700 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20">
                                @foreach(['pending'=>'Menunggu Pembayaran','paid'=>'Menunggu Konfirmasi Admin','confirmed'=>'Waiting Keberangkatan','ongoing'=>'Trip Berjalan','completed'=>'Selesai','cancelled'=>'Dibatalkan','refunded'=>'Refund'] as $val => $label)
                                    <option value="{{ $val }}" {{ $booking->status === $val ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                        </form>
                    </td>
                    <td class="px-5 py-4 text-center">
                        <a href="{{ route('admin.bookings.show', $booking) }}" class="text-xs font-semibold text-primary-500 hover:text-primary-600" title="Detail">
                            Detail
                        </a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="px-5 py-12 text-center text-dark-400">Belum ada booking</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="p-4 border-t border-gray-100">{{ $bookings->links() }}</div>
</div>
@endsection
