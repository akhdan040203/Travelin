<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Favicon -->
        <link rel="icon" href="{{ asset('images/travelin-mark-transparent.png') }}?v={{ filemtime(public_path('images/travelin-mark-transparent.png')) }}" type="image/png">
        <link rel="apple-touch-icon" href="{{ asset('images/travelin-mark-transparent.png') }}?v={{ filemtime(public_path('images/travelin-mark-transparent.png')) }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="min-h-screen flex items-center justify-center bg-white px-4 py-8">
            <div class="w-full max-w-[360px]">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
