<?php
// 📁 app/Http/Controllers/DashboardController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Utilisateur;

class DashboardController extends Controller
{
    /**
     * Route générique qui redirige vers le bon dashboard selon le rôle
     */
    public function index()
    {
        $user = Auth::user();
        
        if (!$user) {
            return redirect()->route('login');
        }
        
        // Vérifier que l'utilisateur est actif (comparaison directe)
        if ($user->statut !== 'actif') {
            Auth::logout();
            return redirect()->route('login')->withErrors(['nom' => 'Votre compte a été désactivé']);
        }

        // Rediriger selon le rôle (comparaison directe)
        if ($user->role === 'admin') {
            return redirect()->route('dashboard.admin');
        } elseif ($user->role === 'operateur') {
            return redirect()->route('dashboard.operateur');
        }

        // Si aucun rôle reconnu, déconnecter et rediriger
        Auth::logout();
        return redirect()->route('login')->withErrors(['nom' => 'Rôle utilisateur non reconnu: ' . $user->role]);
    }

    /**
     * Dashboard Admin
     */
    public function admin()
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        // Vérifier que l'utilisateur est actif (comparaison directe)
        if ($user->statut !== 'actif') {
            Auth::logout();
            return redirect()->route('login')->withErrors(['nom' => 'Votre compte a été désactivé']);
        }

        // Vérifier que l'utilisateur est admin (comparaison directe)
        if ($user->role !== 'admin') {
            // Rediriger vers le bon dashboard selon le rôle
            if ($user->role === 'operateur') {
                return redirect()->route('dashboard.operateur');
            }
            
            // Si aucun rôle reconnu
            abort(403, 'Accès non autorisé - Rôle admin requis. Votre rôle: ' . $user->role);
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

        if (!$user) {
            return redirect()->route('login');
        }

        // Vérifier que l'utilisateur est actif (comparaison directe)
        if ($user->statut !== 'actif') {
            Auth::logout();
            return redirect()->route('login')->withErrors(['nom' => 'Votre compte a été désactivé']);
        }

        // Vérifier que l'utilisateur est opérateur (comparaison directe)
        if ($user->role !== 'operateur') {
            // Rediriger vers le bon dashboard selon le rôle
            if ($user->role === 'admin') {
                return redirect()->route('dashboard.admin');
            }
            
            // Si aucun rôle reconnu
            abort(403, 'Accès non autorisé - Rôle opérateur requis. Votre rôle: ' . $user->role);
        }

        return view('dashboard.operateur', [
            'utilisateur' => $user
        ]);
    }
}