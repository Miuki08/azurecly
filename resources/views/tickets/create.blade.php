<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-sea-blue-800 leading-tight">
                Tambah Berita Baru
            </h2>
            <a href="{{ route('tickets.index') }}" class="inline-flex items-center text-gray-600 hover:text-gray-800 text-sm">
                <i data-lucide="arrow-left" class="w-4 h-4 mr-1"></i>
                Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <form action="{{ route('tickets.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                @csrf

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
                    {{-- KIRI: Informasi Utama (lebih lebar) --}}
                    <div class="lg:col-span-2 space-y-5">
                        <div class="bg-white rounded-lg shadow border border-gray-100">
                            <div class="px-5 py-4 border-b border-gray-100 flex items-center">
                                <div class="p-2 bg-sea-blue-50 rounded-lg mr-3">
                                    <i data-lucide="file-text" class="w-4 h-4 text-sea-blue-700"></i>
                                </div>
                                <div>
                                    <h3 class="text-sm font-semibold text-gray-800">Informasi Utama</h3>
                                    <p class="text-xs text-gray-500">Judul, isi berita, dan gambar utama.</p>
                                </div>
                            </div>

                            <div class="px-5 py-5 space-y-4">
                                {{-- Judul --}}
                                <div>
                                    <label for="title" class="flex items-center justify-between text-sm font-medium text-gray-700 mb-1">
                                        <span>Judul Berita</span>
                                        <span class="text-[11px] text-red-500">required</span>
                                    </label>
                                    <input type="text" name="title" id="title" value="{{ old('title') }}" required
                                           class="w-full rounded-lg border-gray-300 focus:border-sea-blue-500 focus:ring-sea-blue-500 text-sm">
                                    @error('title')
                                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- Deskripsi --}}
                                <div>
                                    <label for="description" class="flex items-center justify-between text-sm font-medium text-gray-700 mb-1">
                                        <span>Deskripsi</span>
                                        <span class="text-[11px] text-red-500">required</span>
                                    </label>
                                    <textarea name="description" id="description" rows="8" required
                                              class="w-full rounded-lg border-gray-300 focus:border-sea-blue-500 focus:ring-sea-blue-500 text-sm">{{ old('description') }}</textarea>
                                    @error('description')
                                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- Gambar besar --}}
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        Gambar Berita
                                    </label>
                                    <div id="image-preview" class="mt-2 grid grid-cols-1 md:grid-cols-[auto,1fr] gap-4 items-start">
                                        <div class="w-full md:w-56 h-40 rounded-lg bg-gray-50 border border-dashed border-gray-300 flex items-center justify-center text-[11px] text-gray-400 overflow-hidden">
                                            <span id="image-placeholder-text">Preview</span>
                                        </div>
                                        <div>
                                            <label class="cursor-pointer inline-flex">
                                                <span class="px-3 py-2 bg-white border border-gray-300 rounded-lg text-xs text-gray-700 hover:bg-gray-50 inline-flex items-center gap-2">
                                                    <i data-lucide="image" class="w-4 h-4"></i>
                                                    Pilih gambar
                                                </span>
                                                <input type="file" name="image" id="image" accept="image/*" class="hidden">
                                            </label>
                                            @error('image')
                                                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                            @enderror
                                            <p class="text-[11px] text-gray-400 mt-1">
                                                Opsional. PNG/JPG maks. 2MB.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Klasifikasi & Lokasi --}}
                    <div class="lg:col-span-1 space-y-5">
                        {{-- Klasifikasi & Sentimen --}}
                        <div class="bg-white rounded-lg shadow border border-gray-100">
                            <div class="px-5 py-4 border-b border-gray-100 flex items-center">
                                <div class="p-2 bg-sea-blue-50 rounded-lg mr-3">
                                    <i data-lucide="sliders" class="w-4 h-4 text-sea-blue-700"></i>
                                </div>
                                <div>
                                    <h3 class="text-sm font-semibold text-gray-800">Klasifikasi & Sentimen</h3>
                                    <p class="text-xs text-gray-500">Kategori, sentiment, dan priority.</p>
                                </div>
                            </div>

                            <div class="px-5 py-5 space-y-4">
                                {{-- Kategori --}}
                                <div>
                                    <label for="category" class="flex items-center justify-between text-sm font-medium text-gray-700 mb-1">
                                        <span>Kategori</span>
                                        <span class="text-[11px] text-red-500">required</span>
                                    </label>
                                    <select name="category" id="category" required
                                            class="w-full rounded-lg border-gray-300 focus:border-sea-blue-500 focus:ring-sea-blue-500 text-sm">
                                        <option value="">Pilih Kategori</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}"
                                                {{ old('category') == $category->id ? 'selected' : '' }}>
                                                {{ $category->Name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('category')
                                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- Sentiment --}}
                                <div>
                                    <label for="sentiment" class="flex items-center justify-between text-sm font-medium text-gray-700 mb-1">
                                        <span>Sentiment</span>
                                        <span class="text-[11px] text-red-500">required</span>
                                    </label>
                                    <select name="sentiment" id="sentiment" required
                                            class="w-full rounded-lg border-gray-300 focus:border-sea-blue-500 focus:ring-sea-blue-500 text-sm">
                                        <option value="">Pilih Sentiment</option>
                                        <option value="positive" {{ old('sentiment') == 'positive' ? 'selected' : '' }}>Positive</option>
                                        <option value="neutral"  {{ old('sentiment') == 'neutral'  ? 'selected' : '' }}>Neutral</option>
                                        <option value="negative" {{ old('sentiment') == 'negative' ? 'selected' : '' }}>Negative</option>
                                    </select>
                                    @error('sentiment')
                                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- Priority --}}
                                <div>
                                    <label for="priority" class="flex items-center justify-between text-sm font-medium text-gray-700 mb-1">
                                        <span>Priority</span>
                                        <span class="text-[11px] text-red-500">required</span>
                                    </label>
                                    <select name="priority" id="priority" required
                                            class="w-full rounded-lg border-gray-300 focus:border-sea-blue-500 focus:ring-sea-blue-500 text-sm">
                                        <option value="">Pilih Priority</option>
                                        <option value="high"   {{ old('priority') == 'high'   ? 'selected' : '' }}>High</option>
                                        <option value="medium" {{ old('priority') == 'medium' ? 'selected' : '' }}>Medium</option>
                                        <option value="low"    {{ old('priority') == 'low'    ? 'selected' : '' }}>Low</option>
                                    </select>
                                    @error('priority')
                                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- Aktor --}}
                                <div>
                                    <label for="actor" class="block text-sm font-medium text-gray-700 mb-1">
                                        Aktor/Pelaku
                                    </label>
                                    <input type="text" name="actor" id="actor" value="{{ old('actor') }}"
                                           class="w-full rounded-lg border-gray-300 focus:border-sea-blue-500 focus:ring-sea-blue-500 text-sm">
                                    @error('actor')
                                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- Tag --}}
                                <div>
                                    <label for="tag" class="block text-sm font-medium text-gray-700 mb-1">
                                        Tag
                                    </label>
                                    <input type="text" name="tag" id="tag" value="{{ old('tag') }}"
                                           placeholder="misal: kebakaran, bandara, darurat"
                                           class="w-full rounded-lg border-gray-300 focus:border-sea-blue-500 focus:ring-sea-blue-500 text-sm">
                                    @error('tag')
                                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Lokasi & Waktu --}}
                        <div class="bg-white rounded-lg shadow border border-gray-100">
                            <div class="px-5 py-4 border-b border-gray-100 flex items-center">
                                <div class="p-2 bg-sea-blue-50 rounded-lg mr-3">
                                    <i data-lucide="map-pin" class="w-4 h-4 text-sea-blue-700"></i>
                                </div>
                                <div>
                                    <h3 class="text-sm font-semibold text-gray-800">Lokasi & Waktu</h3>
                                    <p class="text-xs text-gray-500">Detail lokasi kejadian dan waktu publikasi.</p>
                                </div>
                            </div>

                            <div class="px-5 py-5 space-y-4">
                                {{-- Region --}}
                                <div>
                                    <label for="region" class="block text-sm font-medium text-gray-700 mb-1">
                                        Region
                                    </label>
                                    <select name="region" id="region"
                                            class="w-full rounded-lg border-gray-300 focus:border-sea-blue-500 focus:ring-sea-blue-500 text-sm">
                                        <option value="">Pilih Region</option>
                                        @foreach($regions as $region)
                                            <option value="{{ $region->id }}"
                                                {{ old('region') == $region->id ? 'selected' : '' }}>
                                                {{ $region->Name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('region')
                                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- Lokasi --}}
                                <div>
                                    <label for="location" class="block text-sm font-medium text-gray-700 mb-1">
                                        Lokasi
                                    </label>
                                    <input type="text" name="location" id="location" value="{{ old('location') }}"
                                           placeholder="misal: Bandara Soekarno-Hatta"
                                           class="w-full rounded-lg border-gray-300 focus:border-sea-blue-500 focus:ring-sea-blue-500 text-sm">
                                    @error('location')
                                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- Latitude --}}
                                <div>
                                    <label for="latitude" class="block text-sm font-medium text-gray-700 mb-1">
                                        Latitude
                                    </label>
                                    <input type="text" name="latitude" id="latitude" value="{{ old('latitude') }}"
                                           placeholder="contoh: -6.2088"
                                           class="w-full rounded-lg border-gray-300 focus:border-sea-blue-500 focus:ring-sea-blue-500 text-sm">
                                    @error('latitude')
                                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- Longitude --}}
                                <div>
                                    <label for="longitude" class="block text-sm font-medium text-gray-700 mb-1">
                                        Longitude
                                    </label>
                                    <input type="text" name="longitude" id="longitude" value="{{ old('longitude') }}"
                                           placeholder="contoh: 106.8456"
                                           class="w-full rounded-lg border-gray-300 focus:border-sea-blue-500 focus:ring-sea-blue-500 text-sm">
                                    @error('longitude')
                                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- Tanggal Publikasi --}}
                                <div>
                                    <label for="published_at" class="block text-sm font-medium text-gray-700 mb-1">
                                        Tanggal Publikasi
                                    </label>
                                    <input type="datetime-local" name="published_at" id="published_at" value="{{ old('published_at') }}"
                                           class="w-full rounded-lg border-gray-300 focus:border-sea-blue-500 focus:ring-sea-blue-500 text-sm">
                                    @error('published_at')
                                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Tombol submit di bawah, full width --}}
                <div class="flex justify-end gap-3 pt-3">
                    <a href="{{ route('tickets.index') }}"
                       class="px-4 py-2 border border-gray-300 rounded-lg text-sm text-gray-700 hover:bg-gray-50 transition">
                        Batal
                    </a>
                    <button type="submit"
                            class="px-4 py-2 bg-sea-blue-600 hover:bg-sea-blue-700 text-white rounded-lg text-sm font-medium inline-flex items-center gap-1 transition">
                        <i data-lucide="save" class="w-4 h-4"></i>
                        Simpan Berita
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script src="https://unpkg.com/lucide@latest"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }

            const fileInput = document.getElementById('image');
            const previewBox = document.querySelector('.w-full.md\\:w-56.h-40.rounded-lg'); // atau kasih id sendiri
            const placeholderText = document.getElementById('image-placeholder-text');

            if (fileInput && previewBox) {
                fileInput.addEventListener('change', function (e) {
                    const file = e.target.files[0];
                    if (!file) return;

                    const reader = new FileReader();
                    reader.onload = function (event) {
                        previewBox.style.backgroundImage = `url('${event.target.result}')`;
                        previewBox.style.backgroundSize = 'cover';
                        previewBox.style.backgroundPosition = 'center';
                        previewBox.style.borderStyle = 'solid';
                        if (placeholderText) {
                            placeholderText.style.display = 'none';
                        }
                    };
                    reader.readAsDataURL(file);
                });
            }
        });
    </script>
    @endpush
</x-app-layout>