<?php
// 📁 app/Http/Middleware/CheckRole.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, string $role)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        // Vérifier le rôle
        if ($user->role !== $role) {
            abort(403, 'Accès non autorisé pour ce rôle');
        }

        // Vérifier que l'utilisateur est actif
        if (!$user->estActif()) {
            Auth::logout();
            return redirect()->route('login')->withErrors(['nom' => 'Votre compte a été désactivé']);
        }

        return $next($request);
    }
}