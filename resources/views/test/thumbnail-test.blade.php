<x-app-layout>
    <div class="max-w-4xl mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold mb-8">Thumbnail Test Page</h1>

        <div class="space-y-8">
            <div>
                <h2 class="text-xl font-semibold mb-4">Direct Image Tags</h2>
                @foreach($threads as $thread)
                <div class="bg-white rounded-lg shadow-md p-4 mb-4">
                    <h3 class="font-medium mb-2">{{ $thread->title }}</h3>
                    <div class="flex space-x-2">
                        @php
                            $images = $thread->images->count() > 0 ? $thread->images : $thread->media->where('mime_type', 'LIKE', 'image/%');
                        @endphp
                        @foreach($images->take(4) as $image)
                        <div class="flex-shrink-0">
                            <img src="{{ $image->thumbnail_url }}"
                                 alt="{{ $image->original_filename }}"
                                 class="w-16 h-16 object-cover rounded border border-gray-200"
                                 onerror="console.log('Failed to load:', this.src); this.style.border='2px solid red';">
                            <p class="text-xs mt-1">{{ $image->original_filename }}</p>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endforeach
            </div>

            <div>
                <h2 class="text-xl font-semibold mb-4">Lazy Image Component</h2>
                @foreach($threads as $thread)
                <div class="bg-white rounded-lg shadow-md p-4 mb-4">
                    <h3 class="font-medium mb-2">{{ $thread->title }} (Lazy Component)</h3>
                    <div class="flex space-x-2">
                        @php
                            $images = $thread->images->count() > 0 ? $thread->images : $thread->media->where('mime_type', 'LIKE', 'image/%');
                        @endphp
                        @foreach($images->take(4) as $image)
                        <div class="flex-shrink-0">
                            <x-lazy-image
                                src="{{ $image->thumbnail_url }}"
                                alt="{{ $image->original_filename }}"
                                class="w-16 h-16 object-cover rounded border border-gray-200"
                                :placeholder="true"
                            />
                            <p class="text-xs mt-1">{{ $image->original_filename }}</p>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endforeach
            </div>

            <div>
                <h2 class="text-xl font-semibold mb-4">URL Debug Information</h2>
                @foreach($threads as $thread)
                <div class="bg-gray-50 rounded-lg p-4 mb-4">
                    <h3 class="font-medium mb-2">{{ $thread->title }}</h3>
                    @php
                        $images = $thread->images->count() > 0 ? $thread->images : $thread->media->where('mime_type', 'LIKE', 'image/%');
                    @endphp
                    @foreach($images->take(2) as $image)
                    <div class="text-sm space-y-1 mb-3 p-2 bg-white rounded">
                        <p><strong>File:</strong> {{ $image->original_filename }}</p>
                        <p><strong>Original URL:</strong> <a href="{{ $image->url }}" target="_blank" class="text-blue-600">{{ $image->url }}</a></p>
                        <p><strong>Thumbnail URL:</strong> <a href="{{ $image->thumbnail_url }}" target="_blank" class="text-blue-600">{{ $image->thumbnail_url }}</a></p>
                        <p><strong>Path:</strong> {{ $image->path }}</p>
                        <p><strong>Size:</strong> {{ $image->formatted_size }}</p>
                    </div>
                    @endforeach
                </div>
                @endforeach
            </div>
        </div>
    </div>
</x-app-layout>
