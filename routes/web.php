<?php

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Kasir\DashboardController as KasirDashboardController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Guest Routes — Hanya bisa diakses jika BELUM login
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/', fn () => redirect()->route('login'));
    Route::get('/login', [LoginController::class, 'create'])->name('login');
    Route::post('/login', [LoginController::class, 'store'])->name('login.store');
});

/*
|--------------------------------------------------------------------------
| Authenticated Routes — Wajib login
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    // Logout (bisa dari role mana saja, selama sudah login)
    Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');

    /*
    |----------------------------------------------------------------------
    | Admin Routes — Role: admin
    |----------------------------------------------------------------------
    */
    Route::middleware('role:admin')
        ->prefix('admin')
        ->name('admin.')
        ->group(function () {
            Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

            // === Placeholder untuk route Admin berikutnya ===
            // Route::resource('spareparts', SparepartController::class);
            // Route::resource('expenses', ExpenseController::class);
            // Route::get('reports', ReportController::class)->name('reports');
            // Route::get('predictions', PredictionController::class)->name('predictions');
        });

    /*
    |----------------------------------------------------------------------
    | Kasir Routes — Role: kasir
    |----------------------------------------------------------------------
    */
    Route::middleware('role:kasir')
        ->prefix('kasir')
        ->name('kasir.')
        ->group(function () {
            Route::get('/dashboard', [KasirDashboardController::class, 'index'])->name('dashboard');

            // === Placeholder untuk route Kasir berikutnya ===
            // Route::get('transactions/create', [TransactionController::class, 'create'])->name('transactions.create');
            // Route::post('transactions', [TransactionController::class, 'store'])->name('transactions.store');
        });
});
