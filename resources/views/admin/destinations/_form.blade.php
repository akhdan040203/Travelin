{{-- Destination Form Partial --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    {{-- Main Content --}}
    <div class="lg:col-span-2 space-y-6">
        {{-- Basic Info --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h2 class="font-bold text-dark-900 mb-4">Informasi Dasar</h2>
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-dark-700 mb-1.5">Nama Destinasi *</label>
                    <input type="text" name="name" value="{{ old('name', $destination->name ?? '') }}" required
                           class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20">
                    @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-dark-700 mb-1.5">Kategori *</label>
                        <select name="category_id" required class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm bg-white focus:border-primary-500">
                            <option value="">Pilih Kategori</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ old('category_id', $destination->category_id ?? '') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                            @endforeach
                        </select>
                        @error('category_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-dark-700 mb-1.5">Harga (Rp) *</label>
                        <input type="number" name="price" value="{{ old('price', $destination->price ?? '') }}" required
                               class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20">
                        @error('price') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-dark-700 mb-1.5">Lokasi *</label>
                        <input type="text" name="location" value="{{ old('location', $destination->location ?? '') }}" required
                               class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-dark-700 mb-1.5">Provinsi</label>
                        <input type="text" name="province" value="{{ old('province', $destination->province ?? '') }}"
                               class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-dark-700 mb-1.5">Durasi (hari) *</label>
                        <input type="number" name="duration_days" value="{{ old('duration_days', $destination->duration_days ?? 1) }}" required min="1"
                               class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-dark-700 mb-1.5">Deskripsi Singkat</label>
                    <textarea name="short_description" rows="2" class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 resize-none">{{ old('short_description', $destination->short_description ?? '') }}</textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-dark-700 mb-1.5">Deskripsi Lengkap *</label>
                    <textarea name="description" rows="6" required class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 resize-none">{{ old('description', $destination->description ?? '') }}</textarea>
                    @error('description') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        {{-- Included / Excluded --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h2 class="font-bold text-dark-900 mb-4">Fasilitas</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-dark-700 mb-1.5">Yang Termasuk <span class="text-dark-400 text-xs">(satu per baris)</span></label>
                    <textarea name="included" rows="5" class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 resize-none" placeholder="Transport&#10;Hotel 2 malam&#10;Makan 3x sehari">{{ old('included', isset($destination) && $destination->included ? implode("\n", $destination->included) : '') }}</textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-dark-700 mb-1.5">Tidak Termasuk <span class="text-dark-400 text-xs">(satu per baris)</span></label>
                    <textarea name="excluded" rows="5" class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 resize-none" placeholder="Tiket pesawat&#10;Pengeluaran pribadi">{{ old('excluded', isset($destination) && $destination->excluded ? implode("\n", $destination->excluded) : '') }}</textarea>
                </div>
            </div>
        </div>

        {{-- Itinerary --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h2 class="font-bold text-dark-900 mb-4">Itinerary</h2>
            <div id="itinerary-container" class="space-y-3">
                @php
                    $itinerary = old('itinerary_days') ? collect(old('itinerary_days'))->map(function($d, $i) {
                        return ['day' => $d, 'title' => old('itinerary_titles')[$i] ?? '', 'description' => old('itinerary_descriptions')[$i] ?? ''];
                    })->toArray() : ($destination->itinerary ?? []);
                @endphp
                @forelse($itinerary as $i => $item)
                <div class="flex gap-3 items-start itinerary-row">
                    <input type="text" name="itinerary_days[]" value="{{ $item['day'] ?? '' }}" placeholder="D1" class="w-16 px-3 py-2.5 rounded-xl border border-gray-200 text-sm text-center focus:border-primary-500">
                    <input type="text" name="itinerary_titles[]" value="{{ $item['title'] ?? '' }}" placeholder="Judul" class="w-40 px-3 py-2.5 rounded-xl border border-gray-200 text-sm focus:border-primary-500">
                    <input type="text" name="itinerary_descriptions[]" value="{{ $item['description'] ?? '' }}" placeholder="Deskripsi aktivitas" class="flex-1 px-3 py-2.5 rounded-xl border border-gray-200 text-sm focus:border-primary-500">
                    <button type="button" onclick="this.parentElement.remove()" class="w-9 h-9 rounded-lg bg-red-50 flex items-center justify-center hover:bg-red-100 flex-shrink-0">
                    </button>
                </div>
                @empty
                <div class="flex gap-3 items-start itinerary-row">
                    <input type="text" name="itinerary_days[]" placeholder="D1" class="w-16 px-3 py-2.5 rounded-xl border border-gray-200 text-sm text-center focus:border-primary-500">
                    <input type="text" name="itinerary_titles[]" placeholder="Judul" class="w-40 px-3 py-2.5 rounded-xl border border-gray-200 text-sm focus:border-primary-500">
                    <input type="text" name="itinerary_descriptions[]" placeholder="Deskripsi aktivitas" class="flex-1 px-3 py-2.5 rounded-xl border border-gray-200 text-sm focus:border-primary-500">
                    <button type="button" onclick="this.parentElement.remove()" class="w-9 h-9 rounded-lg bg-red-50 flex items-center justify-center hover:bg-red-100 flex-shrink-0">
                    </button>
                </div>
                @endforelse
            </div>
            <button type="button" onclick="addItinerary()" class="mt-3 text-sm text-primary-500 font-semibold hover:text-primary-600 flex items-center gap-1">
                Tambah Hari
            </button>
        </div>
    </div>

    {{-- Sidebar --}}
    <div class="space-y-6">
        {{-- Featured Image Upload --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h2 class="font-bold text-dark-900 mb-4">Gambar Utama</h2>
            <div class="space-y-3">
                @if(isset($destination) && $destination->featured_image)
                <div class="relative rounded-xl overflow-hidden aspect-video bg-gray-100">
                    <img src="{{ asset('storage/' . $destination->featured_image) }}" class="w-full h-full object-cover" alt="Current">
                    <div class="absolute inset-0 bg-black/40 flex items-center justify-center opacity-0 hover:opacity-100 transition-opacity">
                        <span class="text-white text-xs font-medium">Ganti gambar di bawah</span>
                    </div>
                </div>
                @endif
                <label class="block cursor-pointer">
                    <div class="border-2 border-dashed border-gray-200 rounded-xl p-6 text-center hover:border-primary-500 transition-colors" id="upload-area">
                        <p class="text-dark-400 text-sm" id="upload-text">Klik untuk upload gambar</p>
                        <p class="text-dark-300 text-xs mt-1">JPG, PNG, WebP (Max 5MB)</p>
                    </div>
                    <input type="file" name="featured_image" accept="image/*" class="hidden" onchange="previewImage(this)">
                </label>
                <div id="preview-container" class="hidden rounded-xl overflow-hidden aspect-video bg-gray-100">
                    <img id="preview-image" class="w-full h-full object-cover" alt="Preview">
                </div>
                @error('featured_image') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        {{-- Options --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h2 class="font-bold text-dark-900 mb-4">Opsi</h2>
            <div class="space-y-3">
                <label class="flex items-center gap-3 cursor-pointer">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $destination->is_active ?? true) ? 'checked' : '' }}
                           class="w-5 h-5 rounded-lg border-gray-300 text-primary-500 focus:ring-primary-500">
                    <span class="text-sm text-dark-700">Aktif (tampil di website)</span>
                </label>
                <label class="flex items-center gap-3 cursor-pointer">
                    <input type="checkbox" name="is_featured" value="1" {{ old('is_featured', $destination->is_featured ?? false) ? 'checked' : '' }}
                           class="w-5 h-5 rounded-lg border-gray-300 text-primary-500 focus:ring-primary-500">
                    <span class="text-sm text-dark-700">Featured (tampil di homepage)</span>
                </label>
            </div>
        </div>

        {{-- Submit --}}
        <button type="submit" class="btn-primary w-full flex items-center justify-center gap-2 text-sm">
            {{ isset($destination) ? 'Perbarui Destinasi' : 'Simpan Destinasi' }}
        </button>
    </div>
</div>

@push('scripts')
<script>
function addItinerary() {
    const container = document.getElementById('itinerary-container');
    const count = container.querySelectorAll('.itinerary-row').length + 1;
    container.insertAdjacentHTML('beforeend', `
        <div class="flex gap-3 items-start itinerary-row">
            <input type="text" name="itinerary_days[]" value="D${count}" placeholder="D${count}" class="w-16 px-3 py-2.5 rounded-xl border border-gray-200 text-sm text-center focus:border-primary-500">
            <input type="text" name="itinerary_titles[]" placeholder="Judul" class="w-40 px-3 py-2.5 rounded-xl border border-gray-200 text-sm focus:border-primary-500">
            <input type="text" name="itinerary_descriptions[]" placeholder="Deskripsi aktivitas" class="flex-1 px-3 py-2.5 rounded-xl border border-gray-200 text-sm focus:border-primary-500">
            <button type="button" onclick="this.parentElement.remove()" class="w-9 h-9 rounded-lg bg-red-50 flex items-center justify-center hover:bg-red-100 flex-shrink-0">
            </button>
        </div>
    `);
}

function previewImage(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('preview-image').src = e.target.result;
            document.getElementById('preview-container').classList.remove('hidden');
            document.getElementById('upload-text').textContent = input.files[0].name;
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endpush
