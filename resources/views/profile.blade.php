@extends('layouts.main')

@section('title', 'My Profile - TravelGo')

@section('content')
<div class="min-h-screen bg-[#F9FAFB] pt-28 pb-20">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="space-y-6">
            {{-- Personal Information Card --}}
            <div id="personal-info" class="bg-white rounded-3xl p-8 shadow-sm border border-gray-100">
                <div class="flex items-center justify-between mb-8">
                    <h3 class="text-lg font-black text-dark-900">Personal Information</h3>
                </div>
                
                <livewire:profile.update-profile-information-form />
            </div>

            {{-- Password Card --}}
            <div class="bg-white rounded-3xl p-8 shadow-sm border border-gray-100">
                <div class="flex items-center justify-between mb-8">
                    <h3 class="text-lg font-black text-dark-900">Password Settings</h3>
                </div>

                <livewire:profile.update-password-form />
            </div>

            {{-- Account Danger Zone Card --}}
            <div class="bg-white rounded-3xl p-8 shadow-sm border border-gray-100 border-red-50">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-black text-red-600">Delete Account</h3>
                </div>
                
                <p class="text-sm text-dark-400 mb-6 leading-relaxed">
                    Once your account is deleted, all of its resources and data will be permanently deleted. Please download any data or information that you wish to retain.
                </p>

                <livewire:profile.delete-user-form />
            </div>
        </div>
    </div>
</div>
@endsection
