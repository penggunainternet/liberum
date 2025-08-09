<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== ALL THREADS WITH AUTHOR DETAILS ===\n";

foreach(App\Models\Thread::all() as $thread) {
    echo "Thread ID: {$thread->id} - Title: {$thread->title} - Status: {$thread->status} - Author ID: {$thread->author_id}\n";
}

echo "\n=== ADMIN USER CHECK ===\n";
$admin = App\Models\User::where('type', 3)->first();
if ($admin) {
    echo "Admin user found: ID {$admin->id} - {$admin->name}\n";
    $adminThreads = App\Models\Thread::where('author_id', $admin->id)->get();
    echo "Admin threads count: " . $adminThreads->count() . "\n";
} else {
    echo "No admin user found!\n";
}
