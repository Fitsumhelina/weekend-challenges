<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    protected $routeMiddleware = [
        // ...existing middleware...
        'role' => \Spatie\Permission\Middleware\RoleMiddleware::class,
    ];
}