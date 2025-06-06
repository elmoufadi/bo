@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('content')
<div class="row">
    <div class="col-12">
        <!-- En-tête -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0">
                <i class="bi bi-speedometer2 me-2 text-primary"></i>
                Dashboard Administrateur
            </h1>
            <div class="text-muted">
                <i class="bi bi-person-badge me-1"></i>
                Bienvenue, {{ $utilisateur->nom }}
            </div>
        </div>

        <!-- Statistiques rapides -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card text-white bg-success">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h4 class="card-title">150</h4>
                                <p class="card-text">Utilisateurs Total</p>
                            </div>
                            <div class="align-self-center">
                                <i class="bi bi-people fs-1"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-3">
                <div class="card text-white bg-info">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h4 class="card-title">89</h4>
                                <p class="card-text">Opérateurs Actifs</p>
                            </div>
                            <div class="align-self-center">
                                <i class="bi bi-person-check fs-1"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-3">
                <div class="card text-white bg-warning">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h4 class="card-title">25</h4>
                                <p class="card-text">Tâches En Cours</p>
                            </div>
                            <div class="align-self-center">
                                <i class="bi bi-list-task fs-1"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-3">
                <div class="card text-white bg-danger">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h4 class="card-title">5</h4>
                                <p class="card-text">Alertes</p>
                            </div>
                            <div class="align-self-center">
                                <i class="bi bi-exclamation-triangle fs-1"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Actions rapides -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-lightning-charge me-2"></i>
                            Actions Rapides
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <button class="btn btn-outline-primary w-100">
                                    <i class="bi bi-person-plus-fill d-block fs-1 mb-2"></i>
                                    Ajouter Utilisateur
                                </button>
                            </div>
                            <div class="col-md-3 mb-3">
                                <button class="btn btn-outline-success w-100">
                                    <i class="bi bi-gear-fill d-block fs-1 mb-2"></i>
                                    Paramètres Système
                                </button>
                            </div>
                            <div class="col-md-3 mb-3">
                                <button class="btn btn-outline-info w-100">
                                    <i class="bi bi-bar-chart-fill d-block fs-1 mb-2"></i>
                                    Rapports
                                </button>
                            </div>
                            <div class="col-md-3 mb-3">
                                <button class="btn btn-outline-warning w-100">
                                    <i class="bi bi-shield-check d-block fs-1 mb-2"></i>
                                    Sécurité
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Activités récentes -->
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-clock-history me-2"></i>
                            Activités Récentes
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="list-group list-group-flush">
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-1">Nouvel utilisateur créé</h6>
                                    <p class="mb-1 text-muted">Jean Dupont ajouté comme opérateur</p>
                                    <small class="text-muted">Il y a 2 heures</small>
                                </div>
                                <span class="badge bg-success rounded-pill">Nouveau</span>
                            </div>
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-1">Sauvegarde complétée</h6>
                                    <p class="mb-1 text-muted">Sauvegarde automatique du système</p>
                                    <small class="text-muted">Il y a 4 heures</small>
                                </div>
                                <span class="badge bg-info rounded-pill">Système</span>
                            </div>
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-1">Maintenance programmée</h6>
                                    <p class="mb-1 text-muted">Mise à jour des serveurs prévue</p>
                                    <small class="text-muted">Demain à 02:00</small>
                                </div>
                                <span class="badge bg-warning rounded-pill">Planifié</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-graph-up me-2"></i>
                            Performance Système
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <div class="d-flex justify-content-between">
                                <span>CPU</span>
                                <span>65%</span>
                            </div>
                            <div class="progress">
                                <div class="progress-bar bg-success" style="width: 65%"></div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <div class="d-flex justify-content-between">
                                <span>Mémoire</span>
                                <span>78%</span>
                            </div>
                            <div class="progress">
                                <div class="progress-bar bg-warning" style="width: 78%"></div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <div class="d-flex justify-content-between">
                                <span>Stockage</span>
                                <span>45%</span>
                            </div>
                            <div class="progress">
                                <div class="progress-bar bg-info" style="width: 45%"></div>
                            </div>
                        </div>
                        
                        <div class="text-center mt-3">
                            <small class="text-muted">
                                <i class="bi bi-check-circle text-success me-1"></i>
                                Système opérationnel
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection