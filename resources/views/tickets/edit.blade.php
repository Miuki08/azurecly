<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-sea-blue-800 leading-tight">
                Edit Berita
            </h2>
            <a href="{{ route('tickets.index') }}" class="inline-flex items-center text-gray-600 hover:text-gray-800 text-sm">
                <i data-lucide="arrow-left" class="w-4 h-4 mr-1"></i>
                Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <form action="{{ route('tickets.update', $ticket->id) }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
                    {{-- Informasi Utama + Media --}}
                    <div class="lg:col-span-2 space-y-5">
                        {{-- Card: Informasi Utama --}}
                        <div class="bg-white rounded-lg shadow border border-gray-100">
                            <div class="px-5 py-4 border-b border-gray-100 flex items-center">
                                <div class="p-2 bg-sea-blue-50 rounded-lg mr-3">
                                    <i data-lucide="file-text" class="w-4 h-4 text-sea-blue-700"></i>
                                </div>
                                <div>
                                    <h3 class="text-sm font-semibold text-gray-800">Informasi Utama</h3>
                                    <p class="text-xs text-gray-500">Judul, isi berita, dan media.</p>
                                </div>
                            </div>

                            <div class="px-5 py-5 space-y-4">
                                {{-- Judul --}}
                                <div>
                                    <label for="title" class="flex items-center justify-between text-sm font-medium text-gray-700 mb-1">
                                        <span>Judul Berita</span>
                                        <span class="text-[11px] text-red-500">required</span>
                                    </label>
                                    <input type="text"
                                           name="title"
                                           id="title"
                                           value="{{ old('title', $ticket->Title) }}"
                                           required
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
                                    <textarea name="description"
                                              id="description"
                                              rows="8"
                                              required
                                              class="w-full rounded-lg border-gray-300 focus:border-sea-blue-500 focus:ring-sea-blue-500 text-sm">{{ old('description', $ticket->Description) }}</textarea>
                                    @error('description')
                                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- Media / Gambar Berita (lama + baru) --}}
                                <div x-data="imageUploader()" class="mt-2">
                                    <div class="flex items-center justify-between mb-1">
                                        <label class="block text-sm font-medium text-gray-700">
                                            Media
                                        </label>
                                        <small class="text-[11px] text-gray-400">
                                            {{-- jumlah lama + baru (approx) --}}
                                            <span x-text="{{ $ticket->images->count() }} + files.length"></span> of 5 images
                                        </small>
                                    </div>

                                    <p class="text-[11px] text-gray-400 mb-2">
                                        Gambar yang sudah diupload akan tetap tersimpan. Tambah gambar baru jika diperlukan (maks. total 5).
                                    </p>

                                    <div class="bg-gray-50 border border-gray-200 rounded-lg px-2.5 py-2.5">
                                        <div class="grid grid-cols-4 gap-1.5">
                                            {{-- Gambar lama --}}
                                            @foreach($ticket->images as $img)
                                                <div class="relative">
                                                    <div class="w-full aspect-square rounded-md overflow-hidden border border-gray-200 bg-white">
                                                        <img src="{{ asset('storage/'.$img->Path) }}"
                                                             class="w-full h-full object-cover">
                                                    </div>
                                                    {{-- TODO: tombol hapus per image bisa ditambah di sini nanti --}}
                                                </div>
                                            @endforeach

                                            {{-- Gambar baru via Alpine --}}
                                            <template x-for="(file, index) in files" :key="index">
                                                <div class="relative">
                                                    <div class="w-full aspect-square rounded-md overflow-hidden border border-gray-200 bg-white">
                                                        <img :src="file.url"
                                                             class="w-full h-full object-cover">
                                                    </div>
                                                    <button type="button"
                                                            @click.stop="remove(index)"
                                                            class="absolute top-1 left-1 bg-white/90 hover:bg-red-500 hover:text-white text-gray-600 border border-gray-200 rounded-full w-5 h-5 flex items-center justify-center text-[10px] shadow">
                                                        &times;
                                                    </button>
                                                </div>
                                            </template>

                                            {{-- Kotak + untuk upload baru, batasi supaya tidak lebih dari 5 total --}}
                                            <template x-if="(files.length + {{ $ticket->images->count() }}) < maxFiles">
                                                <div
                                                    x-on:dragover.prevent="dragging = true"
                                                    x-on:dragleave.prevent="dragging = false"
                                                    x-on:drop.prevent="handleDrop($event)"
                                                    @click="$refs.fileInput.click()"
                                                    :class="dragging ? 'border-sea-blue-400 bg-sea-blue-50/40' : 'border-dashed border-gray-300 bg-white'"
                                                    class="w-full aspect-square rounded-md border-2 flex flex-col items-center justify-center text-[9px] text-gray-500 cursor-pointer transition"
                                                >
                                                    <i data-lucide="plus" class="w-4 h-4 text-gray-400 mb-1"></i>
                                                    <p class="text-[9px] text-center leading-tight">Tambah</p>
                                                    <p class="text-[8px] text-gray-400 mt-0.5">Max. 5 files</p>
                                                </div>
                                            </template>
                                        </div>

                                        {{-- input file asli --}}
                                        <input
                                            type="file"
                                            name="images[]"
                                            x-ref="fileInput"
                                            id="images"
                                            accept="image/*"
                                            multiple
                                            class="hidden"
                                            @change="handleInput($event)"
                                        >
                                    </div>

                                    @error('images')
                                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                    @enderror
                                    @error('images.*')
                                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Klasifikasi & Lokasi --}}
                    <div class="lg:col-span-1 space-y-5">
                        {{-- Card: Klasifikasi & Sentimen --}}
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
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}"
                                                {{ old('category', $ticket->CategoryId) == $category->id ? 'selected' : '' }}>
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
                                        <option value="positive" {{ old('sentiment', $ticket->Sentiment) == 'positive' ? 'selected' : '' }}>Positive</option>
                                        <option value="neutral"  {{ old('sentiment', $ticket->Sentiment) == 'neutral'  ? 'selected' : '' }}>Neutral</option>
                                        <option value="negative" {{ old('sentiment', $ticket->Sentiment) == 'negative' ? 'selected' : '' }}>Negative</option>
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
                                        <option value="high"   {{ old('priority', $ticket->Priority) == 'high'   ? 'selected' : '' }}>High</option>
                                        <option value="medium" {{ old('priority', $ticket->Priority) == 'medium' ? 'selected' : '' }}>Medium</option>
                                        <option value="low"    {{ old('priority', $ticket->Priority) == 'low'    ? 'selected' : '' }}>Low</option>
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
                                    <input type="text" name="actor" id="actor"
                                           value="{{ old('actor', $ticket->Actor) }}"
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
                                    <input type="text" name="tag" id="tag"
                                           value="{{ old('tag', $ticket->Tag) }}"
                                           placeholder="misal: kebakaran, bandara, darurat"
                                           class="w-full rounded-lg border-gray-300 focus:border-sea-blue-500 focus:ring-sea-blue-500 text-sm">
                                    @error('tag')
                                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Card: Lokasi & Waktu --}}
                        <div x-data="{ openLocation: false }" class="bg-white rounded-lg shadow border border-gray-100">
                            <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
                                <div class="flex items-center">
                                    <div class="p-2 bg-sea-blue-50 rounded-lg mr-3">
                                        <i data-lucide="map-pin" class="w-4 h-4 text-sea-blue-700"></i>
                                    </div>
                                    <div>
                                        <h3 class="text-sm font-semibold text-gray-800">Lokasi & Waktu</h3>
                                        <p class="text-xs text-gray-500">Detail lokasi kejadian dan waktu publikasi.</p>
                                    </div>
                                </div>

                                <button type="button"
                                        @click="openLocation = !openLocation"
                                        class="inline-flex items-center px-2 py-1 border border-gray-200 rounded-md text-[11px] text-gray-500 hover:bg-gray-50">
                                    <span x-show="openLocation" x-cloak>Sembunyikan</span>
                                    <span x-show="!openLocation" x-cloak>Tampilkan</span>
                                    <i x-show="openLocation" x-cloak data-lucide="chevron-up" class="w-3 h-3 ml-1"></i>
                                    <i x-show="!openLocation" x-cloak data-lucide="chevron-down" class="w-3 h-3 ml-1"></i>
                                </button>
                            </div>

                            <div x-show="openLocation"
                                 x-transition:enter="transition ease-out duration-150"
                                 x-transition:enter-start="opacity-0 -translate-y-1"
                                 x-transition:enter-end="opacity-100 translate-y-0"
                                 x-transition:leave="transition ease-in duration-100"
                                 x-transition:leave-start="opacity-100 translate-y-0"
                                 x-transition:leave-end="opacity-0 -translate-y-1"
                                 class="px-5 py-5 space-y-4">
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
                                                {{ old('region', $ticket->RegionId) == $region->id ? 'selected' : '' }}>
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
                                    <input type="text" name="location" id="location"
                                           value="{{ old('location', $ticket->Location) }}"
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
                                    <input type="text" name="latitude" id="latitude"
                                           value="{{ old('latitude', $ticket->Latitude) }}"
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
                                    <input type="text" name="longitude" id="longitude"
                                           value="{{ old('longitude', $ticket->Longitude) }}"
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
                                    <input type="datetime-local" name="published_at" id="published_at"
                                           value="{{ old('published_at', $ticket->PublishedDate ? $ticket->PublishedDate->format('Y-m-d\TH:i') : '') }}"
                                           class="w-full rounded-lg border-gray-300 focus:border-sea-blue-500 focus:ring-sea-blue-500 text-sm">
                                    @error('published_at')
                                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Tombol submit --}}
                <div class="flex justify-end gap-3 pt-3">
                    <a href="{{ route('tickets.index') }}"
                       class="px-4 py-2 border border-gray-300 rounded-lg text-sm text-gray-700 hover:bg-gray-50 transition">
                        Batal
                    </a>
                    <button type="submit"
                            class="px-4 py-2 bg-sea-blue-600 hover:bg-sea-blue-700 text-white rounded-lg text-sm font-medium inline-flex items-center gap-1 transition">
                        <i data-lucide="save" class="w-4 h-4"></i>
                        Update Berita
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
            });

            function imageUploader() {
                return {
                    files: [],
                    dragging: false,
                    maxFiles: 5,

                    handleInput(e) {
                        const selected = Array.from(e.target.files);
                        this.addFiles(selected);
                    },

                    handleDrop(e) {
                        this.dragging = false;
                        const dropped = Array.from(e.dataTransfer.files);
                        this.addFiles(dropped);
                    },

                    addFiles(newFiles) {
                        const imageFiles = newFiles.filter(file => file.type.startsWith('image/'));

                        let combined = [
                            ...this.files,
                            ...imageFiles.map(file => ({
                                file,
                                url: URL.createObjectURL(file),
                            }))
                        ];

                        if (combined.length > this.maxFiles) {
                            combined = combined.slice(0, this.maxFiles);
                        }

                        this.files = combined;

                        const dataTransfer = new DataTransfer();
                        this.files.forEach(item => dataTransfer.items.add(item.file));
                        if (this.$refs.fileInput) {
                            this.$refs.fileInput.files = dataTransfer.files;
                        }
                    },

                    remove(index) {
                        const removed = this.files.splice(index, 1)[0];
                        if (removed && removed.url) {
                            URL.revokeObjectURL(removed.url);
                        }

                        const dataTransfer = new DataTransfer();
                        this.files.forEach(item => dataTransfer.items.add(item.file));
                        if (this.$refs.fileInput) {
                            this.$refs.fileInput.files = dataTransfer.files;
                        }
                    }
                }
            }
        </script>
    @endpush
</x-app-layout>