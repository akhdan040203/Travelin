@extends('layouts.main')

@section('title', 'Jadwal Keberangkatan - Travelin')

@section('content')
{{-- Page Header --}}
<section class="pt-32 pb-12 bg-gradient-to-br from-gray-50 to-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h1 class="section-title text-4xl">Jadwal Keberangkatan</h1>
        <p class="section-subtitle mx-auto">Pilih jadwal terbaik untuk petualangan kamu</p>

        {{-- Filter --}}
        <form action="{{ route('schedules') }}" method="GET" class="mt-8 mx-auto max-w-3xl rounded-2xl bg-white p-3 shadow-2xl shadow-black/5 border border-gray-100">
            <div class="grid grid-cols-1 sm:grid-cols-[1fr_1fr_auto] gap-3">
                <div class="text-left">
                    <label for="departure_date" class="block text-[10px] font-black text-dark-900 uppercase tracking-widest mb-1.5 pl-1">Tanggal Berangkat</label>
                    <input type="date" id="departure_date" name="departure_date" value="{{ request('departure_date') }}"
                           class="w-full h-11 px-4 rounded-xl border-0 bg-gray-50 text-sm font-semibold text-dark-900 focus:ring-2 focus:ring-primary-500/20">
                </div>

                <div class="text-left">
                    <label for="destination" class="block text-[10px] font-black text-dark-900 uppercase tracking-widest mb-1.5 pl-1">Destinasi</label>
                    <select id="destination" name="destination"
                            class="w-full h-11 px-4 rounded-xl border-0 bg-gray-50 text-sm font-semibold text-dark-900 focus:ring-2 focus:ring-primary-500/20">
                        <option value="">Semua Destinasi</option>
                        @foreach($destinations as $destination)
                            <option value="{{ $destination->id }}" {{ (string) request('destination') === (string) $destination->id ? 'selected' : '' }}>
                                {{ $destination->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="flex items-end gap-2">
                    <button type="submit" class="h-11 px-5 rounded-xl bg-dark-900 text-white text-xs font-black uppercase tracking-widest hover:bg-primary-500 transition-all">
                        Filter
                    </button>
                    @if(request()->filled('departure_date') || request()->filled('destination'))
                        <a href="{{ route('schedules') }}" class="h-11 px-4 rounded-xl bg-gray-50 text-dark-500 text-xs font-black uppercase tracking-widest flex items-center hover:bg-gray-100 transition-all">
                            Reset
                        </a>
                    @endif
                </div>
            </div>
        </form>
    </div>
</section>

{{-- Schedule Cards --}}
<section class="py-12 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        @if($schedules->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($schedules as $schedule)
                    <div class="card-travel p-6">
                        {{-- Date Header --}}
                        <div class="flex items-center gap-4 mb-4">
                            <div class="w-16 h-16 bg-primary-50 rounded-2xl flex flex-col items-center justify-center flex-shrink-0">
                                <span class="text-xl font-bold text-primary-500">{{ $schedule->departure_date->format('d') }}</span>
                                <span class="text-xs font-medium text-primary-500 uppercase">{{ $schedule->departure_date->format('M') }}</span>
                            </div>
                            <div class="min-w-0">
                                <h3 class="font-bold text-dark-900 truncate">{{ $schedule->destination->name }}</h3>
                                <p class="text-dark-400 text-sm flex items-center gap-1 mt-0.5">
                                    {{ $schedule->destination->location }}
                                </p>
                            </div>
                        </div>

                        {{-- Info Grid --}}
                        <div class="grid grid-cols-2 gap-3 mb-4">
                            <div class="bg-gray-50 rounded-xl p-3 text-center">
                                <span class="text-xs text-dark-400 block">Berangkat</span>
                                <span class="text-sm font-semibold text-dark-900">{{ $schedule->departure_date->format('d M Y') }}</span>
                            </div>
                            <div class="bg-gray-50 rounded-xl p-3 text-center">
                                <span class="text-xs text-dark-400 block">Pulang</span>
                                <span class="text-sm font-semibold text-dark-900">{{ $schedule->return_date->format('d M Y') }}</span>
                            </div>
                        </div>

                        @if($schedule->meeting_point)
                        <p class="text-dark-400 text-xs flex items-center gap-1 mb-4">
                            Meeting Point: {{ $schedule->meeting_point }}
                        </p>
                        @endif

                        {{-- Progress Bar --}}
                        <div class="mb-4">
                            <div class="flex justify-between text-xs text-dark-400 mb-1">
                                <span>{{ $schedule->booked }} terbooking</span>
                                <span class="{{ $schedule->available_slots <= 5 ? 'text-red-500 font-semibold' : '' }}">
                                    Sisa {{ $schedule->available_slots }}
                                </span>
                            </div>
                            <div class="w-full bg-gray-100 rounded-full h-2">
                                <div class="bg-gradient-to-r from-primary-400 to-primary-500 h-2 rounded-full"
                                     style="width: {{ min(100, ($schedule->booked / max(1, $schedule->quota)) * 100) }}%"></div>
                            </div>
                        </div>

                        {{-- Price & Action --}}
                        <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                            <div>
                                <span class="text-xs text-dark-400">Harga</span>
                                <p class="text-primary-500 font-bold text-lg">{{ $schedule->formatted_price }}</p>
                            </div>
                            <a href="{{ route('destinations.show', $schedule->destination->slug) }}"
                               class="btn-primary !px-5 !py-2.5 text-sm">
                                Detail & Booking
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-12">{{ $schedules->links() }}</div>
        @else
            <div class="text-center py-20">

                <h3 class="text-xl font-bold text-dark-900">Belum ada jadwal tersedia</h3>
                <p class="text-dark-400 mt-2">Jadwal baru akan segera ditambahkan. Stay tuned!</p>
            </div>
        @endif
    </div>
</section>
@endsection
