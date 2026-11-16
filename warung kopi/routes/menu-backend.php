<?php

use App\Http\Controllers\MenuController;
use App\Http\Middleware\EnsureActiveUser;
use App\Http\Middleware\EnsureCashier;
use Illuminate\Support\Facades\Route;

// Backend JSON contract; include from web.php, not a separate API route group.
// Web routes retain session authentication and Laravel's CSRF protection.
Route::middleware(['auth', EnsureActiveUser::class, EnsureCashier::class])->prefix('internal-api/menus')->name('menus.')->group(function () {
    Route::get('/', [MenuController::class, 'index'])->name('index');
    Route::post('/', [MenuController::class, 'store'])->name('store');
    Route::put('/{id}', [MenuController::class, 'update'])->whereNumber('id')->name('update');
    Route::delete('/{id}', [MenuController::class, 'destroy'])->whereNumber('id')->name('destroy');
});
