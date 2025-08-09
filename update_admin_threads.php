<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "Updating admin threads to approved status...\n";

$updatedCount = App\Models\Thread::where('author_id', 1)
    ->update([
        'status' => 'approved',
        'approved_at' => now(),
        'approved_by' => 1
    ]);

echo "Updated {$updatedCount} threads to approved status.\n";

echo "\n=== UPDATED THREAD STATUS CHECK ===\n";
echo "Pending: " . App\Models\Thread::pending()->count() . "\n";
echo "Approved: " . App\Models\Thread::approved()->count() . "\n";
echo "Rejected: " . App\Models\Thread::rejected()->count() . "\n";
