@extends('layouts.user')

@section('title', 'Wishlist - Travelin')
@section('page_title', 'Wishlist Saya')

@section('content')
    <div class="mb-6">
        <p class="text-dark-400 text-sm">Destinasi yang ingin kamu kunjungi</p>
    </div>

    @if($wishlists->count() > 0)
        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-6">
            @foreach($wishlists as $wishlist)
                <div class="bg-white rounded-2xl shadow-sm overflow-hidden border border-gray-100/80 group hover:shadow-md transition-shadow">
                    <div class="relative h-52 overflow-hidden">
                        <a href="{{ route('destinations.show', $wishlist->destination->slug) }}" class="block h-full">
                            @if($wishlist->destination->featured_image)
                                <img src="{{ asset('storage/' . $wishlist->destination->featured_image) }}" alt="{{ $wishlist->destination->name }}"
                                     class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                            @else
                                <div class="w-full h-full bg-gradient-to-br from-primary-400 to-primary-600"></div>
                            @endif
                        </a>
                        <div class="absolute top-4 right-4">
                            <form method="POST" action="{{ route('user.wishlist.destroy', $wishlist->destination) }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit" aria-label="Hapus dari wishlist" class="w-9 h-9 bg-white rounded-full flex items-center justify-center shadow-lg hover:bg-red-50 transition-colors">
                                    <svg class="w-4 h-4 text-red-500" fill="currentColor" viewBox="0 0 24 24"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
                                </button>
                            </form>
                        </div>
                        <div class="absolute top-4 left-4 bg-primary-500 text-white rounded-lg px-2.5 py-1 text-xs font-semibold">
                            {{ $wishlist->destination->category->name }}
                        </div>
                    </div>
                    <a href="{{ route('destinations.show', $wishlist->destination->slug) }}" class="block">
                        <div class="p-5">
                            <div class="flex items-center gap-1.5 text-dark-400 text-sm mb-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                {{ $wishlist->destination->location }}
                            </div>
                            <h3 class="font-bold text-dark-900 group-hover:text-primary-500 transition-colors">{{ $wishlist->destination->name }}</h3>
                            <div class="flex items-center justify-between mt-3 pt-3 border-t border-gray-100">
                                <p class="text-primary-500 font-bold">{{ $wishlist->destination->formatted_price }}</p>
                                <span class="text-dark-400 text-xs">{{ $wishlist->destination->duration_days }} Hari</span>
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>

        <div class="mt-8">{{ $wishlists->links() }}</div>
    @else
        <div class="bg-white rounded-2xl shadow-sm p-16 text-center border border-gray-100/80">
            <svg class="w-16 h-16 text-dark-200 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
            <h3 class="text-xl font-bold text-dark-900">Wishlist masih kosong</h3>
            <p class="text-dark-400 mt-2">Simpan destinasi favoritmu untuk nanti!</p>
            <a href="{{ route('destinations.index') }}" class="btn-primary mt-6 inline-block">Jelajahi Destinasi</a>
        </div>
    @endif
@endsection
