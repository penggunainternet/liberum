<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Media extends Model
{
    use HasFactory;

    protected $fillable = [
        'filename',
        'original_filename',
        'path',
        'mime_type',
        'size',
        'mediable_type',
        'mediable_id',
    ];

    protected $casts = [
        'size' => 'integer',
    ];

    /**
     * Get the parent mediable model (Thread, Reply, etc.)
     */
    public function mediable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Get the full URL of the media file
     */
    public function getUrlAttribute(): string
    {
        return asset('storage/' . $this->path);
    }

    /**
     * Get thumbnail URL if exists
     */
    public function getThumbnailUrlAttribute(): string
    {
        $pathInfo = pathinfo($this->path);
        $thumbnailPath = $pathInfo['dirname'] . '/thumbnails/' . $pathInfo['basename'];

        if (file_exists(storage_path('app/public/' . $thumbnailPath))) {
            return asset('storage/' . $thumbnailPath);
        }

        return $this->url; // Fallback to original image
    }

    /**
     * Get human readable file size
     */
    public function getFormattedSizeAttribute(): string
    {
        $bytes = $this->size;

        if ($bytes >= 1073741824) {
            return number_format($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            return number_format($bytes / 1024, 2) . ' KB';
        } else {
            return $bytes . ' bytes';
        }
    }

    /**
     * Check if media is an image
     */
    public function isImage(): bool
    {
        return str_starts_with($this->mime_type, 'image/');
    }
}
