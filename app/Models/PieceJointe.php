<?php
// 📁 app/Models/PieceJointe.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class PieceJointe extends Model
{
    use HasFactory;

    protected $table = 'pieces_jointes';
    protected $primaryKey = 'id_piece';

    protected $fillable = [
        'id_message',
        'nom_fichier',
        'chemin_fichier',
        'type_mime',
        'taille_fichier'
    ];

    protected $casts = [
        'taille_fichier' => 'integer',
    ];

    /**
     * Relation : Appartient à un message
     */
    public function message(): BelongsTo
    {
        return $this->belongsTo(Message::class, 'id_message', 'id_message');
    }

    /**
     * Accesseur pour la taille formatée en format humain
     */
    public function getTailleHumainAttribute()
    {
        $bytes = $this->taille_fichier;
        
        if ($bytes === null) {
            return 'Inconnue';
        }

        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, 2) . ' ' . $units[$i];
    }

    /**
     * Accesseur pour l'extension du fichier
     */
    public function getExtensionAttribute()
    {
        return pathinfo($this->nom_fichier, PATHINFO_EXTENSION);
    }

    /**
     * Accesseur pour le nom du fichier sans extension
     */
    public function getNomSansExtensionAttribute()
    {
        return pathinfo($this->nom_fichier, PATHINFO_FILENAME);
    }

    /**
     * Accesseur pour l'icône basée sur le type de fichier
     */
    public function getIconeAttribute()
    {
        $extension = strtolower($this->extension);
        
        return match($extension) {
            'pdf' => '📄',
            'doc', 'docx' => '📝',
            'xls', 'xlsx' => '📊',
            'ppt', 'pptx' => '📋',
            'jpg', 'jpeg', 'png', 'gif', 'bmp' => '🖼️',
            'mp4', 'avi', 'mov' => '🎥',
            'mp3', 'wav', 'ogg' => '🎵',
            'zip', 'rar', '7z' => '📦',
            'txt' => '📄',
            default => '📎'
        };
    }

    /**
     * Accesseur pour la catégorie de fichier
     */
    public function getCategorieAttribute()
    {
        $extension = strtolower($this->extension);
        
        if (in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'svg'])) {
            return 'image';
        } elseif (in_array($extension, ['pdf', 'doc', 'docx', 'txt', 'rtf'])) {
            return 'document';
        } elseif (in_array($extension, ['xls', 'xlsx', 'csv'])) {
            return 'tableur';
        } elseif (in_array($extension, ['mp4', 'avi', 'mov', 'wmv'])) {
            return 'video';
        } elseif (in_array($extension, ['mp3', 'wav', 'ogg', 'flac'])) {
            return 'audio';
        } elseif (in_array($extension, ['zip', 'rar', '7z', 'tar', 'gz'])) {
            return 'archive';
        } else {
            return 'autre';
        }
    }

    /**
     * Scope pour filtrer par type de fichier
     */
    public function scopeParType($query, $type)
    {
        return $query->where('type_mime', 'LIKE', $type . '%');
    }

    /**
     * Scope pour les images
     */
    public function scopeImages($query)
    {
        return $query->where('type_mime', 'LIKE', 'image/%');
    }

    /**
     * Scope pour les documents
     */
    public function scopeDocuments($query)
    {
        return $query->whereIn('type_mime', [
            'application/pdf',
            'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
        ]);
    }

    /**
     * Scope pour les fichiers volumineux
     */
    public function scopeVolumineux($query, $tailleLimite = 5242880) // 5MB par défaut
    {
        return $query->where('taille_fichier', '>', $tailleLimite);
    }

    /**
     * Vérifier si le fichier existe sur le disque
     */
    public function fichierExiste()
    {
        return Storage::exists($this->chemin_fichier);
    }

    /**
     * Obtenir l'URL publique du fichier
     */
    public function getUrlAttribute()
    {
        return Storage::url($this->chemin_fichier);
    }

    /**
     * Obtenir le contenu du fichier
     */
    public function obtenirContenu()
    {
        if ($this->fichierExiste()) {
            return Storage::get($this->chemin_fichier);
        }
        return null;
    }

    /**
     * Supprimer le fichier du stockage
     */
    public function supprimerFichier()
    {
        if ($this->fichierExiste()) {
            Storage::delete($this->chemin_fichier);
        }
        return $this->delete();
    }

    /**
     * Vérifier si le fichier est une image
     */
    public function estImage()
    {
        return str_starts_with($this->type_mime ?? '', 'image/');
    }

    /**
     * Vérifier si le fichier est un PDF
     */
    public function estPdf()
    {
        return $this->type_mime === 'application/pdf';
    }

    /**
     * Vérifier si le fichier est un document Office
     */
    public function estDocumentOffice()
    {
        $typesOffice = [
            'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'application/vnd.ms-excel',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'application/vnd.ms-powerpoint',
            'application/vnd.openxmlformats-officedocument.presentationml.presentation'
        ];
        
        return in_array($this->type_mime, $typesOffice);
    }

    /**
     * Générer un nom de fichier unique pour éviter les conflits
     */
    public static function genererNomFichierUnique($nomOriginal)
    {
        $extension = pathinfo($nomOriginal, PATHINFO_EXTENSION);
        $nom = pathinfo($nomOriginal, PATHINFO_FILENAME);
        
        // Nettoyer le nom de fichier
        $nom = preg_replace('/[^a-zA-Z0-9_-]/', '_', $nom);
        
        // Ajouter un timestamp pour l'unicité
        $timestamp = now()->format('Y-m-d_H-i-s');
        
        return $nom . '_' . $timestamp . '.' . $extension;
    }

    /**
     * Statistiques des pièces jointes
     */
    public static function statistiques($messageId = null)
    {
        $query = static::query();
        
        if ($messageId) {
            $query->where('id_message', $messageId);
        }

        $pieces = $query->get();
        
        return [
            'total' => $pieces->count(),
            'taille_totale' => $pieces->sum('taille_fichier'),
            'taille_moyenne' => $pieces->avg('taille_fichier'),
            'types_fichiers' => $pieces->groupBy('categorie')->map->count(),
            'plus_volumineux' => $pieces->sortByDesc('taille_fichier')->first(),
        ];
    }
}