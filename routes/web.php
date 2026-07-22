<?php

use App\Livewire\Admin\Login as AdminLogin;
use App\Livewire\Admin\ProductManager;
use App\Livewire\Catalog;
use Illuminate\Support\Facades\Route;

// Public Digital Catalog
Route::get('/', Catalog::class)->name('catalog');

// Admin Guest Login
Route::get('/admin/login', AdminLogin::class)->name('admin.login');

// Admin Protected Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/admin', ProductManager::class);
    Route::get('/admin/dashboard', ProductManager::class)->name('admin.dashboard');
});
