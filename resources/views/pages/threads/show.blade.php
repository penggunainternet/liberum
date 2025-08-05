<x-guest-layout>
    <main class="grid grid-cols-4 gap-8 mt-8 wrapper">

        <x-partials.sidenav :thread="$thread" />

        <section class="flex flex-col col-span-3 gap-y-4">

            <x-alerts.main />


            {{-- breadcrumbs --}}
            <div class="flex items-center pb-2 overflow-y-auto whitespace-nowrap">
                <span class="text-gray-600 dark:text-gray-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z" />
                    </svg>
                </span>

                <span class="mx-5 text-gray-500 dark:text-gray-300">
                    <x-heroicon-s-chevron-right class="w-5 h-5" />
                </span>

                <span class="text-gray-500 dark:text-gray-200 ">
                    {{ $category->name() }}
                </span>

                <span class="mx-5 text-gray-500 dark:text-gray-300">
                    <x-heroicon-s-chevron-right class="w-5 h-5" />
                </span>

                <span class="text-yellow-500 dark:text-gray-200 ">
                    {{ $thread->title() }}
                </span>
            </div>

            <article class="p-5 bg-white shadow rounded-lg">
                <div class="relative grid grid-cols-8">




                    {{-- Thread --}}
                    <div class="relative col-span-7 space-y-6">
                        <div class="space-y-3">
                            {{-- Avatar --}}
                            <div class="col-span-1">
                                <x-user.avatar :user="$thread->author()" />
                            </div>
                            <h2 class="text-xl tracking-wide hover:text-blue-400">
                                {{ $thread->title() }}
                            </h2>
                            <div class="text-gray-500">
                                {!! $thread->body() !!}
                            </div>

                            {{-- Images Gallery --}}
                            @php
                                $displayImages = $thread->images->count() > 0 ? $thread->images : $thread->media->where('mime_type', 'LIKE', 'image/%');
                            @endphp

                            @if($displayImages->count() > 0)
                                <div class="mt-4">
                                    <h4 class="text-sm font-medium text-gray-700 mb-3">
                                        Gambar ({{ $displayImages->count() }})
                                        <button onclick="openGallery(0)" class="ml-2 text-blue-500 hover:text-blue-700 text-xs">
                                            Lihat Semua
                                        </button>
                                    </h4>
                                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                                        @foreach($displayImages as $index => $image)
                                            <div class="relative group cursor-pointer" onclick="openGallery({{ $index }})">
                                                <x-lazy-image
                                                    src="{{ $image->url }}"
                                                    alt="{{ $image->original_filename }}"
                                                    class="w-full h-32 object-cover rounded-lg border border-gray-200 hover:border-yellow-400 transition-colors"
                                                    loadingClass="animate-pulse bg-gray-200 rounded-lg"
                                                    placeholder="true"
                                                />
                                                <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-30 transition-opacity rounded-lg flex items-center justify-center">
                                                    <div class="opacity-0 group-hover:opacity-100 transition-opacity text-white text-center">
                                                        <svg class="w-8 h-8 mx-auto mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"></path>
                                                        </svg>
                                                        <span class="text-xs">{{ $index + 1 }}/{{ $displayImages->count() }}</span>
                                                    </div>
                                                </div>
                                                <p class="text-xs text-gray-500 mt-1 truncate">{{ $image->original_filename }}</p>
                                                <p class="text-xs text-gray-400">{{ $image->formatted_size }}</p>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>

                        <div class="flex justify-between">

                            <div class="flex space-x-5 text-gray-500">
                                {{-- Likes --}}
                                <livewire:like-thread :thread="$thread" />

                                {{-- View Count --}}
                                <div class="flex items-center space-x-2">
                                    <x-heroicon-o-eye class="w-4 h-4 text-blue-300" />
                                    <span class="text-xs text-gray-500">{{ views($thread)->count() }}</span>
                                </div>
                            </div>

                            {{-- Date Posted --}}
                            <div class="flex items-center text-xs text-gray-500">
                                <x-heroicon-o-clock class="w-4 h-4 mr-1" />
                                Diposting: {{ $thread->created_at->diffForHumans() }}
                            </div>
                        </div>


                    </div>
                    {{-- Edit Button --}}
                    <div class="absolute top-0 right-2">
                        <div class="flex space-x-2">
                            @can(App\Policies\ThreadPolicy::UPDATE, $thread)
                            <x-links.secondary href="{{ route('threads.edit', $thread->slug()) }}">
                                Edit
                            </x-links.secondary>
                            @endcan

                            @can(App\Policies\ThreadPolicy::DELETE, $thread)
                            <livewire:thread.delete :thread="$thread" :key="$thread->id()" />
                            @endcan
                        </div>
                    </div>
                </div>
            </article>

            {{-- Replies --}}
            <livewire:thread.replies-list :thread="$thread" />

            @auth
            <livewire:thread.reply-form :thread="$thread" />
            @else
            <div class="flex justify-between p-4 text-gray-700 bg-blue-200 rounded">
                <h2>Anda harus login terlebih dahulu sebelum mengirim komentar</h2>
                <a href="{{ route('login') }}">Login</a>
            </div>
            @endauth
        </section>
    </main>

    {{-- Image Modal --}}
    <div id="imageModal" class="fixed inset-0 bg-black bg-opacity-75 z-50 hidden items-center justify-center p-4" onclick="closeImageModal()">
        <div class="max-w-4xl max-h-full relative">
            <button onclick="closeImageModal()" class="absolute -top-10 right-0 text-white hover:text-gray-300 text-2xl">
                ✕
            </button>
            <img id="modalImage" src="" alt="" class="max-w-full max-h-full object-contain rounded-lg">
            <p id="modalImageName" class="text-white text-center mt-2"></p>
        </div>
    </div>

    {{-- Advanced Gallery Modal --}}
    <div id="advancedGallery" class="fixed inset-0 bg-black bg-opacity-90 z-50 hidden items-center justify-center">
        <!-- Gallery Header -->
        <div class="absolute top-0 left-0 right-0 z-10 bg-gradient-to-b from-black to-transparent p-4">
            <div class="flex justify-between items-center text-white">
                <div class="space-y-1">
                    <h3 id="galleryFilename" class="font-medium text-lg"></h3>
                    <div class="flex items-center space-x-4 text-sm text-gray-300">
                        <span id="galleryCounter"></span>
                        <span id="galleryImageSize"></span>
                    </div>
                </div>
                <div class="flex items-center space-x-2">
                    <button onclick="downloadImage()" class="p-2 hover:bg-white hover:bg-opacity-20 rounded-full transition-colors" title="Download">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-4-4m4 4l4-4m5-10V4a2 2 0 00-2-2H5a2 2 0 00-2 2v16l4-2 4 2 4-2 4 2V6a2 2 0 00-2-2h-2"></path>
                        </svg>
                    </button>
                    <button onclick="closeGallery()" class="p-2 hover:bg-white hover:bg-opacity-20 rounded-full transition-colors text-2xl">
                        ✕
                    </button>
                </div>
            </div>
        </div>

        <!-- Navigation Arrows -->
        <button onclick="prevImage()" class="absolute left-4 top-1/2 transform -translate-y-1/2 z-10 bg-black bg-opacity-50 hover:bg-opacity-70 text-white p-3 rounded-full transition-all">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
        </button>

        <button onclick="nextImage()" class="absolute right-4 top-1/2 transform -translate-y-1/2 z-10 bg-black bg-opacity-50 hover:bg-opacity-70 text-white p-3 rounded-full transition-all">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
        </button>

        <!-- Main Image Container -->
        <div class="flex-1 flex items-center justify-center p-4 pt-20 pb-24">
            <img id="galleryMainImage" src="" alt="" class="max-w-full max-h-full object-contain">
        </div>

        <!-- Thumbnail Strip -->
        <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black to-transparent p-4">
            <div class="flex justify-center space-x-2 overflow-x-auto pb-2">
                @php
                    $displayImages = $thread->images->count() > 0 ? $thread->images : $thread->media->where('mime_type', 'LIKE', 'image/%');
                @endphp
                @if($displayImages->count() > 0)
                    @foreach($displayImages as $index => $image)
                        <div class="flex-shrink-0">
                            <x-lazy-image
                                src="{{ $image->url }}"
                                alt="{{ $image->original_filename }}"
                                onclick="goToImage({{ $index }})"
                                class="gallery-thumbnail w-16 h-16 object-cover rounded cursor-pointer border-2 border-transparent hover:border-white transition-colors"
                                loadingClass="animate-pulse bg-gray-600 rounded"
                                placeholder="true"
                            />
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>

    <script>
        // Advanced Gallery Variables
        let currentImageIndex = 0;
        let galleryImages = [];

        @php
            $displayImages = $thread->images->count() > 0 ? $thread->images : $thread->media->where('mime_type', 'LIKE', 'image/%');
        @endphp

        @if($displayImages->count() > 0)
            galleryImages = [
                @foreach($displayImages as $image)
                {
                    url: '{{ $image->url }}',
                    filename: '{{ $image->original_filename }}',
                    size: '{{ $image->formatted_size }}'
                },
                @endforeach
            ];
        @endif

        function openGallery(index = 0) {
            if (galleryImages.length === 0) return;

            currentImageIndex = index;
            const modal = document.getElementById('advancedGallery');
            updateGalleryImage();

            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.body.style.overflow = 'hidden';
        }

        function closeGallery() {
            const modal = document.getElementById('advancedGallery');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            document.body.style.overflow = 'auto';
        }

        function nextImage() {
            if (galleryImages.length === 0) return;
            currentImageIndex = (currentImageIndex + 1) % galleryImages.length;
            updateGalleryImage();
        }

        function prevImage() {
            if (galleryImages.length === 0) return;
            currentImageIndex = currentImageIndex === 0 ? galleryImages.length - 1 : currentImageIndex - 1;
            updateGalleryImage();
        }

        function goToImage(index) {
            currentImageIndex = index;
            updateGalleryImage();
        }

        function updateGalleryImage() {
            if (galleryImages.length === 0 || !galleryImages[currentImageIndex]) return;

            const image = galleryImages[currentImageIndex];
            const galleryImage = document.getElementById('galleryMainImage');
            const galleryFilename = document.getElementById('galleryFilename');
            const galleryCounter = document.getElementById('galleryCounter');
            const gallerySize = document.getElementById('galleryImageSize');

            // Update main image
            galleryImage.src = image.url;
            galleryImage.alt = image.filename;

            // Update info
            galleryFilename.textContent = image.filename;
            galleryCounter.textContent = `${currentImageIndex + 1} / ${galleryImages.length}`;
            gallerySize.textContent = image.size;

            // Update thumbnails
            const thumbnails = document.querySelectorAll('.gallery-thumbnail');
            thumbnails.forEach((thumb, index) => {
                if (index === currentImageIndex) {
                    thumb.classList.add('ring-2', 'ring-yellow-400');
                } else {
                    thumb.classList.remove('ring-2', 'ring-yellow-400');
                }
            });
        }

        function downloadImage() {
            if (galleryImages.length === 0 || !galleryImages[currentImageIndex]) return;

            const image = galleryImages[currentImageIndex];
            const link = document.createElement('a');
            link.href = image.url;
            link.download = image.filename;
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }

        function openImageModal(imageUrl, imageName) {
            const modal = document.getElementById('imageModal');
            const modalImage = document.getElementById('modalImage');
            const modalImageName = document.getElementById('modalImageName');

            modalImage.src = imageUrl;
            modalImage.alt = imageName;
            modalImageName.textContent = imageName;

            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.body.style.overflow = 'hidden';
        }

        function closeImageModal() {
            const modal = document.getElementById('imageModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            document.body.style.overflow = 'auto';
        }

        // Reply Image Upload Alpine.js Component
        function replyImageUpload() {
            return {
                files: [],
                error: '',
                totalSize: 0,
                maxFiles: 3,
                maxTotalSize: 10 * 1024 * 1024, // 10MB

                handleFiles(event) {
                    const selectedFiles = Array.from(event.target.files);
                    this.processFiles(selectedFiles);
                },

                handleDrop(event) {
                    const droppedFiles = Array.from(event.dataTransfer.files);
                    this.processFiles(droppedFiles);
                },

                processFiles(newFiles) {
                    this.error = '';

                    // Filter only image files
                    const imageFiles = newFiles.filter(file => file.type.startsWith('image/'));

                    if (imageFiles.length !== newFiles.length) {
                        this.error = 'Hanya file gambar yang diperbolehkan.';
                        return;
                    }

                    // Check max files limit
                    if (this.files.length + imageFiles.length > this.maxFiles) {
                        this.error = `Maksimal ${this.maxFiles} gambar diperbolehkan.`;
                        return;
                    }

                    // Process each file
                    imageFiles.forEach(file => {
                        // Check total size
                        if (this.totalSize + file.size > this.maxTotalSize) {
                            this.error = 'Total ukuran file melebihi 10MB.';
                            return;
                        }

                        // Create preview
                        const reader = new FileReader();
                        reader.onload = (e) => {
                            this.files.push({
                                file: file,
                                name: file.name,
                                size: file.size,
                                preview: e.target.result
                            });
                            this.updateTotalSize();
                        };
                        reader.readAsDataURL(file);
                    });
                },

                removeFile(index) {
                    this.files.splice(index, 1);
                    this.updateTotalSize();
                    this.error = '';

                    // Update file input
                    const fileInput = this.$refs.fileInput;
                    const dt = new DataTransfer();
                    this.files.forEach(fileObj => dt.items.add(fileObj.file));
                    fileInput.files = dt.files;
                },

                updateTotalSize() {
                    this.totalSize = this.files.reduce((total, fileObj) => total + fileObj.size, 0);
                },

                formatFileSize(bytes) {
                    if (bytes === 0) return '0 Bytes';
                    const k = 1024;
                    const sizes = ['Bytes', 'KB', 'MB'];
                    const i = Math.floor(Math.log(bytes) / Math.log(k));
                    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
                }
            }
        }

        // Keyboard navigation
        document.addEventListener('keydown', function(event) {
            const advancedGallery = document.getElementById('advancedGallery');
            const isGalleryOpen = !advancedGallery.classList.contains('hidden');

            if (event.key === 'Escape') {
                if (isGalleryOpen) {
                    closeGallery();
                } else {
                    closeImageModal();
                }
            }

            if (isGalleryOpen) {
                if (event.key === 'ArrowRight') {
                    event.preventDefault();
                    nextImage();
                }
                if (event.key === 'ArrowLeft') {
                    event.preventDefault();
                    prevImage();
                }
            }
        });
    </script>
</x-guest-layout>
