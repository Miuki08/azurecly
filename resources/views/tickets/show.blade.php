<x-app-layout
    x-data="{
        showEscalationModal: false,
        channel: 'email',
        contactId: '',
        recipient: '',
        message: '',
        showContactDropdown: false,
        selectedContactName: '',
        isSubmitting: false
    }"
>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-sea-blue-800 leading-tight">
                Detail Berita
            </h2>
            <div class="flex items-center gap-2">
                {{-- Delete --}}
                <form action="{{ route('tickets.destroy', $ticket->id) }}" method="POST" class="inline"
                      onsubmit="return confirm('Yakin ingin menghapus berita ini?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="inline-flex items-center px-3 py-2 border border-red-200 text-red-600 hover:bg-red-50 rounded-lg text-sm font-medium transition">
                        <i data-lucide="trash-2" class="w-4 h-4 mr-1.5"></i>
                        Hapus
                    </button>
                </form>

                {{-- Edit --}}
                <a href="{{ route('tickets.edit', $ticket->id) }}"
                   class="inline-flex items-center px-3 py-2 bg-sea-blue-600 hover:bg-sea-blue-700 text-white rounded-lg text-sm font-medium transition">
                    <i data-lucide="edit" class="w-4 h-4 mr-1.5"></i>
                    Edit
                </a>

                {{-- Eskalasi / Share --}}
                <button
                    type="button"
                    @click="showEscalationModal = true"
                    class="inline-flex items-center px-3 py-2 border border-sea-blue-200 text-sea-blue-700 bg-white hover:bg-sea-blue-50 rounded-lg text-sm font-medium transition"
                >
                    <i data-lucide="share-2" class="w-4 h-4 mr-1.5"></i>
                    Eskalasi
                </button>

                {{-- Kembali --}}
                <a href="{{ route('tickets.index') }}"
                   class="inline-flex items-center px-3 py-2 border border-gray-200 text-gray-700 hover:bg-gray-50 rounded-lg text-sm font-medium transition">
                    <i data-lucide="arrow-left" class="w-4 h-4 mr-1.5"></i>
                    Kembali
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-lg shadow border border-gray-100 overflow-hidden">

                {{-- Header status --}}
                <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-sea-blue-50 to-white">
                    <div class="flex flex-col gap-2">
                        {{-- Title --}}
                        <h1 class="text-xl font-semibold text-gray-900 mb-0 line-clamp-2">
                            {{ $ticket->Title }}
                        </h1>

                        {{-- Badges --}}
                        <div class="flex flex-wrap items-center gap-2 text-[11px] text-gray-500">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium
                                {{ $ticket->Sentiment == 'positive' ? 'bg-green-100 text-green-700' :
                                   ($ticket->Sentiment == 'negative' ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-700') }}">
                                {{ ucfirst($ticket->Sentiment) }}
                            </span>

                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium
                                {{ $ticket->Priority == 'high' ? 'bg-red-100 text-red-700' :
                                   ($ticket->Priority == 'medium' ? 'bg-yellow-100 text-yellow-700' : 'bg-green-100 text-green-700') }}">
                                Priority: {{ ucfirst($ticket->Priority) }}
                            </span>

                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-600">
                                <i data-lucide="eye" class="w-3 h-3 mr-1"></i>
                                {{ $ticket->ViewCount }} views
                            </span>
                        </div>

                        {{-- Dibuat / Publikasi --}}
                        <div class="flex items-center justify-between text-[11px] text-gray-500 mt-1">
                            <span>
                                Dibuat: {{ $ticket->created_at?->format('d M Y H:i') }}
                            </span>

                            @if($ticket->PublishedDate)
                                <span>
                                    Publikasi: {{ $ticket->PublishedDate->format('d M Y H:i') }}
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="px-6 py-5 space-y-6">

                    {{-- Hero image --}}
                    @if($ticket->images && $ticket->images->count())
                        @php
                            $hero = $ticket->images->first();
                        @endphp

                        <div class="rounded-lg overflow-hidden border border-gray-200 bg-gray-50">
                            <img
                                src="{{ asset('storage/'.$hero->Path) }}"
                                alt="Media"
                                class="w-full max-h-80 object-cover"
                            >
                        </div>
                    @endif

                    {{-- Grid informasi --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        {{-- Kategori --}}
                        <div class="flex items-center gap-2 bg-gray-50 rounded-lg px-3 py-2.5">
                            <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-white border border-gray-200 text-gray-500">
                                <i data-lucide="folder" class="w-4 h-4"></i>
                            </span>
                            <p class="text-sm text-gray-800 font-medium truncate">
                                {{ $ticket->Category }}
                            </p>
                        </div>

                        {{-- Aktor --}}
                        @if($ticket->Actor)
                            <div class="flex items-center gap-2 bg-gray-50 rounded-lg px-3 py-2.5">
                                <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-white border border-gray-200 text-gray-500">
                                    <i data-lucide="users" class="w-4 h-4"></i>
                                </span>
                                <p class="text-sm text-gray-800 font-medium truncate">
                                    {{ $ticket->Actor }}
                                </p>
                            </div>
                        @endif

                        {{-- Tag --}}
                        @if($ticket->Tag)
                            <div class="flex items-center gap-2 bg-gray-50 rounded-lg px-3 py-2.5">
                                <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-white border border-gray-200 text-gray-500">
                                    <i data-lucide="tag" class="w-4 h-4"></i>
                                </span>
                                <p class="text-sm text-gray-800 font-medium truncate">
                                    {{ $ticket->Tag }}
                                </p>
                            </div>
                        @endif

                        {{-- Region --}}
                        @if($ticket->Region)
                            <div class="flex items-center gap-2 bg-gray-50 rounded-lg px-3 py-2.5">
                                <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-white border border-gray-200 text-gray-500">
                                    <i data-lucide="map" class="w-4 h-4"></i>
                                </span>
                                <p class="text-sm text-gray-800 font-medium truncate">
                                    {{ $ticket->Region }}
                                </p>
                            </div>
                        @endif

                        {{-- Lokasi (full width di md) --}}
                        @if($ticket->Location)
                            <div class="flex items-center gap-2 bg-gray-50 rounded-lg px-3 py-2.5 md:col-span-2">
                                <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-white border border-gray-200 text-gray-500 flex-shrink-0">
                                    <i data-lucide="map-pin" class="w-4 h-4"></i>
                                </span>
                                <p class="text-sm text-gray-800 font-medium">
                                    {{ $ticket->Location }}
                                </p>
                            </div>
                        @endif

                        {{-- Koordinat --}}
                        @if($ticket->Latitude && $ticket->Longitude)
                            <div class="flex items-center gap-2 bg-gray-50 rounded-lg px-3 py-2.5">
                                <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-white border border-gray-200 text-gray-500">
                                    <i data-lucide="navigation" class="w-4 h-4"></i>
                                </span>
                                <p class="text-sm text-gray-800 font-medium">
                                    {{ $ticket->Latitude }}, {{ $ticket->Longitude }}
                                </p>
                            </div>
                        @endif

                        {{-- Tanggal publikasi (sebagai info saja) --}}
                        <div class="flex items-center gap-2 bg-gray-50 rounded-lg px-3 py-2.5">
                            <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-white border border-gray-200 text-gray-500">
                                <i data-lucide="calendar" class="w-4 h-4"></i>
                            </span>
                            <p class="text-sm text-gray-800 font-medium">
                                {{ $ticket->PublishedDate ? $ticket->PublishedDate->format('d F Y H:i') : 'Belum dipublikasi' }}
                            </p>
                        </div>

                        {{-- Penulis --}}
                        <div class="flex items-center gap-2 bg-gray-50 rounded-lg px-3 py-2.5">
                            <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-white border border-gray-200 text-gray-500">
                                <i data-lucide="user" class="w-4 h-4"></i>
                            </span>
                            <p class="text-sm text-gray-800 font-medium">
                                {{ $ticket->creator->name ?? 'Unknown' }}
                            </p>
                        </div>
                    </div>

                    {{-- Deskripsi --}}
                    <div class="pt-2 border-t border-gray-100">
                        <h3 class="text-sm font-semibold text-gray-800 mb-2">Deskripsi</h3>
                        <p class="text-sm text-gray-700 leading-relaxed whitespace-pre-wrap">
                            {{ $ticket->Description }}
                        </p>
                    </div>

                    {{-- Gallery attachment --}}
                    @if($ticket->images && $ticket->images->count() > 1)
                        <div class="pt-4 border-t border-gray-100">
                            <h3 class="text-sm font-semibold text-gray-800 mb-3">Lampiran</h3>
                            <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                                @foreach($ticket->images as $img)
                                    @continue($loop->first) {{-- lewati hero image --}}
                                    <div class="relative rounded-lg overflow-hidden border border-gray-200 bg-gray-50">
                                        <img
                                            src="{{ asset('storage/'.$img->Path) }}"
                                            class="w-full h-32 md:h-36 lg:h-40 object-cover"
                                            alt="Lampiran"
                                        >
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>

    {{-- Modal Eskalasi / Share --}}
    <div
        x-cloak
        x-show="showEscalationModal"
        class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm"
        x-transition.opacity
    >
        <div
            class="bg-white rounded-xl shadow-xl border border-gray-100 w-full max-w-2xl mx-4"
            @click.away="showEscalationModal = false"
            x-transition.scale
        >
            {{-- Header --}}
            <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
                <h3 class="text-sm font-semibold text-gray-800">
                    Eskalasi Berita
                </h3>
                <button type="button" class="text-gray-400 hover:text-gray-600" @click="showEscalationModal = false">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
            </div>

            {{-- Body --}}
            <form
                action="{{ route('tickets.escalate', $ticket->id) }}"
                method="POST"
                class="px-5 py-4 space-y-4"
                @Submit='isSubmitting = true'
            >
                @csrf

                {{-- Channel --}}
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">
                        Channel
                    </label>
                    <div class="flex items-center gap-3 text-xs text-gray-700">
                        <label class="inline-flex items-center gap-1.5">
                            <input type="radio" name="channel" value="email" x-model="channel" class="text-sea-blue-600 border-gray-300">
                            <span>Email</span>
                        </label>
                        <label class="inline-flex items-center gap-1.5">
                            <input type="radio" name="channel" value="whatsapp" x-model="channel" class="text-sea-blue-600 border-gray-300">
                            <span>WhatsApp</span>
                        </label>
                        <label class="inline-flex items-center gap-1.5">
                            <input type="radio" name="channel" value="telegram" x-model="channel" class="text-sea-blue-600 border-gray-300">
                            <span>Telegram</span>
                        </label>
                        <label class="inline-flex items-center gap-1.5">
                            <input type="radio" name="channel" value="both" x-model="channel" class="text-sea-blue-600 border-gray-300">
                            <span>Keduanya</span>
                        </label>
                    </div>
                </div>

                {{-- Kontak atau manual --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div x-data>
                        <label class="block text-xs font-medium text-gray-700 mb-1">
                            Kontak (opsional)
                        </label>
                        <input type="hidden" name="contact_id" :value="contactId">

                        <div class="relative">
                            <button
                                type="button"
                                @click="showContactDropdown = !showContactDropdown"
                                class="w-full flex items-center justify-between rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-left text-sm text-gray-700 hover:border-sea-blue-400 focus:border-sea-blue-500 focus:ring-1 focus:ring-sea-blue-500 transition"
                            >
                                <span class="flex items-center gap-2">
                                    <template x-if="selectedContactName">
                                        <span
                                            class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-sea-blue-100 text-sea-blue-700 text-xs font-semibold uppercase"
                                        >
                                            <span x-text="selectedContactName.charAt(0)"></span>
                                        </span>
                                    </template>

                                    <span class="flex flex-col">
                                        <span
                                            class="text-xs font-medium text-gray-900"
                                            x-text="selectedContactName || 'Pilih kontak...'"
                                        ></span>
                                        <!-- <span class="text-[11px] text-gray-400" x-show="!selectedContactName">
                                            Kontak akan mengisi email / nomor otomatis
                                        </span> -->
                                    </span>
                                </span>

                                <i data-lucide="chevron-down" class="w-4 h-4 text-gray-400"></i>
                            </button>

                            {{-- Dropdown list --}}
                            <div
                                x-show="showContactDropdown"
                                @click.away="showContactDropdown = false"
                                x-transition
                                class="absolute z-50 mt-1 w-full max-h-60 overflow-y-auto rounded-lg border border-gray-200 bg-white shadow-lg"
                            >
                                @forelse($contacts ?? [] as $contact)
                                    @php
                                        $initial = mb_substr($contact->Name, 0, 1);
                                    @endphp
                                    <button
                                        type="button"
                                        class="w-full px-3 py-2.5 flex items-start gap-2 hover:bg-sea-blue-50 text-left text-xs text-gray-800"
                                        @click="
                                            contactId = '{{ $contact->id }}';
                                            selectedContactName = '{{ addslashes($contact->Name) }}';
                                            showContactDropdown = false;
                                        "
                                    >
                                        <span
                                            class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-sea-blue-100 text-sea-blue-700 text-xs font-semibold uppercase flex-shrink-0"
                                        >
                                            {{ $initial }}
                                        </span>

                                        <span class="flex flex-col">
                                            <span class="font-medium text-gray-900">
                                                {{ $contact->Name }}
                                            </span>
                                            @if($contact->Phone)
                                                <span class="flex items-center gap-1 text-[11px] text-gray-600">
                                                    <!-- <i data-lucide="phone" class="w-3 h-3"></i> -->
                                                     <i data-lucide="message-circle" class="w-3 h-3"></i>
                                                    {{ $contact->Phone }}
                                                </span>
                                            @endif

                                            @if($contact->Email)
                                                <span class="flex items-center gap-1 text-[11px] text-gray-600">
                                                    <i data-lucide="mail" class="w-3 h-3"></i>
                                                    {{ $contact->Email }}
                                                </span>
                                            @endif
                                        </span>
                                    </button>
                                @empty
                                    <div class="px-3 py-2 text-[11px] text-gray-400">
                                        Belum ada kontak terdaftar.
                                    </div>
                                @endforelse
                            </div>
                        </div>

                        <p class="mt-1 text-[11px] text-gray-400">
                            Jika dipilih, sistem dapat menggunakan email / nomor dari kontak ini.
                        </p>
                    </div>

                    {{-- Recipient manual --}}
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">
                            Penerima (email atau nomor WhatsApp)
                        </label>
                        <input
                            type="text"
                            name="recipient"
                            x-model="recipient"
                            class="w-full rounded-lg border-gray-300 focus:border-sea-blue-500 focus:ring-sea-blue-500 text-sm"
                            placeholder="contoh: email@domain.go.id atau 62812xxxx"
                        >
                        <p class="mt-1 text-[11px] text-gray-400">
                            Jika kosong dan kontak dipilih, sistem akan memakai data dari kontak.
                        </p>
                    </div>
                </div>

                {{-- Pesan --}}
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">
                        Pesan
                    </label>
                    <textarea
                        name="message"
                        rows="4"
                        x-model="message"
                        class="w-full rounded-lg border-gray-300 focus:border-sea-blue-500 focus:ring-sea-blue-500 text-sm"
                        x-init="message = `Yth,

                    Terkait berita: &quot;{{ addslashes($ticket->Title) }}&quot;.

                    Mohon tindak lanjut.`"
                    ></textarea>
                    <p class="mt-1 text-[11px] text-gray-400">
                        Pesan ini akan dicatat di log eskalasi dan menjadi isi email / WhatsApp.
                    </p>
                </div>

                {{-- Footer --}}
                <div class="flex justify-end gap-2 pt-3 border-t border-gray-100">
                    <button
                        type="button"
                        class="px-4 py-2 border border-gray-300 rounded-lg text-xs text-gray-700 hover:bg-gray-50 transition"
                        @click="showEscalationModal = false"
                        :disabled="isSubmitting"
                    >
                        Batal
                    </button>
                    <button
                        type="submit"
                        class="px-4 py-2 bg-sea-blue-600 hover:bg-sea-blue-700 text-white rounded-lg text-xs font-medium inline-flex items-center gap-1 transition disabled:opacity-60 disabled:cursor-not-allowed"
                        :disabled="isSubmitting"
                    >
                        <svg
                            x-show="isSubmitting"
                            class="w-4 h-4 animate-spin text-white"
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                        >
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 
                                    5.291A7.962 7.962 0 014 12H0c0 
                                    3.042 1.135 5.824 3 7.938l3-2.647z">
                            </path>
                        </svg>

                        <i x-show="!isSubmitting" data-lucide="send" class="w-4 h-4"></i>

                        <span x-text="isSubmitting ? 'Mengirim...' : 'Kirim Eskalasi'"></span>
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

        @if(session('success'))
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: @json(session('success')),
                        confirmButtonColor: '#0284c7',
                    });
                });
            </script>
        @endif

        @if(session('error'))
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: @json(session('error')),
                        confirmButtonColor: '#dc2626',
                    });
                });
            </script>
        @endif
    @endpush
</x-app-layout>