<?php

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public LoginForm $form;

    /**
     * Handle an incoming authentication request.
     */
    public function login(): void
    {
        $this->validate();

        $this->form->authenticate();

        Session::regenerate();

        $defaultRoute = auth()->user()->role === 'admin'
            ? route('admin.dashboard', absolute: false)
            : route('dashboard', absolute: false);

        $this->redirectIntended(default: $defaultRoute, navigate: true);
    }
}; ?>

<div class="rounded-[18px] bg-white px-5 py-7 shadow-[0_22px_60px_rgba(15,23,42,0.10)] sm:px-7">
    <div class="text-center">
        <a href="/" wire:navigate class="mx-auto mb-5 flex h-12 w-12 items-center justify-center rounded-full bg-primary-500 shadow-lg shadow-primary-500/25">
            <img src="{{ asset('images/logo_travel.png') }}?v={{ file_exists(public_path('images/logo_travel.png')) ? filemtime(public_path('images/logo_travel.png')) : time() }}" alt="Travelin" class="h-8 w-8 object-contain">
        </a>
        <h1 class="text-[22px] font-black leading-tight text-dark-900">Login akun</h1>
        <p class="mt-2 text-xs font-medium text-dark-500">
            Belum punya akun?
            <a href="{{ route('register') }}" wire:navigate class="font-bold text-primary-500 hover:text-primary-600">Register</a>
        </p>
    </div>

    <x-auth-session-status class="mt-5 rounded-xl bg-primary-50 px-4 py-3 text-sm font-semibold text-primary-600" :status="session('status')" />

    <form wire:submit="login" class="mt-7 space-y-4">
        <div>
            <label for="email" class="sr-only">Email address</label>
            <input
                wire:model="form.email"
                id="email"
                type="email"
                name="email"
                required
                autofocus
                autocomplete="username"
                placeholder="Email address"
                class="h-14 w-full rounded-[6px] border-0 bg-gray-100 px-4 text-sm font-semibold text-dark-900 placeholder:text-dark-300 shadow-none transition focus:bg-white focus:ring-2 focus:ring-primary-500/30"
            >
            <x-input-error :messages="$errors->get('form.email')" class="mt-2 text-xs" />
        </div>

        <div x-data="{ showPassword: false }">
            <label for="password" class="sr-only">Password</label>
            <div class="relative">
                <input
                    wire:model="form.password"
                    id="password"
                    x-bind:type="showPassword ? 'text' : 'password'"
                    name="password"
                    required
                    autocomplete="current-password"
                    placeholder="Password"
                    class="h-14 w-full rounded-[6px] border-0 bg-gray-100 px-4 pr-12 text-sm font-semibold text-dark-900 placeholder:text-dark-300 shadow-none transition focus:bg-white focus:ring-2 focus:ring-primary-500/30"
                >
                <button
                    type="button"
                    x-on:click="showPassword = ! showPassword"
                    class="absolute right-3 top-1/2 flex h-8 w-8 -translate-y-1/2 items-center justify-center rounded-full text-dark-400 transition hover:bg-white hover:text-dark-900"
                    aria-label="Tampilkan password"
                >
                    <svg x-show="!showPassword" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7S2 12 2 12Z"/>
                        <circle cx="12" cy="12" r="3"/>
                    </svg>
                    <svg x-cloak x-show="showPassword" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="m3 3 18 18"/>
                        <path d="M10.6 10.6A2 2 0 0 0 12 14a2 2 0 0 0 1.4-.6"/>
                        <path d="M9.9 4.2A10.4 10.4 0 0 1 12 4c7 0 10 8 10 8a13.6 13.6 0 0 1-3.1 4.6"/>
                        <path d="M6.1 6.1C3.4 7.9 2 12 2 12s3 8 10 8a9.7 9.7 0 0 0 4-.8"/>
                    </svg>
                </button>
            </div>
            <x-input-error :messages="$errors->get('form.password')" class="mt-2 text-xs" />
        </div>

        <div class="flex justify-end">
            @if (Route::has('password.request'))
                <a class="text-xs font-semibold text-dark-400 transition hover:text-primary-500" href="{{ route('password.request') }}" wire:navigate>
                    Recovery Password
                </a>
            @endif
        </div>

        <button type="submit" class="flex h-14 w-full items-center justify-center rounded-[7px] bg-primary-500 px-5 text-sm font-black text-white shadow-lg shadow-primary-500/25 transition hover:bg-primary-600 focus:outline-none focus:ring-4 focus:ring-primary-500/20">
            Login
        </button>
    </form>
</div>
