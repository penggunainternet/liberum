<x-guest-layout>
    @push('styles')
        <style>
            .post-card {
                transition: all 0.2s ease-in-out;
            }

            .post-card:hover {
                transform: translateY(-1px);
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            }
        </style>
    @endpush

    <main class="grid grid-cols-4 gap-6 mt-8 wrapper">

        <aside class="flex flex-col items-center h-full p-4 space-y-4  rounded-lg">

            <div class="shadow-lg rounded-2xl w-72 bg-white dark:bg-gray-800">
                <x-logos.bg class="rounded-t-lg h-28 w-full mb-4 "/>
                <div class="flex flex-col items-center justify-center p-4 -mt-16">
                    <a href="#" class="block relative">

                        <a href="{{ route('profile', $user) }}" class="flex flex-col items-center text-sm transition border-2 border-transparent rounded-full focus:outline-none focus:border-gray-300">
                            <img class="object-cover w-16 h-16 rounded-full" src="{{ $user->profile_photo_url }}" alt="{{ $user->name() }}" />
                            <span class="flex text-gray-800 dark:text-white text-xl font-medium mt-2">{{ $user->name() }} </span>
                        </a>
                    </a>


                    <p class="text-xs p-2 bg-green-500 text-white px-4 mt-3 rounded-full">
                        {{ $user->rank() }}
                    </p>
                    <div class="rounded-lg p-2 w-full mt-4">
                        <div class="flex items-center justify-between text-sm text-gray-600 dark:text-gray-200">
                            <p class="flex flex-col">
                                Pengikut
                                <span class="text-black dark:text-white font-bold">
                                    {{ count($user->followers()) }}
                                </span>
                            </p>
                            <p class="flex flex-col">
                                Dikuti
                                <span class="text-black dark:text-white font-bold">
                                    {{ count($user->follows) }}
                                </span>
                            </p>
                            <p class="flex flex-col">
                                Bergabung
                                <span class="text-black dark:text-white font-bold">
                                    {{ $user->createdAt() }}
                                </span>
                            </p>
                        </div>

                    </div>
                    @auth
            @unless (auth()->user()->is($user))
            {{-- Follow Buttons --}}

                <div class="w-full">
                    @if(auth()->user()->isFollowing($user))
                    <x-form action="{{ route('follow', $user) }}">
                        <x-jet-button2>
                            {{ __('Batal Ikuti') }}
                        </x-jet-button2>
                    </x-form>
                    @else
                    <x-form action="{{ route('follow', $user) }}">
                        <x-jet-button2>
                            {{ __('Ikuti') }}
                        </x-jet-button2>
                    </x-form>
                    @endif
                </div>
                @endunless
                @endauth
                </div>
            </div>

        </aside>

        <section class="flex flex-col col-span-3 gap-y-4">
            <x-alerts.main />

            <!-- Profile Information -->
            <div class="bg-white shadow rounded-lg p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-4">Tentang {{ $user->name() }}</h2>

                <div class="grid grid-cols-2 gap-6">
                    <div class="space-y-4">
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Statistik Aktivitas</h3>
                            <div class="mt-2 space-y-2">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Total Postingan:</span>
                                    <span class="font-medium">{{ $user->countThreads() }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Total Replies:</span>
                                    <span class="font-medium">{{ $user->countReplies() }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Email:</span>
                                    <span class="font-medium">{{ $user->email ?? 'Tidak tersedia' }}</span>
                                </div>
                            </div>
                        </div>

                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Rank</h3>
                            <div class="mt-2">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                    {{ $user->rank() }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Informasi Akun</h3>
                            <div class="mt-2 space-y-2">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Bergabung:</span>
                                    <span class="font-medium">{{ $user->createdAt() }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Pengikut:</span>
                                    <span class="font-medium">{{ count($user->followers()) }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Mengikuti:</span>
                                    <span class="font-medium">{{ count($user->follows) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Activity -->
            @if($user->countThreads() > 0)
            <div class="bg-white shadow rounded-lg p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-4">Aktivitas Terbaru</h2>
                <div class="space-y-4">
                    @foreach($user->latestThreads(3) as $thread)
                    <div class="border-l-4 border-blue-500 pl-4 py-2">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-600">Membuat postingan baru</p>
                                <a href="{{ route('threads.show', [$thread->category->slug(), $thread->slug()]) }}"
                                   class="text-blue-600 hover:text-blue-800 font-medium">
                                    {{ $thread->title() }}
                                </a>
                                <p class="text-xs text-gray-500 mt-1">
                                    di kategori {{ $thread->category->name() }}
                                </p>
                            </div>
                            <div class="text-xs text-gray-500">
                                {{ $thread->created_at->diffForHumans() }}
                            </div>
                        </div>
                    </div>
                    @endforeach

                    @if($user->countThreads() > 3)
                    <div class="pt-4 border-t border-gray-200">
                        <a href="{{ route('profile', $user) }}?tab=all-posts"
                           class="text-blue-600 hover:text-blue-800 font-medium text-sm">
                            Lihat semua aktivitas →
                        </a>
                    </div>
                    @endif
                </div>
            </div>
            @endif
        </section>
    </main>
</x-guest-layout>
