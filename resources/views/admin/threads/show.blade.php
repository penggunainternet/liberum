<x-admin-layout>
    {{-- Header --}}
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-semibold leading-tight">
                {{ __('Detail Thread - Moderasi') }}
            </h2>
            <div class="flex space-x-4">
                <a href="{{ route('admin.threads.pending') }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200">
                    ← Kembali ke Daftar
                </a>
            </div>
        </div>
    </x-slot>

    <section class="px-6">
        <div class="bg-white border rounded-lg shadow-sm overflow-hidden">
            {{-- Thread Header --}}
            <div class="p-6 border-b bg-gray-50">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center space-x-3">
                        @if($thread->isPending())
                            <span class="px-3 py-1 text-sm bg-yellow-100 text-yellow-800 rounded-full font-medium">
                                Menunggu Persetujuan
                            </span>
                        @elseif($thread->isApproved())
                            <span class="px-3 py-1 text-sm bg-green-100 text-green-800 rounded-full font-medium">
                                ✓ Disetujui
                            </span>
                        @elseif($thread->isRejected())
                            <span class="px-3 py-1 text-sm bg-red-100 text-red-800 rounded-full font-medium">
                                ✗ Ditolak
                            </span>
                        @endif
                    </div>

                    @if($thread->isPending())
                        <div class="flex space-x-3">
                            <form action="{{ route('admin.threads.approve', $thread) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" onclick="return confirm('Apakah Anda yakin ingin menyetujui thread ini?')"
                                        class="px-6 py-2 bg-green-600 text-white rounded hover:bg-green-700">
                                    ✓ Setujui Thread
                                </button>
                            </form>

                            <form action="{{ route('admin.threads.reject', $thread) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" onclick="return confirm('Apakah Anda yakin ingin menolak thread ini?')"
                                        class="px-6 py-2 bg-red-600 text-white rounded hover:bg-red-700">
                                    ✗ Tolak Thread
                                </button>
                            </form>
                        </div>
                    @else
                        <div class="flex space-x-3">
                            @if($thread->isApproved())
                                <form action="{{ route('admin.threads.reject', $thread) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" onclick="return confirm('Apakah Anda yakin ingin mengubah status menjadi ditolak?')"
                                            class="px-6 py-2 bg-red-600 text-white rounded hover:bg-red-700">
                                        ✗ Ubah ke Ditolak
                                    </button>
                                </form>
                            @elseif($thread->isRejected())
                                <form action="{{ route('admin.threads.approve', $thread) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" onclick="return confirm('Apakah Anda yakin ingin menyetujui thread ini?')"
                                            class="px-6 py-2 bg-green-600 text-white rounded hover:bg-green-700">
                                        ✓ Setujui Thread
                                    </button>
                                </form>
                            @endif
                        </div>
                    @endif
                </div>

                <h1 class="text-2xl font-bold text-gray-900 mb-4">{{ $thread->title }}</h1>

                <div class="grid grid-cols-2 gap-6 text-sm text-gray-600">
                    <div>
                        <span class="font-medium">Penulis:</span> {{ $thread->author()->name }}<br>
                        <span class="font-medium">Email Penulis:</span> {{ $thread->author()->email }}<br>
                        <span class="font-medium">Kategori:</span> {{ $thread->category->name }}
                    </div>
                    <div>
                        <span class="font-medium">Dibuat:</span> {{ method_exists($thread->created_at, 'format') ? $thread->created_at->format('d M Y H:i') : $thread->created_at }}<br>
                        @if($thread->approved_at)
                            <span class="font-medium">Disetujui:</span> {{ method_exists($thread->approved_at, 'format') ? $thread->approved_at->format('d M Y H:i') : $thread->approved_at }}<br>
                        @endif
                        @if($thread->approvedBy)
                            <span class="font-medium">Oleh:</span> {{ $thread->approvedBy->name }}
                        @endif
                    </div>
                </div>
            </div>

            {{-- Thread Content --}}
            <div class="p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Isi Thread:</h3>
                <div class="prose max-w-none">
                    {!! $thread->body !!}
                </div>
            </div>

            {{-- Moderation Notes --}}
            <div class="p-6 border-t bg-gray-50">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Catatan Moderasi:</h3>
                <div class="space-y-3">
                    @if($thread->isPending())
                        <div class="flex items-center space-x-2 text-yellow-700">
                            <span class="w-2 h-2 bg-yellow-500 rounded-full"></span>
                            <span>Thread ini menunggu persetujuan dari admin.</span>
                        </div>
                    @elseif($thread->isApproved())
                        <div class="flex items-center space-x-2 text-green-700">
                            <span class="w-2 h-2 bg-green-500 rounded-full"></span>
                            <span>Thread ini telah disetujui dan dapat dilihat oleh public.</span>
                        </div>
                    @elseif($thread->isRejected())
                        <div class="flex items-center space-x-2 text-red-700">
                            <span class="w-2 h-2 bg-red-500 rounded-full"></span>
                            <span>Thread ini ditolak dan tidak dapat dilihat oleh public.</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
</x-admin-layout>
