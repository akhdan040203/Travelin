@extends('layouts.main')

@section('title', 'Booking - ' . $schedule->destination->name . ' - Travelin')

@section('content')
<section class="pt-28 pb-16 bg-gray-50 min-h-screen">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- Header --}}
        <div class="mb-8">
            <a href="{{ route('destinations.show', $schedule->destination->slug) }}" class="text-primary-500 text-sm font-semibold hover:text-primary-600 mb-2 inline-block">← Kembali ke Destinasi</a>
            <h1 class="text-2xl font-bold text-dark-900">Form Booking</h1>
        </div>

        @if(session('error'))
            <div class="bg-red-50 border border-red-200 text-red-800 rounded-xl p-4 mb-6 flex items-center gap-3">
                {{ session('error') }}
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            {{-- Booking Form --}}
            <div class="lg:col-span-2">
                <form action="{{ route('booking.store') }}" method="POST" class="bg-white rounded-2xl shadow-sm p-8 space-y-6">
                    @csrf
                    <input type="hidden" name="schedule_id" value="{{ $schedule->id }}">

                    {{-- Trip Summary --}}
                    <div class="bg-primary-50 rounded-xl p-5 flex items-start gap-4">
                        <div class="w-16 h-16 bg-primary-100 rounded-xl flex flex-col items-center justify-center flex-shrink-0">
                            <span class="text-lg font-bold text-primary-500">{{ $schedule->departure_date->format('d') }}</span>
                            <span class="text-[10px] font-medium text-primary-500 uppercase">{{ $schedule->departure_date->format('M') }}</span>
                        </div>
                        <div>
                            <h3 class="font-bold text-dark-900">{{ $schedule->destination->name }}</h3>
                            <p class="text-dark-400 text-sm mt-1">{{ $schedule->departure_date->format('d M Y') }} - {{ $schedule->return_date->format('d M Y') }}</p>
                            <p class="text-dark-400 text-sm">{{ $schedule->destination->location }}</p>
                            @if($schedule->meeting_point)
                                <p class="text-dark-400 text-sm">Meeting: {{ $schedule->meeting_point }}</p>
                            @endif
                        </div>
                    </div>

                    {{-- Contact Info --}}
                    <div>
                        <h2 class="text-lg font-bold text-dark-900 mb-4">Data Kontak</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="contact_name" class="block text-sm font-medium text-dark-700 mb-2">Nama Lengkap *</label>
                                <input type="text" name="contact_name" id="contact_name"
                                       value="{{ old('contact_name', auth()->user()->name) }}" required
                                       class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 text-sm">
                                @error('contact_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label for="contact_email" class="block text-sm font-medium text-dark-700 mb-2">Email *</label>
                                <input type="email" name="contact_email" id="contact_email"
                                       value="{{ old('contact_email', auth()->user()->email) }}" required
                                       class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 text-sm">
                                @error('contact_email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label for="contact_phone" class="block text-sm font-medium text-dark-700 mb-2">No. Telepon *</label>
                                <input type="text" name="contact_phone" id="contact_phone"
                                       value="{{ old('contact_phone', auth()->user()->phone) }}" required
                                       class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 text-sm"
                                       placeholder="08xxxxxxxxxx">
                                @error('contact_phone') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label for="participants" class="block text-sm font-medium text-dark-700 mb-2">Jumlah Peserta *</label>
                                <select name="participants" id="participants" required
                                        class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 text-sm bg-white"
                                        onchange="updateTotal()">
                                    @for($i = 1; $i <= min(10, $schedule->available_slots); $i++)
                                        <option value="{{ $i }}" {{ old('participants', 1) == $i ? 'selected' : '' }}>{{ $i }} orang</option>
                                    @endfor
                                </select>
                                @error('participants') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    </div>

                    {{-- Payment Method --}}
                    <div>
                        <h2 class="text-lg font-bold text-dark-900 mb-4">Metode Pembayaran</h2>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                            <label class="relative cursor-pointer">
                                <input type="radio" name="payment_method" value="bank_transfer" class="peer sr-only" checked>
                                <div class="border-2 border-gray-200 rounded-xl p-4 text-center peer-checked:border-primary-500 peer-checked:bg-primary-50 transition-all">
                                    <span class="text-sm font-semibold text-dark-900">Transfer Bank</span>
                                </div>
                            </label>
                            <label class="relative cursor-pointer">
                                <input type="radio" name="payment_method" value="e_wallet" class="peer sr-only">
                                <div class="border-2 border-gray-200 rounded-xl p-4 text-center peer-checked:border-primary-500 peer-checked:bg-primary-50 transition-all">
                                    <span class="text-sm font-semibold text-dark-900">E-Wallet</span>
                                </div>
                            </label>
                            <label class="relative cursor-pointer">
                                <input type="radio" name="payment_method" value="credit_card" class="peer sr-only">
                                <div class="border-2 border-gray-200 rounded-xl p-4 text-center peer-checked:border-primary-500 peer-checked:bg-primary-50 transition-all">
                                    <span class="text-sm font-semibold text-dark-900">Kartu Kredit</span>
                                </div>
                            </label>
                        </div>
                        @error('payment_method') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Special Requests --}}
                    <div>
                        <label for="special_requests" class="block text-sm font-medium text-dark-700 mb-2">Permintaan Khusus (Opsional)</label>
                        <textarea name="special_requests" id="special_requests" rows="3"
                                  class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 text-sm resize-none"
                                  placeholder="Contoh: vegetarian, butuh kursi roda, dll">{{ old('special_requests') }}</textarea>
                    </div>

                    <button type="submit" class="btn-primary w-full !py-4 text-base flex items-center justify-center gap-2">
                        Konfirmasi Booking
                    </button>
                </form>
            </div>

            {{-- Price Summary Sidebar --}}
            <div>
                <div class="bg-white rounded-2xl shadow-sm p-6 sticky top-28">
                    <h2 class="font-bold text-dark-900 mb-4">Ringkasan Harga</h2>
                    <div class="space-y-3">
                        <div class="flex justify-between text-sm">
                            <span class="text-dark-400">Harga per orang</span>
                            <span class="text-dark-900 font-medium" id="price-per-person">{{ $schedule->formatted_price }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-dark-400">Jumlah peserta</span>
                            <span class="text-dark-900 font-medium" id="participant-count">× 1</span>
                        </div>
                        <hr class="border-gray-100">
                        <div class="flex justify-between">
                            <span class="font-bold text-dark-900">Total</span>
                            <span class="text-xl font-bold text-primary-500" id="total-price">{{ $schedule->formatted_price }}</span>
                        </div>
                    </div>

                    <div class="mt-6 p-4 bg-gray-50 rounded-xl space-y-2">
                        <p class="text-dark-400 text-xs flex items-center gap-2">
                            Pembayaran aman & terenkripsi
                        </p>
                        <p class="text-dark-400 text-xs flex items-center gap-2">
                            Konfirmasi instan
                        </p>
                        <p class="text-dark-400 text-xs flex items-center gap-2">
                            Bisa dibatalkan gratis 7 hari sebelum keberangkatan
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
    const pricePerPerson = {{ $schedule->effective_price }};

    function updateTotal() {
        const participants = document.getElementById('participants').value;
        const total = pricePerPerson * participants;
        document.getElementById('participant-count').textContent = '× ' + participants;
        document.getElementById('total-price').textContent = 'Rp ' + total.toLocaleString('id-ID');
    }
</script>
@endpush
