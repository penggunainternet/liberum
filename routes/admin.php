<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ThreadModerationController;

Route::group(['prefix' => 'admin', 'as' => 'admin.', 'middleware' => ['auth', 'verified', 'isAdmin']], function () {
    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('index');

    // Users Management
    Route::group(['prefix' => 'users', 'as' => 'users.'], function () {
        Route::get('active', [DashboardController::class, 'getActiveUser'])->name('active');
        Route::get('{user}/promote', [DashboardController::class, 'promoteUser'])->name('promote');
        Route::get('{user}/demote', [DashboardController::class, 'demoteUser'])->name('demote');
        Route::get('{user}/delete', [DashboardController::class, 'deleteUser'])->name('delete');
    });

    // Thread Moderation
    Route::group(['prefix' => 'threads', 'as' => 'threads.'], function () {
        Route::get('pending', [ThreadModerationController::class, 'pending'])->name('pending');
        Route::get('approved', [ThreadModerationController::class, 'approved'])->name('approved');
        Route::get('rejected', [ThreadModerationController::class, 'rejected'])->name('rejected');
        Route::get('{thread}', [ThreadModerationController::class, 'show'])->name('show');
        Route::post('{thread}/approve', [ThreadModerationController::class, 'approve'])->name('approve');
        Route::post('{thread}/reject', [ThreadModerationController::class, 'reject'])->name('reject');
    });

    /* Name: Categories
     * Url: /admin/categories
     * Route: admin.categories.*
     */
    Route::group(['prefix' => 'categories', 'as' => 'categories.'], function () {
        Route::get('/', [CategoryController::class, 'index'])->name('index');
        Route::get('/create', [CategoryController::class, 'create'])->name('create');
        Route::post('/', [CategoryController::class, 'store'])->name('store');
        Route::get('/edit/{category:slug}', [CategoryController::class, 'edit'])->name('edit');
        Route::put('/{category:slug}', [CategoryController::class, 'update'])->name('update');
        Route::get('/{category:slug}', [CategoryController::class, 'destroy'])->name('delete');
    });
});
