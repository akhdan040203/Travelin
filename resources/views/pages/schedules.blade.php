@extends('layouts.main')

@section('title', 'Jadwal Keberangkatan - Travelin')

@section('content')
{{-- Page Header --}}
<section class="pt-32 pb-12 bg-gradient-to-br from-gray-50 to-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h1 class="section-title text-4xl">Jadwal Keberangkatan</h1>
        <p class="section-subtitle mx-auto">Pilih jadwal terbaik untuk petualangan kamu</p>

        {{-- Filter --}}
        @php
            $selectedDestination = $destinations->firstWhere('id', (int) request('destination'));
        @endphp
        <form action="{{ route('schedules') }}" method="GET" class="relative mt-8 mx-auto max-w-6xl rounded-[28px] bg-white px-5 py-6 md:px-7 md:py-6 shadow-2xl shadow-black/10 border border-gray-100/70" style="z-index: 1000;">
            <div class="mb-5 flex items-start justify-between gap-4 text-left">
                <div>
                    <p class="text-xs text-dark-300 font-medium">Your Schedule</p>
                    <p class="text-sm md:text-base font-bold text-dark-900 mt-1">{{ $selectedDestination->name ?? 'Semua Destinasi' }}</p>
                </div>
                @if(request()->filled('departure_date') || request()->filled('destination'))
                    <a href="{{ route('schedules') }}" class="hidden md:inline-flex items-center gap-2 text-[11px] font-semibold text-dark-300 hover:text-primary-500 transition-colors">
                        Reset
                        <span>›</span>
                    </a>
                @endif
            </div>
            <div class="grid grid-cols-1 md:grid-cols-[1fr_1.4fr_auto] items-end gap-4 md:gap-3">
                <div class="min-w-0 space-y-2 text-left">
                    <label for="departure_date" class="block text-[11px] font-medium text-dark-300 pl-4">Tanggal</label>
                    <div class="relative group">
                        <input type="date" id="departure_date" name="departure_date" value="{{ request('departure_date') }}"
                               class="h-12 w-full px-4 rounded-full border border-gray-200 bg-white text-xs font-semibold text-dark-900 focus:ring-2 focus:ring-primary-500/15 focus:border-primary-200 shadow-sm">
                    </div>
                </div>

                <div class="hidden"></div>

                <div class="min-w-0 space-y-2 text-left">
                    <label class="block text-[11px] font-medium text-dark-300 pl-4">Destinasi</label>
                    <div class="relative group">
                        <div class="absolute left-4 top-1/2 -translate-y-1/2 text-dark-900 pointer-events-none">
                            <svg class="w-3 h-3 md:w-4 md:h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 7h18M6 7v13m12-13v13M9 7V4h6v3"/></svg>
                        </div>
                        <button type="button" onclick="toggleScheduleDropdown('destination', event)"
                                class="h-12 w-full pl-10 pr-4 bg-white border border-gray-200 rounded-full text-xs font-semibold text-dark-900 text-left flex items-center justify-between gap-2 focus:ring-2 focus:ring-primary-500/15 focus:border-primary-200 transition-all shadow-sm">
                            <span id="schedule-selected-destination" class="truncate">{{ $selectedDestination->name ?? 'Semua Destinasi' }}</span>
                            <svg class="text-dark-300 transition-transform duration-300 flex-shrink-0" id="schedule-arrow-destination" style="width: 12px; height: 12px;" fill="none" stroke="currentColor" stroke-width="2.4" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                        </button>

                        <div id="schedule-list-destination" class="hidden absolute top-full left-0 right-0 mt-2 bg-white rounded-xl shadow-2xl border border-gray-100 overflow-hidden z-[120] py-1 max-h-60 overflow-y-auto">
                            <div class="px-4 py-2 text-[10px] font-bold text-dark-200 uppercase tracking-widest bg-gray-50/50">Select Destination</div>
                            <div onclick="selectScheduleOption('destination', '', 'Semua Destinasi')" class="px-4 py-2.5 text-xs font-semibold cursor-pointer transition-colors {{ request('destination', '') === '' ? 'bg-red-50 text-primary-600' : 'text-dark-600 hover:bg-red-50 hover:text-primary-600' }}">Semua Destinasi</div>
                            @foreach($destinations as $destination)
                                <div onclick="selectScheduleOption('destination', '{{ $destination->id }}', '{{ $destination->name }}')" class="px-4 py-2.5 text-xs font-semibold cursor-pointer transition-colors {{ (string) request('destination') === (string) $destination->id ? 'bg-red-50 text-primary-600' : 'text-dark-600 hover:bg-red-50 hover:text-primary-600' }}">{{ $destination->name }}</div>
                            @endforeach
                        </div>
                        <input id="schedule-input-destination" type="hidden" name="destination" value="{{ request('destination') }}">
                    </div>
                </div>

                <button type="submit" aria-label="Search" class="w-full md:w-28 h-12 bg-primary-500 text-white rounded-full font-semibold text-xs flex items-center justify-center gap-2 hover:bg-primary-600 transition-all shadow-lg shadow-primary-500/25 group active:scale-95">
                    <span>Search</span>
                    <svg class="hidden md:block w-3.5 h-3.5 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" stroke-width="2.8" viewBox="0 0 24 24">
                        <circle cx="11" cy="11" r="7"/>
                        <path stroke-linecap="round" d="M20 20l-4.2-4.2"/>
                    </svg>
                </button>

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

@push('scripts')
<script>
    function toggleScheduleDropdown(name, event) {
        event.stopPropagation();

        const list = document.getElementById('schedule-list-' + name);
        const arrow = document.getElementById('schedule-arrow-' + name);
        if (!list || !arrow) return;

        document.querySelectorAll('[id^="schedule-list-"]').forEach(el => {
            if (el.id !== 'schedule-list-' + name) el.classList.add('hidden');
        });
        document.querySelectorAll('[id^="schedule-arrow-"]').forEach(el => {
            if (el.id !== 'schedule-arrow-' + name) el.classList.remove('rotate-180');
        });

        list.classList.toggle('hidden');
        arrow.classList.toggle('rotate-180');
    }

    function selectScheduleOption(name, value, label) {
        const selected = document.getElementById('schedule-selected-' + name);
        const input = document.getElementById('schedule-input-' + name);
        const list = document.getElementById('schedule-list-' + name);
        const arrow = document.getElementById('schedule-arrow-' + name);

        if (selected) selected.innerText = label;
        if (input) input.value = value;
        if (list) list.classList.add('hidden');
        if (arrow) arrow.classList.remove('rotate-180');
    }

    window.addEventListener('click', () => {
        document.querySelectorAll('[id^="schedule-list-"]').forEach(el => el.classList.add('hidden'));
        document.querySelectorAll('[id^="schedule-arrow-"]').forEach(el => el.classList.remove('rotate-180'));
    });
</script>
@endpush
