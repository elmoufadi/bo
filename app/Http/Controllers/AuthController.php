<?php
// 📁 app/Http/Controllers/AuthController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Utilisateur;

class AuthController extends Controller
{
    /**
     * Afficher la page de login
     */
    public function showLogin()
    {
        return view('auth.login');
    }

    /**
     * Traiter la connexion
     */
    public function login(Request $request)
    {
        $request->validate([
            'nom' => 'required|string',
            'mot_de_passe' => 'required|string',
        ], [
            'nom.required' => 'Le nom est obligatoire',
            'mot_de_passe.required' => 'Le mot de passe est obligatoire',
        ]);

        // Chercher l'utilisateur par nom
        $utilisateur = Utilisateur::where('nom', $request->nom)->first();

        if (!$utilisateur) {
            return back()->withErrors([
                'nom' => 'Utilisateur non trouvé.',
            ])->withInput($request->only('nom'));
        }

        // Vérifier le mot de passe
        if (!Hash::check($request->mot_de_passe, $utilisateur->mot_de_passe)) {
            return back()->withErrors([
                'mot_de_passe' => 'Mot de passe incorrect.',
            ])->withInput($request->only('nom'));
        }

        // Vérifier si l'utilisateur est actif
        if (!$utilisateur->estActif()) {
            return back()->withErrors([
                'nom' => 'Votre compte est désactivé.',
            ])->withInput($request->only('nom'));
        }

        // Connecter l'utilisateur
        Auth::login($utilisateur);

        // Rediriger vers le dashboard générique qui fera la redirection appropriée
        return redirect()->route('dashboard');
    }

    /**
     * Déconnexion
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Vous avez été déconnecté avec succès.');
    }

    /**
     * Vérifier le statut de l'utilisateur connecté (utilitaire)
     */
    public function checkUserStatus()
    {
        $user = Auth::user();
        
        if (!$user) {
            return false;
        }

        // Si l'utilisateur n'est plus actif, le déconnecter
        if (!$user->estActif()) {
            Auth::logout();
            return false;
        }

        return true;
    }
}