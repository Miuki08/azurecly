<x-guest-layout>
    <x-auth-session-status class="mb-4" :status="session('status')" />

    {{-- Logo --}}
    <div class="flex justify-center mb-4"> {{-- Margin bottom dikurangi --}}
        <div class="w-56 h-24 overflow-hidden rounded-lg shadow-sm"> {{-- Container 192x80 --}}
            <img 
                src="{{ asset('images/azurecly-logo.png') }}" 
                alt="Azurecly" 
                class="w-full h-full object-cover object-center"
                style="object-position: 50% 50%;"
            >
        </div>
    </div>

    {{-- From --}}
    <form method="POST" action="{{ route('login') }}" class="space-y-5"> {{-- Kurangi space-y --}}
        @csrf

        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <i data-lucide="mail" class="h-5 w-5 text-sea-blue-500"></i>
            </div>
            <input 
                type="email" 
                name="email" 
                id="email" 
                value="{{ old('email') }}"
                placeholder="Email address"
                required 
                autofocus 
                autocomplete="username"
                class="block w-full pl-10 pr-3 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-sea-blue-500 focus:border-transparent transition duration-150 ease-in-out @error('email') border-red-500 @enderror"
            >
            @error('email')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <i data-lucide="lock" class="h-5 w-5 text-sea-blue-500"></i>
            </div>
            <input 
                type="password" 
                name="password" 
                id="password" 
                placeholder="Password"
                required 
                autocomplete="current-password"
                class="block w-full pl-10 pr-3 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-sea-blue-500 focus:border-transparent transition duration-150 ease-in-out @error('password') border-red-500 @enderror"
            >
            @error('password')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="text-left -mt-2">
            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="text-sm text-sea-blue-600 hover:text-sea-blue-800 transition duration-150 ease-in-out">
                    Forgot your password?
                </a>
            @endif
        </div>

        <div class="flex justify-center my-3">
            <div class="g-recaptcha" data-sitekey="{{ config('recaptcha.site_key') }}"></div>
            @error('g-recaptcha-response')
                <p class="text-red-500 text-xs mt-1 text-center">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <button type="submit" class="group relative w-full flex justify-center items-center py-2.5 px-4 border border-transparent rounded-lg text-white bg-sea-blue-600 hover:bg-sea-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-sea-blue-500 transition duration-150 ease-in-out">
                <span class="flex items-center">
                    Sign in
                    <i data-lucide="arrow-right" class="ml-2 h-5 w-5 group-hover:translate-x-1 transition-transform duration-150"></i>
                </span>
            </button>
        </div>
    </form>

    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <script>
        lucide.createIcons();
    </script>
</x-guest-layout>