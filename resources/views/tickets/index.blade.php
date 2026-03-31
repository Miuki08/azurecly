<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-xl text-sea-blue-800 leading-tight">
                    News Management
                </h2>
                <!-- <p class="text-xs text-gray-500 mt-0.5">
                    Kelola berita, filter berdasarkan sentiment, priority, dan kategori.
                </p> -->
            </div>

            <div class="flex items-center gap-2">
                {{-- Toggle Filter (icon only) --}}
               <button
                    type="button"
                    id="toggle-filter"
                    class="inline-flex items-center justify-center px-3 py-2 rounded-lg border border-sea-blue-100 text-sea-blue-700 bg-white hover:bg-sea-blue-50 hover:border-sea-blue-200 transition-colors duration-150 text-xs"
                    aria-label="Toggle filters"
                >
                    <i data-lucide="filter" class="w-4 h-4 mr-1"></i>
                    <span class="hidden sm:inline">Filter</span>
                </button>

                <a href="{{ route('tickets.create') }}"
                   class="inline-flex items-center gap-1.5 bg-sea-blue-600 hover:bg-sea-blue-700 text-white px-3 py-1.5 rounded-lg text-xs font-medium transition duration-150">
                    <i data-lucide="plus" class="w-4 h-4"></i>
                    Add News
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Filter Section (hidden by default) --}}
            <div id="filter-panel" class="bg-white border border-sea-blue-100 rounded-xl shadow-sm mb-4 px-3 py-3 sm:px-4 sm:py-4 hidden">
                <form method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-3 text-xs sm:text-sm">
                    {{-- Search --}}
                    <div class="space-y-1">
                        <label class="block font-medium text-gray-600 text-xs">Search</label>
                        <input
                            type="text"
                            name="search"
                            value="{{ request('search') }}"
                            placeholder="Title, description..."
                            class="w-full rounded-lg border-gray-200 focus:border-sea-blue-500 focus:ring-sea-blue-500 text-xs py-1.5"
                        >
                    </div>

                    {{-- Sentiment --}}
                    <div class="space-y-1">
                        <label class="block font-medium text-gray-600 text-xs">Sentiment</label>
                        <select
                            name="sentiment"
                            class="w-full rounded-lg border-gray-200 focus:border-sea-blue-500 focus:ring-sea-blue-500 text-xs py-1.5"
                        >
                            <option value="">All</option>
                            <option value="positive" {{ request('sentiment') == 'positive' ? 'selected' : '' }}>Positive</option>
                            <option value="neutral"  {{ request('sentiment') == 'neutral'  ? 'selected' : '' }}>Neutral</option>
                            <option value="negative" {{ request('sentiment') == 'negative' ? 'selected' : '' }}>Negative</option>
                        </select>
                    </div>

                    {{-- Priority --}}
                    <div class="space-y-1">
                        <label class="block font-medium text-gray-600 text-xs">Priority</label>
                        <select
                            name="priority"
                            class="w-full rounded-lg border-gray-200 focus:border-sea-blue-500 focus:ring-sea-blue-500 text-xs py-1.5"
                        >
                            <option value="">All</option>
                            <option value="high"   {{ request('priority') == 'high'   ? 'selected' : '' }}>High</option>
                            <option value="medium" {{ request('priority') == 'medium' ? 'selected' : '' }}>Medium</option>
                            <option value="low"    {{ request('priority') == 'low'    ? 'selected' : '' }}>Low</option>
                        </select>
                    </div>

                    {{-- Category (select dari tabel) --}}
                    <div class="space-y-1">
                        <label class="block font-medium text-gray-600 text-xs">Category</label>
                        <select
                            name="category"
                            class="w-full rounded-lg border-gray-200 focus:border-sea-blue-500 focus:ring-sea-blue-500 text-xs py-1.5"
                        >
                            <option value="">All</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}"
                                    {{ request('category') == $category->id ? 'selected' : '' }}>
                                    {{ $category->Name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Actions --}}
                    <div class="flex items-end gap-2">
                        <button
                            type="submit"
                            class="inline-flex items-center justify-center gap-1.5 bg-sea-blue-600 hover:bg-sea-blue-700 text-white px-3 py-1.5 rounded-lg text-xs font-medium transition duration-150"
                        >
                            <i data-lucide="search" class="w-3.5 h-3.5"></i>
                            Filter
                        </button>
                        <a
                            href="{{ route('tickets.index') }}"
                            class="inline-flex items-center justify-center gap-1.5 bg-gray-100 hover:bg-gray-200 text-gray-700 px-3 py-1.5 rounded-lg text-xs font-medium transition duration-150"
                        >
                            Reset
                        </a>
                    </div>
                </form>
            </div>

            {{-- Table Section --}}
            <div class="bg-white border border-gray-100 rounded-xl shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-100">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-[11px] font-semibold text-gray-500 uppercase tracking-wide">
                                    Title
                                </th>
                                <th class="px-6 py-3 text-left text-[11px] font-semibold text-gray-500 uppercase tracking-wide">
                                    Location
                                </th>
                                <th class="px-6 py-3 text-left text-[11px] font-semibold text-gray-500 uppercase tracking-wide">
                                    Category
                                </th>
                                <th class="px-6 py-3 text-left text-[11px] font-semibold text-gray-500 uppercase tracking-wide">
                                    Sentiment
                                </th>
                                <th class="px-6 py-3 text-left text-[11px] font-semibold text-gray-500 uppercase tracking-wide">
                                    Priority
                                </th>
                                <th class="px-6 py-3 text-left text-[11px] font-semibold text-gray-500 uppercase tracking-wide">
                                    Views
                                </th>
                                <th class="px-6 py-3 text-left text-[11px] font-semibold text-gray-500 uppercase tracking-wide">
                                    Date
                                </th>
                                <th class="px-6 py-3 text-right text-[11px] font-semibold text-gray-500 uppercase tracking-wide">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            @forelse($tickets as $ticket)
                                <tr class="hover:bg-sea-blue-50/40 transition-colors duration-150">
                                    <td class="px-6 py-4 text-sm">
                                        <a href="{{ route('tickets.show', $ticket->id) }}"
                                           class="text-sea-blue-700 hover:text-sea-blue-900 font-medium hover:underline">
                                            {{ $ticket->Title }}
                                        </a>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-600">
                                        {{ $ticket->Location ?? '-' }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-600">
                                        {{ $ticket->Category }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center px-2.5 py-0.5 text-[11px] font-medium rounded-full
                                            {{ $ticket->Sentiment == 'positive'
                                                ? 'bg-green-50 text-green-700 ring-1 ring-inset ring-green-100'
                                                : ($ticket->Sentiment == 'negative'
                                                    ? 'bg-red-50 text-red-700 ring-1 ring-inset ring-red-100'
                                                    : 'bg-yellow-50 text-yellow-700 ring-1 ring-inset ring-yellow-100') }}">
                                            {{ ucfirst($ticket->Sentiment) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center px-2.5 py-0.5 text-[11px] font-medium rounded-full
                                            {{ $ticket->Priority == 'high'
                                                ? 'bg-red-50 text-red-700 ring-1 ring-inset ring-red-100'
                                                : ($ticket->Priority == 'medium'
                                                    ? 'bg-accent/10 text-accent ring-1 ring-inset ring-accent/20'
                                                    : 'bg-green-50 text-green-700 ring-1 ring-inset ring-green-100') }}">
                                            {{ ucfirst($ticket->Priority) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-600">
                                        {{ $ticket->ViewCount }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-600">
                                        {{ $ticket->PublishedDate ? $ticket->PublishedDate->format('d M Y') : '-' }}
                                    </td>
                                    <td class="px-6 py-4 text-right space-x-2">
                                        <a href="{{ route('tickets.edit', $ticket->id) }}"
                                           class="inline-flex items-center justify-center w-8 h-8 rounded-full text-sea-blue-700 hover:bg-sea-blue-50 transition-colors duration-150">
                                            <i data-lucide="edit-3" class="w-4 h-4"></i>
                                        </a>
                                        <form action="{{ route('tickets.destroy', $ticket->id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button
                                                type="submit"
                                                class="inline-flex items-center justify-center w-8 h-8 rounded-full text-red-600 hover:bg-red-50 transition-colors duration-150"
                                                onclick="return confirm('Are you sure you want to delete this news?')"
                                            >
                                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-6 py-12 text-center text-gray-500">
                                        <i data-lucide="inbox" class="w-10 h-10 mx-auto text-gray-300 mb-3"></i>
                                        <p class="text-sm font-medium">No news data yet</p>
                                        <a href="{{ route('tickets.create') }}"
                                           class="mt-2 inline-block text-sea-blue-700 hover:text-sea-blue-900 text-sm">
                                            Add the first news
                                        </a>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                <div class="px-4 sm:px-6 py-3 border-t border-gray-100">
                    {{ $tickets->links() }}
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://unpkg.com/lucide@latest"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                lucide.createIcons();

                const toggleBtn = document.getElementById('toggle-filter');
                const panel = document.getElementById('filter-panel');

                if (toggleBtn && panel) {
                    toggleBtn.addEventListener('click', () => {
                        panel.classList.toggle('hidden');
                    });
                }
            });
        </script>
    @endpush
</x-app-layout>
