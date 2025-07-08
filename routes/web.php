<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginRedirectController;
use Livewire\Volt\Volt;
use App\Http\Controllers\Admin\EnfantStatistiqueController;
use App\Http\Controllers\Admin\SecteurController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\ProfileController as AdminProfileController;
use App\Http\Controllers\User\UserDashboardController;
use App\Http\Controllers\User\VaccinStatistiqueController;
use App\Http\Controllers\User\ProfileController as UserProfileController;
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
    
    Route::get('/dashboard', function () {
    return redirect()->route('redirect');
    })->middleware(['auth'])->name('dashboard');
   
});
Route::middleware(['auth'])->group(function () {
    Route::get('/admin/enfants-statistiques', [EnfantStatistiqueController::class, 'create'])->name('enfants.create');
    Route::post('/admin/enfants-statistiques', [EnfantStatistiqueController::class, 'storeMultiple'])->name('enfants.storeMultiple');
});

Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('secteurs', SecteurController::class)->except(['show', 'edit', 'update']);
});
Route::middleware(['auth'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::resource('users', UserController::class)->except(['show', 'edit', 'update']);
});
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/profile', [AdminProfileController::class, 'edit'])->name('profile');
    Route::post('/profile', [AdminProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/password', [AdminProfileController::class, 'updatePassword'])->name('profile.updatePassword');
});
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard/user', [UserDashboardController::class, 'index'])->name('dashboard.user');
});
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard/user', [UserDashboardController::class, 'index'])->name('dashboard.user');
    Route::get('/user/enfants-par-age/{annee}', [UserDashboardController::class, 'getStatsByAnnee']);
});
Route::middleware(['auth'])->group(function () {
    Route::get('/user/vaccins/create', [VaccinStatistiqueController::class, 'create'])->name('user.vaccins.create');
    Route::post('/user/vaccins/store', [VaccinStatistiqueController::class, 'store'])->name('user.vaccins.store');
});
Route::middleware(['auth'])->prefix('user')->name('user.')->group(function () {
    Route::get('/profile', [UserProfileController::class, 'edit'])->name('profile');
    Route::post('/profile', [UserProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/password', [UserProfileController::class, 'updatePassword'])->name('profile.updatePassword');
});

Route::get('/test', function () {
    return view('welcome');
});