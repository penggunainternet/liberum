<x-guest-layout>
    <main class="grid grid-cols-4 gap-8 mt-8 wrapper">

        <x-partials.sidenav />

        <section class="flex flex-col col-span-3 gap-y-4">

             {{-- breadcrumb --}}
             <div class="flex items-center pb-2 overflow-y-auto whitespace-nowrap">
                <a href="#" class="text-gray-600 dark:text-gray-200">
                    Postingan
                </a>

                <span class="mx-5 text-gray-500 dark:text-gray-300">
                    <x-heroicon-s-chevron-right class="w-5 h-5" />
                </span>

                <span class="text-gray-500 dark:text-gray-200 ">
                    {{ $thread->title() }}
                </span>

                <span class="mx-4 text-gray-500 dark:text-gray-300">
                    <x-heroicon-s-chevron-right class="w-5 h-5" />
                </span>

                <span class=" text-yellow-500 dark:text-gray-300">
                    Edit
                </span>
            </div>

            <article class="p-5 bg-white shadow rounded-lg">
                <div class="w-full">

                    {{-- Create --}}
                    <div class="space-y-6">
                        <x-form action="{{ route('threads.update', $thread->slug()) }}" enctype="multipart/form-data">>
                            <div class="space-y-8">

                                {{-- Title --}}
                                <div>
                                    <x-form.label for="title" value="{{ __('Judul') }}" />
                                    <x-form.input id="title" class="block w-full mt-1" type="text" name="title" :value="$thread->title()" />
                                    <x-form.error for="title" />
                                </div>

                                {{-- Category --}}
                                <div>
                                    <x-form.label for="category_id" value="{{ __('Kategori') }}" />
                                    <select name="category_id" id="category_id" class="w-full text-sm border-gray-300 rounded-md shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                        @foreach ($categories as $category)
                                        <option value="{{ $category->id() }}" @if($category->id() == $selectedCategory->id) selected @endif>
                                            {{ $category->name() }}
                                        </option>
                                        @endforeach
                                    </select>
                                    <x-form.error for="category_id" />
                                </div>




                                {{-- Body --}}
                                <div>
                                    <x-form.label for="body" value="{{ __('Deskripsi') }}" />
                                    <textarea
                                        id="body"
                                        name="body"
                                        rows="8"
                                        class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                                        placeholder="Tulis deskripsi thread Anda di sini...">{{ $thread->body() }}</textarea>
                                    <x-form.error for="body" />
                                </div>

                                {{-- Current Images Management --}}
                                @if($thread->images->count() > 0)
                                    <div>
                                        <x-form.label value="Gambar Saat Ini" />
                                        <div class="mt-2 grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4" id="currentImages">
                                            @foreach($thread->images as $image)
                                                <div class="relative group" id="image-{{ $image->id }}">
                                                    <x-lazy-image
                                                        src="{{ $image->url }}"
                                                        alt="{{ $image->original_filename }}"
                                                        class="w-full h-32 object-cover rounded-lg border border-gray-200"
                                                        loadingClass="animate-pulse bg-gray-200 rounded-lg"
                                                        placeholder="true"
                                                    />
                                                    <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-50 transition-opacity rounded-lg flex items-center justify-center">
                                                        <button type="button"
                                                                onclick="removeExistingImage({{ $image->id }})"
                                                                class="bg-red-500 text-white p-2 rounded-full opacity-0 group-hover:opacity-100 transition-opacity hover:bg-red-600">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                            </svg>
                                                        </button>
                                                    </div>
                                                    <p class="text-xs text-gray-500 mt-1 truncate">{{ $image->original_filename }}</p>
                                                    <p class="text-xs text-gray-400">{{ $image->formatted_size }}</p>
                                                </div>
                                            @endforeach
                                        </div>
                                        <input type="hidden" name="removed_images" id="removedImages" value="">
                                    </div>
                                @endif

                                {{-- Add New Images --}}
                                <div>
                                    <x-form.label for="images" value="Tambah Gambar Baru (Opsional)" />
                                    <div class="mt-1">
                                        <input type="file"
                                               name="images[]"
                                               id="images"
                                               multiple
                                               accept="image/jpeg,image/png,image/jpg,image/gif,image/webp"
                                               class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-yellow-50 file:text-yellow-700 hover:file:bg-yellow-100"
                                               onchange="previewNewImages(this)">
                                        <p class="mt-1 text-xs text-gray-500">
                                            Maksimal total 5 gambar per thread. Total ukuran maksimal 15MB. Format: JPEG, PNG, JPG, GIF, WebP.
                                        </p>
                                        <x-form.error for="images" />
                                        <x-form.error for="images.*" />
                                    </div>

                                    {{-- New Images Preview --}}
                                    <div id="newImagePreview" class="mt-4 grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4" style="display: none;"></div>
                                </div>

                                {{-- Button --}}
                                <x-buttons.primary>
                                    Simpan Perubahan
                                </x-buttons.primary>
                        </x-form>
                    </div>
                </div>
            </article>
        </section>
    </main>
    @bukScripts(true)

    {{-- Image Management Scripts --}}
    <script>
        let removedImageIds = [];

        // Remove existing image
        function removeExistingImage(imageId) {
            if (confirm('Apakah Anda yakin ingin menghapus gambar ini?')) {
                const imageElement = document.getElementById('image-' + imageId);
                if (imageElement) {
                    imageElement.remove();
                    removedImageIds.push(imageId);
                    document.getElementById('removedImages').value = removedImageIds.join(',');

                    // Update image count validation
                    validateImageCount();
                }
            }
        }

        // Preview new images
        function previewNewImages(input) {
            const preview = document.getElementById('newImagePreview');
            const files = input.files;

            // Clear previous previews
            preview.innerHTML = '';

            if (files.length === 0) {
                preview.style.display = 'none';
                return;
            }

            // Check total image count (existing + new - removed)
            const currentImageCount = document.querySelectorAll('#currentImages > div').length;
            const totalCount = currentImageCount + files.length;

            if (totalCount > 5) {
                alert(`Maksimal 5 gambar per thread. Saat ini ada ${currentImageCount} gambar. Anda hanya bisa menambah ${5 - currentImageCount} gambar lagi.`);
                input.value = '';
                return;
            }

            // Check total size limit (15MB)
            let totalSize = 0;
            for (let file of files) {
                totalSize += file.size;
            }

            if (totalSize > 15728640) { // 15MB in bytes
                alert('Total ukuran gambar baru tidak boleh lebih dari 15MB.');
                input.value = '';
                return;
            }

            preview.style.display = 'grid';

            // Show preview for each new image
            Array.from(files).forEach((file, index) => {
                if (file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const div = document.createElement('div');
                        div.className = 'relative group';
                        div.innerHTML = `
                            <img src="${e.target.result}"
                                 class="w-full h-32 object-cover rounded-lg border border-green-200"
                                 alt="Preview ${index + 1}">
                            <div class="absolute top-0 left-0 bg-green-500 text-white text-xs px-2 py-1 rounded-br-lg">
                                BARU
                            </div>
                            <button type="button"
                                    onclick="removeNewImage(${index})"
                                    class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-6 h-6 text-xs flex items-center justify-center hover:bg-red-600">
                                ×
                            </button>
                            <p class="text-xs text-gray-500 mt-1 truncate">${file.name}</p>
                            <p class="text-xs text-gray-400">${formatFileSize(file.size)}</p>
                        `;
                        preview.appendChild(div);
                    };
                    reader.readAsDataURL(file);
                }
            });
        }

        function removeNewImage(index) {
            const input = document.getElementById('images');
            const dt = new DataTransfer();

            for (let i = 0; i < input.files.length; i++) {
                if (i !== index) {
                    dt.items.add(input.files[i]);
                }
            }

            input.files = dt.files;
            previewNewImages(input);
        }

        function validateImageCount() {
            const currentImageCount = document.querySelectorAll('#currentImages > div').length;
            const newImageInput = document.getElementById('images');
            const maxNewImages = 5 - currentImageCount;

            if (maxNewImages <= 0) {
                newImageInput.disabled = true;
                newImageInput.parentElement.querySelector('p').textContent = 'Maksimal 5 gambar sudah tercapai. Hapus gambar yang ada untuk menambah yang baru.';
            } else {
                newImageInput.disabled = false;
                newImageInput.parentElement.querySelector('p').textContent = `Maksimal ${maxNewImages} gambar lagi bisa ditambahkan. Total ukuran maksimal 15MB. Format: JPEG, PNG, JPG, GIF, WebP.`;
            }
        }

        function formatFileSize(bytes) {
            if (bytes === 0) return '0 Bytes';
            const k = 1024;
            const sizes = ['Bytes', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
        }

        // Initialize validation on page load
        document.addEventListener('DOMContentLoaded', function() {
            validateImageCount();
        });
    </script>
</x-guest-layout>
