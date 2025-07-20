<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\UserController;

Route::get('roles', [RoleController::class, 'index'])->name('roles.index');
Route::get('roles/create', [RoleController::class, 'create'])->name('roles.create');
Route::post('roles', [RoleController::class, 'store'])->name('roles.store');
Route::get('roles/{role}/edit', [RoleController::class, 'edit'])->name('roles.edit');
Route::match(['PUT', 'PATCH'], '/roles/{role}', [RoleController::class, 'update'])->name('roles.update');
// Route::put('roles/{role}', [RoleController::class, 'update'])->name('roles.update');

Route::patch('roles/{role}', [RoleController::class, 'destroy'])->name('roles.destroy');
Route::resource('permissions', PermissionController::class)->except(['create', 'show']);
Route::get('permissions/{id}/edit', [PermissionController::class, 'edit'])->name('permissions.edit');
Route::match(['PUT', 'PATCH'], '/permissions/{permission}', [PermissionController::class, 'update'])->name('roles.permissions');
// Route::put('permissions/{permission}', [PermissionController::class, 'update'])->name('permissions.update');

Route::get('users', [UserController::class, 'index']) ->name('users.index');
Route::get('users/{id}', [UserController::class, 'show']) ->name('users.show');
Route::get('users/create',[UserController::class,'create']) ->name('users.create');
Route::post('users', [UserController::class, 'store'])->name('users.store');
Route::get('users/{id}/edit', [UserController::class, 'edit'])->name('users.edit');
Route::match(['PUT', 'PATCH'], '/users/{id}', [UserController::class, 'update'])->name('users.update');
Route::delete('users/{id}', [UserController::class, 'destroy'])->name('users.destroy');



