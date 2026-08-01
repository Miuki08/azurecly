<div class="space-y-4">
    {{-- Header --}}
    <div class="flex items-start gap-3">
        <div class="p-2 bg-red-100 rounded-lg flex-shrink-0">
            <i data-lucide="trash-2" class="w-5 h-5 text-red-600"></i>
        </div>
        <div class="flex-1">
            <h4 class="text-sm font-semibold text-gray-900">
                {{ __('Delete Account') }}
            </h4>
            <p class="text-xs text-gray-500 mt-0.5">
                {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.') }}
            </p>
        </div>
    </div>

    {{-- Delete Button --}}
    <div class="mt-4">
        <button type="button"
                onclick="document.getElementById('confirm-user-deletion-modal').classList.remove('hidden')"
                class="inline-flex items-center gap-2 px-4 py-2.5 bg-red-600 text-white text-sm font-medium rounded-lg hover:bg-red-700 transition focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
            <i data-lucide="trash-2" class="w-4 h-4"></i>
            <span>{{ __('Delete Account') }}</span>
        </button>
    </div>

    {{-- Confirmation Modal --}}
    <div id="confirm-user-deletion-modal"
         class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 items-center justify-center hidden">
        <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full mx-4 overflow-hidden">
            {{-- Modal Header --}}
            <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-red-50 to-white">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-red-100 rounded-lg">
                        <i data-lucide="alert-triangle" class="w-5 h-5 text-red-600"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-semibold text-gray-900">
                            {{ __('Are you sure?') }}
                        </h3>
                        <p class="text-[10px] text-gray-500">
                            This action cannot be undone
                        </p>
                    </div>
                </div>
            </div>

            {{-- Modal Content --}}
            <div class="px-6 py-5 space-y-4">
                <p class="text-sm text-gray-600">
                    {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.') }}
                </p>

                <form method="post" id="delete-account-form" action="{{ route('profile.destroy') }}" class="space-y-4">
                    @csrf
                    @method('delete')

                    <div>
                        <label for="password" class="block text-xs font-medium text-gray-700 mb-1">
                            {{ __('Password') }}
                        </label>
                        <input type="password" 
                               id="password" 
                               name="password" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent @error('password') border-red-500 @enderror"
                               placeholder="{{ __('Enter your password') }}">
                        @error('password')
                            <p class="mt-1 text-[10px] text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- Modal Footer --}}
            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50 flex items-center justify-end gap-3">
                <button type="button"
                        onclick="document.getElementById('confirm-user-deletion-modal').classList.add('hidden')"
                        class="inline-flex items-center gap-2 px-4 py-2 rounded-lg border border-gray-300 text-xs font-medium text-gray-600 hover:bg-white transition focus:outline-none focus:ring-2 focus:ring-gray-300">
                    <i data-lucide="x" class="w-4 h-4"></i>
                    <span>{{ __('Cancel') }}</span>
                </button>

                <button type="submit"
                        form="delete-account-form"
                        class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-red-600 text-white text-xs font-medium hover:bg-red-700 transition focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                    <span>{{ __('Delete Account') }}</span>
                </button>
            </div>
        </div>
    </div>
</div>