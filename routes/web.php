<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Livewire\Menu;
use App\Http\Middleware\AdminMiddleware;
use Illuminate\Support\Facades\App;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'verified'])->prefix('dashboard')->group(function () {
    Route::get('/orders', function () {
        return view('dashboard.orders.index');
    })->name('order');
    Route::get('/tables', function () {
        return view('dashboard.tables.index');
    })->name('table');
    Route::get('/offers', function () {
        return view('dashboard.offers.index');
    })->name('offer');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Route::middleware(['auth', AdminMiddleware::class])->prefix('admin')->group(function () {
//     Route::get('/products', App\Livewire\Admin\ProductManager::class)->name('admin.products');
//     Route::get('/categories', App\Livewire\Admin\CategoryManager::class)->name('admin.categories');
// });

require __DIR__ . '/auth.php';
