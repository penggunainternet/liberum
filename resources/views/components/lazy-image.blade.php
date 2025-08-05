@props([
    'src',
    'alt' => '',
    'class' => '',
    'placeholder' => false,
    'loadingClass' => 'animate-pulse bg-gray-200'
])

@if($src)
    <div class="lazy-image-wrapper relative" {{ $attributes->except(['class']) }}>
        <!-- Image dengan native lazy loading -->
        <img
            src="{{ $src }}"
            alt="{{ $alt }}"
            class="lazy-image {{ $class }}"
            loading="lazy"
            onload="this.classList.add('loaded'); this.style.opacity='1'; if(this.nextElementSibling) this.nextElementSibling.style.display='none';"
            onerror="this.classList.add('error'); this.style.display='none'; if(this.parentElement.querySelector('.lazy-error-state')) this.parentElement.querySelector('.lazy-error-state').style.display='flex';"
            style="opacity: 0; transition: opacity 0.3s ease-in-out;"
        />

        <!-- Loading placeholder -->
        <div class="lazy-placeholder absolute inset-0 {{ $loadingClass }} flex items-center justify-center">
            @if($placeholder)
            <div class="text-gray-400">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                </svg>
            </div>
            @endif
        </div>

        <!-- Error state -->
        <div class="lazy-error-state absolute inset-0 bg-gray-100 text-gray-400 flex items-center justify-center rounded" style="display: none;">
            <div class="text-center">
                <svg class="w-4 h-4 mx-auto mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <p class="text-xs">Error</p>
            </div>
        </div>
    </div>
@else
    <!-- Fallback jika tidak ada src -->
    <div class="lazy-image-wrapper relative {{ $class }}" {{ $attributes->except(['class']) }}>
        <div class="bg-gray-200 flex items-center justify-center">
            <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
            </svg>
        </div>
    </div>
@endif
<style>

.lazy-image-wrapper {
    min-height: 2rem; /* Prevents layout shift */
}
</style>
