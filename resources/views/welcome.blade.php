<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name', 'Azurecly – Media Intelligence Dashboard') }}</title>

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="antialiased bg-gray-50">
        <div class="min-h-screen flex flex-col">
            {{-- Top bar --}}
            @if (Route::has('login'))
                <header class="w-full border-b border-gray-100 bg-white/80 backdrop-blur">
                    <div class="max-w-6xl mx-auto px-6 py-4 flex items-center justify-between gap-3">
                        <div class="flex items-center gap-2">
                            <div class="h-8 w-8 rounded-xl bg-gradient-to-br from-sea-400 to-sea-600 flex items-center justify-center text-white text-xs font-bold">
                                Az
                            </div>
                            <div class="flex flex-col">
                                <span class="text-sm font-semibold text-gray-900 tracking-tight">
                                    {{ config('app.name', 'Azurecly') }}
                                </span>
                                <span class="text-[11px] text-gray-500">
                                    Media & Contact Intelligence Dashboard
                                </span>
                            </div>
                        </div>

                        <div class="flex items-center gap-3">
                            @auth
                                <a href="{{ url('/dashboard') }}"
                                   class="text-xs md:text-sm font-medium text-gray-700 hover:text-gray-900">
                                    Masuk ke Dashboard
                                </a>
                            @else
                                <a href="{{ route('login') }}"
                                   class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg text-xs md:text-sm font-medium text-gray-700 hover:text-gray-900">
                                    <i data-lucide="log-in" class="w-4 h-4"></i>
                                    Login Admin / Humas
                                </a>
                            @endauth
                        </div>
                    </div>
                </header>
            @endif

            <main class="flex-1">
                {{-- HERO --}}
                <section class="border-b border-gray-100">
                    <div class="max-w-6xl mx-auto px-6 py-12 lg:py-16 grid grid-cols-1 md:grid-cols-2 gap-10 md:gap-14 items-center">
                        {{-- Left: main copy --}}
                        <div class="flex flex-col space-y-6">
                            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-sea-50 text-sea-700 text-[11px] font-medium border border-sea-100">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                <span>Real‑time media monitoring · Omnichannel escalation</span>
                            </div>

                            <div>
                                <h1 class="text-3xl md:text-4xl font-semibold text-gray-900 leading-tight">
                                    Satu dashboard untuk memantau
                                    <span class="text-sea-600">pemberitaan, kontak,</span>
                                    dan eskalasi isu strategis.
                                </h1>
                                <p class="mt-4 text-sm md:text-base text-gray-600 leading-relaxed">
                                    Azurecly membantu tim humas lintas perusahaan memantau berita,
                                    menganalisis sentimen, mengatur prioritas isu, dan menghubungkan
                                    isu tersebut dengan kontak penting — lalu mengeskalasinya ke
                                    WhatsApp, email, maupun Telegram dalam hitungan detik.
                                </p>
                            </div>

                            {{-- Stats --}}
                            <div class="grid grid-cols-3 gap-4 text-xs md:text-sm">
                                <div class="rounded-lg bg-white border border-gray-100 p-3">
                                    <p class="text-[11px] text-gray-500">Berita terpantau</p>
                                    <p class="text-lg font-semibold text-gray-900">
                                        {{ number_format(\App\Models\Ticket::count()) }}
                                    </p>
                                </div>
                                <div class="rounded-lg bg-white border border-gray-100 p-3">
                                    <p class="text-[11px] text-gray-500">Kontak tercatat</p>
                                    <p class="text-lg font-semibold text-gray-900">
                                        {{ number_format(\App\Models\Contact::count()) }}
                                    </p>
                                </div>
                                <div class="rounded-lg bg-white border border-gray-100 p-3">
                                    <p class="text-[11px] text-gray-500">Isu prioritas tinggi</p>
                                    <p class="text-lg font-semibold text-gray-900">
                                        {{ number_format(\App\Models\Ticket::where('Priority', 'high')->count()) }}
                                    </p>
                                </div>
                            </div>

                            {{-- CTA --}}
                            <div class="flex flex-wrap items-center gap-3">
                                @auth
                                    <a href="{{ url('/dashboard') }}"
                                       class="inline-flex items-center gap-2 px-4 py-2.5 rounded-lg bg-sea-600 text-white text-sm font-medium hover:bg-sea-700 shadow-sm transition">
                                        <i data-lucide="layout-dashboard" class="w-4 h-4"></i>
                                        Buka Dashboard Azurecly
                                    </a>
                                @else
                                    <a href="{{ route('login') }}"
                                       class="inline-flex items-center gap-2 px-4 py-2.5 rounded-lg bg-sea-600 text-white text-sm font-medium hover:bg-sea-700 shadow-sm transition">
                                        <i data-lucide="log-in" class="w-4 h-4"></i>
                                        Masuk sebagai Admin / Humas
                                    </a>
                                @endauth

                                <div class="flex items-center gap-2 text-xs text-gray-500">
                                    <i data-lucide="shield-check" class="w-4 h-4 text-sea-500"></i>
                                    <span>Akses aman untuk tim internal & multi-perusahaan</span>
                                </div>
                            </div>
                        </div>

                        {{-- Right: preview --}}
                        <div class="relative">
                            <div class="absolute -inset-4 bg-gradient-to-tr from-sea-50 via-white to-emerald-100 opacity-80 blur-2xl -z-10"></div>

                            <div class="space-y-4">
                                {{-- Card: contoh berita --}}
                                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
                                    <div class="flex items-start justify-between gap-3">
                                        <div>
                                            <p class="text-[11px] uppercase tracking-wide text-gray-400 mb-1">
                                                Contoh berita termonitor
                                            </p>
                                            <p class="text-sm font-semibold text-gray-900 line-clamp-2">
                                                Isu kebijakan publik terbaru di wilayah Jabodetabek
                                            </p>
                                        </div>
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-medium bg-red-50 text-red-700">
                                            High priority
                                        </span>
                                    </div>
                                    <div class="mt-3 flex flex-wrap items-center gap-3 text-[11px] text-gray-500">
                                        <span class="inline-flex items-center gap-1">
                                            <i data-lucide="map-pin" class="w-3 h-3"></i>
                                            Jakarta
                                        </span>
                                        <span class="inline-flex items-center gap-1">
                                            <i data-lucide="smile-plus" class="w-3 h-3"></i>
                                            Negative sentiment
                                        </span>
                                        <span class="inline-flex items-center gap-1">
                                            <i data-lucide="building-2" class="w-3 h-3"></i>
                                            Multi‑perusahaan
                                        </span>
                                    </div>
                                </div>

                                {{-- Card: eskalasi --}}
                                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
                                    <p class="text-[11px] uppercase tracking-wide text-gray-400 mb-2">
                                        Eskalasi berita dalam beberapa klik
                                    </p>
                                    <div class="flex flex-col gap-2 text-xs text-gray-600">
                                        <div class="flex items-center justify-between">
                                            <div class="inline-flex items-center gap-2">
                                                <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-emerald-50 text-emerald-600">
                                                    <i data-lucide="message-circle" class="w-3.5 h-3.5"></i>
                                                </span>
                                                <div>
                                                    <p class="font-medium text-gray-900 text-xs">WhatsApp</p>
                                                    <p class="text-[11px] text-gray-500">Kirim ringkasan isu ke grup kerja</p>
                                                </div>
                                            </div>
                                            <span class="text-[11px] text-emerald-600 font-medium">Live</span>
                                        </div>
                                        <div class="flex items-center justify-between">
                                            <div class="inline-flex items-center gap-2">
                                                <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-sky-50 text-sky-600">
                                                    <i data-lucide="mail" class="w-3.5 h-3.5"></i>
                                                </span>
                                                <div>
                                                    <p class="font-medium text-gray-900 text-xs">Email</p>
                                                    <p class="text-[11px] text-gray-500">Notifikasi formal ke pimpinan</p>
                                                </div>
                                            </div>
                                            <span class="text-[11px] text-sky-600 font-medium">Live</span>
                                        </div>
                                        <div class="flex items-center justify-between">
                                            <div class="inline-flex items-center gap-2">
                                                <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-sea-50 text-sea-600">
                                                    <i data-lucide="send" class="w-3.5 h-3.5"></i>
                                                </span>
                                                <div>
                                                    <p class="font-medium text-gray-900 text-xs">Telegram</p>
                                                    <p class="text-[11px] text-gray-500">Saluran koordinasi cepat</p>
                                                </div>
                                            </div>
                                            <span class="text-[11px] text-sea-600 font-medium">Live</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                {{-- SECTION: Fitur utama --}}
                <section class="bg-white">
                    <div class="max-w-6xl mx-auto px-6 py-10 lg:py-14">
                        <div class="flex items-center justify-between gap-3 mb-6">
                            <div>
                                <h2 class="text-lg md:text-xl font-semibold text-gray-900">
                                    Fitur utama Azurecly
                                </h2>
                                <p class="mt-1 text-xs md:text-sm text-gray-600">
                                    Dirancang untuk alur kerja humas yang butuh kecepatan, akurasi, dan dokumentasi rapi.
                                </p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                            <div class="rounded-xl border border-gray-100 bg-gray-50/60 p-4 flex flex-col gap-2">
                                <div class="inline-flex items-center justify-center w-9 h-9 rounded-lg bg-sea-50 text-sea-600 mb-1">
                                    <i data-lucide="radar" class="w-4 h-4"></i>
                                </div>
                                <h3 class="text-sm font-semibold text-gray-900">
                                    Monitoring & analisis berita
                                </h3>
                                <p class="text-xs text-gray-600 leading-relaxed">
                                    Kumpulkan dan kelola tiket berita dengan metadata lengkap:
                                    lokasi, kategori, sentimen, dan tingkat prioritas untuk setiap isu.
                                </p>
                            </div>

                            <div class="rounded-xl border border-gray-100 bg-gray-50/60 p-4 flex flex-col gap-2">
                                <div class="inline-flex items-center justify-center w-9 h-9 rounded-lg bg-emerald-50 text-emerald-600 mb-1">
                                    <i data-lucide="users-2" class="w-4 h-4"></i>
                                </div>
                                <h3 class="text-sm font-semibold text-gray-900">
                                    Manajemen kontak & perusahaan
                                </h3>
                                <p class="text-xs text-gray-600 leading-relaxed">
                                    Simpan dan kelompokkan kontak penting beserta perusahaan terkait,
                                    lengkap dengan nomor, email, dan peran untuk memudahkan eskalasi.
                                </p>
                            </div>

                            <div class="rounded-xl border border-gray-100 bg-gray-50/60 p-4 flex flex-col gap-2">
                                <div class="inline-flex items-center justify-center w-9 h-9 rounded-lg bg-indigo-50 text-indigo-600 mb-1">
                                    <i data-lucide="share-2" class="w-4 h-4"></i>
                                </div>
                                <h3 class="text-sm font-semibold text-gray-900">
                                    Eskalasi multi‑channel
                                </h3>
                                <p class="text-xs text-gray-600 leading-relaxed">
                                    Buat log eskalasi terstruktur dan kirim pesan ke WhatsApp, email,
                                    atau Telegram dengan template yang konsisten untuk seluruh tim.
                                </p>
                            </div>
                        </div>
                    </div>
                </section>

                {{-- SECTION: Cara Azurecly membantu --}}
                <section class="bg-gray-50">
                    <div class="max-w-6xl mx-auto px-6 py-10 lg:py-14 grid grid-cols-1 md:grid-cols-2 gap-10">
                        <div class="space-y-3">
                            <h2 class="text-lg md:text-xl font-semibold text-gray-900">
                                Dibuat untuk ritme kerja humas sehari‑hari
                            </h2>
                            <p class="text-xs md:text-sm text-gray-600 leading-relaxed">
                                Dari memantau berita pagi hari sampai merangkum laporan untuk pimpinan,
                                Azurecly membantu menjaga alur kerja tetap rapi dan terdokumentasi.
                            </p>
                            <ul class="space-y-2 text-xs text-gray-700">
                                <li class="flex items-start gap-2">
                                    <span class="mt-1 w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                                    <span>Pemetaan isu berdasarkan sentimen dan prioritas sehingga tim tahu mana yang harus ditangani dulu.</span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <span class="mt-1 w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                                    <span>Riwayat eskalasi per berita dan per kontak untuk memudahkan tracking keputusan.</span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <span class="mt-1 w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                                    <span>Pengelolaan lintas perusahaan sehingga satu dashboard bisa melayani beberapa entitas sekaligus.</span>
                                </li>
                            </ul>
                        </div>

                        <div class="rounded-2xl border border-dashed border-sea-200 bg-white/80 p-4 md:p-5 flex flex-col gap-3">
                            <div class="inline-flex items-center gap-2 text-xs font-medium text-sea-700 bg-sea-50 px-3 py-1 rounded-full self-start">
                                <i data-lucide="sparkles" class="w-3.5 h-3.5"></i>
                                <span>Kerja sama & implementasi</span>
                            </div>
                            <p class="text-xs md:text-sm text-gray-700 leading-relaxed">
                                Ingin menerapkan Azurecly di instansi atau perusahaan Anda?
                                Tim kami dapat membantu mulai dari setup awal hingga pendampingan penggunaan.
                            </p>
                            <div class="space-y-1.5 text-xs text-gray-700">
                                <p class="flex items-center gap-2">
                                    <i data-lucide="mail" class="w-4 h-4 text-sea-600"></i>
                                    <span>Email: <a href="mailto:csmarket@azurecly.com" class="underline decoration-sea-300 hover:text-sea-700">csmarket@azurecly.com</a></span>
                                </p>
                                <p class="flex items-center gap-2">
                                    <i data-lucide="phone" class="w-4 h-4 text-sea-600"></i>
                                    <span>WhatsApp: <a href="https://wa.me/6285160805741" target="_blank" class="underline decoration-sea-300 hover:text-sea-700">+62 851-6080-5741</a></span>
                                </p>
                            </div>
                            <p class="text-[11px] text-gray-500">
                                Hubungi kami untuk demo singkat, diskusi kebutuhan khusus, atau integrasi dengan sistem yang sudah ada.
                            </p>
                        </div>
                    </div>
                </section>
            </main>

            {{-- Footer --}}
            <footer class="border-t border-gray-100 bg-white">
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