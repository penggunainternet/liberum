<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController;

Route::group(['prefix' => 'admin', 'as' => 'admin.', 'middleware' => ['auth', 'verified', 'isAdmin']], function () {
    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('index');
    Route::group(['prefix' => 'users', 'as' => 'users.'], function () {
        Route::get('active', [DashboardController::class, 'getActiveUser'])->name('active');
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
