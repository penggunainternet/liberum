<aside class="min-h-screen col-span-1 px-8 bg-white shadow w-56">
    <div class="py-6 space-y-7">
        {{-- Dashboard --}}
        <div>
            <x-sidenav.title>
                {{ __('Dashboard') }}
            </x-sidenav.title>
            <div>
                <x-sidenav.link href="{{ route('profile.show') }}" :active="request()->routeIs('profile.show')">
                    <x-zondicon-user class="w-3  ml-2 mr-3" style="color:#FC9B5C;"/>
                    <span>{{ __('Profil') }}</span>
                </x-sidenav.link>
            </div>

            @if(auth()->user()->isAdmin())
            <div>
                <x-sidenav.link href="{{ route('admin.users.active') }}" :active="request()->routeIs('admin.users.active')">
                    <x-zondicon-user-group class="w-3  ml-2 mr-3" style="color:#FC9B5C;"/>
                    <span>{{ __('Pengguna') }}</span>
                </x-sidenav.link>
            </div>
            @endif

            <div>
                <x-sidenav.link href="{{ route('dashboard.notifications.index') }}" :active="request()->routeIs('dashboard.notifications.index')">
                    <x-zondicon-notifications-outline class="w-3 ml-2 mr-3" style="color:#FC9B5C;"/>
                    <span>{{ __('Notifikasi') }}</span>
                </x-sidenav.link>
            </div>
        </div>

        @if(auth()->user()->isAdmin())
        {{-- Categories --}}
        <div>
            <x-sidenav.title>
                {{ __('Kategori') }}
            </x-sidenav.title>
            <div>
                <x-sidenav.link href="{{ route('admin.categories.index') }}" :active="request()->routeIs('admin.categories.index')">
                    <x-zondicon-view-tile class="w-3  ml-2 mr-3" style="color:#FC9B5C;"/>
                    <span>{{ __('Indeks') }}</span>
                </x-sidenav.link>
            </div>
            <div>
                <x-sidenav.link href="{{ route('admin.categories.create') }}" :active="request()->routeIs('admin.categories.create')">
                    <x-zondicon-compose class="w-3 ml-2 mr-3" style="color:#FC9B5C;"/>
                    <span>{{ __('Tambah Baru') }}</span>
                </x-sidenav.link>
            </div>
        </div>
        @endif



        {{-- Threads --}}
        <div>
            <x-sidenav.title>
                {{ __('Postingan') }}
            </x-sidenav.title>
            <div>
                <x-sidenav.link href="{{ route('dashboard.posts.index') }}" :active="request()->routeIs('dashboard.posts.index')">
                    <x-heroicon-o-document-text class="w-4 ml-2 mr-3" style="color:#FC9B5C;"/>
                    <span>{{ __('Daftar Postingan') }}</span>
                </x-sidenav.link>
            </div>

            @if(auth()->user()->isAdmin())
            <div>
                <x-sidenav.link href="{{ route('admin.threads.pending') }}" :active="request()->routeIs('admin.threads.*')">
                    <x-heroicon-o-clipboard-list class="w-4 ml-2 mr-3" style="color:#FC9B5C;"/>
                    <span>{{ __('Kelola Thread') }}</span>
                    @php
                        $pendingCount = App\Models\Thread::pending()
                            ->whereHas('authorRelation', function($query) {
                                $query->where('type', '!=', 3);
                            })
                            ->count();
                    @endphp
                    @if($pendingCount > 0)
                        <span class="ml-2 px-2 py-1 text-xs bg-red-500 text-white rounded-full">{{ $pendingCount }}</span>
                    @endif
                </x-sidenav.link>
            </div>
            @endif
        </div>



        {{-- Threads --}}
        <div>
            <x-sidenav.title>
                {{ __('Autentikasi') }}
            </x-sidenav.title>
            <div>
                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-sidenav.link href="{{ route('logout') }}" onclick="event.preventDefault();                                               this.closest('form').submit();">
                        <x-heroicon-o-logout class="w-4 ml-2 mr-3" style="color:#FC9B5C;"/>
                        <span>{{ __('Keluar') }}</span>
                    </x-sidenav.link>

                </form>

            </div>
        </div>

    </div>
</aside>
