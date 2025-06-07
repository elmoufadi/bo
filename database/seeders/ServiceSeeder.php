<?php
// 📁 database/seeders/ServiceSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Service;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $services = [
            [
                'nom_service' => 'Direction Générale',
                'email_service' => 'direction@entreprise.com',
                'statut' => 'actif'
            ],
            [
                'nom_service' => 'Ressources Humaines',
                'email_service' => 'rh@entreprise.com',
                'statut' => 'actif'
            ],
            [
                'nom_service' => 'Informatique',
                'email_service' => 'it@entreprise.com',
                'statut' => 'actif'
            ],
            [
                'nom_service' => 'Comptabilité',
                'email_service' => 'compta@entreprise.com',
                'statut' => 'actif'
            ],
            [
                'nom_service' => 'Marketing',
                'email_service' => 'marketing@entreprise.com',
                'statut' => 'actif'
            ],
            [
                'nom_service' => 'Commercial',
                'email_service' => 'commercial@entreprise.com',
                'statut' => 'actif'
            ],
            [
                'nom_service' => 'Production',
                'email_service' => 'production@entreprise.com',
                'statut' => 'actif'
            ],
            [
                'nom_service' => 'Qualité',
                'email_service' => 'qualite@entreprise.com',
                'statut' => 'actif'
            ],
            [
                'nom_service' => 'Logistique',
                'email_service' => 'logistique@entreprise.com',
                'statut' => 'actif'
            ],
            [
                'nom_service' => 'Maintenance',
                'email_service' => 'maintenance@entreprise.com',
                'statut' => 'inactif' // Un service inactif pour tester
            ]
        ];

        foreach ($services as $service) {
            Service::create($service);
        }

        $this->command->info('Services créés avec succès !');
    }
}