<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-xl text-sea-blue-800 leading-tight">
                    @if($mode === 'humas')
                        My Escalation Logs
                    @else
                        Escalation Logs
                    @endif
                </h2>
                <!-- <p class="text-xs text-gray-500 mt-0.5">
                    @if($mode === 'humas')
                        Riwayat eskalasi berita yang kamu lakukan.
                    @else
                        Riwayat semua eskalasi berita pada site ini.
                    @endif
                </p> -->
            </div>

            <div class="flex items-center gap-2">
                <button
                    type="button"
                    id="toggle-filter"
                    class="inline-flex items-center justify-center px-3 py-2 rounded-lg border border-sea-blue-100 text-sea-blue-700 bg-white hover:bg-sea-blue-50 hover:border-sea-blue-200 transition-colors duration-150 text-xs"
                >
                    <i data-lucide="filter" class="w-4 h-4 mr-1"></i>
                    <span class="hidden sm:inline">Filter</span>
                </button>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Filter Section --}}
            <div id="filter-panel" class="bg-white border border-sea-blue-100 rounded-xl shadow-sm mb-4 px-3 py-3 sm:px-4 sm:py-4 hidden">
                <form method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-3 text-xs sm:text-sm">
                    {{-- Search --}}
                    <div class="space-y-1">
                        <label class="block font-medium text-gray-600 text-xs">Search</label>
                        <input
                            type="text"
                            name="search"
                            value="{{ request('search') }}"
                            placeholder="Title, recipient, contact..."
                            class="w-full rounded-lg border-gray-200 focus:border-sea-blue-500 focus:ring-sea-blue-500 text-xs py-1.5"
                        >
                    </div>

                    {{-- Channel --}}
                    <div class="space-y-1">
                        <label class="block font-medium text-gray-600 text-xs">Channel</label>
                        <select
                            name="channel"
                            class="w-full rounded-lg border-gray-200 focus:border-sea-blue-500 focus:ring-sea-blue-500 text-xs py-1.5"
                        >
                            <option value="">All</option>
                            <option value="email"    {{ request('channel') == 'email' ? 'selected' : '' }}>Email</option>
                            <option value="whatsapp" {{ request('channel') == 'whatsapp' ? 'selected' : '' }}>WhatsApp</option>
                            <option value="both"     {{ request('channel') == 'both' ? 'selected' : '' }}>Both</option>
                        </select>
                    </div>

                    {{-- Status --}}
                    <div class="space-y-1">
                        <label class="block font-medium text-gray-600 text-xs">Status</label>
                        <select
                            name="status"
                            class="w-full rounded-lg border-gray-200 focus:border-sea-blue-500 focus:ring-sea-blue-500 text-xs py-1.5"
                        >
                            <option value="">All</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="sent"    {{ request('status') == 'sent'    ? 'selected' : '' }}>Sent</option>
                            <option value="failed"  {{ request('status') == 'failed'  ? 'selected' : '' }}>Failed</option>
                        </select>
                    </div>

                    {{-- Info text --}}
                    <div class="space-y-1 md:col-span-2">
                        <label class="block font-medium text-gray-600 text-xs">Info</label>
                        <p class="text-[11px] text-gray-500">
                            Menampilkan log eskalasi berdasarkan waktu terkirim (SentDate) dan waktu dibuat.
                        </p>
                    </div>

                    {{-- Actions --}}
                    <div class="flex items-end gap-2 md:col-span-1">
                        <button
                            type="submit"
                            class="inline-flex items-center justify-center gap-1.5 bg-sea-blue-600 hover:bg-sea-blue-700 text-white px-3 py-1.5 rounded-lg text-xs font-medium transition duration-150"
                        >
                            <i data-lucide="search" class="w-3.5 h-3.5"></i>
                            Filter
                        </button>
                        <a
                            href="{{ route($mode === 'humas' ? 'escalations.my' : 'escalations.index') }}"
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
                                    Ticket
                                </th>
                                <th class="px-6 py-3 text-left text-[11px] font-semibold text-gray-500 uppercase tracking-wide">
                                    Channel
                                </th>
                                <th class="px-6 py-3 text-left text-[11px] font-semibold text-gray-500 uppercase tracking-wide">
                                    Recipient
                                </th>
                                <th class="px-6 py-3 text-left text-[11px] font-semibold text-gray-500 uppercase tracking-wide">
                                    Contact
                                </th>
                                <th class="px-6 py-3 text-left text-[11px] font-semibold text-gray-500 uppercase tracking-wide">
                                    Status
                                </th>
                                <th class="px-6 py-3 text-left text-[11px] font-semibold text-gray-500 uppercase tracking-wide">
                                    Sent At
                                </th>
                                <th class="px-6 py-3 text-left text-[11px] font-semibold text-gray-500 uppercase tracking-wide">
                                    By
                                </th>
                                <th class="px-6 py-3 text-right text-[11px] font-semibold text-gray-500 uppercase tracking-wide">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            @forelse($logs as $log)
                                <tr class="hover:bg-sea-blue-50/40 transition-colors duration-150">
                                    {{-- Ticket --}}
                                    <td class="px-6 py-4 text-sm">
                                        @if($log->ticket)
                                            <a
                                                href="{{ route('tickets.show', $log->ticket->id) }}"
                                                class="text-sea-blue-700 hover:text-sea-blue-900 font-medium hover:underline"
                                            >
                                                {{ $log->ticket->Title }}
                                            </a>
                                        @else
                                            <span class="text-gray-400 text-xs italic">Ticket deleted</span>
                                        @endif
                                    </td>

                                    {{-- Channel --}}
                                    <td class="px-6 py-4 text-sm">
                                        <span class="inline-flex items-center px-2.5 py-0.5 text-[11px] font-medium rounded-full
                                            @if($log->Channel === 'email')
                                                bg-sky-50 text-sky-700 ring-1 ring-inset ring-sky-100
                                            @elseif($log->Channel === 'whatsapp')
                                                bg-green-50 text-green-700 ring-1 ring-inset ring-green-100
                                            @else
                                                bg-purple-50 text-purple-700 ring-1 ring-inset ring-purple-100
                                            @endif
                                        ">
                                            {{ ucfirst($log->Channel) }}
                                        </span>
                                    </td>

                                    {{-- Recipient --}}
                                    <td class="px-6 py-4 text-sm text-gray-700">
                                        {{ $log->Recipient ?? '-' }}
                                    </td>

                                    {{-- Contact --}}
                                    <td class="px-6 py-4 text-sm text-gray-700">
                                        @if($log->contact)
                                            {{ $log->contact->Name }}
                                        @else
                                            <span class="text-gray-400 text-xs italic">Manual recipient</span>
                                        @endif
                                    </td>

                                    {{-- Status --}}
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center px-2.5 py-0.5 text-[11px] font-medium rounded-full
                                            @if($log->Status === 'sent')
                                                bg-green-50 text-green-700 ring-1 ring-inset ring-green-100
                                            @elseif($log->Status === 'failed')
                                                bg-red-50 text-red-700 ring-1 ring-inset ring-red-100
                                            @else
                                                bg-yellow-50 text-yellow-700 ring-1 ring-inset ring-yellow-100
                                            @endif
                                        ">
                                            {{ ucfirst($log->Status) }}
                                        </span>
                                    </td>

                                    {{-- SentDate --}}
                                    <td class="px-6 py-4 text-sm text-gray-600">
                                        @if($log->SentDate)
                                            {{ $log->SentDate->format('d M Y H:i') }}
                                        @else
                                            <span class="text-gray-400 text-xs italic">Not sent</span>
                                        @endif
                                    </td>

                                    {{-- Escalated by --}}
                                    <td class="px-6 py-4 text-sm text-gray-700">
                                        {{ $log->escalator?->name ?? '-' }}
                                    </td>

                                    {{-- Actions (view-only) --}}
                                    <td class="px-6 py-4 text-right space-x-2">
                                        @if($log->ticket)
                                            <a
                                                href="{{ route('tickets.show', $log->ticket->id) }}"
                                                class="inline-flex items-center justify-center w-8 h-8 rounded-full text-sea-blue-700 hover:bg-sea-blue-50 transition-colors duration-150"
                                                title="Lihat ticket"
                                            >
                                                <i data-lucide="eye" class="w-4 h-4"></i>
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-6 py-12 text-center text-gray-500">
                                        <i data-lucide="inbox" class="w-10 h-10 mx-auto text-gray-300 mb-3"></i>
                                        <p class="text-sm font-medium">Belum ada eskalasi berita</p>
                                        <p class="text-xs text-gray-400 mt-1">
                                            Coba lakukan eskalasi dari salah satu berita untuk melihat log di sini.
                                        </p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                <div class="px-4 sm:px-6 py-3 border-t border-gray-100">
                    {{ $logs->links() }}
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
