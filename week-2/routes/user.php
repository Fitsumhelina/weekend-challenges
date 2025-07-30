<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\UserController;


Route::middleware('auth')->group(function () {
    Route::resource('role', RoleController::class)->except(['show']);
    Route::resource('permission', PermissionController::class)->only(['index']);
    Route::resource('user', UserController::class);
});