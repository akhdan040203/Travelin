<?php

use App\Livewire\Actions\Logout;
use Illuminate\Support\Facades\Auth;
use Livewire\Volt\Component;

new class extends Component
{
    public string $password = '';

    /**
     * Delete the currently authenticated user.
     */
    public function deleteUser(Logout $logout): void
    {
        $this->validate([
            'password' => ['required', 'string', 'current_password'],
        ]);

        tap(Auth::user(), $logout(...))->delete();

        $this->redirect('/', navigate: true);
    }
}; ?>

<section class="space-y-6">
<section>
    <button
        x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
        class="px-8 py-3 bg-red-600 text-white text-xs font-black uppercase tracking-widest rounded-xl hover:bg-red-700 active:scale-95 transition-all shadow-lg shadow-red-500/20"
    >
        {{ __('Delete Account') }}
    </button>

    <x-modal name="confirm-user-deletion" :show="$errors->isNotEmpty()" focusable>
        <form wire:submit="deleteUser" class="p-8">
            <h2 class="text-xl font-black text-dark-900 mb-2">
                {{ __('Are you sure you want to delete your account?') }}
            </h2>

            <p class="text-sm text-dark-400 mb-8 leading-relaxed">
                {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.') }}
            </p>

            <div class="space-y-1.5">
                <label for="delete_password" class="text-[10px] font-black uppercase tracking-widest text-dark-400 ml-1">Your Password</label>
                <input wire:model="password" id="delete_password" name="password" type="password" 
                       class="block w-full px-4 py-3.5 rounded-xl border-gray-100 bg-gray-50 text-sm font-bold text-dark-900 focus:bg-white focus:ring-red-500 focus:border-red-500 transition-all shadow-sm" 
                       placeholder="Enter your password to confirm">
                <x-input-error :messages="$errors->get('password')" class="text-xs" />
            </div>

            <div class="mt-10 flex justify-end gap-3">
                <button type="button" x-on:click="$dispatch('close')" class="px-6 py-3 border border-gray-200 rounded-xl text-xs font-black uppercase tracking-widest text-dark-600 hover:bg-gray-50 transition-all">
                    {{ __('Cancel') }}
                </button>

                <button type="submit" class="px-6 py-3 bg-red-600 text-white text-xs font-black uppercase tracking-widest rounded-xl hover:bg-red-700 active:scale-95 transition-all shadow-lg shadow-red-500/20">
                    {{ __('Permanently Delete') }}
                </button>
            </div>
        </form>
    </x-modal>
</section>
</section>
