<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-xl text-sea-blue-800 leading-tight">
                    Contacts
                </h2>
                <!-- <p class="text-xs text-gray-500 mt-0.5">
                    Kelola kontak humas, media, dan relasi penting lainnya.
                </p> -->
            </div>

            <div class="flex items-center gap-2">
                <button
                    type="button"
                    id="toggle-filter"
                    class="inline-flex items-center justify-center px-3 py-2 rounded-lg border border-sea-blue-100 text-sea-blue-700 bg-white hover:bg-sea-blue-50 hover:border-sea-blue-200 transition-colors duration-150 text-xs"
                    aria-label="Toggle filters"
                >
                    <i data-lucide="filter" class="w-4 h-4 mr-1"></i>
                    <span class="hidden sm:inline">Filter</span>
                </button>

                <a href="{{ route('contacts.create') }}"
                   class="inline-flex items-center gap-1.5 bg-sea-blue-600 hover:bg-sea-blue-700 text-white px-3 py-1.5 rounded-lg text-xs font-medium transition duration-150">
                    <i data-lucide="plus" class="w-4 h-4"></i>
                    Add Contact
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Filter panel --}}
            <div id="filter-panel" class="bg-white border border-sea-blue-100 rounded-xl shadow-sm mb-4 px-3 py-3 sm:px-4 sm:py-4 hidden">
                <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-3 text-xs sm:text-sm">
                    <div class="space-y-1">
                        <label class="block font-medium text-gray-600 text-xs">Search</label>
                        <input type="text" name="search" value="{{ request('search') }}"
                               placeholder="Name, email, phone..."
                               class="w-full rounded-lg border-gray-200 focus:border-sea-blue-500 focus:ring-sea-blue-500 text-xs py-1.5">
                    </div>

                    <div class="space-y-1">
                        <label class="block font-medium text-gray-600 text-xs">Category</label>
                        <select name="category" class="w-full rounded-lg border-gray-200 focus:border-sea-blue-500 focus:ring-sea-blue-500 text-xs py-1.5">
                            <option value="">All</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat }}" {{ request('category') == $cat ? 'selected' : '' }}>
                                    {{ ucfirst($cat) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="space-y-1">
                        <label class="block font-medium text-gray-600 text-xs">Favorite</label>
                        <select name="favorite" class="w-full rounded-lg border-gray-200 focus:border-sea-blue-500 focus:ring-sea-blue-500 text-xs py-1.5">
                            <option value="">All</option>
                            <option value="1" {{ request('favorite') === '1' ? 'selected' : '' }}>Only favorites</option>
                            <option value="0" {{ request('favorite') === '0' ? 'selected' : '' }}>Non favorites</option>
                        </select>
                    </div>

                    <div class="flex items-end gap-2">
                        <button type="submit"
                                class="inline-flex items-center justify-center gap-1.5 bg-sea-blue-600 hover:bg-sea-blue-700 text-white px-3 py-1.5 rounded-lg text-xs font-medium transition duration-150">
                            <i data-lucide="search" class="w-3.5 h-3.5"></i>
                            Filter
                        </button>
                        <a href="{{ route('contacts.index') }}"
                           class="inline-flex items-center justify-center gap-1.5 bg-gray-100 hover:bg-gray-200 text-gray-700 px-3 py-1.5 rounded-lg text-xs font-medium transition duration-150">
                            Reset
                        </a>
                    </div>
                </form>
            </div>

            {{-- Table --}}
            <div class="bg-white border border-gray-100 rounded-xl shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-100">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-[11px] font-semibold text-gray-500 uppercase tracking-wide">Name</th>
                                <th class="px-6 py-3 text-left text-[11px] font-semibold text-gray-500 uppercase tracking-wide">Email</th>
                                <th class="px-6 py-3 text-left text-[11px] font-semibold text-gray-500 uppercase tracking-wide">Phone</th>
                                <th class="px-6 py-3 text-left text-[11px] font-semibold text-gray-500 uppercase tracking-wide">Institution</th>
                                <th class="px-6 py-3 text-left text-[11px] font-semibold text-gray-500 uppercase tracking-wide">Category</th>
                                <th class="px-6 py-3 text-center text-[11px] font-semibold text-gray-500 uppercase tracking-wide">Fav</th>
                                <th class="px-6 py-3 text-right text-[11px] font-semibold text-gray-500 uppercase tracking-wide">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            @forelse($contacts as $contact)
                                <tr class="hover:bg-sea-blue-50/40 transition-colors duration-150">
                                    <td class="px-6 py-3 text-sm text-gray-800 font-medium">
                                        {{ $contact->Name }}
                                    </td>
                                    <td class="px-6 py-3 text-sm text-gray-600">
                                        {{ $contact->Email ?? '-' }}
                                    </td>
                                    <td class="px-6 py-3 text-sm text-gray-600">
                                        {{ $contact->Phone ?? '-' }}
                                    </td>
                                    <td class="px-6 py-3 text-sm text-gray-600">
                                        {{ $contact->Institution ?? '-' }}
                                    </td>
                                    <td class="px-6 py-3 text-sm text-gray-600">
                                        {{ ucfirst($contact->Category) }}
                                    </td>
                                    <td class="px-6 py-3 text-center">
                                        @if($contact->Favorite)
                                            <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-yellow-100 text-yellow-700">
                                                <i data-lucide="star" class="w-3 h-3"></i>
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-3 text-right space-x-1">
                                        <a href="{{ route('contacts.edit', $contact->id) }}"
                                           class="inline-flex items-center justify-center w-8 h-8 rounded-full text-sea-blue-700 hover:bg-sea-blue-50 transition-colors duration-150">
                                            <i data-lucide="edit-3" class="w-4 h-4"></i>
                                        </a>
                                        <form action="{{ route('contacts.destroy', $contact->id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="inline-flex items-center justify-center w-8 h-8 rounded-full text-red-600 hover:bg-red-50 transition-colors duration-150"
                                                    onclick="return confirm('Yakin ingin menghapus kontak ini?')">
                                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-10 text-center text-gray-500">
                                        <i data-lucide="address-book" class="w-10 h-10 mx-auto text-gray-300 mb-3"></i>
                                        <p class="text-sm font-medium">Belum ada kontak</p>
                                        <a href="{{ route('contacts.create') }}"
                                           class="mt-2 inline-block text-sea-blue-700 hover:text-sea-blue-900 text-sm">
                                            Tambahkan kontak pertama
                                        </a>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="px-4 sm:px-6 py-3 border-t border-gray-100">
                    {{ $contacts->links() }}
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
