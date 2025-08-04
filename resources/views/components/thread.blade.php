<article class="p-5 bg-white shadow rounded-lg">

    <div class="relative grid grid-cols-8 gap-3 ">
        {{-- Avatar --}}


        {{-- Content --}}
        <div class="col-span-6 space-y-4">
            <div class="col-span-1 mb-1">
                <x-user.avatar :user="$thread->author()" />
            </div>

            <a href="{{ route('threads.show', [$thread->category->slug(), $thread->slug()]) }}" class="space-y-2">
                <h2 class="text-xl tracking-wide hover:text-blue-400">
                    {{ $thread->title() }}
                </h2>
                <p class="text-gray-500">
                    {{ $thread->excerpt() }}
                </p>
            </a>

            {{-- Thread Images Thumbnail --}}
            @php
                $threadImages = $thread->images->count() > 0 ? $thread->images : $thread->media->where('mime_type', 'LIKE', 'image/%');
            @endphp
            @if($threadImages->count() > 0)
                <div class="mt-3">
                    <div class="flex items-center space-x-2 mb-2">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                        </svg>
                        <span class="text-xs text-gray-500">{{ $threadImages->count() }} gambar</span>
                    </div>
                    <a href="{{ route('threads.show', [$thread->category->slug(), $thread->slug()]) }}" class="block">
                        <div class="flex space-x-2 overflow-x-auto pb-2">
                            @foreach($threadImages->take(4) as $image)
                                <div class="flex-shrink-0">
                                    <img src="{{ $image->thumbnail_url }}"
                                         alt="{{ $image->original_filename }}"
                                         class="w-16 h-16 object-cover rounded border border-gray-200 hover:opacity-80 hover:border-blue-300 transition-all cursor-pointer">
                                </div>
                            @endforeach
                            @if($threadImages->count() > 4)
                                <div class="flex-shrink-0 w-16 h-16 bg-gray-100 rounded border border-gray-200 flex items-center justify-center hover:bg-gray-200 transition-colors cursor-pointer">
                                    <span class="text-xs text-gray-500 font-medium">+{{ $threadImages->count() - 4 }}</span>
                                </div>
                            @endif
                        </div>
                    </a>
                </div>
            @endif

            {{-- Indicators --}}
            <div class="flex space-x-6">

                {{-- Likes Count --}}
                <div class="flex items-center space-x-2">
                    <x-heroicon-o-thumb-up class="w-5 h-5 text-red-300" />
                    <span class="text-xs text-gray-500">{{ count($thread->likes()) }}</span>
                </div>

                {{-- Comments Count --}}
                <div class="flex items-center space-x-2">
                    <x-heroicon-o-chat-alt class="w-4 h-4 text-green-300" />
                    <span class="text-xs text-gray-500">{{ count($thread->replies()) }} komentar</span>
                </div>

                {{-- Views Count --}}
                <div class="flex items-center space-x-2">
                    <x-heroicon-o-eye class="w-4 h-4 text-blue-300" />
                    <span class="text-xs text-gray-500">{{ views($thread)->count() }}</span>
                </div>

                {{-- Post Date --}}
                <div class="flex items-center space-x-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span class="text-xs text-gray-500">{{ $thread->created_at->diffForHumans() }}</span>
                </div>
            </div>
        </div>

        {{-- Tags --}}
        <div class="absolute right-2">
            <div class="flex space-x-2">
                <a href="{{ route('threads.sort', $thread->category->slug) }}" class="p-1 text-xs text-white bg-green-400 rounded">
                    {{ $thread->category->name }}
                </a>
            </div>
        </div>
    </div>
</article>
