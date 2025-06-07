<?php
// 📁 app/Models/Service.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Service extends Model
{
    use HasFactory;

    protected $table = 'services';
    protected $primaryKey = 'id_service';

    protected $fillable = [
        'nom_service',
        'email_service',
        'statut'
    ];

    protected $casts = [
        'statut' => 'string',
    ];

    /**
     * Relation : Un service a plusieurs distributions de messages
     */
    public function distributionMessages(): HasMany
    {
        return $this->hasMany(DistributionMessage::class, 'id_service', 'id_service');
    }

    /**
     * Relation : Un service reçoit plusieurs messages via la table pivot
     */
    public function messages(): BelongsToMany
    {
        return $this->belongsToMany(
            Message::class, 
            'distribution_messages', 
            'id_service', 
            'id_message', 
            'id_service', 
            'id_message'
        )->withPivot('date_distribution', 'statut_lecture')
         ->withTimestamps();
    }

    /**
     * Relation : Messages non lus pour ce service
     */
    public function messagesNonLus(): BelongsToMany
    {
        return $this->messages()->wherePivot('statut_lecture', 'non_lu');
    }

    /**
     * Relation : Messages lus pour ce service
     */
    public function messagesLus(): BelongsToMany
    {
        return $this->messages()->wherePivot('statut_lecture', 'lu');
    }

    /**
     * Scope pour les services actifs
     */
    public function scopeActif($query)
    {
        return $query->where('statut', 'actif');
    }

    /**
     * Scope pour les services inactifs
     */
    public function scopeInactif($query)
    {
        return $query->where('statut', 'inactif');
    }

    /**
     * Compter les messages non lus
     */
    public function getNombreMessagesNonLusAttribute()
    {
        return $this->messagesNonLus()->count();
    }

    /**
     * Compter le total des messages reçus
     */
    public function getTotalMessagesAttribute()
    {
        return $this->messages()->count();
    }

    /**
     * Vérifier si le service est actif
     */
    public function estActif()
    {
        return $this->statut === 'actif';
    }

    /**
     * Activer le service
     */
    public function activer()
    {
        $this->update(['statut' => 'actif']);
    }

    /**
     * Désactiver le service
     */
    public function desactiver()
    {
        $this->update(['statut' => 'inactif']);
    }
}