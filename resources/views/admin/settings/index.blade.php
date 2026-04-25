<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-sea-blue-800 leading-tight">
            {{ __('Settings') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            @if (session('status'))
                <div class="mb-4 rounded-md bg-green-50 border border-green-200 px-4 py-3 text-sm text-green-800">
                    {{ session('status') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-4 rounded-md bg-red-50 border border-red-200 px-4 py-3 text-sm text-red-800">
                    <ul class="list-disc list-inside space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div
                x-data="{ tab: 'company' }"
                class="bg-white shadow rounded-lg"
            >
                {{-- Tabs header --}}
                <div class="border-b border-gray-200 px-4 sm:px-6">
                    <nav class="-mb-px flex space-x-6" aria-label="Tabs">
                        <button
                            type="button"
                            class="whitespace-nowrap py-4 px-1 border-b-2 text-sm font-medium"
                            :class="tab === 'company'
                                ? 'border-sea-blue-500 text-sea-blue-600'
                                : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                            @click="tab = 'company'"
                        >
                            Profil Perusahaan
                        </button>

                        <button
                            type="button"
                            class="whitespace-nowrap py-4 px-1 border-b-2 text-sm font-medium"
                            :class="tab === 'regions'
                                ? 'border-sea-blue-500 text-sea-blue-600'
                                : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                            @click="tab = 'regions'"
                        >
                            Region
                        </button>

                        <button
                            type="button"
                            class="whitespace-nowrap py-4 px-1 border-b-2 text-sm font-medium"
                            :class="tab === 'categories'
                                ? 'border-sea-blue-500 text-sea-blue-600'
                                : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                            @click="tab = 'categories'"
                        >
                            Category
                        </button>

                        <button
                            type="button"
                            class="whitespace-nowrap py-4 px-1 border-b-2 text-sm font-medium"
                            :class="tab === 'users'
                                ? 'border-sea-blue-500 text-sea-blue-600'
                                : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                            @click="tab = 'users'"
                        >
                            Pengguna
                        </button>
                    </nav>
                </div>

                {{-- Tabs content --}}
                <div class="px-4 sm:px-6 py-6">
                    {{-- Company --}}
                    <div x-show="tab === 'company'" x-cloak>
                        @include('admin.settings.partials.company')
                    </div>

                    {{-- Regions --}}
                    <div x-show="tab === 'regions'" x-cloak>
                        @include('admin.settings.partials.regions')
                    </div>

                    {{-- Categories --}}
                    <div x-show="tab === 'categories'" x-cloak>
                        @include('admin.settings.partials.categories')
                    </div>

                    {{-- Users --}}
                    <div x-show="tab === 'users'" x-cloak>
                        @include('admin.settings.partials.users')
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>