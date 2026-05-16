@extends('layouts.main')

@section('title', 'Hubungi Kami - Travelin')
@section('meta_description', 'Hubungi tim Travelin untuk pertanyaan, pemesanan, atau custom trip. Kami siap membantu perjalanan impian Anda!')

@push('styles')
<style>
    /* ── Form field overrides ────────── */
    .c-input {
        width: 100%;
        padding: 0.7rem 1rem;
        border-radius: 10px;
        border: 1.5px solid #e5e7eb;
        font-size: 0.9rem;
        font-family: inherit;
        color: #111827;
        background: #fff;
        outline: none;
        transition: border-color 0.2s, box-shadow 0.2s;
    }
    .c-input:focus {
        border-color: var(--color-primary, #ff4d6d);
        box-shadow: 0 0 0 3px rgba(255, 77, 109, 0.12);
    }
    .c-input.error { border-color: #f87171; }
    .c-input::placeholder { color: #9ca3af; }

    .c-label {
        display: block;
        font-size: 0.8125rem;
        font-weight: 600;
        color: #374151;
        margin-bottom: 0.35rem;
    }

    /* ── Info icon cell ──────────────── */
    .ci-icon {
        width: 42px;
        height: 42px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    /* ── Social icon ─────────────────── */
    .soc-icon {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: transform 0.2s, opacity 0.2s;
        text-decoration: none;
    }
    .soc-icon:hover { transform: translateY(-2px); opacity: 0.85; }

    /* ── Map embed ───────────────────── */
    .c-map {
        border-radius: 16px;
        overflow: hidden;
        border: 1px solid #f0f0f0;
    }

    /* ── submit loading spinner ──────── */
    @keyframes spin { 100%{ transform: rotate(360deg); } }
    .spin { animation: spin 0.8s linear infinite; }
</style>
@endpush

@section('content')

{{-- ═══════════════════════════════════════════
     PAGE WRAPPER  (starts below fixed navbar)
═══════════════════════════════════════════ --}}
<div class="pt-28 pb-20 bg-white min-h-screen">
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

    {{-- ── TOP HEADER  (2 columns) ─────────────────────────────── --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-12 items-end">

        {{-- Left: label + big title --}}
        <div>
            <span class="text-xs font-bold uppercase tracking-widest text-primary-500 mb-3 block">
                Hubungi Kami
            </span>
            <h1 class="text-4xl sm:text-5xl font-extrabold text-dark-900 leading-tight">
                Ada yang Ingin<br>
                Kamu Tanyakan?
            </h1>
        </div>

        {{-- Right: description --}}
        <div class="lg:pt-10">
            <p class="text-dark-400 text-base leading-relaxed max-w-md">
                Ceritakan kebutuhan trip impianmu, tanyakan soal paket wisata, atau minta penawaran custom trip. Tim Travelin siap membantu kamu merencanakan perjalanan terbaik!
            </p>
        </div>

    </div>

    {{-- ── MAIN CONTENT  (form kiri · info+map kanan) ────────── --}}
    <div class="grid grid-cols-1 lg:grid-cols-5 gap-8 items-start">

        {{-- ════════════════════════════════
             KIRI — Form Card
        ════════════════════════════════ --}}
        <div class="lg:col-span-3">
            <div class="card-travel p-8">

                <h2 class="font-bold text-dark-900 text-xl mb-6">Kirim Pesan</h2>

                {{-- Success alert --}}
                @if(session('success'))
                <div class="flex items-start gap-3 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl p-4 mb-5 text-sm">
                    <svg class="w-5 h-5 text-emerald-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <p>{{ session('success') }}</p>
                </div>
                @endif

                {{-- Validation errors --}}
                @if($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-700 rounded-xl p-4 mb-5 text-sm">
                    <ul class="space-y-1 list-disc list-inside">
                        @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
                    </ul>
                </div>
                @endif

                <form action="{{ route('contact.store') }}" method="POST" id="c-form" novalidate class="space-y-4">
                    @csrf

                    {{-- Row 1: Nama + Email --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="c-name" class="c-label">Nama Lengkap <span class="text-primary-500">*</span></label>
                            <div class="relative">
                                <input type="text" id="c-name" name="name" required autocomplete="name"
                                       value="{{ old('name') }}" placeholder="Nama kamu"
                                       class="c-input pr-10 @error('name') error @enderror">
                                <span class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-300">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                </span>
                            </div>
                            @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label for="c-email" class="c-label">Email <span class="text-primary-500">*</span></label>
                            <div class="relative">
                                <input type="email" id="c-email" name="email" required autocomplete="email"
                                       value="{{ old('email') }}" placeholder="email@kamu.com"
                                       class="c-input pr-10 @error('email') error @enderror">
                                <span class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-300">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                    </svg>
                                </span>
                            </div>
                            @error('email')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    {{-- Row 2: No. HP + Subjek --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="c-phone" class="c-label">No. WhatsApp / HP</label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-dark-400 text-sm font-semibold">+62</span>
                                <input type="tel" id="c-phone" name="phone" autocomplete="tel"
                                       value="{{ old('phone') }}" placeholder="812-3456-7890"
                                       class="c-input pl-12 @error('phone') error @enderror">
                            </div>
                            @error('phone')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label for="c-subject" class="c-label">Topik <span class="text-primary-500">*</span></label>
                            <div class="relative">
                                <select id="c-subject" name="subject" required
                                        class="c-input pr-10 appearance-none @error('subject') error @enderror">
                                    <option value="" disabled @if(!old('subject')) selected @endif>Pilih topik...</option>
                                    <option value="Pertanyaan Paket Wisata"  @selected(old('subject')=='Pertanyaan Paket Wisata')>Pertanyaan Paket Wisata</option>
                                    <option value="Custom Trip"              @selected(old('subject')=='Custom Trip')>Custom Trip</option>
                                    <option value="Info Pembayaran"          @selected(old('subject')=='Info Pembayaran')>Info Pembayaran</option>
                                    <option value="Keluhan / Saran"          @selected(old('subject')=='Keluhan / Saran')>Keluhan / Saran</option>
                                    <option value="Kerjasama"                @selected(old('subject')=='Kerjasama')>Kerjasama / Mitra</option>
                                    <option value="Lainnya"                  @selected(old('subject')=='Lainnya')>Lainnya</option>
                                </select>
                                <span class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </span>
                            </div>
                            @error('subject')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    {{-- Pesan --}}
                    <div>
                        <label for="c-message" class="c-label">Pesan <span class="text-primary-500">*</span></label>
                        <textarea id="c-message" name="message" rows="5" required
                                  placeholder="Ceritakan kebutuhan trip impian kamu, atau tulis pertanyaanmu di sini..."
                                  class="c-input resize-none @error('message') error @enderror">{{ old('message') }}</textarea>
                        @error('message')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    {{-- Submit --}}
                    <button type="submit" id="c-submit" class="btn-primary w-full flex items-center justify-center gap-2 !py-3.5 !text-base">
                        <svg id="c-icon" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                        </svg>
                        <span id="c-label">Kirim Pesan ke Travelin</span>
                    </button>
                </form>
            </div>
        </div>

        {{-- ════════════════════════════════
             KANAN — Info Cards + Map
        ════════════════════════════════ --}}
        <div class="lg:col-span-2 space-y-4">

            {{-- WhatsApp --}}
            <a href="https://wa.me/6281234567890" target="_blank" rel="noopener"
               class="card-travel p-5 flex items-start gap-4 hover:-translate-y-1 transition-transform duration-200 no-underline">
                <div class="ci-icon bg-[#e7fef1]">
                    <svg class="w-5 h-5 text-[#25D366]" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-xs font-semibold text-dark-300 uppercase tracking-wider mb-0.5">WhatsApp</p>
                    <p class="font-bold text-dark-900">+62 812-3456-7890</p>
                    <p class="text-sm text-[#25D366] font-medium mt-0.5">Chat Sekarang →</p>
                </div>
            </a>

            {{-- Email --}}
            <a href="mailto:info@travelin.com"
               class="card-travel p-5 flex items-start gap-4 hover:-translate-y-1 transition-transform duration-200 no-underline">
                <div class="ci-icon bg-primary-50">
                    <svg class="w-5 h-5 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-xs font-semibold text-dark-300 uppercase tracking-wider mb-0.5">Email</p>
                    <p class="font-bold text-dark-900">info@travelin.com</p>
                    <p class="text-sm text-dark-400">booking@travelin.com</p>
                </div>
            </a>

            {{-- Jam Operasional --}}
            <div class="card-travel p-5 flex items-start gap-4">
                <div class="ci-icon bg-amber-50">
                    <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="flex-1">
                    <p class="text-xs font-semibold text-dark-300 uppercase tracking-wider mb-1.5">Jam Operasional</p>
                    <div class="space-y-1">
                        <div class="flex justify-between text-sm">
                            <span class="text-dark-400">Senin – Jumat</span>
                            <span class="font-semibold text-dark-900">08.00 – 17.00</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-dark-400">Sabtu</span>
                            <span class="font-semibold text-dark-900">09.00 – 14.00</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-dark-400">Minggu</span>
                            <span class="font-semibold text-primary-400">Tutup</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Kantor --}}
            <div class="card-travel p-5 flex items-start gap-4">
                <div class="ci-icon bg-blue-50">
                    <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-xs font-semibold text-dark-300 uppercase tracking-wider mb-0.5">Kantor Travelin</p>
                    <p class="font-bold text-dark-900">Jl. Sudirman No. 123</p>
                    <p class="text-sm text-dark-400">Jakarta Pusat, 10220</p>
                </div>
            </div>

            {{-- Sosial Media --}}
            <div class="card-travel p-5">
                <p class="text-xs font-semibold text-dark-300 uppercase tracking-wider mb-3">Ikuti Kami</p>
                <div class="flex gap-2">
                    {{-- Instagram --}}
                    <a href="#" target="_blank" rel="noopener" title="Instagram"
                       class="soc-icon bg-pink-50 text-pink-500 hover:bg-pink-500 hover:text-white transition">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/>
                        </svg>
                    </a>
                    {{-- Facebook --}}
                    <a href="#" target="_blank" rel="noopener" title="Facebook"
                       class="soc-icon bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white transition">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                        </svg>
                    </a>
                    {{-- X / Twitter --}}
                    <a href="#" target="_blank" rel="noopener" title="Twitter / X"
                       class="soc-icon bg-gray-100 text-dark-700 hover:bg-dark-900 hover:text-white transition">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
                        </svg>
                    </a>
                    {{-- TikTok --}}
                    <a href="#" target="_blank" rel="noopener" title="TikTok"
                       class="soc-icon bg-gray-100 text-dark-700 hover:bg-dark-900 hover:text-white transition">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M19.59 6.69a4.83 4.83 0 01-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 01-2.88 2.5 2.89 2.89 0 01-2.89-2.89 2.89 2.89 0 012.89-2.89c.28 0 .54.04.79.1V9.01a6.33 6.33 0 00-.79-.05 6.34 6.34 0 00-6.34 6.34 6.34 6.34 0 006.34 6.34 6.34 6.34 0 006.33-6.34V8.69a8.17 8.17 0 004.78 1.52V6.75a4.85 4.85 0 01-1.01-.06z"/>
                        </svg>
                    </a>
                </div>
            </div>

            {{-- Google Maps --}}
            <div class="c-map">
                <iframe
                    title="Lokasi Kantor Travelin"
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3966.295!2d106.82162!3d-6.20876!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69f40c8e028bc1%3A0x7aa3cb0ce9ee2fc7!2sJl.%20Jend.%20Sudirman%2C%20Jakarta!5e0!3m2!1sid!2sid!4v1715601000000"
                    width="100%"
                    height="260"
                    style="border:0; display:block;"
                    allowfullscreen
                    loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade">
                </iframe>
            </div>

        </div>
        {{-- end right col --}}

    </div>
    {{-- end grid --}}

</div>
</div>

@endsection

@push('scripts')
<script>
    // Submit loading state
    document.getElementById('c-form')?.addEventListener('submit', function () {
        const btn   = document.getElementById('c-submit');
        const label = document.getElementById('c-label');
        const icon  = document.getElementById('c-icon');
        if (!btn) return;
        btn.disabled = true;
        label.textContent = 'Mengirim...';
        icon.outerHTML = `<svg class="w-5 h-5 spin" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
        </svg>`;
    });

    // Auto-dismiss success alert
    const alert = document.querySelector('.bg-emerald-50');
    if (alert) {
        setTimeout(() => {
            alert.style.transition = 'opacity .4s, transform .4s';
            alert.style.opacity = '0';
            alert.style.transform = 'translateY(-6px)';
            setTimeout(() => alert.remove(), 400);
        }, 5000);
    }
</script>
@endpush
