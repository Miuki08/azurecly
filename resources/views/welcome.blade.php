<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name', 'Media Intelligence Dashboard') }}</title>

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="antialiased bg-gray-50">
        <div class="min-h-screen flex flex-col">
            {{-- Top bar login/dashboard --}}
            @if (Route::has('login'))
                <div class="w-full flex justify-end px-6 py-4">
                    @auth
                        <a href="{{ url('/dashboard') }}"
                           class="text-sm font-medium text-gray-700 hover:text-gray-900">
                            Masuk ke Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}"
                           class="text-sm font-medium text-gray-700 hover:text-gray-900">
                            Log in
                        </a>
                    @endauth
                </div>
            @endif

            {{-- Hero section --}}
            <main class="flex-1 flex items-center">
                <div class="max-w-6xl mx-auto px-6 py-10 grid grid-cols-1 md:grid-cols-2 gap-10 md:gap-14">
                    {{-- Left: copy --}}
                    <div class="flex flex-col justify-center space-y-6">
                        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-sea-blue-50 text-sea-blue-700 text-xs font-medium">
                            <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                            Real‑time media monitoring & contact hub
                        </div>

                        <div>
                            <h1 class="text-3xl md:text-4xl font-semibold text-gray-900 leading-tight">
                                Media Intelligence & Contact Management
                                untuk Tim Humas Modern
                            </h1>
                            <p class="mt-4 text-sm md:text-base text-gray-600 leading-relaxed">
                                Aplikasi ini membantu tim humas memantau pemberitaan, mengelola kontak penting,
                                serta menganalisis sentimen dan prioritas isu dalam satu dashboard yang terintegrasi.
                            </p>
                        </div>

                        <div class="grid grid-cols-3 gap-4 text-xs md:text-sm">
                            <div>
                                <p class="text-gray-500">Berita Terpantau</p>
                                <p class="text-lg font-semibold text-gray-900">
                                    {{ number_format(\App\Models\Ticket::count()) }}
                                </p>
                            </div>
                            <div>
                                <p class="text-gray-500">Kontak Tercatat</p>
                                <p class="text-lg font-semibold text-gray-900">
                                    {{ number_format(\App\Models\Contact::count()) }}
                                </p>
                            </div>
                            <div>
                                <p class="text-gray-500">Fokus Isu</p>
                                <p class="text-lg font-semibold text-gray-900">
                                    {{ number_format(\App\Models\Ticket::where('Priority', 'high')->count()) }}
                                </p>
                            </div>
                        </div>

                        <div class="flex flex-wrap gap-3">
                            @auth
                                <a href="{{ url('/dashboard') }}"
                                   class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-sea-blue-600 text-white text-sm font-medium hover:bg-sea-blue-700 transition">
                                    <i data-lucide="layout-dashboard" class="w-4 h-4"></i>
                                    Buka Dashboard
                                </a>
                            @else
                                <a href="{{ route('login') }}"
                                   class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-sea-blue-600 text-white text-sm font-medium hover:bg-sea-blue-700 transition">
                                    <i data-lucide="log-in" class="w-4 h-4"></i>
                                    Masuk sebagai Admin
                                </a>
                            @endauth>

                            <div class="flex items-center gap-2 text-xs text-gray-500">
                                <i data-lucide="shield-check" class="w-4 h-4"></i>
                                Akses terbatas untuk tim internal
                            </div>
                        </div>
                    </div>

                    {{-- Right: simple preview cards --}}
                    <div class="relative">
                        <div class="absolute -inset-4 bg-gradient-to-tr from-sea-blue-100 via-white to-emerald-100 opacity-70 blur-2xl -z-10"></div>

                        <div class="space-y-4">
                            {{-- Card: contoh berita --}}
                            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
                                <div class="flex items-start justify-between gap-3">
                                    <div>
                                        <p class="text-xs uppercase tracking-wide text-gray-400 mb-1">
                                            Contoh Berita
                                        </p>
                                        <p class="text-sm font-semibold text-gray-900 line-clamp-2">
                                            Isu kebijakan publik terbaru di wilayah Jabodetabek
                                        </p>
                                    </div>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-medium bg-red-50 text-red-700">
                                        High
                                    </span>
                                </div>
                                <div class="mt-3 flex items-center gap-3 text-[11px] text-gray-500">
                                    <span class="inline-flex items-center gap-1">
                                        <i data-lucide="map-pin" class="w-3 h-3"></i>
                                        Jakarta
                                    </span>
                                    <span class="inline-flex items-center gap-1">
                                        <i data-lucide="smile-plus" class="w-3 h-3"></i>
                                        Negative
                                    </span>
                                    <span class="inline-flex items-center gap-1">
                                        <i data-lucide="eye" class="w-3 h-3"></i>
                                        124 views
                                    </span>
                                </div>
                            </div>

                            {{-- Card: kontak --}}
                            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
                                <p class="text-xs uppercase tracking-wide text-gray-400 mb-1">
                                    Contoh Kontak
                                </p>
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-sm font-semibold text-gray-900">
                                            Humas Pemerintah Kota
                                        </p>
                                        <p class="text-xs text-gray-500 mt-0.5">
                                            Kepala Subbagian Humas · Pemerintah Kota Depok
                                        </p>
                                    </div>
                                    <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-yellow-50 text-yellow-600">
                                        <i data-lucide="star" class="w-3.5 h-3.5"></i>
                                    </span>
                                </div>
                            </div>

                            {{-- Card: ringkasan --}}
                            <div class="bg-gray-900 rounded-xl text-white p-4">
                                <p class="text-xs uppercase tracking-wide text-gray-400 mb-2">
                                    Kenapa aplikasi ini?
                                </p>
                                <ul class="space-y-1.5 text-xs text-gray-200">
                                    >
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                                        Sentiment analysis otomatis untuk tiap berita.
                                    </li>
                                    >
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                                        Penandaan prioritas isu untuk respon cepat.
                                    </li>
                                    >
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                                        Manajemen kontak terpadu untuk eskalasi dan koordinasi.
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </main>

            {{-- Footer --}}
            <footer class="border-t border-gray-100">
                <div class="max-w-6xl mx-auto px-6 py-4 flex flex-col sm:flex-row items-center justify-between gap-2">
                    <p class="text-xs text-gray-500">
                        &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
                    </p>
                    <p class="text-xs text-gray-400">
                        Laravel v{{ Illuminate\Foundation\Application::VERSION }} · PHP v{{ PHP_VERSION }}
                    </p>
                </div>
            </footer>
        </div>

        <script src="https://unpkg.com/lucide@latest"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                if (typeof lucide !== 'undefined') {
                    lucide.createIcons();
                }
            });
        </script>
    </body>
</html>