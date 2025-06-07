<?php
// 📁 database/seeders/PieceJointeSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\PieceJointe;
use App\Models\Message;

class PieceJointeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $messages = Message::all();

        // Définir les pièces jointes pour des messages spécifiques
        $piecesJointes = [
            // Message 1 : Demande d'information
            [
                'message_reference' => 'MSG-2025-0001',
                'pieces' => [
                    [
                        'nom_fichier' => 'cahier_des_charges.pdf',
                        'chemin_fichier' => 'attachments/2025/06/cahier_des_charges_2025-06-08_10-30-15.pdf',
                        'type_mime' => 'application/pdf',
                        'taille_fichier' => 245760
                    ]
                ]
            ],
            
            // Message 3 : Réclamation client
            [
                'message_reference' => 'MSG-2025-0003',
                'pieces' => [
                    [
                        'nom_fichier' => 'photo_defaut_produit.jpg',
                        'chemin_fichier' => 'attachments/2025/06/photo_defaut_produit_2025-06-08_11-15-23.jpg',
                        'type_mime' => 'image/jpeg',
                        'taille_fichier' => 1024000
                    ],
                    [
                        'nom_fichier' => 'bon_livraison.pdf',
                        'chemin_fichier' => 'attachments/2025/06/bon_livraison_2025-06-08_11-16-45.pdf',
                        'type_mime' => 'application/pdf',
                        'taille_fichier' => 156800
                    ]
                ]
            ],
            
            // Message 4 : Proposition commerciale
            [
                'message_reference' => 'MSG-2025-0004',
                'pieces' => [
                    [
                        'nom_fichier' => 'proposition_partenariat.docx',
                        'chemin_fichier' => 'attachments/2025/06/proposition_partenariat_2025-06-08_12-00-00.docx',
                        'type_mime' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                        'taille_fichier' => 512000
                    ],
                    [
                        'nom_fichier' => 'tarifs_2025.xlsx',
                        'chemin_fichier' => 'attachments/2025/06/tarifs_2025_2025-06-08_12-01-30.xlsx',
                        'type_mime' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                        'taille_fichier' => 89600
                    ]
                ]
            ],
            
            // Message 7 : Mise à jour sécurité
            [
                'message_reference' => 'MSG-2025-0007',
                'pieces' => [
                    [
                        'nom_fichier' => 'procedure_mise_a_jour.pdf',
                        'chemin_fichier' => 'attachments/2025/06/procedure_mise_a_jour_2025-06-08_14-20-33.pdf',
                        'type_mime' => 'application/pdf',
                        'taille_fichier' => 456789
                    ],
                    [
                        'nom_fichier' => 'patch_securite_v2.1.zip',
                        'chemin_fichier' => 'attachments/2025/06/patch_securite_v2_1_2025-06-08_14-21-45.zip',
                        'type_mime' => 'application/zip',
                        'taille_fichier' => 15728640
                    ]
                ]
            ],
            
            // Message 12 : Candidature
            [
                'message_reference' => 'MSG-2025-0012',
                'pieces' => [
                    [
                        'nom_fichier' => 'CV_candidat.pdf',
                        'chemin_fichier' => 'attachments/2025/06/CV_candidat_2025-06-08_17-15-45.pdf',
                        'type_mime' => 'application/pdf',
                        'taille_fichier' => 345678
                    ],
                    [
                        'nom_fichier' => 'lettre_motivation.docx',
                        'chemin_fichier' => 'attachments/2025/06/lettre_motivation_2025-06-08_17-16-30.docx',
                        'type_mime' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                        'taille_fichier' => 78954
                    ]
                ]
            ]
        ];

        foreach ($piecesJointes as $messagePieces) {
            $message = $messages->where('numero_reference', $messagePieces['message_reference'])->first();
            
            if (!$message) {
                continue;
            }

            foreach ($messagePieces['pieces'] as $pieceData) {
                PieceJointe::create([
                    'id_message' => $message->id_message,
                    'nom_fichier' => $pieceData['nom_fichier'],
                    'chemin_fichier' => $pieceData['chemin_fichier'],
                    'type_mime' => $pieceData['type_mime'],
                    'taille_fichier' => $pieceData['taille_fichier']
                ]);
            }
        }

        $this->command->info('Pièces jointes créées avec succès !');
    }
}