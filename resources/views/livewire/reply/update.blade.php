<div>

    <div x-data="
    {
        editReply:false,
        focus: function() {
            const textInput = this.$refs.textInput;
            textInput.focus();
        }
    }" x-cloak>

        <div x-show="!editReply" class="relative">

            <div class="p-5 space-y-4 text-gray-500 bg-white border-l-4 border-blue-300 shadow rounded-lg">
                <div class="grid grid-cols-8">


                    <div class="relative col-span-7 space-y-4">
                         {{-- Avatar --}}
                        <div class="col-span-1">
                            @if($author)
                                <x-user.avatar :user="$author" />
                            @else
                                <div class="w-8 h-8 bg-gray-300 rounded-full flex items-center justify-center">
                                    <span class="text-xs text-gray-600">?</span>
                                </div>
                            @endif
                        </div>
                        <p>
                            {{ $replyOrigBody }}
                        </p>

                        {{-- Reply Images Gallery --}}
                        @php
                            $displayImages = $reply->images->count() > 0 ? $reply->images : $reply->media->where('mime_type', 'LIKE', 'image/%');
                        @endphp

                        @if($displayImages->count() > 0)
                            <div class="mt-4">
                                <h5 class="text-xs font-medium text-gray-600 mb-2">
                                    Gambar Reply ({{ $displayImages->count() }})
                                </h5>
                                <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                                    @foreach($displayImages as $image)
                                        <div class="relative group cursor-pointer" onclick="openImageModal('{{ $image->url }}', '{{ $image->original_filename }}')">
                                            <x-lazy-image
                                                src="{{ $image->url }}"
                                                alt="{{ $image->original_filename }}"
                                                class="w-full h-24 object-cover rounded border border-gray-200 hover:border-blue-300 transition-colors"
                                                loadingClass="animate-pulse bg-gray-200 rounded"
                                                placeholder="true"
                                            />
                                            <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-20 transition-opacity rounded flex items-center justify-center">
                                                <svg class="w-4 h-4 text-white opacity-0 group-hover:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"></path>
                                                </svg>
                                            </div>
                                            <p class="text-xs text-gray-500 mt-1 truncate">{{ $image->original_filename }}</p>
                                            <p class="text-xs text-gray-400">{{ $image->formatted_size }}</p>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <div class=" flex justify-between w-full bottom-1">

                            {{-- Likes --}}
                            <div class="flex space-x-5 text-gray-500">
                                <livewire:like-reply :reply='App\Models\Reply::find($replyId)'>
                            </div>

                            {{-- Date Posted --}}
                            <div class="flex items-center text-xs text-gray-500">
                                <x-heroicon-o-clock class="w-4 h-4 mr-1" />
                                Dibalas: {{ $createdAt->diffForHumans() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="absolute flex space-x-3 top-4 right-4">
                @can(App\Policies\ReplyPolicy::UPDATE, App\Models\Reply::find($replyId))
                <x-links.secondary x-on:click="editReply = true; $nextTick(() => focus())" class="cursor-pointer">
                    Edit
                </x-links.secondary>
                @endcan

                @can(App\Policies\ReplyPolicy::DELETE, App\Models\Reply::find($replyId))
                <livewire:reply.delete :replyId="$replyId" :wire:key="$replyId" :page="request()->fullUrl()" />
                @endcan
            </div>

        </div>

        <div x-show="editReply">
            <form wire:submit.prevent="updateReply" enctype="multipart/form-data">
                <div class="space-y-4">
                    {{-- Reply Text --}}
                    <textarea class="w-full bg-gray-100 border-none shadow-inner focus:ring-blue-500 resize-none"
                              rows="3"
                              name="replyNewBody"
                              wire:model.lazy="replyNewBody"
                              x-ref="textInput"
                              placeholder="Edit balasan Anda..."></textarea>

                    {{-- Current Images --}}
                    @php
                        $currentImages = $reply->images->count() > 0 ? $reply->images : $reply->media->where('mime_type', 'LIKE', 'image/%');
                    @endphp

                    @if($currentImages->count() > 0)
                        <div class="space-y-2">
                            <h6 class="text-sm font-medium text-gray-700">Gambar Saat Ini:</h6>
                            <div class="grid grid-cols-3 gap-2">
                                @foreach($currentImages as $image)
                                    <div class="relative">
                                        <x-lazy-image
                                            src="{{ $image->url }}"
                                            alt="{{ $image->original_filename }}"
                                            class="w-full h-20 object-cover rounded border"
                                            loadingClass="animate-pulse bg-gray-200 rounded"
                                            placeholder="true"
                                        />
                                        <button type="button"
                                                wire:click="removeImage({{ $image->id }})"
                                                class="absolute top-1 right-1 bg-red-500 text-white rounded-full w-5 h-5 flex items-center justify-center text-xs hover:bg-red-600">
                                            ×
                                        </button>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- New Image Upload --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tambah Gambar Baru:</label>
                        <input type="file"
                               wire:model="images"
                               multiple
                               accept="image/*"
                               class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                        @error('images.*')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Preview New Images --}}
                    @if($images)
                        <div class="space-y-2">
                            <h6 class="text-sm font-medium text-gray-700">Preview Gambar Baru:</h6>
                            <div class="grid grid-cols-3 gap-2">
                                @foreach($images as $index => $image)
                                    <div class="relative">
                                        <img src="{{ $image->temporaryUrl() }}"
                                             class="w-full h-20 object-cover rounded border">
                                        <button type="button"
                                                wire:click="$set('images.{{ $index }}', null)"
                                                class="absolute top-1 right-1 bg-red-500 text-white rounded-full w-5 h-5 flex items-center justify-center text-xs hover:bg-red-600">
                                            ×
                                        </button>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- Action Buttons --}}
                    <div class="flex space-x-3 text-sm">
                        <button type="button"
                                class="px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400"
                                x-on:click="editReply = false">
                            Batal
                        </button>
                        <button type="submit"
                                class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600"
                                x-on:click="editReply = false">
                            Simpan Perubahan
                        </button>
                    </div>
                </div>
            </form>
        </div>

    </div>

</div>
