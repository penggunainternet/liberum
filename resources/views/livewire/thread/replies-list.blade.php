<div>
    <div class="mt-6 space-y-5">
        <h2 class="mb-0 text-sm font-bold uppercase">Komentar</h2>
        <hr>
        @forelse($replies as $reply)
            <livewire:reply.update :reply="$reply" :wire:key="$reply->id()" />
        @empty
            <div class="text-gray-500 text-center py-8">
                <p>Belum ada komentar. Jadilah yang pertama berkomentar!</p>
            </div>
        @endforelse
    </div>
</div>
