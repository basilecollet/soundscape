<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\ProjectController;
use App\Http\Controllers\Portfolio\AboutController;
use App\Http\Controllers\Portfolio\ContactController;
use App\Http\Controllers\Portfolio\HomeController;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::get('/', HomeController::class)->name('home');
Route::get('/about', AboutController::class)->name('about');
Route::get('/contact', ContactController::class)->name('contact');

Route::redirect('dashboard', 'admin')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');

    // Admin routes
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');
        Route::resource('/project', ProjectController::class)->only('index', 'create', 'edit', 'destroy');
        Route::get('/content', [AdminController::class, 'content'])->name('content.index');
        Route::get('/content/{id}/edit', [AdminController::class, 'editContent'])->name('content.edit');
        Route::get('/section-settings', [AdminController::class, 'sectionSettings'])->name('section-settings');
    });
});

require __DIR__.'/auth.php';
