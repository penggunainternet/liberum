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

echo "=== ASSIGN PROFILE PHOTO TO USER ===\n\n";

try {
    $user = User::first();
    if ($user) {
        echo "User found: " . $user->name . "\n";
        echo "Current profile photo path: " . ($user->profile_photo_path ?? 'NULL') . "\n";

        // Assign the existing profile photo
        $user->profile_photo_path = 'profile-photos/VlRk89wNWcyO09bMqXKWnBDNJpnPMTx5QSOWBouT.jpg';
        $user->save();

        echo "Profile photo assigned successfully!\n";
        echo "New profile photo path: " . $user->profile_photo_path . "\n";
        echo "New profile photo URL: " . $user->profile_photo_url . "\n";

        // Verify the file exists
        $photoPath = storage_path('app/public/' . $user->profile_photo_path);
        echo "Photo file exists: " . (file_exists($photoPath) ? 'YES' : 'NO') . "\n";

    } else {
        echo "No users found in database\n";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

echo "\n=== COMPLETED ===\n";
