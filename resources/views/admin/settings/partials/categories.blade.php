<div
    x-data="{ openCreateCategory: false }"
    x-cloak
>
    <div class="flex items-center justify-between mb-4">
        <div>
            <h3 class="text-lg font-semibold text-gray-900">Categories</h3>
            <p class="text-xs text-gray-500">
                Kelola kategori berita untuk site ini.
            </p>
        </div>

        <button
            type="button"
            @click="openCreateCategory = true"
            class="inline-flex items-center gap-1.5 bg-sea-blue-600 hover:bg-sea-blue-700 text-white px-3 py-1.5 rounded-lg text-xs font-medium transition"
        >
            <i data-lucide="plus" class="w-4 h-4"></i>
            Add Category
        </button>
    </div>

    <div class="bg-white border border-gray-100 rounded-xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-100 text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-2 text-left text-[11px] font-semibold text-gray-500 uppercase tracking-wide">
                            Name
                        </th>
                        <th class="px-4 py-2 text-left text-[11px] font-semibold text-gray-500 uppercase tracking-wide">
                            Slug
                        </th>
                        <th class="px-4 py-2 text-right text-[11px] font-semibold text-gray-500 uppercase tracking-wide">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    @forelse ($categories as $category)
                        <tr class="hover:bg-sea-blue-50/40 transition-colors duration-150">
                            <td class="px-4 py-2 text-sm text-gray-900">
                                {{ $category->Name }}
                            </td>
                            <td class="px-4 py-2 text-sm text-gray-600">
                                {{ $category->Slug }}
                            </td>
                            <td class="px-4 py-2 text-right">
                                <form
                                    action="{{ route('admin.settings.categories.destroy', $category) }}"
                                    method="POST"
                                    class="inline"
                                    onsubmit="return confirm('Yakin hapus category ini?')"
                                >
                                    @csrf
                                    @method('DELETE')
                                    <button
                                        type="submit"
                                        class="inline-flex items-center justify-center w-8 h-8 rounded-full text-red-600 hover:bg-red-50 transition"
                                    >
                                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-4 py-8 text-center text-sm text-gray-500">
                                <i data-lucide="inbox" class="w-8 h-8 mx-auto text-gray-300 mb-2"></i>
                                Belum ada category.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Modal create category --}}
    <div
        x-show="openCreateCategory"
        class="fixed inset-0 z-40 flex items-center justify-center bg-black/40"
        @keydown.escape.window="openCreateCategory = false"
    >
        <div
            x-show="openCreateCategory"
            x-transition
            class="bg-white rounded-xl shadow-lg w-full max-w-md mx-4"
        >
            <div class="px-4 py-3 border-b border-gray-100 flex items-center justify-between">
                <h3 class="text-sm font-semibold text-gray-900">
                    Add Category
                </h3>
                <button
                    type="button"
                    class="text-gray-400 hover:text-gray-600"
                    @click="openCreateCategory = false"
                >
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
            </div>

            <form
                action="{{ route('admin.settings.categories.store') }}"
                method="POST"
                class="px-4 py-4 space-y-4"
            >
                @csrf

                <div>
                    <label class="block text-xs font-medium text-gray-700">Nama Category</label>
                    <input
                        type="text"
                        name="Name"
                        class="mt-1 block w-full rounded-lg border-gray-200 focus:border-sea-blue-500 focus:ring-sea-blue-500 text-sm"
                        required
                    >
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700">Slug</label>
                    <input
                        type="text"
                        name="Slug"
                        class="mt-1 block w-full rounded-lg border-gray-200 focus:border-sea-blue-500 focus:ring-sea-blue-500 text-sm"
                        required
                    >
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700">Description</label>
                    <textarea
                        name="Description"
                        rows="3"
                        class="mt-1 block w-full rounded-lg border-gray-200 focus:border-sea-blue-500 focus:ring-sea-blue-500 text-sm"
                    ></textarea>
                </div>

                <div class="pt-2 flex justify-end gap-2">
                    <button
                        type="button"
                        class="inline-flex items-center px-3 py-1.5 text-xs rounded-lg border border-gray-200 text-gray-700 hover:bg-gray-50"
                        @click="openCreateCategory = false"
                    >
                        Cancel
                    </button>
                    <button
                        type="submit"
                        class="inline-flex items-center px-4 py-1.5 text-xs font-medium rounded-lg text-white bg-sea-blue-600 hover:bg-sea-blue-700"
                    >
                        Save
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
