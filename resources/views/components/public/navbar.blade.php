{{-- Navbar --}}
@php
    $wishlistCount = auth()->check() && auth()->user()->role !== 'admin'
        ? auth()->user()->wishlists()->count()
        : 0;
    $useTransparentNavbar = ($transparent ?? false) && ! auth()->check();
@endphp
<nav id="navbar" data-transparent="{{ $useTransparentNavbar ? 'true' : 'false' }}"
     class="fixed top-0 left-0 right-0 bg-transparent transition-all duration-300"
     style="z-index: 99990;">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div id="navbar-shell" class="mt-3 flex h-14 items-center justify-between rounded-full border px-4 shadow-lg shadow-black/5 backdrop-blur-xl transition-all duration-300 {{ $useTransparentNavbar ? 'border-white/20 bg-white/10' : 'border-gray-100 bg-white/80' }}">
            {{-- Logo --}}
            <a href="{{ route('home') }}" class="flex items-center gap-2 group">
                <div class="w-7 h-7 rounded-full flex items-center justify-center overflow-hidden transition-transform group-hover:scale-110">
                    <img src="{{ asset('images/travelin-mark-transparent.png') }}?v={{ filemtime(public_path('images/travelin-mark-transparent.png')) }}" alt="Travelin Logo" 
                         class="w-full h-full object-contain rounded-full">
                </div>
                <span id="logo-text" class="text-base font-bold transition-colors {{ $useTransparentNavbar ? 'text-white' : 'text-dark-900' }}">
                    Travel<span class="text-primary-500">in</span>
                </span>
            </a>

            {{-- Desktop Navigation --}}
            <div class="hidden md:flex items-center justify-center gap-1">
                @php
                    $navItems = [
                        ['route' => 'home', 'label' => 'Home'],
                        ['route' => 'destinations.index', 'label' => 'Destinasi'],
                        ['route' => 'schedules', 'label' => 'Jadwal'],
                        ['route' => 'faq', 'label' => 'FAQ'],
                        ['route' => 'contact', 'label' => 'Kontak'],
                    ];
                @endphp

                @foreach($navItems as $item)
                    <a href="{{ route($item['route']) }}"
                       class="nav-link-hero px-4 py-2 rounded-full text-xs font-medium transition-all duration-300 hover:bg-white/15 hover:text-primary-500
                              {{ $useTransparentNavbar ? 'text-white' : 'text-dark-900' }}
                              {{ request()->routeIs($item['route']) ? '!text-primary-500 bg-primary-50' : '' }}">
                        {{ $item['label'] }}
                    </a>
                @endforeach
            </div>

            {{-- Auth Buttons --}}
            <div class="hidden md:flex items-center justify-end gap-2 min-w-[180px]">
                @auth
                    @if(auth()->user()->role === 'admin')
                        <a href="{{ route('admin.dashboard') }}"
                           class="nav-link-hero px-4 py-2 rounded-full text-xs font-medium transition-all {{ $useTransparentNavbar ? 'text-white' : 'text-dark-900' }} hover:text-primary-500">
                            Admin
                        </a>
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="rounded-full bg-white px-5 py-2 text-xs font-bold text-dark-900 shadow-sm transition hover:bg-primary-500 hover:text-white">
                                Logout
                            </button>
                        </form>
                    @else
                        <details class="relative group" style="z-index: 99995;">
                            <summary class="list-none cursor-pointer flex items-center gap-2 rounded-full py-1.5 pl-1.5 pr-3 transition-all {{ $useTransparentNavbar ? 'text-white hover:bg-white/10' : 'text-dark-900 hover:bg-gray-100' }}">
                                <span class="w-8 h-8 rounded-full bg-gradient-to-br from-primary-400 to-primary-600 flex items-center justify-center overflow-hidden text-white text-xs font-bold shadow-md shadow-primary-500/20">
                                    @if(auth()->user()->avatar_url)
                                        <img src="{{ auth()->user()->avatar_url }}" alt="{{ auth()->user()->name }}" class="h-full w-full object-cover">
                                    @else
                                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                    @endif
                                </span>
                                <span class="text-xs font-semibold max-w-32 truncate">{{ auth()->user()->name }}</span>
                                <svg class="w-4 h-4 opacity-60 transition-transform group-open:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </summary>
                            <div class="absolute right-0 top-full mt-3 w-60 overflow-hidden rounded-2xl bg-white shadow-2xl shadow-black/10 ring-1 ring-black/5" style="z-index: 100000;">
                                <div class="px-4 py-3 border-b border-gray-100">
                                    <p class="text-sm font-bold text-dark-900 truncate">{{ auth()->user()->name }}</p>
                                    <p class="text-xs text-dark-400 truncate">{{ auth()->user()->email }}</p>
                                </div>
                                <div class="p-2">
                                    <a href="{{ route('user.wishlist') }}" class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium text-dark-600 hover:bg-primary-50 hover:text-primary-600 transition-colors">
                                        <span class="relative">
                                            <svg class="js-wishlist-nav-icon w-4 h-4 {{ $wishlistCount > 0 ? 'text-red-500' : '' }}" fill="{{ $wishlistCount > 0 ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                                            <span class="js-wishlist-badge absolute -right-2 -top-2 min-w-4 h-4 rounded-full bg-red-500 px-1 text-[9px] leading-4 text-white text-center font-bold {{ $wishlistCount > 0 ? '' : 'hidden' }}">{{ $wishlistCount > 9 ? '9+' : $wishlistCount }}</span>
                                        </span>
                                        <span class="flex-1">Wishlist</span>
                                    </a>
                                    <a href="{{ route('profile') }}" class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium text-dark-600 hover:bg-primary-50 hover:text-primary-600 transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                        Profile Setting
                                    </a>
                                    <a href="{{ route('user.bookings') }}" class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium text-dark-600 hover:bg-primary-50 hover:text-primary-600 transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                                        History
                                    </a>
                                    <div class="my-2 h-px bg-gray-100"></div>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="flex w-full items-center gap-3 rounded-xl px-3 py-2.5 text-left text-sm font-medium text-red-500 hover:bg-red-50 transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7" d="M15.75 9V5.75A2.75 2.75 0 0013 3H6.75A2.75 2.75 0 004 5.75v12.5A2.75 2.75 0 006.75 21H13a2.75 2.75 0 002.75-2.75V15M10 12h10m0 0-3-3m3 3-3 3"/></svg>
                                            Logout
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </details>
                    @endif
                @else
                    <a href="{{ route('login') }}"
                       class="nav-link-hero rounded-full border border-white/50 px-5 py-2 text-xs font-medium transition-all {{ $useTransparentNavbar ? 'text-white hover:bg-white/10' : 'text-dark-900 hover:bg-gray-100' }}">
                        Login
                    </a>
                    <a href="{{ route('register') }}" class="inline-flex items-center gap-2 rounded-full bg-white px-5 py-2 text-xs font-bold text-dark-900 shadow-sm transition hover:bg-primary-500 hover:text-white">
                        Sign Up
                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2.3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M7 17 17 7M9 7h8v8"/></svg>
                    </a>
                @endauth
            </div>

            {{-- Mobile Actions --}}
            <div class="md:hidden flex items-center gap-2">
                @auth
                    @if(auth()->user()->role !== 'admin')
                        <a href="{{ route('user.wishlist') }}"
                           aria-label="Wishlist"
                           class="js-wishlist-nav-target nav-link-hero relative w-10 h-10 rounded-full flex items-center justify-center transition-colors {{ $useTransparentNavbar ? 'text-white hover:bg-white/10' : 'text-dark-900 hover:bg-gray-100' }}">
                            <svg class="js-wishlist-nav-icon w-5 h-5 {{ $wishlistCount > 0 ? 'text-red-500' : '' }}" fill="{{ $wishlistCount > 0 ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                            </svg>
                            <span class="js-wishlist-badge absolute right-1 top-1 min-w-4 h-4 rounded-full bg-red-500 px-1 text-[9px] leading-4 text-white text-center font-bold {{ $wishlistCount > 0 ? '' : 'hidden' }}">{{ $wishlistCount > 9 ? '9+' : $wishlistCount }}</span>
                        </a>
                        <a href="{{ route('user.bookings') }}"
                           aria-label="History pembelian"
                           class="nav-link-hero relative w-10 h-10 rounded-full flex items-center justify-center transition-colors {{ $useTransparentNavbar ? 'text-white hover:bg-white/10' : 'text-dark-900 hover:bg-gray-100' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.9" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2M9 12h6M9 16h4"/>
                            </svg>
                        </a>
                    @endif
                @else
                    <a href="{{ route('login') }}"
                       aria-label="Wishlist"
                       class="nav-link-hero w-10 h-10 rounded-full flex items-center justify-center transition-colors {{ $useTransparentNavbar ? 'text-white hover:bg-white/10' : 'text-dark-900 hover:bg-gray-100' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                        </svg>
                    </a>
                @endauth

                <button id="mobile-menu-btn" aria-label="Buka menu"
                        class="nav-link-hero w-10 h-10 rounded-full flex items-center justify-center transition-all duration-300 {{ $useTransparentNavbar ? 'text-white hover:bg-white/10' : 'text-dark-900 hover:bg-gray-100' }}">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path id="hamburger-svg-path" stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
            </div>
        </div>

        {{-- Mobile Menu --}}
        <div id="mobile-menu" class="hidden fixed top-20 right-4 left-4 md:hidden max-h-[calc(100vh-6rem)] overflow-y-auto bg-white rounded-3xl shadow-2xl ring-1 ring-black/5" style="z-index: 100000;">
            <div class="p-4 space-y-2">
                @foreach($navItems as $item)
                    <a href="{{ route($item['route']) }}"
                       class="block px-5 py-4 rounded-2xl text-lg font-bold transition-all hover:bg-primary-50 hover:text-primary-500
                              {{ request()->routeIs($item['route']) ? 'text-primary-500 bg-primary-50' : 'text-dark-900' }}">
                        {{ $item['label'] }}
                    </a>
                @endforeach
                <hr class="my-2 border-gray-100">
                @auth
                    <div class="grid grid-cols-2 gap-3 p-2">
                        <a href="{{ route('profile') }}" class="flex items-center justify-center px-4 py-3.5 rounded-2xl text-sm font-bold text-dark-900 bg-gray-50 hover:bg-gray-100 transition-all">
                            Profile
                        </a>
                        <form method="POST" action="{{ route('logout') }}" class="w-full">
                            @csrf
                            <button type="submit" class="w-full flex items-center justify-center px-4 py-3.5 rounded-2xl text-sm font-bold text-red-500 bg-red-50 hover:bg-red-100 transition-all">
                                Logout
                            </button>
                        </form>
                        @if(auth()->user()->role !== 'admin')
                        @endif
                    </div>
                @else
                    <div class="flex flex-col gap-2 p-2 pt-0">
                        <a href="{{ route('login') }}" class="block w-full px-5 py-4 rounded-2xl text-center text-sm font-bold text-dark-900 bg-gray-50 hover:bg-gray-100 transition-all">
                            Login
                        </a>
                        <a href="{{ route('register') }}" class="block w-full px-5 py-4 rounded-2xl text-center text-sm font-bold text-white bg-primary-500 shadow-lg shadow-primary-500/20 active:scale-[0.98] transition-all">
                            Register
                        </a>
                    </div>
                @endauth
            </div>
        </div>
    </div>
</nav>
