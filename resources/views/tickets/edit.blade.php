<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-sea-blue-800 leading-tight">
                {{ __('Edit Berita') }}
            </h2>
            <a href="{{ route('tickets.index') }}" class="text-gray-600 hover:text-gray-800">
                <i data-lucide="arrow-left" class="w-5 h-5"></i>
                Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <form action="{{ route('tickets.update', $ticket->id) }}" method="POST" class="space-y-6 p-6">
                    @csrf
                    @method('PUT')

                    {{-- Judul --}}
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Judul Berita *</label>
                        <input type="text" name="title" id="title" value="{{ old('title', $ticket->Title) }}" required
                            class="w-full rounded-lg border-gray-300 focus:border-sea-blue-500 focus:ring-sea-blue-500">
                        @error('title')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Deskripsi --}}
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Deskripsi *</label>
                        <textarea name="description" id="description" rows="6" required
                            class="w-full rounded-lg border-gray-300 focus:border-sea-blue-500 focus:ring-sea-blue-500">{{ old('description', $ticket->Description) }}</textarea>
                        @error('description')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Grid 2 Kolom (sama seperti create) --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="category" class="block text-sm font-medium text-gray-700 mb-1">Kategori *</label>
                            <input type="text" name="category" id="category" value="{{ old('category', $ticket->Category) }}" required
                                class="w-full rounded-lg border-gray-300 focus:border-sea-blue-500 focus:ring-sea-blue-500">
                        </div>

                        <div>
                            <label for="sentiment" class="block text-sm font-medium text-gray-700 mb-1">Sentiment *</label>
                            <select name="sentiment" id="sentiment" required
                                class="w-full rounded-lg border-gray-300 focus:border-sea-blue-500 focus:ring-sea-blue-500">
                                <option value="positive" {{ old('sentiment', $ticket->Sentiment) == 'positive' ? 'selected' : '' }}>Positive</option>
                                <option value="neutral" {{ old('sentiment', $ticket->Sentiment) == 'neutral' ? 'selected' : '' }}>Neutral</option>
                                <option value="negative" {{ old('sentiment', $ticket->Sentiment) == 'negative' ? 'selected' : '' }}>Negative</option>
                            </select>
                        </div>

                        <div>
                            <label for="priority" class="block text-sm font-medium text-gray-700 mb-1">Priority *</label>
                            <select name="priority" id="priority" required
                                class="w-full rounded-lg border-gray-300 focus:border-sea-blue-500 focus:ring-sea-blue-500">
                                <option value="high" {{ old('priority', $ticket->Priority) == 'high' ? 'selected' : '' }}>High</option>
                                <option value="medium" {{ old('priority', $ticket->Priority) == 'medium' ? 'selected' : '' }}>Medium</option>
                                <option value="low" {{ old('priority', $ticket->Priority) == 'low' ? 'selected' : '' }}>Low</option>
                            </select>
                        </div>

                        <div>
                            <label for="actor" class="block text-sm font-medium text-gray-700 mb-1">Aktor/Pelaku</label>
                            <input type="text" name="actor" id="actor" value="{{ old('actor', $ticket->Actor) }}"
                                class="w-full rounded-lg border-gray-300 focus:border-sea-blue-500 focus:ring-sea-blue-500">
                        </div>

                        <div>
                            <label for="tag" class="block text-sm font-medium text-gray-700 mb-1">Tag</label>
                            <input type="text" name="tag" id="tag" value="{{ old('tag', $ticket->Tag) }}" placeholder="pisahkan dengan koma"
                                class="w-full rounded-lg border-gray-300 focus:border-sea-blue-500 focus:ring-sea-blue-500">
                        </div>

                        <div>
                            <label for="region" class="block text-sm font-medium text-gray-700 mb-1">Region</label>
                            <input type="text" name="region" id="region" value="{{ old('region', $ticket->Region) }}"
                                class="w-full rounded-lg border-gray-300 focus:border-sea-blue-500 focus:ring-sea-blue-500">
                        </div>

                        <div>
                            <label for="location" class="block text-sm font-medium text-gray-700 mb-1">Lokasi</label>
                            <input type="text" name="location" id="location" value="{{ old('location', $ticket->Location) }}"
                                class="w-full rounded-lg border-gray-300 focus:border-sea-blue-500 focus:ring-sea-blue-500">
                        </div>

                        <div>
                            <label for="latitude" class="block text-sm font-medium text-gray-700 mb-1">Latitude</label>
                            <input type="text" name="latitude" id="latitude" value="{{ old('latitude', $ticket->Latitude) }}"
                                class="w-full rounded-lg border-gray-300 focus:border-sea-blue-500 focus:ring-sea-blue-500">
                        </div>

                        <div>
                            <label for="longitude" class="block text-sm font-medium text-gray-700 mb-1">Longitude</label>
                            <input type="text" name="longitude" id="longitude" value="{{ old('longitude', $ticket->Longitude) }}"
                                class="w-full rounded-lg border-gray-300 focus:border-sea-blue-500 focus:ring-sea-blue-500">
                        </div>

                        <div>
                            <label for="published_at" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Publikasi</label>
                            <input type="datetime-local" name="published_at" id="published_at" value="{{ old('published_at', $ticket->PublishedDate ? $ticket->PublishedDate->format('Y-m-d\TH:i') : '') }}"
                                class="w-full rounded-lg border-gray-300 focus:border-sea-blue-500 focus:ring-sea-blue-500">
                        </div>
                    </div>

                    {{-- Tombol Submit --}}
                    <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200">
                        <a href="{{ route('tickets.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                            Batal
                        </a>
                        <button type="submit" class="px-4 py-2 bg-sea-blue-600 hover:bg-sea-blue-700 text-white rounded-lg font-medium transition">
                            <i data-lucide="save" class="w-4 h-4 inline mr-1"></i>
                            Update Berita
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://unpkg.com/lucide@latest"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            lucide.createIcons();
        });
    </script>
    @endpush
</x-app-layout>