<?php
// 📁 app/Http/Kernel.php (partie middleware)

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    // ... autres middlewares ...

    /**
     * The application's route middleware.
     */
    protected $middlewareAliases = [
        // ... autres middlewares existants ...
        'check.role' => \App\Http\Middleware\CheckRole::class,
        // ... autres middlewares ...
    ];
}