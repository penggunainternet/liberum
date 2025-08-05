<?php

require_once 'vendor/autoload.php';

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use App\Models\User;

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$request = Request::capture();
$response = $kernel->handle($request);

echo "=== TESTING PROFILE PHOTO SYSTEM ===\n\n";

// Test 1: Check if profile photos feature is enabled
echo "1. Profile Photos Feature Status:\n";
$profilePhotosEnabled = Laravel\Jetstream\Jetstream::managesProfilePhotos();
echo "   Profile photos enabled: " . ($profilePhotosEnabled ? 'YES' : 'NO') . "\n";

// Test 2: Check storage configuration
echo "\n2. Storage Configuration:\n";
$profilePhotoDisk = config('jetstream.profile_photo_disk', 'public');
echo "   Profile photo disk: $profilePhotoDisk\n";

$publicDiskConfig = config('filesystems.disks.public');
echo "   Public disk driver: " . $publicDiskConfig['driver'] . "\n";
echo "   Public disk root: " . $publicDiskConfig['root'] . "\n";
echo "   Public disk URL: " . $publicDiskConfig['url'] . "\n";

// Test 3: Check storage link
echo "\n3. Storage Link Status:\n";
$storageLink = public_path('storage');
$storageExists = is_link($storageLink) || is_dir($storageLink);
echo "   Storage link exists: " . ($storageExists ? 'YES' : 'NO') . "\n";
if ($storageExists) {
    echo "   Storage link path: " . realpath($storageLink) . "\n";
}

// Test 4: Test with actual user
echo "\n4. User Profile Photo Testing:\n";
try {
    $user = User::first();
    if ($user) {
        echo "   User found: " . $user->name . "\n";
        echo "   Profile photo path: " . ($user->profile_photo_path ?? 'NULL') . "\n";
        echo "   Profile photo URL: " . $user->profile_photo_url . "\n";

        // Check if profile photo file exists
        if ($user->profile_photo_path) {
            $photoPath = storage_path('app/public/' . $user->profile_photo_path);
            echo "   Photo file exists: " . (file_exists($photoPath) ? 'YES' : 'NO') . "\n";
            echo "   Photo file path: $photoPath\n";
        }
    } else {
        echo "   No users found in database\n";
    }
} catch (Exception $e) {
    echo "   Error: " . $e->getMessage() . "\n";
}

// Test 5: Check profile-photos directory
echo "\n5. Profile Photos Directory:\n";
$profilePhotosDir = storage_path('app/public/profile-photos');
echo "   Directory exists: " . (is_dir($profilePhotosDir) ? 'YES' : 'NO') . "\n";
if (is_dir($profilePhotosDir)) {
    $files = scandir($profilePhotosDir);
    $photoFiles = array_filter($files, function($file) {
        return !in_array($file, ['.', '..']);
    });
    echo "   Photo files count: " . count($photoFiles) . "\n";
    if (count($photoFiles) > 0) {
        echo "   Photo files: " . implode(', ', array_slice($photoFiles, 0, 5)) . "\n";
    }
}

// Test 6: Test default avatar
echo "\n6. Default Avatar Testing:\n";
$defaultAvatarPath = public_path('img/default-avatar.svg');
echo "   Default avatar exists: " . (file_exists($defaultAvatarPath) ? 'YES' : 'NO') . "\n";
if (file_exists($defaultAvatarPath)) {
    echo "   Default avatar size: " . filesize($defaultAvatarPath) . " bytes\n";
}

echo "\n=== TEST COMPLETED ===\n";
