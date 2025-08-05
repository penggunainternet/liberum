<?php

require_once __DIR__ . '/vendor/autoload.php';

use App\Models\Thread;
use App\Jobs\UpdateThread;
use App\Http\Requests\ThreadStoreRequest;

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== TEST UPDATE THREAD SLUG ===\n\n";

// Ambil thread yang ada
$thread = Thread::where('slug', 'tips-menulis-cerpen')->first();

if (!$thread) {
    echo "Error: Thread not found!\n";
    exit;
}

echo "Original thread:\n";
echo "ID: {$thread->id}\n";
echo "Title: {$thread->title}\n";
echo "Slug: {$thread->slug}\n\n";

// Test update dengan judul yang sudah ada
echo "=== TESTING UPDATE TO EXISTING TITLE ===\n";
echo "Trying to update to: 'Tips Menulis Cerpen' (same as current)\n";

try {
    $updateJob = new UpdateThread(
        $thread,
        'Tips Menulis Cerpen', // Same title
        'Updated description',
        null, // same category
        [] // no images
    );

    $updatedThread = $updateJob->handle();
    echo "✅ Success! Updated slug: {$updatedThread->slug}\n";
    echo "   (Should remain the same because it's the same thread)\n\n";

} catch (Exception $e) {
    echo "❌ Error: {$e->getMessage()}\n\n";
}

// Test update dengan judul yang konflik dengan thread lain
echo "=== TESTING UPDATE TO CONFLICTING TITLE ===\n";
echo "Trying to update to: 'Review Buku Terbaru' (already exists)\n";

try {
    $updateJob = new UpdateThread(
        $thread,
        'Review Buku Terbaru', // Title that already exists
        'Updated description',
        null, // same category
        [] // no images
    );

    $updatedThread = $updateJob->handle();
    echo "✅ Success! Updated slug: {$updatedThread->slug}\n";
    echo "   (Should add number suffix to avoid conflict)\n\n";

} catch (Exception $e) {
    echo "❌ Error: {$e->getMessage()}\n\n";
}

// Show final state
echo "=== FINAL THREAD STATE ===\n";
$thread->refresh();
echo "ID: {$thread->id}\n";
echo "Title: {$thread->title}\n";
echo "Slug: {$thread->slug}\n";

echo "\n=== TEST COMPLETED ===\n";
