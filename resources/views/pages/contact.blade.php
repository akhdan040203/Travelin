@extends('layouts.main')

@section('title', 'Hubungi Kami - Travelin')

@section('content')
{{-- Header --}}
<section class="pt-32 pb-12 bg-gradient-to-br from-gray-50 to-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h1 class="section-title text-4xl">Hubungi Kami</h1>
        <p class="section-subtitle mx-auto">Ada pertanyaan atau ingin request custom trip? Jangan ragu menghubungi kami!</p>
    </div>
</section>

{{-- Contact Content --}}
<section class="py-12 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
            {{-- Contact Info Cards --}}
            <div class="space-y-6">
                <div class="card-travel p-6">
                    <div class="w-12 h-12 rounded-xl bg-primary-50 flex items-center justify-center mb-4">
                    </div>
                    <h3 class="font-bold text-dark-900 mb-2">Alamat</h3>
                    <p class="text-dark-400 text-sm">Jl. Sudirman No. 123<br>Jakarta Pusat, 10220</p>
                </div>
                <div class="card-travel p-6">
                    <div class="w-12 h-12 rounded-xl bg-primary-50 flex items-center justify-center mb-4">
                    </div>
                    <h3 class="font-bold text-dark-900 mb-2">Telepon</h3>
                    <p class="text-dark-400 text-sm">+62 812-3456-7890</p>
                    <p class="text-dark-400 text-sm">+62 21-1234-5678</p>
                </div>
                <div class="card-travel p-6">
                    <div class="w-12 h-12 rounded-xl bg-primary-50 flex items-center justify-center mb-4">
                    </div>
                    <h3 class="font-bold text-dark-900 mb-2">Email</h3>
                    <p class="text-dark-400 text-sm">info@travelin.com</p>
                    <p class="text-dark-400 text-sm">booking@travelin.com</p>
                </div>
                <div class="card-travel p-6">
                    <div class="w-12 h-12 rounded-xl bg-primary-50 flex items-center justify-center mb-4">
                    </div>
                    <h3 class="font-bold text-dark-900 mb-2">Jam Operasional</h3>
                    <p class="text-dark-400 text-sm">Senin - Jumat: 08:00 - 17:00</p>
                    <p class="text-dark-400 text-sm">Sabtu: 09:00 - 14:00</p>
                </div>
            </div>

            {{-- Contact Form --}}
            <div class="lg:col-span-2">
                <div class="card-travel p-8">
                    <h2 class="text-2xl font-bold text-dark-900 mb-6">Kirim Pesan</h2>

                    @if(session('success'))
                        <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl p-4 mb-6 flex items-center gap-3">
                            {{ session('success') }}
                        </div>
                    @endif

                    <form id="whatsapp-contact-form" class="space-y-5">
                        <div>
                            <label for="whatsapp-message" class="block text-sm font-medium text-dark-700 mb-2">Pesan *</label>
                            <textarea name="message" id="whatsapp-message" rows="7" required placeholder="Tulis pesan kamu di sini..."
                                      class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 text-sm resize-none"></textarea>
                            <p id="whatsapp-message-error" class="hidden text-red-500 text-xs mt-1">Pesan wajib diisi.</p>
                        </div>
                        <button type="submit" class="btn-primary !px-8 flex items-center gap-2">
                            Send
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
    document.getElementById('whatsapp-contact-form')?.addEventListener('submit', function (event) {
        event.preventDefault();

        const input = document.getElementById('whatsapp-message');
        const error = document.getElementById('whatsapp-message-error');
        const message = input.value.trim();

        if (!message) {
            error?.classList.remove('hidden');
            input.focus();
            return;
        }

        error?.classList.add('hidden');

        const phone = '6281234567890';
        const text = encodeURIComponent(message);
        window.open(`https://wa.me/${phone}?text=${text}`, '_blank', 'noopener');
    });
</script>
@endpush
