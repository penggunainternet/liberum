<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== DEBUGGING THREAD UPDATE ===\n";

// Check current thread statuses by author_id
$adminThreads = App\Models\Thread::where('author_id', 1)->get();
echo "Admin threads found: " . $adminThreads->count() . "\n";

foreach($adminThreads as $thread) {
    echo "Thread ID: {$thread->id} - Title: {$thread->title} - Current Status: {$thread->status}\n";
}

echo "\nTrying to update one by one...\n";
foreach($adminThreads as $thread) {
    $oldStatus = $thread->status;
    $thread->status = 'approved';
    $thread->approved_at = now();
    $thread->approved_by = 1;
    $saved = $thread->save();
    echo "Thread {$thread->id}: {$oldStatus} -> approved (saved: " . ($saved ? 'YES' : 'NO') . ")\n";
}

echo "\n=== FINAL STATUS CHECK ===\n";
echo "Pending: " . App\Models\Thread::pending()->count() . "\n";
echo "Approved: " . App\Models\Thread::approved()->count() . "\n";
echo "Rejected: " . App\Models\Thread::rejected()->count() . "\n";
