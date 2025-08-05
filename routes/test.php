<?php

use Illuminate\Support\Facades\Route;
use Intervention\Image\Facades\Image;

// Test route for lazy loading
Route::get('/lazy-test', function () {
    return view('test.lazy-loading');
})->name('lazy.test');

// Test route untuk thumbnail
Route::get('/thumbnail-test', function () {
    $threads = \App\Models\Thread::with(['media', 'images'])->whereHas('media')->take(3)->get();
    return view('test.thumbnail-test', compact('threads'));
})->name('thumbnail.test');

// Test route for image processing
Route::get('/test-image', function() {
    try {
        // Test if Intervention Image is working
        $image = Image::canvas(100, 100, '#ff0000');

        return response()->json([
            'status' => 'success',
            'message' => 'Intervention Image is working!',
            'width' => $image->width(),
            'height' => $image->height()
        ]);
    } catch (Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => $e->getMessage()
        ]);
    }
});
