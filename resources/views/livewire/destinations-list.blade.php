<div>
    {{-- Search & Filter Bar (matching homepage style) --}}
    <div class="relative mb-10 max-w-6xl mx-auto px-2 sm:px-0" style="z-index: 1000;">
        @php
            $categories = \App\Models\Category::where('is_active', true)->get();
            $selectedCategory = $categories->firstWhere('slug', $category);
            $priceLabels = [
                '' => 'Any',
                '0-1000000' => 'Under 1jt',
                '1000000-5000000' => '1-5jt',
                '10000000+' => '10jt+',
            ];
            $selectedPriceLabel = $priceLabels[$priceRange] ?? 'Any';
        @endphp
        <div id="destination-search-card" class="relative bg-white rounded-[28px] shadow-2xl shadow-black/10 px-5 py-5 md:px-7 md:py-6 border border-gray-100/70 cursor-pointer md:cursor-default transition-all duration-300" style="z-index: 1001;">
            <div class="mb-0 md:mb-5 flex items-start justify-between gap-4">
                <div>
                    <p class="text-xs text-dark-300 font-medium">Your Location</p>
                    <p class="text-sm md:text-base font-bold text-dark-900 mt-1">{{ $search ?: 'Rembang, Indonesia' }}</p>
                </div>
                <button type="button" id="destination-search-toggle" aria-label="Buka filter" class="md:hidden flex h-9 w-9 items-center justify-center rounded-full bg-gray-50 text-dark-400 transition-all duration-300">
                    <svg id="destination-search-toggle-icon" class="h-4 w-4 transition-transform duration-300" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                <div class="hidden">
                <a href="{{ route('destinations.index') }}" class="hidden md:inline-flex items-center gap-2 text-[11px] font-semibold text-dark-300 hover:text-primary-500 transition-colors">
                    <span>›</span>
                </a>
                </div>
            </div>
            <div id="destination-search-form" class="max-h-0 overflow-hidden opacity-0 mt-0 transition-all duration-300 md:max-h-none md:overflow-visible md:opacity-100 md:mt-0">
                <div class="grid grid-cols-1 md:grid-cols-[1.15fr_1fr_1fr_auto] items-end gap-4 md:gap-3">
                    {{-- Column 1: Location --}}
                    <div class="min-w-0 space-y-2">
                        <label class="block text-[11px] font-medium text-dark-300 pl-4">Location</label>
                        <div class="relative group">
                            <div class="absolute left-4 top-1/2 -translate-y-1/2 text-dark-900 group-focus-within:text-primary-500 transition-colors">
                                <svg class="w-3 h-3 md:w-4 md:h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg>
                            </div>
                            <input type="text" wire:model.live.debounce.450ms="search" placeholder="Bali"
                                   class="h-12 w-full pl-10 pr-4 bg-white border border-gray-200 rounded-full text-xs font-semibold text-dark-900 placeholder:text-dark-300 focus:ring-2 focus:ring-primary-500/15 focus:border-primary-200 transition-all shadow-sm">
                        </div>
                    </div>

                    <div class="hidden"></div>

                    {{-- Column 2: Category --}}
                    <div class="min-w-0 space-y-2">
                        <label class="block text-[11px] font-medium text-dark-300 pl-4">Category</label>
                        <div class="relative group">
                            <div class="absolute left-4 top-1/2 -translate-y-1/2 text-dark-900 pointer-events-none">
                                <svg class="w-3 h-3 md:w-4 md:h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                            </div>
                            <button type="button" onclick="toggleDestinationDropdown('category', event)"
                                    class="h-12 w-full pl-10 pr-4 bg-white border border-gray-200 rounded-full text-xs font-semibold text-dark-900 text-left flex items-center justify-between gap-2 focus:ring-2 focus:ring-primary-500/15 focus:border-primary-200 transition-all shadow-sm">
                                <span id="dest-selected-category" class="truncate">{{ $selectedCategory->name ?? 'Any' }}</span>
                                <svg class="text-dark-300 transition-transform duration-300 flex-shrink-0" id="dest-arrow-category" style="width: 12px; height: 12px;" fill="none" stroke="currentColor" stroke-width="2.4" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                            </button>
                            <div id="dest-list-category" class="hidden absolute top-full left-0 right-0 mt-2 bg-white rounded-xl shadow-2xl border border-gray-100 overflow-hidden py-1 max-h-60 overflow-y-auto" style="z-index: 1002;">
                                <div class="px-4 py-2 text-[10px] font-bold text-dark-200 uppercase tracking-widest bg-gray-50/50">Select Category</div>
                                <div wire:click="$set('category', '')" onclick="selectDestinationOption('category', '', 'Any')" class="px-4 py-2.5 text-xs font-semibold cursor-pointer transition-colors {{ $category === '' ? 'bg-red-50 text-primary-600' : 'text-dark-600 hover:bg-red-50 hover:text-primary-600' }}">Any</div>
                                @foreach($categories as $cat)
                                    <div wire:click="$set('category', '{{ $cat->slug }}')" onclick="selectDestinationOption('category', '{{ $cat->slug }}', '{{ $cat->name }}')" class="px-4 py-2.5 text-xs font-semibold cursor-pointer transition-colors {{ $category === $cat->slug ? 'bg-red-50 text-primary-600' : 'text-dark-600 hover:bg-red-50 hover:text-primary-600' }}">{{ $cat->name }}</div>
                                @endforeach
                            </div>
                            <input id="dest-input-category" type="hidden" name="category" value="{{ $category }}">
                        </div>
                    </div>

                    <div class="hidden"></div>

                    {{-- Column 3: Price --}}
                    <div class="min-w-0 space-y-2">
                        <label class="block text-[11px] font-medium text-dark-300 pl-4">Price</label>
                        <div class="relative group">
                            <div class="absolute left-4 top-1/2 -translate-y-1/2 text-dark-900 pointer-events-none">
                                <svg class="w-3 h-3 md:w-4 md:h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.407 2.67 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.407-2.67-1"/></svg>
                            </div>
                            <button type="button" onclick="toggleDestinationDropdown('price', event)"
                                    class="h-12 w-full pl-10 pr-4 bg-white border border-gray-200 rounded-full text-xs font-semibold text-dark-900 text-left flex items-center justify-between gap-2 focus:ring-2 focus:ring-primary-500/15 focus:border-primary-200 transition-all shadow-sm">
                                <span id="dest-selected-price" class="truncate">{{ $selectedPriceLabel }}</span>
                                <svg class="text-dark-300 transition-transform duration-300 flex-shrink-0" id="dest-arrow-price" style="width: 12px; height: 12px;" fill="none" stroke="currentColor" stroke-width="2.4" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                            </button>
                            <div id="dest-list-price" class="hidden absolute top-full left-0 right-0 mt-2 bg-white rounded-xl shadow-2xl border border-gray-100 overflow-hidden py-1" style="z-index: 1002;">
                                <div class="px-4 py-2 text-[10px] font-bold text-dark-200 uppercase tracking-widest bg-gray-50/50">Select Price Range</div>
                                <div wire:click="$set('priceRange', '')" onclick="selectDestinationOption('price', '', 'Any')" class="px-4 py-2.5 text-xs font-semibold cursor-pointer transition-colors {{ $priceRange === '' ? 'bg-red-50 text-primary-600' : 'text-dark-600 hover:bg-red-50 hover:text-primary-600' }}">Any</div>
                                <div wire:click="$set('priceRange', '0-1000000')" onclick="selectDestinationOption('price', '0-1000000', 'Under 1jt')" class="px-4 py-2.5 text-xs font-semibold cursor-pointer transition-colors {{ $priceRange === '0-1000000' ? 'bg-red-50 text-primary-600' : 'text-dark-600 hover:bg-red-50 hover:text-primary-600' }}">Under 1jt</div>
                                <div wire:click="$set('priceRange', '1000000-5000000')" onclick="selectDestinationOption('price', '1000000-5000000', '1-5jt')" class="px-4 py-2.5 text-xs font-semibold cursor-pointer transition-colors {{ $priceRange === '1000000-5000000' ? 'bg-red-50 text-primary-600' : 'text-dark-600 hover:bg-red-50 hover:text-primary-600' }}">1-5jt</div>
                                <div wire:click="$set('priceRange', '10000000+')" onclick="selectDestinationOption('price', '10000000+', '10jt+')" class="px-4 py-2.5 text-xs font-semibold cursor-pointer transition-colors {{ $priceRange === '10000000+' ? 'bg-red-50 text-primary-600' : 'text-dark-600 hover:bg-red-50 hover:text-primary-600' }}">10jt+</div>
                            </div>
                            <input id="dest-input-price" type="hidden" name="price_range" value="{{ $priceRange }}">
                        </div>
                    </div>

                    <button type="button" aria-label="Live search" class="w-full md:w-28 h-12 bg-primary-500 text-white rounded-full font-semibold text-xs flex items-center justify-center gap-2 shadow-lg shadow-primary-500/25">
                        <span wire:loading.remove>Live</span>
                        <span wire:loading>Loading</span>
                        <svg class="hidden md:block w-3.5 h-3.5 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" stroke-width="2.8" viewBox="0 0 24 24">
                            <circle cx="11" cy="11" r="7"/>
                            <path stroke-linecap="round" d="M20 20l-4.2-4.2"/>
                        </svg>
                    </button>
                </div>
                <input type="hidden" name="sort" value="{{ $sort }}">
            </div>
        </div>
    </div>

    @if($destinations->count() > 0)
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-dark-900 font-bold text-sm">Semua Destinasi</h2>
            <p class="text-dark-400 text-xs">Menampilkan {{ $destinations->count() }} dari {{ $destinations->total() }} hasil</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5 md:gap-6">
            @foreach($destinations as $dest)
                @php
                    $isWishlisted = auth()->check() ? auth()->user()->hasWishlisted($dest->id) : false;
                @endphp
                <div class="group bg-white rounded-lg shadow-xl shadow-black/5 overflow-hidden transition-all duration-500 hover:-translate-y-2 border border-gray-50 flex flex-col h-full">
                    {{-- Card Image --}}
                    <div class="relative h-[300px] md:h-[320px] overflow-hidden m-2 rounded-lg">
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
                                : (isset($localImages[$dest->slug]) ? asset($localImages[$dest->slug]) : 'https://images.unsplash.com/photo-1544644181-1484b3fdfc62?w=800&q=80&fit=crop');
                        @endphp
                        <img src="{{ $cardImg }}" 
                             alt="{{ $dest->name }}" 
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700" loading="lazy">
                        <a href="{{ route('destinations.show', $dest->slug) }}" class="absolute inset-0 z-10" aria-label="Lihat detail {{ $dest->name }}"></a>
                        
                        <div class="absolute top-3 right-3 z-20">
                            @auth
                                @if(auth()->user()->role !== 'admin')
                                    <form method="POST" action="{{ route('user.wishlist.toggle', $dest) }}" class="js-wishlist-form" data-destination-id="{{ $dest->id }}">
                                        @csrf
                                        <button type="submit" aria-label="Toggle wishlist" class="js-wishlist-button w-9 h-9 rounded-full bg-white/95 text-red-500 shadow-lg flex items-center justify-center hover:bg-red-50 transition-colors">
                                            <svg class="js-wishlist-icon w-4 h-4" fill="{{ $isWishlisted ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.9" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                            </svg>
                                        </button>
                                    </form>
                                @endif
                            @else
                                <a href="{{ route('login') }}" aria-label="Login untuk wishlist" class="w-9 h-9 rounded-full bg-white/95 text-red-500 shadow-lg flex items-center justify-center hover:bg-red-50 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.9" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                    </svg>
                                </a>
                            @endauth
                        </div>

                    </div>

                    {{-- Card Content --}}
                    <div class="p-6 pt-2 flex flex-col flex-1">
                        <a href="{{ route('destinations.show', $dest->slug) }}" class="text-xl font-bold text-dark-900 mb-3 leading-tight group-hover:text-primary-500 transition-colors line-clamp-2">{{ $dest->name }}</a>

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

                        <div class="flex flex-wrap gap-2 mb-6">
                            @php $dates = ['12 May 2024', '18 Jun 2024', '22 Jul 2024']; @endphp
                            @foreach($dates as $date)
                                <div class="px-3 py-1.5 bg-gray-50 border border-gray-100 rounded-lg text-[10px] font-semibold text-dark-400">{{ $date }}</div>
                            @endforeach
                            <div class="px-3 py-1.5 bg-primary-50 text-primary-600 rounded-lg text-[10px] font-bold">hot deal</div>
                        </div>

                        <div class="flex items-end justify-between gap-4 mt-auto">
                            <div class="min-w-0 flex-1">
                                <span class="text-xs text-dark-300 line-through block">Rp {{ number_format($dest->price * 1.2, 0, ',', '.') }}</span>
                                <span class="block truncate text-xl font-bold text-primary-500">{{ $dest->formatted_price }}</span>
                            </div>
                            <a href="{{ route('destinations.show', $dest->slug) }}" class="inline-flex h-9 flex-shrink-0 items-center justify-center rounded-lg bg-dark-900 px-4 text-[10px] font-black uppercase tracking-widest text-white hover:bg-primary-500 transition-colors">
                                Book
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Load More --}}
        @if($destinations->hasMorePages())
            <div class="text-center mt-14">
                <button wire:click="loadMore" wire:loading.attr="disabled" class="bg-dark-900 text-white px-8 py-3 rounded-xl font-bold text-xs uppercase tracking-wider hover:bg-primary-500 transition-all shadow-lg active:scale-95">
                    <span wire:loading.remove>Tampilkan Lebih Banyak</span>
                    <span wire:loading>Memuat...</span>
                </button>
            </div>
        @endif
    @else
        <div class="text-center py-20 bg-gray-50 rounded-2xl border border-dashed border-gray-200">
            <h3 class="text-2xl font-bold text-dark-900">Destinasi Tidak Ditemukan</h3>
            <p class="text-dark-400 mt-2 mb-8 text-sm">Maaf, kami tidak menemukan petualangan yang cocok.</p>
            <button wire:click="$set('search', ''); $set('category', '');" class="bg-primary-500 text-white px-6 py-2.5 rounded-xl font-bold text-xs uppercase tracking-wider hover:bg-primary-600 transition-all">Lihat Semua</button>
        </div>
    @endif
</div>

@push('scripts')
<script>
    function setDestinationSearchOpen(isOpen) {
        const card = document.getElementById('destination-search-card');
        const form = document.getElementById('destination-search-form');
        const toggle = document.getElementById('destination-search-toggle');
        const icon = document.getElementById('destination-search-toggle-icon');
        if (!card || !form) return;

        card.dataset.open = isOpen ? 'true' : 'false';
        toggle?.setAttribute('aria-label', isOpen ? 'Tutup filter' : 'Buka filter');
        icon?.classList.toggle('rotate-180', isOpen);
        card.classList.toggle('py-6', isOpen);
        card.classList.toggle('py-5', !isOpen);
        form.classList.toggle('max-h-[520px]', isOpen);
        form.classList.toggle('opacity-100', isOpen);
        form.classList.toggle('mt-5', isOpen);
        form.classList.toggle('max-h-0', !isOpen);
        form.classList.toggle('opacity-0', !isOpen);
        form.classList.toggle('mt-0', !isOpen);
    }

    document.addEventListener('click', (event) => {
        const card = document.getElementById('destination-search-card');
        const form = document.getElementById('destination-search-form');
        const toggle = event.target.closest('#destination-search-toggle');

        if (!card || !form || window.innerWidth >= 768) return;

        if (toggle) {
            event.stopPropagation();
            setDestinationSearchOpen(card.dataset.open !== 'true');
            return;
        }

        if (event.target.closest('#destination-search-form')) {
            event.stopPropagation();
            return;
        }

        if (event.target.closest('#destination-search-card') && card.dataset.open !== 'true') {
            setDestinationSearchOpen(true);
        }
    });

    window.addEventListener('resize', () => {
        const card = document.getElementById('destination-search-card');
        const form = document.getElementById('destination-search-form');
        const icon = document.getElementById('destination-search-toggle-icon');
        if (!card || !form) return;

        if (window.innerWidth >= 768) {
            card.dataset.open = 'false';
            icon?.classList.remove('rotate-180');
            card.classList.remove('py-5');
            card.classList.add('py-6');
            form.classList.remove('max-h-0', 'opacity-0', 'mt-0');
            form.classList.add('max-h-[520px]', 'opacity-100', 'mt-5');
        } else if (card.dataset.open !== 'true') {
            setDestinationSearchOpen(false);
        }
    });

    function toggleDestinationDropdown(name, event) {
        event.stopPropagation();

        const list = document.getElementById('dest-list-' + name);
        const arrow = document.getElementById('dest-arrow-' + name);
        if (!list || !arrow) return;

        document.querySelectorAll('[id^="dest-list-"]').forEach(el => {
            if (el.id !== 'dest-list-' + name) el.classList.add('hidden');
        });
        document.querySelectorAll('[id^="dest-arrow-"]').forEach(el => {
            if (el.id !== 'dest-arrow-' + name) el.classList.remove('rotate-180');
        });

        list.classList.toggle('hidden');
        arrow.classList.toggle('rotate-180');
    }

    function selectDestinationOption(name, value, label) {
        const selected = document.getElementById('dest-selected-' + name);
        const input = document.getElementById('dest-input-' + name);
        const list = document.getElementById('dest-list-' + name);
        const arrow = document.getElementById('dest-arrow-' + name);

        if (selected) selected.innerText = label;
        if (input) input.value = value;
        if (list) list.classList.add('hidden');
        if (arrow) arrow.classList.remove('rotate-180');
    }

    window.addEventListener('click', () => {
        document.querySelectorAll('[id^="dest-list-"]').forEach(el => el.classList.add('hidden'));
        document.querySelectorAll('[id^="dest-arrow-"]').forEach(el => el.classList.remove('rotate-180'));
    });
</script>
@endpush
