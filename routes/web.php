<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginRedirectController;
use Livewire\Volt\Volt;

require __DIR__.'/auth.php';

// Route d'accueil redirige vers le bon dashboard selon le rôle
Route::get('/', function () {
    return redirect()->route('redirect');
});

// Route après login
Route::get('/redirect', [LoginRedirectController::class, 'handle'])
    ->middleware(['auth'])
    ->name('redirect');

// Route d'exemple utilisée par Laravel Breeze ou Jetstream
Route::get('/home', function () {
    return redirect()->route('redirect');
})->name('home');

// Dashboards spécifiques
Route::view('/dashboard/admin', 'dashboard.admin')
    ->middleware(['auth'])
    ->name('dashboard.admin');

Route::view('/dashboard/user', 'dashboard.user')
    ->middleware(['auth'])
    ->name('dashboard.user');

// Pages de paramètres protégées
Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');
});
