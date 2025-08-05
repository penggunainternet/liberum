<?php

require_once __DIR__ . '/vendor/autoload.php';

use App\Models\Thread;
use App\Models\User;
use App\Models\Category;
use App\Jobs\CreateThread;
use Illuminate\Support\Str;

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== TEST DUPLICATE SLUG HANDLING ===\n\n";

// Cari user admin dan category
$admin = User::where('email', 'admin@example.com')->first();
$category = Category::first();

if (!$admin || !$category) {
    echo "Error: Admin user or category not found!\n";
    exit;
}

echo "Admin: {$admin->name} (ID: {$admin->id})\n";
echo "Category: {$category->name} (ID: {$category->id})\n\n";

// Test dengan judul yang sama
$titles = [
    "Tips Menulis Cerpen",
    "Tips Menulis Cerpen", // Duplikat
    "Tips Menulis Cerpen", // Duplikat lagi
    "Review Buku Terbaru",
    "Review Buku Terbaru", // Duplikat
];

echo "=== CREATING THREADS WITH DUPLICATE TITLES ===\n\n";

foreach ($titles as $index => $title) {
    echo "Creating thread #" . ($index + 1) . ": '$title'\n";

    try {
        $job = new CreateThread(
            $title,
            "Ini adalah deskripsi untuk thread: $title",
            $category->id,
            $admin,
            [] // no images
        );

        $thread = $job->handle();
        echo "✅ Success! Slug: {$thread->slug}\n";
        echo "   URL: /threads/{$category->slug}/{$thread->slug}\n\n";

    } catch (Exception $e) {
        echo "❌ Error: {$e->getMessage()}\n\n";
    }
}

echo "=== FINAL THREADS LIST ===\n";
foreach (Thread::orderBy('id', 'desc')->take(10)->get() as $thread) {
    echo "ID {$thread->id}: {$thread->title} -> {$thread->slug}\n";
}

echo "\n=== TEST COMPLETED ===\n";
