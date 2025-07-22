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
Route::delete('roles/{role}', [RoleController::class, 'destroy'])->name('roles.destroy');
// Route::put('roles/{role}', [RoleController::class, 'update'])->name('roles.update');

Route::resource('permission', PermissionController::class)->except(['show']);
// Route::get('permissions/create', [PermissionController::class, 'create'])->name('permissions.create');
// Route::post('permissions', [PermissionController::class, 'store'])->name('permissions.store');
// Route::get('permissions/{id}/edit', [PermissionController::class, 'edit'])->name('permissions.edit');


Route::get('users', [UserController::class, 'index'])->name('users.index');
Route::get('users/{user}', [UserController::class, 'show'])->name('users.show');
Route::get('users/create', [UserController::class, 'create'])->name('users.create');
Route::post('users', [UserController::class, 'store'])->name('users.store');
Route::get('users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
Route::match(['PUT', 'PATCH'], '/users/{user}', [UserController::class, 'update'])->name('users.update');
Route::delete('users/{user}', [UserController::class, 'destroy'])->name('users.destroy');

