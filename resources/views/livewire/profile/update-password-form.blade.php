<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;
use Livewire\Volt\Component;

new class extends Component
{
    public string $current_password = '';
    public string $password = '';
    public string $password_confirmation = '';

    /**
     * Update the password for the currently authenticated user.
     */
    public function updatePassword(): void
    {
        try {
            $validated = $this->validate([
                'current_password' => ['required', 'string', 'current_password'],
                'password' => ['required', 'string', Password::defaults(), 'confirmed'],
            ]);
        } catch (ValidationException $e) {
            $this->reset('current_password', 'password', 'password_confirmation');

            throw $e;
        }

        Auth::user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        $this->reset('current_password', 'password', 'password_confirmation');

        $this->dispatch('password-updated');
    }
}; ?>

<section>
    <form wire:submit="updatePassword" class="space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="space-y-1.5">
                <label for="update_password_current_password" class="text-[10px] font-black uppercase tracking-widest text-dark-400 ml-1">Current Password</label>
                <input wire:model="current_password" id="update_password_current_password" name="current_password" type="password" 
                       class="block w-full px-4 py-3.5 rounded-xl border-gray-100 bg-gray-50 text-sm font-bold text-dark-900 focus:bg-white focus:ring-primary-500 focus:border-primary-500 transition-all shadow-sm" 
                       autocomplete="current-password">
                <x-input-error :messages="$errors->get('current_password')" class="text-xs" />
            </div>

            <div class="space-y-1.5">
                <label for="update_password_password" class="text-[10px] font-black uppercase tracking-widest text-dark-400 ml-1">New Password</label>
                <input wire:model="password" id="update_password_password" name="password" type="password" 
                       class="block w-full px-4 py-3.5 rounded-xl border-gray-100 bg-gray-50 text-sm font-bold text-dark-900 focus:bg-white focus:ring-primary-500 focus:border-primary-500 transition-all shadow-sm" 
                       autocomplete="new-password">
                <x-input-error :messages="$errors->get('password')" class="text-xs" />
            </div>

            <div class="space-y-1.5">
                <label for="update_password_password_confirmation" class="text-[10px] font-black uppercase tracking-widest text-dark-400 ml-1">Confirm New Password</label>
                <input wire:model="password_confirmation" id="update_password_password_confirmation" name="password_confirmation" type="password" 
                       class="block w-full px-4 py-3.5 rounded-xl border-gray-100 bg-gray-50 text-sm font-bold text-dark-900 focus:bg-white focus:ring-primary-500 focus:border-primary-500 transition-all shadow-sm" 
                       autocomplete="new-password">
                <x-input-error :messages="$errors->get('password_confirmation')" class="text-xs" />
            </div>
        </div>

        <div class="pt-4 flex items-center justify-end gap-4 border-t border-gray-50">
            <x-action-message class="text-xs font-bold text-green-500" on="password-updated">
                {{ __('Password updated successfully.') }}
            </x-action-message>

            <button type="submit" class="px-8 py-3 bg-dark-900 text-white text-xs font-black uppercase tracking-widest rounded-xl hover:bg-primary-500 active:scale-95 transition-all shadow-lg shadow-black/10">
                Update Password
            </button>
        </div>
    </form>
</section>
