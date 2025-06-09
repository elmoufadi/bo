<?php
// 📁 app/Http/Controllers/StatistiqueController.php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\Service;
use App\Models\Utilisateur;
use App\Models\DistributionMessage;
use App\Models\PieceJointe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class StatistiqueController extends Controller
{
    /**
     * S'assurer que l'utilisateur est connecté
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Dashboard principal avec toutes les statistiques
     */
    public function dashboard(Request $request)
    {
        $periode = $request->get('periode', 30); // 30 jours par défaut
        $user = Auth::user();

        // Statistiques générales
        $statsGenerales = $this->getStatistiquesGenerales($periode);

        // Évolution des messages (30 derniers jours)
        $evolutionMessages = $this->getEvolutionMessages($periode);

        // Répartition par priorité
        $repartitionPriorite = $this->getRepartitionPriorite($periode);

        // Répartition par statut
        $repartitionStatut = $this->getRepartitionStatut($periode);

        // Top services les plus actifs
        $topServices = $this->getTopServices($periode);

        // Messages urgents non traités
        $messagesUrgents = $this->getMessagesUrgents();

        // Statistiques de lecture
        $statsLecture = $this->getStatistiquesLecture($periode);

        // Performance par service
        $performanceServices = $this->getPerformanceServices($periode);

        return view('statistiques.dashboard', compact(
            'statsGenerales',
            'evolutionMessages',
            'repartitionPriorite',
            'repartitionStatut',
            'topServices',
            'messagesUrgents',
            'statsLecture',
            'performanceServices',
            'periode'
        ));
    }

    /**
     * Statistiques détaillées des messages
     */
    public function messages(Request $request)
    {
        $periode = $request->get('periode', 30);

        // Messages par mois (12 derniers mois)
        $messagesParMois = $this->getMessagesParMois();

        // Messages par jour de la semaine
        $messagesParJourSemaine = $this->getMessagesParJourSemaine($periode);

        // Messages par heure de la journée
        $messagesParHeure = $this->getMessagesParHeure($periode);

        // Temps de traitement moyen
        $tempsTraitement = $this->getTempsTraitement($periode);

        // Messages par expéditeur
        $messagesParExpediteur = $this->getMessagesParExpediteur($periode);

        // Évolution par priorité
        $evolutionPriorite = $this->getEvolutionPriorite();

        return view('statistiques.messages', compact(
            'messagesParMois',
            'messagesParJourSemaine',
            'messagesParHeure',
            'tempsTraitement',
            'messagesParExpediteur',
            'evolutionPriorite',
            'periode'
        ));
    }

    /**
     * Statistiques par service
     */
    public function services(Request $request)
    {
        $periode = $request->get('periode', 30);

        // Performance de chaque service
        $performanceServices = Service::withCount([
            'distributionMessages as total_messages' => function($q) use ($periode) {
                $q->where('date_distribution', '>=', now()->subDays($periode));
            },
            'distributionMessages as messages_lus' => function($q) use ($periode) {
                $q->where('statut_lecture', 'lu')
                  ->where('date_distribution', '>=', now()->subDays($periode));
            },
            'distributionMessages as messages_non_lus' => function($q) use ($periode) {
                $q->where('statut_lecture', 'non_lu')
                  ->where('date_distribution', '>=', now()->subDays($periode));
            }
        ])->where('statut', 'actif')->get();

        // Temps de lecture moyen par service
        $tempsLectureServices = $this->getTempsLectureParService($periode);

        // Messages par service et par priorité
        $messagesParServicePriorite = $this->getMessagesParServicePriorite($periode);

        // Évolution du taux de lecture par service
        $evolutionTauxLecture = $this->getEvolutionTauxLecture();

        return view('statistiques.services', compact(
            'performanceServices',
            'tempsLectureServices',
            'messagesParServicePriorite',
            'evolutionTauxLecture',
            'periode'
        ));
    }

    /**
     * Statistiques des utilisateurs (admin seulement)
     */
    public function utilisateurs(Request $request)
    {
        if (!Auth::user()->estAdmin()) {
            abort(403, 'Accès réservé aux administrateurs');
        }

        $periode = $request->get('periode', 30);

        // Répartition par rôle
        $repartitionRoles = Utilisateur::selectRaw('role, count(*) as count')
                                     ->groupBy('role')
                                     ->pluck('count', 'role');

        // Répartition par statut
        $repartitionStatuts = Utilisateur::selectRaw('statut, count(*) as count')
                                        ->groupBy('statut')
                                        ->pluck('count', 'statut');

        // Utilisateurs les plus récents
        $utilisateursRecents = Utilisateur::orderBy('created_at', 'desc')
                                        ->limit(10)
                                        ->get();

        // Activité par utilisateur (si logs disponibles)
        $activiteUtilisateurs = $this->getActiviteUtilisateurs($periode);

        return view('statistiques.utilisateurs', compact(
            'repartitionRoles',
            'repartitionStatuts',
            'utilisateursRecents',
            'activiteUtilisateurs',
            'periode'
        ));
    }

    /**
     * Statistiques des pièces jointes
     */
    public function piecesJointes(Request $request)
    {
        $periode = $request->get('periode', 30);

        // Stats générales des pièces jointes
        $statsGenerales = PieceJointe::where('created_at', '>=', now()->subDays($periode))
                                   ->selectRaw('
                                       count(*) as total,
                                       sum(taille_fichier) as taille_totale,
                                       avg(taille_fichier) as taille_moyenne,
                                       max(taille_fichier) as plus_gros_fichier
                                   ')
                                   ->first();

        // Répartition par type de fichier
        $repartitionTypes = PieceJointe::where('created_at', '>=', now()->subDays($periode))
                                     ->get()
                                     ->groupBy(function($piece) {
                                         return $piece->categorie;
                                     })
                                     ->map->count();

        // Évolution des uploads
        $evolutionUploads = $this->getEvolutionUploads($periode);

        // Top des plus gros fichiers
        $plusGrosFichiers = PieceJointe::with(['message'])
                                     ->where('created_at', '>=', now()->subDays($periode))
                                     ->orderBy('taille_fichier', 'desc')
                                     ->limit(10)
                                     ->get();

        // Messages avec le plus de pièces jointes
        $messagesAvecPlusDePieces = Message::withCount('piecesJointes')
                                         ->where('date_reception', '>=', now()->subDays($periode))
                                         ->orderBy('pieces_jointes_count', 'desc')
                                         ->limit(10)
                                         ->get();

        return view('statistiques.pieces-jointes', compact(
            'statsGenerales',
            'repartitionTypes',
            'evolutionUploads',
            'plusGrosFichiers',
            'messagesAvecPlusDePieces',
            'periode'
        ));
    }

    /**
     * Rapport complet exportable
     */
    public function rapport(Request $request)
    {
        $periode = $request->get('periode', 30);
        $format = $request->get('format', 'web'); // web, pdf, excel

        // Collecter toutes les données
        $donnees = [
            'periode' => $periode,
            'date_generation' => now(),
            'statistiques_generales' => $this->getStatistiquesGenerales($periode),
            'evolution_messages' => $this->getEvolutionMessages($periode),
            'performance_services' => $this->getPerformanceServices($periode),
            'statistiques_lecture' => $this->getStatistiquesLecture($periode),
            'repartition_priorite' => $this->getRepartitionPriorite($periode),
            'top_services' => $this->getTopServices($periode),
            'messages_urgents' => $this->getMessagesUrgents(),
        ];

        if ($format === 'web') {
            return view('statistiques.rapport', compact('donnees'));
        }

        // Pour PDF et Excel, on pourrait ajouter des packages comme DomPDF ou Maatwebsite/Excel
        // Pour l'instant, retourner un CSV simple
        if ($format === 'csv') {
            return $this->exportCSV($donnees);
        }

        return view('statistiques.rapport', compact('donnees'));
    }

    /**
     * API pour les graphiques en temps réel
     */
    public function apiDashboard(Request $request)
    {
        $periode = $request->get('periode', 7);

        return response()->json([
            'stats_generales' => $this->getStatistiquesGenerales($periode),
            'evolution_messages' => $this->getEvolutionMessages($periode),
            'repartition_priorite' => $this->getRepartitionPriorite($periode),
            'top_services' => $this->getTopServices($periode),
            'derniere_mise_a_jour' => now()->format('H:i:s')
        ]);
    }

    /**
     * Méthodes privées pour récupérer les données
     */
    private function getStatistiquesGenerales($periode)
    {
        $dateDebut = now()->subDays($periode);
        
        return [
            'total_messages' => Message::count(),
            'messages_periode' => Message::where('date_reception', '>=', $dateDebut)->count(),
            'messages_traites' => Message::where('statut', 'traite')->count(),
            'messages_urgents' => Message::where('priorite', 'urgente')->where('statut', '!=', 'traite')->count(),
            'total_services' => Service::where('statut', 'actif')->count(),
            'total_utilisateurs' => Utilisateur::where('statut', 'actif')->count(),
            'total_distributions' => DistributionMessage::where('date_distribution', '>=', $dateDebut)->count(),
            'taux_lecture_global' => $this->calculerTauxLecture($periode),
            'pieces_jointes' => PieceJointe::where('created_at', '>=', $dateDebut)->count(),
            'espace_stockage' => PieceJointe::sum('taille_fichier'),
        ];
    }

    private function getEvolutionMessages($periode)
    {
        $evolution = [];
        
        for ($i = $periode - 1; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $count = Message::whereDate('date_reception', $date->toDateString())->count();
            
            $evolution[] = [
                'date' => $date->format('d/m'),
                'count' => $count,
                'date_complete' => $date->format('Y-m-d')
            ];
        }
        
        return $evolution;
    }

    private function getRepartitionPriorite($periode)
    {
        return Message::where('date_reception', '>=', now()->subDays($periode))
                     ->selectRaw('priorite, count(*) as count')
                     ->groupBy('priorite')
                     ->pluck('count', 'priorite')
                     ->toArray();
    }

    private function getRepartitionStatut($periode)
    {
        return Message::where('date_reception', '>=', now()->subDays($periode))
                     ->selectRaw('statut, count(*) as count')
                     ->groupBy('statut')
                     ->pluck('count', 'statut')
                     ->toArray();
    }

    private function getTopServices($periode)
    {
        return Service::withCount([
            'distributionMessages as messages_recus' => function($q) use ($periode) {
                $q->where('date_distribution', '>=', now()->subDays($periode));
            }
        ])->where('statut', 'actif')
          ->orderBy('messages_recus', 'desc')
          ->limit(10)
          ->get();
    }

    private function getMessagesUrgents()
    {
        return Message::where('priorite', 'urgente')
                     ->where('statut', '!=', 'traite')
                     ->with(['distributionMessages.service'])
                     ->orderBy('date_reception', 'desc')
                     ->limit(10)
                     ->get();
    }

    private function getStatistiquesLecture($periode)
    {
        $distributions = DistributionMessage::where('date_distribution', '>=', now()->subDays($periode));
        
        $total = $distributions->count();
        $lus = $distributions->where('statut_lecture', 'lu')->count();
        
        return [
            'total_distributions' => $total,
            'messages_lus' => $lus,
            'messages_non_lus' => $total - $lus,
            'taux_lecture' => $total > 0 ? round(($lus / $total) * 100, 2) : 0,
            'temps_lecture_moyen' => $this->calculerTempsLectureMoyen($periode)
        ];
    }

    private function getPerformanceServices($periode)
    {
        return Service::with(['distributionMessages' => function($q) use ($periode) {
                        $q->where('date_distribution', '>=', now()->subDays($periode));
                    }])
                    ->where('statut', 'actif')
                    ->get()
                    ->map(function($service) {
                        $distributions = $service->distributionMessages;
                        $total = $distributions->count();
                        $lus = $distributions->where('statut_lecture', 'lu')->count();
                        
                        return [
                            'service' => $service->nom_service,
                            'total_messages' => $total,
                            'messages_lus' => $lus,
                            'messages_non_lus' => $total - $lus,
                            'taux_lecture' => $total > 0 ? round(($lus / $total) * 100, 2) : 0
                        ];
                    });
    }

    private function getMessagesParMois()
    {
        $messagesParMois = [];
        
        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $count = Message::whereYear('date_reception', $date->year)
                           ->whereMonth('date_reception', $date->month)
                           ->count();
            
            $messagesParMois[] = [
                'mois' => $date->format('M Y'),
                'count' => $count
            ];
        }
        
        return $messagesParMois;
    }

    private function getMessagesParJourSemaine($periode)
    {
        return Message::where('date_reception', '>=', now()->subDays($periode))
                     ->selectRaw('DAYOFWEEK(date_reception) as jour, count(*) as count')
                     ->groupBy('jour')
                     ->orderBy('jour')
                     ->get()
                     ->mapWithKeys(function($item) {
                         $jours = ['Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'];
                         return [$jours[$item->jour - 1] => $item->count];
                     });
    }

    private function getMessagesParHeure($periode)
    {
        return Message::where('date_reception', '>=', now()->subDays($periode))
                     ->selectRaw('HOUR(date_reception) as heure, count(*) as count')
                     ->groupBy('heure')
                     ->orderBy('heure')
                     ->pluck('count', 'heure')
                     ->toArray();
    }

    private function calculerTauxLecture($periode)
    {
        $total = DistributionMessage::where('date_distribution', '>=', now()->subDays($periode))->count();
        $lus = DistributionMessage::where('date_distribution', '>=', now()->subDays($periode))
                                 ->where('statut_lecture', 'lu')
                                 ->count();
        
        return $total > 0 ? round(($lus / $total) * 100, 2) : 0;
    }

    private function calculerTempsLectureMoyen($periode)
    {
        $distributionsLues = DistributionMessage::where('date_distribution', '>=', now()->subDays($periode))
                                               ->where('statut_lecture', 'lu')
                                               ->get();
        
        if ($distributionsLues->isEmpty()) {
            return 0;
        }

        $tempsTotal = $distributionsLues->sum(function($dist) {
            return $dist->getDureeAvantLecture() ?? 0;
        });
        
        return round($tempsTotal / $distributionsLues->count(), 2);
    }

    private function getEvolutionUploads($periode)
    {
        $evolution = [];
        
        for ($i = $periode - 1; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $count = PieceJointe::whereDate('created_at', $date->toDateString())->count();
            
            $evolution[] = [
                'date' => $date->format('d/m'),
                'count' => $count
            ];
        }
        
        return $evolution;
    }

    private function exportCSV($donnees)
    {
        $csv = "Rapport Statistiques Bureau d'Ordre\n";
        $csv .= "Période: {$donnees['periode']} jours\n";
        $csv .= "Généré le: " . $donnees['date_generation']->format('d/m/Y H:i') . "\n\n";
        
        $csv .= "STATISTIQUES GÉNÉRALES\n";
        foreach ($donnees['statistiques_generales'] as $key => $value) {
            $csv .= str_replace('_', ' ', ucfirst($key)) . "," . $value . "\n";
        }
        
        $filename = 'rapport_statistiques_' . date('Y-m-d_H-i-s') . '.csv';
        
        return response($csv)
               ->header('Content-Type', 'text/csv; charset=utf-8')
               ->header('Content-Disposition', "attachment; filename={$filename}");
    }

    // Méthodes additionnelles pour données plus spécifiques
    private function getTempsTraitement($periode) { return []; }
    private function getMessagesParExpediteur($periode) { return []; }
    private function getEvolutionPriorite() { return []; }
    private function getTempsLectureParService($periode) { return []; }
    private function getMessagesParServicePriorite($periode) { return []; }
    private function getEvolutionTauxLecture() { return []; }
    private function getActiviteUtilisateurs($periode) { return []; }
}