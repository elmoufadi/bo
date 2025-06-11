<?php
// 📁 app/Http/Controllers/MessageController.php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\Service;
use App\Models\DistributionMessage;
use App\Models\PieceJointe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class MessageController extends Controller
{
    /**
     * Afficher la liste des messages avec filtres
     */
    public function index(Request $request)
    {
        $query = Message::with(['distributionMessages.service', 'piecesJointes'])
                       ->orderBy('date_reception', 'desc');

        // Filtres
        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }

        if ($request->filled('priorite')) {
            $query->where('priorite', $request->priorite);
        }

        if ($request->filled('date_debut')) {
            $query->whereDate('date_reception', '>=', $request->date_debut);
        }

        if ($request->filled('date_fin')) {
            $query->whereDate('date_reception', '<=', $request->date_fin);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('objet', 'LIKE', "%{$search}%")
                  ->orWhere('expediteur', 'LIKE', "%{$search}%")
                  ->orWhere('numero_reference', 'LIKE', "%{$search}%")
                  ->orWhere('contenu', 'LIKE', "%{$search}%");
            });
        }

        $messages = $query->paginate(15);

        // Statistiques pour le dashboard
        $stats = [
            'total' => Message::count(),
            'recu' => Message::where('statut', 'recu')->count(),
            'distribue' => Message::where('statut', 'distribue')->count(),
            'traite' => Message::where('statut', 'traite')->count(),
            'urgents' => Message::where('priorite', 'urgente')->count(),
        ];

        return view('messages.index', compact('messages', 'stats'));
    }

    /**
     * Afficher le formulaire de création d'un nouveau message
     */
    public function create()
    {
        if (auth()->user()->role === 'admin') {
            return redirect()->route('messages.index')->with('error', 'Admins cannot create new messages.');
        }
        $services = Service::all();
        return view('messages.create', compact('services'));
    }

    /**
     * Enregistrer un nouveau message
     */
    public function store(Request $request)
    {
        if (auth()->user()->role === 'admin') {
            return redirect()->route('messages.index')->with('error', 'Admins cannot create new messages.');
        }
        $request->validate([
            'expediteur' => 'required|string|max:200',
            'email_expediteur' => 'nullable|email|max:150',
            'objet' => 'required|string|max:200',
            'contenu' => 'required|string',
            'priorite' => 'required|in:normale,haute,urgente',
            'pieces_jointes.*' => 'nullable|file|max:10240', // 10MB max
            'services' => 'nullable|array',
            'services.*' => 'exists:services,id_service'
        ], [
            'expediteur.required' => 'L\'expéditeur est obligatoire',
            'objet.required' => 'L\'objet est obligatoire',
            'contenu.required' => 'Le contenu est obligatoire',
            'priorite.required' => 'La priorité est obligatoire',
            'pieces_jointes.*.max' => 'Chaque fichier ne doit pas dépasser 10MB',
        ]);

        // Créer le message
        $message = Message::create([
            'numero_reference' => Message::genererNumeroReference(),
            'expediteur' => $request->expediteur,
            'email_expediteur' => $request->email_expediteur,
            'objet' => $request->objet,
            'contenu' => $request->contenu,
            'date_reception' => now(),
            'statut' => 'recu',
            'priorite' => $request->priorite,
        ]);

        // Gérer les pièces jointes
        if ($request->hasFile('pieces_jointes')) {
            foreach ($request->file('pieces_jointes') as $file) {
                $this->ajouterPieceJointe($message, $file);
            }
        }

        // Distribuer automatiquement si des services sont sélectionnés
        if ($request->filled('services')) {
            $this->distribuerMessage($message, $request->services);
        }

        return redirect()->route('messages.show', $message->id_message)
                        ->with('success', 'Message créé avec succès !');
    }

    /**
     * Afficher les détails d'un message
     */
    public function show(Message $message)
    {
        $message->load(['distributionMessages.service', 'piecesJointes']);
        $services = Service::where('statut', 'actif')->get();
        
        return view('messages.show', compact('message', 'services'));
    }

    /**
     * Afficher le formulaire de modification
     */
    public function edit(Message $message)
    {
        $services = Service::where('statut', 'actif')->get();
        $servicesDistribues = $message->distributionMessages->pluck('id_service')->toArray();
        
        return view('messages.edit', compact('message', 'services', 'servicesDistribues'));
    }

    /**
     * Mettre à jour un message
     */
    public function update(Request $request, Message $message)
    {
        $request->validate([
            'expediteur' => 'required|string|max:200',
            'email_expediteur' => 'nullable|email|max:150',
            'objet' => 'required|string|max:200',
            'contenu' => 'required|string',
            'priorite' => 'required|in:normale,haute,urgente',
            'statut' => 'required|in:recu,distribue,traite',
            'pieces_jointes.*' => 'nullable|file|max:10240', // 10MB max
            'services' => 'nullable|array',
            'services.*' => 'exists:services,id_service'
        ]);

        $message->update([
            'expediteur' => $request->expediteur,
            'email_expediteur' => $request->email_expediteur,
            'objet' => $request->objet,
            'contenu' => $request->contenu,
            'priorite' => $request->priorite,
            'statut' => $request->statut,
        ]);

        // Gérer les pièces jointes (ajout de nouvelles)
        if ($request->hasFile('pieces_jointes')) {
            foreach ($request->file('pieces_jointes') as $file) {
                $this->ajouterPieceJointe($message, $file);
            }
        }

        // Synchroniser les services concernés
        if ($request->filled('services')) {
            $message->services()->sync($request->services);
        } else {
            $message->services()->sync([]); // Détacher tous les services si aucun n'est sélectionné
        }

        return redirect()->route('messages.show', $message->id_message)
                        ->with('success', 'Message mis à jour avec succès !');
    }

    /**
     * Supprimer un message
     */
    public function destroy(Message $message)
    {
        // Supprimer les pièces jointes du stockage
        foreach ($message->piecesJointes as $piece) {
            if (Storage::exists($piece->chemin_fichier)) {
                Storage::delete($piece->chemin_fichier);
            }
        }

        // Supprimer le message (cascade supprimera les relations)
        $message->delete();

        return redirect()->route('messages.index')
                        ->with('success', 'Message supprimé avec succès !');
    }

    /**
     * Distribuer un message aux services sélectionnés
     */
    public function distribute(Request $request, Message $message)
    {
        $request->validate([
            'services' => 'required|array|min:1',
            'services.*' => 'exists:services,id_service'
        ], [
            'services.required' => 'Veuillez sélectionner au moins un service',
            'services.min' => 'Veuillez sélectionner au moins un service',
        ]);

        $this->distribuerMessage($message, $request->services);

        return redirect()->route('messages.show', $message->id_message)
                        ->with('success', 'Message distribué avec succès !');
    }

    /**
     * Marquer un message comme traité
     */
    public function markAsProcessed(Message $message)
    {
        $message->marquerCommeTraite();

        return redirect()->route('messages.show', $message->id_message)
                        ->with('success', 'Message marqué comme traité !');
    }

    /**
     * Méthodes privées utilitaires
     */
    private function distribuerMessage(Message $message, array $serviceIds)
    {
        foreach ($serviceIds as $serviceId) {
            DistributionMessage::firstOrCreate([
                'id_message' => $message->id_message,
                'id_service' => $serviceId,
            ], [
                'date_distribution' => now(),
                'statut_lecture' => 'non_lu'
            ]);
        }

        // Mettre à jour le statut du message
        if ($message->statut === 'recu') {
            $message->update(['statut' => 'distribue']);
        }
    }

    private function ajouterPieceJointe(Message $message, $file)
    {
        $nomFichier = PieceJointe::genererNomFichierUnique($file->getClientOriginalName());
        $cheminFichier = $file->storeAs('attachments/' . date('Y/m'), $nomFichier);

        PieceJointe::create([
            'id_message' => $message->id_message,
            'nom_fichier' => $file->getClientOriginalName(),
            'chemin_fichier' => $cheminFichier,
            'type_mime' => $file->getMimeType(),
            'taille_fichier' => $file->getSize(),
        ]);
    }

    /**
     * Messages urgents pour notification
     */
    public function urgent()
    {
        $messages = Message::where('priorite', 'urgente')
                          ->where('statut', '!=', 'traite')
                          ->with(['distributionMessages.service'])
                          ->orderBy('date_reception', 'desc')
                          ->paginate(10);

        return view('messages.urgent', compact('messages'));
    }

    /**
     * Messages non distribués
     */
    public function nonDistribues()
    {
        $messages = Message::where('statut', 'recu')
                          ->orderBy('date_reception', 'desc')
                          ->paginate(15);

        return view('messages.non-distribues', compact('messages'));
    }

    /**
     * Recherche avancée
     */
    public function search(Request $request)
    {
        $query = Message::with(['distributionMessages.service', 'piecesJointes']);

        if ($request->filled('q')) {
            $search = $request->q;
            $query->where(function($q) use ($search) {
                $q->where('objet', 'LIKE', "%{$search}%")
                  ->orWhere('expediteur', 'LIKE', "%{$search}%")
                  ->orWhere('numero_reference', 'LIKE', "%{$search}%")
                  ->orWhere('contenu', 'LIKE', "%{$search}%");
            });
        }

        $messages = $query->orderBy('date_reception', 'desc')->paginate(15);

        return view('messages.search-results', compact('messages'));
    }
}