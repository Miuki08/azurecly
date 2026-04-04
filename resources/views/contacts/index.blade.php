<div data-default-category="{{ $categories[0] ?? 'humas' }}"></div>
<x-app-layout x-data="contactPage">
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-xl text-sea-blue-800 leading-tight">
                    Contacts
                </h2>
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

                <button
                    type="button"
                    @click="openCreate()"
                    class="inline-flex items-center gap-1.5 bg-sea-blue-600 hover:bg-sea-blue-700 text-white px-3 py-1.5 rounded-lg text-xs font-medium transition duration-150"
                >
                    <i data-lucide="plus" class="w-4 h-4"></i>
                    Add Contact
                </button>
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
                                        <button
                                            type="button"
                                            class="inline-flex items-center justify-center w-8 h-8 rounded-full text-sea-blue-700 hover:bg-sea-blue-50 transition-colors duration-150"
                                            @click="openEdit({{ $contact->toJson() }})"
                                        >
                                            <i data-lucide="edit-3" class="w-4 h-4"></i>
                                        </button>

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
                                        <button
                                            type="button"
                                            class="mt-2 inline-flex items-center gap-1 text-sea-blue-700 hover:text-sea-blue-900 text-sm"
                                            @click="openCreate()"
                                        >
                                            <i data-lucide="plus" class="w-4 h-4"></i>
                                            Tambahkan kontak pertama
                                        </button>
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

        {{-- Modal Add/Edit Contact --}}
        <div
            x-cloak
            x-show="showModal"
            class="fixed inset-0 z-40 flex items-center justify-center bg-black/30 backdrop-blur-sm"
            x-transition.opacity
        >
            <div
                class="bg-white rounded-xl shadow-xl border border-gray-100 w-full max-w-lg mx-4"
                @click.away="closeModal()"
                x-transition.scale
            >
                <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
                    <h3 class="text-sm font-semibold text-gray-800" x-text="mode === 'create' ? 'Add Contact' : 'Edit Contact'"></h3>
                    <button type="button" class="text-gray-400 hover:text-gray-600" @click="closeModal()">
                        <i data-lucide="x" class="w-4 h-4"></i>
                    </button>
                </div>

                <form
                    :action="mode === 'create' ? '{{ route('contacts.store') }}' : '{{ url('contacts') }}/' + form.id"
                    method="POST"
                    class="px-5 py-4 space-y-4"
                >
                    @csrf
                    <template x-if="mode === 'edit'">
                        <input type="hidden" name="_method" value="PUT">
                    </template>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">
                                Name <span class="text-[10px] text-red-500">required</span>
                            </label>
                            <input type="text" name="name" x-model="form.name" required
                                   class="w-full rounded-lg border-gray-300 focus:border-sea-blue-500 focus:ring-sea-blue-500 text-sm">
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">
                                Email
                            </label>
                            <input type="email" name="email" x-model="form.email"
                                   class="w-full rounded-lg border-gray-300 focus:border-sea-blue-500 focus:ring-sea-blue-500 text-sm">
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">
                                Phone
                            </label>
                            <input type="text" name="phone" x-model="form.phone"
                                   class="w-full rounded-lg border-gray-300 focus:border-sea-blue-500 focus:ring-sea-blue-500 text-sm">
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">
                                Position
                            </label>
                            <input type="text" name="position" x-model="form.position"
                                   class="w-full rounded-lg border-gray-300 focus:border-sea-blue-500 focus:ring-sea-blue-500 text-sm">
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">
                                Institution
                            </label>
                            <input type="text" name="institution" x-model="form.institution"
                                   class="w-full rounded-lg border-gray-300 focus:border-sea-blue-500 focus:ring-sea-blue-500 text-sm">
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">
                                Category
                            </label>
                            <select name="category" x-model="form.category"
                                    class="w-full rounded-lg border-gray-300 focus:border-sea-blue-500 focus:ring-sea-blue-500 text-sm">
                                @foreach($categories as $cat)
                                    <option value="{{ $cat }}">{{ ucfirst($cat) }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="flex items-center gap-2">
                        <input id="favorite" name="favorite" type="checkbox" value="1"
                               class="h-4 w-4 text-sea-blue-600 border-gray-300 rounded"
                               x-model="form.favorite">
                        <label for="favorite" class="text-xs text-gray-700">
                            Tandai sebagai favorite
                        </label>
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">
                            Notes
                        </label>
                        <textarea name="notes" rows="3"
                                  class="w-full rounded-lg border-gray-300 focus:border-sea-blue-500 focus:ring-sea-blue-500 text-sm"
                                  x-model="form.notes"></textarea>
                    </div>

                    <div class="flex justify-end gap-2 pt-3 border-t border-gray-100">
                        <button type="button"
                                class="px-4 py-2 border border-gray-300 rounded-lg text-xs text-gray-700 hover:bg-gray-50 transition"
                                @click="closeModal()">
                            Batal
                        </button>
                        <button type="submit"
                                class="px-4 py-2 bg-sea-blue-600 hover:bg-sea-blue-700 text-white rounded-lg text-xs font-medium inline-flex items-center gap-1 transition">
                            <i data-lucide="save" class="w-4 h-4"></i>
                            <span x-text="mode === 'create' ? 'Simpan Kontak' : 'Update Kontak'"></span>
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