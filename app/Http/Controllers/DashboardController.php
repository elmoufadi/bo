<?php
// 📁 app/Http/Controllers/DashboardController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Dashboard Admin
     */
    public function admin()
    {
        // Vérifier que l'utilisateur est admin
        if (!Auth::user()->estAdmin()) {
            abort(403, 'Accès non autorisé');
        }

        return view('dashboard.admin', [
            'utilisateur' => Auth::user()
        ]);
    }

    /**
     * Dashboard Opérateur
     */
    public function operateur()
    {
        // Vérifier que l'utilisateur est opérateur
        if (!Auth::user()->estOperateur()) {
            abort(403, 'Accès non autorisé');
        }

        return view('dashboard.operateur', [
            'utilisateur' => Auth::user()
        ]);
    }
}