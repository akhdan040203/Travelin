@extends('admin.layouts.app')
@section('page_title', 'Kelola Jadwal')

@section('content')
<div class="flex items-center justify-between mb-6">
    <p class="text-dark-400 text-sm">{{ $schedules->total() }} jadwal</p>
    <a href="{{ route('admin.schedules.create') }}" class="btn-primary text-sm flex items-center gap-2">
        Tambah Jadwal
    </a>
</div>

{{-- Filter --}}
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 mb-6">
    <form action="{{ route('admin.schedules.index') }}" method="GET" class="flex flex-wrap gap-3">
        <select name="destination" class="px-4 py-2.5 rounded-xl border border-gray-200 text-sm bg-white focus:border-primary-500">
            <option value="">Semua Destinasi</option>
            @foreach($destinations as $dest)
                <option value="{{ $dest->id }}" {{ request('destination') == $dest->id ? 'selected' : '' }}>{{ $dest->name }}</option>
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
                    <th class="text-left px-5 py-3.5 text-xs font-semibold text-dark-500 uppercase">Destinasi</th>
                    <th class="text-left px-5 py-3.5 text-xs font-semibold text-dark-500 uppercase">Berangkat</th>
                    <th class="text-left px-5 py-3.5 text-xs font-semibold text-dark-500 uppercase">Kembali</th>
                    <th class="text-center px-5 py-3.5 text-xs font-semibold text-dark-500 uppercase">Kuota</th>
                    <th class="text-left px-5 py-3.5 text-xs font-semibold text-dark-500 uppercase">Harga</th>
                    <th class="text-center px-5 py-3.5 text-xs font-semibold text-dark-500 uppercase">Status</th>
                    <th class="text-center px-5 py-3.5 text-xs font-semibold text-dark-500 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($schedules as $schedule)
                <tr class="hover:bg-gray-50/50">
                    <td class="px-5 py-4 text-sm font-medium text-dark-900">{{ $schedule->destination->name ?? '-' }}</td>
                    <td class="px-5 py-4 text-sm text-dark-600">{{ $schedule->departure_date->format('d M Y') }}</td>
                    <td class="px-5 py-4 text-sm text-dark-600">{{ $schedule->return_date->format('d M Y') }}</td>
                    <td class="px-5 py-4 text-center">
                        <span class="text-sm text-dark-900 font-medium">{{ $schedule->booked }}/{{ $schedule->quota }}</span>
                        <div class="w-full bg-gray-100 rounded-full h-1.5 mt-1">
                            <div class="bg-primary-500 h-1.5 rounded-full" style="width: {{ min(100, ($schedule->booked / max(1,$schedule->quota)) * 100) }}%"></div>
                        </div>
                    </td>
                    <td class="px-5 py-4 text-sm font-semibold text-dark-900">{{ $schedule->formatted_price }}</td>
                    <td class="px-5 py-4 text-center">
                        @php $sColors = ['open'=>'bg-emerald-100 text-emerald-700','closed'=>'bg-red-100 text-red-700','full'=>'bg-yellow-100 text-yellow-700']; @endphp
                        <span class="px-2.5 py-1 rounded-full text-[10px] font-semibold {{ $sColors[$schedule->status] ?? 'bg-gray-100' }}">{{ ucfirst($schedule->status) }}</span>
                    </td>
                    <td class="px-5 py-4">
                        <div class="flex items-center justify-center gap-2">
                            <a href="{{ route('admin.schedules.edit', $schedule) }}" class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center hover:bg-blue-100 transition-colors" title="Edit">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L9.38 17.272a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897l10.134-10.133z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 7.125L16.875 4.5"/>
                                </svg>
                            </a>
                            <form action="{{ route('admin.schedules.destroy', $schedule) }}" method="POST" onsubmit="return confirm('Yakin hapus jadwal ini?')">
                                @csrf @method('DELETE')
                                <button class="w-8 h-8 rounded-lg bg-red-50 text-red-500 flex items-center justify-center hover:bg-red-100 transition-colors" title="Hapus">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 7h12"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 7V5a1 1 0 011-1h4a1 1 0 011 1v2"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 11v6M14 11v6"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7l1 13h6l1-13"/>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="px-5 py-12 text-center text-dark-400">Belum ada jadwal</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="p-4 border-t border-gray-100">{{ $schedules->links() }}</div>
</div>
@endsection
