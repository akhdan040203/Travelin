@extends('layouts.main')

@section('title', 'Travelin - Jelajahi Keindahan Indonesia')
@section('meta_description', 'Temukan dan booking paket wisata terbaik di Indonesia. Raja Ampat, Bromo, Bali, Komodo dan destinasi impian lainnya.')

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
    $heroImages = array_merge($localImages, [
        'raja-ampat-paradise' => 'https://images.unsplash.com/photo-1516690561799-46d8f74f9abf?w=1920&q=90&fit=crop',
        'bali-island-hopping' => 'https://images.unsplash.com/photo-1577949269674-517f978815cf?w=1920&q=90&fit=crop',
    ]);
    $defaultImg = 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?w=1920&q=80&fit=crop';
@endphp
<section class="relative h-screen min-h-[700px] overflow-hidden" id="hero-section">
    @foreach($featuredDestinations->take(5) as $index => $dest)
    @php
        $heroImg = $dest->featured_image
            ? asset('storage/' . $dest->featured_image)
            : (isset($heroImages[$dest->slug])
                ? (str_starts_with($heroImages[$dest->slug], 'http') ? $heroImages[$dest->slug] : asset($heroImages[$dest->slug]))
                : $defaultImg);
    @endphp
    <div class="hero-bg absolute inset-0 transition-opacity duration-1000 ease-in-out {{ $index === 0 ? 'opacity-100 z-[1]' : 'opacity-0 z-0' }}"
         data-index="{{ $index }}">
        <img src="{{ $heroImg }}" alt="{{ $dest->name }}"
             class="w-full h-full object-cover scale-105" loading="{{ $index === 0 ? 'eager' : 'lazy' }}">
        <div class="absolute inset-0 bg-gradient-to-r from-dark-900/85 via-dark-900/50 to-dark-900/30"></div>
        <div class="absolute inset-0 bg-gradient-to-t from-dark-900/60 via-transparent to-dark-900/20"></div>
    </div>
    @endforeach

    {{-- Fallback if no featured destinations --}}
    @if($featuredDestinations->isEmpty())
    <div class="absolute inset-0 z-[1]">
        <img src="{{ $defaultImg }}" class="w-full h-full object-cover" alt="Travel">
        <div class="absolute inset-0 bg-gradient-to-r from-dark-900/85 via-dark-900/50 to-dark-900/30"></div>
    </div>
    @endif

    {{-- Hero Content --}}
    <div class="relative z-10 h-full flex items-center">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full">
            <div class="max-w-xl lg:pl-16">
                {{-- Left: Text Content --}}
                <div class="pt-12 sm:pt-20 lg:pt-0">

                    {{-- Destination Name (Large) --}}
                    @php
                        $heroName = $featuredDestinations->isNotEmpty()
                            ? strtoupper(explode(' ', $featuredDestinations->first()->name)[0])
                            : 'TRAVEL';
                        $heroFontClass = strlen($heroName) > 7
                            ? 'text-4xl sm:text-5xl lg:text-6xl'
                            : 'text-6xl sm:text-7xl lg:text-8xl';
                    @endphp
                    <h1 class="{{ $heroFontClass }} font-black text-white leading-none tracking-tight mb-4 transition-all duration-700" id="hero-title">
                        {{ $heroName }}
                    </h1>

                    {{-- Description --}}
                    <p class="text-white/70 text-sm sm:text-base max-w-md leading-relaxed mb-8 transition-all duration-500" id="hero-description">
                        @if($featuredDestinations->isNotEmpty())
                            {{ $featuredDestinations->first()->short_description ?? $featuredDestinations->first()->description }}
                        @else
                            Temukan pengalaman Open Trip all-inclusive terbaik bersama Travelin.
                        @endif
                    </p>

                    {{-- Explore Button --}}
                    <a href="{{ $featuredDestinations->isNotEmpty() ? route('destinations.show', $featuredDestinations->first()->slug) : route('destinations.index') }}"
                       class="inline-flex items-center gap-3 bg-primary-500 text-white px-8 py-4 rounded-xl font-semibold text-sm
                              hover:bg-primary-600 transition-all duration-300 shadow-lg shadow-primary-500/30 hover:shadow-xl hover:shadow-primary-500/40
                              group" id="hero-explore-btn">
                        Explore
                    </a>

                    {{-- Card Pagination Controls --}}
                    <div class="flex items-center gap-4 mt-8">
                        <div class="flex items-center gap-3">
                            <button onclick="prevHero()" class="w-11 h-11 rounded-full border border-white/20 flex items-center justify-center text-white/60 hover:text-white hover:border-white/50 hover:bg-white/5 transition-all">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                            </button>
                            <button onclick="nextHero()" class="w-11 h-11 rounded-full border border-white/20 flex items-center justify-center text-white/60 hover:text-white hover:border-white/50 hover:bg-white/5 transition-all">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            </button>
                        </div>
                        <div class="flex items-center gap-2">
                            @foreach($featuredDestinations->take(5) as $index => $dest)
                            <button class="card-page-dot h-2 rounded-full transition-all duration-300 {{ $index === 0 ? 'bg-primary-500 w-6' : 'bg-white/30 w-2' }}"
                                    data-index="{{ $index }}" onclick="switchHero({{ $index }})"></button>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Right: Horizontal Card Carousel (Foxico-style perspective) --}}
    <div class="hidden lg:block absolute right-0 top-1/2 -translate-y-1/2 z-20" id="hero-cards-wrapper" style="width: 55%; max-width: 680px;">
        <div class="relative w-full" style="height: 440px; perspective: 1200px;">
            @foreach($featuredDestinations->take(5) as $index => $dest)
            @php
                $cardImg = $dest->featured_image
                    ? asset('storage/' . $dest->featured_image)
                    : (isset($heroImages[$dest->slug])
                        ? (str_starts_with($heroImages[$dest->slug], 'http') ? $heroImages[$dest->slug] : asset($heroImages[$dest->slug]))
                        : $defaultImg);
            @endphp
            <div class="hero-card absolute cursor-pointer rounded-2xl overflow-hidden
                        transition-all duration-700 ease-[cubic-bezier(0.25,0.8,0.25,1)] group hero-glass-card"
                 data-index="{{ $index }}"
                 onclick="switchHero({{ $index }})">

                {{-- Glassmorphism border --}}
                <div class="hero-card-border absolute inset-0 rounded-2xl border-2 z-30 pointer-events-none transition-all duration-500
                            {{ $index === 0 ? 'border-white/50' : 'border-white/20' }}"></div>

                {{-- Card Image --}}
                <img src="{{ $cardImg }}" alt="{{ $dest->name }}"
                     class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105" loading="lazy">

                {{-- Gradient Overlay --}}
                <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-black/10"></div>



                {{-- Card Content (bottom) --}}
                <div class="absolute bottom-0 left-0 right-0 p-4 z-20">
                    <p class="text-white/50 text-[10px] font-medium uppercase tracking-wider mb-1">{{ $dest->category->name ?? 'Destination' }}</p>
                    <h3 class="text-white font-bold text-sm leading-tight drop-shadow-lg">{{ $dest->name }}</h3>
                    <div class="flex items-center gap-1.5 mt-1.5">
                        <svg class="w-3 h-3 text-white/40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        <span class="text-white/50 text-[10px]">{{ $dest->location }}</span>
                    </div>
                </div>

                {{-- Hover shine --}}
                <div class="absolute inset-0 opacity-0 group-hover:opacity-100 transition-opacity duration-500
                            bg-gradient-to-tr from-transparent via-white/5 to-white/10 pointer-events-none"></div>
            </div>
            @endforeach
        </div>

    </div>

    {{-- Left Side Indicator (Vertical Line with Numbers) --}}
    <div class="hidden lg:flex absolute left-10 top-1/2 -translate-y-1/2 flex-col items-center gap-8 z-20">
        <div class="w-[1px] h-24 bg-gradient-to-b from-transparent via-white/20 to-white/40"></div>
        <div class="flex flex-col gap-10">
            @foreach($featuredDestinations->take(5) as $index => $dest)
                <div class="side-indicator-item relative flex items-center justify-center group cursor-pointer" 
                     onclick="switchHero({{ $index }})" data-index="{{ $index }}">
                    {{-- Number --}}
                    <span class="side-indicator-num absolute -left-12 text-[10px] font-bold tracking-widest transition-all duration-500 {{ $index === 0 ? 'text-primary-500 opacity-100' : 'text-white/20 opacity-0 group-hover:opacity-40' }}">
                        {{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}
                    </span>
                    
                    {{-- Dot/Circle --}}
                    <div class="side-indicator-dot w-2 h-2 rounded-full border border-white/30 bg-transparent transition-all duration-500 group-hover:border-white/60
                                {{ $index === 0 ? 'w-4 h-4 border-primary-500 bg-primary-500 shadow-[0_0_15px_rgba(253,60,98,0.6)]' : '' }}">
                    </div>
                </div>
            @endforeach
        </div>
        <div class="w-[1px] h-24 bg-gradient-to-t from-transparent via-white/20 to-white/40"></div>
    </div>
</section>

{{-- ============================================ --}}
{{-- SEARCH & FILTER SECTION - NEW MODEL --}}
{{-- ============================================ --}}
<section class="relative -mt-16 sm:-mt-14 lg:-mt-16" style="z-index: 1000;">
    <div class="max-w-6xl mx-auto px-6 sm:px-6 lg:px-8">
        <div id="home-search-card" class="relative bg-white rounded-[28px] shadow-2xl shadow-black/10 px-5 py-5 md:px-7 md:py-6 border border-gray-100/70 cursor-pointer md:cursor-default transition-all duration-300" style="z-index: 1001;">
            <div class="mb-0 md:mb-5 flex items-start justify-between gap-4">
                <div>
                    <p class="text-xs text-dark-300 font-medium">Your Location</p>
                    <p class="text-sm md:text-base font-bold text-dark-900 mt-1">Rembang, Indonesia</p>
                </div>
                <button type="button" id="home-search-toggle" aria-label="Buka filter" class="md:hidden flex h-9 w-9 items-center justify-center rounded-full bg-gray-50 text-dark-400 transition-all duration-300">
                    <svg id="home-search-toggle-icon" class="h-4 w-4 transition-transform duration-300" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                <a href="{{ route('destinations.index') }}" class="hidden md:inline-flex items-center gap-2 text-[11px] font-semibold text-dark-300 hover:text-primary-500 transition-colors">
                    Search History
                    <span>›</span>
                </a>
            </div>
            <form id="home-search-form" action="{{ route('destinations.index') }}" method="GET" class="max-h-0 overflow-hidden opacity-0 mt-0 transition-all duration-300 md:max-h-none md:overflow-visible md:opacity-100 md:mt-0">
                <div class="grid grid-cols-1 md:grid-cols-[1.15fr_1fr_1fr_auto] items-end gap-4 md:gap-3">
                    {{-- Column 1: Location --}}
                    <div class="min-w-0 space-y-2">
                        <label class="block text-[11px] font-medium text-dark-300 pl-4">Location</label>
                        <div class="relative group">
                            <div class="absolute left-4 top-1/2 -translate-y-1/2 text-dark-900 group-focus-within:text-primary-500 transition-colors">
                                <svg class="w-3 h-3 md:w-4 md:h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg>
                            </div>
                            <input type="text" name="search" placeholder="Bali" 
                                   class="h-12 w-full pl-10 pr-4 bg-white border border-gray-200 rounded-full text-xs font-semibold text-dark-900 placeholder:text-dark-300 focus:ring-2 focus:ring-primary-500/15 focus:border-primary-200 transition-all shadow-sm">
                        </div>
                    </div>

                    {{-- Divider --}}
                    <div class="hidden"></div>

                    {{-- Column 2: Category --}}
                    <div class="min-w-0 space-y-2">
                        <label class="block text-[11px] font-medium text-dark-300 pl-4">Category</label>
                        <div class="relative group">
                            <div class="absolute left-4 top-1/2 -translate-y-1/2 text-dark-900 group-focus-within:text-primary-500 transition-colors pointer-events-none">
                                <svg class="w-3 h-3 md:w-4 md:h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                            </div>
                            <button type="button" onclick="toggleCustomDropdown('category', event)"
                                    class="h-12 w-full pl-10 pr-4 bg-white border border-gray-200 rounded-full text-xs font-semibold text-dark-900 text-left flex items-center justify-between gap-2 focus:ring-2 focus:ring-primary-500/15 focus:border-primary-200 transition-all shadow-sm">
                                <span id="selected-category" class="truncate">Any</span>
                                <svg class="text-dark-300 transition-transform duration-300 flex-shrink-0" id="arrow-category" style="width: 12px; height: 12px;" fill="none" stroke="currentColor" stroke-width="2.4" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                            </button>
                            
                            <div id="list-category" class="hidden absolute top-full left-0 right-0 mt-2 bg-white rounded-xl shadow-2xl border border-gray-100 overflow-hidden py-1 max-h-60 overflow-y-auto" style="z-index: 1002;">
                                <div class="px-4 py-2 text-[10px] font-bold text-dark-200 uppercase tracking-widest bg-gray-50/50">Select Category</div>
                                <div onclick="selectCustomOption('category', '', 'Any')" class="px-4 py-2.5 text-xs font-semibold cursor-pointer transition-colors {{ request('category', '') === '' ? 'bg-red-50 text-primary-600' : 'text-dark-600 hover:bg-red-50 hover:text-primary-600' }}">Any</div>
                                @foreach(\App\Models\Category::where('is_active', true)->get() as $cat)
                                    <div onclick="selectCustomOption('category', '{{ $cat->slug }}', '{{ $cat->name }}')" class="px-4 py-2.5 text-xs font-semibold cursor-pointer transition-colors {{ request('category') === $cat->slug ? 'bg-red-50 text-primary-600' : 'text-dark-600 hover:bg-red-50 hover:text-primary-600' }}">{{ $cat->name }}</div>
                                @endforeach
                            </div>
                            <input type="hidden" name="category" value="">
                            <select class="hidden" name="category_old" 
                                    class="w-full pl-9 md:pl-10 pr-3 py-3 md:py-2.5 bg-gray-50 border-0 rounded-lg text-xs md:text-xs font-semibold text-dark-900 appearance-none cursor-pointer focus:ring-2 focus:ring-primary-500/15 transition-all">
                                <option value="">Any</option>
                                @foreach(\App\Models\Category::where('is_active', true)->get() as $cat)
                                    <option value="{{ $cat->slug }}">{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- Divider --}}
                    <div class="hidden"></div>

                    {{-- Column 3: Price --}}
                    <div class="min-w-0 space-y-2">
                        <label class="block text-[11px] font-medium text-dark-300 pl-4">Price</label>
                        <div class="relative group">
                            <div class="absolute left-4 top-1/2 -translate-y-1/2 text-dark-900 group-focus-within:text-primary-500 transition-colors pointer-events-none">
                                <svg class="w-3 h-3 md:w-4 md:h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.407 2.67 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.407-2.67-1M12 16c-1.052 0-2.017-.323-2.482-.857l-1.018 1.018A6 6 0 0012 18V16z" /></svg>
                            </div>
                            <button type="button" onclick="toggleCustomDropdown('price', event)"
                                    class="h-12 w-full pl-10 pr-4 bg-white border border-gray-200 rounded-full text-xs font-semibold text-dark-900 text-left flex items-center justify-between gap-2 focus:ring-2 focus:ring-primary-500/15 focus:border-primary-200 transition-all shadow-sm">
                                <span id="selected-price" class="truncate">Any</span>
                                <svg class="text-dark-300 transition-transform duration-300 flex-shrink-0" id="arrow-price" style="width: 12px; height: 12px;" fill="none" stroke="currentColor" stroke-width="2.4" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                            </button>
                            
                            <div id="list-price" class="hidden absolute top-full left-0 right-0 mt-2 bg-white rounded-xl shadow-2xl border border-gray-100 overflow-hidden py-1" style="z-index: 1002;">
                                <div class="px-4 py-2 text-[10px] font-bold text-dark-200 uppercase tracking-widest bg-gray-50/50">Select Price Range</div>
                                <div onclick="selectCustomOption('price', '', 'Any')" class="px-4 py-2.5 text-xs font-semibold cursor-pointer transition-colors {{ request('price_range', '') === '' ? 'bg-red-50 text-primary-600' : 'text-dark-600 hover:bg-red-50 hover:text-primary-600' }}">Any</div>
                                <div onclick="selectCustomOption('price', '0-1000000', 'Under 1jt')" class="px-4 py-2.5 text-xs font-semibold cursor-pointer transition-colors {{ request('price_range') === '0-1000000' ? 'bg-red-50 text-primary-600' : 'text-dark-600 hover:bg-red-50 hover:text-primary-600' }}">Under 1jt</div>
                                <div onclick="selectCustomOption('price', '1000000-5000000', '1-5jt')" class="px-4 py-2.5 text-xs font-semibold cursor-pointer transition-colors {{ request('price_range') === '1000000-5000000' ? 'bg-red-50 text-primary-600' : 'text-dark-600 hover:bg-red-50 hover:text-primary-600' }}">1-5jt</div>
                                <div onclick="selectCustomOption('price', '10000000+', '10jt+')" class="px-4 py-2.5 text-xs font-semibold cursor-pointer transition-colors {{ request('price_range') === '10000000+' ? 'bg-red-50 text-primary-600' : 'text-dark-600 hover:bg-red-50 hover:text-primary-600' }}">10jt+</div>
                            </div>
                            <input type="hidden" name="price_range" value="">
                            <select class="hidden" name="price_old" 
                                    class="w-full pl-9 md:pl-10 pr-3 py-3 md:py-2.5 bg-gray-50 border-0 rounded-lg text-xs md:text-xs font-semibold text-dark-900 appearance-none cursor-pointer focus:ring-2 focus:ring-primary-500/15 transition-all">
                                <option value="">Any</option>
                                <option value="0-1000000">Under 1jt</option>
                                <option value="1000000-5000000">1-5jt</option>
                                <option value="10000000+">10jt+</option>
                            </select>
                        </div>
                    </div>

                    {{-- Search Button (inline) --}}
                    <button type="submit" aria-label="Search" class="w-full md:w-28 h-12 bg-primary-500 text-white rounded-full font-semibold text-xs flex items-center justify-center gap-2 hover:bg-primary-600 transition-all shadow-lg shadow-primary-500/25 group active:scale-95">
                        <span>Search</span>
                        <svg class="hidden md:block w-3.5 h-3.5 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" stroke-width="2.8" viewBox="0 0 24 24">
                            <circle cx="11" cy="11" r="7"/>
                            <path stroke-linecap="round" d="M20 20l-4.2-4.2"/>
                        </svg>
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>

{{-- ============================================ --}}
{{-- TRUST STATS SECTION --}}
{{-- ============================================ --}}
<section class="pt-32 md:pt-20 pb-12 bg-white border-b border-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-start justify-between md:justify-center gap-2 md:gap-10 max-w-3xl mx-auto">
            <div class="px-1 md:px-4 py-3 text-center flex-1">
                <img src="{{ asset('images/logo_travel.png') }}?v={{ filemtime(public_path('images/logo_travel.png')) }}" alt="Travel experiences" class="mx-auto mb-3 h-12 w-12 object-contain">
                <p class="text-primary-500 text-xs font-black leading-none">24,000+</p>
                <p class="text-dark-900 text-sm font-bold mt-2">Travel Experiences</p>
            </div>

            <div class="px-1 md:px-4 py-3 text-center flex-1">
                <img src="{{ asset('images/logo_destinasion.png') }}?v={{ filemtime(public_path('images/logo_destinasion.png')) }}" alt="Countries" class="mx-auto mb-3 h-12 w-12 object-contain">
                <p class="text-primary-500 text-xs font-black leading-none">55+</p>
                <p class="text-dark-900 text-sm font-bold mt-2">Countries</p>
            </div>

            <div class="px-1 md:px-4 py-3 text-center flex-1">
                <img src="{{ asset('images/logo_user.png') }}?v={{ filemtime(public_path('images/logo_user.png')) }}" alt="Users per year" class="mx-auto mb-3 h-12 w-12 object-contain">
                <p class="text-primary-500 text-xs font-black leading-none">84 Million+</p>
                <p class="text-dark-900 text-sm font-bold mt-2">User per year</p>
            </div>
        </div>
    </div>
</section>

{{-- ============================================ --}}
{{-- BEST TRAVEL DEALS SECTION --}}
{{-- ============================================ --}}
<section class="pt-24 pb-12 md:pt-28 md:pb-20 bg-white relative z-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- Section Header --}}
        <div class="text-center mb-20">
            <h2 class="text-4xl md:text-5xl font-black text-dark-900 leading-tight">
                Discover the Best <span class="text-primary-500 text-stroke-thin">Open Trip</span> <br> of the Month
            </h2>
            <p class="text-dark-300 text-sm mt-6 max-w-2xl mx-auto leading-relaxed font-medium uppercase tracking-widest opacity-70">
                Explore our all-inclusive open trip packages. Every journey includes premium hotels, flights, and curated experiences.
            </p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            {{-- Left: Featured Large Card (Desktop Only) --}}
            <div class="hidden lg:block lg:col-span-5 h-[700px]">
                <div class="group relative h-full rounded-lg overflow-hidden shadow-2xl transition-all duration-700 hover:shadow-primary-500/20">
                    <img src="{{ asset('images/destinations/bali.png') }}" alt="Featured" class="w-full h-full object-cover transition-transform duration-1000 group-hover:scale-110">
                    <div class="absolute inset-0 bg-gradient-to-t from-dark-900/90 via-dark-900/10 to-transparent"></div>
                    
                    {{-- Mini Slider indicators --}}
                    <div class="absolute bottom-10 left-10 flex gap-1.5 z-20">
                        <span class="w-8 h-1.5 rounded-full bg-primary-500"></span>
                        <span class="w-3 h-1.5 rounded-full bg-white/30"></span>
                        <span class="w-3 h-1.5 rounded-full bg-white/30"></span>
                    </div>

                    <div class="absolute bottom-10 left-10 right-10 z-10 pt-10">
                        <h3 class="text-4xl md:text-5xl font-black text-white leading-[1.1] mb-4">
                            Enjoy the <br>
                            <span class="text-primary-500">Mesmerizing</span> <br>
                            Beauty of Nature
                        </h3>
                    </div>
                </div>
            </div>

            {{-- Right: Grid of Cards --}}
            <div class="lg:col-span-7 grid grid-cols-2 md:grid-cols-2 gap-3 md:gap-8">
                @foreach($popularDestinations->take(4) as $dest)
                <div class="group bg-white rounded-lg overflow-hidden transition-all duration-500 hover:-translate-y-2">
                    <div class="relative h-[120px] md:h-[220px] rounded-lg overflow-hidden m-1 md:m-2">
                        @php
                            $localImages = [
                                'raja-ampat-paradise' => 'images/destinations/raja-ampat.png',
                                'bromo-sunrise-experience' => 'images/destinations/bromo.png',
                                'bali-island-hopping' => 'images/destinations/bali.png',
                                'taman-nasional-komodo' => 'images/destinations/komodo.png',
                                'yogyakarta-heritage-tour' => 'images/destinations/yogyakarta.png',
                                'dieng-plateau-adventure' => 'images/destinations/dieng.png',
                            ];
                            $cardImg = $dest->featured_image 
                                ? asset('storage/' . $dest->featured_image) 
                                : (isset($localImages[$dest->slug]) ? asset($localImages[$dest->slug]) : 'https://images.unsplash.com/photo-1537996194471-e657df975ab4?w=800&q=80&fit=crop');
                        @endphp
                        <img src="{{ $cardImg }}" alt="{{ $dest->name }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                    </div>
                    <div class="p-3 md:p-6 pt-2">
                        <h4 class="text-sm md:text-lg font-black text-dark-900 mb-1 md:mb-2 truncate">{{ $dest->name }}</h4>
                        <p class="text-dark-400 text-[10px] md:text-xs line-clamp-1 md:line-clamp-2 mb-3 md:mb-4 leading-relaxed">{{ $dest->short_description }}</p>
                        
                        <div class="flex justify-end border-t border-gray-50 pt-3 md:pt-4">
                            <a href="{{ route('destinations.show', $dest->slug) }}" class="inline-flex items-center gap-1 border-b-2 border-dark-900 pb-0.5 text-[10px] md:text-xs font-black uppercase tracking-widest text-dark-900 hover:border-primary-500 hover:text-primary-500 transition-colors">
                                Detail
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</section>

{{-- ============================================ --}}
{{-- NEW POPULAR DESTINATIONS SECTION --}}
{{-- ============================================ --}}
<section class="pt-12 pb-8 md:pt-20 md:pb-12 bg-white relative overflow-hidden" id="popular-destinations">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- Section Header --}}
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-12 md:mb-16 text-center md:text-left">
            <div class="max-w-xl mx-auto md:mx-0">
                <h2 class="text-3xl md:text-5xl font-black text-dark-900 leading-tight">Popular <br> <span class="text-primary-500 text-stroke-thin">Open Trips</span></h2>
                <p class="text-dark-300 text-[10px] md:text-sm mt-4 font-medium uppercase tracking-[0.2em] opacity-60 leading-relaxed">Explore our all-inclusive packages this month, featuring the best destinations.</p>
            </div>
        </div>

        {{-- Horizontal Scroll Container --}}
        <div class="relative group/scroll">
            <div class="overflow-x-auto pb-8 md:pb-14 hide-scrollbar scroll-smooth" id="popular-scroll">
            <div class="flex gap-4 md:gap-6 w-max px-0.5">
                @foreach($popularDestinations as $dest)
                <div class="w-[85vw] md:w-[388px] shrink-0 bg-white rounded-lg shadow-xl shadow-black/5 overflow-hidden transition-all duration-500 hover:-translate-y-2 border border-gray-50 flex flex-col">
                    {{-- Card Image --}}
                    <div class="relative h-[340px] overflow-hidden m-2 rounded-lg">
                        @php
                            $localImages = [
                                'raja-ampat-paradise' => 'images/destinations/raja-ampat.png',
                                'bromo-sunrise-experience' => 'images/destinations/bromo.png',
                                'bali-island-hopping' => 'images/destinations/bali.png',
                                'taman-nasional-komodo' => 'images/destinations/komodo.png',
                                'yogyakarta-heritage-tour' => 'images/destinations/yogyakarta.png',
                                'dieng-plateau-adventure' => 'images/destinations/dieng.png',
                            ];
                            $cardImg = $dest->featured_image 
                                ? asset('storage/' . $dest->featured_image) 
                                : (isset($localImages[$dest->slug]) ? asset($localImages[$dest->slug]) : 'https://images.unsplash.com/photo-1537996194471-e657df975ab4?w=800&q=80&fit=crop');
                        @endphp
                        <img src="{{ $cardImg }}" 
                             alt="{{ $dest->name }}" 
                             class="w-full h-full object-cover">
                        
                        {{-- Bottom Badges --}}
                        <div class="absolute bottom-4 left-4 right-4 flex items-center justify-between">
                            <div class="flex items-center gap-1.5 px-3 py-1.5 bg-black/30 backdrop-blur-md rounded-xl border border-white/10">
                                <span class="text-[10px] font-bold text-white uppercase tracking-wider truncate max-w-[120px]">{{ $dest->location }}</span>
                            </div>
                        </div>

                        {{-- Card Dots --}}
                        <div class="absolute bottom-1 w-full flex justify-center gap-1.5">
                            <span class="w-1.5 h-1.5 rounded-full bg-white"></span>
                            <span class="w-1.5 h-1.5 rounded-full bg-white/40"></span>
                            <span class="w-1.5 h-1.5 rounded-full bg-white/40"></span>
                        </div>
                    </div>

                    {{-- Card Content --}}
                    <div class="p-6 pt-2 flex flex-col flex-1">
                        <h3 class="text-xl font-bold text-dark-900 mb-3 leading-tight">{{ $dest->name }}</h3>
                        
                        {{-- Icon-free Details (List) --}}
                        <div class="grid grid-cols-2 gap-y-2 gap-x-4 mb-5">
                            <div class="flex items-center gap-2">
                                <span class="w-1 h-1 rounded-full bg-primary-500"></span>
                                <span class="text-[11px] text-dark-400 font-medium">Flights Included</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="w-1 h-1 rounded-full bg-primary-500"></span>
                                <span class="text-[11px] text-dark-400 font-medium">Luxury Hotels</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="w-1 h-1 rounded-full bg-primary-500"></span>
                                <span class="text-[11px] text-dark-400 font-medium">Full Transfers</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="w-1 h-1 rounded-full bg-primary-500"></span>
                                <span class="text-[11px] text-dark-400 font-medium">12+ Activities</span>
                            </div>
                        </div>

                        {{-- Date Pills --}}
                        <div class="flex flex-wrap gap-2 mb-6">
                            @php $dates = ['12 May 2024', '18 Jun 2024', '22 Jul 2024']; @endphp
                            @foreach($dates as $date)
                            <div class="px-3 py-1.5 bg-gray-50 border border-gray-100 rounded-lg text-[10px] font-semibold text-dark-400">{{ $date }}</div>
                            @endforeach
                            <div class="px-3 py-1.5 bg-primary-50 text-primary-600 rounded-lg text-[10px] font-bold">hot deal</div>
                        </div>

                        {{-- Pricing and CTA --}}
                        <div class="mt-auto flex items-end justify-between gap-4">
                            <div class="min-w-0 flex-1">
                                <span class="text-xs text-dark-300 line-through block">Rp {{ number_format($dest->price * 1.2, 0, ',', '.') }}</span>
                                <span class="text-xl font-bold text-primary-500">Rp {{ number_format($dest->price, 0, ',', '.') }}</span>
                            </div>
                            <a href="{{ route('destinations.show', $dest->slug) }}" class="inline-flex h-9 flex-shrink-0 items-center justify-center rounded-lg bg-dark-900 px-4 text-[10px] font-black uppercase tracking-widest text-white hover:bg-primary-500 transition-colors">
                                Book
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        </div>

        {{-- Bottom Pagination Controls --}}
        <div class="flex items-center justify-center md:justify-start gap-3 mt-6">
            <div class="flex items-center gap-3">
                <button onclick="scrollPopular('left')" class="w-11 h-11 md:w-12 md:h-12 rounded-lg border border-gray-100 flex items-center justify-center text-dark-400 hover:text-primary-500 hover:border-primary-500/50 hover:bg-primary-50 transition-all shadow-sm active:scale-90 group">
                    <svg class="w-5 h-5 group-hover:-translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>
                </button>
                <button onclick="scrollPopular('right')" class="w-11 h-11 md:w-12 md:h-12 rounded-lg border border-gray-100 flex items-center justify-center text-dark-400 hover:text-primary-500 hover:border-primary-500/50 hover:bg-primary-50 transition-all shadow-sm active:scale-90 group">
                    <svg class="w-5 h-5 group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                </button>
            </div>
            
            <a href="{{ route('destinations.index') }}" class="ml-6 flex items-center gap-2 text-[11px] font-black text-dark-900 uppercase tracking-widest hover:text-primary-500 transition-all group">
                See All Packages
                <svg class="w-3.5 h-3.5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
            </a>
        </div>
    </div>
</section>

{{-- ============================================ --}}
{{-- TESTIMONIALS SECTION --}}
{{-- ============================================ --}}
<section class="pt-12 pb-24 md:pt-16 md:pb-24 bg-gray-50/50 relative overflow-hidden">
    {{-- Decorative Background Elements --}}
    <div class="absolute top-0 right-0 w-96 h-96 bg-primary-100/30 rounded-full blur-3xl -translate-y-1/2 translate-x-1/2"></div>
    <div class="absolute bottom-0 left-0 w-96 h-96 bg-blue-100/30 rounded-full blur-3xl translate-y-1/2 -translate-x-1/2"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        {{-- Section Header --}}
        <div class="text-center mb-16">
            <h2 class="text-3xl md:text-5xl font-black text-dark-900 leading-tight">
                <span class="block md:inline">What Our</span>
                <span class="block md:inline"><span class="text-primary-500 text-stroke-thin">Travelers</span> Say</span>
            </h2>
            <p class="text-dark-300 text-[10px] md:text-sm mt-4 font-medium uppercase tracking-[0.2em] opacity-60">Over 500+ travelers have shared their amazing experiences with us.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @php
                $testimonials = [
                    [
                        'name' => 'Sarah Johnson',
                        'role' => 'Cultural Enthusiast',
                        'image' => 'https://images.unsplash.com/photo-1494790108377-be9c29b29330?w=400&q=80&fit=crop',
                        'text' => 'Trip ke Labuan Bajo benar-benar luar biasa! Semua detail sudah diurus, mulai dari kapal pinisi hingga spot diving rahasia. Benar-benar all-inclusive tanpa pusing.',
                        'rating' => 5
                    ],
                    [
                        'name' => 'Michael Chen',
                        'role' => 'Photography Hobbyist',
                        'image' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=400&q=80&fit=crop',
                        'text' => 'Pengalaman sunrise di Bromo dengan tim Travelin sangat profesional. Guide-nya tahu persis sudut terbaik untuk memotret tanpa harus berdesakan dengan turis lain.',
                        'rating' => 5
                    ],
                    [
                        'name' => 'Dian Kusuma',
                        'role' => 'Solo Traveler',
                        'image' => 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?w=400&q=80&fit=crop',
                        'text' => 'Awalnya ragu ikut open trip sendirian, tapi ternyata asik banget! Kenalan baru dan semua fasilitas hotelnya jempolan. Pasti bakal booking lagi untuk destinasi berikutnya.',
                        'rating' => 5
                    ],
                ];
            @endphp

            @foreach($testimonials as $testi)
            <div class="bg-white p-8 rounded-lg shadow-xl shadow-black/5 border border-gray-100/50 flex flex-col hover:-translate-y-2 transition-all duration-500 h-full">
                {{-- Quote Icon --}}
                <div class="mb-6 text-primary-500/20">
                    <svg class="w-10 h-10" fill="currentColor" viewBox="0 0 24 24"><path d="M14.017 21L14.017 18C14.017 16.899 14.899 16 16 16L18 16L18 14C18 11.791 16.209 10 14 10L14 8C17.314 8 20 10.686 20 14L20 16L20 21L14.017 21ZM4 21L4 18C4 16.899 4.899 16 6 16L8 16L8 14C8 11.791 6.209 10 4 10L4 8C7.314 8 10 10.686 10 14L10 16L10 21L4 21Z"/></svg>
                </div>
                
                {{-- Review Text --}}
                <p class="text-dark-400 text-sm italic leading-relaxed mb-8 flex-grow">"{{ $testi['text'] }}"</p>
                
                {{-- Reviewer Info --}}
                <div class="flex items-center gap-4 mt-auto">
                    <img src="{{ $testi['image'] }}" alt="{{ $testi['name'] }}" class="w-12 h-12 rounded-full object-cover">
                    <div class="flex-1">
                        <h4 class="text-sm font-black text-dark-900 leading-tight">{{ $testi['name'] }}</h4>
                        <p class="text-[10px] text-dark-300 font-bold uppercase tracking-wider mt-0.5">{{ $testi['role'] }}</p>
                    </div>
                    {{-- Stars --}}
                    <div class="flex items-center gap-0.5">
                        @for($i = 0; $i < $testi['rating']; $i++)
                            <svg class="w-3 h-3 text-amber-500" fill="currentColor" viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                        @endfor
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>




{{-- ============================================ --}}
{{-- FAQ SECTION --}}
{{-- ============================================ --}}
<section class="py-28 bg-gray-50/50">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-4xl md:text-5xl font-black text-dark-900 mb-6">Frequently Asked Questions</h2>
            <p class="text-dark-400 font-medium uppercase tracking-widest text-xs opacity-70">FAQs address common inquiries and provide essential information, helping users find solutions quickly.</p>
        </div>

        <div class="space-y-4">
            @php
                $faqs = [
                    ['q' => 'How do I book a trip on Travelin?', 'a' => 'After finding your desired flight, hotel, or activity, simply follow the on-screen prompts to select your dates, number of travelers, and any additional preferences. Then proceed to the secure payment gateway to confirm your booking.'],
                    ['q' => 'Does Travelin offer travel insurance?', 'a' => 'Yes, we provide comprehensive travel insurance options to protect your journey against unexpected events. You can add insurance during the checkout process.'],
                    ['q' => 'Does Travelin provide travel recommendations?', 'a' => 'Absolutely! Our experts curate personalized recommendations based on your preferences and previous trips to ensure you have the best experience.'],
                    ['q' => 'Do you offer discount for group bookings?', 'a' => 'We offer special rates for groups of 10 or more. Please contact our support team for a custom quote.'],
                    ['q' => 'Can I cancel or reschedule my trip?', 'a' => 'Cancellation and rescheduling policies vary by package. You can check the specific terms of your booking in your dashboard or contact our customer support.'],
                ];
            @endphp

            @foreach($faqs as $index => $faq)
            <div class="group bg-white rounded-lg border border-gray-100 overflow-hidden transition-all duration-300 hover:shadow-xl hover:shadow-black/5">
                <button onclick="toggleFaq({{ $index }})" class="w-full px-8 py-6 flex items-center justify-between text-left">
                    <span class="text-base font-black text-dark-900 group-hover:text-primary-500 transition-colors">{{ $faq['q'] }}</span>
                    <svg class="w-5 h-5 text-dark-300 transform transition-transform duration-300 faq-icon-{{ $index }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7"/></svg>
                </button>
                <div class="faq-content-{{ $index }} hidden px-8 pb-8">
                    <p class="text-dark-400 text-sm leading-relaxed">{{ $faq['a'] }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ============================================ --}}
{{-- FOOTER CTA BANNER --}}
{{-- ============================================ --}}
<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="relative h-[500px] rounded-lg overflow-hidden flex items-center justify-center text-center">
            <img src="https://images.unsplash.com/photo-1506929562872-bb421503ef21?w=1920&q=80&fit=crop" class="absolute inset-0 w-full h-full object-cover">
            <div class="absolute inset-0 bg-dark-900/40 backdrop-blur-[2px]"></div>
            <div class="relative z-10 px-6">
                <h2 class="text-4xl md:text-6xl font-black text-white leading-tight mb-8">
                    Get Ready Your Thrilling <br>
                    Journey Into Nature Today
                </h2>
                <p class="text-white/80 text-lg mb-12 max-w-2xl mx-auto font-medium">Excited to plan your next adventure? Let's explore details to make it an unforgettable experience!</p>
                <a href="{{ route('destinations.index') }}" class="inline-flex items-center px-10 py-5 bg-white text-dark-900 font-black text-[11px] uppercase tracking-[0.2em] rounded-lg hover:bg-primary-500 hover:text-white transition-all shadow-2xl active:scale-95">
                    Get Started Now
                </a>
            </div>
        </div>
    </div>
</section>

<script>
    function toggleFaq(index) {
        const content = document.querySelector(`.faq-content-${index}`);
        const icon = document.querySelector(`.faq-icon-${index}`);
        
        const isHidden = content.classList.contains('hidden');
        
        // Close all first (optional, for accordion effect)
        // document.querySelectorAll('[class^="faq-content-"]').forEach(el => el.classList.add('hidden'));
        // document.querySelectorAll('[class^="faq-icon-"]').forEach(el => el.classList.remove('rotate-180'));
        
        if (isHidden) {
            content.classList.remove('hidden');
            icon.classList.add('rotate-180');
        } else {
            content.classList.add('hidden');
            icon.classList.remove('rotate-180');
        }
    }
</script>
</section>

@endsection

@push('styles')
<style>
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .hide-scrollbar::-webkit-scrollbar {
        display: none;
    }
    .hide-scrollbar {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }

    /* Hero Card Carousel */
    .hero-glass-card {
        box-shadow:
            0 15px 50px rgba(0, 0, 0, 0.4),
            0 5px 15px rgba(0, 0, 0, 0.2),
            inset 0 1px 0 rgba(255, 255, 255, 0.1);
    }
    .hero-glass-card:hover {
        box-shadow:
            0 20px 60px rgba(0, 0, 0, 0.5),
            0 8px 20px rgba(0, 0, 0, 0.3),
            inset 0 1px 0 rgba(255, 255, 255, 0.15);
    }
</style>
@endpush

@push('scripts')
@php
    $heroJsonData = $featuredDestinations->take(5)->map(function($d) {
        return [
            'name' => strtoupper(explode(' ', $d->name)[0]),
            'description' => $d->short_description ?: \Illuminate\Support\Str::limit($d->description, 150),
            'slug' => $d->slug,
        ];
    })->values();
@endphp
<script>
    // Hero data from Blade
    const heroData = @json($heroJsonData);

    let currentHero = 0;
    let heroInterval = null;
    const AUTO_PLAY_DELAY = 6000;
    const totalCards = heroData.length;

    // Card carousel layout config
    const CARD_CONFIG = {
        mainWidth: 240,    // Active card width
        mainHeight: 380,   // Active card height
        sideWidth: 160,    // Side card width
        sideHeight: 300,   // Side card height
        farWidth: 120,     // Far card width
        farHeight: 240,    // Far card height
        gap: 14,           // Gap between cards
        offsetY: 20,       // Vertical offset for depth
    };

    // Position all cards based on the active index
    function layoutCards(activeIndex) {
        const cards = document.querySelectorAll('.hero-card');
        const container = document.querySelector('#hero-cards-wrapper .relative');
        if (!container || cards.length === 0) return;

        const containerW = container.offsetWidth;
        const containerH = container.offsetHeight;

        cards.forEach(card => {
            const i = parseInt(card.dataset.index);
            // Calculate circular distance for wrapping
            let rawDiff = i - activeIndex;
            // Wrap around: find shortest path in circular list
            if (rawDiff > totalCards / 2) rawDiff -= totalCards;
            if (rawDiff < -totalCards / 2) rawDiff += totalCards;
            const diff = rawDiff;

            const border = card.querySelector('.hero-card-border');

            if (diff === 0) {
                // ACTIVE card — large, front-center
                const x = 0;
                const y = (containerH - CARD_CONFIG.mainHeight) / 2;
                card.style.width = CARD_CONFIG.mainWidth + 'px';
                card.style.height = CARD_CONFIG.mainHeight + 'px';
                card.style.transform = `translate(${x}px, ${y}px) scale(1)`;
                card.style.opacity = '1';
                card.style.zIndex = '20';
                card.style.filter = 'none';
                if (border) {
                    border.classList.remove('border-white/20');
                    border.classList.add('border-white/50');
                }
            } else if (diff === 1) {
                // NEXT card — medium, to the right
                const x = CARD_CONFIG.mainWidth + CARD_CONFIG.gap;
                const y = (containerH - CARD_CONFIG.sideHeight) / 2 + CARD_CONFIG.offsetY;
                card.style.width = CARD_CONFIG.sideWidth + 'px';
                card.style.height = CARD_CONFIG.sideHeight + 'px';
                card.style.transform = `translate(${x}px, ${y}px) scale(1)`;
                card.style.opacity = '0.85';
                card.style.zIndex = '15';
                card.style.filter = 'none';
                if (border) {
                    border.classList.remove('border-white/50');
                    border.classList.add('border-white/20');
                }
            } else if (diff === 2) {
                // FAR-NEXT card — small, further right
                const x = CARD_CONFIG.mainWidth + CARD_CONFIG.sideWidth + CARD_CONFIG.gap * 2;
                const y = (containerH - CARD_CONFIG.farHeight) / 2 + CARD_CONFIG.offsetY * 1.5;
                card.style.width = CARD_CONFIG.farWidth + 'px';
                card.style.height = CARD_CONFIG.farHeight + 'px';
                card.style.transform = `translate(${x}px, ${y}px) scale(1)`;
                card.style.opacity = '0.5';
                card.style.zIndex = '10';
                card.style.filter = 'brightness(0.7)';
                if (border) {
                    border.classList.remove('border-white/50');
                    border.classList.add('border-white/20');
                }
            } else if (diff === -1) {
                // PREV card — slides off to the left partially
                const x = -CARD_CONFIG.sideWidth * 0.6;
                const y = (containerH - CARD_CONFIG.sideHeight) / 2 + CARD_CONFIG.offsetY;
                card.style.width = CARD_CONFIG.sideWidth + 'px';
                card.style.height = CARD_CONFIG.sideHeight + 'px';
                card.style.transform = `translate(${x}px, ${y}px) scale(0.9)`;
                card.style.opacity = '0.3';
                card.style.zIndex = '5';
                card.style.filter = 'brightness(0.5)';
                if (border) {
                    border.classList.remove('border-white/50');
                    border.classList.add('border-white/20');
                }
            } else {
                // All other cards — hidden off-screen
                card.style.width = CARD_CONFIG.farWidth + 'px';
                card.style.height = CARD_CONFIG.farHeight + 'px';
                const hideX = diff > 0
                    ? CARD_CONFIG.mainWidth + CARD_CONFIG.sideWidth + CARD_CONFIG.farWidth + CARD_CONFIG.gap * 3 + 50
                    : -CARD_CONFIG.sideWidth * 1.5;
                card.style.transform = `translate(${hideX}px, ${containerH/2 - CARD_CONFIG.farHeight/2}px) scale(0.8)`;
                card.style.opacity = '0';
                card.style.zIndex = '1';
                card.style.filter = 'brightness(0.3)';
                if (border) {
                    border.classList.remove('border-white/50');
                    border.classList.add('border-white/20');
                }
            }
        });
    }

    function switchHero(index) {
        if (index === currentHero || !heroData[index]) return;
        currentHero = index;

        // 1. Crossfade backgrounds
        document.querySelectorAll('.hero-bg').forEach(bg => {
            const i = parseInt(bg.dataset.index);
            if (i === index) {
                bg.classList.remove('opacity-0', 'z-0');
                bg.classList.add('opacity-100', 'z-[1]');
            } else {
                bg.classList.remove('opacity-100', 'z-[1]');
                bg.classList.add('opacity-0', 'z-0');
            }
        });

        // 2. Update text with fade
        const title = document.getElementById('hero-title');
        const desc = document.getElementById('hero-description');

        title.style.opacity = '0';
        title.style.transform = 'translateY(20px)';
        desc.style.opacity = '0';

        setTimeout(() => {
            title.textContent = heroData[index].name;
            desc.textContent = heroData[index].description;

            const name = heroData[index].name;
            title.classList.remove('text-4xl', 'text-5xl', 'text-6xl', 'text-7xl', 'text-8xl',
                                   'sm:text-5xl', 'sm:text-7xl', 'lg:text-6xl', 'lg:text-8xl');
            if (name.length > 7) {
                title.classList.add('text-4xl', 'sm:text-5xl', 'lg:text-6xl');
            } else {
                title.classList.add('text-6xl', 'sm:text-7xl', 'lg:text-8xl');
            }

            title.style.opacity = '1';
            title.style.transform = 'translateY(0)';
            desc.style.opacity = '1';
        }, 300);

        // 3. Update explore link
        const btn = document.getElementById('hero-explore-btn');
        if (btn) btn.href = '/destinations/' + heroData[index].slug;

        // 4. Reposition card carousel
        layoutCards(index);

        // 5. Update left-side pagination dots
        document.querySelectorAll('.card-page-dot').forEach(dot => {
            const i = parseInt(dot.dataset.index);
            if (i === index) {
                dot.classList.remove('bg-white/30', 'w-2');
                dot.classList.add('bg-primary-500', 'w-6');
            } else {
                dot.classList.remove('bg-primary-500', 'w-6');
                dot.classList.add('bg-white/30', 'w-2');
            }
        });

        // 7. Update sidebar indicator
        document.querySelectorAll('.side-indicator-item').forEach(item => {
            const i = parseInt(item.dataset.index);
            const num = item.querySelector('.side-indicator-num');
            const dot = item.querySelector('.side-indicator-dot');

            if (i === index) {
                num.classList.remove('text-white/20', 'opacity-0');
                num.classList.add('text-primary-500', 'opacity-100');
                dot.classList.remove('w-2', 'h-2', 'border-white/30', 'bg-transparent');
                dot.classList.add('w-4', 'h-4', 'border-primary-500', 'bg-primary-500', 'shadow-[0_0_15px_rgba(253,60,98,0.6)]');
            } else {
                num.classList.remove('text-primary-500', 'opacity-100');
                num.classList.add('text-white/20', 'opacity-0');
                dot.classList.remove('w-4', 'h-4', 'border-primary-500', 'bg-primary-500', 'shadow-[0_0_15px_rgba(253,60,98,0.6)]');
                dot.classList.add('w-2', 'h-2', 'border-white/30', 'bg-transparent');
            }
        });

        resetAutoPlay();
    }

    function nextHero() {
        const next = (currentHero + 1) % heroData.length;
        switchHero(next);
    }

    function prevHero() {
        const prev = (currentHero - 1 + heroData.length) % heroData.length;
        switchHero(prev);
    }

    function startAutoPlay() {
        heroInterval = setInterval(() => nextHero(), AUTO_PLAY_DELAY);
    }

    function resetAutoPlay() {
        if (heroInterval) clearInterval(heroInterval);
        startAutoPlay();
    }

    // Init: layout cards + auto-play + hover pause
    document.addEventListener('DOMContentLoaded', () => {
        layoutCards(0); // Initial layout

        const heroSection = document.getElementById('hero-section');
        if (heroSection) {
            heroSection.addEventListener('mouseenter', () => {
                if (heroInterval) clearInterval(heroInterval);
            });
            heroSection.addEventListener('mouseleave', () => startAutoPlay());
        }

        const searchCard = document.getElementById('home-search-card');
        const searchForm = document.getElementById('home-search-form');
        const searchToggle = document.getElementById('home-search-toggle');
        const searchToggleIcon = document.getElementById('home-search-toggle-icon');
        if (searchCard && searchForm) {
            const setSearchOpen = (isOpen) => {
                searchCard.dataset.open = isOpen ? 'true' : 'false';
                searchToggle?.setAttribute('aria-label', isOpen ? 'Tutup filter' : 'Buka filter');
                searchToggleIcon?.classList.toggle('rotate-180', isOpen);
                searchCard.classList.toggle('py-6', isOpen);
                searchCard.classList.toggle('py-5', !isOpen);
                searchForm.classList.toggle('max-h-[520px]', isOpen);
                searchForm.classList.toggle('opacity-100', isOpen);
                searchForm.classList.toggle('mt-5', isOpen);
                searchForm.classList.toggle('max-h-0', !isOpen);
                searchForm.classList.toggle('opacity-0', !isOpen);
                searchForm.classList.toggle('mt-0', !isOpen);
            };

            searchCard.addEventListener('click', () => {
                if (window.innerWidth >= 768 || searchCard.dataset.open === 'true') return;
                setSearchOpen(true);
            });

            searchToggle?.addEventListener('click', (event) => {
                event.stopPropagation();
                if (window.innerWidth >= 768) return;
                setSearchOpen(searchCard.dataset.open !== 'true');
            });

            searchForm.addEventListener('click', (event) => {
                event.stopPropagation();
            });

            window.addEventListener('resize', () => {
                if (window.innerWidth >= 768) {
                    searchCard.dataset.open = 'false';
                    searchToggleIcon?.classList.remove('rotate-180');
                searchCard.classList.remove('py-5');
                searchCard.classList.add('py-6');
                searchForm.classList.remove('max-h-0', 'opacity-0', 'mt-0');
                searchForm.classList.add('max-h-[520px]', 'opacity-100', 'mt-5');
                } else if (searchCard.dataset.open !== 'true') {
                    setSearchOpen(false);
                }
            });
        }
        startAutoPlay();
    });

    // Scroll Popular Destinations
    function scrollPopular(direction) {
        const scroll = document.getElementById('popular-scroll');
        const amount = 350;
        if (direction === 'left') {
            scroll.scrollBy({ left: -amount, behavior: 'smooth' });
        } else {
            scroll.scrollBy({ left: amount, behavior: 'smooth' });
        }
    }

    function toggleCustomDropdown(name, event) {
        event.stopPropagation();
        const list = document.getElementById('list-' + name);
        const arrow = document.getElementById('arrow-' + name);
        document.querySelectorAll('[id^="list-"]').forEach(el => {
            if (el.id !== 'list-' + name) el.classList.add('hidden');
        });
        document.querySelectorAll('[id^="arrow-"]').forEach(el => {
            if (el.id !== 'arrow-' + name) el.classList.remove('rotate-180');
        });
        list.classList.toggle('hidden');
        arrow.classList.toggle('rotate-180');
    }

    function selectCustomOption(name, value, label) {
        document.getElementById('selected-' + name).innerText = label;
        const inputName = name === 'price' ? 'price_range' : name;
        document.getElementsByName(inputName)[0].value = value;
        document.getElementById('list-' + name).classList.add('hidden');
        document.getElementById('arrow-' + name).classList.remove('rotate-180');
    }

    window.addEventListener('click', () => {
        document.querySelectorAll('[id^="list-"]').forEach(el => el.classList.add('hidden'));
        document.querySelectorAll('[id^="arrow-"]').forEach(el => el.classList.remove('rotate-180'));
    });
</script>
@endpush


