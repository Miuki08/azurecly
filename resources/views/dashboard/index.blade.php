<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-sea-blue-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- Stat Cards dengan Icon --}}
            <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-6">
                
                {{-- Total Berita --}}
                <div class="bg-gradient-to-br from-sea-blue-50 to-white rounded-lg shadow p-5">
                    <div class="flex items-center justify-between mb-2">
                        <div class="p-2 bg-sea-blue-100 rounded-lg">
                            <i data-lucide="newspaper" class="w-5 h-5 text-sea-blue-600"></i>
                        </div>
                        <span class="text-2xl font-bold text-gray-800">{{ $stats['total_berita'] }}</span>
                    </div>
                    <p class="text-sm text-gray-600">Total Berita</p>
                </div>

                {{-- Berita Hari Ini --}}
                <div class="bg-gradient-to-br from-green-50 to-white rounded-lg shadow p-5">
                    <div class="flex items-center justify-between mb-2">
                        <div class="p-2 bg-green-100 rounded-lg">
                            <i data-lucide="calendar" class="w-5 h-5 text-green-600"></i>
                        </div>
                        <span class="text-2xl font-bold text-gray-800">{{ $stats['berita_hari_ini'] }}</span>
                    </div>
                    <p class="text-sm text-gray-600">Hari Ini</p>
                </div>

                {{-- Positive Sentiment --}}
                <div class="bg-gradient-to-br from-emerald-50 to-white rounded-lg shadow p-5">
                    <div class="flex items-center justify-between mb-2">
                        <div class="p-2 bg-emerald-100 rounded-lg">
                            <i data-lucide="thumbs-up" class="w-5 h-5 text-emerald-600"></i>
                        </div>
                        <span class="text-2xl font-bold text-gray-800">{{ $stats['positive_sentiment'] }}</span>
                    </div>
                    <p class="text-sm text-gray-600">Sentiment Positif</p>
                </div>

                {{-- Negative Sentiment --}}
                <div class="bg-gradient-to-br from-red-50 to-white rounded-lg shadow p-5">
                    <div class="flex items-center justify-between mb-2">
                        <div class="p-2 bg-red-100 rounded-lg">
                            <i data-lucide="thumbs-down" class="w-5 h-5 text-red-600"></i>
                        </div>
                        <span class="text-2xl font-bold text-gray-800">{{ $stats['negative_sentiment'] }}</span>
                    </div>
                    <p class="text-sm text-gray-600">Sentiment Negatif</p>
                </div>

                {{-- High Priority --}}
                <div class="bg-gradient-to-br from-yellow-50 to-white rounded-lg shadow p-5">
                    <div class="flex items-center justify-between mb-2">
                        <div class="p-2 bg-yellow-100 rounded-lg">
                            <i data-lucide="alert-triangle" class="w-5 h-5 text-yellow-600"></i>
                        </div>
                        <span class="text-2xl font-bold text-gray-800">{{ $stats['high_priority'] }}</span>
                    </div>
                    <p class="text-sm text-gray-600">High Priority</p>
                </div>
            </div>

            {{-- Maps Section --}}
            <div class="bg-white rounded-lg shadow mb-6 overflow-hidden">
                <div class="p-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                        <div class="p-2 bg-sea-blue-100 rounded-lg mr-3">
                            <i data-lucide="map" class="w-5 h-5 text-sea-blue-600"></i>
                        </div>
                        Peta Sebaran Berita
                    </h3>
                </div>
                <div id="map" style="height: 400px; width: 100%;" class="w-full"></div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Kategori Populer --}}
                {{-- Kategori Populer --}}
<div class="bg-white rounded-lg shadow p-6">
    <h3 class="text-lg font-semibold text-gray-800 flex items-center mb-4">
        <div class="p-2 bg-sea-blue-100 rounded-lg mr-3">
            <i data-lucide="trending-up" class="w-5 h-5 text-sea-blue-600"></i>
        </div>
        Kategori Terpopuler
    </h3>
    <div class="space-y-4">
        @php
            $max = $popular_categories->max('total');
        @endphp

        @foreach($popular_categories as $category)
        <div>
            <div class="flex items-center justify-between mb-1">
                <div class="flex items-center">
                    <i data-lucide="folder" class="w-4 h-4 text-gray-400 mr-2"></i>
                    <span class="text-sm font-medium text-gray-700">
                        {{ $category->name ?? 'Tanpa kategori' }}
                    </span>
                </div>
                <span class="text-sm font-semibold text-gray-900">
                    {{ $category->total }} berita
                </span>
            </div>
            <div class="w-full h-2 bg-gray-100 rounded-full overflow-hidden">
                @php
                    $percentage = $max > 0 ? ($category->total / $max) * 100 : 0;
                @endphp
                <div class="h-full bg-sea-blue-500 rounded-full" style="width: {{ $percentage }}%"></div>
            </div>
        </div>
        @endforeach
    </div>
</div>

                {{-- High Priority Negative --}}
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold text-gray-800 flex items-center mb-4">
                        <div class="p-2 bg-red-100 rounded-lg mr-3">
                            <i data-lucide="alert-circle" class="w-5 h-5 text-red-600"></i>
                        </div>
                        High Priority - Negative Sentiment
                    </h3>
                    <div class="space-y-4">
                        @forelse($high_priority_negative as $ticket)
                        <div class="border-b border-gray-100 last:border-0 pb-4 last:pb-0">
                            <div class="flex items-start">
                                <div class="p-1.5 bg-red-50 rounded-lg mr-3 mt-1">
                                    <i data-lucide="alert-triangle" class="w-4 h-4 text-red-500"></i>
                                </div>
                                <div class="flex-1">
                                    <h4 class="font-semibold text-gray-800 hover:text-sea-blue-600 cursor-pointer flex items-center">
                                        {{ $ticket->Title }}
                                        <span class="ml-2 px-2 py-0.5 bg-red-100 text-red-800 text-xs font-medium rounded-full">
                                            High
                                        </span>
                                    </h4>
                                    <p class="text-sm text-gray-600 mt-1 line-clamp-2">
                                        {{ $ticket->Description }}
                                    </p>
                                    <div class="flex items-center mt-2 text-xs text-gray-500">
                                        <i data-lucide="map-pin" class="w-3 h-3 mr-1"></i>
                                        <span>{{ $ticket->Location ?? 'Tidak ada lokasi' }}</span>
                                        <span class="mx-2">•</span>
                                        <i data-lucide="clock" class="w-3 h-3 mr-1"></i>
                                        <span>{{ $ticket->PublishedDate ? $ticket->PublishedDate->diffForHumans() : 'Belum dipublikasi' }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="text-center py-8">
                            <div class="p-3 bg-gray-100 rounded-full inline-block mb-3">
                                <i data-lucide="inbox" class="w-6 h-6 text-gray-400"></i>
                            </div>
                            <p class="text-gray-500">Tidak ada berita high priority dengan negative sentiment</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <style>
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        
        /* Custom marker styles */
        .custom-marker {
            background: transparent;
            border: none;
        }
        
        /* Map container */
        #map {
            z-index: 1;
        }
        
        /* Leaflet popup customization */
        .leaflet-popup-content {
            font-family: 'Inter', sans-serif;
            font-size: 13px;
            line-height: 1.5;
            min-width: 200px;
        }
        
        .leaflet-popup-content h4 {
            margin-bottom: 4px;
            color: #1f2937;
        }
        
        .leaflet-popup-content p {
            margin: 2px 0;
            color: #6b7280;
        }
    </style>
    @endpush

    @push('scripts')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Inisialisasi Lucide Icons
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }

            // Inisialisasi map
            var map = L.map('map').setView([-6.2088, 106.8456], 10);
            
            // Tile layer (OpenStreetMap gratis)
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(map);

            // Data dari controller
            var tickets = @json($maps_data);

            // Fungsi untuk mendapatkan icon berdasarkan sentiment
            function getIcon(sentiment) {
                var color = sentiment === 'positive' ? '#10b981' : 
                           sentiment === 'negative' ? '#ef4444' : '#f59e0b';
                
                return L.divIcon({
                    className: 'custom-marker',
                    html: `<div style="background-color: ${color}; width: 14px; height: 14px; border-radius: 50%; border: 3px solid white; box-shadow: 0 2px 6px rgba(0,0,0,0.3);"></div>`,
                    iconSize: [20, 20],
                    popupAnchor: [0, -10]
                });
            }

            // Tambah markers jika ada data
            if (tickets && tickets.length > 0) {
                tickets.forEach(function(ticket) {
                    if (ticket.Latitude && ticket.Longitude) {
                        var marker = L.marker([parseFloat(ticket.Latitude), parseFloat(ticket.Longitude)], {
                            icon: getIcon(ticket.Sentiment)
                        }).addTo(map);

                        // Popup content
                        var popupContent = `
                            <div style="font-family: 'Inter', sans-serif; padding: 4px;">
                                <h4 style="font-weight: 600; color: #1f2937; margin-bottom: 4px;">${ticket.Title}</h4>
                                <p style="color: #6b7280; font-size: 12px; margin: 2px 0;">
                                    <strong>Lokasi:</strong> ${ticket.Location || 'Tidak ada lokasi'}
                                </p>
                                <p style="color: #6b7280; font-size: 12px; margin: 2px 0;">
                                    <strong>Priority:</strong> 
                                    <span style="color: ${ticket.Priority === 'high' ? '#ef4444' : ticket.Priority === 'medium' ? '#f59e0b' : '#10b981'}">
                                        ${ticket.Priority}
                                    </span> | 
                                    <strong>Sentiment:</strong> 
                                    <span style="color: ${ticket.Sentiment === 'positive' ? '#10b981' : ticket.Sentiment === 'negative' ? '#ef4444' : '#f59e0b'}">
                                        ${ticket.Sentiment}
                                    </span>
                                </p>
                            </div>
                        `;
                        
                        marker.bindPopup(popupContent);
                    }
                });

                // Adjust map bounds to show all markers
                var group = new L.featureGroup();
                tickets.forEach(function(ticket) {
                    if (ticket.Latitude && ticket.Longitude) {
                        group.addLayer(L.marker([parseFloat(ticket.Latitude), parseFloat(ticket.Longitude)]));
                    }
                });
                map.fitBounds(group.getBounds().pad(0.1));
            } else {
                // Jika tidak ada data, tetap di view Jakarta
                console.log('Tidak ada data marker untuk ditampilkan');
            }
        });
    </script>
    @endpush
</x-app-layout>