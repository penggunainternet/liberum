
{{-- Sub Navigation for Thread Moderation --}}
@if(request()->routeIs('admin.threads.*'))
    <div class="bg-gray-50 border-b border-gray-200 mb-4">
        <div class="px-4">
            <nav class="flex space-x-4">
                <a href="{{ route('admin.threads.pending') }}"
                   class="py-2 text-sm font-medium {{ request()->routeIs('admin.threads.pending') ? 'text-yellow-700 border-b-2 border-yellow-500' : 'text-gray-600 hover:text-gray-900' }}">
                    ⏳ Pending
                    @php $pendingCount = App\Models\Thread::pending()->count(); @endphp
                    @if($pendingCount > 0)
                        <span class="ml-1 px-2 py-1 text-xs bg-yellow-100 text-yellow-800 rounded-full">{{ $pendingCount }}</span>
                    @endif
                </a>

                <a href="{{ route('admin.threads.approved') }}"
                   class="py-2 text-sm font-medium {{ request()->routeIs('admin.threads.approved') ? 'text-green-700 border-b-2 border-green-500' : 'text-gray-600 hover:text-gray-900' }}">
                    ✅ Approved
                    @php $approvedCount = App\Models\Thread::approved()->count(); @endphp
                    <span class="ml-1 px-2 py-1 text-xs bg-green-100 text-green-800 rounded-full">{{ $approvedCount }}</span>
                </a>

                <a href="{{ route('admin.threads.rejected') }}"
                   class="py-2 text-sm font-medium {{ request()->routeIs('admin.threads.rejected') ? 'text-red-700 border-b-2 border-red-500' : 'text-gray-600 hover:text-gray-900' }}">
                    ❌ Rejected
                    @php $rejectedCount = App\Models\Thread::rejected()->count(); @endphp
                    <span class="ml-1 px-2 py-1 text-xs bg-red-100 text-red-800 rounded-full">{{ $rejectedCount }}</span>
                </a>
            </nav>
        </div>
    </div>
@endif
