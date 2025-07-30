<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;
use App\Http\Middleware\Authenticate;

class Kernel extends HttpKernel
{
    protected $routeMiddleware = [
        // 'auth' => \App\Http\Middleware\Authenticate::class,

        'role' => \Spatie\Permission\Middleware\RoleMiddleware::class,
    ];
}