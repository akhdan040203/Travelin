<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Akun Saya - Travelin')</title>

    <!-- Favicon -->
    <link rel="icon" href="{{ asset('images/travelin-mark-transparent.png') }}" type="image/png">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    @stack('styles')
</head>
<body class="font-sans antialiased bg-gray-50/50 text-dark-900">
    
    {{-- Navbar Homepage --}}
    @include('components.public.navbar', ['transparent' => false])

    <div class="pt-28 pb-20">
        {{-- Container yang lebih ramping agar card tidak stretching --}}
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- Main Content Slot --}}
            <main>
                {{-- Flash Messages --}}
                @if(session('success'))
                    <div class="mb-8 bg-emerald-50 border border-emerald-100 text-emerald-800 rounded-2xl p-4 flex items-center gap-3 animate-fade-up">
                        <div class="w-8 h-8 rounded-full bg-emerald-500 flex items-center justify-center text-white flex-shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                        </div>
                        <p class="text-sm font-bold">{{ session('success') }}</p>
                    </div>
                @endif

                @yield('content')
            </main>

        </div>
    </div>

    {{-- Footer --}}
    @include('components.public.footer')

    @livewireScripts
    @stack('scripts')
</body>
</html>
