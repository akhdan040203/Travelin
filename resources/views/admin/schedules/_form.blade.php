{{-- Schedule Form Partial --}}
<div class="max-w-2xl">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-4">
        <div>
            <label class="block text-sm font-medium text-dark-700 mb-1.5">Destinasi *</label>
            <select name="destination_id" required class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm bg-white focus:border-primary-500">
                <option value="">Pilih Destinasi</option>
                @foreach($destinations as $dest)
                    <option value="{{ $dest->id }}" {{ old('destination_id', $schedule->destination_id ?? '') == $dest->id ? 'selected' : '' }}>{{ $dest->name }}</option>
                @endforeach
            </select>
            @error('destination_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-dark-700 mb-1.5">Tanggal Berangkat *</label>
                <input type="date" name="departure_date" value="{{ old('departure_date', isset($schedule) ? $schedule->departure_date->format('Y-m-d') : '') }}" required
                       class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20">
                @error('departure_date') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-dark-700 mb-1.5">Tanggal Kembali *</label>
                <input type="date" name="return_date" value="{{ old('return_date', isset($schedule) ? $schedule->return_date->format('Y-m-d') : '') }}" required
                       class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20">
                @error('return_date') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-dark-700 mb-1.5">Kuota Peserta *</label>
                <input type="number" name="quota" value="{{ old('quota', $schedule->quota ?? 20) }}" required min="1"
                       class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20">
                @error('quota') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-dark-700 mb-1.5">Harga Khusus (kosongkan = harga destinasi)</label>
                <input type="number" name="price" value="{{ old('price', $schedule->price ?? '') }}"
                       class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20" placeholder="Opsional">
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-dark-700 mb-1.5">Meeting Point</label>
            <input type="text" name="meeting_point" value="{{ old('meeting_point', $schedule->meeting_point ?? '') }}"
                   class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20" placeholder="Contoh: Bandara Soekarno-Hatta Terminal 3">
        </div>

        <div>
            <label class="block text-sm font-medium text-dark-700 mb-1.5">Status *</label>
            <select name="status" required class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm bg-white focus:border-primary-500">
                <option value="open" {{ old('status', $schedule->status ?? 'open') === 'open' ? 'selected' : '' }}>Open</option>
                <option value="closed" {{ old('status', $schedule->status ?? '') === 'closed' ? 'selected' : '' }}>Closed</option>
                <option value="full" {{ old('status', $schedule->status ?? '') === 'full' ? 'selected' : '' }}>Full</option>
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-dark-700 mb-1.5">Catatan</label>
            <textarea name="notes" rows="3" class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm resize-none focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20">{{ old('notes', $schedule->notes ?? '') }}</textarea>
        </div>

        <button type="submit" class="btn-primary flex items-center gap-2 text-sm">
            {{ isset($schedule) ? 'Perbarui Jadwal' : 'Simpan Jadwal' }}
        </button>
    </div>
</div>
