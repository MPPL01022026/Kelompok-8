<?php

use App\Http\Controllers\OrderController;
use App\Http\Middleware\EnsureActiveUser;
use App\Http\Middleware\EnsureCashier;
use Illuminate\Support\Facades\Route;

// Session authentication and CSRF protection are supplied by web.php.
Route::middleware(['auth', EnsureActiveUser::class, EnsureCashier::class])->prefix('internal-api/orders')->name('orders.')->group(function () {
    Route::get('/create', [OrderController::class, 'create'])->name('create');
    Route::post('/', [OrderController::class, 'store'])->name('store');
});
