<x-app-layout
    x-data="{
        showEscalationModal: false,
        showEscalationDropdown: false,
        selectedPlatforms: [],
        availablePlatforms: ['email', 'whatsapp', 'telegram'],
        contactId: '',
        recipient: '',
        message: '',
        showContactDropdown: false,
        selectedContactName: '',
        isSubmitting: false,
        showRelatedSidebar: false,
        
        togglePlatform(platform) {
            if (this.selectedPlatforms.includes(platform)) {
                this.selectedPlatforms = this.selectedPlatforms.filter(p => p !== platform);
            } else {
                this.selectedPlatforms.push(platform);
            }
        },
        
        getPlatformIcon(platform) {
            const icons = {
                'email': 'mail',
                'whatsapp': 'message-circle',
                'telegram': 'send'
            };
            return icons[platform] || 'share-2';
        }
    }"
>
    {{-- Header --}}
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-bold text-xl text-gray-900 leading-tight">
                {{ __('Detail Berita') }}
            </h2>
            
            <div class="flex items-center gap-2">
                {{-- Visibility Badge with Dropdown --}}
                <div x-data="{ openVisibility: false }" class="relative">
                    <button
                        type="button"
                        @click="openVisibility = !openVisibility"
                        class="inline-flex items-center px-3 py-2 border border-gray-200 text-gray-700 bg-white hover:bg-gray-50 rounded-xl text-xs font-medium transition shadow-sm"
                    >
                        <span class="inline-flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full {{ $ticket->HandlerType ? 'bg-emerald-500' : 'bg-gray-400' }}"></span>
                            <span class="text-xs">
                                {{ $ticket->HandlerType ? 'Eksternal' : 'Internal' }}
                            </span>
                        </span>
                        <i data-lucide="chevron-down" class="w-4 h-4 ml-1 text-gray-400"></i>
                    </button>

                    <div
                        x-cloak
                        x-show="openVisibility"
                        @click.away="openVisibility = false"
                        x-transition
                        class="absolute right-0 mt-2 w-56 bg-white rounded-xl shadow-xl border border-gray-100 z-40 overflow-hidden"
                    >
                        <form action="{{ route('tickets.visibility', $ticket->id) }}" method="POST" class="py-2">
                            @csrf

                            <p class="px-3 pb-2 text-[10px] text-gray-400 border-b border-gray-100">
                                Atur visibilitas berita
                            </p>

                            <button
                                type="submit"
                                name="HandlerType"
                                value="0"
                                class="w-full flex items-center justify-between px-3 py-2.5 text-xs text-gray-700 hover:bg-gray-50 transition"
                            >
                                <span class="flex items-center gap-2">
                                    <span class="w-2 h-2 rounded-full bg-gray-400"></span>
                                    <span>Internal</span>
                                </span>
                                <input
                                    type="radio"
                                    class="text-sea-blue-600 border-gray-300"
                                    @click.stop
                                    {{ !$ticket->HandlerType ? 'checked' : '' }}
                                >
                            </button>

                            <button
                                type="submit"
                                name="HandlerType"
                                value="1"
                                class="w-full flex items-center justify-between px-3 py-2.5 text-xs text-gray-700 hover:bg-gray-50 transition"
                            >
                                <span class="flex items-center gap-2">
                                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                                    <span>Eksternal</span>
                                </span>
                                <input
                                    type="radio"
                                    class="text-sea-blue-600 border-gray-300"
                                    @click.stop
                                    {{ $ticket->HandlerType ? 'checked' : '' }}
                                >
                            </button>
                        </form>
                    </div>
                </div>

                {{-- Actions Dropdown --}}
                <div x-data="{ openActions: false }" class="relative">
                    <button
                        type="button"
                        @click="openActions = !openActions"
                        class="inline-flex items-center px-3 py-2 border border-gray-200 text-gray-700 bg-white hover:bg-gray-50 rounded-xl text-xs font-medium transition shadow-sm"
                    >
                        <i data-lucide="more-vertical" class="w-4 h-4"></i>
                    </button>

                    <div
                        x-cloak
                        x-show="openActions"
                        @click.away="openActions = false"
                        x-transition
                        class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-xl border border-gray-100 z-40 overflow-hidden"
                    >
                        {{-- Edit --}}
                        <a href="{{ route('tickets.edit', $ticket->id) }}"
                           class="flex items-center gap-2 px-3 py-2.5 text-xs text-gray-700 hover:bg-gray-50 transition">
                            <i data-lucide="edit-2" class="w-4 h-4 text-sea-blue-600"></i>
                            <span>Edit Berita</span>
                        </a>

                        {{-- Delete --}}
                        <form action="{{ route('tickets.destroy', $ticket->id) }}" method="POST" class="inline"
                              onsubmit="return confirm('Yakin ingin menghapus berita ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    class="w-full flex items-center gap-2 px-3 py-2.5 text-xs text-red-600 hover:bg-red-50 transition">
                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                                <span>Hapus Berita</span>
                            </button>
                        </form>
                    </div>
                </div>

                {{-- Eskalasi Dropdown --}}
                <div class="relative">
                    <button
                        type="button"
                        @click="showEscalationDropdown = !showEscalationDropdown"
                        class="inline-flex items-center px-3 py-2 bg-sea-blue-600 hover:bg-sea-blue-700 text-white rounded-xl text-xs font-medium transition shadow-sm"
                    >
                        <i data-lucide="share-2" class="w-4 h-4 mr-1.5"></i>
                        <span>Eskalasi</span>
                    </button>

                    <div
                        x-cloak
                        x-show="showEscalationDropdown"
                        @click.away="showEscalationDropdown = false"
                        x-transition
                        class="absolute right-0 mt-2 w-64 bg-white rounded-xl shadow-xl border border-gray-100 z-40 overflow-hidden"
                    >
                        <div class="px-3 py-2 border-b border-gray-100">
                            <p class="text-[10px] text-gray-400">Pilih platform eskalasi</p>
                        </div>

                        <div class="p-2 space-y-1">
                            @foreach(['email' => 'Email', 'whatsapp' => 'WhatsApp', 'telegram' => 'Telegram'] as $key => $label)
                                <label class="flex items-center justify-between px-3 py-2.5 text-xs text-gray-700 hover:bg-gray-50 rounded-lg cursor-pointer transition">
                                    <span class="flex items-center gap-2">
                                        <i data-lucide="{{ $key === 'email' ? 'mail' : ($key === 'whatsapp' ? 'message-circle' : 'send') }}" 
                                           class="w-4 h-4 text-gray-400"></i>
                                        <span>{{ $label }}</span>
                                    </span>
                                    <input
                                        type="checkbox"
                                        :value="'{{ $key }}'"
                                        x-model="selectedPlatforms"
                                        @click.stop
                                        class="text-sea-blue-600 border-gray-300 rounded focus:ring-sea-blue-500"
                                    >
                                </label>
                            @endforeach
                        </div>

                        {{-- Active Platforms Badges --}}
                        <div class="px-3 py-2 border-t border-gray-100">
                            <div class="flex flex-wrap gap-1">
                                <template x-for="platform in selectedPlatforms" :key="platform">
                                    <span class="inline-flex items-center gap-1 px-2 py-1 bg-sea-blue-50 text-sea-blue-700 rounded-md text-[10px] font-medium">
                                        <i :data-lucide="getPlatformIcon(platform)" class="w-3 h-3"></i>
                                        <span x-text="platform.charAt(0).toUpperCase() + platform.slice(1)"></span>
                                    </span>
                                </template>
                                <template x-if="selectedPlatforms.length === 0">
                                    <span class="text-[10px] text-gray-400 italic">Belum ada platform dipilih</span>
                                </template>
                            </div>
                        </div>

                        {{-- Open Modal Button --}}
                        <div class="px-3 py-2 border-t border-gray-100 bg-gray-50">
                            <button
                                type="button"
                                @click="if (selectedPlatforms.length > 0) { showEscalationModal = true; showEscalationDropdown = false; } else { Swal.fire({ icon: 'warning', title: 'Pilih Platform', text: 'Pilih minimal 1 platform untuk eskalasi', confirmButtonColor: '#0284c7' }); }"
                                class="w-full flex items-center justify-center gap-2 px-3 py-2 bg-sea-blue-600 hover:bg-sea-blue-700 text-white rounded-lg text-xs font-medium transition"
                            >
                                <i data-lucide="arrow-right" class="w-4 h-4"></i>
                                <span>Lanjutkan Eskalasi</span>
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Kembali Button --}}
                <a href="{{ route('tickets.index') }}"
                   class="inline-flex items-center px-3 py-2 border border-gray-200 text-gray-700 hover:bg-gray-50 rounded-xl text-xs font-medium transition shadow-sm">
                    <i data-lucide="arrow-left" class="w-4 h-4 mr-1.5"></i>
                    <span class="hidden sm:inline">Kembali</span>
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- Grid Layout: Main Content + Sidebar --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                
                {{-- Main Content (2/3) --}}
                <div class="lg:col-span-2 space-y-6">
                    
                    {{-- Card 1: Ticket Overview --}}
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition-shadow duration-300">
                        {{-- Header Status --}}
                        <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-sea-blue-50 via-white to-white">
                            <div class="flex flex-col gap-3">
                                {{-- Title --}}
                                <h1 class="text-xl font-bold text-gray-900 line-clamp-2">
                                    {{ $ticket->Title }}
                                </h1>

                                {{-- Badges --}}
                                <div class="flex flex-wrap items-center gap-2">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium
                                        {{ $ticket->Sentiment == 'positive' ? 'bg-green-100 text-green-700' :
                                           ($ticket->Sentiment == 'negative' ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-700') }}">
                                        <i data-lucide="smile" class="w-3 h-3 mr-1"></i>
                                        {{ ucfirst($ticket->Sentiment) }}
                                    </span>

                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium
                                        {{ $ticket->Priority == 'high' ? 'bg-red-100 text-red-700' :
                                           ($ticket->Priority == 'medium' ? 'bg-yellow-100 text-yellow-700' : 'bg-green-100 text-green-700') }}">
                                        <i data-lucide="flag" class="w-3 h-3 mr-1"></i>
                                        Priority: {{ ucfirst($ticket->Priority) }}
                                    </span>

                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-600">
                                        <i data-lucide="eye" class="w-3 h-3 mr-1"></i>
                                        {{ $ticket->ViewCount }} views
                                    </span>

                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-indigo-100 text-indigo-700">
                                        <i data-lucide="user" class="w-3 h-3 mr-1"></i>
                                        {{ $ticket->creator->name ?? 'Unknown' }}
                                    </span>
                                </div>

                                {{-- Timestamps --}}
                                <div class="flex items-center justify-between text-[10px] text-gray-500">
                                    <span class="flex items-center gap-1">
                                        <i data-lucide="clock" class="w-3 h-3"></i>
                                        Dibuat: {{ $ticket->created_at?->format('d M Y, H:i') }}
                                    </span>
                                    @if($ticket->PublishedDate)
                                        <span class="flex items-center gap-1">
                                            <i data-lucide="calendar" class="w-3 h-3"></i>
                                            Publikasi: {{ $ticket->PublishedDate->format('d M Y, H:i') }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="px-6 py-5 space-y-6">
                            {{-- Hero Image --}}
                            @if($ticket->images && $ticket->images->count())
                                @php
                                    $hero = $ticket->images->first();
                                @endphp

                                <div class="rounded-xl overflow-hidden border border-gray-200 bg-gray-50 group">
                                    <img
                                        src="{{ asset('storage/'.$hero->Path) }}"
                                        alt="Media"
                                        class="w-full max-h-96 object-cover group-hover:scale-105 transition-transform duration-300"
                                    >
                                </div>
                            @endif

                            {{-- Info Grid (Improved) --}}
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                {{-- Category --}}
                                <div class="flex items-center gap-3 bg-gradient-to-br from-gray-50 to-white rounded-xl px-4 py-3 border border-gray-100 hover:border-sea-blue-200 hover:shadow-sm transition-all duration-200">
                                    <span class="inline-flex items-center justify-center w-9 h-9 rounded-full bg-sea-blue-100 text-sea-blue-600 flex-shrink-0">
                                        <i data-lucide="folder" class="w-4 h-4"></i>
                                    </span>
                                    <div>
                                        <p class="text-[10px] text-gray-500 font-medium">Kategori</p>
                                        <p class="text-sm text-gray-800 font-semibold truncate">
                                            {{ $ticket->Category }}
                                        </p>
                                    </div>
                                </div>

                                {{-- Actor --}}
                                @if($ticket->Actor)
                                    <div class="flex items-center gap-3 bg-gradient-to-br from-gray-50 to-white rounded-xl px-4 py-3 border border-gray-100 hover:border-sea-blue-200 hover:shadow-sm transition-all duration-200">
                                        <span class="inline-flex items-center justify-center w-9 h-9 rounded-full bg-purple-100 text-purple-600 flex-shrink-0">
                                            <i data-lucide="users" class="w-4 h-4"></i>
                                        </span>
                                        <div>
                                            <p class="text-[10px] text-gray-500 font-medium">Aktor</p>
                                            <p class="text-sm text-gray-800 font-semibold truncate">
                                                {{ $ticket->Actor }}
                                            </p>
                                        </div>
                                    </div>
                                @endif

                                {{-- Tag --}}
                                @if($ticket->Tag)
                                    <div class="flex items-center gap-3 bg-gradient-to-br from-gray-50 to-white rounded-xl px-4 py-3 border border-gray-100 hover:border-sea-blue-200 hover:shadow-sm transition-all duration-200">
                                        <span class="inline-flex items-center justify-center w-9 h-9 rounded-full bg-orange-100 text-orange-600 flex-shrink-0">
                                            <i data-lucide="tag" class="w-4 h-4"></i>
                                        </span>
                                        <div>
                                            <p class="text-[10px] text-gray-500 font-medium">Tag</p>
                                            <p class="text-sm text-gray-800 font-semibold truncate">
                                                {{ $ticket->Tag }}
                                            </p>
                                        </div>
                                    </div>
                                @endif

                                {{-- Region --}}
                                @if($ticket->Region)
                                    <div class="flex items-center gap-3 bg-gradient-to-br from-gray-50 to-white rounded-xl px-4 py-3 border border-gray-100 hover:border-sea-blue-200 hover:shadow-sm transition-all duration-200">
                                        <span class="inline-flex items-center justify-center w-9 h-9 rounded-full bg-green-100 text-green-600 flex-shrink-0">
                                            <i data-lucide="map" class="w-4 h-4"></i>
                                        </span>
                                        <div>
                                            <p class="text-[10px] text-gray-500 font-medium">Region</p>
                                            <p class="text-sm text-gray-800 font-semibold truncate">
                                                {{ $ticket->Region }}
                                            </p>
                                        </div>
                                    </div>
                                @endif

                                {{-- Location (full width) --}}
                                @if($ticket->Location)
                                    <div class="flex items-center gap-3 bg-gradient-to-br from-gray-50 to-white rounded-xl px-4 py-3 border border-gray-100 hover:border-sea-blue-200 hover:shadow-sm transition-all duration-200 md:col-span-2">
                                        <span class="inline-flex items-center justify-center w-9 h-9 rounded-full bg-red-100 text-red-600 flex-shrink-0">
                                            <i data-lucide="map-pin" class="w-4 h-4"></i>
                                        </span>
                                        <div class="flex-1">
                                            <p class="text-[10px] text-gray-500 font-medium">Lokasi</p>
                                            <p class="text-sm text-gray-800 font-semibold">
                                                {{ $ticket->Location }}
                                            </p>
                                        </div>
                                    </div>
                                @endif

                                {{-- Coordinates --}}
                                @if($ticket->Latitude && $ticket->Longitude)
                                    <div class="flex items-center gap-3 bg-gradient-to-br from-gray-50 to-white rounded-xl px-4 py-3 border border-gray-100 hover:border-sea-blue-200 hover:shadow-sm transition-all duration-200">
                                        <span class="inline-flex items-center justify-center w-9 h-9 rounded-full bg-blue-100 text-blue-600 flex-shrink-0">
                                            <i data-lucide="navigation" class="w-4 h-4"></i>
                                        </span>
                                        <div>
                                            <p class="text-[10px] text-gray-500 font-medium">Koordinat</p>
                                            <p class="text-sm text-gray-800 font-semibold">
                                                {{ number_format($ticket->Latitude, 4) }}, {{ number_format($ticket->Longitude, 4) }}
                                            </p>
                                        </div>
                                    </div>
                                @endif

                                {{-- Published Date --}}
                                <div class="flex items-center gap-3 bg-gradient-to-br from-gray-50 to-white rounded-xl px-4 py-3 border border-gray-100 hover:border-sea-blue-200 hover:shadow-sm transition-all duration-200">
                                    <span class="inline-flex items-center justify-center w-9 h-9 rounded-full bg-amber-100 text-amber-600 flex-shrink-0">
                                        <i data-lucide="calendar" class="w-4 h-4"></i>
                                    </span>
                                    <div>
                                        <p class="text-[10px] text-gray-500 font-medium">Dipublikasi</p>
                                        <p class="text-sm text-gray-800 font-semibold">
                                            {{ $ticket->PublishedDate ? $ticket->PublishedDate->format('d F Y') : 'Belum dipublikasi' }}
                                        </p>
                                    </div>
                                </div>
                            </div>

                            {{-- Description with Copy & PDF --}}
                            <div class="pt-4 border-t border-gray-100">
                                <div class="flex items-center justify-between mb-3">
                                    <h3 class="text-sm font-bold text-gray-900">Deskripsi</h3>
                                    
                                    <div class="flex items-center gap-2">
                                        {{-- Copy Button --}}
                                        <button
                                            type="button"
                                            onclick="copyDescription()"
                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 border border-gray-200 text-gray-600 hover:bg-gray-50 rounded-lg text-xs font-medium transition"
                                        >
                                            <i data-lucide="copy" class="w-3.5 h-3.5"></i>
                                            <span>Copy</span>
                                        </button>

                                        {{-- PDF Button --}}
                                        <a
                                            href="{{ route('tickets.export-pdf', $ticket->id) }}"
                                            target="_blank"
                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-sea-blue-600 hover:bg-sea-blue-700 text-white rounded-lg text-xs font-medium transition"
                                        >
                                            <i data-lucide="file-text" class="w-3.5 h-3.5"></i>
                                            <span>Save PDF</span>
                                        </a>
                                    </div>
                                </div>

                                <div class="relative">
                                    <p id="ticket-description" class="text-sm text-gray-700 leading-relaxed whitespace-pre-wrap">
                                        {{ $ticket->Description }}
                                    </p>
                                </div>
                            </div>

                            {{-- Gallery Attachments --}}
                            @if($ticket->images && $ticket->images->count() > 1)
                                <div class="pt-4 border-t border-gray-100">
                                    <h3 class="text-sm font-bold text-gray-900 mb-3">Lampiran ({{ $ticket->images->count() }})</h3>
                                    <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                                        @foreach($ticket->images as $img)
                                            @continue($loop->first)
                                            <div class="relative rounded-xl overflow-hidden border border-gray-200 bg-gray-50 group cursor-pointer hover:shadow-md transition-all duration-200">
                                                <img
                                                    src="{{ asset('storage/'.$img->Path) }}"
                                                    class="w-full h-32 md:h-36 lg:h-40 object-cover group-hover:scale-105 transition-transform duration-300"
                                                    alt="Lampiran"
                                                >
                                                <div class="absolute inset-0 bg-black/0 group-hover:bg-black/20 transition-all duration-200"></div>
                                                <div class="absolute bottom-2 right-2 opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                                                    <a
                                                        href="{{ asset('storage/'.$img->Path) }}"
                                                        download
                                                        class="inline-flex items-center gap-1 px-2 py-1 bg-white/90 backdrop-blur-sm rounded-lg text-[10px] font-medium text-gray-700 hover:bg-white transition"
                                                    >
                                                        <i data-lucide="download" class="w-3 h-3"></i>
                                                        <span>Download</span>
                                                    </a>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Sidebar (1/3) - Related News --}}
                @if(isset($relatedNews) && $relatedNews->count() > 0)
                    <div class="lg:col-span-1">
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition-shadow duration-300 sticky top-6">
                            {{-- Header --}}
                            <div class="px-5 py-4 border-b border-gray-100 bg-gradient-to-r from-indigo-50 via-white to-white">
                                <div class="flex items-center gap-2">
                                    <div class="p-2 bg-indigo-100 rounded-lg">
                                        <i data-lucide="newspaper" class="w-5 h-5 text-indigo-600"></i>
                                    </div>
                                    <div>
                                        <h3 class="text-sm font-bold text-gray-900">
                                            Saran Berita
                                        </h3>
                                        <p class="text-[10px] text-gray-500">
                                            Berita terkait untuk Anda
                                        </p>
                                    </div>
                                </div>
                            </div>

                            {{-- Content --}}
                            <div class="p-4 space-y-3">
                                @foreach($relatedNews as $related)
                                    <a href="{{ route('tickets.show', $related->id) }}"
                                       class="block p-3 rounded-xl border border-gray-100 hover:border-sea-blue-200 hover:bg-gradient-to-br hover:from-sea-blue-50 hover:to-white transition-all duration-200 group">
                                        <div class="flex items-start gap-3">
                                            {{-- Thumbnail --}}
                                            @if($related->images && $related->images->count())
                                                <div class="w-16 h-16 rounded-lg overflow-hidden bg-gray-100 flex-shrink-0">
                                                    <img
                                                        src="{{ asset('storage/'.$related->images->first()->Path) }}"
                                                        alt="Thumbnail"
                                                        class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-200"
                                                    >
                                                </div>
                                            @else
                                                <div class="w-16 h-16 rounded-lg bg-gradient-to-br from-sea-blue-100 to-sea-blue-200 flex items-center justify-center flex-shrink-0">
                                                    <i data-lucide="image" class="w-6 h-6 text-sea-blue-400"></i>
                                                </div>
                                            @endif

                                            {{-- Info --}}
                                            <div class="flex-1 min-w-0">
                                                <h4 class="text-xs font-semibold text-gray-900 line-clamp-2 group-hover:text-sea-blue-600 transition-colors">
                                                    {{ $related->Title }}
                                                </h4>

                                                <div class="flex items-center gap-2 mt-2">
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[9px] font-medium
                                                        {{ $related->Sentiment == 'positive' ? 'bg-green-100 text-green-700' :
                                                           ($related->Sentiment == 'negative' ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-700') }}">
                                                        {{ ucfirst($related->Sentiment) }}
                                                    </span>

                                                    <span class="text-[9px] text-gray-400">
                                                        {{ $related->PublishedDate?->diffForHumans() ?? 'Baru' }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                @endforeach

                                {{-- View All --}}
                                <a href="{{ route('tickets.index') }}"
                                   class="block w-full text-center px-4 py-2.5 border border-gray-200 text-sea-blue-600 hover:bg-sea-blue-50 rounded-xl text-xs font-medium transition">
                                    Lihat Semua Berita
                                </a>
                            </div>
                        </div>
                    </div>
                @endif

            </div>
        </div>
    </div>

    {{-- Modal Eskalasi --}}
    <div
        x-cloak
        x-show="showEscalationModal"
        class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm"
        x-transition.opacity
    >
        <div
            class="bg-white rounded-2xl shadow-2xl border border-gray-100 w-full max-w-2xl mx-4 overflow-hidden"
            @click.away="showEscalationModal = false"
            x-transition.scale
        >
            {{-- Header --}}
            <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-sea-blue-50 via-white to-white">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-sea-blue-100 rounded-lg">
                            <i data-lucide="share-2" class="w-5 h-5 text-sea-blue-600"></i>
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-gray-900">
                                Eskalasi Berita
                            </h3>
                            <p class="text-[10px] text-gray-500" x-text="`Kirim ke ${selectedPlatforms.length} platform`">
                                Kirim ke platform
                            </p>
                        </div>
                    </div>
                    <button type="button" class="text-gray-400 hover:text-gray-600 transition" @click="showEscalationModal = false">
                        <i data-lucide="x" class="w-5 h-5"></i>
                    </button>
                </div>
            </div>

            {{-- Body --}}
            <form
                action="{{ route('tickets.escalate', $ticket->id) }}"
                method="POST"
                class="px-6 py-5 space-y-4"
                @submit="isSubmitting = true"
            >
                @csrf

                <input type="hidden" name="platforms" :value="JSON.stringify(selectedPlatforms)">
                <input type="hidden" name="contact_id" :value="contactId">

                {{-- Selected Platforms --}}
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-2">
                        Platform Terpilih
                    </label>
                    <div class="flex flex-wrap gap-2">
                        <template x-for="platform in selectedPlatforms" :key="platform">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-sea-blue-50 border border-sea-blue-200 text-sea-blue-700 rounded-lg text-xs font-medium">
                                <i :data-lucide="getPlatformIcon(platform)" class="w-4 h-4"></i>
                                <span x-text="platform.charAt(0).toUpperCase() + platform.slice(1)"></span>
                            </span>
                        </template>
                    </div>
                </div>

                {{-- Kontak atau manual --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">
                            Kontak (opsional)
                        </label>
                        <input type="hidden" name="contact_id" :value="contactId">

                        <div class="relative">
                            <button
                                type="button"
                                @click="showContactDropdown = !showContactDropdown"
                                class="w-full flex items-center justify-between rounded-xl border border-gray-300 bg-white px-3 py-2.5 text-left text-sm text-gray-700 hover:border-sea-blue-400 focus:border-sea-blue-500 focus:ring-1 focus:ring-sea-blue-500 transition"
                            >
                                <span class="flex items-center gap-2">
                                    <template x-if="selectedContactName">
                                        <span
                                            class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-sea-blue-100 text-sea-blue-700 text-xs font-semibold uppercase"
                                        >
                                            <span x-text="selectedContactName.charAt(0)"></span>
                                        </span>
                                    </template>

                                    <span class="flex flex-col">
                                        <span
                                            class="text-xs font-medium text-gray-900"
                                            x-text="selectedContactName || 'Pilih kontak...'"
                                        ></span>
                                    </span>
                                </span>

                                <i data-lucide="chevron-down" class="w-4 h-4 text-gray-400"></i>
                            </button>

                            {{-- Dropdown list --}}
                            <div
                                x-show="showContactDropdown"
                                @click.away="showContactDropdown = false"
                                x-transition
                                class="absolute z-50 mt-1 w-full max-h-60 overflow-y-auto rounded-xl border border-gray-200 bg-white shadow-xl"
                            >
                                @forelse($contacts ?? [] as $contact)
                                    @php
                                        $initial = mb_substr($contact->Name, 0, 1);
                                    @endphp
                                    <button
                                        type="button"
                                        class="w-full px-3 py-2.5 flex items-start gap-2 hover:bg-sea-blue-50 text-left text-xs text-gray-800 transition"
                                        @click="
                                            contactId = '{{ $contact->id }}';
                                            selectedContactName = '{{ addslashes($contact->Name) }}';
                                            showContactDropdown = false;
                                        "
                                    >
                                        <span
                                            class="inline-flex items-center justify-center w-9 h-9 rounded-full bg-sea-blue-100 text-sea-blue-700 text-xs font-semibold uppercase flex-shrink-0"
                                        >
                                            {{ $initial }}
                                        </span>

                                        <span class="flex flex-col">
                                            <span class="font-medium text-gray-900">
                                                {{ $contact->Name }}
                                            </span>
                                            @if($contact->Phone)
                                                <span class="flex items-center gap-1 text-[10px] text-gray-600">
                                                    <i data-lucide="message-circle" class="w-3 h-3"></i>
                                                    {{ $contact->Phone }}
                                                </span>
                                            @endif

                                            @if($contact->Email)
                                                <span class="flex items-center gap-1 text-[10px] text-gray-600">
                                                    <i data-lucide="mail" class="w-3 h-3"></i>
                                                    {{ $contact->Email }}
                                                </span>
                                            @endif
                                        </span>
                                    </button>
                                @empty
                                    <div class="px-3 py-2 text-[10px] text-gray-400">
                                        Belum ada kontak terdaftar.
                                    </div>
                                @endforelse
                            </div>
                        </div>

                        <p class="mt-1.5 text-[10px] text-gray-400">
                            Kontak akan mengisi email / nomor otomatis
                        </p>
                    </div>

                    {{-- Recipient manual --}}
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">
                            Penerima (email atau nomor WhatsApp)
                        </label>
                        <input
                            type="text"
                            name="recipient"
                            x-model="recipient"
                            class="w-full rounded-xl border-gray-300 focus:border-sea-blue-500 focus:ring-sea-blue-500 text-sm"
                            placeholder="contoh: email@domain.go.id atau 62812xxxx"
                        >
                        <p class="mt-1.5 text-[10px] text-gray-400">
                            Jika kosong dan kontak dipilih, sistem akan memakai data dari kontak
                        </p>
                    </div>
                </div>

                {{-- Pesan --}}
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">
                        Pesan
                    </label>
                    <textarea
                        name="message"
                        rows="4"
                        x-model="message"
                        class="w-full rounded-xl border-gray-300 focus:border-sea-blue-500 focus:ring-sea-blue-500 text-sm"
                        x-init="message = `Yth,\n\nTerkait berita: &quot;{{ addslashes($ticket->Title) }}&quot;.\n\nMohon tindak lanjut.`"
                    ></textarea>
                    <p class="mt-1.5 text-[10px] text-gray-400">
                        Pesan ini akan dicatat di log eskalasi dan menjadi isi email / WhatsApp
                    </p>
                </div>

                {{-- Footer --}}
                <div class="flex justify-end gap-2 pt-4 border-t border-gray-100">
                    <button
                        type="button"
                        class="px-4 py-2 border border-gray-300 rounded-xl text-xs text-gray-700 hover:bg-gray-50 transition"
                        @click="showEscalationModal = false"
                        :disabled="isSubmitting"
                    >
                        Batal
                    </button>
                    <button
                        type="submit"
                        class="px-4 py-2 bg-sea-blue-600 hover:bg-sea-blue-700 text-white rounded-xl text-xs font-medium inline-flex items-center gap-2 transition disabled:opacity-60 disabled:cursor-not-allowed"
                        :disabled="isSubmitting"
                    >
                        <svg
                            x-show="isSubmitting"
                            class="w-4 h-4 animate-spin text-white"
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                        >
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 
                                    5.291A7.962 7.962 0 014 12H0c0 
                                    3.042 1.135 5.824 3 7.938l3-2.647z">
                            </path>
                        </svg>

                        <i x-show="!isSubmitting" data-lucide="send" class="w-4 h-4"></i>

                        <span x-text="isSubmitting ? 'Mengirim...' : 'Kirim Eskalasi'"></span>
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

                // Copy Description Function
                function copyDescription() {
                    const text = document.getElementById('ticket-description').innerText;
                    navigator.clipboard.writeText(text).then(() => {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: 'Deskripsi berhasil disalin ke clipboard!',
                            confirmButtonColor: '#0284c7',
                            timer: 2000,
                            showConfirmButton: false,
                        });
                    }).catch(err => {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: 'Gagal menyalin deskripsi',
                            confirmButtonColor: '#dc2626',
                        });
                    });
                }

                window.copyDescription = copyDescription;
            });
        </script>

        @if(session('success'))
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: @json(session('success')),
                        confirmButtonColor: '#0284c7',
                    });
                });
            </script>
        @endif

        @if(session('error'))
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: @json(session('error')),
                        confirmButtonColor: '#dc2626',
                    });
                });
            </script>
        @endif
    @endpush
</x-app-layout>