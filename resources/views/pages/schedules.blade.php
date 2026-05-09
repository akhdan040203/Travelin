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
        <form id="schedule-search-card" action="{{ route('schedules') }}" method="GET" class="relative mt-8 mx-auto max-w-6xl rounded-[28px] bg-white px-5 py-5 md:px-7 md:py-6 shadow-2xl shadow-black/10 border border-gray-100/70 cursor-pointer md:cursor-default transition-all duration-300" style="z-index: 2000;">
            <div class="mb-0 md:mb-5 flex items-start justify-between gap-4 text-left">
                <div>
                    <p class="text-xs text-dark-300 font-medium">Your Schedule</p>
                    <p class="text-sm md:text-base font-bold text-dark-900 mt-1">{{ $selectedDestination->name ?? 'Semua Destinasi' }}</p>
                </div>
                <button type="button" id="schedule-search-toggle" aria-label="Buka filter" class="md:hidden flex h-9 w-9 items-center justify-center rounded-full bg-gray-50 text-dark-400 transition-all duration-300">
                    <svg id="schedule-search-toggle-icon" class="h-4 w-4 transition-transform duration-300" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                @if(request()->filled('departure_date') || request()->filled('destination'))
                    <a href="{{ route('schedules') }}" class="hidden md:inline-flex items-center gap-2 text-[11px] font-semibold text-dark-300 hover:text-primary-500 transition-colors">
                        Reset
                        <span>›</span>
                    </a>
                @endif
            </div>
            <div id="schedule-search-form" class="max-h-0 overflow-hidden opacity-0 mt-0 transition-all duration-300 md:max-h-none md:overflow-visible md:opacity-100 md:mt-0">
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

                        <div id="schedule-list-destination" class="hidden absolute top-full left-0 right-0 mt-2 bg-white rounded-xl shadow-2xl border border-gray-100 overflow-hidden z-[2200] py-1 max-h-60 overflow-y-auto">
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
            </div>
        </form>
    </div>
</section>

{{-- Schedule Cards --}}
<section class="py-12 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        @if($schedules->count() > 0)
            <div class="md:hidden divide-y divide-gray-100 border-y border-gray-100">
                @foreach($schedules as $schedule)
                    <div class="flex items-center gap-4 border-l-4 border-primary-500 bg-white py-5 pl-4 pr-1">
                        <div class="w-14 h-14 bg-primary-50 rounded-2xl flex flex-col items-center justify-center flex-shrink-0">
                            <span class="text-lg font-black text-primary-500">{{ $schedule->departure_date->format('d') }}</span>
                            <span class="text-[10px] font-bold text-primary-500 uppercase">{{ $schedule->departure_date->format('M') }}</span>
                        </div>
                        <a href="{{ route('destinations.show', $schedule->destination->slug) }}" class="min-w-0 flex-1">
                            <h3 class="font-black text-dark-900 leading-tight truncate">{{ $schedule->destination->name }}</h3>
                            <p class="mt-1 text-sm text-dark-400 truncate">{{ $schedule->destination->location }}</p>
                            <div class="mt-2 flex items-center justify-between gap-3">
                                <span class="text-xs font-bold text-primary-500">{{ $schedule->formatted_price }}</span>
                                <span class="text-[10px] font-semibold text-dark-300">Sisa {{ $schedule->available_slots }} slot</span>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>

            <div class="hidden md:grid md:grid-cols-2 lg:grid-cols-3 gap-6">
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
    function setScheduleSearchOpen(isOpen) {
        const card = document.getElementById('schedule-search-card');
        const form = document.getElementById('schedule-search-form');
        const toggle = document.getElementById('schedule-search-toggle');
        const icon = document.getElementById('schedule-search-toggle-icon');
        if (!card || !form) return;

        card.dataset.open = isOpen ? 'true' : 'false';
        toggle?.setAttribute('aria-label', isOpen ? 'Tutup filter' : 'Buka filter');
        icon?.classList.toggle('rotate-180', isOpen);
        card.classList.toggle('py-6', isOpen);
        card.classList.toggle('py-5', !isOpen);
        form.classList.toggle('max-h-[520px]', isOpen);
        form.classList.toggle('overflow-visible', isOpen);
        form.classList.toggle('opacity-100', isOpen);
        form.classList.toggle('mt-5', isOpen);
        form.classList.toggle('max-h-0', !isOpen);
        form.classList.toggle('overflow-hidden', !isOpen);
        form.classList.toggle('opacity-0', !isOpen);
        form.classList.toggle('mt-0', !isOpen);
    }

    document.addEventListener('click', (event) => {
        const card = document.getElementById('schedule-search-card');
        const toggle = event.target.closest('#schedule-search-toggle');
        if (!card || window.innerWidth >= 768) return;

        if (toggle) {
            event.stopPropagation();
            setScheduleSearchOpen(card.dataset.open !== 'true');
            return;
        }

        if (event.target.closest('#schedule-search-form')) {
            event.stopPropagation();
            return;
        }

        if (event.target.closest('#schedule-search-card') && card.dataset.open !== 'true') {
            setScheduleSearchOpen(true);
        }
    });

    window.addEventListener('resize', () => {
        const card = document.getElementById('schedule-search-card');
        const form = document.getElementById('schedule-search-form');
        const icon = document.getElementById('schedule-search-toggle-icon');
        if (!card || !form) return;

        if (window.innerWidth >= 768) {
            card.dataset.open = 'false';
            icon?.classList.remove('rotate-180');
            card.classList.remove('py-5');
            card.classList.add('py-6');
            form.classList.remove('max-h-0', 'overflow-hidden', 'opacity-0', 'mt-0');
            form.classList.add('max-h-[520px]', 'overflow-visible', 'opacity-100', 'mt-5');
        } else if (card.dataset.open !== 'true') {
            setScheduleSearchOpen(false);
        }
    });

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
