<x-app-layout>
    <div class="max-w-4xl mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold mb-8">Lazy Loading Test Page</h1>

        <div class="mb-8">
            <h2 class="text-xl font-semibold mb-4">Loading Stats</h2>
            <div id="loadingStats" class="bg-blue-50 p-4 rounded">
                <div class="grid grid-cols-4 gap-4 text-center">
                    <div>
                        <span class="block text-2xl font-bold text-blue-600" id="totalImages">0</span>
                        <span class="text-sm text-gray-600">Total</span>
                    </div>
                    <div>
                        <span class="block text-2xl font-bold text-green-600" id="loadedImages">0</span>
                        <span class="text-sm text-gray-600">Loaded</span>
                    </div>
                    <div>
                        <span class="block text-2xl font-bold text-red-600" id="errorImages">0</span>
                        <span class="text-sm text-gray-600">Errors</span>
                    </div>
                    <div>
                        <span class="block text-2xl font-bold text-yellow-600" id="pendingImages">0</span>
                        <span class="text-sm text-gray-600">Pending</span>
                    </div>
                </div>
            </div>

            <div class="mt-4 space-x-2">
                <button onclick="loadAll()" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                    Load All Images
                </button>
                <button onclick="retryErrors()" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">
                    Retry Failed Images
                </button>
                <button onclick="updateStats()" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">
                    Update Stats
                </button>
            </div>
        </div>

        <div class="space-y-12">
            <div>
                <h2 class="text-xl font-semibold mb-4">Test Images - Different Sizes</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @for($i = 1; $i <= 9; $i++)
                    <div class="bg-white rounded-lg shadow-md overflow-hidden">
                        <div class="aspect-square">
                            <x-lazy-image
                                src="https://picsum.photos/400/400?random={{ $i }}"
                                alt="Test Image {{ $i }}"
                                class="w-full h-full object-cover"
                                placeholder="true"
                            />
                        </div>
                        <div class="p-3">
                            <h3 class="font-medium">Test Image {{ $i }}</h3>
                            <p class="text-sm text-gray-600">400x400 pixels</p>
                        </div>
                    </div>
                    @endfor
                </div>
            </div>

            <div>
                <h2 class="text-xl font-semibold mb-4">High Resolution Images</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @for($i = 10; $i <= 13; $i++)
                    <div class="bg-white rounded-lg shadow-md overflow-hidden">
                        <div class="aspect-video">
                            <x-lazy-image
                                src="https://picsum.photos/800/600?random={{ $i }}"
                                alt="High Res Image {{ $i }}"
                                class="w-full h-full object-cover"
                                placeholder="true"
                            />
                        </div>
                        <div class="p-3">
                            <h3 class="font-medium">High Resolution {{ $i }}</h3>
                            <p class="text-sm text-gray-600">800x600 pixels</p>
                        </div>
                    </div>
                    @endfor
                </div>
            </div>

            <div>
                <h2 class="text-xl font-semibold mb-4">Portrait Images</h2>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    @for($i = 14; $i <= 21; $i++)
                    <div class="bg-white rounded-lg shadow-md overflow-hidden">
                        <div class="aspect-photo">
                            <x-lazy-image
                                src="https://picsum.photos/300/400?random={{ $i }}"
                                alt="Portrait Image {{ $i }}"
                                class="w-full h-full object-cover"
                                placeholder="true"
                            />
                        </div>
                        <div class="p-2">
                            <h3 class="font-medium text-sm">Portrait {{ $i }}</h3>
                            <p class="text-xs text-gray-600">300x400 pixels</p>
                        </div>
                    </div>
                    @endfor
                </div>
            </div>

            <div>
                <h2 class="text-xl font-semibold mb-4">Error Test (Broken URLs)</h2>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    @for($i = 1; $i <= 4; $i++)
                    <div class="bg-white rounded-lg shadow-md overflow-hidden">
                        <div class="aspect-square">
                            <x-lazy-image
                                src="https://broken-url-{{ $i }}.jpg"
                                alt="Broken Image {{ $i }}"
                                class="w-full h-full object-cover"
                                placeholder="true"
                            />
                        </div>
                        <div class="p-2">
                            <h3 class="font-medium text-sm">Broken {{ $i }}</h3>
                            <p class="text-xs text-gray-600">Should show error</p>
                        </div>
                    </div>
                    @endfor
                </div>
            </div>

            <div>
                <h2 class="text-xl font-semibold mb-4">Very Large Images (Performance Test)</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @for($i = 30; $i <= 35; $i++)
                    <div class="bg-white rounded-lg shadow-md overflow-hidden">
                        <div class="aspect-video">
                            <x-lazy-image
                                src="https://picsum.photos/1920/1080?random={{ $i }}"
                                alt="Large Image {{ $i }}"
                                class="w-full h-full object-cover"
                                placeholder="true"
                            />
                        </div>
                        <div class="p-3">
                            <h3 class="font-medium">Large Image {{ $i }}</h3>
                            <p class="text-sm text-gray-600">1920x1080 pixels (~500KB)</p>
                        </div>
                    </div>
                    @endfor
                </div>
            </div>
        </div>

        <div class="mt-16 text-center">
            <p class="text-gray-600">
                Scroll to see lazy loading in action. Check browser DevTools Network tab to see images loading on demand.
            </p>
        </div>
    </div>

    <script>
        function updateStats() {
            if (window.lazyLoader) {
                const stats = window.lazyLoader.getStats();
                document.getElementById('totalImages').textContent = stats.total;
                document.getElementById('loadedImages').textContent = stats.loaded;
                document.getElementById('errorImages').textContent = stats.errors;
                document.getElementById('pendingImages').textContent = stats.pending;
            }
        }

        function loadAll() {
            if (window.lazyLoader) {
                window.lazyLoader.loadAll();
                setTimeout(updateStats, 1000);
            }
        }

        function retryErrors() {
            if (window.lazyLoader) {
                window.lazyLoader.retryErrors();
                setTimeout(updateStats, 1000);
            }
        }

        // Update stats every 2 seconds
        setInterval(updateStats, 2000);

        // Initial stats update
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(updateStats, 1000);
        });
    </script>
</x-app-layout>
