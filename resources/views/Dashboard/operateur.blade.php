@extends('layouts.app')

@section('title', 'Dashboard Opérateur')

@section('content')
<div class="row">
    <div class="col-12">
        <!-- En-tête -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0">
                <i class="bi bi-tools me-2 text-success"></i>
                Dashboard Opérateur
            </h1>
            <div class="text-muted">
                <i class="bi bi-person-gear me-1"></i>
                Bienvenue, {{ $utilisateur->nom }}
            </div>
        </div>

        <!-- Métriques opérateur -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card text-white bg-primary">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h4 class="card-title">12</h4>
                                <p class="card-text">Tâches Assignées</p>
                            </div>
                            <div class="align-self-center">
                                <i class="bi bi-list-check fs-1"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-3">
                <div class="card text-white bg-success">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h4 class="card-title">8</h4>
                                <p class="card-text">Tâches Complétées</p>
                            </div>
                            <div class="align-self-center">
                                <i class="bi bi-check-circle fs-1"></i>
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
                                <h4 class="card-title">3</h4>
                                <p class="card-text">En Cours</p>
                            </div>
                            <div class="align-self-center">
                                <i class="bi bi-hourglass-split fs-1"></i>
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
                                <h4 class="card-title">1</h4>
                                <p class="card-text">En Attente</p>
                            </div>
                            <div class="align-self-center">
                                <i class="bi bi-clock fs-1"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Actions opérateur -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-wrench-adjustable me-2"></i>
                            Outils de Travail
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <button class="btn btn-outline-primary w-100">
                                    <i class="bi bi-plus-circle-fill d-block fs-1 mb-2"></i>
                                    Nouvelle Tâche
                                </button>
                            </div>
                            <div class="col-md-4 mb-3">
                                <button class="btn btn-outline-info w-100">
                                    <i class="bi bi-search d-block fs-1 mb-2"></i>
                                    Rechercher
                                </button>
                            </div>
                            <div class="col-md-4 mb-3">
                                <button class="btn btn-outline-success w-100">
                                    <i class="bi bi-file-earmark-text-fill d-block fs-1 mb-2"></i>
                                    Rapports
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Mes tâches -->
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-kanban me-2"></i>
                            Mes Tâches
                        </h5>
                        <div class="btn-group btn-group-sm" role="group">
                            <input type="radio" class="btn-check" name="filter" id="tous" checked>
                            <label class="btn btn-outline-secondary" for="tous">Tous</label>
                            
                            <input type="radio" class="btn-check" name="filter" id="actif">
                            <label class="btn btn-outline-warning" for="actif">En cours</label>
                            
                            <input type="radio" class="btn-check" name="filter" id="termine">
                            <label class="btn btn-outline-success" for="termine">Terminé</label>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Tâche</th>
                                        <th>Priorité</th>
                                        <th>Échéance</th>
                                        <th>Statut</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            <strong>Vérification système</strong><br>
                                            <small class="text-muted">Contrôle routine des serveurs</small>
                                        </td>
                                        <td><span class="badge bg-danger">Haute</span></td>
                                        <td>Aujourd'hui 17:00</td>
                                        <td><span class="badge bg-warning">En cours</span></td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <button class="btn btn-outline-primary" title="Voir">
                                                    <i class="bi bi-eye"></i>
                                                </button>
                                                <button class="btn btn-outline-success" title="Terminer">
                                                    <i class="bi bi-check"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <strong>Rapport mensuel</strong><br>
                                            <small class="text-muted">Génération du rapport d'activité</small>
                                        </td>
                                        <td><span class="badge bg-warning">Moyenne</span></td>
                                        <td>Demain 12:00</td>
                                        <td><span class="badge bg-info">En attente</span></td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <button class="btn btn-outline-primary" title="Voir">
                                                    <i class="bi bi-eye"></i>
                                                </button>
                                                <button class="btn btn-outline-warning" title="Commencer">
                                                    <i class="bi bi-play"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <strong>Maintenance préventive</strong><br>
                                            <small class="text-muted">Nettoyage des bases de données</small>
                                        </td>
                                        <td><span class="badge bg-success">Basse</span></td>
                                        <td>15/06/2025</td>
                                        <td><span class="badge bg-success">Terminé</span></td>
                                        <td>
                                            <button class="btn btn-outline-primary btn-sm" title="Voir rapport">
                                                <i class="bi bi-file-text"></i>
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <!-- Notifications -->
                <div class="card mb-3">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-bell me-2"></i>
                            Notifications
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="list-group list-group-flush">
                            <div class="list-group-item border-0 px-0">
                                <div class="d-flex">
                                    <div class="flex-shrink-0">
                                        <i class="bi bi-exclamation-triangle-fill text-warning"></i>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h6 class="mb-1">Rappel</h6>
                                        <p class="mb-1 small">Tâche urgente à terminer</p>
                                        <small class="text-muted">Il y a 30 min</small>
                                    </div>
                                </div>
                            </div>
                            <div class="list-group-item border-0 px-0">
                                <div class="d-flex">
                                    <div class="flex-shrink-0">
                                        <i class="bi bi-info-circle-fill text-info"></i>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h6 class="mb-1">Information</h6>
                                        <p class="mb-1 small">Nouvelle procédure disponible</p>
                                        <small class="text-muted">Il y a 2 heures</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Aide rapide -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-question-circle me-2"></i>
                            Aide Rapide
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <button class="btn btn-outline-secondary btn-sm">
                                <i class="bi bi-book me-2"></i>
                                Documentation
                            </button>
                            <button class="btn btn-outline-secondary btn-sm">
                                <i class="bi bi-headset me-2"></i>
                                Support Technique
                            </button>
                            <button class="btn btn-outline-secondary btn-sm">
                                <i class="bi bi-chat-dots me-2"></i>
                                FAQ
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection