<?php
// 📁 app/Http/Controllers/ServiceController.php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\DistributionMessage;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ServiceController extends Controller
{
    /**
     * Vérifier que l'utilisateur est admin pour toutes les actions
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('check.role:admin');
    }

    /**
     * Afficher la liste de tous les services
     */
    public function index(Request $request)
    {
        $query = Service::withCount([
            'distributionMessages',
            'messagesNonLus',
            'messagesLus'
        ]);

        // Filtrer par statut si demandé
        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }

        // Recherche par nom
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nom_service', 'LIKE', "%{$search}%")
                  ->orWhere('email_service', 'LIKE', "%{$search}%");
            });
        }

        $services = $query->orderBy('nom_service')->paginate(15);

        // Statistiques globales
        $stats = [
            'total' => Service::count(),
            'actifs' => Service::where('statut', 'actif')->count(),
            'inactifs' => Service::where('statut', 'inactif')->count(),
            'avec_email' => Service::whereNotNull('email_service')->count(),
        ];

        return view('services.index', compact('services', 'stats'));
    }

    /**
     * Afficher le formulaire de création d'un nouveau service
     */
    public function create()
    {
        return view('services.create');
    }

    /**
     * Enregistrer un nouveau service
     */
    public function store(Request $request)
    {
        $request->validate([
            'nom_service' => 'required|string|max:100|unique:services,nom_service',
            'email_service' => 'nullable|email|max:150|unique:services,email_service',
            'statut' => 'required|in:actif,inactif',
        ], [
            'nom_service.required' => 'Le nom du service est obligatoire',
            'nom_service.unique' => 'Ce nom de service existe déjà',
            'email_service.email' => 'L\'email doit être valide',
            'email_service.unique' => 'Cet email est déjà utilisé par un autre service',
            'statut.required' => 'Le statut est obligatoire',
        ]);

        $service = Service::create([
            'nom_service' => $request->nom_service,
            'email_service' => $request->email_service,
            'statut' => $request->statut,
        ]);

        return redirect()->route('services.show', $service->id_service)
                        ->with('success', 'Service créé avec succès !');
    }

    /**
     * Afficher les détails d'un service
     */
    public function show(Service $service)
    {
        // Charger les relations avec les statistiques
        $service->load(['distributionMessages.message', 'messages']);

        // Messages récents du service (15 derniers)
        $messagesRecents = $service->messages()
                                 ->withPivot('date_distribution', 'statut_lecture')
                                 ->orderByPivot('date_distribution', 'desc')
                                 ->limit(15)
                                 ->get();

        // Statistiques détaillées
        $stats = [
            'total_messages' => $service->distributionMessages()->count(),
            'messages_lus' => $service->distributionMessages()->where('statut_lecture', 'lu')->count(),
            'messages_non_lus' => $service->distributionMessages()->where('statut_lecture', 'non_lu')->count(),
            'messages_urgent' => $service->messages()->where('priorite', 'urgente')->count(),
            'messages_cette_semaine' => $service->distributionMessages()
                                              ->where('date_distribution', '>=', now()->subWeek())
                                              ->count(),
            'messages_ce_mois' => $service->distributionMessages()
                                        ->where('date_distribution', '>=', now()->subMonth())
                                        ->count(),
        ];

        // Calcul du taux de lecture
        $stats['taux_lecture'] = $stats['total_messages'] > 0 
            ? round(($stats['messages_lus'] / $stats['total_messages']) * 100, 2)
            : 0;

        return view('services.show', compact('service', 'messagesRecents', 'stats'));
    }

    /**
     * Afficher le formulaire de modification
     */
    public function edit(Service $service)
    {
        return view('services.edit', compact('service'));
    }

    /**
     * Mettre à jour un service
     */
    public function update(Request $request, Service $service)
    {
        $request->validate([
            'nom_service' => [
                'required',
                'string',
                'max:100',
                Rule::unique('services', 'nom_service')->ignore($service->id_service, 'id_service')
            ],
            'email_service' => [
                'nullable',
                'email',
                'max:150',
                Rule::unique('services', 'email_service')->ignore($service->id_service, 'id_service')
            ],
            'statut' => 'required|in:actif,inactif',
        ], [
            'nom_service.required' => 'Le nom du service est obligatoire',
            'nom_service.unique' => 'Ce nom de service existe déjà',
            'email_service.email' => 'L\'email doit être valide',
            'email_service.unique' => 'Cet email est déjà utilisé par un autre service',
        ]);

        $service->update([
            'nom_service' => $request->nom_service,
            'email_service' => $request->email_service,
            'statut' => $request->statut,
        ]);

        return redirect()->route('services.show', $service->id_service)
                        ->with('success', 'Service mis à jour avec succès !');
    }

    /**
     * Supprimer un service
     */
    public function destroy(Service $service)
    {
        // Vérifier s'il y a des messages distribués
        $messagesDistribues = $service->distributionMessages()->count();
        
        if ($messagesDistribues > 0) {
            return redirect()->route('services.index')
                           ->with('error', "Impossible de supprimer le service '{$service->nom_service}'. Il a {$messagesDistribues} message(s) distribué(s).");
        }

        $nomService = $service->nom_service;
        $service->delete();

        return redirect()->route('services.index')
                        ->with('success', "Service '{$nomService}' supprimé avec succès !");
    }

    /**
     * Activer un service
     */
    public function activate(Service $service)
    {
        $service->activer();

        return redirect()->back()
                        ->with('success', "Service '{$service->nom_service}' activé avec succès !");
    }

    /**
     * Désactiver un service
     */
    public function deactivate(Service $service)
    {
        $service->desactiver();

        return redirect()->back()
                        ->with('success', "Service '{$service->nom_service}' désactivé avec succès !");
    }

    /**
     * Basculer le statut d'un service (actif/inactif)
     */
    public function toggleStatus(Service $service)
    {
        $nouveauStatut = $service->statut === 'actif' ? 'inactif' : 'actif';
        $service->update(['statut' => $nouveauStatut]);

        $message = $nouveauStatut === 'actif' ? 'activé' : 'désactivé';
        
        return redirect()->back()
                        ->with('success', "Service '{$service->nom_service}' {$message} avec succès !");
    }

    /**
     * Afficher les messages d'un service spécifique
     */
    public function messages(Service $service, Request $request)
    {
        $query = $service->messages()
                        ->withPivot('date_distribution', 'statut_lecture')
                        ->orderByPivot('date_distribution', 'desc');

        // Filtrer par statut de lecture
        if ($request->filled('statut_lecture')) {
            $query->wherePivot('statut_lecture', $request->statut_lecture);
        }

        // Filtrer par priorité
        if ($request->filled('priorite')) {
            $query->where('priorite', $request->priorite);
        }

        // Filtrer par période
        if ($request->filled('periode')) {
            switch ($request->periode) {
                case 'aujourd_hui':
                    $query->wherePivot('date_distribution', '>=', today());
                    break;
                case 'cette_semaine':
                    $query->wherePivot('date_distribution', '>=', now()->subWeek());
                    break;
                case 'ce_mois':
                    $query->wherePivot('date_distribution', '>=', now()->subMonth());
                    break;
            }
        }

        $messages = $query->paginate(15);

        return view('services.messages', compact('service', 'messages'));
    }

    /**
     * Exporter la liste des services
     */
    public function export(Request $request)
    {
        $services = Service::with(['distributionMessages'])->get();

        $csvContent = "Nom du Service,Email,Statut,Total Messages,Messages Lus,Messages Non Lus,Taux de Lecture\n";
        
        foreach ($services as $service) {
            $totalMessages = $service->distributionMessages->count();
            $messagesLus = $service->distributionMessages->where('statut_lecture', 'lu')->count();
            $messagesNonLus = $service->distributionMessages->where('statut_lecture', 'non_lu')->count();
            $tauxLecture = $totalMessages > 0 ? round(($messagesLus / $totalMessages) * 100, 2) : 0;

            $csvContent .= sprintf(
                "%s,%s,%s,%d,%d,%d,%s%%\n",
                $service->nom_service,
                $service->email_service ?? '',
                ucfirst($service->statut),
                $totalMessages,
                $messagesLus,
                $messagesNonLus,
                $tauxLecture
            );
        }

        $filename = 'services_' . date('Y-m-d_H-i-s') . '.csv';
        
        return response($csvContent)
               ->header('Content-Type', 'text/csv')
               ->header('Content-Disposition', "attachment; filename={$filename}");
    }
}