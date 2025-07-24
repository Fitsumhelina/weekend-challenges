<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\UserController;

Route::resource('role', RoleController::class)->except(['show']);
Route::resource('permission', PermissionController::class)->except(['show']);
Route::resource('user', UserController::class);