<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-sea-blue-800 leading-tight">
                Detail Berita
            </h2>
            <div class="flex items-center gap-2">
                {{-- Delete --}}
                <form action="{{ route('tickets.destroy', $ticket->id) }}" method="POST" class="inline"
                      onsubmit="return confirm('Yakin ingin menghapus berita ini?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="inline-flex items-center px-3 py-2 border border-red-200 text-red-600 hover:bg-red-50 rounded-lg text-sm font-medium transition">
                        <i data-lucide="trash-2" class="w-4 h-4 mr-1.5"></i>
                        Hapus
                    </button>
                </form>

                {{-- Edit --}}
                <a href="{{ route('tickets.edit', $ticket->id) }}"
                   class="inline-flex items-center px-3 py-2 bg-sea-blue-600 hover:bg-sea-blue-700 text-white rounded-lg text-sm font-medium transition">
                    <i data-lucide="edit" class="w-4 h-4 mr-1.5"></i>
                    Edit
                </a>

                {{-- Kembali --}}
                <a href="{{ route('tickets.index') }}"
                   class="inline-flex items-center px-3 py-2 border border-gray-200 text-gray-700 hover:bg-gray-50 rounded-lg text-sm font-medium transition">
                    <i data-lucide="arrow-left" class="w-4 h-4 mr-1.5"></i>
                    Kembali
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-lg shadow border border-gray-100 overflow-hidden">
                {{-- Header status --}}
                <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-sea-blue-50 to-white">
                    <div class="flex flex-wrap items-center justify-between gap-3">
                        <div>
                            <h1 class="text-xl font-semibold text-gray-900 mb-1">
                                {{ $ticket->Title }}
                            </h1>
                            <div class="flex flex-wrap items-center gap-2 text-[11px] text-gray-500">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium
                                    {{ $ticket->Sentiment == 'positive' ? 'bg-green-100 text-green-700' :
                                       ($ticket->Sentiment == 'negative' ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-700') }}">
                                    {{ ucfirst($ticket->Sentiment) }}
                                </span>
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium
                                    {{ $ticket->Priority == 'high' ? 'bg-red-100 text-red-700' :
                                       ($ticket->Priority == 'medium' ? 'bg-yellow-100 text-yellow-700' : 'bg-green-100 text-green-700') }}">
                                    Priority: {{ ucfirst($ticket->Priority) }}
                                </span>
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-600">
                                    <i data-lucide="eye" class="w-3 h-3 mr-1"></i>
                                    {{ $ticket->ViewCount }} views
                                </span>
                            </div>
                        </div>

                        <div class="flex flex-col items-end text-right text-[11px] text-gray-500">
                            <span>Dibuat: {{ $ticket->created_at?->format('d M Y H:i') }}</span>
                            @if($ticket->PublishedDate)
                                <span>Publikasi: {{ $ticket->PublishedDate->format('d M Y H:i') }}</span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="px-6 py-5 space-y-6">
                    {{-- Gallery gambar (setelah header status) --}}
                    @if($ticket->images && $ticket->images->count())
                        <div>
                            <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                                @foreach($ticket->images as $img)
                                    <div class="relative rounded-lg overflow-hidden border border-gray-200 bg-gray-50">
                                        <img src="{{ asset('storage/'.$img->Path) }}"
                                             class="w-full h-40 md:h-44 lg:h-48 object-cover"
                                             alt="Media">
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- Grid informasi (ikon + isi saja) --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        {{-- Kategori --}}
                        <div class="flex items-center gap-2 bg-gray-50 rounded-lg px-3 py-2.5">
                            <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-white border border-gray-200 text-gray-500">
                                <i data-lucide="folder" class="w-4 h-4"></i>
                            </span>
                            <p class="text-sm text-gray-800 font-medium truncate">
                                {{ $ticket->Category }}
                            </p>
                        </div>

                        {{-- Aktor --}}
                        @if($ticket->Actor)
                            <div class="flex items-center gap-2 bg-gray-50 rounded-lg px-3 py-2.5">
                                <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-white border border-gray-200 text-gray-500">
                                    <i data-lucide="users" class="w-4 h-4"></i>
                                </span>
                                <p class="text-sm text-gray-800 font-medium truncate">
                                    {{ $ticket->Actor }}
                                </p>
                            </div>
                        @endif

                        {{-- Tag --}}
                        @if($ticket->Tag)
                            <div class="flex items-center gap-2 bg-gray-50 rounded-lg px-3 py-2.5">
                                <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-white border border-gray-200 text-gray-500">
                                    <i data-lucide="tag" class="w-4 h-4"></i>
                                </span>
                                <p class="text-sm text-gray-800 font-medium truncate">
                                    {{ $ticket->Tag }}
                                </p>
                            </div>
                        @endif

                        {{-- Region --}}
                        @if($ticket->Region)
                            <div class="flex items-center gap-2 bg-gray-50 rounded-lg px-3 py-2.5">
                                <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-white border border-gray-200 text-gray-500">
                                    <i data-lucide="map" class="w-4 h-4"></i>
                                </span>
                                <p class="text-sm text-gray-800 font-medium truncate">
                                    {{ $ticket->Region }}
                                </p>
                            </div>
                        @endif

                        {{-- Lokasi --}}
                        @if($ticket->Location)
                            <div class="flex items-center gap-2 bg-gray-50 rounded-lg px-3 py-2.5 md:col-span-2">
                                <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-white border border-gray-200 text-gray-500 flex-shrink-0">
                                    <i data-lucide="map-pin" class="w-4 h-4"></i>
                                </span>
                                <p class="text-sm text-gray-800 font-medium">
                                    {{ $ticket->Location }}
                                </p>
                            </div>
                        @endif

                        {{-- Koordinat --}}
                        @if($ticket->Latitude && $ticket->Longitude)
                            <div class="flex items-center gap-2 bg-gray-50 rounded-lg px-3 py-2.5">
                                <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-white border border-gray-200 text-gray-500">
                                    <i data-lucide="navigation" class="w-4 h-4"></i>
                                </span>
                                <p class="text-sm text-gray-800 font-medium">
                                    {{ $ticket->Latitude }}, {{ $ticket->Longitude }}
                                </p>
                            </div>
                        @endif

                        {{-- Tanggal publikasi --}}
                        <div class="flex items-center gap-2 bg-gray-50 rounded-lg px-3 py-2.5">
                            <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-white border border-gray-200 text-gray-500">
                                <i data-lucide="calendar" class="w-4 h-4"></i>
                            </span>
                            <p class="text-sm text-gray-800 font-medium">
                                {{ $ticket->PublishedDate ? $ticket->PublishedDate->format('d F Y H:i') : 'Belum dipublikasi' }}
                            </p>
                        </div>

                        {{-- Penulis --}}
                        <div class="flex items-center gap-2 bg-gray-50 rounded-lg px-3 py-2.5">
                            <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-white border border-gray-200 text-gray-500">
                                <i data-lucide="user" class="w-4 h-4"></i>
                            </span>
                            <p class="text-sm text-gray-800 font-medium">
                                {{ $ticket->creator->name ?? 'Unknown' }}
                            </p>
                        </div>
                    </div>

                    {{-- Deskripsi (dipindah ke bawah info) --}}
                    <div class="pt-2 border-t border-gray-100">
                        <h3 class="text-sm font-semibold text-gray-800 mb-2">Deskripsi</h3>
                        <p class="text-sm text-gray-700 leading-relaxed whitespace-pre-wrap">
                            {{ $ticket->Description }}
                        </p>
                    </div>
                </div>
            </div>
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
        </script>
    @endpush
</x-app-layout>