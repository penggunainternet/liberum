<?php

namespace App\Traits;

use App\Models\Media;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

trait HasMedia
{
    /**
     * Get all media for this model
     */
    public function media(): MorphMany
    {
        return $this->morphMany(Media::class, 'mediable');
    }

    /**
     * Get only images
     */
    public function images(): MorphMany
    {
        return $this->media()->where('mime_type', 'LIKE', 'image/%');
    }

    /**
     * Add media file from upload with image optimization
     */
    public function addMedia(UploadedFile $file, string $collection = 'default'): Media
    {
        $filename = $this->generateUniqueFilename($file);
        $path = $collection . '/' . $filename;
        $fullPath = storage_path('app/public/' . $path);

        // Ensure directory exists
        $directory = dirname($fullPath);
        if (!file_exists($directory)) {
            mkdir($directory, 0755, true);
        }

        $optimizedSize = $file->getSize(); // Default to original size

        if ($this->isImageFile($file)) {
            // Process image with Intervention Image
            $image = Image::make($file);

            // Get original dimensions
            $originalWidth = $image->width();
            $originalHeight = $image->height();

            // Resize if image is too large (max width: 1920px, max height: 1080px)
            if ($originalWidth > 1920 || $originalHeight > 1080) {
                $image->resize(1920, 1080, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize(); // Prevent upsizing
                });
            }

            // Compress image based on file type
            $quality = $this->getCompressionQuality($file->getMimeType());

            if (in_array($file->getMimeType(), ['image/jpeg', 'image/jpg'])) {
                $image->encode('jpg', $quality);
            } elseif ($file->getMimeType() === 'image/png') {
                $image->encode('png');
            } elseif ($file->getMimeType() === 'image/webp') {
                $image->encode('webp', $quality);
            }

            // Save optimized image
            $image->save($fullPath);

            // Create thumbnail
            $this->createThumbnail($image, $collection, $filename);

            // Get file size after optimization
            $optimizedSize = filesize($fullPath);
        } else {
            // Store non-image files as is
            Storage::disk('public')->put($path, file_get_contents($file));
        }

        // Create media record
        return $this->media()->create([
            'filename' => $filename,
            'original_filename' => $file->getClientOriginalName(),
            'path' => $path,
            'mime_type' => $file->getMimeType(),
            'size' => $optimizedSize,
        ]);
    }

    /**
     * Create thumbnail for image
     */
    private function createThumbnail($image, string $collection, string $filename): void
    {
        $thumbnailDir = $collection . '/thumbnails/';
        $thumbnailPath = storage_path('app/public/' . $thumbnailDir);

        // Ensure thumbnail directory exists
        if (!file_exists($thumbnailPath)) {
            mkdir($thumbnailPath, 0755, true);
        }

        // Create thumbnail (300x300 max)
        $thumbnail = clone $image;
        $thumbnail->resize(300, 300, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        });

        $thumbnailFilePath = $thumbnailPath . $filename;
        $thumbnail->save($thumbnailFilePath, 80); // 80% quality for thumbnails
    }

    /**
     * Get compression quality based on mime type
     */
    private function getCompressionQuality(string $mimeType): int
    {
        return match ($mimeType) {
            'image/jpeg', 'image/jpg' => 85, // Good balance between quality and size
            'image/webp' => 80,
            'image/png' => 9, // PNG compression level (0-9)
            default => 85
        };
    }

    /**
     * Check if file is an image
     */
    private function isImageFile(UploadedFile $file): bool
    {
        return str_starts_with($file->getMimeType(), 'image/');
    }

    /**
     * Generate unique filename
     */
    private function generateUniqueFilename(UploadedFile $file): string
    {
        $extension = $file->getClientOriginalExtension();
        return Str::random(40) . '.' . $extension;
    }

    /**
     * Add multiple media files
     */
    public function addMediaFromArray(array $files, string $collection = 'default'): array
    {
        $mediaItems = [];
        foreach ($files as $file) {
            if ($file instanceof UploadedFile) {
                $mediaItems[] = $this->addMedia($file, $collection);
            }
        }
        return $mediaItems;
    }

    /**
     * Delete media and file
     */
    public function deleteMedia(Media $media): bool
    {
        // Delete file from storage
        if (Storage::disk('public')->exists($media->path)) {
            Storage::disk('public')->delete($media->path);
        }

        // Delete database record
        return $media->delete();
    }

    /**
     * Delete all media
     */
    public function clearMedia(): void
    {
        foreach ($this->media as $media) {
            $this->deleteMedia($media);
        }
    }

    /**
     * Get total size of all media
     */
    public function getTotalMediaSize(): int
    {
        return $this->media()->sum('size');
    }

    /**
     * Check if can add more media based on limits
     */
    public function canAddMedia(int $maxCount = 5, int $maxTotalSize = 15728640): array // 15MB in bytes
    {
        $currentCount = $this->media()->count();
        $currentSize = $this->getTotalMediaSize();

        return [
            'can_add' => $currentCount < $maxCount && $currentSize < $maxTotalSize,
            'current_count' => $currentCount,
            'max_count' => $maxCount,
            'current_size' => $currentSize,
            'max_size' => $maxTotalSize,
            'remaining_count' => max(0, $maxCount - $currentCount),
            'remaining_size' => max(0, $maxTotalSize - $currentSize),
        ];
    }
}
