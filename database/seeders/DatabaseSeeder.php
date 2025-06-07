<?php
// 📁 database/seeders/DatabaseSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->command->info('🚀 Début du seeding de la base de données...');
        
        // Ordre important : respecter les dépendances entre les modèles
        $this->call([
            // 1. Utilisateurs (indépendant)
            UtilisateurSeeder::class,
            
            // 2. Services (indépendant)
            ServiceSeeder::class,
            
            // 3. Messages (indépendant)
            MessageSeeder::class,
            
            // 4. Distribution des messages (dépend de Messages et Services)
            DistributionMessageSeeder::class,
            
            // 5. Pièces jointes (dépend de Messages)
            PieceJointeSeeder::class,
        ]);
        
        $this->command->info('✅ Seeding terminé avec succès !');
        $this->command->info('');
        $this->command->info('📊 Résumé des données créées :');
        $this->command->info('- Utilisateurs : ' . \App\Models\Utilisateur::count());
        $this->command->info('- Services : ' . \App\Models\Service::count());
        $this->command->info('- Messages : ' . \App\Models\Message::count());
        $this->command->info('- Distributions : ' . \App\Models\DistributionMessage::count());
        $this->command->info('- Pièces jointes : ' . \App\Models\PieceJointe::count());
        $this->command->info('');
        $this->command->info('🔐 Comptes de test disponibles :');
        $this->command->info('- Admin : admin / admin123');
        $this->command->info('- Opérateur : operateur / operateur123');
    }
}