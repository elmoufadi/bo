<?php
// 📁 app/Models/DistributionMessage.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DistributionMessage extends Model
{
    use HasFactory;

    protected $table = 'distribution_messages';
    protected $primaryKey = 'id_distribution';

    protected $fillable = [
        'id_message',
        'id_service',
        'date_distribution',
        'statut_lecture'
    ];

    protected $casts = [
        'date_distribution' => 'datetime',
        'statut_lecture' => 'string',
    ];

    /**
     * Relation : Appartient à un message
     */
    public function message(): BelongsTo
    {
        return $this->belongsTo(Message::class, 'id_message', 'id_message');
    }

    /**
     * Relation : Appartient à un service
     */
    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class, 'id_service', 'id_service');
    }

    /**
     * Scopes pour les statuts de lecture
     */
    public function scopeNonLu($query)
    {
        return $query->where('statut_lecture', 'non_lu');
    }

    public function scopeLu($query)
    {
        return $query->where('statut_lecture', 'lu');
    }

    /**
     * Scope pour un service spécifique
     */
    public function scopeParService($query, $serviceId)
    {
        return $query->where('id_service', $serviceId);
    }

    /**
     * Scope pour un message spécifique
     */
    public function scopeParMessage($query, $messageId)
    {
        return $query->where('id_message', $messageId);
    }

    /**
     * Scope pour les distributions récentes
     */
    public function scopeRecentes($query, $jours = 7)
    {
        return $query->where('date_distribution', '>=', now()->subDays($jours));
    }

    /**
     * Scope pour les distributions d'aujourd'hui
     */
    public function scopeAujourdhui($query)
    {
        return $query->whereDate('date_distribution', today());
    }

    /**
     * Accesseur pour le statut de lecture formaté
     */
    public function getStatutLectureFormatAttribute()
    {
        return match($this->statut_lecture) {
            'non_lu' => '📩 Non lu',
            'lu' => '📨 Lu',
            default => '❓ Inconnu'
        };
    }

    /**
     * Accesseur pour la couleur du statut
     */
    public function getStatutColorAttribute()
    {
        return match($this->statut_lecture) {
            'non_lu' => 'blue',
            'lu' => 'green',
            default => 'gray'
        };
    }

    /**
     * Vérifier si le message est lu
     */
    public function estLu()
    {
        return $this->statut_lecture === 'lu';
    }

    /**
     * Vérifier si le message est non lu
     */
    public function estNonLu()
    {
        return $this->statut_lecture === 'non_lu';
    }

    /**
     * Marquer comme lu
     */
    public function marquerCommeLu()
    {
        $this->update(['statut_lecture' => 'lu']);
        return $this;
    }

    /**
     * Marquer comme non lu
     */
    public function marquerCommeNonLu()
    {
        $this->update(['statut_lecture' => 'non_lu']);
        return $this;
    }

    /**
     * Obtenir le délai de lecture (temps écoulé depuis la distribution)
     */
    public function getDelaiLectureAttribute()
    {
        return $this->date_distribution->diffForHumans();
    }

    /**
     * Obtenir la durée avant lecture (si lu)
     */
    public function getDureeAvantLecture()
    {
        if ($this->estNonLu()) {
            return null;
        }

        return $this->date_distribution->diffInMinutes($this->updated_at);
    }

    /**
     * Statistiques de distribution pour un service
     */
    public static function statistiquesParService($serviceId, $periode = 30)
    {
        $distributions = static::parService($serviceId)
            ->where('date_distribution', '>=', now()->subDays($periode))
            ->get();

        return [
            'total' => $distributions->count(),
            'lus' => $distributions->where('statut_lecture', 'lu')->count(),
            'non_lus' => $distributions->where('statut_lecture', 'non_lu')->count(),
            'taux_lecture' => $distributions->count() > 0 
                ? round(($distributions->where('statut_lecture', 'lu')->count() / $distributions->count()) * 100, 2)
                : 0
        ];
    }

    /**
     * Statistiques globales de distribution
     */
    public static function statistiquesGlobales($periode = 30)
    {
        $distributions = static::where('date_distribution', '>=', now()->subDays($periode))->get();

        return [
            'total_distributions' => $distributions->count(),
            'messages_lus' => $distributions->where('statut_lecture', 'lu')->count(),
            'messages_non_lus' => $distributions->where('statut_lecture', 'non_lu')->count(),
            'taux_lecture_global' => $distributions->count() > 0 
                ? round(($distributions->where('statut_lecture', 'lu')->count() / $distributions->count()) * 100, 2)
                : 0,
            'services_actifs' => $distributions->pluck('id_service')->unique()->count()
        ];
    }
}