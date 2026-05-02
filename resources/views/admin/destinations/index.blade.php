@extends('admin.layouts.app')
@section('page_title', 'Kelola Destinasi')

@section('content')
<div class="flex items-center justify-between mb-6">
    <div>
        <p class="text-dark-400 text-sm">{{ $destinations->total() }} destinasi ditemukan</p>
    </div>
    <a href="{{ route('admin.destinations.create') }}" class="btn-primary text-sm flex items-center gap-2">
        Tambah Destinasi
    </a>
</div>

{{-- Filters --}}
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 mb-6">
    <form action="{{ route('admin.destinations.index') }}" method="GET" class="flex flex-wrap gap-3">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari destinasi..."
               class="flex-1 min-w-[200px] px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20">
        <select name="category" class="px-4 py-2.5 rounded-xl border border-gray-200 text-sm bg-white focus:border-primary-500">
            <option value="">Semua Kategori</option>
            @foreach($categories as $cat)
                <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
            @endforeach
        </select>
        <button type="submit" class="px-5 py-2.5 bg-dark-900 text-white rounded-xl text-sm font-medium hover:bg-dark-800 transition-colors">Filter</button>
    </form>
</div>

{{-- Table --}}
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50 border-b border-gray-100">
                <tr>
                    <th class="text-left px-5 py-3.5 text-xs font-semibold text-dark-500 uppercase">Destinasi</th>
                    <th class="text-left px-5 py-3.5 text-xs font-semibold text-dark-500 uppercase">Kategori</th>
                    <th class="text-left px-5 py-3.5 text-xs font-semibold text-dark-500 uppercase">Harga</th>
                    <th class="text-left px-5 py-3.5 text-xs font-semibold text-dark-500 uppercase">Durasi</th>
                    <th class="text-center px-5 py-3.5 text-xs font-semibold text-dark-500 uppercase">Status</th>
                    <th class="text-center px-5 py-3.5 text-xs font-semibold text-dark-500 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($destinations as $dest)
                <tr class="hover:bg-gray-50/50 transition-colors">
                    <td class="px-5 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 rounded-xl overflow-hidden flex-shrink-0 bg-gray-100">
                                @if($dest->featured_image)
                                    <img src="{{ asset('storage/' . $dest->featured_image) }}" class="w-full h-full object-cover" alt="">
                                @else
                                    <div class="w-full h-full bg-gradient-to-br from-primary-400 to-primary-600 flex items-center justify-center">
                                    </div>
                                @endif
                            </div>
                            <div class="min-w-0">
                                <p class="font-semibold text-dark-900 text-sm truncate">{{ $dest->name }}</p>
                                <p class="text-dark-400 text-xs truncate">{{ $dest->location }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-5 py-4">
                        <span class="text-sm text-dark-600">{{ $dest->category->name ?? '-' }}</span>
                    </td>
                    <td class="px-5 py-4">
                        <span class="text-sm font-semibold text-dark-900">{{ $dest->formatted_price }}</span>
                    </td>
                    <td class="px-5 py-4">
                        <span class="text-sm text-dark-600">{{ $dest->duration_days }} hari</span>
                    </td>
                    <td class="px-5 py-4 text-center">
                        @if($dest->is_active)
                            <span class="inline-flex px-2.5 py-1 rounded-full text-[10px] font-semibold bg-emerald-100 text-emerald-700">Aktif</span>
                        @else
                            <span class="inline-flex px-2.5 py-1 rounded-full text-[10px] font-semibold bg-red-100 text-red-700">Nonaktif</span>
                        @endif
                        @if($dest->is_featured)
                            <span class="inline-flex px-2.5 py-1 rounded-full text-[10px] font-semibold bg-amber-100 text-amber-700">Featured</span>
                        @endif
                    </td>
                    <td class="px-5 py-4">
                        <div class="flex items-center justify-center gap-2">
                            <a href="{{ route('admin.destinations.edit', $dest) }}" class="w-8 h-8 rounded-lg bg-blue-50 flex items-center justify-center hover:bg-blue-100 transition-colors" title="Edit">
                            </a>
                            <form action="{{ route('admin.destinations.destroy', $dest) }}" method="POST" onsubmit="return confirm('Yakin hapus destinasi ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="w-8 h-8 rounded-lg bg-red-50 flex items-center justify-center hover:bg-red-100 transition-colors" title="Hapus">
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="px-5 py-12 text-center text-dark-400">Belum ada destinasi</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="p-4 border-t border-gray-100">{{ $destinations->links() }}</div>
</div>
@endsection
