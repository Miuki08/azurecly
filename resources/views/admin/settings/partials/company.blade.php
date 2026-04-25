@php
    $site = $site ?? null;
@endphp

<div
    x-data="{ openCompanyModal: false }"
    x-cloak
>
    <div class="flex items-center justify-between mb-4">
        <div>
            <h3 class="text-lg font-semibold text-gray-900">
                Company Profile
            </h3>
            <p class="text-xs text-gray-500">
                Kelola informasi perusahaan untuk site ini.
            </p>
        </div>

        <button
            type="button"
            @click="openCompanyModal = true"
            class="inline-flex items-center gap-1.5 bg-sea-blue-600 hover:bg-sea-blue-700 text-white px-3 py-1.5 rounded-lg text-xs font-medium transition"
        >
            <i data-lucide="{{ $site ? 'edit-3' : 'plus' }}" class="w-4 h-4"></i>
            {{ $site ? 'Edit Company' : 'Create Company' }}
        </button>
    </div>

    {{-- Card ringkas --}}
    <div class="border border-gray-100 rounded-xl bg-gray-50/60 px-4 py-3 text-sm">
        @if (! $site)
            <div class="flex items-start gap-3">
                <div class="mt-0.5">
                    <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-amber-100 text-amber-700">
                        <i data-lucide="alert-circle" class="w-3.5 h-3.5"></i>
                    </span>
                </div>
                <div>
                    <p class="text-gray-700 font-medium">
                        Perusahaan belum terdaftar.
                    </p>
                    <p class="text-xs text-gray-500 mt-0.5">
                        Tambahkan profil perusahaan terlebih dahulu agar region, category, dan user bisa diatur per-site.
                    </p>
                </div>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                <div>
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">
                        Nama Perusahaan
                    </p>
                    <p class="text-sm text-gray-900">
                        {{ $site->name }}
                    </p>
                </div>
                <div>
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">
                        Slug / Kode
                    </p>
                    <p class="text-sm text-gray-900">
                        {{ $site->slug }}
                    </p>
                </div>
                <div>
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">
                        Email
                    </p>
                    <p class="text-sm text-gray-900">
                        {{ $site->email ?? '-' }}
                    </p>
                </div>
                <div>
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">
                        Phone
                    </p>
                    <p class="text-sm text-gray-900">
                        {{ $site->phone ?? '-' }}
                    </p>
                </div>
                <div class="md:col-span-2">
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">
                        Alamat
                    </p>
                    <p class="text-sm text-gray-900">
                        {{ $site->address ?? '-' }}
                    </p>
                </div>
            </div>
        @endif
    </div>

    {{-- Modal Create / Edit Company --}}
    <div
        x-show="openCompanyModal"
        class="fixed inset-0 z-40 flex items-center justify-center bg-black/40"
        @keydown.escape.window="openCompanyModal = false"
    >
        <div
            x-show="openCompanyModal"
            x-transition
            class="bg-white rounded-xl shadow-lg w-full max-w-lg mx-4"
        >
            <div class="px-4 py-3 border-b border-gray-100 flex items-center justify-between">
                <h3 class="text-sm font-semibold text-gray-900">
                    {{ $site ? 'Edit Company Profile' : 'Create Company Profile' }}
                </h3>
                <button
                    type="button"
                    class="text-gray-400 hover:text-gray-600"
                    @click="openCompanyModal = false"
                >
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
            </div>

            <form
                action="{{ $site ? route('admin.settings.site.update', $site->id) : route('admin.settings.site.store') }}"
                method="POST"
                class="px-4 py-4 space-y-4"
            >
                @csrf
                @if($site)
                    @method('PUT')
                @endif

                <div>
                    <label class="block text-xs font-medium text-gray-700">Nama Perusahaan</label>
                    <input
                        type="text"
                        name="name"
                        value="{{ old('name', $site->name ?? '') }}"
                        class="mt-1 block w-full rounded-lg border-gray-200 focus:border-sea-blue-500 focus:ring-sea-blue-500 text-sm"
                        required
                    >
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-medium text-gray-700">Slug / Kode</label>
                        <input
                            type="text"
                            name="slug"
                            value="{{ old('slug', $site->slug ?? '') }}"
                            class="mt-1 block w-full rounded-lg border-gray-200 focus:border-sea-blue-500 focus:ring-sea-blue-500 text-sm"
                            required
                        >
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700">Email</label>
                        <input
                            type="email"
                            name="email"
                            value="{{ old('email', $site->email ?? '') }}"
                            class="mt-1 block w-full rounded-lg border-gray-200 focus:border-sea-blue-500 focus:ring-sea-blue-500 text-sm"
                        >
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-medium text-gray-700">Phone</label>
                        <input
                            type="text"
                            name="phone"
                            value="{{ old('phone', $site->phone ?? '') }}"
                            class="mt-1 block w-full rounded-lg border-gray-200 focus:border-sea-blue-500 focus:ring-sea-blue-500 text-sm"
                        >
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-700">Alamat</label>
                    <textarea
                        name="address"
                        rows="3"
                        class="mt-1 block w-full rounded-lg border-gray-200 focus:border-sea-blue-500 focus:ring-sea-blue-500 text-sm"
                    >{{ old('address', $site->address ?? '') }}</textarea>
                </div>

                <div class="pt-2 flex justify-end gap-2">
                    <button
                        type="button"
                        class="inline-flex items-center px-3 py-1.5 text-xs rounded-lg border border-gray-200 text-gray-700 hover:bg-gray-50"
                        @click="openCompanyModal = false"
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
