<?php

use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LaundryServiceController;
use App\Http\Controllers\LaundryTransactionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserManagementController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return auth()->check() ? redirect()->route('dashboard') : redirect()->route('login');
});
Route::get('/tracking', [LaundryTransactionController::class, 'tracking'])->name('tracking');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', DashboardController::class)->name('dashboard');
    Route::resource('services', LaundryServiceController::class)
        ->parameters(['services' => 'laundryService']);
    Route::resource('customers', CustomerController::class)
        ->parameters(['customers' => 'customer']);
    Route::resource('transactions', LaundryTransactionController::class)
        ->parameters(['transactions' => 'laundryTransaction']);
    Route::get('/transactions-recap', [LaundryTransactionController::class, 'index'])->name('transactions.recap');
    Route::get('/transactions/{laundryTransaction}/print', [LaundryTransactionController::class, 'printReceipt'])->name('transactions.print');
    Route::get('/reports', [LaundryTransactionController::class, 'report'])->name('reports.index');
    Route::get('/reports/export', [LaundryTransactionController::class, 'exportReport'])->name('reports.export');

    Route::middleware('admin')->group(function () {
        Route::resource('users', UserManagementController::class);
    });

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
