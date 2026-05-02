@extends('layouts.main')

@section('title', $destination->name . ' - Travelin')
@section('meta_description', $destination->short_description)

@section('content')
@php
    $localImages = [
        'raja-ampat-paradise' => 'images/destinations/raja-ampat.png',
        'bromo-sunrise-experience' => 'images/destinations/bromo.png',
        'bali-island-hopping' => 'images/destinations/bali.png',
        'taman-nasional-komodo' => 'images/destinations/komodo.png',
        'yogyakarta-heritage-tour' => 'images/destinations/yogyakarta.png',
        'dieng-plateau-adventure' => 'images/destinations/dieng.png',
    ];

    $defaultImage = 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?w=1920&q=80&fit=crop';
    $heroImage = $destination->featured_image
        ? asset('storage/' . $destination->featured_image)
        : (isset($localImages[$destination->slug]) ? asset($localImages[$destination->slug]) : $defaultImage);

    $fallbackGalleries = [
        'raja-ampat-paradise' => [
            ['src' => asset('images/destinations/raja-ampat.png'), 'caption' => 'Laguna dan pulau karst Raja Ampat'],
            ['src' => 'https://images.unsplash.com/photo-1516690561799-46d8f74f9abf?w=900&q=80&fit=crop', 'caption' => 'Snorkeling di laut tropis'],
            ['src' => 'https://images.unsplash.com/photo-1500375592092-40eb2168fd21?w=900&q=80&fit=crop', 'caption' => 'Pantai pasir putih dan air jernih'],
        ],
        'bromo-sunrise-experience' => [
            ['src' => asset('images/destinations/bromo.png'), 'caption' => 'Sunrise di kawasan Gunung Bromo'],
            ['src' => 'https://images.unsplash.com/photo-1500530855697-b586d89ba3ee?w=900&q=80&fit=crop', 'caption' => 'Viewpoint pegunungan saat pagi'],
            ['src' => 'https://images.unsplash.com/photo-1464822759023-fed622ff2c3b?w=900&q=80&fit=crop', 'caption' => 'Rute alam dan lanskap vulkanik'],
        ],
        'bali-island-hopping' => [
            ['src' => asset('images/destinations/bali.png'), 'caption' => 'Tebing pantai ikonik Bali'],
            ['src' => 'https://images.unsplash.com/photo-1537996194471-e657df975ab4?w=900&q=80&fit=crop', 'caption' => 'Suasana pulau tropis'],
            ['src' => 'https://images.unsplash.com/photo-1555400038-63f5ba517a47?w=900&q=80&fit=crop', 'caption' => 'Budaya dan lanskap Bali'],
        ],
        'taman-nasional-komodo' => [
            ['src' => asset('images/destinations/komodo.png'), 'caption' => 'Bukit dan pulau di Labuan Bajo'],
            ['src' => 'https://images.unsplash.com/photo-1518548419970-58e3b4079ab2?w=900&q=80&fit=crop', 'caption' => 'Pantai dan kapal trip pulau'],
            ['src' => 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?w=900&q=80&fit=crop', 'caption' => 'Snorkeling dan pantai eksotis'],
        ],
        'yogyakarta-heritage-tour' => [
            ['src' => asset('images/destinations/yogyakarta.png'), 'caption' => 'Warisan budaya Yogyakarta'],
            ['src' => 'https://images.unsplash.com/photo-1596402184320-417e7178b2cd?w=900&q=80&fit=crop', 'caption' => 'Candi dan arsitektur bersejarah'],
            ['src' => 'https://images.unsplash.com/photo-1558005530-a7958896ec60?w=900&q=80&fit=crop', 'caption' => 'Suasana kota dan budaya lokal'],
        ],
        'dieng-plateau-adventure' => [
            ['src' => asset('images/destinations/dieng.png'), 'caption' => 'Dataran tinggi Dieng'],
            ['src' => 'https://images.unsplash.com/photo-1500534314209-a25ddb2bd429?w=900&q=80&fit=crop', 'caption' => 'Golden sunrise di dataran tinggi'],
            ['src' => 'https://images.unsplash.com/photo-1501785888041-af3ef285b470?w=900&q=80&fit=crop', 'caption' => 'Danau dan jalur alam pegunungan'],
        ],
    ];

    $galleryImages = $destination->galleries
        ->take(3)
        ->map(fn($gallery) => [
            'src' => \Illuminate\Support\Str::startsWith($gallery->image, ['http://', 'https://'])
                ? $gallery->image
                : asset('storage/' . $gallery->image),
            'caption' => $gallery->caption ?? $destination->name,
        ])
        ->values();

    if ($galleryImages->count() < 3) {
        $galleryImages = $galleryImages
            ->concat(collect($fallbackGalleries[$destination->slug] ?? [
                ['src' => $heroImage, 'caption' => $destination->name],
                ['src' => 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?w=900&q=80&fit=crop', 'caption' => 'Pemandangan trip pilihan'],
                ['src' => 'https://images.unsplash.com/photo-1500530855697-b586d89ba3ee?w=900&q=80&fit=crop', 'caption' => 'Pengalaman perjalanan'],
            ]))
            ->take(3)
            ->values();
    }

    $tripBenefits = collect($destination->included ?: [
        'Transportasi selama trip',
        'Akomodasi sesuai paket',
        'Guide lokal berpengalaman',
        'Tiket masuk destinasi utama',
        'Dokumentasi perjalanan',
        'Bantuan tim Travelin selama trip',
    ]);

    $isWishlisted = auth()->check()
        ? auth()->user()->hasWishlisted($destination->id)
        : false;
@endphp

{{-- Hero Image --}}
<section class="relative h-[50vh] md:h-[60vh] overflow-hidden">
    <img src="{{ $heroImage }}" alt="{{ $destination->name }}"
         class="w-full h-full object-cover">
    <div class="absolute inset-0 bg-gradient-to-t from-dark-900/80 via-dark-900/30 to-transparent"></div>

    <div class="absolute bottom-0 left-0 right-0 p-8">
        <div class="max-w-7xl mx-auto">
            
            <h1 class="text-3xl md:text-5xl font-bold text-white">{{ $destination->name }}</h1>
            <div class="flex flex-wrap items-center gap-4 mt-3 text-white/80">
                <span class="flex items-center gap-1.5">
                    {{ $destination->location }}
                </span>
            </div>
            <div class="mt-5">
                @auth
                    @if(auth()->user()->role !== 'admin')
                        <form method="POST" action="{{ route('user.wishlist.toggle', $destination) }}" class="js-wishlist-form inline-flex" data-destination-id="{{ $destination->id }}">
                            @csrf
                            <button type="submit" class="js-wishlist-button inline-flex items-center gap-2 rounded-xl px-4 py-2.5 text-xs font-black uppercase tracking-widest transition-all shadow-lg {{ $isWishlisted ? 'bg-red-500 text-white shadow-red-500/25 hover:bg-red-600' : 'bg-white text-dark-900 hover:bg-red-50 hover:text-red-500' }}">
                                <svg class="js-wishlist-icon w-4 h-4" fill="{{ $isWishlisted ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.9" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                </svg>
                                <span class="js-wishlist-label">{{ $isWishlisted ? 'Tersimpan' : 'Tambah Wishlist' }}</span>
                            </button>
                        </form>
                    @endif
                @else
                    <a href="{{ route('login') }}" class="inline-flex items-center gap-2 rounded-xl bg-white px-4 py-2.5 text-xs font-black uppercase tracking-widest text-dark-900 shadow-lg hover:bg-red-50 hover:text-red-500 transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.9" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                        </svg>
                        Login untuk Wishlist
                    </a>
                @endauth
            </div>
        </div>
    </div>
</section>

{{-- Content --}}
<section class="py-12 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
            {{-- Main Content --}}
            <div class="lg:col-span-2 space-y-10">
                {{-- Description --}}
                <div>
                    <h2 class="text-2xl font-bold text-dark-900 mb-4">Tentang Destinasi</h2>
                    <div class="prose prose-lg text-dark-600 max-w-none">
                        {!! nl2br(e($destination->description)) !!}
                    </div>
                </div>

                {{-- Trip Benefits --}}
                <div class="bg-emerald-50 rounded-2xl p-6 md:p-8 border border-emerald-100">
                    <div class="flex items-start justify-between gap-4 mb-6">
                        <div>
                            <h2 class="text-2xl font-bold text-dark-900 mt-1">Apa Saja yang Kamu Dapatkan</h2>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        @foreach($tripBenefits as $benefit)
                            <div class="flex items-start gap-3 rounded-xl bg-white/80 px-4 py-3 text-sm text-dark-700 shadow-sm shadow-emerald-900/5">
                                <span class="mt-0.5 flex h-5 w-5 flex-shrink-0 items-center justify-center rounded-full bg-emerald-500 text-white">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M20 7 9 18l-5-5"/>
                                    </svg>
                                </span>
                                <span>{{ $benefit }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Gallery --}}
                <div>
                    <h2 class="text-2xl font-bold text-dark-900 mb-6">Galeri Foto</h2>
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 sm:gap-4">
                        @foreach($galleryImages as $image)
                            <div class="relative aspect-square rounded-xl sm:rounded-2xl overflow-hidden group shadow-xl shadow-black/5 {{ $loop->last && $galleryImages->count() % 2 === 1 ? 'col-span-2 sm:col-span-1' : '' }}">
                                <img src="{{ $image['src'] }}"
                                     alt="{{ $image['caption'] }}"
                                     class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700"
                                     loading="lazy">
                                <div class="absolute inset-0 bg-gradient-to-t from-black/55 via-black/5 to-transparent opacity-80"></div>
                                <div class="absolute left-4 right-4 bottom-4">
                                    <p class="text-white text-xs font-bold leading-snug">{{ $image['caption'] }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Itinerary --}}
                @if($destination->itinerary)
                <div>
                    <h2 class="text-2xl font-bold text-dark-900 mb-6">Itinerary</h2>
                    <div class="space-y-4">
                        @foreach($destination->itinerary as $item)
                            <div class="flex gap-4 group">
                                <div class="flex flex-col items-center">
                                    <div class="w-12 h-12 rounded-xl bg-primary-500 text-white flex items-center justify-center font-bold shadow-lg shadow-primary-500/30 group-hover:scale-110 transition-transform">
                                        {{ $item['day'] }}
                                    </div>
                                    @if(!$loop->last)
                                        <div class="w-0.5 flex-1 bg-primary-100 mt-2"></div>
                                    @endif
                                </div>
                                <div class="pb-8">
                                    <h3 class="font-bold text-dark-900 text-lg">{{ $item['title'] }}</h3>
                                    <p class="text-dark-400 mt-1">{{ $item['description'] }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>

            {{-- Sidebar --}}
            <div class="lg:col-span-1">
                <div class="sticky top-28 space-y-6">
                    {{-- Price Card --}}
                    <div class="card-travel p-6">
                        <div class="text-center mb-6">
                            <span class="text-dark-400 text-[10px] font-black uppercase tracking-widest">All-Inclusive Price</span>
                            <p class="text-3xl font-black text-primary-500 mt-2">{{ $destination->formatted_price }}</p>
                            <div class="flex items-center justify-center gap-2 mt-2">
                                <span class="text-dark-400 text-xs font-medium italic">Includes: Flight, Hotel, & More</span>
                            </div>
                        </div>

                        {{-- Schedule List --}}
                        <h3 class="font-bold text-dark-900 mb-3">Jadwal Tersedia</h3>
                        <div class="space-y-3 max-h-80 overflow-y-auto">
                            @forelse($destination->availableSchedules as $schedule)
                                <div class="border border-gray-200 rounded-xl p-4 hover:border-primary-500 transition-colors cursor-pointer group">
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="font-semibold text-dark-900 text-sm">{{ $schedule->departure_date->format('d M Y') }}</span>
                                        <span class="text-xs px-2 py-1 rounded-full {{ $schedule->available_slots <= 5 ? 'bg-red-100 text-red-600' : 'bg-emerald-100 text-emerald-600' }}">
                                            Sisa {{ $schedule->available_slots }}
                                        </span>
                                    </div>
                                    <p class="text-dark-400 text-xs">Kembali: {{ $schedule->return_date->format('d M Y') }}</p>
                                    <div class="flex items-center justify-between mt-3">
                                        <span class="font-bold text-primary-500">{{ $schedule->formatted_price }}</span>
                                        @auth
                                            <a href="{{ route('booking.create', $schedule->id) }}"
                                               class="text-xs btn-primary !px-3 !py-1.5">Pilih</a>
                                        @else
                                            <a href="{{ route('login') }}" class="text-xs btn-primary !px-3 !py-1.5">Login untuk Booking</a>
                                        @endauth
                                    </div>
                                </div>
                            @empty
                                <p class="text-dark-400 text-sm text-center py-4">Belum ada jadwal tersedia.</p>
                            @endforelse
                        </div>
                    </div>

                    {{-- Quick Info Card --}}
                    <div class="card-travel p-6">
                        <h3 class="font-bold text-dark-900 mb-4">Info Cepat</h3>
                        <ul class="space-y-3">
                            <li class="flex items-center justify-between text-sm">
                                <span class="text-dark-400">Durasi</span>
                                <span class="font-semibold text-dark-900">{{ $destination->duration_days }} Hari</span>
                            </li>
                            <li class="flex items-center justify-between text-sm">
                                <span class="text-dark-400">Lokasi</span>
                                <span class="font-semibold text-dark-900">{{ $destination->province ?? $destination->location }}</span>
                            </li>
                            <li class="flex items-center justify-between text-sm">
                                <span class="text-dark-400">Kategori</span>
                                <span class="font-semibold text-dark-900">{{ $destination->category->name }}</span>
                            </li>

                        </ul>
                    </div>

                    {{-- Need Help --}}
                    <div class="bg-dark-900 text-white rounded-2xl p-6 text-center">
                        <h3 class="font-bold text-lg mb-2">Butuh Bantuan?</h3>
                        <p class="text-dark-300 text-sm mb-4">Hubungi kami untuk info lebih lanjut</p>
                        <a href="https://wa.me/6281234567890" class="btn-primary w-full justify-center flex items-center gap-2" target="_blank">
                            Chat WhatsApp
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- Related Destinations --}}
        @if($relatedDestinations->count() > 0)
        <div class="mt-16 pt-16 border-t border-gray-100">
            <h2 class="text-2xl font-bold text-dark-900 mb-8">Destinasi Serupa</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($relatedDestinations as $related)
                    @php
                        $relatedImage = $related->featured_image
                            ? asset('storage/' . $related->featured_image)
                            : (isset($localImages[$related->slug]) ? asset($localImages[$related->slug]) : $defaultImage);
                    @endphp
                    <a href="{{ route('destinations.show', $related->slug) }}" class="card-travel group block">
                        <div class="relative h-52 overflow-hidden">
                            <img src="{{ $relatedImage }}" alt="{{ $related->name }}"
                                 class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                        </div>
                        <div class="p-5">
                            <h3 class="font-bold text-dark-900 group-hover:text-primary-500 transition-colors">{{ $related->name }}</h3>
                            <p class="text-primary-500 font-bold mt-2">{{ $related->formatted_price }}</p>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</section>
@endsection
