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
            @foreach(['pending' => 'Booking (Pending)', 'paid' => 'Menunggu Konfirmasi', 'confirmed' => 'Waiting (Siap)', 'ongoing' => 'Perjalanan', 'completed' => 'Selesai', 'cancelled' => 'Dibatalkan', 'refunded' => 'Refund'] as $val => $label)
                <option value="{{ $val }}" {{ request('status') == $val ? 'selected' : '' }}>{{ $label }}</option>
            @endforeach
        </select>
        <button type="submit" class="px-5 py-2.5 bg-dark-900 text-white rounded-xl text-sm font-medium hover:bg-dark-800 transition-colors">Filter</button>
    </form>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50 border-b border-gray-100">
                <tr>
                    <th class="text-left px-5 py-3.5 text-xs font-semibold text-dark-500 uppercase tracking-wider">Kode</th>
                    <th class="text-left px-5 py-3.5 text-xs font-semibold text-dark-500 uppercase tracking-wider">User / Kontak</th>
                    <th class="text-left px-5 py-3.5 text-xs font-semibold text-dark-500 uppercase tracking-wider">Destinasi</th>
                    <th class="text-center px-5 py-3.5 text-xs font-semibold text-dark-500 uppercase tracking-wider">Peserta/Status</th>
                    <th class="text-left px-5 py-3.5 text-xs font-semibold text-dark-500 uppercase tracking-wider">Total</th>
                    <th class="text-center px-5 py-3.5 text-xs font-semibold text-dark-500 uppercase tracking-wider">Update Status</th>
                    <th class="text-center px-5 py-3.5 text-xs font-semibold text-dark-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($bookings as $booking)
                @php
                    $colors = ['pending'=>'bg-yellow-10 border-yellow-100 text-yellow-700','paid'=>'bg-blue-10 border-blue-100 text-blue-700','confirmed'=>'bg-emerald-10 border-emerald-100 text-emerald-700','ongoing'=>'bg-indigo-10 border-indigo-100 text-indigo-700','completed'=>'bg-emerald-10 border-emerald-100 text-emerald-700','cancelled'=>'bg-red-10 border-red-100 text-red-700','refunded'=>'bg-gray-10 border-gray-100 text-gray-700'];
                @endphp
                <tr class="hover:bg-gray-50/50 transition-colors">
                    <td class="px-5 py-4">
                        <span class="text-sm font-mono font-bold text-primary-500 tracking-tighter">{{ $booking->booking_code }}</span>
                        <p class="text-dark-400 text-[10px] uppercase font-bold mt-1">{{ $booking->created_at->format('d M Y') }}</p>
                    </td>
                    <td class="px-5 py-4">
                        <p class="text-sm font-bold text-dark-900 leading-tight">{{ $booking->contact_name }}</p>
                        <p class="text-[11px] text-dark-400">{{ $booking->user->email ?? '-' }}</p>
                    </td>
                    <td class="px-5 py-4">
                        <p class="text-sm font-bold text-dark-900 truncate max-w-[180px]">{{ $booking->schedule->destination->name ?? '-' }}</p>
                        <p class="text-[11px] text-dark-400">{{ $booking->schedule->departure_date->format('d M Y') ?? '-' }}</p>
                    </td>
                    <td class="px-5 py-4 text-center">
                        <div class="inline-flex flex-col items-center">
                            <span class="text-sm font-bold text-dark-900 mb-1">{{ $booking->participants }} Peserta</span>
                            <span class="text-[9px] font-black uppercase px-2 py-0.5 rounded-full {{ $colors[$booking->status] ?? 'bg-gray-100' }}">
                                {{ $booking->status === 'confirmed' ? 'Waiting' : ($booking->status === 'ongoing' ? 'Perjalanan' : $booking->status_label) }}
                            </span>
                        </div>
                    </td>
                    <td class="px-5 py-4 text-sm font-black text-dark-900 tracking-tight">{{ $booking->formatted_total_price }}</td>
                    <td class="px-5 py-4">
                        <form action="{{ route('admin.bookings.updateStatus', $booking) }}" method="POST" class="flex items-center justify-center">
                            @csrf
                            @method('PATCH')
                            <select name="status"
                                    onchange="this.classList.add('opacity-50','pointer-events-none'); this.form.submit();"
                                    class="w-48 rounded-xl border border-gray-200 bg-white px-3 py-2 text-[11px] font-bold text-dark-700 focus:border-primary-500 focus:ring-4 focus:ring-primary-500/10 cursor-pointer transition-all">
                                @foreach(['pending' => 'Booking (Pending)', 'paid' => 'Menunggu Konfirmasi', 'confirmed' => 'Waiting (Siap)', 'ongoing' => 'Perjalanan', 'completed' => 'Selesai', 'cancelled' => 'Dibatalkan', 'refunded' => 'Refund'] as $val => $label)
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
