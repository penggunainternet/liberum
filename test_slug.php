<?php

require_once __DIR__ . '/vendor/autoload.php';

use App\Models\Thread;
use Illuminate\Support\Str;

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== TEST SLUG GENERATION ===\n\n";

// Function untuk test unique slug
function generateUniqueSlug($title) {
    $baseSlug = Str::slug($title);
    $slug = $baseSlug;
    $counter = 1;

    while (Thread::where('slug', $slug)->exists()) {
        $slug = $baseSlug . '-' . $counter;
        $counter++;
    }

    return $slug;
}

// Test dengan judul yang sama
$title = "Tips Menulis Novel Yang Bagus";
echo "Testing title: '$title'\n";
echo "Current threads count: " . Thread::count() . "\n\n";

echo "Existing threads and their slugs:\n";
foreach (Thread::select('id', 'title', 'slug')->get() as $thread) {
    echo "ID {$thread->id}: {$thread->title} -> {$thread->slug}\n";
}

echo "\n--- Generating unique slugs ---\n";
echo "Slug 1: " . generateUniqueSlug($title) . "\n";
echo "Slug 2: " . generateUniqueSlug($title) . "\n";
echo "Slug 3: " . generateUniqueSlug($title) . "\n";

echo "\n=== TEST COMPLETED ===\n";
