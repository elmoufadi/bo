<?php
// 📁 app/Http/Traits/ChecksUserStatus.php

namespace App\Http\Traits;

use Illuminate\Support\Facades\Auth;

trait ChecksUserStatus
{
    /**
     * Vérifier que l'utilisateur est connecté et actif
     * Déconnecte automatiquement si inactif
     */
    protected function checkActiveUser()
    {
        $user = Auth::user();
        
        if (!$user) {
            return redirect()->route('login');
        }

        if (!$user->estActif()) {
            Auth::logout();
            return redirect()->route('login')->withErrors(['nom' => 'Votre compte a été désactivé']);
        }

        return null; // Pas de problème
    }

    /**
     * Vérifier le rôle et rediriger si nécessaire
     */
    protected function checkUserRole($requiredRole)
    {
        $user = Auth::user();
        
        // Vérifier d'abord le statut
        $statusCheck = $this->checkActiveUser();
        if ($statusCheck) {
            return $statusCheck;
        }

        // Vérifier le rôle
        if ($user->role !== $requiredRole) {
            // Rediriger vers le bon dashboard selon le rôle actuel
            if ($user->estAdmin()) {
                return redirect()->route('dashboard.admin');
            } elseif ($user->estOperateur()) {
                return redirect()->route('dashboard.operateur');
            }
            
            // Si aucun rôle reconnu
            abort(403, "Accès non autorisé - Rôle {$requiredRole} requis");
        }

        return null; // Pas de problème
    }
}