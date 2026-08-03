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

                                    {{-- Actions --}}
                                    <td class="px-6 py-4 text-right space-x-2">
                                        @if($log->ticket)
                                            <a
                                                href="{{ route('tickets.show', $log->ticket->id) }}"
                                                class="inline-flex items-center justify-center w-8 h-8 rounded-full text-sea-blue-700 hover:bg-sea-blue-50 transition-colors duration-150"
                                                title="Lihat berita"
                                            >
                                                <i data-lucide="eye" class="w-4 h-4"></i>
                                            </a>
                                        @endif

                                        @if(auth()->user()->Admin())
                                            <button
                                                type="button"
                                                class="inline-flex items-center justify-center w-8 h-8 rounded-full text-red-600 hover:bg-red-50 transition-colors duration-150 face-delete-btn"
                                                title="Hapus eskalasi dengan verifikasi wajah"
                                                data-escalation-id="{{ $log->id }}"
                                            >
                                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                                            </button>
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

    {{-- Modal verify biometric --}}
    <div id="face-modal"
        class="fixed inset-0 bg-black/40 z-50 items-center justify-center hidden">
        <div class="bg-white rounded-xl shadow-xl max-w-lg w-full mx-4">
            <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <div class="p-2 bg-sea-blue-100 rounded-lg">
                        <i data-lucide="scan-face" class="w-5 h-5 text-sea-blue-600"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-semibold text-gray-900">
                            Verifikasi Wajah untuk Hapus Eskalasi
                        </h3>
                        <p class="text-[11px] text-gray-500">
                            Pastikan hanya Anda yang menghapus eskalasi ini.
                        </p>
                    </div>
                </div>
                <button type="button" id="face-modal-close"
                        class="w-7 h-7 inline-flex items-center justify-center rounded-full hover:bg-gray-100 text-gray-400 hover:text-gray-600">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
            </div>

            <div class="px-5 py-4 space-y-3">
                <div class="text-[11px] text-gray-500">
                    Wajah diproses lokal di browser. Sistem hanya mengirim deskriptor numerik untuk diverifikasi dengan data yang tersimpan di akun admin.
                </div>

                <div class="border border-gray-200 rounded-lg overflow-hidden bg-gray-50">
                    <div class="p-3">
                        <div class="flex items-center justify-between mb-1.5">
                            <span class="text-xs font-medium text-gray-700">
                                Camera Preview
                            </span>
                            <span id="face-modal-status"
                                class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full bg-gray-100 text-[11px] text-gray-600">
                                <span class="w-1.5 h-1.5 rounded-full bg-gray-400"></span>
                                <span>Idle</span>
                            </span>
                        </div>
                        <div class="relative rounded-md overflow-hidden bg-black aspect-video">
                            <video id="face-modal-video" autoplay muted playsinline class="w-full h-full object-cover"></video>
                            <canvas id="face-modal-overlay" class="absolute inset-0 w-full h-full"></canvas>
                        </div>
                        <p class="mt-1.5 text-[11px] text-gray-500">
                            Posisikan wajah di tengah frame, pencahayaan cukup, lalu klik "Verify & Delete".
                        </p>
                    </div>
                </div>

                <p id="face-modal-message" class="text-[11px] text-gray-500"></p>
            </div>

            <div class="px-5 py-3 border-t border-gray-100 flex items-center justify-between">
                <button type="button"
                        id="face-modal-cancel"
                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-gray-200 text-xs text-gray-600 hover:bg-gray-50">
                    <i data-lucide="x-circle" class="w-3.5 h-3.5"></i>
                    <span>Batal</span>
                </button>

                <div class="flex items-center gap-2">
                    <button type="button"
                            id="face-modal-start"
                            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-gray-300 text-xs text-gray-700 hover:bg-gray-50">
                        <i data-lucide="video" class="w-3.5 h-3.5"></i>
                        <span>Start Camera</span>
                    </button>

                    <button type="button"
                            id="face-modal-verify"
                            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-red-600 text-white text-xs font-medium hover:bg-red-700 disabled:opacity-40 disabled:cursor-not-allowed"
                            disabled>
                        <i data-lucide="shield-check" class="w-3.5 h-3.5"></i>
                        <span>Verify & Delete</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

   @push('scripts')
        <script src="https://unpkg.com/lucide@latest"></script>
        <script defer src="https://cdn.jsdelivr.net/npm/face-api.js@0.22.2/dist/face-api.min.js"></script>

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

                const modal       = document.getElementById('face-modal');
                const closeBtn    = document.getElementById('face-modal-close');
                const cancelBtn   = document.getElementById('face-modal-cancel');
                const startBtn    = document.getElementById('face-modal-start');
                const verifyBtn   = document.getElementById('face-modal-verify');
                const video       = document.getElementById('face-modal-video');
                const overlay     = document.getElementById('face-modal-overlay');
                const statusBadge = document.getElementById('face-modal-status');
                const messageEl   = document.getElementById('face-modal-message');

                const deleteButtons = document.querySelectorAll('.face-delete-btn');

                let stream = null;
                let modelsLoaded = false;
                let currentEscalationId = null;

                function setStatus(text, colorDot = '#9CA3AF') {
                    if (!statusBadge) return;
                    const dot = statusBadge.querySelector('span.w-1\\.5') || statusBadge.children[0];
                    const label = statusBadge.querySelector('span:nth-child(2)') || statusBadge.children[1];
                    dot.style.backgroundColor = colorDot;
                    label.textContent = text;
                }

                async function loadModels() {
                    if (modelsLoaded) return;
                    setStatus('Loading models...', '#F59E0B');
                    messageEl.textContent = 'Loading face recognition models, please wait...';

                    const MODEL_URL = '/models/face-api';

                    await Promise.all([
                        faceapi.nets.tinyFaceDetector.loadFromUri(MODEL_URL),
                        faceapi.nets.faceLandmark68Net.loadFromUri(MODEL_URL),
                        faceapi.nets.faceRecognitionNet.loadFromUri(MODEL_URL),
                    ]);

                    modelsLoaded = true;
                    setStatus('Models loaded', '#10B981');
                    messageEl.textContent = 'Models loaded. Start the camera, then click "Verify & Delete".';
                }

                async function startCamera() {
                    try {
                        await loadModels();
                        stream = await navigator.mediaDevices.getUserMedia({ video: { facingMode: 'user' } });
                        video.srcObject = stream;
                        verifyBtn.disabled = false;
                        setStatus('Camera on', '#22C55E');
                        messageEl.textContent = 'Camera started. Position your face in the center.';
                    } catch (err) {
                        console.error(err);
                        setStatus('Camera error', '#EF4444');
                        messageEl.textContent = 'Failed to start camera. Please check permissions or device.';
                    }
                }

                function stopCamera() {
                    if (stream) {
                        stream.getTracks().forEach(t => t.stop());
                        stream = null;
                    }
                    verifyBtn.disabled = true;
                    setStatus('Idle', '#9CA3AF');
                }

                function openModal(escalationId) {
                    currentEscalationId = escalationId;
                    modal.classList.remove('hidden');
                    modal.classList.add('flex');
                    setStatus('Idle', '#9CA3AF');
                    messageEl.textContent = 'Click "Start Camera" to begin face verification.';
                    const ctx = overlay.getContext('2d');
                    ctx.clearRect(0, 0, overlay.width, overlay.height);
                }

                function closeModal() {
                    stopCamera();
                    modal.classList.add('hidden');
                    modal.classList.remove('flex');
                    currentEscalationId = null;
                }

                async function captureAndVerify() {
                    try {
                        if (!modelsLoaded) {
                            await loadModels();
                        }
                        if (!video.srcObject) {
                            messageEl.textContent = 'Camera is not running.';
                            return;
                        }
                        if (!currentEscalationId) {
                            messageEl.textContent = 'Escalation ID is missing.';
                            return;
                        }

                        setStatus('Detecting face...', '#F59E0B');
                        messageEl.textContent = 'Detecting your face...';

                        const displaySize = { width: video.videoWidth, height: video.videoHeight };
                        faceapi.matchDimensions(overlay, displaySize);

                        const options = new faceapi.TinyFaceDetectorOptions({
                            inputSize: 224,
                            scoreThreshold: 0.5,
                        });

                        const detection = await faceapi
                            .detectSingleFace(video, options)
                            .withFaceLandmarks()
                            .withFaceDescriptor();

                        const ctx = overlay.getContext('2d');
                        ctx.clearRect(0, 0, overlay.width, overlay.height);

                        if (!detection) {
                            setStatus('No face detected', '#F97316');
                            messageEl.textContent = 'Face not detected. Please check lighting and distance, then try again.';
                            return;
                        }

                        const resizedDetections = faceapi.resizeResults(detection, displaySize);
                        faceapi.draw.drawDetections(overlay, resizedDetections);
                        faceapi.draw.drawFaceLandmarks(overlay, resizedDetections);

                        const descriptorArray = Array.from(detection.descriptor);

                        setStatus('Verifying...', '#3B82F6');
                        messageEl.textContent = 'Verifying your face...';

                        const res = await fetch(
                            "{{ route('escalations.verify-face', ['escalation' => '__ID__']) }}".replace('__ID__', currentEscalationId),
                            {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    'Accept': 'application/json',
                                },
                                body: JSON.stringify({
                                    descriptor: descriptorArray
                                })
                            }
                        );

                        const data = await res.json();

                        if (!res.ok || !data.ok) {
                            setStatus('Verification failed', '#EF4444');
                            messageEl.textContent = data.message || 'Face verification failed.';
                            return;
                        }

                        setStatus('Verified', '#10B981');
                        messageEl.textContent = data.message || 'Face verified & escalation deleted.';

                        setTimeout(() => {
                            closeModal();
                            window.location.reload();
                        }, 800);

                    } catch (err) {
                        console.error(err);
                        setStatus('Error', '#EF4444');
                        messageEl.textContent = 'An error occurred while verifying your face.';
                    }
                }

                deleteButtons.forEach(btn => {
                    btn.addEventListener('click', () => {
                        const escalationId = btn.getAttribute('data-escalation-id');
                        openModal(escalationId);
                    });
                });

                if (startBtn) startBtn.addEventListener('click', startCamera);
                if (verifyBtn) verifyBtn.addEventListener('click', captureAndVerify);
                if (closeBtn) closeBtn.addEventListener('click', closeModal);
                if (cancelBtn) cancelBtn.addEventListener('click', closeModal);

                window.addEventListener('beforeunload', stopCamera);
            });
        </script>
    @endpush
</x-app-layout>
