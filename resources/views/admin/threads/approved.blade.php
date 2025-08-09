<x-admin-layout>
    {{-- Header --}}
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight">
            {{ __('Thread yang Disetujui') }}
        </h2>
    </x-slot>

    <section class="px-6">
        @if($threads->count() > 0)
            <div class="space-y-4">
                @foreach($threads as $thread)
                    <div class="bg-white border rounded-lg p-6 shadow-sm">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <div class="flex items-center space-x-3 mb-2">
                                    <span class="px-2 py-1 text-xs bg-green-100 text-green-800 rounded-full font-medium">
                                        ✓ Disetujui
                                    </span>
                                    <span class="text-sm text-gray-500">
                                        Disetujui {{ $thread->approved_at && method_exists($thread->approved_at, 'diffForHumans') ? $thread->approved_at->diffForHumans() : ($thread->approved_at ?: 'N/A') }}
                                        @if($thread->approvedBy)
                                            oleh {{ $thread->approvedBy->name }}
                                        @endif
                                    </span>
                                </div>

                                <h3 class="text-lg font-semibold text-gray-900 mb-2">
                                    <a href="{{ route('admin.threads.show', $thread) }}" class="hover:text-blue-600">
                                        {{ $thread->title }}
                                    </a>
                                </h3>

                                <div class="text-sm text-gray-600 mb-3">
                                    <span class="font-medium">Penulis:</span> {{ $thread->author()->name }} |
                                    <span class="font-medium">Kategori:</span> {{ $thread->category->name }} |
                                    <span class="font-medium">Dibuat:</span> {{ method_exists($thread->created_at, 'format') ? $thread->created_at->format('d M Y H:i') : $thread->created_at }}
                                </div>

                                <div class="text-gray-700 mb-4">
                                    {{ Str::limit(strip_tags($thread->body), 200) }}
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center justify-between pt-4 border-t">
                            <a href="{{ route('admin.threads.show', $thread) }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                Lihat Detail
                            </a>

                            <div class="flex space-x-3">
                                <span class="px-4 py-2 bg-green-100 text-green-800 text-sm rounded font-medium">
                                    ✓ Disetujui
                                </span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Pagination --}}
            <div class="mt-6">
                {{ $threads->links() }}
            </div>
        @else
            <div class="text-center py-12">
                <div class="text-gray-500 text-lg">
                    📝 Belum ada thread yang disetujui
                </div>
            </div>
        @endif
    </section>
</x-admin-layout>
