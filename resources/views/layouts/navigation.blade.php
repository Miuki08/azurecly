@php
    $user = Auth::user();

    // Dashboard aktif untuk semua route dashboard
    $isDashboard = request()->routeIs('dashboard')
        || request()->routeIs('dashboard.admin')
        || request()->routeIs('dashboard.humas')
        || request()->routeIs('dashboard.media');
@endphp

<nav x-data="{ open: false }" class="bg-white border-b border-gray-200 shadow-sm">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="flex items-center">
                        <div class="h-14 w-24 overflow-hidden rounded-md">
                            <img
                                src="{{ asset('images/azurecly-logo.png') }}"
                                alt="Azurecly"
                                class="w-full h-full object-cover object-center"
                                style="object-position: 50% 50%;"
                            >
                        </div>
                    </a>
                </div>

                <!-- Navigation Links (Desktop) -->
                <div class="hidden space-x-6 sm:-my-px sm:ms-10 sm:flex sm:items-center">
                    {{-- Dashboard (selalu ada) --}}
                    <x-nav-link :href="route('dashboard')" :active="$isDashboard" class="flex items-center">
                        <i data-lucide="layout-dashboard" class="w-4 h-4 mr-1.5"></i>
                        {{ __('Dashboard') }}
                    </x-nav-link>

                    {{-- News hanya admin & humas --}}
                    @if(in_array($user->role, ['admin', 'humas']))
                        <x-nav-link :href="route('tickets.index')" :active="request()->routeIs('tickets.*')" class="flex items-center">
                            <i data-lucide="newspaper" class="w-4 h-4 mr-1.5"></i>
                            {{ __('News') }}
                        </x-nav-link>
                    @endif

                    {{-- Contact hanya admin & humas (kalau memang begitu kebijakannya) --}}
                    @if(in_array($user->role, ['admin', 'humas']))
                        <x-nav-link :href="route('contacts.index')" :active="request()->routeIs('contacts.*')" class="flex items-center">
                            <i data-lucide="contact-2" class="w-4 h-4 mr-1.5"></i>
                            {{ __('Contact') }}
                        </x-nav-link>
                    @endif
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-lg text-gray-600 bg-white hover:text-sea-blue-600 hover:bg-sea-blue-50 focus:outline-none transition duration-150 ease-in-out">
                            <div class="flex items-center space-x-2">
                                <div class="w-8 h-8 rounded-full bg-sea-blue-100 flex items-center justify-center">
                                    <i data-lucide="user" class="w-4 h-4 text-sea-blue-600"></i>
                                </div>
                                <span>{{ $user->name }}</span>
                            </div>
                            <div class="ms-2">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')" class="flex items-center">
                            <i data-lucide="user-circle" class="w-4 h-4 mr-2"></i>
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')" class="flex items-center text-red-600 hover:text-red-700"
                                             onclick="event.preventDefault(); this.closest('form').submit();">
                                <i data-lucide="log-out" class="w-4 h-4 mr-2"></i>
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-sea-blue-600 hover:bg-sea-blue-50 focus:outline-none focus:bg-sea-blue-50 focus:text-sea-blue-600 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex"
                              stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden"
                              stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            {{-- Dashboard --}}
            <x-responsive-nav-link :href="route('dashboard')" :active="$isDashboard" class="flex items-center">
                <i data-lucide="layout-dashboard" class="w-4 h-4 mr-2"></i>
                {{ __('Dashboard') }}
            </x-responsive-nav-link>

            {{-- News hanya admin & humas --}}
            @if(in_array($user->role, ['admin', 'humas']))
                <x-responsive-nav-link :href="route('tickets.index')" :active="request()->routeIs('tickets.*')" class="flex items-center">
                    <i data-lucide="newspaper" class="w-4 h-4 mr-2"></i>
                    {{ __('News') }}
                </x-responsive-nav-link>
            @endif

            {{-- Contact hanya admin & humas --}}
            @if(in_array($user->role, ['admin', 'humas']))
                <x-responsive-nav-link :href="route('contacts.index')" :active="request()->routeIs('contacts.*')" class="flex items-center">
                    <i data-lucide="contact-2" class="w-4 h-4 mr-2"></i>
                    {{ __('Contact') }}
                </x-responsive-nav-link>
            @endif
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 rounded-full bg-sea-blue-100 flex items-center justify-center">
                        <i data-lucide="user" class="w-5 h-5 text-sea-blue-600"></i>
                    </div>
                    <div>
                        <div class="font-medium text-base text-gray-800">{{ $user->name }}</div>
                        <div class="font-medium text-sm text-gray-500">{{ $user->email }}</div>
                    </div>
                </div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')" class="flex items-center">
                    <i data-lucide="user-circle" class="w-4 h-4 mr-2"></i>
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')" class="flex items-center text-red-600 hover:text-red-700"
                                            onclick="event.preventDefault(); this.closest('form').submit();">
                        <i data-lucide="log-out" class="w-4 h-4 mr-2"></i>
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>

{{-- Script Lucide Icons --}}
@push('scripts')
<script src="https://unpkg.com/lucide@latest"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    });
</script>
@endpush