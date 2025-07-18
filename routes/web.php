<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [ProfileController::class, 'dashboard'])->middleware(['auth'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/expenses', [ProfileController::class, 'expenses'])->name('expenses');
    Route::get('/income', [ProfileController::class, 'income'])->name('income');
    Route::get('/income/{id}', [ProfileController::class, 'showIncome'])->name('income.show');
    Route::get('/expenses/{id}', [ProfileController::class, 'showExpense'])->name('expenses.show');
    Route::post('/income', [TransactionController::class, 'storeIncome'])->name('income.store');
    Route::post('/expenses', [TransactionController::class, 'storeExpense'])->name('expenses.store');

    Route::middleware(['role:Admin'])->group (function () {
        Route::post('/expenses', [ProfileController::class, 'storeExpense'])->name('expenses.store');
        Route::post('/income', [ProfileController::class, 'storeIncome'])->name('income.store');
        Route::put('/expenses/{id}', [ProfileController::class, 'updateExpenses'])->name('expenses.update');
        Route::put('/income/{id}', [ProfileController::class, 'updateIncome'])->name('income.update');
        Route::get('/users', [ProfileController::class, 'users'])->name('users');
        Route::delete('expenses/{id}', [ProfileController::class, 'deleteExpense'])->name('expenses.delete');
        Route::delete('income/{id}', [ProfileController::class, 'deleteIncome'])->name('income.delete');
        Route::get('/reports', [ProfileController::class, 'reports'])->name('reports');
        // Route::get('/settings', [ProfileController::class, 'settings'])->name('settings');
 
    });
});



require __DIR__.'/auth.php';
