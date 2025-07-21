<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\IncomeController;

Route::get('/income', [IncomeController::class, 'index'])->name('income.index');
Route::get('/income/create', [IncomeController::class, 'create'])->name('income.create');
Route::post('/income', [IncomeController::class, 'store'])->name('income.store');
Route::get('/income/{id}', [IncomeController::class, 'show'])->name('income.show');
Route::get('/income/{id}/edit', [IncomeController::class, 'edit'])->name('income.edit');
Route::put('/income/{id}', [IncomeController::class, 'update'])->name('income.update');
Route::patch('/income/{id}/approve', [IncomeController::class, 'approve'])->name('income.approve');
Route::delete('/income/{id}', [IncomeController::class, 'destroy'])->name('income.destroy');