<?php
// 📁 app/Http/Controllers/DistributionController.php

namespace App\Http\Controllers;

use App\Models\DistributionMessage;
use App\Models\Message;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DistributionController extends Controller
{
    /**
     * S'assurer que l'utilisateur est connecté
     */
    public function __construct()
    {
        $this->Middleware('auth');
    }

    /**
     * Afficher toutes les distributions avec filtres
     */
    public function index(Request $request)
    {
        $query = DistributionMessage::with(['message', 'service'])
                                   ->orderBy('date_distribution', 'desc');

        // Filtrer par service
        if ($request->filled('service_id')) {
            $query->where('id_service', $request->service_id);
        }

        // Filtrer par statut de lecture
        if ($request->filled('statut_lecture')) {
            $query->where('statut_lecture', $request->statut_lecture);
        }

        // Filtrer par priorité du message
        if ($request->filled('priorite')) {
            $query->whereHas('message', function($q) use ($request) {
                $q->where('priorite', $request->priorite);
            });
        }

        // Filtrer par période
        if ($request->filled('periode')) {
            switch ($request->periode) {
                case 'aujourd_hui':
                    $query->whereDate('date_distribution', today());
                    break;
                case 'cette_semaine':
                    $query->where('date_distribution', '>=', now()->subWeek());
                    break;
                case 'ce_mois':
                    $query->where('date_distribution', '>=', now()->subMonth());
                    break;
                case 'derniers_30_jours':
                    $query->where('date_distribution', '>=', now()->subDays(30));
                    break;
            }
        }

        // Recherche dans le contenu des messages
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('message', function($q) use ($search) {
                $q->where('objet', 'LIKE', "%{$search}%")
                  ->orWhere('expediteur', 'LIKE', "%{$search}%")
                  ->orWhere('numero_reference', 'LIKE', "%{$search}%");
            });
        }

        $distributions = $query->paginate(20);

        // Récupérer les services pour les filtres
        $services = Service::where('statut', 'actif')->orderBy('nom_service')->get();

        // Statistiques générales
        $stats = [
            'total_distributions' => DistributionMessage::count(),
            'messages_lus' => DistributionMessage::where('statut_lecture', 'lu')->count(),
            'messages_non_lus' => DistributionMessage::where('statut_lecture', 'non_lu')->count(),
            'distributions_aujourd_hui' => DistributionMessage::whereDate('date_distribution', today())->count(),
            'distributions_cette_semaine' => DistributionMessage::where('date_distribution', '>=', now()->subWeek())->count(),
        ];

        // Calcul du taux de lecture
        $stats['taux_lecture'] = $stats['total_distributions'] > 0 
            ? round(($stats['messages_lus'] / $stats['total_distributions']) * 100, 2)
            : 0;

        return view('distributions.index', compact('distributions', 'services', 'stats'));
    }

    /**
     * Afficher les distributions d'un service spécifique
     */
    public function parService(Service $service, Request $request)
    {
        $query = $service->distributionMessages()
                        ->with(['message'])
                        ->orderBy('date_distribution', 'desc');

        // Filtrer par statut de lecture
        if ($request->filled('statut_lecture')) {
            $query->where('statut_lecture', $request->statut_lecture);
        }

        // Filtrer par priorité
        if ($request->filled('priorite')) {
            $query->whereHas('message', function($q) use ($request) {
                $q->where('priorite', $request->priorite);
            });
        }

        // Filtrer par période
        if ($request->filled('periode')) {
            switch ($request->periode) {
                case 'aujourd_hui':
                    $query->whereDate('date_distribution', today());
                    break;
                case 'cette_semaine':
                    $query->where('date_distribution', '>=', now()->subWeek());
                    break;
                case 'ce_mois':
                    $query->where('date_distribution', '>=', now()->subMonth());
                    break;
            }
        }

        $distributions = $query->paginate(15);

        // Statistiques du service
        $stats = DistributionMessage::statistiquesParService($service->id_service);

        return view('distributions.par-service', compact('service', 'distributions', 'stats'));
    }

    /**
     * Marquer une distribution comme lue
     */
    public function marquerCommeLu(DistributionMessage $distribution)
    {
        $distribution->marquerCommeLu();

        return response()->json([
            'success' => true,
            'message' => 'Message marqué comme lu',
            'statut' => 'lu'
        ]);
    }

    /**
     * Marquer une distribution comme non lue
     */
    public function marquerCommeNonLu(DistributionMessage $distribution)
    {
        $distribution->marquerCommeNonLu();

        return response()->json([
            'success' => true,
            'message' => 'Message marqué comme non lu',
            'statut' => 'non_lu'
        ]);
    }

    /**
     * Marquer toutes les distributions d'un service comme lues
     */
    public function marquerToutCommeLu(Service $service)
    {
        $distributionsNonLues = $service->distributionMessages()
                                       ->where('statut_lecture', 'non_lu')
                                       ->get();

        foreach ($distributionsNonLues as $distribution) {
            $distribution->marquerCommeLu();
        }

        $count = $distributionsNonLues->count();

        return redirect()->back()->with('success', "{$count} message(s) marqué(s) comme lu(s) pour le service {$service->nom_service}");
    }

    /**
     * Afficher le détail d'une distribution
     */
    public function show(DistributionMessage $distribution)
    {
        $distribution->load(['message.piecesJointes', 'service']);

        // Marquer automatiquement comme lu si c'était non lu
        if ($distribution->estNonLu()) {
            $distribution->marquerCommeLu();
        }

        return view('distributions.show', compact('distribution'));
    }

    /**
     * Supprimer une distribution
     */
    public function destroy(DistributionMessage $distribution)
    {
        // Vérifier les permissions
        $user = Auth::user();
        if (!$user->estAdmin()) {
            abort(403, 'Seuls les administrateurs peuvent supprimer les distributions');
        }

        $serviceName = $distribution->service->nom_service;
        $messageObjet = $distribution->message->objet;
        
        $distribution->delete();

        return redirect()->route('distributions.index')
                        ->with('success', "Distribution supprimée : '{$messageObjet}' pour le service '{$serviceName}'");
    }

    /**
     * Messages non lus pour l'utilisateur connecté
     */
    public function messagesNonLus()
    {
        $user = Auth::user();
        
        // Si l'utilisateur est admin, voir tous les messages non lus
        if ($user->estAdmin()) {
            $distributions = DistributionMessage::where('statut_lecture', 'non_lu')
                                               ->with(['message', 'service'])
                                               ->orderBy('date_distribution', 'desc')
                                               ->paginate(20);
        } else {
            // Pour les opérateurs, voir seulement leurs services (à implémenter si besoin)
            $distributions = collect(); // Vide pour l'instant
        }

        return view('distributions.non-lus', compact('distributions'));
    }

    /**
     * Messages urgents non traités
     */
    public function messagesUrgents()
    {
        $distributions = DistributionMessage::whereHas('message', function($q) {
                                               $q->where('priorite', 'urgente')
                                                 ->where('statut', '!=', 'traite');
                                           })
                                           ->with(['message', 'service'])
                                           ->orderBy('date_distribution', 'desc')
                                           ->paginate(15);

        return view('distributions.urgents', compact('distributions'));
    }

    /**
     * Statistiques globales des distributions
     */
    public function statistiques(Request $request)
    {
        $periode = $request->get('periode', 30); // 30 jours par défaut

        // Statistiques générales
        $statsGenerales = DistributionMessage::statistiquesGlobales($periode);

        // Distributions par service
        $distributionsParService = Service::withCount([
            'distributionMessages',
            'distributionMessages as messages_lus' => function($q) use ($periode) {
                $q->where('statut_lecture', 'lu')
                  ->where('date_distribution', '>=', now()->subDays($periode));
            },
            'distributionMessages as messages_non_lus' => function($q) use ($periode) {
                $q->where('statut_lecture', 'non_lu')
                  ->where('date_distribution', '>=', now()->subDays($periode));
            }
        ])->where('statut', 'actif')->get();

        // Évolution des distributions par jour (30 derniers jours)
        $evolutionQuotidienne = [];
        for ($i = 29; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $count = DistributionMessage::whereDate('date_distribution', $date->toDateString())->count();
            
            $evolutionQuotidienne[] = [
                'date' => $date->format('d/m'),
                'count' => $count
            ];
        }

        // Messages par priorité
        $messagesParPriorite = DistributionMessage::join('messages', 'distribution_messages.id_message', '=', 'messages.id_message')
                                                 ->where('distribution_messages.date_distribution', '>=', now()->subDays($periode))
                                                 ->selectRaw('messages.priorite, count(*) as count')
                                                 ->groupBy('messages.priorite')
                                                 ->pluck('count', 'priorite')
                                                 ->toArray();

        // Temps de lecture moyen par service
        $tempsLectureParService = Service::with(['distributionMessages' => function($q) use ($periode) {
                                           $q->where('statut_lecture', 'lu')
                                             ->where('date_distribution', '>=', now()->subDays($periode));
                                       }])
                                       ->where('statut', 'actif')
                                       ->get()
                                       ->map(function($service) {
                                           $distributionsLues = $service->distributionMessages;
                                           $tempsLectureMoyen = $distributionsLues->avg(function($dist) {
                                               return $dist->getDureeAvantLecture();
                                           });
                                           
                                           return [
                                               'service' => $service->nom_service,
                                               'temps_moyen' => round($tempsLectureMoyen ?? 0, 2)
                                           ];
                                       });

        return view('distributions.statistiques', compact(
            'statsGenerales',
            'distributionsParService', 
            'evolutionQuotidienne',
            'messagesParPriorite',
            'tempsLectureParService',
            'periode'
        ));
    }

    /**
     * Distribuer un message à plusieurs services
     */
    public function distribuer(Request $request, Message $message)
    {
        $request->validate([
            'services' => 'required|array|min:1',
            'services.*' => 'exists:services,id_service'
        ], [
            'services.required' => 'Veuillez sélectionner au moins un service',
            'services.min' => 'Veuillez sélectionner au moins un service',
        ]);

        $nouveauxServices = [];
        
        foreach ($request->services as $serviceId) {
            $distribution = DistributionMessage::firstOrCreate([
                'id_message' => $message->id_message,
                'id_service' => $serviceId,
            ], [
                'date_distribution' => now(),
                'statut_lecture' => 'non_lu'
            ]);

            if ($distribution->wasRecentlyCreated) {
                $service = Service::find($serviceId);
                $nouveauxServices[] = $service->nom_service;
            }
        }

        // Mettre à jour le statut du message
        if ($message->statut === 'recu') {
            $message->update(['statut' => 'distribue']);
        }

        if (count($nouveauxServices) > 0) {
            $servicesNoms = implode(', ', $nouveauxServices);
            return redirect()->back()->with('success', "Message distribué avec succès aux services : {$servicesNoms}");
        } else {
            return redirect()->back()->with('info', 'Le message était déjà distribué à tous les services sélectionnés');
        }
    }

    /**
     * Exporter les distributions
     */
    public function export(Request $request)
    {
        $query = DistributionMessage::with(['message', 'service']);

        // Appliquer les mêmes filtres que l'index
        if ($request->filled('service_id')) {
            $query->where('id_service', $request->service_id);
        }

        if ($request->filled('statut_lecture')) {
            $query->where('statut_lecture', $request->statut_lecture);
        }

        if ($request->filled('periode')) {
            switch ($request->periode) {
                case 'aujourd_hui':
                    $query->whereDate('date_distribution', today());
                    break;
                case 'cette_semaine':
                    $query->where('date_distribution', '>=', now()->subWeek());
                    break;
                case 'ce_mois':
                    $query->where('date_distribution', '>=', now()->subMonth());
                    break;
            }
        }

        $distributions = $query->orderBy('date_distribution', 'desc')->get();

        $csvContent = "Date Distribution,Service,Message,Expediteur,Priorite,Statut Lecture,Delai\n";
        
        foreach ($distributions as $distribution) {
            $csvContent .= sprintf(
                "%s,%s,%s,%s,%s,%s,%s\n",
                $distribution->date_distribution->format('d/m/Y H:i'),
                $distribution->service->nom_service,
                str_replace(',', ';', $distribution->message->objet),
                str_replace(',', ';', $distribution->message->expediteur),
                ucfirst($distribution->message->priorite),
                ucfirst($distribution->statut_lecture),
                $distribution->delai_lecture
            );
        }

        $filename = 'distributions_' . date('Y-m-d_H-i-s') . '.csv';
        
        return response($csvContent)
               ->header('Content-Type', 'text/csv; charset=utf-8')
               ->header('Content-Disposition', "attachment; filename={$filename}");
    }
}