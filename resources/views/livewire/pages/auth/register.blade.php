<?php

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';

    /**
     * Handle an incoming registration request.
     */
    public function register(): void
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
        ]);

        $validated['password'] = Hash::make($validated['password']);

        event(new Registered($user = User::create($validated)));

        Auth::login($user);

        $this->redirect(route('dashboard', absolute: false), navigate: true);
    }
}; ?>

<div class="rounded-[18px] bg-white px-5 py-7 shadow-[0_22px_60px_rgba(15,23,42,0.10)] sm:px-7">
    <div class="text-center">
        <a href="/" wire:navigate class="mx-auto mb-5 flex h-12 w-12 items-center justify-center rounded-full bg-primary-500 shadow-lg shadow-primary-500/25">
            <img src="{{ asset('images/travelin-mark-transparent.png') }}?v={{ filemtime(public_path('images/travelin-mark-transparent.png')) }}" alt="Travelin" class="h-8 w-8 object-contain">
        </a>
        <h1 class="text-[22px] font-black leading-tight text-dark-900">Create an account</h1>
        <p class="mt-2 text-xs font-medium text-dark-500">
            Already have an account?
            <a href="{{ route('login') }}" wire:navigate class="font-bold text-primary-500 hover:text-primary-600">Login</a>
        </p>
    </div>

    <form wire:submit="register" class="mt-7 space-y-4">
        <div>
            <label for="name" class="sr-only">Name</label>
            <input
                wire:model="name"
                id="name"
                type="text"
                name="name"
                required
                autofocus
                autocomplete="name"
                placeholder="Full name"
                class="h-14 w-full rounded-[6px] border-0 bg-gray-100 px-4 text-sm font-semibold text-dark-900 placeholder:text-dark-300 shadow-none transition focus:bg-white focus:ring-2 focus:ring-primary-500/30"
            >
            <x-input-error :messages="$errors->get('name')" class="mt-2 text-xs" />
        </div>

        <div>
            <label for="email" class="sr-only">Email address</label>
            <input
                wire:model="email"
                id="email"
                type="email"
                name="email"
                required
                autocomplete="username"
                placeholder="Email address"
                class="h-14 w-full rounded-[6px] border-0 bg-gray-100 px-4 text-sm font-semibold text-dark-900 placeholder:text-dark-300 shadow-none transition focus:bg-white focus:ring-2 focus:ring-primary-500/30"
            >
            <x-input-error :messages="$errors->get('email')" class="mt-2 text-xs" />
        </div>

        <div x-data="{ showPassword: false }">
            <label for="password" class="sr-only">Password</label>
            <div class="relative">
                <input
                    wire:model="password"
                    id="password"
                    x-bind:type="showPassword ? 'text' : 'password'"
                    name="password"
                    required
                    autocomplete="new-password"
                    placeholder="Password"
                    class="h-14 w-full rounded-[6px] border-0 bg-gray-100 px-4 pr-12 text-sm font-semibold text-dark-900 placeholder:text-dark-300 shadow-none transition focus:bg-white focus:ring-2 focus:ring-primary-500/30"
                >
                <button type="button" x-on:click="showPassword = ! showPassword" class="absolute right-3 top-1/2 flex h-8 w-8 -translate-y-1/2 items-center justify-center rounded-full text-dark-400 transition hover:bg-white hover:text-dark-900" aria-label="Tampilkan password">
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
            <x-input-error :messages="$errors->get('password')" class="mt-2 text-xs" />
        </div>

        <div x-data="{ showPassword: false }">
            <label for="password_confirmation" class="sr-only">Confirm Password</label>
            <div class="relative">
                <input
                    wire:model="password_confirmation"
                    id="password_confirmation"
                    x-bind:type="showPassword ? 'text' : 'password'"
                    name="password_confirmation"
                    required
                    autocomplete="new-password"
                    placeholder="Confirm password"
                    class="h-14 w-full rounded-[6px] border-0 bg-gray-100 px-4 pr-12 text-sm font-semibold text-dark-900 placeholder:text-dark-300 shadow-none transition focus:bg-white focus:ring-2 focus:ring-primary-500/30"
                >
                <button type="button" x-on:click="showPassword = ! showPassword" class="absolute right-3 top-1/2 flex h-8 w-8 -translate-y-1/2 items-center justify-center rounded-full text-dark-400 transition hover:bg-white hover:text-dark-900" aria-label="Tampilkan konfirmasi password">
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
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-xs" />
        </div>

        <button type="submit" class="flex h-14 w-full items-center justify-center rounded-[7px] bg-primary-500 px-5 text-sm font-black text-white shadow-lg shadow-primary-500/25 transition hover:bg-primary-600 focus:outline-none focus:ring-4 focus:ring-primary-500/20">
            Register
        </button>
    </form>
</div>
