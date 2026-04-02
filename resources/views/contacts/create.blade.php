<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-sea-blue-800 leading-tight">
                Add Contact
            </h2>
            <a href="{{ route('contacts.index') }}"
               class="inline-flex items-center text-gray-600 hover:text-gray-800 text-sm">
                <i data-lucide="arrow-left" class="w-4 h-4 mr-1"></i>
                Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <form action="{{ route('contacts.store') }}" method="POST" class="space-y-5 bg-white rounded-lg shadow border border-gray-100 px-5 py-5">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Name <span class="text-[11px] text-red-500">required</span>
                        </label>
                        <input type="text" name="name" value="{{ old('name') }}" required
                               class="w-full rounded-lg border-gray-300 focus:border-sea-blue-500 focus:ring-sea-blue-500 text-sm">
                        @error('name')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Email
                        </label>
                        <input type="email" name="email" value="{{ old('email') }}"
                               class="w-full rounded-lg border-gray-300 focus:border-sea-blue-500 focus:ring-sea-blue-500 text-sm">
                        @error('email')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Phone
                        </label>
                        <input type="text" name="phone" value="{{ old('phone') }}"
                               class="w-full rounded-lg border-gray-300 focus:border-sea-blue-500 focus:ring-sea-blue-500 text-sm">
                        @error('phone')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Position
                        </label>
                        <input type="text" name="position" value="{{ old('position') }}"
                               class="w-full rounded-lg border-gray-300 focus:border-sea-blue-500 focus:ring-sea-blue-500 text-sm">
                        @error('position')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Institution
                        </label>
                        <input type="text" name="institution" value="{{ old('institution') }}"
                               class="w-full rounded-lg border-gray-300 focus:border-sea-blue-500 focus:ring-sea-blue-500 text-sm">
                        @error('institution')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Category
                        </label>
                        <select name="category"
                                class="w-full rounded-lg border-gray-300 focus:border-sea-blue-500 focus:ring-sea-blue-500 text-sm">
                            @foreach($categories as $cat)
                                <option value="{{ $cat }}" {{ old('category', 'humas') == $cat ? 'selected' : '' }}>
                                    {{ ucfirst($cat) }}
                                </option>
                            @endforeach
                        </select>
                        @error('category')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    <input id="favorite" name="favorite" type="checkbox" value="1"
                           class="h-4 w-4 text-sea-blue-600 border-gray-300 rounded"
                           {{ old('favorite') ? 'checked' : '' }}>
                    <label for="favorite" class="text-sm text-gray-700">
                        Tandai sebagai favorite
                    </label>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Notes
                    </label>
                    <textarea name="notes" rows="4"
                              class="w-full rounded-lg border-gray-300 focus:border-sea-blue-500 focus:ring-sea-blue-500 text-sm">{{ old('notes') }}</textarea>
                    @error('notes')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-end gap-3 pt-3 border-t border-gray-100">
                    <a href="{{ route('contacts.index') }}"
                       class="px-4 py-2 border border-gray-300 rounded-lg text-sm text-gray-700 hover:bg-gray-50 transition">
                        Batal
                    </a>
                    <button type="submit"
                            class="px-4 py-2 bg-sea-blue-600 hover:bg-sea-blue-700 text-white rounded-lg text-sm font-medium inline-flex items-center gap-1 transition">
                        <i data-lucide="save" class="w-4 h-4"></i>
                        Simpan Kontak
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
            });
        </script>
    @endpush
</x-app-layout>
