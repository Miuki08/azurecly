<x-app-layout>
    {{-- Header Section with Gradient --}}
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <div class="p-2 bg-gradient-to-br from-sea-blue-500 to-sea-blue-600 rounded-xl shadow-lg">
                <i data-lucide="user-circle-2" class="w-6 h-6 text-white"></i>
            </div>
            <div>
                <h2 class="font-bold text-xl text-gray-900 leading-tight">
                    {{ __('Profile') }}
                </h2>
                <p class="text-xs text-gray-500 mt-0.5">
                    Manage your account settings and preferences
                </p>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                
                {{-- Profile Overview --}}
                <div class="md:col-span-2 bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition-shadow duration-300">
                    {{-- Card Header --}}
                    <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="p-2 bg-sea-blue-100 rounded-lg">
                                    <i data-lucide="user" class="w-5 h-5 text-sea-blue-600"></i>
                                </div>
                                <div>
                                    <h3 class="text-sm font-semibold text-gray-900">
                                        {{ __('Profile Information') }}
                                    </h3>
                                    <p class="text-xs text-gray-500">
                                        Update your account's profile information
                                    </p>
                                </div>
                            </div>
                            @if($user->last_profile_update_at)
                                <span class="text-[10px] text-gray-400">
                                    Updated {{ $user->last_profile_update_at->diffForHumans() }}
                                </span>
                            @endif
                        </div>
                    </div>

                    {{-- Card Content --}}
                    <div class="px-6 py-6">
                        <div class="flex items-start gap-6">
                            {{-- Avatar Section --}}
                            <div class="flex-shrink-0">
                                <div class="relative group">
                                    {{-- Avatar Display --}}
                                    @if($user->avatar_path)
                                        <img id="avatar-preview" 
                                            src="{{ asset('storage/' . $user->avatar_path) }}" 
                                            alt="Avatar"
                                            class="w-24 h-24 rounded-full object-cover shadow-lg border-4 border-white">
                                    @else
                                        <div id="avatar-placeholder" 
                                            class="w-24 h-24 rounded-full bg-gradient-to-br from-sea-blue-400 to-sea-blue-600 flex items-center justify-center text-white text-3xl font-bold shadow-lg">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </div>
                                    @endif
                                    
                                    {{-- Upload Button --}}
                                    <button type="button" 
                                            onclick="document.getElementById('avatar-upload').click()"
                                            class="absolute bottom-0 right-0 p-2 bg-sea-blue-600 rounded-full shadow-lg hover:bg-sea-blue-700 transition transform group-hover:scale-110">
                                        <i data-lucide="camera" class="w-4 h-4 text-white"></i>
                                    </button>
                                    
                                    {{-- Hidden Form for Avatar Upload --}}
                                    <form id="avatar-form" method="POST" action="{{ route('profile.update-avatar') }}" enctype="multipart/form-data" class="hidden">
                                        @csrf
                                        @method('POST')
                                        <input type="file" 
                                            id="avatar-upload" 
                                            name="avatar" 
                                            accept="image/*" 
                                            class="hidden"
                                            onchange="previewAndUploadAvatar(this)">
                                    </form>

                                    {{-- Loading Overlay --}}
                                    <div id="avatar-loading" 
                                        class="absolute inset-0 w-24 h-24 rounded-full bg-black/50 flex items-center justify-center hidden">
                                        <i data-lucide="loader-2" class="w-8 h-8 text-white animate-spin"></i>
                                    </div>
                                </div>

                                {{-- Face Status Badge --}}
                                <div class="mt-3 flex justify-center">
                                    @if($hasFace)
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-emerald-50 border border-emerald-200 text-[10px] font-medium text-emerald-700">
                                            <i data-lucide="shield-check" class="w-3 h-3"></i>
                                            Verified
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-amber-50 border border-amber-200 text-[10px] font-medium text-amber-700">
                                            <i data-lucide="alert-circle" class="w-3 h-3"></i>
                                            Not Verified
                                        </span>
                                    @endif
                                </div>

                                {{-- Success Message --}}
                                <div id="avatar-success" 
                                    class="mt-2 text-center text-xs text-emerald-600 font-medium hidden">
                                    <i data-lucide="check-circle" class="w-3.5 h-3.5 inline mr-1"></i>
                                    Avatar updated!
                                </div>
                            </div>

                            {{-- Profile Form --}}
                            <div class="flex-1 space-y-4">
                                <form id="profile-form" method="POST" action="{{ route('profile.update') }}" class="space-y-4">
                                    @csrf
                                    @method('patch')

                                    {{-- Name --}}
                                    <div>
                                        <label for="name" class="block text-xs font-medium text-gray-700 mb-1">
                                            Name
                                        </label>
                                        <input type="text" 
                                               name="name" 
                                               id="name" 
                                               value="{{ $user->name }}"
                                               class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-sea-blue-500 focus:border-transparent @error('name') border-red-500 @enderror">
                                        @error('name')
                                            <p class="mt-1 text-[10px] text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    {{-- Email --}}
                                    <div>
                                        <label for="email" class="block text-xs font-medium text-gray-700 mb-1">
                                            Email
                                        </label>
                                        <input type="email" 
                                               name="email" 
                                               id="email" 
                                               value="{{ $user->email }}"
                                               class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-sea-blue-500 focus:border-transparent @error('email') border-red-500 @enderror">
                                        @error('email')
                                            <p class="mt-1 text-[10px] text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    {{-- Organization (Read-only) --}}
                                    <div>
                                        <label class="block text-xs font-medium text-gray-700 mb-1">
                                            Organization
                                        </label>
                                        <div class="px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg text-sm text-gray-600">
                                            {{ $user->site?->Name ?? '-' }}
                                        </div>
                                    </div>

                                    {{-- Submit Button --}}
                                    <div class="flex items-center gap-3 pt-2">
                                        <button type="button" 
                                                id="profile-save-trigger"
                                                class="px-4 py-2 bg-sea-blue-600 text-white text-sm font-medium rounded-lg hover:bg-sea-blue-700 transition focus:outline-none focus:ring-2 focus:ring-sea-blue-500 focus:ring-offset-2">
                                            Save Changes
                                        </button>
                                        
                                        @if (session('status') === 'profile-updated')
                                            <span class="text-xs text-emerald-600 font-medium">Saved!</span>
                                        @endif
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Card 2: Activity Stats (Small Card - 1 column) --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition-shadow duration-300">
                    {{-- Card Header --}}
                    <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
                        <div class="flex items-center gap-2">
                            <div class="p-2 bg-indigo-100 rounded-lg">
                                <i data-lucide="activity" class="w-5 h-5 text-indigo-600"></i>
                            </div>
                            <h3 class="text-sm font-semibold text-gray-900">
                                Activity
                            </h3>
                        </div>
                    </div>

                    {{-- Card Content --}}
                    <div class="px-6 py-5 space-y-4">
                        {{-- Last Login --}}
                        <div class="space-y-1.5">
                            <div class="flex items-center gap-2 text-xs text-gray-500">
                                <i data-lucide="log-in" class="w-3.5 h-3.5"></i>
                                <span>Last Login</span>
                            </div>
                            @if($user->last_login_at)
                                <div class="px-3 py-2 bg-gray-50 rounded-lg">
                                    <p class="text-sm font-medium text-gray-900">
                                        {{ $user->last_login_at->format('d M Y, H:i') }}
                                    </p>
                                    <p class="text-[10px] text-gray-500">
                                        {{ $user->last_login_at->diffForHumans() }}
                                    </p>
                                    @if($user->last_login_ip)
                                        <p class="text-[10px] text-gray-400 mt-1">
                                            IP: {{ $user->last_login_ip }}
                                        </p>
                                    @endif
                                </div>
                            @else
                                <p class="text-xs text-gray-400 italic">No data</p>
                            @endif
                        </div>

                        {{-- Account Age --}}
                        <div class="space-y-1.5">
                            <div class="flex items-center gap-2 text-xs text-gray-500">
                                <i data-lucide="calendar" class="w-3.5 h-3.5"></i>
                                <span>Member Since</span>
                            </div>
                            <div class="px-3 py-2 bg-gray-50 rounded-lg">
                                <p class="text-sm font-medium text-gray-900">
                                    {{ $user->created_at->format('d M Y') }}
                                </p>
                                <p class="text-[10px] text-gray-500">
                                    {{ $user->created_at->diffForHumans() }}
                                </p>
                            </div>
                        </div>

                        {{-- Role Badge --}}
                        <div class="space-y-1.5">
                            <div class="flex items-center gap-2 text-xs text-gray-500">
                                <i data-lucide="badge" class="w-3.5 h-3.5"></i>
                                <span>Role</span>
                            </div>
                            <div class="px-3 py-2 bg-gray-50 rounded-lg">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium
                                    @if($user->Admin())
                                        bg-red-100 text-red-800
                                    @elseif($user->Humas())
                                        bg-blue-100 text-blue-800
                                    @elseif($user->Media())
                                        bg-green-100 text-green-800
                                    @endif">
                                    {{ ucfirst($user->role) }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Card 3: Face Verification (Full Width) --}}
                <div class="md:col-span-3 bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition-shadow duration-300">
                    {{-- Card Header --}}
                    <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="p-2 bg-purple-100 rounded-lg">
                                    <i data-lucide="scan-face" class="w-5 h-5 text-purple-600"></i>
                                </div>
                                <div>
                                    <h3 class="text-sm font-semibold text-gray-900">
                                        Face Verification
                                    </h3>
                                    <p class="text-xs text-gray-500">
                                        Secure your account with facial recognition
                                    </p>
                                </div>
                            </div>
                            @if($hasFace)
                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-emerald-50 border border-emerald-200 text-xs font-medium text-emerald-700">
                                    <i data-lucide="shield-check" class="w-4 h-4"></i>
                                    <span>Face Registered</span>
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-amber-50 border border-amber-200 text-xs font-medium text-amber-700">
                                    <i data-lucide="alert-triangle" class="w-4 h-4"></i>
                                    <span>Not Registered</span>
                                </span>
                            @endif
                        </div>
                    </div>

                    {{-- Card Content --}}
                    <div class="px-6 py-6">
                        @if(!$hasFace)
                            {{-- Warning Box --}}
                            <div class="mb-6 rounded-xl bg-amber-50 border border-amber-200 px-4 py-3">
                                <div class="flex items-start gap-3">
                                    <i data-lucide="info" class="w-5 h-5 text-amber-600 flex-shrink-0 mt-0.5"></i>
                                    <div>
                                        <p class="text-sm font-medium text-amber-800">
                                            Face Verification Required
                                        </p>
                                        <p class="text-xs text-amber-700 mt-1">
                                            Register your face to enable profile editing, password changes, and account deletion. This adds an extra layer of security to your account.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endif

                        {{-- Camera Preview & Controls --}}
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            {{-- Left: Camera Preview --}}
                            <div class="space-y-3">
                                <div class="flex items-center justify-between">
                                    <label class="text-xs font-semibold text-gray-700">
                                        Camera Preview
                                    </label>
                                    <span id="face-status-badge"
                                          class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-gray-100 border border-gray-200 text-[10px] font-medium text-gray-600">
                                        <span class="w-2 h-2 rounded-full bg-gray-400"></span>
                                        <span>Idle</span>
                                    </span>
                                </div>
                                
                                <div class="relative rounded-xl overflow-hidden bg-black shadow-lg aspect-video">
                                    <video id="face-video" autoplay muted playsinline class="w-full h-full object-cover"></video>
                                    <canvas id="face-overlay" class="absolute inset-0 w-full h-full"></canvas>
                                    
                                    {{-- Overlay when no camera --}}
                                    <div id="camera-placeholder" 
                                         class="absolute inset-0 flex items-center justify-center bg-gray-900/80">
                                        <div class="text-center">
                                            <i data-lucide="camera-off" class="w-12 h-12 text-gray-500 mx-auto mb-2"></i>
                                            <p class="text-xs text-gray-400">Click "Start Camera" to begin</p>
                                        </div>
                                    </div>
                                </div>

                                <p class="text-[10px] text-gray-500">
                                    💡 <strong>Tip:</strong> Ensure good lighting and position your face in the center of the frame.
                                </p>
                            </div>

                            {{-- Right: Instructions & Controls --}}
                            <div class="space-y-4">
                                {{-- Instructions --}}
                                <div class="px-4 py-3 bg-gray-50 rounded-xl border border-gray-200">
                                    <p class="text-xs font-semibold text-gray-800 mb-2">
                                        How to register your face
                                    </p>
                                    <ol class="list-decimal list-inside text-[10px] text-gray-600 space-y-1.5">
                                        <li>Click "Start Camera" and allow browser access</li>
                                        <li>Position your face in the center of the frame</li>
                                        <li>Wait for face detection (green outline)</li>
                                        <li>Click "Capture & Save" to register</li>
                                    </ol>
                                </div>

                                {{-- Action Buttons --}}
                                <div class="flex flex-wrap gap-2">
                                    <button type="button"
                                            id="face-start-camera"
                                            class="inline-flex items-center gap-2 px-4 py-2.5 rounded-lg border border-gray-300 text-xs font-medium text-gray-700 hover:bg-gray-50 transition focus:outline-none focus:ring-2 focus:ring-sea-blue-500">
                                        <i data-lucide="video" class="w-4 h-4"></i>
                                        <span>{{ $hasFace ? 'Re-capture' : 'Start Camera' }}</span>
                                    </button>

                                    <button type="button"
                                            id="face-capture-save"
                                            class="inline-flex items-center gap-2 px-4 py-2.5 rounded-lg bg-sea-blue-600 text-white text-xs font-medium hover:bg-sea-blue-700 transition disabled:opacity-50 disabled:cursor-not-allowed focus:outline-none focus:ring-2 focus:ring-sea-blue-500 focus:ring-offset-2"
                                            disabled>
                                        <i data-lucide="save" class="w-4 h-4"></i>
                                        <span>Capture & Save</span>
                                    </button>

                                    <button type="button"
                                            id="face-stop-camera"
                                            class="inline-flex items-center gap-2 px-4 py-2.5 rounded-lg border border-gray-200 text-xs font-medium text-gray-500 hover:bg-gray-50 transition focus:outline-none focus:ring-2 focus:ring-gray-300">
                                        <i data-lucide="square" class="w-3.5 h-3.5"></i>
                                        <span>Stop</span>
                                    </button>
                                </div>

                                {{-- Message Area --}}
                                <div id="face-message" class="min-h-[40px] px-3 py-2 rounded-lg bg-gray-50 border border-gray-200">
                                    <p class="text-[10px] text-gray-500">
                                        Ready to capture your face descriptor.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Card 4: Password & Security (Full Width) - Only if hasFace --}}
                @if($hasFace)
                    <div class="md:col-span-3 bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition-shadow duration-300">
                        <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
                            <div class="flex items-center gap-3">
                                <div class="p-2 bg-red-100 rounded-lg">
                                    <i data-lucide="lock" class="w-5 h-5 text-red-600"></i>
                                </div>
                                <h3 class="text-sm font-semibold text-gray-900">
                                    Password & Security
                                </h3>
                            </div>
                        </div>

                        <div class="px-6 py-6 space-y-6">
                            {{-- Update Password Form --}}
                            <section id="profile-password-section">
                                @include('profile.partials.update-password-form')
                            </section>

                            {{-- Delete Account Form --}}
                            <section id="profile-delete-section" class="pt-6 border-t border-gray-100">
                                @include('profile.partials.delete-user-form')
                            </section>
                        </div>
                    </div>
                @endif

            </div> {{-- End Bento Grid --}}

            {{-- Face Verification Modal --}}
            <div id="profile-face-modal"
                 class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 items-center justify-center hidden">
                <div class="bg-white rounded-2xl shadow-2xl max-w-lg w-full mx-4 overflow-hidden">
                    {{-- Modal Header --}}
                    <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="p-2 bg-purple-100 rounded-lg">
                                    <i data-lucide="scan-face" class="w-5 h-5 text-purple-600"></i>
                                </div>
                                <div>
                                    <h3 class="text-sm font-semibold text-gray-900">
                                        Face Verification Required
                                    </h3>
                                    <p class="text-[10px] text-gray-500" id="profile-face-modal-purpose">
                                        Verify your identity to proceed
                                    </p>
                                </div>
                            </div>
                            <button type="button" 
                                    id="profile-face-modal-close"
                                    class="w-8 h-8 inline-flex items-center justify-center rounded-full hover:bg-gray-100 text-gray-400 hover:text-gray-600 transition">
                                <i data-lucide="x" class="w-4 h-4"></i>
                            </button>
                        </div>
                    </div>

                    {{-- Modal Content --}}
                    <div class="px-6 py-5 space-y-4">
                        {{-- Camera Preview --}}
                        <div class="border border-gray-200 rounded-xl overflow-hidden bg-gray-50">
                            <div class="p-4">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-xs font-semibold text-gray-700">
                                        Camera Preview
                                    </span>
                                    <span id="profile-face-status"
                                          class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-gray-100 border border-gray-200 text-[10px] font-medium text-gray-600">
                                        <span class="w-2 h-2 rounded-full bg-gray-400"></span>
                                        <span>Idle</span>
                                    </span>
                                </div>
                                <div class="relative rounded-lg overflow-hidden bg-black aspect-video">
                                    <video id="profile-face-video" autoplay muted playsinline class="w-full h-full object-cover"></video>
                                    <canvas id="profile-face-overlay" class="absolute inset-0 w-full h-full"></canvas>
                                </div>
                                <p class="mt-2 text-[10px] text-gray-500">
                                    Position your face in the center, ensure good lighting, then click "Verify".
                                </p>
                            </div>
                        </div>

                        {{-- Message Area --}}
                        <div id="profile-face-message" class="min-h-[40px] px-3 py-2 rounded-lg bg-gray-50 border border-gray-200">
                            <p class="text-[10px] text-gray-500">
                                Click "Start Camera" to begin verification.
                            </p>
                        </div>
                    </div>

                    {{-- Modal Footer --}}
                    <div class="px-6 py-4 border-t border-gray-100 bg-gray-50 flex items-center justify-between">
                        <button type="button"
                                id="profile-face-cancel"
                                class="inline-flex items-center gap-2 px-4 py-2 rounded-lg border border-gray-300 text-xs font-medium text-gray-600 hover:bg-white transition focus:outline-none focus:ring-2 focus:ring-gray-300">
                            <i data-lucide="x-circle" class="w-4 h-4"></i>
                            <span>Cancel</span>
                        </button>

                        <div class="flex items-center gap-2">
                            <button type="button"
                                    id="profile-face-start"
                                    class="inline-flex items-center gap-2 px-4 py-2 rounded-lg border border-gray-300 text-xs font-medium text-gray-700 hover:bg-white transition focus:outline-none focus:ring-2 focus:ring-sea-blue-500">
                                <i data-lucide="video" class="w-4 h-4"></i>
                                <span>Start Camera</span>
                            </button>
                            <button type="button"
                                    id="profile-face-verify"
                                    class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-sea-blue-600 text-white text-xs font-medium hover:bg-sea-blue-700 disabled:opacity-50 disabled:cursor-not-allowed transition focus:outline-none focus:ring-2 focus:ring-sea-blue-500 focus:ring-offset-2"
                                    disabled>
                                <i data-lucide="shield-check" class="w-4 h-4"></i>
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

            function previewAndUploadAvatar(input) {
                    if (input.files && input.files[0]) {
                        const file = input.files[0];
                        const reader = new FileReader();
                        
                        document.getElementById('avatar-loading').classList.remove('hidden');
                        document.getElementById('avatar-success').classList.add('hidden');
                        
                        reader.onload = function(e) {
                            const placeholder = document.getElementById('avatar-placeholder');
                            const preview = document.getElementById('avatar-preview');
                            
                            if (placeholder) {
                                placeholder.outerHTML = `<img id="avatar-preview" src="${e.target.result}" alt="Avatar" class="w-24 h-24 rounded-full object-cover shadow-lg border-4 border-white">`;
                            } else if (preview) {
                                preview.src = e.target.result;
                            }

                            const formData = new FormData();
                            formData.append('avatar', file);
                            formData.append('_token', '{{ csrf_token() }}');
                            
                            fetch("{{ route('profile.update-avatar') }}", {
                                method: 'POST',
                                body: formData
                            })
                            .then(response => {
                                if (response.ok) {
                                    document.getElementById('avatar-success').classList.remove('hidden');
                                    setTimeout(() => {
                                        document.getElementById('avatar-success').classList.add('hidden');
                                    }, 3000);
                                } else {
                                    alert('Failed to upload avatar. Please try again.');
                                    window.location.reload();
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                alert('An error occurred while uploading.');
                                window.location.reload();
                            })
                            .finally(() => {
                                document.getElementById('avatar-loading').classList.add('hidden');
                            });
                        };
                        
                        reader.readAsDataURL(file);
                    }
                }
        </script>
    @endpush
</x-app-layout>