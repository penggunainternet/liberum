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
                            <x-user.avatar :user="$author" />
                        </div>
                        <p>
                            {{ $replyOrigBody }}
                        </p>

                        {{-- Reply Images Gallery --}}
                        @if($reply->images->count() > 0)
                            <div class="mt-4">
                                <h5 class="text-xs font-medium text-gray-600 mb-2">
                                    Gambar Reply ({{ $reply->images->count() }})
                                </h5>
                                <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                                    @foreach($reply->images as $image)
                                        <div class="relative group cursor-pointer" onclick="openImageModal('{{ $image->url }}', '{{ $image->original_filename }}')">
                                            <img src="{{ $image->url }}"
                                                 alt="{{ $image->original_filename }}"
                                                 class="w-full h-24 object-cover rounded border border-gray-200 hover:border-blue-300 transition-colors">
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
                    {{ __('Edit') }}
                </x-links.secondary>
                @endcan

                @can(App\Policies\ReplyPolicy::DELETE, App\Models\Reply::find($replyId))
                <livewire:reply.delete :replyId="$replyId" :wire:key="$replyId" :page="request()->fullUrl()" />
                @endcan
            </div>

        </div>

        <div x-show="editReply">

            <form wire:submit.prevent="updateReply">
                <input class="w-full bg-gray-100 border-none shadow-inner focus:ring-blue-500" type="text" name="replyNewBody" wire:model.lazy="replyNewBody" x-ref="textInput" x-on:keydown.enter="editReply = false" x-on:keydown.escape="editReply = false">

                <div class="mt-2 space-x-3 text-sm">
                    <button type="button" class="text-green-400" x-on:click="editReply = false">
                        Cancel
                    </button>
                    <button type="submit" class="text-red-400" x-on:click="editReply = false">
                        Save
                    </button>
                </div>
            </form>
        </div>

    </div>

</div>
