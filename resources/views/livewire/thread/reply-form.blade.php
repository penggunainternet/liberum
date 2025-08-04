<div>
    @auth
    <div class="p-5 space-y-4 bg-white shadow rounded-lg">
        <h2 class="text-gray-500">Kirim komentar</h2>

        @if (session()->has('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                {{ session('success') }}
            </div>
        @endif

        <form wire:submit.prevent="submitReply">
            <div>
                <input type="text"
                       wire:model.defer="body"
                       class="w-full rounded-md bg-gray-200 border-none shadow-inner focus:ring-blue-400"
                       placeholder="Tulis komentar Anda..." />
                @error('body') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            {{-- Image Upload --}}
            <div class="space-y-3">
                <div class="flex items-center justify-between">
                    <label class="block text-sm font-medium text-gray-700">
                        Gambar Reply (Opsional)
                    </label>
                    <span class="text-xs text-gray-500">Maks 3 gambar, 5MB per gambar</span>
                </div>

                <input type="file"
                       wire:model="images"
                       multiple
                       accept="image/*"
                       class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">

                @error('images.*') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror

                {{-- Image Previews --}}
                @if ($images)
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-3 mt-3">
                        @foreach ($images as $index => $image)
                            <div class="relative">
                                <img src="{{ $image->temporaryUrl() }}"
                                     alt="Preview"
                                     class="w-full h-24 object-cover rounded border">
                                <button type="button"
                                        wire:click="$set('images.{{ $index }}', null)"
                                        class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs hover:bg-red-600">
                                    ×
                                </button>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <div class="grid mt-4">
                <button type="submit"
                        class="justify-self-end px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                    <span wire:loading.remove wire:target="submitReply">Kirim</span>
                    <span wire:loading wire:target="submitReply">Mengirim...</span>
                </button>
            </div>
        </form>
    </div>
    @else
        <div class="p-5 bg-gray-100 rounded-lg text-center">
            <p class="text-gray-600">
                <a href="{{ route('login') }}" class="text-blue-600 hover:underline">Login</a>
                untuk memberikan komentar.
            </p>
        </div>
    @endauth
</div>
