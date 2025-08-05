<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Daftar Postingan Saya
            </h2>
            <div class="text-sm text-gray-600">
                Total: {{ $user->countThreads() }} postingan
            </div>
        </div>
    </x-slot>

    <div class="wrapper">
        @if($posts->count() > 0)
            <div class="space-y-6">
                @foreach($posts as $post)
                    <div class="bg-white border border-gray-200 rounded-lg shadow-sm hover:shadow-md transition-shadow">
                        <div class="p-6">
                            <!-- Header with Category and Actions -->
                            <div class="flex items-start justify-between mb-4">
                                <div class="flex items-center space-x-3">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        {{ $post->category->name() }}
                                    </span>
                                    <span class="text-sm text-gray-500">
                                        {{ $post->created_at->diffForHumans() }}
                                    </span>
                                </div>

                                <div class="flex items-center space-x-2">
                                    @can(\App\Policies\ThreadPolicy::UPDATE, $post)
                                        <a href="{{ route('threads.edit', $post->slug()) }}"
                                           class="text-sm text-blue-600 hover:text-blue-800 font-medium">
                                            Edit
                                        </a>
                                    @endcan

                                    @can(\App\Policies\ThreadPolicy::DELETE, $post)
                                        <button onclick="confirmDelete('{{ $post->slug() }}')"
                                                class="text-sm text-red-600 hover:text-red-800 font-medium">
                                            Hapus
                                        </button>
                                    @endcan
                                </div>
                            </div>

                            <!-- Title -->
                            <h3 class="text-lg font-semibold text-gray-900 mb-3">
                                <a href="{{ route('threads.show', [$post->category->slug(), $post->slug()]) }}"
                                   class="hover:text-blue-600 transition-colors">
                                    {{ $post->title() }}
                                </a>
                            </h3>

                            <!-- Content Preview -->
                            <div class="text-gray-600 mb-4 line-clamp-3">
                                {!! \Illuminate\Support\Str::limit(strip_tags($post->body()), 200) !!}
                            </div>

                            <!-- Stats -->
                            <div class="flex items-center space-x-6 text-sm text-gray-500">
                                <div class="flex items-center space-x-1">
                                    <x-heroicon-o-eye class="w-4 h-4" />
                                    <span>{{ $post->views_count ?? 0 }} views</span>
                                </div>

                                <div class="flex items-center space-x-1">
                                    <x-heroicon-o-heart class="w-4 h-4" />
                                    <span>{{ $post->likesCount() }} likes</span>
                                </div>

                                <div class="flex items-center space-x-1">
                                    <x-heroicon-o-chat-alt class="w-4 h-4" />
                                    <span>{{ $post->repliesCount() }} replies</span>
                                </div>

                                <div class="ml-auto">
                                    <a href="{{ route('threads.show', [$post->category->slug(), $post->slug()]) }}"
                                       class="text-blue-600 hover:text-blue-800 font-medium">
                                        Lihat Detail →
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-8">
                {{ $posts->links() }}
            </div>
        @else
            <!-- Empty State -->
            <div class="text-center py-12">
                <x-heroicon-o-document-text class="w-16 h-16 mx-auto text-gray-400 mb-4" />
                <h3 class="text-lg font-medium text-gray-900 mb-2">
                    Belum Ada Postingan
                </h3>
                <p class="text-gray-600 mb-6">
                    Anda belum membuat postingan apapun. Mulai berbagi dengan komunitas!
                </p>
                <a href="{{ route('threads.create') }}"
                   class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring focus:ring-blue-300 disabled:opacity-25 transition">
                    <x-heroicon-o-plus class="w-4 h-4 mr-2" />
                    Buat Postingan Pertama
                </a>
            </div>
        @endif
    </div>

    <!-- Custom Styles -->
    @push('styles')
        <style>
            .line-clamp-3 {
                display: -webkit-box;
                -webkit-line-clamp: 3;
                line-clamp: 3;
                -webkit-box-orient: vertical;
                overflow: hidden;
            }
        </style>
    @endpush

    <!-- Delete Confirmation -->
    @push('scripts')
        <script>
            function confirmDelete(slug) {
                if (confirm('Apakah Anda yakin ingin menghapus postingan ini?')) {
                    // Create and submit delete form
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = `/threads/${slug}`;

                    // Add CSRF token
                    const csrfToken = document.createElement('input');
                    csrfToken.type = 'hidden';
                    csrfToken.name = '_token';
                    csrfToken.value = '{{ csrf_token() }}';
                    form.appendChild(csrfToken);

                    // Add method override
                    const methodField = document.createElement('input');
                    methodField.type = 'hidden';
                    methodField.name = '_method';
                    methodField.value = 'DELETE';
                    form.appendChild(methodField);

                    document.body.appendChild(form);
                    form.submit();
                }
            }
        </script>
    @endpush
</x-app-layout>
