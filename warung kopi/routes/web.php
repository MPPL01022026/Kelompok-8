<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Middleware\EnsureActiveUser;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => redirect()->route('dashboard'));

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'create'])->name('login');
    Route::post('/login', [LoginController::class, 'store'])->name('login.store');
});

// Tambahkan route fitur internal ke grup ini agar membutuhkan akun aktif.
Route::middleware(['auth', EnsureActiveUser::class])->group(function () {
    // Halaman sementara sampai dashboard fitur Irma/Rizqi siap diintegrasikan.
    Route::view('/dashboard', 'dashboard')->name('dashboard');
    Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');
});
