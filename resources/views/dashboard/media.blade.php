<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-sea-blue-800 leading-tight">
                Dashboard Media
            </h2>

            {{-- Filter area (placeholder) --}}
            <div class="flex items-center gap-2">
                {{-- Contoh filter sentiment (default positive) --}}
                <form method="GET" action="{{ route('dashboard.media') }}" class="flex items-center gap-2">
                    <select name="sentiment"
                            class="text-xs border-gray-300 rounded-md focus:ring-sea-blue-500 focus:border-sea-blue-500">
                        <option value="positive" {{ request('sentiment', 'positive') === 'positive' ? 'selected' : '' }}>
                            Sentiment Positif
                        </option>
                        <option value="all" {{ request('sentiment') === 'all' ? 'selected' : '' }}>
                            Semua Sentiment
                        </option>
                    </select>
                    {{-- Tambah filter lain di sini kalau perlu (region, date, dsb) --}}
                </form>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            {{-- TIDAK ADA cards statistik di media --}}

            {{-- list berita + chip ranking --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                {{-- berita terbaru --}}
                <div class="lg:col-span-2 space-y-3">
                    <h3 class="text-sm font-semibold text-gray-800 mb-1 flex items-center">
                        <div class="p-1.5 bg-sea-blue-100 rounded-lg mr-2">
                            <i data-lucide="clock-3" class="w-4 h-4 text-sea-blue-600"></i>
                        </div>
                        Berita Terkini untuk Media
                    </h3>

                    @forelse($latestTickets as $ticket)
                        <a href="{{ route('tickets.show', $ticket->id) }}"
                           class="flex gap-3 bg-white border border-gray-100 rounded-lg p-3 hover:border-sea-blue-200 hover:shadow-sm transition">
                            @php
                                $img = $ticket->images->first() ?? null;
                            @endphp

                            {{-- Thumbnail --}}
                            @if($img)
                                <div class="w-24 h-16 rounded-md overflow-hidden bg-gray-100 flex-shrink-0">
                                    <img src="{{ asset('storage/'.$img->Path) }}"
                                         class="w-full h-full object-cover"
                                         alt="Media">
                                </div>
                            @else
                                <div class="w-24 h-16 rounded-md bg-gray-100 flex items-center justify-center text-[11px] text-gray-400 flex-shrink-0">
                                    No Image
                                </div>
                            @endif

                            <div class="flex-1 flex flex-col">
                                {{-- Judul --}}
                                <h4 class="text-sm font-semibold text-gray-900 line-clamp-2">
                                    {{ $ticket->Title }}
                                </h4>

                                {{-- Description --}}
                                <p class="mt-1 text-[11px] text-gray-600 line-clamp-2">
                                    {{ Str::limit($ticket->Description, 140) }}
                                </p>

                                {{-- sentiment + priority + region + date --}}
                                <div class="mt-2 flex items-center justify-between gap-2">
                                    <div class="flex flex-wrap items-center gap-2 text-[10px]">
                                        {{-- Sentiment (di media default positive, tapi tetap kasih chip) --}}
                                        @php
                                            $sentimentClasses = match ($ticket->Sentiment) {
                                                'positive' => 'bg-emerald-50 text-emerald-700 border-emerald-100',
                                                'negative' => 'bg-red-50 text-red-700 border-red-100',
                                                'neutral'  => 'bg-amber-50 text-amber-700 border-amber-100',
                                                default    => 'bg-gray-50 text-gray-600 border-gray-100',
                                            };

                                            $sentimentIcon = match ($ticket->Sentiment) {
                                                'positive' => 'smile-plus',
                                                'negative' => 'frown',
                                                'neutral'  => 'minus',
                                                default    => 'activity',
                                            };
                                        @endphp

                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full border {{ $sentimentClasses }}">
                                            <i data-lucide="{{ $sentimentIcon }}" class="w-3 h-3 mr-1"></i>
                                            {{ ucfirst($ticket->Sentiment ?? 'Unknown') }}
                                        </span>

                                        {{-- Priority --}}
                                        @php
                                            $priorityClasses = match ($ticket->Priority) {
                                                'high'   => 'bg-red-50 text-red-700 border-red-100',
                                                'medium' => 'bg-amber-50 text-amber-700 border-amber-100',
                                                'low'    => 'bg-gray-50 text-gray-600 border-gray-100',
                                                default  => 'bg-gray-50 text-gray-600 border-gray-100',
                                            };

                                            $priorityIcon = match ($ticket->Priority) {
                                                'high'   => 'flame',
                                                'medium' => 'alert-triangle',
                                                'low'    => 'circle',
                                                default  => 'alert-circle',
                                            };
                                        @endphp

                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full border {{ $priorityClasses }}">
                                            <i data-lucide="{{ $priorityIcon }}" class="w-3 h-3 mr-1"></i>
                                            {{ ucfirst($ticket->Priority ?? 'Unknown') }}
                                        </span>

                                        {{-- Region --}}
                                        @if($ticket->Region)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full border bg-sky-50 text-sky-700 border-sky-100">
                                                <i data-lucide="map-pin" class="w-3 h-3 mr-1"></i>
                                                {{ $ticket->Region }}
                                            </span>
                                        @endif
                                    </div>

                                    {{-- Date --}}
                                    @if($ticket->PublishedDate)
                                        <div class="text-[10px] text-gray-500 flex items-center flex-shrink-0">
                                            <i data-lucide="calendar-clock" class="w-3 h-3 mr-1"></i>
                                            <span>{{ $ticket->PublishedDate->format('d M Y H:i') }}</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </a>
                    @empty
                        <p class="text-xs text-gray-500">Belum ada berita.</p>
                    @endforelse
                </div>

                {{-- actor / tag / region --}}
                <div class="space-y-4">
                    {{-- Aktor terpopuler --}}
                    <div class="bg-white border border-gray-100 rounded-lg p-4">
                        <div class="flex items-center mb-3">
                            <div class="p-1.5 bg-sea-blue-100 rounded-lg mr-2">
                                <i data-lucide="users" class="w-4 h-4 text-sea-blue-600"></i>
                            </div>
                            <h3 class="text-xs font-semibold text-gray-800">Aktor Terpopuler</h3>
                        </div>

                        <div class="flex flex-wrap gap-2">
                            @forelse($topActors as $row)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full bg-slate-50 text-slate-700 text-[11px] border border-slate-100">
                                    <i data-lucide="user" class="w-3 h-3 mr-1"></i>
                                    <span class="truncate max-w-[140px]">{{ $row->Actor }}</span>
                                </span>
                            @empty
                                <p class="text-[11px] text-gray-400">Belum ada data aktor.</p>
                            @endforelse
                        </div>
                    </div>

                    {{-- Tag terpopuler --}}
                    <div class="bg-white border border-gray-100 rounded-lg p-4">
                        <div class="flex items-center mb-3">
                            <div class="p-1.5 bg-emerald-100 rounded-lg mr-2">
                                <i data-lucide="hash" class="w-4 h-4 text-emerald-600"></i>
                            </div>
                            <h3 class="text-xs font-semibold text-gray-800">Tag Terpopuler</h3>
                        </div>

                        <div class="flex flex-wrap gap-2">
                            @forelse($topTags as $row)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full bg-emerald-50 text-emerald-700 text-[11px] border border-emerald-100">
                                    #{{ $row->Tag }}
                                </span>
                            @empty
                                <p class="text-[11px] text-gray-400">Belum ada data tag.</p>
                            @endforelse
                        </div>
                    </div>

                    {{-- Region terpopuler --}}
                    <div class="bg-white border border-gray-100 rounded-lg p-4">
                        <div class="flex items-center mb-3">
                            <div class="p-1.5 bg-sky-100 rounded-lg mr-2">
                                <i data-lucide="map-pin" class="w-4 h-4 text-sky-600"></i>
                            </div>
                            <h3 class="text-xs font-semibold text-gray-800">Region Terpopuler</h3>
                        </div>

                        <div class="flex flex-wrap gap-2">
                            @forelse($topRegions as $row)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full bg-sky-50 text-sky-700 text-[11px] border border-sky-100">
                                    <i data-lucide="map" class="w-3 h-3 mr-1"></i>
                                    {{ $row->Region }}
                                </span>
                            @empty
                                <p class="text-[11px] text-gray-400">Belum ada data region.</p>
                            @endforelse
                        </div>
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