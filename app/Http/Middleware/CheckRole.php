<?php
// 📁 app/Http/Middleware/CheckRole.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (!$request->user()) {
            return redirect()->route('login');
        }

        if ($role === 'admin' && !$request->user()->estAdmin()) {
            return redirect()->route('dashboard')->with('error', 'Accès non autorisé.');
        }

        if ($role === 'operateur' && !$request->user()->estOperateur()) {
            return redirect()->route('dashboard')->with('error', 'Accès non autorisé.');
        }

        return $next($request);
    }
}