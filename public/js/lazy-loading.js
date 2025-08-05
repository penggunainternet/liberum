/**
 * Advanced Lazy Loading Implementation for Liberum Forum
 * Optimized for performance and user experience
 */

class LazyImageLoader {
    constructor() {
        this.observer = null;
        this.images = [];
        this.loadedImages = new Set();
        this.errorImages = new Set();
        this.init();
    }

    init() {
        if ("IntersectionObserver" in window) {
            this.setupIntersectionObserver();
        } else {
            this.fallbackLoad();
        }

        // Load images that are already in viewport
        this.checkInitialViewport();
    }

    setupIntersectionObserver() {
        const options = {
            root: null,
            rootMargin: "100px 0px", // Start loading 100px before image enters viewport
            threshold: 0.1,
        };

        this.observer = new IntersectionObserver((entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    this.loadImage(entry.target);
                    this.observer.unobserve(entry.target);
                }
            });
        }, options);

        // Observe all lazy images
        this.observeImages();
    }

    observeImages() {
        const lazyImages = document.querySelectorAll("[data-lazy-src]");
        lazyImages.forEach((img) => {
            this.observer.observe(img);
            this.images.push(img);
        });
    }

    loadImage(img) {
        const src = img.dataset.lazySrc;
        const placeholder = img.dataset.placeholder;

        // Show loading state
        this.showLoadingState(img);

        // Create new image element to test loading
        const imageLoader = new Image();

        imageLoader.onload = () => {
            this.onImageLoad(img, src);
        };

        imageLoader.onerror = () => {
            this.onImageError(img);
        };

        // Start loading
        imageLoader.src = src;
    }

    showLoadingState(img) {
        img.classList.add("lazy-loading");

        // Add loading animation if not present
        if (!img.nextElementSibling?.classList.contains("lazy-spinner")) {
            const spinner = this.createSpinner();
            img.parentNode.insertBefore(spinner, img.nextSibling);
        }
    }

    onImageLoad(img, src) {
        img.src = src;
        img.classList.remove("lazy-loading");
        img.classList.add("lazy-loaded");

        // Remove loading spinner
        const spinner = img.nextElementSibling;
        if (spinner?.classList.contains("lazy-spinner")) {
            spinner.remove();
        }

        // Add fade-in animation
        img.style.opacity = "0";
        img.style.transition = "opacity 0.3s ease-in-out";

        // Trigger reflow and fade in
        img.offsetHeight;
        img.style.opacity = "1";

        this.loadedImages.add(img);
    }

    onImageError(img) {
        img.classList.remove("lazy-loading");
        img.classList.add("lazy-error");

        // Remove loading spinner
        const spinner = img.nextElementSibling;
        if (spinner?.classList.contains("lazy-spinner")) {
            spinner.remove();
        }

        // Show error placeholder
        this.showErrorState(img);
        this.errorImages.add(img);
    }

    createSpinner() {
        const spinner = document.createElement("div");
        spinner.className =
            "lazy-spinner absolute inset-0 flex items-center justify-center bg-gray-100";
        spinner.innerHTML = `
            <svg class="animate-spin h-6 w-6 text-gray-400" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
        `;
        return spinner;
    }

    showErrorState(img) {
        const errorDiv = document.createElement("div");
        errorDiv.className =
            "lazy-error-state absolute inset-0 flex items-center justify-center bg-gray-100 text-gray-400";
        errorDiv.innerHTML = `
            <div class="text-center">
                <svg class="w-8 h-8 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <p class="text-xs">Gagal memuat</p>
            </div>
        `;
        img.parentNode.insertBefore(errorDiv, img.nextSibling);
    }

    checkInitialViewport() {
        const viewportHeight = window.innerHeight;
        this.images.forEach((img) => {
            const rect = img.getBoundingClientRect();
            if (rect.top < viewportHeight && rect.bottom > 0) {
                this.loadImage(img);
                if (this.observer) {
                    this.observer.unobserve(img);
                }
            }
        });
    }

    fallbackLoad() {
        // For browsers without IntersectionObserver support
        const lazyImages = document.querySelectorAll("[data-lazy-src]");
        lazyImages.forEach((img) => {
            this.loadImage(img);
        });
    }

    // Public method to manually trigger loading
    loadAll() {
        this.images.forEach((img) => {
            if (!this.loadedImages.has(img) && !this.errorImages.has(img)) {
                this.loadImage(img);
            }
        });
    }

    // Public method to reload failed images
    retryErrors() {
        this.errorImages.forEach((img) => {
            const errorState = img.nextElementSibling;
            if (errorState?.classList.contains("lazy-error-state")) {
                errorState.remove();
            }
            img.classList.remove("lazy-error");
            this.loadImage(img);
        });
        this.errorImages.clear();
    }

    // Get loading statistics
    getStats() {
        return {
            total: this.images.length,
            loaded: this.loadedImages.size,
            errors: this.errorImages.size,
            pending:
                this.images.length -
                this.loadedImages.size -
                this.errorImages.size,
        };
    }
}

// Auto-initialize when DOM is ready
document.addEventListener("DOMContentLoaded", () => {
    window.lazyLoader = new LazyImageLoader();
});

// Reinitialize on Livewire updates
document.addEventListener("livewire:load", () => {
    if (window.lazyLoader) {
        window.lazyLoader.observeImages();
    }
});

// Helper function for manual lazy loading setup
window.initLazyImage = function (img, src, placeholder = null) {
    img.dataset.lazySrc = src;
    if (placeholder) {
        img.dataset.placeholder = placeholder;
    }

    // Add to existing observer if available
    if (window.lazyLoader && window.lazyLoader.observer) {
        window.lazyLoader.observer.observe(img);
        window.lazyLoader.images.push(img);
    }
};
