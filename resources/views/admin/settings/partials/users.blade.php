<div
    x-data="{ openCreateUser: false }"
    x-cloak
>
    <div class="flex items-center justify-between mb-4">
        <div>
            <h3 class="text-lg font-semibold text-gray-900">Users</h3>
            <p class="text-xs text-gray-500">
                Register akun baru dan lihat pengguna pada site ini.
            </p>
        </div>

        <button
            type="button"
            @click="openCreateUser = true"
            class="inline-flex items-center gap-1.5 bg-sea-blue-600 hover:bg-sea-blue-700 text-white px-3 py-1.5 rounded-lg text-xs font-medium transition"
        >
            <i data-lucide="user-plus" class="w-4 h-4"></i>
            Add User
        </button>
    </div>

    <div class="bg-white border border-gray-100 rounded-xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-100 text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-2 text-left text-[11px] font-semibold text-gray-500 uppercase tracking-wide">
                            Name
                        </th>
                        <th class="px-4 py-2 text-left text-[11px] font-semibold text-gray-500 uppercase tracking-wide">
                            Email
                        </th>
                        <th class="px-4 py-2 text-left text-[11px] font-semibold text-gray-500 uppercase tracking-wide">
                            Role
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    @forelse ($users as $user)
                        <tr class="hover:bg-sea-blue-50/40 transition-colors duration-150">
                            <td class="px-4 py-2 text-sm text-gray-900">
                                {{ $user->name }}
                            </td>
                            <td class="px-4 py-2 text-sm text-gray-600">
                                {{ $user->email }}
                            </td>
                            <td class="px-4 py-2">
                                <span class="inline-flex items-center px-2.5 py-0.5 text-[11px] font-medium rounded-full
                                    {{ $user->role === 'admin'
                                        ? 'bg-purple-50 text-purple-700 ring-1 ring-purple-100'
                                        : ($user->role === 'humas'
                                            ? 'bg-blue-50 text-blue-700 ring-1 ring-blue-100'
                                            : 'bg-emerald-50 text-emerald-700 ring-1 ring-emerald-100') }}">
                                    {{ ucfirst($user->role) }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-4 py-8 text-center text-sm text-gray-500">
                                <i data-lucide="inbox" class="w-8 h-8 mx-auto text-gray-300 mb-2"></i>
                                Belum ada user lain di site ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Modal create user --}}
    <div
        x-show="openCreateUser"
        class="fixed inset-0 z-40 flex items-center justify-center bg-black/40"
        @keydown.escape.window="openCreateUser = false"
    >
        <div
            x-show="openCreateUser"
            x-transition
            class="bg-white rounded-xl shadow-lg w-full max-w-lg mx-4"
        >
            <div class="px-4 py-3 border-b border-gray-100 flex items-center justify-between">
                <h3 class="text-sm font-semibold text-gray-900">
                    Register New User
                </h3>
                <button
                    type="button"
                    class="text-gray-400 hover:text-gray-600"
                    @click="openCreateUser = false"
                >
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
            </div>

            <form
                action="{{ route('admin.settings.users.store') }}"
                method="POST"
                class="px-4 py-4 space-y-4"
            >
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-medium text-gray-700">Name</label>
                        <input
                            type="text"
                            name="name"
                            class="mt-1 block w-full rounded-lg border-gray-200 focus:border-sea-blue-500 focus:ring-sea-blue-500 text-sm"
                            required
                        >
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700">Email</label>
                        <input
                            type="email"
                            name="email"
                            class="mt-1 block w-full rounded-lg border-gray-200 focus:border-sea-blue-500 focus:ring-sea-blue-500 text-sm"
                            required
                        >
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-medium text-gray-700">Role</label>
                        <select
                            name="role"
                            class="mt-1 block w-full rounded-lg border-gray-200 focus:border-sea-blue-500 focus:ring-sea-blue-500 text-sm"
                            required
                        >
                            <option value="humas">Humas</option>
                            <option value="media">Media</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-medium text-gray-700">Password</label>
                        <input
                            type="password"
                            name="password"
                            class="mt-1 block w-full rounded-lg border-gray-200 focus:border-sea-blue-500 focus:ring-sea-blue-500 text-sm"
                            required
                        >
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700">Confirm Password</label>
                        <input
                            type="password"
                            name="password_confirmation"
                            class="mt-1 block w-full rounded-lg border-gray-200 focus:border-sea-blue-500 focus:ring-sea-blue-500 text-sm"
                            required
                        >
                    </div>
                </div>

                <div class="pt-2 flex justify-end gap-2">
                    <button
                        type="button"
                        class="inline-flex items-center px-3 py-1.5 text-xs rounded-lg border border-gray-200 text-gray-700 hover:bg-gray-50"
                        @click="openCreateUser = false"
                    >
                        Cancel
                    </button>
                    <button
                        type="submit"
                        class="inline-flex items-center px-4 py-1.5 text-xs font-medium rounded-lg text-white bg-sea-blue-600 hover:bg-sea-blue-700"
                    >
                        Save
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
