<?php

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\ExpenseController;
use App\Http\Controllers\Admin\JasaServisController;
use App\Http\Controllers\Admin\PredictionController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\SparepartController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Kasir\DashboardController as KasirDashboardController;
use App\Http\Controllers\Kasir\EstimasiController;
use App\Http\Controllers\Kasir\SparepartController as KasirSparepartController;
use App\Http\Controllers\Kasir\TransactionController;
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

Route::middleware('auth')->group(function () {

    Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');

    Route::middleware('role:admin')
        ->prefix('admin')
        ->name('admin.')
        ->group(function () {
            Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
            Route::resource('spareparts', SparepartController::class)->except(['show']);
            Route::resource('expenses', ExpenseController::class)->except(['show']);
            Route::resource('jasa-servis', JasaServisController::class)
                ->parameters(['jasa-servis' => 'jasaServis'])
                ->except(['show']);
            Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
            Route::get('/reports/print', [ReportController::class, 'print'])->name('reports.print');
            Route::get('/predictions', [PredictionController::class, 'index'])->name('predictions.index');
            Route::post('/predictions/generate', [PredictionController::class, 'generate'])->name('predictions.generate');
        });

    Route::middleware('role:kasir')
        ->prefix('kasir')
        ->name('kasir.')
        ->group(function () {
            Route::get('/dashboard', [KasirDashboardController::class, 'index'])->name('dashboard');

            // Transaksi (POS)
            Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions.index');
            Route::get('/transactions/create', [TransactionController::class, 'create'])->name('transactions.create');
            Route::post('/transactions', [TransactionController::class, 'store'])->name('transactions.store');
            Route::get('/transactions/{transaction}/receipt', [TransactionController::class, 'receipt'])->name('transactions.receipt');

            // Aksi Estimasi (approve / cancel / complete)
            Route::post('/transactions/{transaction}/approve', [EstimasiController::class, 'approve'])->name('transactions.approve');
            Route::post('/transactions/{transaction}/cancel', [EstimasiController::class, 'cancel'])->name('transactions.cancel');
            Route::post('/transactions/{transaction}/complete', [EstimasiController::class, 'complete'])->name('transactions.complete');

            // Cek Stok (read-only)
            Route::get('/spareparts', [KasirSparepartController::class, 'index'])->name('spareparts.index');
        });
});
