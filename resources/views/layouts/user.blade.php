<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="@yield('meta_description', 'Travelin - Area Customer')">

    <title>@yield('title', 'Area Customer - Travelin')</title>

    <!-- Favicon -->
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="font-sans antialiased bg-gray-50">
    @php
        $wishlistCount = auth()->user()->wishlists()->count();
    @endphp
    <div class="min-h-screen flex">
        {{-- Sidebar --}}
        <aside id="user-sidebar" class="fixed inset-y-0 left-0 z-50 w-64 bg-white border-r border-gray-100 transform transition-transform duration-300 lg:translate-x-0 -translate-x-full">
            {{-- Logo --}}
            <div class="flex items-center gap-3 px-6 h-16 border-b border-gray-100">
                <a href="{{ route('home') }}" class="flex items-center gap-2">
                    <div class="w-9 h-9 rounded-full flex items-center justify-center overflow-hidden">
                        <img src="{{ asset('images/travelin-mark-transparent.png') }}?v={{ filemtime(public_path('images/travelin-mark-transparent.png')) }}" alt="Travelin Logo" class="w-full h-full object-contain rounded-full">
                    </div>
                    <span class="text-lg font-bold text-dark-900">Travel<span class="text-primary-500">in</span></span>
                </a>
            </div>

            {{-- User Info --}}
            <div class="px-6 py-5 border-b border-gray-100">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-primary-400 to-primary-600 flex items-center justify-center overflow-hidden text-white text-sm font-bold shadow-md shadow-primary-500/20">
                        @if(auth()->user()->avatar_url)
                            <img src="{{ auth()->user()->avatar_url }}" alt="{{ auth()->user()->name }}" class="h-full w-full object-cover">
                        @else
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        @endif
                    </div>
                    <div class="min-w-0">
                        <p class="text-sm font-semibold text-dark-900 truncate">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-dark-400 truncate">{{ auth()->user()->email }}</p>
                    </div>
                </div>
            </div>

            {{-- Navigation --}}
            <nav class="px-4 py-5 space-y-1 overflow-y-auto" style="max-height: calc(100vh - 12rem)">
                <p class="px-3 pb-2 text-[10px] font-semibold text-dark-300 uppercase tracking-wider">Menu Utama</p>

                <a href="{{ route('user.bookings') }}" 
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('user.bookings') || request()->routeIs('user.bookings.show') ? 'bg-primary-50 text-primary-600' : 'text-dark-500 hover:bg-gray-50 hover:text-dark-900' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                    History Pembelian
                </a>

                <a href="{{ route('user.wishlist') }}" 
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('user.wishlist') ? 'bg-primary-50 text-primary-600' : 'text-dark-500 hover:bg-gray-50 hover:text-dark-900' }}">
                    <span class="relative">
                        <svg class="w-5 h-5 {{ $wishlistCount > 0 ? 'text-red-500' : '' }}" fill="{{ $wishlistCount > 0 ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                        @if($wishlistCount > 0)
                            <span class="absolute -right-2 -top-2 min-w-4 h-4 rounded-full bg-red-500 px-1 text-[9px] leading-4 text-white text-center font-bold">{{ $wishlistCount > 9 ? '9+' : $wishlistCount }}</span>
                        @endif
                    </span>
                    <span class="flex-1">Wishlist</span>
                </a>

                <p class="px-3 pt-5 pb-2 text-[10px] font-semibold text-dark-300 uppercase tracking-wider">Akun</p>

                <a href="{{ route('profile') }}" 
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('profile') ? 'bg-primary-50 text-primary-600' : 'text-dark-500 hover:bg-gray-50 hover:text-dark-900' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    Edit Profil
                </a>

                <p class="px-3 pt-5 pb-2 text-[10px] font-semibold text-dark-300 uppercase tracking-wider">Lainnya</p>

                @if(auth()->user()->role === 'admin')
                <a href="{{ route('admin.dashboard') }}" 
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-dark-500 hover:bg-gray-50 hover:text-dark-900 transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    Admin Panel
                </a>
                @endif

                <a href="{{ route('destinations.index') }}" 
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-dark-500 hover:bg-gray-50 hover:text-dark-900 transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Jelajahi Destinasi
                </a>

                <a href="{{ route('home') }}" 
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-dark-500 hover:bg-gray-50 hover:text-dark-900 transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                    Kembali ke Website
                </a>

            </nav>
        </aside>

        {{-- Main Content --}}
        <div class="flex-1 lg:ml-64">
            {{-- Top Bar --}}
            <header class="sticky top-0 z-40 bg-white/80 backdrop-blur-xl border-b border-gray-100 h-16 flex items-center px-6">
                <button id="user-sidebar-toggle" class="lg:hidden px-3 py-2 rounded-lg hover:bg-gray-100 mr-4 text-dark-900" onclick="toggleUserSidebar()">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>
                <div class="flex-1">
                    <h1 class="text-lg font-bold text-dark-900">@yield('page_title', 'Area Customer')</h1>
                </div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('destinations.index') }}" class="hidden sm:inline-flex items-center gap-2 px-4 py-2 bg-primary-50 text-primary-600 rounded-xl text-sm font-medium hover:bg-primary-100 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                        Booking Baru
                    </a>
                    <details class="relative group">
                        <summary class="list-none cursor-pointer flex items-center gap-2 rounded-xl px-2 py-1.5 hover:bg-gray-100 transition-colors">
                            <span class="w-9 h-9 rounded-full bg-gradient-to-br from-primary-400 to-primary-600 flex items-center justify-center overflow-hidden text-white text-sm font-bold shadow-md shadow-primary-500/20">
                                @if(auth()->user()->avatar_url)
                                    <img src="{{ auth()->user()->avatar_url }}" alt="{{ auth()->user()->name }}" class="h-full w-full object-cover">
                                @else
                                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                @endif
                            </span>
                            <svg class="w-4 h-4 text-dark-400 transition-transform group-open:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </summary>
                        <div class="absolute right-0 mt-3 w-60 overflow-hidden rounded-2xl bg-white shadow-2xl shadow-black/10 ring-1 ring-black/5">
                            <div class="px-4 py-3 border-b border-gray-100">
                                <p class="text-sm font-bold text-dark-900 truncate">{{ auth()->user()->name }}</p>
                                <p class="text-xs text-dark-400 truncate">{{ auth()->user()->email }}</p>
                            </div>
                            <div class="p-2">
                                <a href="{{ route('user.wishlist') }}" class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium text-dark-600 hover:bg-primary-50 hover:text-primary-600 transition-colors">
                                    <span class="relative">
                                        <svg class="w-4 h-4 {{ $wishlistCount > 0 ? 'text-red-500' : '' }}" fill="{{ $wishlistCount > 0 ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                                        @if($wishlistCount > 0)
                                            <span class="absolute -right-2 -top-2 min-w-4 h-4 rounded-full bg-red-500 px-1 text-[9px] leading-4 text-white text-center font-bold">{{ $wishlistCount > 9 ? '9+' : $wishlistCount }}</span>
                                        @endif
                                    </span>
                                    <span class="flex-1">Wishlist</span>
                                </a>
                                <a href="{{ route('profile') }}" class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium text-dark-600 hover:bg-primary-50 hover:text-primary-600 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                    Profile Setting
                                </a>
                                <a href="{{ route('user.bookings') }}" class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium text-dark-600 hover:bg-primary-50 hover:text-primary-600 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                                    History Pembelian
                                </a>
                            </div>
                        </div>
                    </details>
                </div>
            </header>

            {{-- Page Content --}}
            <main class="p-6">
                {{-- Flash Messages --}}
                @if(session('success'))
                    <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl p-4 flex items-center gap-3">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        {{ session('success') }}
                    </div>
                @endif
                @if(session('error'))
                    <div class="mb-6 bg-red-50 border border-red-200 text-red-800 rounded-xl p-4 flex items-center gap-3">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        {{ session('error') }}
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    {{-- Sidebar overlay --}}
    <div id="user-sidebar-overlay" class="fixed inset-0 bg-black/50 z-40 hidden lg:hidden" onclick="toggleUserSidebar()"></div>

    @livewireScripts
    <script>
        function toggleUserSidebar() {
            const sidebar = document.getElementById('user-sidebar');
            const overlay = document.getElementById('user-sidebar-overlay');
            sidebar.classList.toggle('-translate-x-full');
            overlay.classList.toggle('hidden');
        }
    </script>
    @stack('scripts')
</body>
</html>
