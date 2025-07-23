<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;
use App\Exports\IncomeExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\IncomeController;
use App\Http\Controllers\ExpenseController;



Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth'])
    ->name('dashboard');


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
});

Route::get('/income/export', [IncomeController::class, 'export'])->name('income.export');
Route::get('/expense/export', [ExpenseController::class, 'export'])->name('expense.export');




require __DIR__.'/auth.php';
require __DIR__.'/user.php';
require __DIR__.'/income.php';
require __DIR__.'/expense.php';
