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
                            <a href="{{ route('admin.schedules.edit', $schedule) }}" class="w-8 h-8 rounded-lg bg-blue-50 flex items-center justify-center hover:bg-blue-100" title="Edit">
                            </a>
                            <form action="{{ route('admin.schedules.destroy', $schedule) }}" method="POST" onsubmit="return confirm('Yakin hapus jadwal ini?')">
                                @csrf @method('DELETE')
                                <button class="w-8 h-8 rounded-lg bg-red-50 flex items-center justify-center hover:bg-red-100">
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
