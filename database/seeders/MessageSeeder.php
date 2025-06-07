<?php
// 📁 database/seeders/MessageSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Message;
use Carbon\Carbon;

class MessageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $messages = [
            [
                'expediteur' => 'Jean Dupont',
                'email_expediteur' => 'jean.dupont@client.com',
                'objet' => 'Demande d\'information sur les produits',
                'contenu' => 'Bonjour, je souhaiterais obtenir des informations détaillées sur votre gamme de produits. Pourriez-vous me transmettre un catalogue complet ? Merci.',
                'date_reception' => Carbon::now()->subDays(5),
                'statut' => 'distribue',
                'priorite' => 'normale'
            ],
            [
                'expediteur' => 'Marie Martin',
                'email_expediteur' => 'marie.martin@partenaire.fr',
                'objet' => 'URGENT - Problème de livraison commande #12345',
                'contenu' => 'Bonjour, nous avons un problème urgent avec la commande #12345. La livraison était prévue aujourd\'hui mais nous n\'avons rien reçu. Merci de traiter en priorité.',
                'date_reception' => Carbon::now()->subDays(1),
                'statut' => 'distribue',
                'priorite' => 'urgente'
            ],
            [
                'expediteur' => 'Service Client ABC',
                'email_expediteur' => 'contact@abc-corp.com',
                'objet' => 'Réclamation client - Produit défectueux',
                'contenu' => 'Un de nos clients nous signale un défaut sur le produit référence XYZ-456. Merci de nous indiquer la procédure de retour et d\'échange.',
                'date_reception' => Carbon::now()->subDays(3),
                'statut' => 'traite',
                'priorite' => 'haute'
            ],
            [
                'expediteur' => 'Pierre Durand',
                'email_expediteur' => 'p.durand@fournisseur.net',
                'objet' => 'Proposition commerciale - Nouveau partenariat',
                'contenu' => 'Bonjour, nous souhaitons vous proposer un nouveau partenariat commercial. Vous trouverez ci-joint notre proposition détaillée. Nous restons à votre disposition pour en discuter.',
                'date_reception' => Carbon::now()->subDays(7),
                'statut' => 'recu',
                'priorite' => 'normale'
            ],
            [
                'expediteur' => 'Sarah Johnson',
                'email_expediteur' => 'sarah.j@international.com',
                'objet' => 'Demande de devis pour 1000 unités',
                'contenu' => 'Hello, we would like to request a quote for 1000 units of your product REF-789. Please include shipping costs to London. Best regards.',
                'date_reception' => Carbon::now()->subDays(2),
                'statut' => 'distribue',
                'priorite' => 'haute'
            ],
            [
                'expediteur' => 'Comptabilité SARL Dubois',
                'email_expediteur' => 'compta@dubois.fr',
                'objet' => 'Facture en souffrance #FAC-2024-0156',
                'contenu' => 'Bonjour, nous vous informons que la facture #FAC-2024-0156 d\'un montant de 2 450€ est en souffrance depuis 15 jours. Merci de régulariser rapidement.',
                'date_reception' => Carbon::now()->subDays(4),
                'statut' => 'distribue',
                'priorite' => 'haute'
            ],
            [
                'expediteur' => 'Support Technique',
                'email_expediteur' => 'support@techno-solutions.com',
                'objet' => 'Mise à jour de sécurité importante',
                'contenu' => 'Une mise à jour de sécurité critique est disponible pour vos équipements. Merci de l\'installer dans les plus brefs délais. Procédure en pièce jointe.',
                'date_reception' => Carbon::now()->subHours(6),
                'statut' => 'recu',
                'priorite' => 'urgente'
            ],
            [
                'expediteur' => 'Inspection du Travail',
                'email_expediteur' => 'inspection@travail.gouv.fr',
                'objet' => 'Convocation pour contrôle du 15 juin 2025',
                'contenu' => 'Monsieur, Madame, vous êtes convoqués pour un contrôle de conformité le 15 juin 2025 à 14h00. Merci de préparer les documents listés en annexe.',
                'date_reception' => Carbon::now()->subDays(6),
                'statut' => 'distribue',
                'priorite' => 'urgente'
            ],
            [
                'expediteur' => 'Newsletter Marketing',
                'email_expediteur' => 'news@marketing-pro.com',
                'objet' => 'Nouvelles tendances du marché 2025',
                'contenu' => 'Découvrez les nouvelles tendances du marché pour 2025. Notre équipe d\'analystes a préparé un rapport complet sur les évolutions à prévoir.',
                'date_reception' => Carbon::now()->subDays(8),
                'statut' => 'recu',
                'priorite' => 'normale'
            ],
            [
                'expediteur' => 'Directeur Commercial XYZ',
                'email_expediteur' => 'directeur@xyz-company.com',
                'objet' => 'Invitation salon professionnel - Stand partagé',
                'contenu' => 'Bonjour, nous organisons un stand au salon professionnel de septembre. Seriez-vous intéressés pour partager un espace ? Conditions avantageuses.',
                'date_reception' => Carbon::now()->subDays(10),
                'statut' => 'traite',
                'priorite' => 'normale'
            ],
            [
                'expediteur' => 'Service Juridique',
                'email_expediteur' => 'juridique@cabinet-avocat.fr',
                'objet' => 'Mise en demeure - Contrat #CNT-2024-089',
                'contenu' => 'Suite aux manquements constatés dans l\'exécution du contrat #CNT-2024-089, nous vous mettons en demeure de vous conformer aux clauses dans un délai de 15 jours.',
                'date_reception' => Carbon::now()->subDays(1),
                'statut' => 'recu',
                'priorite' => 'urgente'
            ],
            [
                'expediteur' => 'Candidat Emploi',
                'email_expediteur' => 'candidat@email.com',
                'objet' => 'Candidature spontanée - Poste de technicien',
                'contenu' => 'Madame, Monsieur, je vous adresse ma candidature spontanée pour un poste de technicien dans votre entreprise. Vous trouverez mon CV en pièce jointe.',
                'date_reception' => Carbon::now()->subDays(9),
                'statut' => 'distribue',
                'priorite' => 'normale'
            ],
            [
                'expediteur' => 'Assurance Professionnelle',
                'email_expediteur' => 'contrats@assurance-pro.fr',
                'objet' => 'Renouvellement contrat assurance - Échéance juin 2025',
                'contenu' => 'Votre contrat d\'assurance professionnelle arrive à échéance le 30 juin 2025. Merci de nous retourner les documents de renouvellement avant le 15 juin.',
                'date_reception' => Carbon::now()->subDays(12),
                'statut' => 'distribue',
                'priorite' => 'haute'
            ],
            [
                'expediteur' => 'Organisme de Formation',
                'email_expediteur' => 'formation@center-learning.com',
                'objet' => 'Catalogue formations 2025 - Nouvelles sessions',
                'contenu' => 'Découvrez notre nouveau catalogue de formations 2025. Nouvelles sessions disponibles en management, qualité et sécurité. Tarifs préférentiels jusqu\'au 30 juin.',
                'date_reception' => Carbon::now()->subDays(15),
                'statut' => 'recu',
                'priorite' => 'normale'
            ],
            [
                'expediteur' => 'Maintenance Équipements',
                'email_expediteur' => 'maintenance@equipment-service.com',
                'objet' => 'Maintenance préventive machine #EQ-456 - Planification',
                'contenu' => 'La maintenance préventive de la machine #EQ-456 est programmée pour la semaine du 16 juin. Merci de confirmer votre disponibilité pour l\'arrêt de production.',
                'date_reception' => Carbon::now()->subDays(3),
                'statut' => 'distribue',
                'priorite' => 'haute'
            ]
        ];

        foreach ($messages as $messageData) {
            // Générer un numéro de référence unique
            $messageData['numero_reference'] = Message::genererNumeroReference();
            
            Message::create($messageData);
        }

        $this->command->info('Messages créés avec succès !');
    }
}