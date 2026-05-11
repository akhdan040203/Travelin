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
                @php
                    $fallbackImages = [
                        'raja-ampat-paradise' => 'images/destinations/raja-ampat.png',
                        'bromo-sunrise-experience' => 'images/destinations/bromo.png',
                        'bali-island-hopping' => 'images/destinations/bali.png',
                        'taman-nasional-komodo' => 'images/destinations/komodo.png',
                        'yogyakarta-heritage-tour' => 'images/destinations/yogyakarta.png',
                        'dieng-plateau-adventure' => 'images/destinations/dieng.png',
                    ];

                    $thumbnail = $dest->featured_image
                        ? asset('storage/' . $dest->featured_image)
                        : (isset($fallbackImages[$dest->slug]) ? asset($fallbackImages[$dest->slug]) : null);
                @endphp
                <tr class="hover:bg-gray-50/50 transition-colors">
                    <td class="px-5 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 rounded-xl overflow-hidden flex-shrink-0 bg-gray-100">
                                @if($thumbnail)
                                    <img src="{{ $thumbnail }}" class="w-full h-full object-cover" alt="{{ $dest->name }}">
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
                            <a href="{{ route('admin.destinations.edit', $dest) }}" class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center hover:bg-blue-100 transition-colors" title="Edit">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L9.38 17.272a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897l10.134-10.133z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 7.125L16.875 4.5"/>
                                </svg>
                            </a>
                            <form action="{{ route('admin.destinations.destroy', $dest) }}" method="POST" onsubmit="return confirm('Yakin hapus destinasi ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="w-8 h-8 rounded-lg bg-red-50 text-red-500 flex items-center justify-center hover:bg-red-100 transition-colors" title="Hapus">
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
                <tr><td colspan="6" class="px-5 py-12 text-center text-dark-400">Belum ada destinasi</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="p-4 border-t border-gray-100">{{ $destinations->links() }}</div>
</div>
@endsection
