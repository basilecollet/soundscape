<?php

use App\Http\Controllers\Portfolio\AboutController;
use App\Http\Controllers\Portfolio\ContactController;
use App\Http\Controllers\Portfolio\HomeController;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::get('/', HomeController::class)->name('home');
Route::get('/about', AboutController::class)->name('about');
Route::get('/contact', ContactController::class)->name('contact');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');
});

require __DIR__.'/auth.php';
