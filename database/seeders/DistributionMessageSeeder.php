<?php
// 📁 database/seeders/DistributionMessageSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\DistributionMessage;
use App\Models\Message;
use App\Models\Service;
use Carbon\Carbon;

class DistributionMessageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $messages = Message::all();
        $services = Service::where('statut', 'actif')->get();

        // Définir les distributions spécifiques
        $distributions = [
            // Message 1 : Demande d'information -> Commercial + Direction
            [
                'message_reference' => 'MSG-2025-0001',
                'services' => ['Commercial', 'Direction Générale'],
                'statut_lecture' => ['lu', 'lu'],
                'jours_apres_message' => [0, 1]
            ],
            
            // Message 2 : URGENT livraison -> Logistique + Commercial + Direction
            [
                'message_reference' => 'MSG-2025-0002',
                'services' => ['Logistique', 'Commercial', 'Direction Générale'],
                'statut_lecture' => ['lu', 'lu', 'non_lu'],
                'jours_apres_message' => [0, 0, 0]
            ],
            
            // Message 3 : Réclamation -> Qualité + Commercial
            [
                'message_reference' => 'MSG-2025-0003',
                'services' => ['Qualité', 'Commercial'],
                'statut_lecture' => ['lu', 'lu'],
                'jours_apres_message' => [0, 2]
            ],
            
            // Message 4 : Proposition commerciale -> Commercial
            [
                'message_reference' => 'MSG-2025-0004',
                'services' => ['Commercial'],
                'statut_lecture' => ['non_lu'],
                'jours_apres_message' => [0]
            ],
            
            // Message 5 : Demande devis -> Commercial + Production
            [
                'message_reference' => 'MSG-2025-0005',
                'services' => ['Commercial', 'Production'],
                'statut_lecture' => ['lu', 'non_lu'],
                'jours_apres_message' => [0, 1]
            ],
            
            // Message 6 : Facture en souffrance -> Comptabilité + Direction
            [
                'message_reference' => 'MSG-2025-0006',
                'services' => ['Comptabilité', 'Direction Générale'],
                'statut_lecture' => ['lu', 'lu'],
                'jours_apres_message' => [0, 1]
            ],
            
            // Message 7 : Mise à jour sécurité -> Informatique
            [
                'message_reference' => 'MSG-2025-0007',
                'services' => ['Informatique'],
                'statut_lecture' => ['non_lu'],
                'jours_apres_message' => [0]
            ],
            
            // Message 8 : Inspection travail -> Direction + RH
            [
                'message_reference' => 'MSG-2025-0008',
                'services' => ['Direction Générale', 'Ressources Humaines'],
                'statut_lecture' => ['lu', 'lu'],
                'jours_apres_message' => [0, 0]
            ],
            
            // Message 9 : Newsletter -> Marketing
            [
                'message_reference' => 'MSG-2025-0009',
                'services' => ['Marketing'],
                'statut_lecture' => ['non_lu'],
                'jours_apres_message' => [0]
            ],
            
            // Message 10 : Salon professionnel -> Marketing + Direction
            [
                'message_reference' => 'MSG-2025-0010',
                'services' => ['Marketing', 'Direction Générale'],
                'statut_lecture' => ['lu', 'lu'],
                'jours_apres_message' => [1, 2]
            ],
            
            // Message 11 : Mise en demeure -> Direction + Comptabilité
            [
                'message_reference' => 'MSG-2025-0011',
                'services' => ['Direction Générale', 'Comptabilité'],
                'statut_lecture' => ['non_lu', 'non_lu'],
                'jours_apres_message' => [0, 0]
            ],
            
            // Message 12 : Candidature -> RH
            [
                'message_reference' => 'MSG-2025-0012',
                'services' => ['Ressources Humaines'],
                'statut_lecture' => ['lu'],
                'jours_apres_message' => [2]
            ],
            
            // Message 13 : Assurance -> Direction + Comptabilité
            [
                'message_reference' => 'MSG-2025-0013',
                'services' => ['Direction Générale', 'Comptabilité'],
                'statut_lecture' => ['lu', 'non_lu'],
                'jours_apres_message' => [1, 3]
            ],
            
            // Message 14 : Formation -> RH
            [
                'message_reference' => 'MSG-2025-0014',
                'services' => ['Ressources Humaines'],
                'statut_lecture' => ['non_lu'],
                'jours_apres_message' => [0]
            ],
            
            // Message 15 : Maintenance -> Production
            [
                'message_reference' => 'MSG-2025-0015',
                'services' => ['Production'],
                'statut_lecture' => ['lu'],
                'jours_apres_message' => [0]
            ]
        ];

        foreach ($distributions as $dist) {
            $message = $messages->where('numero_reference', $dist['message_reference'])->first();
            
            if (!$message) {
                continue;
            }

            foreach ($dist['services'] as $index => $serviceName) {
                $service = $services->where('nom_service', $serviceName)->first();
                
                if (!$service) {
                    continue;
                }

                // Calculer la date de distribution
                $dateDistribution = $message->date_reception->copy()->addDays($dist['jours_apres_message'][$index]);
                
                DistributionMessage::create([
                    'id_message' => $message->id_message,
                    'id_service' => $service->id_service,
                    'date_distribution' => $dateDistribution,
                    'statut_lecture' => $dist['statut_lecture'][$index]
                ]);
            }
        }

        $this->command->info('Distributions de messages créées avec succès !');
    }
}