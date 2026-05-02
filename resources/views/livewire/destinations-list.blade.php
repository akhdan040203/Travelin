<div>
    {{-- Search & Filter Bar (matching homepage style) --}}
    <div class="mb-10 max-w-5xl mx-auto">
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
        <div class="bg-white rounded-xl shadow-2xl px-3 py-3 sm:px-4 lg:px-6 lg:py-4 border border-gray-100/50">
            <form action="{{ route('destinations.index') }}" method="GET">
                <div class="flex items-end gap-1.5 sm:gap-2 md:gap-4">
                    {{-- Column 1: Location --}}
                    <div class="min-w-0 flex-[0.78] md:flex-1 space-y-1.5">
                        <label class="block h-3 text-[8px] md:text-[10px] font-black text-dark-900 uppercase tracking-widest pl-1 leading-3">Location</label>
                        <div class="relative group">
                            <div class="absolute left-2.5 md:left-3 top-1/2 -translate-y-1/2 text-dark-300 group-focus-within:text-primary-500 transition-colors">
                                <svg class="w-3 h-3 md:w-4 md:h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg>
                            </div>
                            <input type="text" name="search" value="{{ $search }}" placeholder="Bali"
                                   class="h-10 md:h-auto w-full pl-7 md:pl-10 pr-2 md:pr-3 py-0 md:py-2.5 bg-gray-50 border-0 rounded-lg text-[10px] md:text-xs font-semibold text-dark-900 placeholder:text-dark-200 focus:ring-2 focus:ring-primary-500/15 transition-all">
                        </div>
                    </div>

                    <div class="hidden md:block w-px h-8 bg-gray-200"></div>

                    {{-- Column 2: Category --}}
                    <div class="min-w-0 flex-1 space-y-1.5">
                        <label class="block h-3 text-[8px] md:text-[10px] font-black text-dark-900 uppercase tracking-widest pl-1 leading-3">Category</label>
                        <div class="relative group">
                            <div class="absolute left-2.5 md:left-3 top-1/2 -translate-y-1/2 text-dark-300 pointer-events-none">
                                <svg class="w-3 h-3 md:w-4 md:h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                            </div>
                            <button type="button" onclick="toggleDestinationDropdown('category', event)"
                                    class="h-10 md:h-auto w-full pl-7 md:pl-10 pr-2 md:pr-10 py-0 md:py-2.5 bg-gray-50 border-0 rounded-lg text-[10px] md:text-xs font-semibold text-dark-900 text-left flex items-center justify-between gap-1 focus:ring-2 focus:ring-primary-500/15 transition-all">
                                <span id="dest-selected-category" class="truncate">{{ $selectedCategory->name ?? 'Any' }}</span>
                                <svg class="w-2.5 h-2.5 md:w-3 md:h-3 text-dark-300 transition-transform duration-300 flex-shrink-0" id="dest-arrow-category" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 9l-7 7-7-7"/></svg>
                            </button>
                            <div id="dest-list-category" class="hidden absolute top-full left-0 right-0 mt-2 bg-white rounded-xl shadow-2xl border border-gray-100 overflow-hidden z-[100] py-1 max-h-60 overflow-y-auto">
                                <div class="px-4 py-2 text-[10px] font-bold text-dark-200 uppercase tracking-widest bg-gray-50/50">Select Category</div>
                                <div onclick="selectDestinationOption('category', '', 'Any')" class="px-4 py-2.5 text-xs font-semibold text-dark-600 hover:bg-primary-50 hover:text-primary-600 cursor-pointer transition-colors">Any</div>
                                @foreach($categories as $cat)
                                    <div onclick="selectDestinationOption('category', '{{ $cat->slug }}', '{{ $cat->name }}')" class="px-4 py-2.5 text-xs font-semibold text-dark-600 hover:bg-primary-50 hover:text-primary-600 cursor-pointer transition-colors">{{ $cat->name }}</div>
                                @endforeach
                            </div>
                            <input id="dest-input-category" type="hidden" name="category" value="{{ $category }}">
                        </div>
                    </div>

                    <div class="hidden md:block w-px h-8 bg-gray-200"></div>

                    {{-- Column 3: Price --}}
                    <div class="min-w-0 flex-1 space-y-1.5">
                        <label class="block h-3 text-[8px] md:text-[10px] font-black text-dark-900 uppercase tracking-widest pl-1 leading-3">Price</label>
                        <div class="relative group">
                            <div class="absolute left-2.5 md:left-3 top-1/2 -translate-y-1/2 text-dark-300 pointer-events-none">
                                <svg class="w-3 h-3 md:w-4 md:h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.407 2.67 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.407-2.67-1"/></svg>
                            </div>
                            <button type="button" onclick="toggleDestinationDropdown('price', event)"
                                    class="h-10 md:h-auto w-full pl-7 md:pl-10 pr-2 md:pr-10 py-0 md:py-2.5 bg-gray-50 border-0 rounded-lg text-[10px] md:text-xs font-semibold text-dark-900 text-left flex items-center justify-between gap-1 focus:ring-2 focus:ring-primary-500/15 transition-all">
                                <span id="dest-selected-price" class="truncate">{{ $selectedPriceLabel }}</span>
                                <svg class="w-2.5 h-2.5 md:w-3 md:h-3 text-dark-300 transition-transform duration-300 flex-shrink-0" id="dest-arrow-price" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 9l-7 7-7-7"/></svg>
                            </button>
                            <div id="dest-list-price" class="hidden absolute top-full left-0 right-0 mt-2 bg-white rounded-xl shadow-2xl border border-gray-100 overflow-hidden z-[100] py-1">
                                <div class="px-4 py-2 text-[10px] font-bold text-dark-200 uppercase tracking-widest bg-gray-50/50">Select Price Range</div>
                                <div onclick="selectDestinationOption('price', '', 'Any')" class="px-4 py-2.5 text-xs font-semibold text-dark-600 hover:bg-primary-50 hover:text-primary-600 cursor-pointer transition-colors">Any</div>
                                <div onclick="selectDestinationOption('price', '0-1000000', 'Under 1jt')" class="px-4 py-2.5 text-xs font-semibold text-dark-600 hover:bg-primary-50 hover:text-primary-600 cursor-pointer transition-colors">Under 1jt</div>
                                <div onclick="selectDestinationOption('price', '1000000-5000000', '1-5jt')" class="px-4 py-2.5 text-xs font-semibold text-dark-600 hover:bg-primary-50 hover:text-primary-600 cursor-pointer transition-colors">1-5jt</div>
                                <div onclick="selectDestinationOption('price', '10000000+', '10jt+')" class="px-4 py-2.5 text-xs font-semibold text-dark-600 hover:bg-primary-50 hover:text-primary-600 cursor-pointer transition-colors">10jt+</div>
                            </div>
                            <input id="dest-input-price" type="hidden" name="price_range" value="{{ $priceRange }}">
                        </div>
                    </div>

                    <button type="submit" aria-label="Search" class="flex-shrink-0 w-11 md:w-auto h-10 md:h-auto bg-dark-900 text-white px-0 md:px-4 py-0 md:py-2.5 rounded-lg font-bold text-[10px] md:text-xs uppercase tracking-wider flex items-center justify-center gap-2 hover:bg-primary-500 transition-all shadow-lg shadow-dark-900/10 hover:shadow-primary-500/30 group active:scale-95">
                        <span class="hidden md:inline">Search</span>
                        <svg class="w-4 h-4 md:w-3.5 md:h-3.5 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" stroke-width="2.8" viewBox="0 0 24 24">
                            <circle cx="11" cy="11" r="7"/>
                            <path stroke-linecap="round" d="M20 20l-4.2-4.2"/>
                        </svg>
                    </button>
                </div>
                <input type="hidden" name="sort" value="{{ $sort }}">
            </form>
        </div>
    </div>

    @if($destinations->count() > 0)
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-dark-900 font-bold text-sm">Semua Destinasi</h2>
            <p class="text-dark-400 text-xs">Menampilkan {{ $destinations->count() }} dari {{ $destinations->total() }} hasil</p>
        </div>

        <div class="grid grid-cols-2 lg:grid-cols-3 gap-3 sm:gap-6">
            @foreach($destinations as $dest)
                @php
                    $isWishlisted = auth()->check() ? auth()->user()->hasWishlisted($dest->id) : false;
                @endphp
                <div class="group bg-white rounded-xl sm:rounded-2xl shadow-lg shadow-black/5 overflow-hidden transition-all duration-500 hover:-translate-y-1.5 hover:shadow-xl border border-gray-100/80 flex flex-col h-full">
                    {{-- Card Image --}}
                    <div class="relative h-32 sm:h-56 overflow-hidden">
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
                        
                        {{-- Gradient overlay --}}
                        <div class="absolute inset-0 bg-gradient-to-t from-black/50 via-transparent to-transparent"></div>

                        <div class="absolute top-2 left-2 sm:top-3 sm:left-3 z-20">
                            @auth
                                @if(auth()->user()->role !== 'admin')
                                    <form method="POST" action="{{ route('user.wishlist.toggle', $dest) }}" class="js-wishlist-form" data-destination-id="{{ $dest->id }}">
                                        @csrf
                                        <button type="submit" aria-label="Toggle wishlist" class="js-wishlist-button w-8 h-8 sm:w-9 sm:h-9 rounded-full bg-white/95 text-red-500 shadow-lg flex items-center justify-center hover:bg-red-50 transition-colors">
                                            <svg class="js-wishlist-icon w-4 h-4" fill="{{ $isWishlisted ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.9" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                            </svg>
                                        </button>
                                    </form>
                                @endif
                            @else
                                <a href="{{ route('login') }}" aria-label="Login untuk wishlist" class="w-8 h-8 sm:w-9 sm:h-9 rounded-full bg-white/95 text-red-500 shadow-lg flex items-center justify-center hover:bg-red-50 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.9" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                    </svg>
                                </a>
                            @endauth
                        </div>

                        {{-- Bottom overlay info --}}
                        <div class="absolute bottom-2 left-2 right-2 sm:bottom-3 sm:left-3 sm:right-3 flex items-center">
                            <div class="min-w-0 flex items-center gap-1 sm:gap-1.5 px-2 sm:px-2.5 py-1 bg-black/40 backdrop-blur-sm rounded-md sm:rounded-lg border border-white/10">
                                <svg class="w-2.5 h-2.5 sm:w-3 sm:h-3 text-white/60 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                <span class="truncate text-[8px] sm:text-[10px] font-semibold text-white/90">{{ $dest->location }}</span>
                            </div>
                        </div>
                    </div>

                    {{-- Card Content --}}
                    <div class="p-3 sm:p-5 flex flex-col flex-1">
                        <a href="{{ route('destinations.show', $dest->slug) }}" class="text-xs sm:text-lg font-bold text-dark-900 mb-1 sm:mb-1.5 leading-tight group-hover:text-primary-500 transition-colors line-clamp-2">{{ $dest->name }}</a>
                        <p class="hidden sm:block text-dark-400 text-xs leading-relaxed line-clamp-2 mb-5">{{ $dest->short_description }}</p>

                        {{-- Pricing and CTA --}}
                        <div class="flex items-end justify-between gap-2 mt-auto pt-3 sm:pt-4 border-t border-gray-100">
                            <div class="min-w-0">
                                <span class="text-[9px] text-dark-300 font-bold uppercase tracking-widest block mb-0.5">Mulai dari</span>
                                <span class="block truncate text-[11px] sm:text-lg font-black text-primary-500 tracking-tight">{{ $dest->formatted_price }}</span>
                            </div>
                            <a href="{{ route('destinations.show', $dest->slug) }}" class="px-2 sm:px-4 py-1.5 sm:py-2 bg-dark-900 text-white text-[8px] sm:text-[10px] font-bold rounded-md sm:rounded-lg group-hover:bg-primary-500 transition-all uppercase tracking-wider">
                                Detail →
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
