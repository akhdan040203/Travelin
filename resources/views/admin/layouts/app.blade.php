<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Panel') - Travelin Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="font-sans antialiased bg-gray-50">
    <div class="min-h-screen flex">
        {{-- Sidebar --}}
        <aside id="sidebar" class="fixed inset-y-0 left-0 z-50 w-64 bg-dark-900 text-white transform transition-transform duration-300 lg:translate-x-0 -translate-x-full">
            <div class="flex items-center gap-3 px-6 h-16 border-b border-white/10">
                <div class="w-8 h-8 rounded-full flex items-center justify-center overflow-hidden">
                    <img src="{{ asset('images/logoo3.png') }}" alt="Travelin Logo" class="w-full h-full object-contain rounded-full">
                </div>
                <span class="text-lg font-bold">Travel<span class="text-primary-500">in</span> <span class="text-xs font-normal text-dark-400">Admin</span></span>
            </div>

            <nav class="px-4 py-6 space-y-1 overflow-y-auto" style="max-height: calc(100vh - 8rem)">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('admin.dashboard') ? 'bg-primary-500 text-white' : 'text-dark-300 hover:bg-white/5 hover:text-white' }}">
                    Dashboard
                </a>

                <p class="px-3 pt-4 pb-2 text-[10px] font-semibold text-dark-500 uppercase tracking-wider">Kelola</p>

                <a href="{{ route('admin.destinations.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('admin.destinations.*') ? 'bg-primary-500 text-white' : 'text-dark-300 hover:bg-white/5 hover:text-white' }}">
                    Destinasi
                </a>
                <a href="{{ route('admin.schedules.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('admin.schedules.*') ? 'bg-primary-500 text-white' : 'text-dark-300 hover:bg-white/5 hover:text-white' }}">
                    Jadwal
                </a>
                <a href="{{ route('admin.bookings.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('admin.bookings.*') ? 'bg-primary-500 text-white' : 'text-dark-300 hover:bg-white/5 hover:text-white' }}">
                    Booking
                </a>

                <p class="px-3 pt-4 pb-2 text-[10px] font-semibold text-dark-500 uppercase tracking-wider">Lainnya</p>

                <a href="{{ route('home') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-dark-300 hover:bg-white/5 hover:text-white transition-all">
                    Lihat Website
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-dark-300 hover:bg-red-500/10 hover:text-red-400 transition-all">
                        Logout
                    </button>
                </form>
            </nav>
        </aside>

        {{-- Main Content --}}
        <div class="flex-1 lg:ml-64">
            {{-- Top Bar --}}
            <header class="sticky top-0 z-40 bg-white/80 backdrop-blur-xl border-b border-gray-100 h-16 flex items-center px-6">
                <button id="sidebar-toggle" class="lg:hidden px-3 py-2 rounded-lg hover:bg-gray-100 mr-4 text-xs font-bold text-dark-900">
                    MENU
                </button>
                <div class="flex-1">
                    <h1 class="text-lg font-bold text-dark-900">@yield('page_title', 'Dashboard')</h1>
                </div>
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full bg-gradient-to-br from-primary-400 to-primary-600 flex items-center justify-center text-white text-sm font-bold">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                    <span class="text-sm font-medium text-dark-900 hidden sm:block">{{ auth()->user()->name }}</span>
                </div>
            </header>

            {{-- Page Content --}}
            <main class="p-6">
                {{-- Flash Messages --}}
                @if(session('success'))
                    <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl p-4 flex items-center gap-3">
                        {{ session('success') }}
                    </div>
                @endif
                @if(session('error'))
                    <div class="mb-6 bg-red-50 border border-red-200 text-red-800 rounded-xl p-4 flex items-center gap-3">
                        {{ session('error') }}
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    {{-- Sidebar overlay --}}
    <div id="sidebar-overlay" class="fixed inset-0 bg-black/50 z-40 hidden lg:hidden" onclick="toggleSidebar()"></div>

    @livewireScripts
    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebar-overlay');
            sidebar.classList.toggle('-translate-x-full');
            overlay.classList.toggle('hidden');
        }
        document.getElementById('sidebar-toggle')?.addEventListener('click', toggleSidebar);
    </script>
    @stack('scripts')
</body>
</html>
