@extends('layouts.main')

@section('title', $destination->name . ' - Travelin')
@section('meta_description', $destination->short_description ?? str($destination->description)->limit(150))

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

    $fallbackImage = $localImages[$destination->slug] ?? 'images/destinations/bali.png';
    $heroImage = $destination->featured_image
        ? asset('storage/' . $destination->featured_image)
        : asset($fallbackImage);

    $galleryMap = [
        'raja-ampat-paradise' => [
            'images/destinations/raja-ampat.png',
            'https://images.unsplash.com/photo-1516690561799-46d8f74f9abf?w=900&q=85&fit=crop',
            'https://images.unsplash.com/photo-1544644181-1484b3fdfc62?w=900&q=85&fit=crop',
        ],
        'bali-island-hopping' => [
            'images/destinations/bali.png',
            'https://images.unsplash.com/photo-1537996194471-e657df975ab4?w=900&q=85&fit=crop',
            'https://source.unsplash.com/900x700/?bali,beach,nusa-penida',
        ],
        'bromo-sunrise-experience' => [
            'images/destinations/bromo.png',
            'https://images.unsplash.com/photo-1589553416260-f586c8f1514f?w=900&q=85&fit=crop',
            'https://images.unsplash.com/photo-1500530855697-b586d89ba3ee?w=900&q=85&fit=crop',
        ],
        'taman-nasional-komodo' => [
            'images/destinations/komodo.png',
            'https://images.unsplash.com/photo-1552733407-5d5c46c3bb3b?w=900&q=85&fit=crop',
            'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?w=900&q=85&fit=crop',
        ],
    ];

    $gallery = collect($galleryMap[$destination->slug] ?? [$fallbackImage, 'images/destinations/raja-ampat.png', 'images/destinations/bali.png'])
        ->map(fn ($image) => str_starts_with($image, 'http') ? $image : asset($image));

    $benefits = [
        'Akomodasi pilihan selama trip',
        'Transportasi lokal sesuai itinerary',
        'Tiket masuk destinasi utama',
        'Guide lokal berpengalaman',
        'Dokumentasi perjalanan',
        'Bantuan reservasi dan briefing trip',
    ];

    $itinerary = [
        ['day' => 'Day 1', 'title' => 'Arrival & Check-in', 'desc' => 'Penjemputan, check-in, briefing perjalanan, lalu menikmati spot santai di sekitar area destinasi.'],
        ['day' => 'Day 2', 'title' => 'Explore Highlight', 'desc' => 'Mengunjungi spot utama, photo stop terbaik, aktivitas pilihan, dan sunset point.'],
        ['day' => 'Day 3', 'title' => 'Local Experience & Return', 'desc' => 'Waktu bebas, belanja lokal, persiapan pulang, dan transfer menuju meeting/drop point.'],
    ];

    $openSchedule = $destination->schedules->firstWhere('status', 'open') ?? $destination->schedules->first();
    $bookingUrl = $openSchedule ? route('booking.create', $openSchedule->id) : route('schedules', ['destination' => $destination->id]);
@endphp

<section class="relative min-h-screen bg-white pb-28 md:hidden">
    <div class="relative h-[52vh] min-h-[430px] overflow-hidden bg-dark-900">
        <img src="{{ $heroImage }}" alt="{{ $destination->name }}" class="h-full w-full object-cover">
        <div class="absolute inset-0 bg-gradient-to-t from-black/55 via-black/10 to-black/20"></div>

        <div class="absolute left-4 right-4 top-24 z-20 flex items-center justify-between md:left-8 md:right-8">
            <a href="{{ route('destinations.index') }}" class="inline-flex h-11 w-11 items-center justify-center rounded-full bg-white/90 text-dark-900 shadow-xl backdrop-blur hover:bg-white">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.4" d="M15 19l-7-7 7-7"/></svg>
            </a>

            @auth
                @if(auth()->user()->role !== 'admin')
                    @php $isWishlisted = auth()->user()->hasWishlisted($destination->id); @endphp
                    <form method="POST" action="{{ route('user.wishlist.toggle', $destination) }}" class="js-wishlist-form" data-destination-id="{{ $destination->id }}">
                        @csrf
                        <button type="submit" aria-label="Toggle wishlist" class="js-wishlist-button inline-flex h-11 w-11 items-center justify-center rounded-full bg-white/90 text-red-500 shadow-xl backdrop-blur hover:bg-red-50">
                            <svg class="js-wishlist-icon h-5 w-5" fill="{{ $isWishlisted ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.9" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                        </button>
                    </form>
                @endif
            @else
                <a href="{{ route('login') }}" class="inline-flex h-11 w-11 items-center justify-center rounded-full bg-white/90 text-red-500 shadow-xl backdrop-blur hover:bg-red-50">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.9" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                </a>
            @endauth
        </div>
    </div>

    <div class="relative z-10 mx-auto -mt-16 max-w-5xl px-4 sm:px-6 lg:px-8">
        <div class="rounded-t-[32px] bg-white px-4 py-6 shadow-2xl shadow-black/10 sm:rounded-[32px] sm:p-8">
            <div class="flex items-start justify-between gap-4">
                <div class="min-w-0">
                    <h1 class="text-2xl font-black text-dark-900 md:text-4xl">{{ $destination->name }}</h1>
                    <p class="mt-1 flex items-center gap-1.5 text-xs font-medium text-dark-400">
                        <span class="h-2 w-2 rounded-full bg-primary-500"></span>
                        {{ $destination->location }}
                    </p>
                </div>
                <div class="shrink-0 text-right">
                    <p class="text-xl font-black text-primary-500 md:text-3xl">{{ $destination->formatted_price }}</p>
                    <p class="text-[10px] font-semibold uppercase tracking-widest text-dark-300">/person</p>
                </div>
            </div>

            <div class="mt-5 flex items-center gap-4 text-xs text-dark-500">
                <div class="flex -space-x-2">
                    <span class="h-8 w-8 rounded-full border-2 border-white bg-primary-100"></span>
                    <span class="h-8 w-8 rounded-full border-2 border-white bg-dark-100"></span>
                    <span class="grid h-8 w-8 place-items-center rounded-full border-2 border-white bg-primary-500 text-[10px] font-bold text-white">32</span>
                </div>
                <span class="font-medium">People Reviewed</span>
                <span class="ml-auto font-bold text-dark-900">★ 4.5 / 5</span>
            </div>

            <div class="mt-6 border-b border-gray-100">
                <div class="grid grid-cols-3 gap-2">
                    <button type="button" data-detail-tab="about" class="detail-tab border-b-2 border-primary-500 pb-3 text-sm font-bold text-dark-900">Tentang</button>
                    <button type="button" data-detail-tab="benefits" class="detail-tab border-b-2 border-transparent pb-3 text-sm font-bold text-dark-300">Benefit</button>
                    <button type="button" data-detail-tab="itinerary" class="detail-tab border-b-2 border-transparent pb-3 text-sm font-bold text-dark-300">Itinerary</button>
                </div>
            </div>

            <div class="pt-6">
                <div data-detail-panel="about" class="detail-panel space-y-6">
                    <div>
                        <h2 class="text-lg font-black text-dark-900">Tentang Destinasi</h2>
                        <p class="mt-3 text-sm leading-7 text-dark-500">
                            {{ $destination->description ?? $destination->short_description }}
                        </p>
                    </div>

                    <div>
                        <h3 class="mb-3 text-base font-black text-dark-900">Galeri Foto</h3>
                        <div class="grid grid-cols-2 gap-3 md:grid-cols-3">
                            @foreach($gallery as $image)
                                <img src="{{ $image }}" alt="{{ $destination->name }} gallery" class="h-36 w-full rounded-2xl object-cover md:h-44">
                            @endforeach
                        </div>
                    </div>
                </div>

                <div data-detail-panel="benefits" class="detail-panel hidden">
                    <h2 class="text-lg font-black text-dark-900">Apa Saja yang Didapat</h2>
                    <div class="mt-4 grid gap-3 md:grid-cols-2">
                        @foreach($benefits as $benefit)
                            <div class="flex items-center gap-3 rounded-2xl bg-primary-50 px-4 py-3 text-sm font-semibold text-dark-700">
                                <span class="grid h-7 w-7 place-items-center rounded-full bg-primary-500 text-white">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                </span>
                                {{ $benefit }}
                            </div>
                        @endforeach
                    </div>
                </div>

                <div data-detail-panel="itinerary" class="detail-panel hidden">
                    <h2 class="text-lg font-black text-dark-900">Itinerary Trip</h2>
                    <div class="mt-5 space-y-4">
                        @foreach($itinerary as $item)
                            <div class="flex gap-4 rounded-2xl border border-gray-100 p-4">
                                <div class="grid h-12 w-12 shrink-0 place-items-center rounded-2xl bg-dark-900 text-[11px] font-black uppercase text-white">{{ $item['day'] }}</div>
                                <div>
                                    <h3 class="font-black text-dark-900">{{ $item['title'] }}</h3>
                                    <p class="mt-1 text-sm leading-6 text-dark-500">{{ $item['desc'] }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="hidden md:block bg-white pb-20">
    <div class="relative h-[450px] overflow-hidden bg-dark-900">
        <img src="{{ $heroImage }}" alt="{{ $destination->name }}" class="h-full w-full object-cover">
        <div class="absolute inset-0 bg-gradient-to-r from-dark-900/85 via-dark-900/45 to-dark-900/10"></div>
        <div class="absolute inset-0 bg-gradient-to-t from-dark-900/70 via-transparent to-dark-900/20"></div>
        <div class="absolute inset-x-0 bottom-0">
            <div class="mx-auto max-w-7xl px-6 pb-14 lg:px-8">
                <div>
                    <p class="mb-4 inline-flex rounded-full bg-white/15 px-4 py-2 text-xs font-black uppercase tracking-widest text-white backdrop-blur">{{ $destination->category->name ?? 'Destination' }}</p>
                    <h1 class="max-w-4xl text-6xl font-black leading-tight text-white">{{ $destination->name }}</h1>
                    <div class="mt-7 flex items-center gap-5 text-sm text-white/75">
                        <span class="inline-flex items-center gap-2">
                            <span class="h-2.5 w-2.5 rounded-full bg-primary-500"></span>
                            {{ $destination->location }}
                        </span>
                        <span>★ 4.5 / 5</span>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <div class="mx-auto grid max-w-7xl grid-cols-[1fr_360px] gap-10 px-6 py-16 lg:px-8">
        <div class="space-y-14">
            <div>
                <p class="text-xs font-black uppercase tracking-[0.25em] text-primary-500">Overview</p>
                <h2 class="mt-3 text-4xl font-black text-dark-900">Tentang Destinasi</h2>
                <p class="mt-5 text-base leading-8 text-dark-500">{{ $destination->description ?? $destination->short_description }}</p>
            </div>

            <div>
                <p class="text-xs font-black uppercase tracking-[0.25em] text-primary-500">Gallery</p>
                <h2 class="mt-3 text-3xl font-black text-dark-900">Galeri Foto</h2>
                <div class="mt-6 grid grid-cols-3 gap-4">
                    @foreach($gallery as $image)
                        <img src="{{ $image }}" alt="{{ $destination->name }} gallery" class="h-60 w-full rounded-2xl object-cover shadow-lg shadow-black/5">
                    @endforeach
                </div>
            </div>

            <div>
                <p class="text-xs font-black uppercase tracking-[0.25em] text-primary-500">Benefits</p>
                <h2 class="mt-3 text-3xl font-black text-dark-900">Apa Saja yang Didapat</h2>
                <div class="mt-6 grid grid-cols-2 gap-4">
                    @foreach($benefits as $benefit)
                        <div class="flex items-center gap-3 rounded-2xl border border-primary-100 bg-primary-50 px-4 py-4 text-sm font-semibold text-dark-700">
                            <span class="grid h-9 w-9 shrink-0 place-items-center rounded-full bg-primary-500 text-white">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                            </span>
                            {{ $benefit }}
                        </div>
                    @endforeach
                </div>
            </div>

            <div>
                <p class="text-xs font-black uppercase tracking-[0.25em] text-primary-500">Trip Plan</p>
                <h2 class="mt-3 text-3xl font-black text-dark-900">Itinerary</h2>
                <div class="mt-6 space-y-4">
                    @foreach($itinerary as $item)
                        <div class="flex gap-4 rounded-2xl border border-gray-100 bg-white p-5 shadow-lg shadow-black/5">
                            <div class="grid h-14 w-14 shrink-0 place-items-center rounded-2xl bg-dark-900 text-xs font-black uppercase text-white">{{ $item['day'] }}</div>
                            <div>
                                <h3 class="font-black text-dark-900">{{ $item['title'] }}</h3>
                                <p class="mt-1 text-sm leading-6 text-dark-500">{{ $item['desc'] }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <aside>
            <div class="sticky top-28 rounded-3xl border border-gray-100 bg-white p-6 shadow-2xl shadow-black/10">
                <h3 class="text-xl font-black text-dark-900">Siap berangkat?</h3>
                <p class="mt-2 text-sm leading-6 text-dark-400">Pilih jadwal terbaik dan amankan slot perjalananmu.</p>
                <div class="my-6 h-px bg-gray-100"></div>
                <div class="flex items-end justify-between">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-widest text-dark-300">Harga</p>
                        <p class="mt-1 text-2xl font-black text-primary-500">{{ $destination->formatted_price }}</p>
                    </div>
                </div>
                <a href="{{ $bookingUrl }}" class="mt-6 inline-flex h-14 w-full items-center justify-center rounded-2xl bg-primary-500 text-sm font-black text-white shadow-lg shadow-primary-500/25 transition hover:bg-primary-600">
                    Book Now
                </a>
                <a href="{{ route('schedules', ['destination' => $destination->id]) }}" class="mt-3 inline-flex h-12 w-full items-center justify-center rounded-2xl bg-gray-50 text-sm font-black text-dark-700 transition hover:bg-gray-100">
                    Lihat Jadwal
                </a>
            </div>
        </aside>
    </div>
</section>

<div class="fixed bottom-0 left-0 right-0 z-[1000] border-t border-gray-100 bg-white/95 px-4 py-3 shadow-2xl shadow-black/15 backdrop-blur md:hidden">
    <div class="mx-auto flex max-w-sm justify-center">
        <a href="{{ $bookingUrl }}" class="inline-flex h-12 w-full max-w-xs items-center justify-center rounded-full bg-primary-500 px-6 text-sm font-black text-white shadow-lg shadow-primary-500/25 transition hover:bg-primary-600">
            Book Now
        </a>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.querySelectorAll('.detail-tab').forEach(tab => {
        tab.addEventListener('click', () => {
            const target = tab.dataset.detailTab;

            document.querySelectorAll('.detail-tab').forEach(item => {
                item.classList.toggle('border-primary-500', item === tab);
                item.classList.toggle('text-dark-900', item === tab);
                item.classList.toggle('border-transparent', item !== tab);
                item.classList.toggle('text-dark-300', item !== tab);
            });

            document.querySelectorAll('.detail-panel').forEach(panel => {
                panel.classList.toggle('hidden', panel.dataset.detailPanel !== target);
            });
        });
    });
</script>
@endpush
