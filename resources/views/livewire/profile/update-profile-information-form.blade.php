<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Livewire\Volt\Component;
use Livewire\WithFileUploads;

new class extends Component
{
    use WithFileUploads;

    public string $name = '';
    public string $email = '';
    public $avatar = null;

    /**
     * Mount the component.
     */
    public function mount(): void
    {
        $this->name = Auth::user()->name;
        $this->email = Auth::user()->email;
    }

    /**
     * Update the profile information for the currently authenticated user.
     */
    public function updateProfileInformation(): void
    {
        $user = Auth::user();

        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique(User::class)->ignore($user->id)],
            'avatar' => ['nullable', 'image', 'max:2048'],
        ]);

        if ($this->avatar) {
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }

            $validated['avatar'] = $this->avatar->store('avatars', 'public');
        } else {
            unset($validated['avatar']);
        }

        $user->fill($validated);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        $this->dispatch('profile-updated', name: $user->name);
        $this->avatar = null;
    }

    /**
     * Send an email verification notification to the current user.
     */
    public function sendVerification(): void
    {
        $user = Auth::user();

        if ($user->hasVerifiedEmail()) {
            $this->redirectIntended(default: route('dashboard', absolute: false));

            return;
        }

        $user->sendEmailVerificationNotification();

        Session::flash('status', 'verification-link-sent');
    }
}; ?>

<section>
    <form wire:submit="updateProfileInformation" class="space-y-8">
        {{-- Avatar Edit Section --}}
        <div class="flex flex-col md:flex-row items-center gap-8 p-6 bg-gray-50 rounded-2xl border border-gray-100">
            <div class="relative">
                <div class="h-24 w-24 overflow-hidden rounded-full ring-4 ring-white shadow-xl shadow-black/5">
                    @if($avatar)
                        <img src="{{ $avatar->temporaryUrl() }}" class="h-full w-full object-cover" alt="Preview foto profil">
                    @elseif(auth()->user()->avatar_url)
                        <img src="{{ auth()->user()->avatar_url }}" class="h-full w-full object-cover" alt="Foto profil">
                    @else
                        <div class="flex h-full w-full items-center justify-center text-3xl font-black text-primary-500 bg-white">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                    @endif
                </div>
                <label for="avatar" class="absolute -bottom-1 -right-1 h-8 w-8 bg-dark-900 rounded-full flex items-center justify-center text-white cursor-pointer hover:bg-primary-500 transition-colors shadow-lg">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                </label>
                <input wire:model="avatar" id="avatar" name="avatar" type="file" accept="image/*" class="hidden">
            </div>
            <div class="flex-1 text-center md:text-left">
                <h4 class="text-sm font-black text-dark-900 mb-1">Profile Photo</h4>
                <p class="text-xs text-dark-400 mb-0">Recommended size: 400x400px (Max 2MB)</p>
                <x-input-error class="mt-2 text-xs" :messages="$errors->get('avatar')" />
            </div>
        </div>

        {{-- Form Fields Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="space-y-1.5">
                <label for="name" class="text-[10px] font-black uppercase tracking-widest text-dark-400 ml-1">Full Name</label>
                <input wire:model="name" id="name" name="name" type="text" 
                       class="block w-full px-4 py-3.5 rounded-xl border-gray-100 bg-gray-50 text-sm font-bold text-dark-900 focus:bg-white focus:ring-primary-500 focus:border-primary-500 transition-all shadow-sm" 
                       required autofocus autocomplete="name">
                <x-input-error class="text-xs" :messages="$errors->get('name')" />
            </div>

            <div class="space-y-1.5">
                <label for="email" class="text-[10px] font-black uppercase tracking-widest text-dark-400 ml-1">Email Address</label>
                <input wire:model="email" id="email" name="email" type="email" 
                       class="block w-full px-4 py-3.5 rounded-xl border-gray-100 bg-gray-50 text-sm font-bold text-dark-900 focus:bg-white focus:ring-primary-500 focus:border-primary-500 transition-all shadow-sm" 
                       required autocomplete="username">
                <x-input-error class="text-xs" :messages="$errors->get('email')" />
            </div>

            @php
                $user = auth()->user();
                // We use existing fields but formatted as seen in screenshot
            @endphp
            
            <div class="space-y-1.5">
                <label for="phone" class="text-[10px] font-black uppercase tracking-widest text-dark-400 ml-1">Phone Number</label>
                <input value="{{ $user->phone ?? '+62 000 0000 0000' }}" disabled
                       class="block w-full px-4 py-3.5 rounded-xl border-gray-100 bg-gray-50/50 text-sm font-bold text-dark-300 cursor-not-allowed italic">
                <p class="text-[9px] text-dark-300 italic px-1">Currently read-only</p>
            </div>

            <div class="space-y-1.5">
                <label class="text-[10px] font-black uppercase tracking-widest text-dark-400 ml-1">Account Role</label>
                <div class="block w-full px-4 py-3.5 rounded-xl border border-gray-100 bg-gray-50/50 text-sm font-bold text-dark-300 capitalize">
                    {{ $user->role }} Member
                </div>
            </div>
        </div>

        <div class="pt-4 flex items-center justify-end gap-4 border-t border-gray-50">
            <x-action-message class="text-xs font-bold text-green-500" on="profile-updated">
                {{ __('Saved successfully.') }}
            </x-action-message>

            <button type="submit" class="px-8 py-3 bg-dark-900 text-white text-xs font-black uppercase tracking-widest rounded-xl hover:bg-primary-500 active:scale-95 transition-all shadow-lg shadow-black/10">
                Save Changes
            </button>
        </div>
    </form>
</section>
