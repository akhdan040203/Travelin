@extends('layouts.main')

@section('title', 'FAQ - Travelin')

@section('content')
{{-- Header --}}
<section class="pt-32 pb-12 bg-gradient-to-br from-gray-50 to-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h1 class="section-title text-4xl">Frequently Asked Questions</h1>
        <p class="section-subtitle mx-auto">Temukan jawaban untuk pertanyaan yang sering diajukan</p>
    </div>
</section>

{{-- FAQ Accordion --}}
<section class="py-12 bg-white">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="space-y-4">
            @foreach($faqs as $index => $faq)
                <div class="border border-gray-200 rounded-2xl overflow-hidden hover:border-primary-200 transition-colors"
                     x-data="{ open: {{ $index === 0 ? 'true' : 'false' }} }">
                    <button @click="open = !open"
                            class="w-full flex items-center justify-between p-6 text-left focus:outline-none group">
                        <h3 class="font-semibold text-dark-900 pr-4 group-hover:text-primary-500 transition-colors">
                            {{ $faq->question }}
                        </h3>
                        <div class="flex-shrink-0 w-8 h-8 rounded-full bg-gray-100 group-hover:bg-primary-50 flex items-center justify-center transition-all"
                             :class="{ 'bg-primary-50': open }">
                        </div>
                    </button>
                    <div x-show="open" x-collapse>
                        <div class="px-6 pb-6">
                            <p class="text-dark-500 leading-relaxed">{{ $faq->answer }}</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Still Have Questions --}}
        <div class="mt-16 bg-gradient-to-br from-primary-500 to-primary-600 rounded-3xl p-8 md:p-12 text-center">
            <h2 class="text-2xl font-bold text-white mb-3">Masih punya pertanyaan?</h2>
            <p class="text-white/80 mb-6">Tim kami siap membantu kamu 24/7</p>
            <div class="flex flex-wrap justify-center gap-4">
                <a href="{{ route('contact') }}" class="btn-white">
                    Hubungi Kami
                </a>
                <a href="https://wa.me/6281234567890" class="bg-white/20 backdrop-blur text-white px-6 py-3 rounded-full font-semibold hover:bg-white/30 transition-all" target="_blank">
                    Chat WhatsApp
                </a>
            </div>
        </div>
    </div>
</section>
@endsection
