<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-sea-blue-800 leading-tight">
                {{ __('Detail Berita') }}
            </h2>
            <div class="space-x-2">
                <a href="{{ route('tickets.edit', $ticket->id) }}" class="bg-sea-blue-600 hover:bg-sea-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium inline-flex items-center">
                    <i data-lucide="edit" class="w-4 h-4 mr-2"></i>
                    Edit
                </a>
                <a href="{{ route('tickets.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg text-sm font-medium inline-flex items-center">
                    <i data-lucide="arrow-left" class="w-4 h-4 mr-2"></i>
                    Kembali
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-lg shadow overflow-hidden">
                {{-- Header dengan Status --}}
                <div class="p-6 border-b border-gray-200 bg-gradient-to-r from-sea-blue-50 to-white">
                    <h1 class="text-2xl font-bold text-gray-800 mb-2">{{ $ticket->Title }}</h1>
                    <div class="flex flex-wrap gap-3 items-center">
                        <span class="px-3 py-1 text-sm font-medium rounded-full 
                            {{ $ticket->Sentiment == 'positive' ? 'bg-green-100 text-green-800' : 
                               ($ticket->Sentiment == 'negative' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                            {{ ucfirst($ticket->Sentiment) }}
                        </span>
                        <span class="px-3 py-1 text-sm font-medium rounded-full 
                            {{ $ticket->Priority == 'high' ? 'bg-red-100 text-red-800' : 
                               ($ticket->Priority == 'medium' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800') }}">
                            Priority: {{ ucfirst($ticket->Priority) }}
                        </span>
                        <span class="px-3 py-1 text-sm font-medium bg-gray-100 text-gray-700 rounded-full">
                            <i data-lucide="eye" class="w-3 h-3 inline mr-1"></i>
                            {{ $ticket->ViewCount }} views
                        </span>
                    </div>
                </div>

                {{-- Content --}}
                <div class="p-6 space-y-6">
                    {{-- Deskripsi --}}
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800 mb-2">Deskripsi</h3>
                        <p class="text-gray-700 leading-relaxed whitespace-pre-wrap">{{ $ticket->Description }}</p>
                    </div>

                    {{-- Grid Informasi --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="bg-gray-50 rounded-lg p-4">
                            <div class="flex items-center text-gray-500 text-sm mb-2">
                                <i data-lucide="folder" class="w-4 h-4 mr-2"></i>
                                Kategori
                            </div>
                            <p class="text-gray-800 font-medium">{{ $ticket->Category }}</p>
                        </div>

                        @if($ticket->Actor)
                        <div class="bg-gray-50 rounded-lg p-4">
                            <div class="flex items-center text-gray-500 text-sm mb-2">
                                <i data-lucide="users" class="w-4 h-4 mr-2"></i>
                                Aktor/Pelaku
                            </div>
                            <p class="text-gray-800 font-medium">{{ $ticket->Actor }}</p>
                        </div>
                        @endif

                        @if($ticket->Tag)
                        <div class="bg-gray-50 rounded-lg p-4">
                            <div class="flex items-center text-gray-500 text-sm mb-2">
                                <i data-lucide="tag" class="w-4 h-4 mr-2"></i>
                                Tag
                            </div>
                            <p class="text-gray-800 font-medium">{{ $ticket->Tag }}</p>
                        </div>
                        @endif

                        @if($ticket->Region)
                        <div class="bg-gray-50 rounded-lg p-4">
                            <div class="flex items-center text-gray-500 text-sm mb-2">
                                <i data-lucide="map" class="w-4 h-4 mr-2"></i>
                                Region
                            </div>
                            <p class="text-gray-800 font-medium">{{ $ticket->Region }}</p>
                        </div>
                        @endif

                        @if($ticket->Location)
                        <div class="bg-gray-50 rounded-lg p-4">
                            <div class="flex items-center text-gray-500 text-sm mb-2">
                                <i data-lucide="map-pin" class="w-4 h-4 mr-2"></i>
                                Lokasi
                            </div>
                            <p class="text-gray-800 font-medium">{{ $ticket->Location }}</p>
                        </div>
                        @endif

                        @if($ticket->Latitude && $ticket->Longitude)
                        <div class="bg-gray-50 rounded-lg p-4">
                            <div class="flex items-center text-gray-500 text-sm mb-2">
                                <i data-lucide="navigation" class="w-4 h-4 mr-2"></i>
                                Koordinat
                            </div>
                            <p class="text-gray-800 font-medium">{{ $ticket->Latitude }}, {{ $ticket->Longitude }}</p>
                        </div>
                        @endif

                        <div class="bg-gray-50 rounded-lg p-4">
                            <div class="flex items-center text-gray-500 text-sm mb-2">
                                <i data-lucide="calendar" class="w-4 h-4 mr-2"></i>
                                Tanggal Publikasi
                            </div>
                            <p class="text-gray-800 font-medium">{{ $ticket->PublishedDate ? $ticket->PublishedDate->format('d F Y H:i') : 'Belum dipublikasi' }}</p>
                        </div>

                        <div class="bg-gray-50 rounded-lg p-4">
                            <div class="flex items-center text-gray-500 text-sm mb-2">
                                <i data-lucide="user" class="w-4 h-4 mr-2"></i>
                                Penulis
                            </div>
                            <p class="text-gray-800 font-medium">{{ $ticket->creator->name ?? 'Unknown' }}</p>
                        </div>
                    </div>

                    {{-- Action Buttons --}}
                    <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200">
                        <form action="{{ route('tickets.destroy', $ticket->id) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg font-medium transition" onclick="return confirm('Yakin ingin menghapus berita ini?')">
                                <i data-lucide="trash-2" class="w-4 h-4 inline mr-1"></i>
                                Hapus Berita
                            </button>
                        </form>
                        <a href="{{ route('tickets.edit', $ticket->id) }}" class="px-4 py-2 bg-sea-blue-600 hover:bg-sea-blue-700 text-white rounded-lg font-medium transition inline-flex items-center">
                            <i data-lucide="edit" class="w-4 h-4 mr-1"></i>
                            Edit
                        </a>
                    </div>
                </div>
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