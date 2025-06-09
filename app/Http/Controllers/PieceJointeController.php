<?php
// 📁 app/Http/Controllers/PieceJointeController.php

namespace App\Http\Controllers;

use App\Models\PieceJointe;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;

class PieceJointeController extends Controller
{
    /**
     * S'assurer que l'utilisateur est connecté
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Afficher toutes les pièces jointes avec filtres
     */
    public function index(Request $request)
    {
        $query = PieceJointe::with(['message'])
                           ->orderBy('created_at', 'desc');

        // Filtrer par type de fichier
        if ($request->filled('type_fichier')) {
            $type = $request->type_fichier;
            switch ($type) {
                case 'images':
                    $query->where('type_mime', 'LIKE', 'image/%');
                    break;
                case 'documents':
                    $query->whereIn('type_mime', [
                        'application/pdf',
                        'application/msword',
                        'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
                    ]);
                    break;
                case 'tableurs':
                    $query->whereIn('type_mime', [
                        'application/vnd.ms-excel',
                        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                        'text/csv'
                    ]);
                    break;
                case 'archives':
                    $query->whereIn('type_mime', [
                        'application/zip',
                        'application/x-rar-compressed',
                        'application/x-7z-compressed'
                    ]);
                    break;
            }
        }

        // Filtrer par taille
        if ($request->filled('taille')) {
            switch ($request->taille) {
                case 'petit':
                    $query->where('taille_fichier', '<', 1048576); // < 1MB
                    break;
                case 'moyen':
                    $query->whereBetween('taille_fichier', [1048576, 5242880]); // 1-5MB
                    break;
                case 'grand':
                    $query->where('taille_fichier', '>', 5242880); // > 5MB
                    break;
            }
        }

        // Recherche par nom de fichier
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('nom_fichier', 'LIKE', "%{$search}%");
        }

        // Filtrer par message
        if ($request->filled('message_id')) {
            $query->where('id_message', $request->message_id);
        }

        $piecesJointes = $query->paginate(20);

        // Statistiques
        $stats = PieceJointe::statistiques();

        return view('pieces-jointes.index', compact('piecesJointes', 'stats'));
    }

    /**
     * Afficher le formulaire d'upload
     */
    public function create(Request $request)
    {
        $messageId = $request->get('message_id');
        $message = null;
        
        if ($messageId) {
            $message = Message::find($messageId);
        }

        return view('pieces-jointes.create', compact('message'));
    }

    /**
     * Upload de fichiers
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_message' => 'required|exists:messages,id_message',
            'fichiers.*' => 'required|file|max:20480', // 20MB max
        ], [
            'id_message.required' => 'Le message est obligatoire',
            'id_message.exists' => 'Message non trouvé',
            'fichiers.*.required' => 'Aucun fichier sélectionné',
            'fichiers.*.file' => 'Le fichier n\'est pas valide',
            'fichiers.*.max' => 'Le fichier ne doit pas dépasser 20MB',
        ]);

        $message = Message::findOrFail($request->id_message);
        $fichiersUpload = [];
        $erreurs = [];

        if ($request->hasFile('fichiers')) {
            foreach ($request->file('fichiers') as $file) {
                try {
                    // Vérifier le type de fichier
                    $typesAutorises = [
                        'application/pdf',
                        'application/msword',
                        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                        'application/vnd.ms-excel',
                        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                        'text/csv',
                        'image/jpeg',
                        'image/png',
                        'image/gif',
                        'image/bmp',
                        'application/zip',
                        'application/x-rar-compressed',
                        'text/plain'
                    ];

                    if (!in_array($file->getMimeType(), $typesAutorises)) {
                        $erreurs[] = "Type de fichier non autorisé : {$file->getClientOriginalName()}";
                        continue;
                    }

                    // Générer nom unique
                    $nomFichierUnique = PieceJointe::genererNomFichierUnique($file->getClientOriginalName());
                    
                    // Stocker le fichier
                    $cheminFichier = $file->storeAs(
                        'attachments/' . date('Y/m'), 
                        $nomFichierUnique,
                        'public'
                    );

                    // Créer l'enregistrement
                    $pieceJointe = PieceJointe::create([
                        'id_message' => $message->id_message,
                        'nom_fichier' => $file->getClientOriginalName(),
                        'chemin_fichier' => $cheminFichier,
                        'type_mime' => $file->getMimeType(),
                        'taille_fichier' => $file->getSize(),
                    ]);

                    $fichiersUpload[] = $pieceJointe;

                } catch (\Exception $e) {
                    $erreurs[] = "Erreur lors de l'upload de {$file->getClientOriginalName()} : " . $e->getMessage();
                }
            }
        }

        // Préparer le message de retour
        $message_retour = '';
        if (count($fichiersUpload) > 0) {
            $message_retour .= count($fichiersUpload) . ' fichier(s) uploadé(s) avec succès. ';
        }
        if (count($erreurs) > 0) {
            $message_retour .= 'Erreurs : ' . implode(', ', $erreurs);
        }

        if ($request->expectsJson()) {
            return response()->json([
                'success' => count($fichiersUpload) > 0,
                'message' => $message_retour,
                'fichiers' => $fichiersUpload,
                'erreurs' => $erreurs
            ]);
        }

        if (count($fichiersUpload) > 0) {
            return redirect()->route('messages.show', $message->id_message)
                           ->with('success', $message_retour);
        } else {
            return redirect()->back()
                           ->with('error', $message_retour)
                           ->withInput();
        }
    }

    /**
     * Afficher les détails d'une pièce jointe
     */
    public function show(PieceJointe $pieceJointe)
    {
        $pieceJointe->load(['message']);
        
        return view('pieces-jointes.show', compact('pieceJointe'));
    }

    /**
     * Télécharger un fichier
     */
    public function download(PieceJointe $pieceJointe)
    {
        // Vérifier que le fichier existe
        if (!$pieceJointe->fichierExiste()) {
            abort(404, 'Fichier non trouvé');
        }

        // Obtenir le chemin complet du fichier
        $cheminComplet = storage_path('app/public/' . $pieceJointe->chemin_fichier);
        
        // Vérifier que le fichier existe physiquement
        if (!file_exists($cheminComplet)) {
            abort(404, 'Fichier non trouvé sur le serveur');
        }

        // Retourner le fichier pour téléchargement
        return Response::download(
            $cheminComplet,
            $pieceJointe->nom_fichier,
            [
                'Content-Type' => $pieceJointe->type_mime,
                'Content-Length' => $pieceJointe->taille_fichier
            ]
        );
    }

    /**
     * Afficher un fichier dans le navigateur (aperçu)
     */
    public function preview(PieceJointe $pieceJointe)
    {
        // Vérifier que le fichier existe
        if (!$pieceJointe->fichierExiste()) {
            abort(404, 'Fichier non trouvé');
        }

        // Types de fichiers qui peuvent être affichés dans le navigateur
        $typesApercu = [
            'application/pdf',
            'image/jpeg',
            'image/png',
            'image/gif',
            'image/bmp',
            'text/plain'
        ];

        if (!in_array($pieceJointe->type_mime, $typesApercu)) {
            return $this->download($pieceJointe);
        }

        $cheminComplet = storage_path('app/public/' . $pieceJointe->chemin_fichier);

        return Response::file(
            $cheminComplet,
            [
                'Content-Type' => $pieceJointe->type_mime,
                'Content-Disposition' => 'inline; filename="' . $pieceJointe->nom_fichier . '"'
            ]
        );
    }

    /**
     * Supprimer une pièce jointe
     */
    public function destroy(PieceJointe $pieceJointe)
    {
        $nomFichier = $pieceJointe->nom_fichier;
        $messageId = $pieceJointe->id_message;

        // Supprimer le fichier du stockage
        if ($pieceJointe->fichierExiste()) {
            Storage::disk('public')->delete($pieceJointe->chemin_fichier);
        }

        // Supprimer l'enregistrement
        $pieceJointe->delete();

        return redirect()->route('messages.show', $messageId)
                        ->with('success', "Fichier '{$nomFichier}' supprimé avec succès !");
    }

    /**
     * Upload AJAX pour une interface plus fluide
     */
    public function uploadAjax(Request $request): JsonResponse
    {
        $request->validate([
            'id_message' => 'required|exists:messages,id_message',
            'fichier' => 'required|file|max:20480',
        ]);

        try {
            $message = Message::findOrFail($request->id_message);
            $file = $request->file('fichier');

            // Vérifier le type de fichier
            $typesAutorises = [
                'application/pdf',
                'application/msword',
                'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                'application/vnd.ms-excel',
                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'text/csv',
                'image/jpeg',
                'image/png',
                'image/gif',
                'image/bmp',
                'application/zip',
                'text/plain'
            ];

            if (!in_array($file->getMimeType(), $typesAutorises)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Type de fichier non autorisé'
                ], 400);
            }

            // Générer nom unique et stocker
            $nomFichierUnique = PieceJointe::genererNomFichierUnique($file->getClientOriginalName());
            $cheminFichier = $file->storeAs(
                'attachments/' . date('Y/m'), 
                $nomFichierUnique,
                'public'
            );

            // Créer l'enregistrement
            $pieceJointe = PieceJointe::create([
                'id_message' => $message->id_message,
                'nom_fichier' => $file->getClientOriginalName(),
                'chemin_fichier' => $cheminFichier,
                'type_mime' => $file->getMimeType(),
                'taille_fichier' => $file->getSize(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Fichier uploadé avec succès',
                'fichier' => [
                    'id' => $pieceJointe->id_piece,
                    'nom' => $pieceJointe->nom_fichier,
                    'taille' => $pieceJointe->taille_humain,
                    'icone' => $pieceJointe->icone,
                    'url_download' => route('pieces-jointes.download', $pieceJointe),
                    'url_preview' => route('pieces-jointes.preview', $pieceJointe)
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de l\'upload : ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Supprimer via AJAX
     */
    public function destroyAjax(PieceJointe $pieceJointe): JsonResponse
    {
        try {
            $nomFichier = $pieceJointe->nom_fichier;

            // Supprimer le fichier du stockage
            if ($pieceJointe->fichierExiste()) {
                Storage::disk('public')->delete($pieceJointe->chemin_fichier);
            }

            // Supprimer l'enregistrement
            $pieceJointe->delete();

            return response()->json([
                'success' => true,
                'message' => "Fichier '{$nomFichier}' supprimé avec succès"
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la suppression : ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtenir les informations d'un fichier via AJAX
     */
    public function getInfo(PieceJointe $pieceJointe): JsonResponse
    {
        return response()->json([
            'id' => $pieceJointe->id_piece,
            'nom_fichier' => $pieceJointe->nom_fichier,
            'taille_humain' => $pieceJointe->taille_humain,
            'type_mime' => $pieceJointe->type_mime,
            'categorie' => $pieceJointe->categorie,
            'icone' => $pieceJointe->icone,
            'extension' => $pieceJointe->extension,
            'date_creation' => $pieceJointe->created_at->format('d/m/Y H:i'),
            'fichier_existe' => $pieceJointe->fichierExiste(),
            'message' => [
                'id' => $pieceJointe->message->id_message,
                'objet' => $pieceJointe->message->objet,
                'numero_reference' => $pieceJointe->message->numero_reference
            ]
        ]);
    }

    /**
     * Statistiques des pièces jointes
     */
    public function statistiques()
    {
        $stats = PieceJointe::statistiques();

        // Répartition par type de fichier (30 derniers jours)
        $repartitionTypes = PieceJointe::where('created_at', '>=', now()->subDays(30))
                                     ->get()
                                     ->groupBy('categorie')
                                     ->map->count();

        // Évolution des uploads (30 derniers jours)
        $evolutionUploads = [];
        for ($i = 29; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $count = PieceJointe::whereDate('created_at', $date->toDateString())->count();
            
            $evolutionUploads[] = [
                'date' => $date->format('d/m'),
                'count' => $count
            ];
        }

        // Top 10 des plus gros fichiers
        $plusGrosFichiers = PieceJointe::with(['message'])
                                     ->orderBy('taille_fichier', 'desc')
                                     ->limit(10)
                                     ->get();

        return view('pieces-jointes.statistiques', compact(
            'stats',
            'repartitionTypes',
            'evolutionUploads',
            'plusGrosFichiers'
        ));
    }

    /**
     * Nettoyer les fichiers orphelins
     */
    public function nettoyage()
    {
        // Vérifier les permissions admin
        if (!Auth::user()->estAdmin()) {
            abort(403, 'Accès réservé aux administrateurs');
        }

        $fichiersOrphelins = 0;
        $espaceDiskLibere = 0;

        // Trouver les enregistrements sans fichier physique
        $piecesJointes = PieceJointe::all();
        
        foreach ($piecesJointes as $piece) {
            if (!$piece->fichierExiste()) {
                $espaceDiskLibere += $piece->taille_fichier;
                $piece->delete();
                $fichiersOrphelins++;
            }
        }

        $espaceDiskLibereHumain = $this->formatBytes($espaceDiskLibere);

        return redirect()->route('pieces-jointes.index')
                        ->with('success', "Nettoyage terminé : {$fichiersOrphelins} fichier(s) orphelin(s) supprimé(s). Espace libéré : {$espaceDiskLibereHumain}");
    }

    /**
     * Formater les octets en format humain
     */
    private function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, $precision) . ' ' . $units[$i];
    }
}