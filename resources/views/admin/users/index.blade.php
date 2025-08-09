<x-admin-layout>

    {{-- Header --}}
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight">
            {{ __('Pengguna') }}
        </h2>
    </x-slot>

    <section class="px-6">
        {{-- Flash Messages --}}
        @if(session('success'))
            <div class="mb-4 px-4 py-3 text-green-700 bg-green-100 border border-green-200 rounded">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mb-4 px-4 py-3 text-red-700 bg-red-100 border border-red-200 rounded">
                {{ session('error') }}
            </div>
        @endif

        <div class="overflow-hidden border border-gray-100 rounded-md">
            <table class="min-w-full">
                <thead class="rounded-lg" style="background-color:#FC9B5C; ">
                    <tr>
                        <x-table.head>Nama</x-table.head>
                        <x-table.head>Email</x-table.head>
                        <x-table.head class="text-center">Role</x-table.head>
                        <x-table.head class="text-center">Tanggal Bergabung</x-table.head>
                        <x-table.head class="text-center">Aksi</x-table.head>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-200 divide-solid">
                    @foreach ($users as $user)
                    <tr>
                            <x-table.data>
                                <div class="flex items-center">
                                    <img class="w-8 h-8 rounded-full mr-3" src="{{ $user->profile_photo_url }}" alt="{{ $user->name }}">
                                    <div class="overflow-hidden">{{ $user->name }}</div>
                                </div>
                            </x-table.data>
                            <x-table.data>
                                <div class="overflow-hidden">{{ $user->email }}</div>
                            </x-table.data>
                            <x-table.data class="text-center">
                                <div class="overflow-hidden">
                                    @php
                                        $i = $user->type;
                                    @endphp
                                    @switch($i)
                                        @case(1)
                                            <span class="px-2 py-1 text-xs bg-blue-100 text-blue-800 rounded-full">User</span>
                                            @break
                                        @case(3)
                                            <span class="px-2 py-1 text-xs bg-red-100 text-red-800 rounded-full">Admin</span>
                                        @break
                                        @default
                                            <span class="px-2 py-1 text-xs bg-gray-100 text-gray-800 rounded-full">Guest</span>
                                    @endswitch
                                </div>
                            </x-table.data>
                            <x-table.data class="text-center">
                                <div class="overflow-hidden">{{ $user->created_at->format('d M Y') }}</div>
                            </x-table.data>
                            <x-table.data class="text-center">
                                <div class="flex items-center justify-center space-x-2">
                                    <a href="{{ route('profile.show', $user) }}" class="text-blue-600 hover:text-blue-900 text-sm">Lihat</a>
                                    @if($user->type !== 3)
                                    <button onclick="confirmAction('promote', {{ $user->id }})" class="text-green-600 hover:text-green-900 text-sm">Jadikan Admin</button>
                                    @else
                                    <button onclick="confirmAction('demote', {{ $user->id }})" class="text-orange-600 hover:text-orange-900 text-sm">Hapus Admin</button>
                                    @endif
                                    <button onclick="confirmAction('delete', {{ $user->id }})" class="text-red-600 hover:text-red-900 text-sm">Hapus</button>
                                </div>
                            </x-table.data>
                    </tr>
                    @endforeach
                </tbody>

            </table>
        </div>

        {{-- Pagination --}}
        <div class="mt-4">
            {{ $users->links() }}
        </div>
    </section>

    {{-- JavaScript for actions --}}
    <script>
        function confirmAction(action, userId) {
            let message = '';
            let url = '';

            switch(action) {
                case 'promote':
                    message = 'Apakah Anda yakin ingin menjadikan user ini sebagai admin?';
                    url = `/admin/users/${userId}/promote`;
                    break;
                case 'demote':
                    message = 'Apakah Anda yakin ingin menghapus status admin dari user ini?';
                    url = `/admin/users/${userId}/demote`;
                    break;
                case 'delete':
                    message = 'Apakah Anda yakin ingin menghapus user ini? Tindakan ini tidak dapat dibatalkan.';
                    url = `/admin/users/${userId}/delete`;
                    break;
            }

            if(confirm(message)) {
                window.location.href = url;
            }
        }
    </script>
</x-admin-layout>
