<div class="space-y-4">
    {{-- Header --}}
    <div class="flex items-start gap-3">
        <div class="p-2 bg-red-100 rounded-lg flex-shrink-0">
            <i data-lucide="lock" class="w-5 h-5 text-red-600"></i>
        </div>
        <div class="flex-1">
            <h4 class="text-sm font-semibold text-gray-900">
                {{ __('Update Password') }}
            </h4>
            <p class="text-xs text-gray-500 mt-0.5">
                {{ __('Ensure your account is using a long, random password to stay secure.') }}
            </p>
        </div>
    </div>

    {{-- Form --}}
    <form method="post" action="{{ route('password.update') }}" class="mt-4 space-y-4">
        @csrf
        @method('put')

        {{-- Current Password --}}
        <div>
            <label for="update_password_current_password" class="block text-xs font-medium text-gray-700 mb-1">
                {{ __('Current Password') }}
            </label>
            <input type="password" 
                   id="update_password_current_password" 
                   name="current_password" 
                   class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-sea-blue-500 focus:border-transparent @error('current_password') border-red-500 @enderror"
                   autocomplete="current-password">
            @error('current_password')
                <p class="mt-1 text-[10px] text-red-600">{{ $message }}</p>
            @enderror
        </div>

        {{-- New Password --}}
        <div>
            <label for="update_password_password" class="block text-xs font-medium text-gray-700 mb-1">
                {{ __('New Password') }}
            </label>
            <input type="password" 
                   id="update_password_password" 
                   name="password" 
                   class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-sea-blue-500 focus:border-transparent @error('password') border-red-500 @enderror"
                   autocomplete="new-password">
            @error('password')
                <p class="mt-1 text-[10px] text-red-600">{{ $message }}</p>
            @enderror
        </div>

        {{-- Confirm Password --}}
        <div>
            <label for="update_password_password_confirmation" class="block text-xs font-medium text-gray-700 mb-1">
                {{ __('Confirm Password') }}
            </label>
            <input type="password" 
                   id="update_password_password_confirmation" 
                   name="password_confirmation" 
                   class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-sea-blue-500 focus:border-transparent"
                   autocomplete="new-password">
        </div>

        {{-- Submit --}}
        <div class="flex items-center gap-3 pt-2">
            <button type="submit" 
                    class="px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-lg hover:bg-red-700 transition focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                {{ __('Save') }}
            </button>
            
            @if (session('status') === 'password-updated')
                <span class="text-xs text-emerald-600 font-medium inline-flex items-center gap-1.5">
                    <i data-lucide="check-circle" class="w-3.5 h-3.5"></i>
                    {{ __('Saved.') }}
                </span>
            @endif
        </div>
    </form>
</div>