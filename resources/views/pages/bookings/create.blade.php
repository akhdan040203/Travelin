@extends('layouts.main')

@section('title', 'Form Booking - Travelin')

@section('content')
<section class="min-h-screen bg-gray-50 pt-32 pb-16">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-8">
            <p class="text-xs font-black uppercase tracking-[0.25em] text-primary-500">Checkout</p>
            <h1 class="mt-2 text-3xl md:text-5xl font-black text-dark-900">Form Booking</h1>
            <p class="mt-3 text-sm text-dark-400">Isi data perjalananmu sebelum lanjut ke pembayaran Midtrans.</p>
        </div>

        <div class="grid gap-6 lg:grid-cols-[1fr_360px]">
            <form action="{{ route('booking.store') }}" method="POST" class="rounded-3xl bg-white p-5 md:p-8 shadow-xl shadow-black/5 border border-gray-100">
                @csrf

                <div class="grid gap-5 md:grid-cols-2">
                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold text-dark-400 mb-2">Pilih Jadwal</label>
                        <select name="schedule_id" class="w-full rounded-2xl border-gray-100 bg-gray-50 text-sm font-semibold text-dark-900 focus:border-primary-300 focus:ring-primary-500/20">
                            @foreach($schedules as $item)
                                <option value="{{ $item->id }}" @selected(old('schedule_id', $schedule->id) == $item->id)>
                                    {{ $item->departure_date->format('d M Y') }} - {{ $item->return_date->format('d M Y') }} | Sisa {{ $item->available_slots ?? $item->quota }}
                                </option>
                            @endforeach
                        </select>
                        @error('schedule_id') <p class="mt-2 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-dark-400 mb-2">Nama Lengkap</label>
                        <input type="text" name="contact_name" value="{{ old('contact_name', auth()->user()->name) }}" class="w-full rounded-2xl border-gray-100 bg-gray-50 text-sm font-semibold text-dark-900 focus:border-primary-300 focus:ring-primary-500/20" required>
                        @error('contact_name') <p class="mt-2 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-dark-400 mb-2">No HP</label>
                        <input type="tel" name="contact_phone" value="{{ old('contact_phone') }}" placeholder="08xxxxxxxxxx" class="w-full rounded-2xl border-gray-100 bg-gray-50 text-sm font-semibold text-dark-900 focus:border-primary-300 focus:ring-primary-500/20" required>
                        @error('contact_phone') <p class="mt-2 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-dark-400 mb-2">Booking Berapa Orang</label>
                        <input type="number" name="participants" value="{{ old('participants', 1) }}" min="1" max="50" class="w-full rounded-2xl border-gray-100 bg-gray-50 text-sm font-semibold text-dark-900 focus:border-primary-300 focus:ring-primary-500/20" required>
                        @error('participants') <p class="mt-2 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-dark-400 mb-2">Email</label>
                        <input type="email" value="{{ auth()->user()->email }}" class="w-full rounded-2xl border-gray-100 bg-gray-50 text-sm font-semibold text-dark-300" disabled>
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold text-dark-400 mb-2">Catatan Tambahan</label>
                        <textarea name="special_requests" rows="4" placeholder="Opsional" class="w-full rounded-2xl border-gray-100 bg-gray-50 text-sm font-semibold text-dark-900 focus:border-primary-300 focus:ring-primary-500/20">{{ old('special_requests') }}</textarea>
                    </div>
                </div>

                <button type="submit" class="mt-6 inline-flex h-13 w-full items-center justify-center rounded-2xl bg-primary-500 px-6 py-4 text-sm font-black text-white shadow-lg shadow-primary-500/25 hover:bg-primary-600 transition">
                    Checkout & Bayar
                </button>
            </form>

            <aside class="rounded-3xl bg-white p-5 md:p-6 shadow-xl shadow-black/5 border border-gray-100 h-fit">
                <h2 class="text-xl font-black text-dark-900">{{ $schedule->destination->name }}</h2>
                <p class="mt-2 text-sm text-dark-400">{{ $schedule->destination->location }}</p>
                <div class="my-5 h-px bg-gray-100"></div>
                <div class="space-y-3 text-sm">
                    <div class="flex justify-between gap-4">
                        <span class="text-dark-400">Harga/orang</span>
                        <span class="font-black text-dark-900">{{ $schedule->formatted_price }}</span>
                    </div>
                    <div class="flex justify-between gap-4">
                        <span class="text-dark-400">Berangkat</span>
                        <span class="font-bold text-dark-900">{{ $schedule->departure_date->format('d M Y') }}</span>
                    </div>
                    <div class="flex justify-between gap-4">
                        <span class="text-dark-400">Pulang</span>
                        <span class="font-bold text-dark-900">{{ $schedule->return_date->format('d M Y') }}</span>
                    </div>
                </div>
            </aside>
        </div>
    </div>
</section>
@endsection
