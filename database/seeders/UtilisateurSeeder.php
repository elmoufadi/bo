<?php
// 📁 database/seeders/UtilisateurSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Utilisateur;
use Illuminate\Support\Facades\Hash;

class UtilisateurSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Créer un administrateur
        Utilisateur::create([
            'nom' => 'admin',
            'email' => 'admin@example.com',
            'mot_de_passe' => 'admin123', // Sera hashé automatiquement via le mutateur
            'role' => 'admin',
            'statut' => 'actif'
        ]);

        // Créer un opérateur
        Utilisateur::create([
            'nom' => 'operateur',
            'email' => 'operateur@example.com',
            'mot_de_passe' => 'operateur123', // Sera hashé automatiquement via le mutateur
            'role' => 'operateur',
            'statut' => 'actif'
        ]);

        // Créer quelques utilisateurs supplémentaires
        Utilisateur::create([
            'nom' => 'jean.dupont',
            'email' => 'jean.dupont@example.com',
            'mot_de_passe' => 'password123',
            'role' => 'operateur',
            'statut' => 'actif'
        ]);

        Utilisateur::create([
            'nom' => 'marie.martin',
            'email' => 'marie.martin@example.com',
            'mot_de_passe' => 'password123',
            'role' => 'operateur',
            'statut' => 'inactif'
        ]);

        Utilisateur::create([
            'nom' => 'admin.system',
            'email' => 'admin.system@example.com',
            'mot_de_passe' => 'system123',
            'role' => 'admin',
            'statut' => 'actif'
        ]);
    }
}