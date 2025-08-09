<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                {{ __('Profil') }}
            </h2>

        </div>
    </x-slot>

    <div class="wrapper">
        <!-- Profile Overview Card -->
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg mb-6">
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-gray-900">
                        Ringkasan Profil Public
                    </h3>

                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Profile Preview -->
                    <div class="flex items-center space-x-4">
                        <img class="w-16 h-16 rounded-full object-cover"
                             src="{{ auth()->user()->profile_photo_url }}"
                             alt="{{ auth()->user()->name }}">
                        <div>
                            <h4 class="text-lg font-semibold text-gray-900">{{ auth()->user()->name }}</h4>
                            <p class="text-sm text-gray-600">{{ auth()->user()->email }}</p>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                {{ auth()->user()->rank() }}
                            </span>
                        </div>
                    </div>

                    <!-- Statistics -->
                    <div class="space-y-3">
                        <h5 class="text-sm font-medium text-gray-700">Aktivitas Forum</h5>
                        <div class="space-y-2">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Postingan:</span>
                                <span class="font-medium">{{ auth()->user()->countThreads() }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Replies:</span>
                                <span class="font-medium">{{ auth()->user()->countReplies() }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Social Stats -->
                    <div class="space-y-3">
                        <h5 class="text-sm font-medium text-gray-700">Jejaring Sosial</h5>
                        <div class="space-y-2">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Pengikut:</span>
                                <span class="font-medium">{{ count(auth()->user()->followers()) }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Mengikuti:</span>
                                <span class="font-medium">{{ count(auth()->user()->follows) }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Bergabung:</span>
                                <span class="font-medium">{{ auth()->user()->createdAt() }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="mt-6 pt-4 border-t border-gray-200">
                    <div class="flex space-x-3">

                        <a href="{{ route('dashboard.posts.index') }}"
                           class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <x-heroicon-o-document-text class="w-4 h-4 mr-2" />
                            Kelola Postingan
                        </a>
                    </div>
                </div>
            </div>
        </div>

        @if (Laravel\Fortify\Features::canUpdateProfileInformation())
        @livewire('profile.update-profile-information-form')

        <x-jet-section-border />
        @endif

        @if (Laravel\Fortify\Features::enabled(Laravel\Fortify\Features::updatePasswords()))
        <div class="mt-10 sm:mt-0">
            @livewire('profile.update-password-form')
        </div>

        <x-jet-section-border />
        @endif

        @if (Laravel\Fortify\Features::canManageTwoFactorAuthentication())
        <div class="mt-10 sm:mt-0">
            @livewire('profile.two-factor-authentication-form')
        </div>

        <x-jet-section-border />
        @endif

        <div class="mt-10 sm:mt-0">
            @livewire('profile.logout-other-browser-sessions-form')
        </div>

        @if (Laravel\Jetstream\Jetstream::hasAccountDeletionFeatures())
        <x-jet-section-border />

        <div class="mt-10 sm:mt-0">
            @livewire('profile.delete-user-form')
        </div>
        @endif
    </div>
</x-app-layout>
