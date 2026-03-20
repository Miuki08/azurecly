<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-sea-blue-800 leading-tight">
                {{ __('Manajemen Berita') }}
            </h2>
            <a href="{{ route('tickets.create') }}" class="bg-sea-blue-600 hover:bg-sea-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium flex items-center transition duration-150">
                <i data-lucide="plus" class="w-4 h-4 mr-2"></i>
                Tambah Berita
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- Filter Section --}}
            <div class="bg-white rounded-lg shadow mb-6 p-4">
                <form method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Cari</label>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Judul, deskripsi..." class="w-full rounded-lg border-gray-300 focus:border-sea-blue-500 focus:ring-sea-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Sentiment</label>
                        <select name="sentiment" class="w-full rounded-lg border-gray-300 focus:border-sea-blue-500 focus:ring-sea-blue-500">
                            <option value="">Semua</option>
                            <option value="positive" {{ request('sentiment') == 'positive' ? 'selected' : '' }}>Positive</option>
                            <option value="neutral" {{ request('sentiment') == 'neutral' ? 'selected' : '' }}>Neutral</option>
                            <option value="negative" {{ request('sentiment') == 'negative' ? 'selected' : '' }}>Negative</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Priority</label>
                        <select name="priority" class="w-full rounded-lg border-gray-300 focus:border-sea-blue-500 focus:ring-sea-blue-500">
                            <option value="">Semua</option>
                            <option value="high" {{ request('priority') == 'high' ? 'selected' : '' }}>High</option>
                            <option value="medium" {{ request('priority') == 'medium' ? 'selected' : '' }}>Medium</option>
                            <option value="low" {{ request('priority') == 'low' ? 'selected' : '' }}>Low</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
                        <input type="text" name="category" value="{{ request('category') }}" placeholder="Kategori..." class="w-full rounded-lg border-gray-300 focus:border-sea-blue-500 focus:ring-sea-blue-500">
                    </div>
                    <div class="flex items-end space-x-2">
                        <button type="submit" class="bg-sea-blue-600 hover:bg-sea-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition duration-150">
                            <i data-lucide="search" class="w-4 h-4 inline mr-1"></i>
                            Filter
                        </button>
                        <a href="{{ route('tickets.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition duration-150">
                            Reset
                        </a>
                    </div>
                </form>
            </div>

            {{-- Table Section --}}
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Judul</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kategori</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sentiment</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Priority</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Lokasi</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Views</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($tickets as $ticket)
                            <tr class="hover:bg-gray-50 transition duration-150">
                                <td class="px-6 py-4">
                                    <a href="{{ route('tickets.show', $ticket->id) }}" class="text-sea-blue-600 hover:text-sea-blue-800 font-medium hover:underline">
                                        {{ $ticket->Title }}
                                    </a>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">{{ $ticket->Category }}</td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-1 text-xs font-medium rounded-full 
                                        {{ $ticket->Sentiment == 'positive' ? 'bg-green-100 text-green-800' : 
                                           ($ticket->Sentiment == 'negative' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                                        {{ ucfirst($ticket->Sentiment) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-1 text-xs font-medium rounded-full 
                                        {{ $ticket->Priority == 'high' ? 'bg-red-100 text-red-800' : 
                                           ($ticket->Priority == 'medium' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800') }}">
                                        {{ ucfirst($ticket->Priority) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">{{ $ticket->Location ?? '-' }}</td>
                                <td class="px-6 py-4 text-sm text-gray-600">{{ $ticket->ViewCount }}</td>
                                <td class="px-6 py-4 text-sm text-gray-600">{{ $ticket->PublishedDate ? $ticket->PublishedDate->format('d M Y') : '-' }}</td>
                                <td class="px-6 py-4 text-right space-x-2">
                                    <a href="{{ route('tickets.edit', $ticket->id) }}" class="text-sea-blue-600 hover:text-sea-blue-800 inline-flex items-center">
                                        <i data-lucide="edit" class="w-4 h-4"></i>
                                    </a>
                                    <form action="{{ route('tickets.destroy', $ticket->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800 inline-flex items-center" onclick="return confirm('Yakin ingin menghapus berita ini?')">
                                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="px-6 py-12 text-center text-gray-500">
                                    <i data-lucide="inbox" class="w-12 h-12 mx-auto text-gray-300 mb-3"></i>
                                    <p>Belum ada data berita</p>
                                    <a href="{{ route('tickets.create') }}" class="mt-2 inline-block text-sea-blue-600 hover:text-sea-blue-800">Tambah berita pertama</a>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                {{-- Pagination --}}
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $tickets->links() }}
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