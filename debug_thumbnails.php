<?php

require_once __DIR__ . '/vendor/autoload.php';

use App\Models\Thread;
use App\Models\Media;

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== THUMBNAIL DEBUG ===\n\n";

// Get thread with images
$threads = Thread::with(['media', 'images'])->whereHas('media')->take(3)->get();

foreach ($threads as $thread) {
    echo "Thread: {$thread->title}\n";
    echo "Thread ID: {$thread->id}\n";

    $images = $thread->images->count() > 0 ? $thread->images : $thread->media->where('mime_type', 'LIKE', 'image/%');

    foreach ($images as $image) {
        echo "  Image: {$image->original_filename}\n";
        echo "  Original URL: {$image->url}\n";
        echo "  Thumbnail URL: {$image->thumbnail_url}\n";
        echo "  Original Path: {$image->path}\n";

        // Check if files exist
        $originalPath = storage_path('app/public/' . $image->path);
        $pathInfo = pathinfo($image->path);
        $thumbnailPath = storage_path('app/public/' . $pathInfo['dirname'] . '/thumbnails/' . $pathInfo['basename']);

        echo "  Original exists: " . (file_exists($originalPath) ? 'YES' : 'NO') . "\n";
        echo "  Thumbnail exists: " . (file_exists($thumbnailPath) ? 'YES' : 'NO') . "\n";
        echo "  Original size: " . (file_exists($originalPath) ? filesize($originalPath) . ' bytes' : 'N/A') . "\n";
        echo "  Thumbnail size: " . (file_exists($thumbnailPath) ? filesize($thumbnailPath) . ' bytes' : 'N/A') . "\n";
        echo "  ---\n";
    }
    echo "\n";
}

echo "=== END DEBUG ===\n";
