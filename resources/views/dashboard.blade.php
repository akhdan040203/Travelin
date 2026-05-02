<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <script>window.location.href = "{{ route('user.dashboard') }}";</script>
                <p class="text-gray-900">Redirecting to dashboard...</p>
                <a href="{{ route('user.dashboard') }}" class="text-blue-500 underline">Click here if not redirected</a>
            </div>
        </div>
    </div>
</x-app-layout>
