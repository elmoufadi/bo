<?php
// 📁 app/Models/Utilisateur.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Utilisateur extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'utilisateurs';
    protected $primaryKey = 'id_utilisateur';

    protected $fillable = [
        'nom',
        'email',
        'mot_de_passe',
        'role',
        'statut'
    ];

    protected $hidden = [
        'mot_de_passe',
        'remember_token',
    ];

    protected $casts = [
        'role' => 'string',
        'statut' => 'string',
        'email_verified_at' => 'datetime',
    ];

    /**
     * Mutateur pour hasher le mot de passe automatiquement
     */
    public function setMotDePasseAttribute($value)
    {
        $this->attributes['mot_de_passe'] = bcrypt($value);
    }

    /**
     * Accesseur pour le nom complet (si nécessaire plus tard)
     */
    public function getNomCompletAttribute()
    {
        return $this->nom;
    }

    /**
     * Scope pour les utilisateurs actifs
     */
    public function scopeActif($query)
    {
        return $query->where('statut', 'actif');
    }

    /**
     * Scope pour les admins
     */
    public function scopeAdmin($query)
    {
        return $query->where('role', 'admin');
    }

    /**
     * Scope pour les opérateurs
     */
    public function scopeOperateur($query)
    {
        return $query->where('role', 'operateur');
    }

    /**
     * Vérifier si l'utilisateur est admin
     */
    public function estAdmin()
    {
        return $this->role === 'admin';
    }

    /**
     * Vérifier si l'utilisateur est opérateur
     */
    public function estOperateur()
    {
        return $this->role === 'operateur';
    }

    /**
     * Vérifier si l'utilisateur est actif
     */
    public function estActif()
    {
        return $this->statut === 'actif';
    }
}