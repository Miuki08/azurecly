<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-sea-blue-800 leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white border border-gray-100 rounded-xl shadow-sm">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-sea-blue-100 rounded-lg">
                            <i data-lucide="user-circle-2" class="w-5 h-5 text-sea-blue-600"></i>
                        </div>
                        <div>
                            <h3 class="text-sm font-semibold text-gray-900">
                                {{ __('Account & Security') }}
                            </h3>
                            <p class="text-xs text-gray-500">
                                Kelola informasi profil, password, dan penghapusan akun dengan lapisan keamanan tambahan berbasis wajah.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="px-6 py-5 space-y-8">
                    @if(!$hasFace)
                        {{-- Read-only summary when no face is registered --}}
                        <section class="space-y-4 text-sm">
                            <header>
                                <h2 class="text-sm font-semibold text-gray-900">
                                    {{ __('Profile Information') }}
                                </h2>
                                <p class="mt-1 text-xs text-gray-600">
                                    Profil terkunci hingga kamu mendaftarkan wajahmu untuk verifikasi tambahan.
                                </p>
                            </header>

                            <div class="space-y-3">
                                <div>
                                    <div class="text-xs font-semibold text-gray-500">Name</div>
                                    <div class="mt-0.5 text-gray-800">{{ $user->name }}</div>
                                </div>
                                <div>
                                    <div class="text-xs font-semibold text-gray-500">Email</div>
                                    <div class="mt-0.5 text-gray-800">{{ $user->email }}</div>
                                </div>
                                <div>
                                    <div class="text-xs font-semibold text-gray-500">Organization</div>
                                    <div class="mt-0.5 text-gray-800">
                                        {{ $user->site?->Name ?? '-' }}
                                    </div>
                                </div>
                            </div>

                            <div class="mt-3 rounded-lg bg-amber-50 border border-amber-100 px-3 py-2 text-[11px] text-amber-800">
                                Untuk mengubah nama, email, password, atau menghapus akun, daftarkan wajahmu terlebih dahulu di bagian <span class="font-semibold">Face Verification</span> di bawah.
                            </div>
                        </section>
                    @else
                        <section id="profile-info-section">
                            @include('profile.partials.update-profile-information-form')
                        </section>

                        <section id="profile-password-section" class="pt-4 border-t border-gray-100">
                            @include('profile.partials.update-password-form')
                        </section>

                        <section id="profile-delete-section" class="pt-4 border-t border-gray-100">
                            @include('profile.partials.delete-user-form')
                        </section>
                    @endif
                </div>
            </div>

            <div class="bg-white border border-gray-100 rounded-xl shadow-sm">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-sea-blue-100 rounded-lg">
                            <i data-lucide="scan-face" class="w-5 h-5 text-sea-blue-600"></i>
                        </div>
                        <div>
                            <h3 class="text-sm font-semibold text-gray-900">
                                Face Verification
                            </h3>
                            <p class="text-xs text-gray-500">
                                Daftarkan atau perbarui deskriptor wajah yang digunakan untuk mengamankan perubahan profil dan penghapusan akun.
                            </p>
                        </div>
                    </div>

                    @if($user->FaceDescription)
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-emerald-50 border border-emerald-100 text-[11px] text-emerald-700">
                            <i data-lucide="shield-check" class="w-3 h-3"></i>
                            <span>Face registered</span>
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-amber-50 border border-amber-100 text-[11px] text-amber-700">
                            <i data-lucide="alert-triangle" class="w-3 h-3"></i>
                            <span>Not registered</span>
                        </span>
                    @endif
                </div>

                <div class="px-6 py-5 space-y-4">
                    <p class="text-xs text-gray-500">
                        Wajah diproses secara lokal di browser menggunakan face-api.js. Azurecly hanya menyimpan deskriptor numerik (bukan foto mentah) di akunmu. [web:172]
                    </p>

                    <div class="border border-gray-200 rounded-lg overflow-hidden bg-gray-50 flex flex-col md:flex-row">
                        <div class="md:w-1/2 p-3 flex flex-col">
                            <div class="text-xs font-medium text-gray-700 mb-2 flex items-center justify-between">
                                <span>Camera Preview</span>
                                <span id="face-status-badge"
                                      class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full bg-gray-100 text-[11px] text-gray-600">
                                    <span class="w-1.5 h-1.5 rounded-full bg-gray-400"></span>
                                    <span>Idle</span>
                                </span>
                            </div>
                            <div class="relative rounded-md overflow-hidden bg-black aspect-video">
                                <video id="face-video" autoplay muted playsinline class="w-full h-full object-cover"></video>
                                <canvas id="face-overlay" class="absolute inset-0 w-full h-full"></canvas>
                            </div>
                            <p class="mt-2 text-[11px] text-gray-500">
                                Pastikan wajah terlihat jelas, tidak terlalu gelap, dan berada di tengah frame.
                            </p>
                        </div>

                        <div class="md:w-1/2 p-3 flex flex-col justify-between">
                            <div class="space-y-2">
                                <p class="text-xs font-semibold text-gray-800">
                                    Langkah pendaftaran wajah
                                </p>
                                <ol class="list-decimal list-inside text-[11px] text-gray-600 space-y-1">
                                    <li>Aktifkan kamera dan izinkan akses browser.</li>
                                    <li>Posisikan wajah di tengah dan tahan beberapa detik.</li>
                                    <li>Klik "Capture &amp; Save" untuk menyimpan deskriptor wajah.</li>
                                </ol>
                            </div>

                            <div class="mt-3 flex flex-wrap items-center gap-2">
                                <button type="button"
                                        id="face-start-camera"
                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-gray-300 text-xs text-gray-700 hover:bg-gray-50 transition">
                                    <i data-lucide="video" class="w-4 h-4"></i>
                                    <span>{{ $user->FaceDescription ? 'Re-capture' : 'Start Camera' }}</span>
                                </button>

                                <button type="button"
                                        id="face-capture-save"
                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-sea-blue-600 text-white text-xs font-medium hover:bg-sea-blue-700 transition disabled:opacity-40 disabled:cursor-not-allowed"
                                        disabled>
                                    <i data-lucide="save" class="w-4 h-4"></i>
                                    <span>Capture &amp; Save</span>
                                </button>

                                <button type="button"
                                        id="face-stop-camera"
                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-gray-200 text-xs text-gray-500 hover:bg-gray-50 transition">
                                    <i data-lucide="square" class="w-3 h-3"></i>
                                    <span>Stop</span>
                                </button>
                            </div>

                            <p id="face-message" class="mt-2 text-[11px] text-gray-500"></p>
                        </div>
                    </div>
                </div>
            </div>

            <div id="profile-face-modal"
                 class="fixed inset-0 bg-black/40 z-50 items-center justify-center hidden">
                <div class="bg-white rounded-xl shadow-xl max-w-md w-full mx-4">
                    <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <div class="p-2 bg-sea-blue-100 rounded-lg">
                                <i data-lucide="scan-face" class="w-5 h-5 text-sea-blue-600"></i>
                            </div>
                            <div>
                                <h3 class="text-sm font-semibold text-gray-900">
                                    Verifikasi Wajah Diperlukan
                                </h3>
                                <p class="text-[11px] text-gray-500" id="profile-face-modal-purpose">
                                    Kami perlu memverifikasi wajahmu sebelum melanjutkan.
                                </p>
                            </div>
                        </div>
                        <button type="button" id="profile-face-modal-close"
                                class="w-7 h-7 inline-flex items-center justify-center rounded-full hover:bg-gray-100 text-gray-400 hover:text-gray-600">
                            <i data-lucide="x" class="w-4 h-4"></i>
                        </button>
                    </div>

                    <div class="px-5 py-4 space-y-3">
                        <div class="border border-gray-200 rounded-lg overflow-hidden bg-gray-50">
                            <div class="p-3">
                                <div class="flex items-center justify-between mb-1.5">
                                    <span class="text-xs font-medium text-gray-700">
                                        Camera Preview
                                    </span>
                                    <span id="profile-face-status"
                                          class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full bg-gray-100 text-[11px] text-gray-600">
                                        <span class="w-1.5 h-1.5 rounded-full bg-gray-400"></span>
                                        <span>Idle</span>
                                    </span>
                                </div>
                                <div class="relative rounded-md overflow-hidden bg-black aspect-video">
                                    <video id="profile-face-video" autoplay muted playsinline class="w-full h-full object-cover"></video>
                                    <canvas id="profile-face-overlay" class="absolute inset-0 w-full h-full"></canvas>
                                </div>
                                <p class="mt-1.5 text-[11px] text-gray-500">
                                    Posisikan wajah di tengah frame, pencahayaan cukup, lalu klik "Verify".
                                </p>
                            </div>
                        </div>

                        <p id="profile-face-message" class="text-[11px] text-gray-500"></p>
                    </div>

                    <div class="px-5 py-3 border-t border-gray-100 flex items-center justify-between">
                        <button type="button"
                                id="profile-face-cancel"
                                class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-gray-200 text-xs text-gray-600 hover:bg-gray-50">
                            <i data-lucide="x-circle" class="w-3.5 h-3.5"></i>
                            <span>Batal</span>
                        </button>

                        <div class="flex items-center gap-2">
                            <button type="button"
                                    id="profile-face-start"
                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-gray-300 text-xs text-gray-700 hover:bg-gray-50">
                                <i data-lucide="video" class="w-3.5 h-3.5"></i>
                                <span>Start Camera</span>
                            </button>
                            <button type="button"
                                    id="profile-face-verify"
                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-sea-blue-600 text-white text-xs font-medium hover:bg-sea-blue-700 disabled:opacity-40 disabled:cursor-not-allowed"
                                    disabled>
                                <i data-lucide="shield-check" class="w-3.5 h-3.5"></i>
                                <span>Verify</span>
                            </button>
                        </div>
                    </div>
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

                const video       = document.getElementById('face-video');
                const overlay     = document.getElementById('face-overlay');
                const startBtn    = document.getElementById('face-start-camera');
                const captureBtn  = document.getElementById('face-capture-save');
                const stopBtn     = document.getElementById('face-stop-camera');
                const messageEl   = document.getElementById('face-message');
                const statusBadge = document.getElementById('face-status-badge');

                let stream = null;
                let modelsLoaded = false;

                function setStatus(text, colorDot = '#9CA3AF') {
                    if (!statusBadge) return;
                    const dot   = statusBadge.querySelector('span.w-1\\.5') || statusBadge.children[0];
                    const label = statusBadge.querySelector('span:nth-child(2)') || statusBadge.children[1];
                    dot.style.backgroundColor = colorDot;
                    label.textContent = text;
                }

                async function loadModels() {
                    if (modelsLoaded) return;
                    setStatus('Loading models...', '#F59E0B');
                    if (messageEl) {
                        messageEl.textContent = 'Loading face recognition models, please wait...';
                    }

                    const MODEL_URL = '/models/face-api';

                    await Promise.all([
                        faceapi.nets.tinyFaceDetector.loadFromUri(MODEL_URL),
                        faceapi.nets.faceLandmark68Net.loadFromUri(MODEL_URL),
                        faceapi.nets.faceRecognitionNet.loadFromUri(MODEL_URL),
                    ]);

                    modelsLoaded = true;
                    setStatus('Models loaded', '#10B981');
                    if (messageEl) {
                        messageEl.textContent = 'Models loaded. You can start the camera and capture your face.';
                    }
                }

                async function startCamera() {
                    try {
                        await loadModels();

                        stream = await navigator.mediaDevices.getUserMedia({ video: { facingMode: 'user' } });
                        video.srcObject = stream;
                        setStatus('Camera on', '#22C55E');
                        captureBtn.disabled = false;
                        messageEl.textContent = 'Camera started. Position your face in the center of the frame.';
                    } catch (err) {
                        console.error(err);
                        setStatus('Camera error', '#EF4444');
                        messageEl.textContent = 'Failed to start camera. Please check permissions or device.';
                    }
                }

                function stopCamera() {
                    if (stream) {
                        stream.getTracks().forEach(track => track.stop());
                        stream = null;
                    }
                    captureBtn.disabled = true;
                    setStatus('Idle', '#9CA3AF');
                    if (messageEl) {
                        messageEl.textContent = 'Camera stopped.';
                    }
                }

                async function captureAndSave() {
                    try {
                        if (!modelsLoaded) {
                            await loadModels();
                        }
                        if (!video.srcObject) {
                            messageEl.textContent = 'Camera is not running.';
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
                            messageEl.textContent = 'Face not detected. Please ensure your face is clearly visible and try again.';
                            return;
                        }

                        const resizedDetections = faceapi.resizeResults(detection, displaySize);
                        faceapi.draw.drawDetections(overlay, resizedDetections);
                        faceapi.draw.drawFaceLandmarks(overlay, resizedDetections);

                        const descriptorArray = Array.from(detection.descriptor);

                        setStatus('Saving...', '#3B82F6');
                        messageEl.textContent = 'Saving face descriptor...';

                        const res = await fetch("{{ route('profile.face-enroll-local') }}", {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json',
                            },
                            body: JSON.stringify({
                                descriptor: descriptorArray
                            })
                        });

                        if (!res.ok) {
                            throw new Error('Failed to save descriptor');
                        }

                        const data = await res.json();
                        setStatus('Saved', '#10B981');
                        messageEl.textContent = data.message || 'Face descriptor saved successfully.';

                        setTimeout(() => window.location.reload(), 800);

                    } catch (err) {
                        console.error(err);
                        setStatus('Error', '#EF4444');
                        messageEl.textContent = 'An error occurred while capturing or saving your face descriptor.';
                    }
                }

                if (startBtn)   startBtn.addEventListener('click', startCamera);
                if (stopBtn)    stopBtn.addEventListener('click', stopCamera);
                if (captureBtn) captureBtn.addEventListener('click', captureAndSave);

                const profileFormSection   = document.querySelector('#profile-info-section form');
                const profilePasswordForm  = document.querySelector('#profile-password-section form');
                const deleteUserForm       = document.querySelector('#profile-delete-section form[action="{{ route('profile.destroy') }}"]');

                if (profileFormSection) {
                    const btn = profileFormSection.querySelector('button[type="submit"], [type="submit"].btn-primary');
                    if (btn) {
                        btn.type = 'button';
                        btn.id   = 'profile-save-trigger';
                    }
                }

                if (profilePasswordForm) {
                    const btn = profilePasswordForm.querySelector('button[type="submit"]');
                    if (btn) {
                        btn.type = 'button';
                        btn.id   = 'password-save-trigger';
                    }
                }

                if (deleteUserForm) {
                    const deleteButton = deleteUserForm.querySelector('x-danger-button, button[type="submit"]');
                }

                const pfModal       = document.getElementById('profile-face-modal');
                const pfCloseBtn    = document.getElementById('profile-face-modal-close');
                const pfCancelBtn   = document.getElementById('profile-face-cancel');
                const pfStartBtn    = document.getElementById('profile-face-start');
                const pfVerifyBtn   = document.getElementById('profile-face-verify');
                const pfVideo       = document.getElementById('profile-face-video');
                const pfOverlay     = document.getElementById('profile-face-overlay');
                const pfStatus      = document.getElementById('profile-face-status');
                const pfMessage     = document.getElementById('profile-face-message');
                const pfPurposeText = document.getElementById('profile-face-modal-purpose');

                let pfStream = null;
                let pfAction = null; 

                function setPfStatus(text, colorDot = '#9CA3AF') {
                    if (!pfStatus) return;
                    const dot   = pfStatus.querySelector('span.w-1\\.5') || pfStatus.children[0];
                    const label = pfStatus.querySelector('span:nth-child(2)') || pfStatus.children[1];
                    dot.style.backgroundColor = colorDot;
                    label.textContent = text;
                }

                async function pfLoadModelsIfNeeded() {
                    if (!modelsLoaded) {
                        await loadModels();
                    }
                }

                async function pfStartCamera() {
                    try {
                        await pfLoadModelsIfNeeded();
                        pfStream = await navigator.mediaDevices.getUserMedia({ video: { facingMode: 'user' } });
                        pfVideo.srcObject = pfStream;
                        pfVerifyBtn.disabled = false;
                        setPfStatus('Camera on', '#22C55E');
                        pfMessage.textContent = 'Camera started. Position your face in the center.';
                    } catch (err) {
                        console.error(err);
                        setPfStatus('Camera error', '#EF4444');
                        pfMessage.textContent = 'Failed to start camera. Please check permissions or device.';
                    }
                }

                function pfStopCamera() {
                    if (pfStream) {
                        pfStream.getTracks().forEach(t => t.stop());
                        pfStream = null;
                    }
                    pfVerifyBtn.disabled = true;
                    setPfStatus('Idle', '#9CA3AF');
                }

                function pfOpenModal(action, purposeText) {
                    pfAction = action;
                    pfModal.classList.remove('hidden');
                    pfModal.classList.add('flex');
                    setPfStatus('Idle', '#9CA3AF');
                    pfMessage.textContent = 'Click "Start Camera" to begin face verification.';
                    if (purposeText) {
                        pfPurposeText.textContent = purposeText;
                    }
                    const ctx = pfOverlay.getContext('2d');
                    ctx.clearRect(0, 0, pfOverlay.width, pfOverlay.height);
                }

                function pfCloseModal() {
                    pfStopCamera();
                    pfModal.classList.add('hidden');
                    pfModal.classList.remove('flex');
                    pfAction = null;
                }

                async function pfCaptureAndVerify() {
                    try {
                        if (!modelsLoaded) {
                            await pfLoadModelsIfNeeded();
                        }
                        if (!pfVideo.srcObject) {
                            pfMessage.textContent = 'Camera is not running.';
                            return;
                        }

                        setPfStatus('Detecting face...', '#F59E0B');
                        pfMessage.textContent = 'Detecting your face...';

                        const displaySize = { width: pfVideo.videoWidth, height: pfVideo.videoHeight };
                        faceapi.matchDimensions(pfOverlay, displaySize);

                        const options = new faceapi.TinyFaceDetectorOptions({
                            inputSize: 224,
                            scoreThreshold: 0.5,
                        });

                        const detection = await faceapi
                            .detectSingleFace(pfVideo, options)
                            .withFaceLandmarks()
                            .withFaceDescriptor();

                        const ctx = pfOverlay.getContext('2d');
                        ctx.clearRect(0, 0, pfOverlay.width, pfOverlay.height);

                        if (!detection) {
                            setPfStatus('No face detected', '#F97316');
                            pfMessage.textContent = 'Face not detected. Please ensure your face is clearly visible and try again.';
                            return;
                        }

                        const resizedDetections = faceapi.resizeResults(detection, displaySize);
                        faceapi.draw.drawDetections(pfOverlay, resizedDetections);
                        faceapi.draw.drawFaceLandmarks(pfOverlay, resizedDetections);

                        const descriptorArray = Array.from(detection.descriptor);

                        setPfStatus('Verifying...', '#3B82F6');
                        pfMessage.textContent = 'Verifying your face...';

                        const res = await fetch("{{ route('profile.face-verify') }}", {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json',
                            },
                            body: JSON.stringify({ descriptor: descriptorArray })
                        });

                        const data = await res.json();

                        if (!res.ok || !data.ok) {
                            setPfStatus('Verification failed', '#EF4444');
                            pfMessage.textContent = data.message || 'Face verification failed.';
                            return;
                        }

                        setPfStatus('Verified', '#10B981');
                        pfMessage.textContent = data.message || 'Face verified.';

                        setTimeout(() => {
                            pfCloseModal();
                            if (pfAction === 'update-profile' && profileFormSection) {
                                profileFormSection.submit();
                            } else if (pfAction === 'update-password' && profilePasswordForm) {
                                profilePasswordForm.submit();
                            } else if (pfAction === 'delete-account' && deleteUserForm) {
                                deleteUserForm.submit();
                            }
                        }, 500);

                    } catch (err) {
                        console.error(err);
                        setPfStatus('Error', '#EF4444');
                        pfMessage.textContent = 'An error occurred while verifying your face.';
                    }
                }

                const profileSaveTrigger = document.getElementById('profile-save-trigger');
                const passwordSaveTrigger = document.getElementById('password-save-trigger');

                if (profileSaveTrigger && profileFormSection) {
                    profileSaveTrigger.addEventListener('click', function () {
                        pfOpenModal('update-profile', 'Kami perlu memverifikasi wajahmu sebelum menyimpan perubahan profil.');
                    });
                }

                if (passwordSaveTrigger && profilePasswordForm) {
                    passwordSaveTrigger.addEventListener('click', function () {
                        pfOpenModal('update-password', 'Kami perlu memverifikasi wajahmu sebelum mengubah password akun.');
                    });
                }

                if (pfStartBtn)  pfStartBtn.addEventListener('click', pfStartCamera);
                if (pfVerifyBtn) pfVerifyBtn.addEventListener('click', pfCaptureAndVerify);
                if (pfCloseBtn)  pfCloseBtn.addEventListener('click', pfCloseModal);
                if (pfCancelBtn) pfCancelBtn.addEventListener('click', pfCloseModal);

                window.addEventListener('beforeunload', () => {
                    stopCamera();
                    pfStopCamera();
                });
            });
        </script>
    @endpush
</x-app-layout>