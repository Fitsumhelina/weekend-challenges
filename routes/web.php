<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TransactionController;

Route::view('/', 'welcome');

Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [ProfileController::class, 'dashboard'])->name('dashboard');

    // Profile
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'edit'])->name('edit');
        Route::patch('/', [ProfileController::class, 'update'])->name('update');
        Route::delete('/', [ProfileController::class, 'destroy'])->name('destroy');
    });
    // Authenticated routes
    Route::prefix('income')->name('income.')->group(function () {
        Route::get('/', [TransactionController::class, 'indexIncome'])->name('index');
        Route::get('/{id}', [TransactionController::class, 'showIncome'])->name('show');
    });

    Route::prefix('expenses')->name('expenses.')->group(function () {
        Route::get('/', [TransactionController::class, 'indexExpenses'])->name('index');
        Route::get('/{id}', [TransactionController::class, 'showExpense'])->name('show');
    });

    // Admin-only routes
    Route::middleware('role:Admin')->group(function () {
        Route::post('/income', [TransactionController::class, 'storeIncome'])->name('income.store');
        Route::put('/income/{id}', [TransactionController::class, 'updateIncome'])->name('income.update');
        Route::delete('/income/{id}', [TransactionController::class, 'deleteIncome'])->name('income.delete');

        Route::post('/expenses', [TransactionController::class, 'storeExpense'])->name('expenses.store');
        Route::put('/expenses/{id}', [TransactionController::class, 'updateExpenses'])->name('expenses.update');
        Route::delete('/expenses/{id}', [TransactionController::class, 'deleteExpense'])->name('expenses.delete');
    });

        // Admin features
        // Route::get('/users', [ProfileController::class, 'users'])->name('users');
        // Route::get('/reports', [ProfileController::class, 'reports'])->name('reports');
        // Route::get('/settings', [ProfileController::class, 'settings'])->name('settings'); // Uncomment if needed
    });


require __DIR__.'/auth.php';
