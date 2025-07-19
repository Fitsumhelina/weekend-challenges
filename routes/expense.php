<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ExpenseController;

Route::get('/expense', [ExpenseController::class, 'index'])->name('income.index');
Route::get('/expense/create', [ExpenseController::class, 'create'])->name('expense.create');
Route::post('/expense', [ExpenseController::class, 'store'])->name('expense.store');
Route::get('/expense/{id}', [ExpenseController::class, 'show'])->name('expense.show');
Route::get('/expense/{id}/edit', [ExpenseController::class, 'edit'])->name('expense.edit');
Route::put('/expense/{id}', [ExpenseController::class, 'update'])->name('expense.update');
Route::delete('/expense/{id}', [ExpenseController::class, 'destroy'])->name('expense.destroy');