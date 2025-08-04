<div>
    @if(Auth::guest())
    <span class="flex items-center space-x-2">
        <x-heroicon-o-thumb-up class="w-5 h-5 text-red-300" />
        <span class="text-xs font-bold">{{ $this->reply->likesRelation()->count() }}</span>
    </span>
    @else
    <button wire:click="toggleLike" class="flex items-center space-x-2 cursor-pointer">
        <x-heroicon-o-thumb-up class="w-5 h-5 text-red-300" />
        <span class="text-xs font-bold">{{ $this->reply->likesRelation()->count() }}</span>
    </button>
    @endif
</div>
