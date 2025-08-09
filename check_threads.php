<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== USERS ===\n";
foreach(App\Models\User::all() as $user) {
    echo $user->id . ": " . $user->name . " (type: " . $user->type . ", isAdmin: " . ($user->isAdmin() ? 'YES' : 'NO') . ")\n";
}

echo "\n=== THREAD STATUS CHECK ===\n";
echo "Pending: " . App\Models\Thread::pending()->count() . "\n";
echo "Approved: " . App\Models\Thread::approved()->count() . "\n";
echo "Rejected: " . App\Models\Thread::rejected()->count() . "\n\n";

echo "=== ALL THREADS ===\n";
foreach(App\Models\Thread::all() as $thread) {
    $author = $thread->author()->first();
    echo $thread->title . " - Status: " . $thread->status . " - Author: " . ($author ? $author->name . " (ID: " . $author->id . ")" : 'Unknown') . "\n";
}
