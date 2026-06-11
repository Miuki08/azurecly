<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-sea-blue-800 leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            {{-- Profile information card --}}
            <div class="bg-white border border-gray-100 rounded-xl shadow-sm">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-sea-blue-100 rounded-lg">
                            <i data-lucide="user-circle-2" class="w-5 h-5 text-sea-blue-600"></i>
                        </div>
                        <div>
                            <h3 class="text-sm font-semibold text-gray-900">
                                {{ __('Profile Information') }}
                            </h3>
                            <p class="text-xs text-gray-500">
                                {{ __("Update your account's profile information and email address.") }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="px-6 py-5">
                    <form method="post" action="{{ route('profile.update') }}" class="space-y-5">
                        @csrf
                        @method('patch')

                        <div>
                            <x-input-label for="name" :value="__('Name')" />
                            <x-text-input id="name" name="name" type="text"
                                class="mt-1 block w-full"
                                :value="old('name', $user->name)" required autofocus autocomplete="name" />
                            <x-input-error class="mt-2" :messages="$errors->get('name')" />
                        </div>

                        <div>
                            <x-input-label for="email" :value="__('Email')" />
                            <x-text-input id="email" name="email" type="email"
                                class="mt-1 block w-full"
                                :value="old('email', $user->email)" required autocomplete="username" />
                            <x-input-error class="mt-2" :messages="$errors->get('email')" />

                            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                                <div class="mt-3 rounded-lg bg-amber-50 border border-amber-100 px-3 py-2">
                                    <p class="text-xs text-amber-800">
                                        {{ __('Your email address is unverified.') }}

                                        <button form="send-verification"
                                            class="underline text-xs text-amber-700 hover:text-amber-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500">
                                            {{ __('Click here to re-send the verification email.') }}
                                        </button>
                                    </p>

                                    @if (session('status') === 'verification-link-sent')
                                        <p class="mt-1 font-medium text-xs text-emerald-600">
                                            {{ __('A new verification link has been sent to your email address.') }}
                                        </p>
                                    @endif
                                </div>
                            @endif
                        </div>

                        <div class="flex items-center gap-4">
                            <x-primary-button>{{ __('Save') }}</x-primary-button>

                            @if (session('status') === 'profile-updated')
                                <p x-data="{ show: true }"
                                   x-show="show"
                                   x-transition
                                   x-init="setTimeout(() => show = false, 2000)"
                                   class="text-sm text-gray-600">
                                    {{ __('Saved.') }}
                                </p>
                            @endif
                        </div>
                    </form>

                    <form id="send-verification" method="post" action="{{ route('verification.send') }}" class="hidden">
                        @csrf
                    </form>
                </div>
            </div>

            {{-- Face verification card --}}
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
                                Daftarkan wajahmu untuk memverifikasi identitas saat menghapus eskalasi dan aksi sensitif lainnya.
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
                        Wajah diproses secara lokal di browser menggunakan face-api.js. Azurecly hanya menyimpan deskriptor numerik (bukan foto mentah) di akunmu.
                    </p>

                    {{-- Video preview --}}
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
                                    <li>Klik "Capture & Save" untuk menyimpan deskriptor wajah.</li>
                                </ol>
                            </div>

                            <div class="mt-3 flex flex-wrap items-center gap-2">
                                <button type="button"
                                        id="face-start-camera"
                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-gray-300 text-xs text-gray-700 hover:bg-gray-50 transition">
                                    <i data-lucide="video" class="w-4 h-4"></i>
                                    <span>Start Camera</span>
                                </button>

                                <button type="button"
                                        id="face-capture-save"
                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-sea-blue-600 text-white text-xs font-medium hover:bg-sea-blue-700 transition disabled:opacity-40 disabled:cursor-not-allowed"
                                        disabled>
                                    <i data-lucide="save" class="w-4 h-4"></i>
                                    <span>Capture & Save</span>
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

        </div>
    </div>

    @push('scripts')
        {{-- Lucide untuk ikon --}}
        <script src="https://unpkg.com/lucide@latest"></script>

        {{-- TensorFlow.js & face-api.js --}}
        <!-- <script defer src="https://cdn.jsdelivr.net/npm/@tensorflow/tfjs@4.21.0/dist/tf.min.js"></script> -->
        <script defer src="https://cdn.jsdelivr.net/npm/face-api.js@0.22.2/dist/face-api.min.js"></script>

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                if (typeof lucide !== 'undefined') {
                    lucide.createIcons();
                }

                const video = document.getElementById('face-video');
                const overlay = document.getElementById('face-overlay');
                const startBtn = document.getElementById('face-start-camera');
                const captureBtn = document.getElementById('face-capture-save');
                const stopBtn = document.getElementById('face-stop-camera');
                const messageEl = document.getElementById('face-message');
                const statusBadge = document.getElementById('face-status-badge');

                let stream = null;
                let modelsLoaded = false;

                function setStatus(text, colorDot = '#9CA3AF') {
                    if (!statusBadge) return;
                    statusBadge.querySelector('span.w-1\\.5').style.backgroundColor = colorDot;
                    statusBadge.querySelector('span:nth-child(2)').textContent = text;
                }

                async function loadModels() {
                    if (modelsLoaded) return;
                    setStatus('Loading models...', '#F59E0B');
                    messageEl.textContent = 'Loading face recognition models, please wait...';

                    const MODEL_URL = '/models/face-api'; // SESUAIKAN: letakkan model di public/models/face-api [web:114][web:116]

                    await Promise.all([
                        faceapi.nets.tinyFaceDetector.loadFromUri(MODEL_URL),
                        faceapi.nets.faceLandmark68Net.loadFromUri(MODEL_URL),
                        faceapi.nets.faceRecognitionNet.loadFromUri(MODEL_URL),
                    ]);

                    modelsLoaded = true;
                    setStatus('Models loaded', '#10B981');
                    messageEl.textContent = 'Models loaded. You can start the camera and capture your face.';
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
                    messageEl.textContent = 'Camera stopped.';
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

                        // const detection = await faceapi
                        //     .detectSingleFace(video, new faceapi.TinyFaceDetectorOptions())
                        //     .withFaceLandmarks()
                        //     .withFaceDescriptor();

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

                    } catch (err) {
                        console.error(err);
                        setStatus('Error', '#EF4444');
                        messageEl.textContent = 'An error occurred while capturing or saving your face descriptor.';
                    }
                }

                if (startBtn) startBtn.addEventListener('click', startCamera);
                if (stopBtn) stopBtn.addEventListener('click', stopCamera);
                if (captureBtn) captureBtn.addEventListener('click', captureAndSave);

                window.addEventListener('beforeunload', stopCamera);
            });
        </script>
    @endpush
</x-app-layout>