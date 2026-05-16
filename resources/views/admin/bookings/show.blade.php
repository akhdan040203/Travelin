@extends('admin.layouts.app')
@section('page_title', 'Detail Booking #' . $booking->booking_code)

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.bookings.index') }}" class="text-primary-500 text-sm font-bold hover:text-primary-600 transition-colors">← Kembali ke Daftar</a>
</div>

{{-- Tracker Section --}}
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-6">
    <h2 class="text-sm font-black text-dark-900 uppercase tracking-widest mb-8 text-center">Progres Perjalanan</h2>
    
    @php
        $flow = ['pending', 'confirmed', 'ongoing', 'completed'];
        $isCancelled = in_array($booking->status, ['cancelled', 'refunded']);
        
        if ($booking->status === 'paid') {
            $currentIdx = 0.5;
        } else {
            $currentIdx = array_search($booking->status, $flow);
            if ($currentIdx === false) $currentIdx = 0;
        }

        $statusLabels = [
            'pending'   => 'Booking',
            'confirmed' => 'Waiting',
            'ongoing'   => 'Perjalanan',
            'completed' => 'Selesai'
        ];
    @endphp

    <div class="max-w-4xl mx-auto relative flex justify-between px-4">
        {{-- Progress Line --}}
        <div class="absolute top-4 left-4 right-4 h-0.5 bg-gray-100">
             @if(!$isCancelled)
                <div class="absolute top-0 left-0 h-full bg-primary-500 transition-all duration-1000" 
                     style="width: {{ ($currentIdx / (count($flow) - 1)) * 100 }}%"></div>
             @endif
        </div>

        {{-- Steps --}}
        @if(!$isCancelled)
            @foreach($flow as $i => $step)
                <div class="relative flex flex-col items-center flex-1 z-10">
                    <div class="w-9 h-9 rounded-full flex items-center justify-center transition-all border-2
                        {{ $i < $currentIdx ? 'bg-emerald-500 border-emerald-500 text-white' : ($i <= $currentIdx ? 'bg-primary-500 border-primary-500 text-white shadow-xl shadow-primary-500/30' : 'bg-white border-gray-100 text-gray-300') }}">
                        @if($i < $currentIdx)
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                        @elseif($i == $currentIdx)
                            <div class="w-2 h-2 bg-white rounded-full animate-pulse"></div>
                        @else
                            <div class="w-2 h-2 bg-gray-100 rounded-full"></div>
                        @endif
                    </div>
                    <span class="text-[10px] font-black mt-3 uppercase tracking-tighter {{ $i <= $currentIdx ? 'text-dark-900 font-bold' : 'text-gray-400' }}">
                        {{ $statusLabels[$step] }}
                    </span>
                </div>
            @endforeach
        @else
            <div class="relative flex flex-col items-center flex-1 z-10">
                <div class="w-9 h-9 bg-emerald-500 border-2 border-emerald-500 text-white rounded-full flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                </div>
                <span class="text-[10px] font-black mt-3 uppercase text-emerald-600">Booking</span>
            </div>
            <div class="relative flex flex-col items-center flex-1 z-10">
                <div class="w-9 h-9 bg-red-500 border-2 border-red-500 text-white rounded-full flex items-center justify-center shadow-xl shadow-red-500/30">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"/></svg>
                </div>
                <span class="text-[10px] font-black mt-3 uppercase text-red-600 font-bold">{{ $booking->status === 'refunded' ? 'Refunded' : 'Batal' }}</span>
            </div>
            @for($n=0;$n<2;$n++) <div class="flex-1"></div> @endfor
        @endif
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    {{-- Main Info --}}
    <div class="lg:col-span-2 space-y-6">
        {{-- Booking Info --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between mb-6">
                <h2 class="font-black text-dark-900 uppercase text-sm tracking-widest">Data Customer</h2>
                @php
                    $colors = ['pending'=>'bg-yellow-50 text-yellow-700 border-yellow-100','paid'=>'bg-blue-50 text-blue-700 border-blue-100','confirmed'=>'bg-emerald-50 text-emerald-700 border-emerald-100','ongoing'=>'bg-indigo-50 text-indigo-700 border-indigo-100','completed'=>'bg-emerald-50 text-emerald-700 border-emerald-100','cancelled'=>'bg-red-50 text-red-700 border-red-100','refunded'=>'bg-gray-50 text-gray-700 border-gray-100'];
                @endphp
                <span class="px-3 py-1 text-[10px] font-black uppercase tracking-wider rounded-full border {{ $colors[$booking->status] ?? 'bg-gray-100' }}">
                    {{ $booking->status === 'confirmed' ? 'Waiting' : ($booking->status === 'ongoing' ? 'Perjalanan' : $booking->status_label) }}
                </span>
            </div>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-y-6 gap-x-4">
                <div><span class="text-dark-300 text-[10px] font-black uppercase block mb-1">Kode Booking</span><span class="font-bold text-primary-500 font-mono">{{ $booking->booking_code }}</span></div>
                <div><span class="text-dark-300 text-[10px] font-black uppercase block mb-1">Tanggal Pesan</span><span class="font-bold text-dark-900">{{ $booking->created_at->format('d M Y, H:i') }}</span></div>
                <div><span class="text-dark-300 text-[10px] font-black uppercase block mb-1">Nama Kontak</span><span class="font-bold text-dark-900">{{ $booking->contact_name }}</span></div>
                <div><span class="text-dark-300 text-[10px] font-black uppercase block mb-1">Email</span><span class="font-bold text-dark-900">{{ $booking->contact_email }}</span></div>
                <div><span class="text-dark-300 text-[10px] font-black uppercase block mb-1">Telepon</span><span class="font-bold text-dark-900">{{ $booking->contact_phone }}</span></div>
                <div><span class="text-dark-300 text-[10px] font-black uppercase block mb-1">Metode Pembayaran</span><span class="font-bold text-dark-900 uppercase text-xs">{{ $booking->payment_method }}</span></div>
            </div>
        </div>

        {{-- Trip Detail --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h2 class="font-black text-dark-900 uppercase text-sm tracking-widest mb-6">Detail Destinasi</h2>
            <div class="flex items-center gap-6">
                <div class="w-20 h-20 rounded-2xl overflow-hidden bg-gray-100 flex-shrink-0 shadow-sm">
                    @if($booking->schedule->destination->featured_image)
                        <img src="{{ asset('storage/' . $booking->schedule->destination->featured_image) }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full bg-gradient-to-br from-primary-400 to-primary-600"></div>
                    @endif
                </div>
                <div>
                    <h3 class="font-black text-dark-900 text-lg leading-tight">{{ $booking->schedule->destination->name }}</h3>
                    <p class="text-dark-400 text-sm font-medium mt-1">{{ $booking->schedule->destination->location }}</p>
                    <div class="flex items-center gap-3 mt-2 font-bold text-xs text-primary-500">
                        <span>{{ $booking->schedule->departure_date->format('d M Y') }}</span>
                        <span class="text-gray-300">•</span>
                        <span>{{ $booking->participants }} Peserta</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Sidebar Admin --}}
    <div class="space-y-6">
        {{-- Update Status --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h2 class="font-black text-dark-900 uppercase text-sm tracking-widest mb-6">Kelola Status</h2>
            <form action="{{ route('admin.bookings.updateStatus', $booking) }}" method="POST" class="space-y-4">
                @csrf @method('PATCH')
                <select name="status" class="w-full px-4 py-3.5 rounded-xl border border-gray-200 text-xs font-bold bg-white focus:border-primary-500 focus:ring-4 focus:ring-primary-500/10 transition-all">
                    @foreach(['pending' => 'Booking (Pending)', 'paid' => 'Menunggu Konfirmasi', 'confirmed' => 'Waiting (Siap)', 'ongoing' => 'Perjalanan', 'completed' => 'Selesai', 'cancelled' => 'Dibatalkan', 'refunded' => 'Refund'] as $val => $label)
                        <option value="{{ $val }}" {{ $booking->status === $val ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                <button type="submit" class="w-full bg-dark-900 hover:bg-dark-800 text-white text-[11px] font-black py-4 rounded-xl shadow-lg transition-all uppercase tracking-widest">
                    Update Progres
                </button>
            </form>
        </div>

        {{-- Payment Summary --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h2 class="font-black text-dark-900 uppercase text-sm tracking-widest mb-4">Total Pembayaran</h2>
            <div class="space-y-3">
                <div class="flex justify-between text-xs font-medium text-dark-400"><span>Harga x Peserta</span><span>Rp {{ number_format($booking->price_per_person, 0, ',', '.') }} x {{ $booking->participants }}</span></div>
                <hr class="border-gray-50">
                <div class="flex justify-between items-end">
                    <span class="text-[10px] font-black text-dark-300 uppercase">Total Tagihan</span>
                    <span class="text-xl font-black text-primary-500 leading-none">{{ $booking->formatted_total_price }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
