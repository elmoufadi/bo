<?php
// 📁 app/Models/Message.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Message extends Model
{
    use HasFactory;

    protected $table = 'messages';
    protected $primaryKey = 'id_message';

    protected $fillable = [
        'numero_reference',
        'expediteur',
        'email_expediteur',
        'objet',
        'contenu',
        'date_reception',
        'statut',
        'priorite'
    ];

    protected $casts = [
        'date_reception' => 'datetime',
        'statut' => 'string',
        'priorite' => 'string',
    ];

    /**
     * Relation : Un message a plusieurs distributions vers les services
     */
    public function distributionMessages(): HasMany
    {
        return $this->hasMany(DistributionMessage::class, 'id_message', 'id_message');
    }

    /**
     * Relation : Un message a plusieurs pièces jointes
     */
    public function piecesJointes(): HasMany
    {
        return $this->hasMany(PieceJointe::class, 'id_message', 'id_message');
    }

    /**
     * Relation : Un message est distribué à plusieurs services
     */
    public function services(): BelongsToMany
    {
        return $this->belongsToMany(
            Service::class, 
            'distribution_messages', 
            'id_message', 
            'id_service', 
            'id_message', 
            'id_service'
        )->withPivot('date_distribution', 'statut_lecture')
         ->withTimestamps();
    }

    /**
     * Scopes pour les statuts
     */
    public function scopeRecu($query)
    {
        return $query->where('statut', 'recu');
    }

    public function scopeDistribue($query)
    {
        return $query->where('statut', 'distribue');
    }

    public function scopeTraite($query)
    {
        return $query->where('statut', 'traite');
    }

    /**
     * Scopes pour les priorités
     */
    public function scopeUrgent($query)
    {
        return $query->where('priorite', 'urgente');
    }

    public function scopeHautePriorite($query)
    {
        return $query->where('priorite', 'haute');
    }

    public function scopeNormale($query)
    {
        return $query->where('priorite', 'normale');
    }

    public function scopeParPriorite($query, $priorite)
    {
        return $query->where('priorite', $priorite);
    }

    /**
     * Scope pour les messages récents
     */
    public function scopeRecents($query, $jours = 7)
    {
        return $query->where('date_reception', '>=', now()->subDays($jours));
    }

    /**
     * Accesseur pour la couleur de priorité
     */
    public function getPrioriteColorAttribute()
    {
        return match($this->priorite) {
            'urgente' => 'red',
            'haute' => 'orange',
            'normale' => 'green',
            default => 'gray'
        };
    }

    /**
     * Accesseur pour le badge de priorité
     */
    public function getPrioriteBadgeAttribute()
    {
        return match($this->priorite) {
            'urgente' => '🔴 URGENT',
            'haute' => '🟠 HAUTE',
            'normale' => '🟢 NORMALE',
            default => '⚪ INCONNUE'
        };
    }

    /**
     * Accesseur pour le statut formaté
     */
    public function getStatutFormatAttribute()
    {
        return match($this->statut) {
            'recu' => 'Reçu',
            'distribue' => 'Distribué',
            'traite' => 'Traité',
            default => 'Inconnu'
        };
    }

    /**
     * Mutateur pour le numéro de référence (en majuscules)
     */
    public function setNumeroReferenceAttribute($value)
    {
        $this->attributes['numero_reference'] = strtoupper($value);
    }

    /**
     * Vérifier si le message a des pièces jointes
     */
    public function aPiecesJointes()
    {
        return $this->piecesJointes()->exists();
    }

    /**
     * Compter les pièces jointes
     */
    public function getNombrePiecesJointesAttribute()
    {
        return $this->piecesJointes()->count();
    }

    /**
     * Distribuer le message à un ou plusieurs services
     */
    public function distribuerAuxServices(array $serviceIds)
    {
        foreach ($serviceIds as $serviceId) {
            DistributionMessage::firstOrCreate([
                'id_message' => $this->id_message,
                'id_service' => $serviceId,
            ], [
                'date_distribution' => now(),
                'statut_lecture' => 'non_lu'
            ]);
        }

        // Mettre à jour le statut du message
        $this->update(['statut' => 'distribue']);
    }

    /**
     * Marquer le message comme traité
     */
    public function marquerCommeTraite()
    {
        $this->update(['statut' => 'traite']);
    }

    /**
     * Générer un numéro de référence unique
     */
    public static function genererNumeroReference()
    {
        $prefix = 'MSG-' . date('Y');
        $dernierNumero = static::where('numero_reference', 'LIKE', $prefix . '%')
                              ->orderBy('numero_reference', 'desc')
                              ->first();

        if ($dernierNumero) {
            $numero = intval(substr($dernierNumero->numero_reference, -4)) + 1;
        } else {
            $numero = 1;
        }

        return $prefix . '-' . str_pad($numero, 4, '0', STR_PAD_LEFT);
    }
}