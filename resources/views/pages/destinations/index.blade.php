@extends('layouts.main')

@section('title', 'Destinasi Wisata - Travelin')

@section('content')
{{-- Page Header --}}
<section class="pt-28 pb-8 bg-gradient-to-br from-gray-50 to-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-2">
            <h1 class="text-3xl md:text-4xl font-black text-dark-900 tracking-tight">Destinasi Wisata</h1>
            <p class="text-dark-400 text-sm mt-2">Temukan paket wisata terbaik untuk liburan impianmu</p>
        </div>

        {{-- Livewire Destinations List --}}
        <div class="mt-8 pb-20">
            <livewire:destinations-list 
                :search="request('search')" 
                :category="request('category')" 
                :sort="request('sort', 'latest')" 
                :price-range="request('price_range')" 
            />
        </div>
    </div>
</section>
@endsection

@push('styles')
<style>
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>
@endpush
