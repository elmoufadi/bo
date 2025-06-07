<?php
// 📁 app/Http/Controllers/DashboardController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Route générique qui redirige vers le bon dashboard selon le rôle
     */
    public function index()
    {
        $user = Auth::user();
        
        // Vérifier que l'utilisateur est actif
        if (!$user->estActif()) {
            Auth::logout();
            return redirect()->route('login')->withErrors(['nom' => 'Votre compte a été désactivé']);
        }

        // Rediriger selon le rôle
        if ($user->estAdmin()) {
            return redirect()->route('dashboard.admin');
        } elseif ($user->estOperateur()) {
            return redirect()->route('dashboard.operateur');
        }

        // Si aucun rôle reconnu, déconnecter et rediriger
        Auth::logout();
        return redirect()->route('login')->withErrors(['nom' => 'Rôle utilisateur non reconnu']);
    }

    /**
     * Dashboard Admin
     */
    public function admin()
    {
        $user = Auth::user();

        // Vérifier que l'utilisateur est actif
        if (!$user->estActif()) {
            Auth::logout();
            return redirect()->route('login')->withErrors(['nom' => 'Votre compte a été désactivé']);
        }

        // Vérifier que l'utilisateur est admin
        if (!$user->estAdmin()) {
            // Rediriger vers le bon dashboard selon le rôle
            if ($user->estOperateur()) {
                return redirect()->route('dashboard.operateur');
            }
            
            // Si aucun rôle reconnu
            abort(403, 'Accès non autorisé - Rôle admin requis');
        }

        return view('dashboard.admin', [
            'utilisateur' => $user
        ]);
    }

    /**
     * Dashboard Opérateur
     */
    public function operateur()
    {
        $user = Auth::user();

        // Vérifier que l'utilisateur est actif
        if (!$user->estActif()) {
            Auth::logout();
            return redirect()->route('login')->withErrors(['nom' => 'Votre compte a été désactivé']);
        }

        // Vérifier que l'utilisateur est opérateur
        if (!$user->estOperateur()) {
            // Rediriger vers le bon dashboard selon le rôle
            if ($user->estAdmin()) {
                return redirect()->route('dashboard.admin');
            }
            
            // Si aucun rôle reconnu
            abort(403, 'Accès non autorisé - Rôle opérateur requis');
        }

        return view('dashboard.operateur', [
            'utilisateur' => $user
        ]);
    }
}