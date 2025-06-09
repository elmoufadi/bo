@extends('layouts.app')

@section('title', 'Gestion des Messages')

@section('content')
<div class="container-fluid">
    <!-- En-tête -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0">
                <i class="bi bi-envelope-fill me-2 text-primary"></i>
                Gestion des Messages
            </h1>
            <p class="text-muted mb-0">Bureau d'Ordre</p>
        </div>
        <div>
            <button class="btn btn-primary">
                <i class="bi bi-plus-circle me-2"></i>
                Nouveau Message
            </button>
        </div>
    </div>

    <!-- Statistiques rapides -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4>25</h4>
                            <p class="mb-0">Total Messages</p>
                        </div>
                        <i class="bi bi-envelope fs-1"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-secondary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4>8</h4>
                            <p class="mb-0">Reçus</p>
                        </div>
                        <i class="bi bi-inbox fs-1"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4>12</h4>
                            <p class="mb-0">Distribués</p>
                        </div>
                        <i class="bi bi-share fs-1"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4>3</h4>
                            <p class="mb-0">Urgents</p>
                        </div>
                        <i class="bi bi-exclamation-triangle fs-1"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtres simples -->
    <div class="card mb-4">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Statut</label>
                    <select class="form-select">
                        <option value="">Tous les statuts</option>
                        <option value="recu">Reçu</option>
                        <option value="distribue">Distribué</option>
                        <option value="traite">Traité</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Priorité</label>
                    <select class="form-select">
                        <option value="">Toutes les priorités</option>
                        <option value="normale">Normale</option>
                        <option value="haute">Haute</option>
                        <option value="urgente">Urgente</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Recherche</label>
                    <input type="text" class="form-control" placeholder="Rechercher...">
                </div>
                <div class="col-md-2">
                    <label class="form-label">&nbsp;</label>
                    <div class="d-grid">
                        <button type="button" class="btn btn-outline-primary">
                            <i class="bi bi-funnel me-1"></i>
                            Filtrer
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Liste des messages -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">
                <i class="bi bi-list me-2"></i>
                Liste des Messages
            </h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Référence</th>
                            <th>Expéditeur</th>
                            <th>Objet</th>
                            <th>Date</th>
                            <th>Priorité</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Messages d'exemple -->
                        <tr>
                            <td>
                                <strong>MSG-2025-0001</strong>
                            </td>
                            <td>
                                <div>
                                    <div class="fw-medium">Jean Dupont</div>
                                    <small class="text-muted">jean@example.com</small>
                                </div>
                            </td>
                            <td>Demande d'information produits</td>
                            <td>
                                <div>09/06/2025</div>
                                <small class="text-muted">14:30</small>
                            </td>
                            <td>
                                <span class="badge bg-success">
                                    <i class="bi bi-check-circle-fill me-1"></i>
                                    Normale
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-primary">
                                    <i class="bi bi-share-fill me-1"></i>
                                    Distribué
                                </span>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <button class="btn btn-outline-primary" title="Voir">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                    <button class="btn btn-outline-secondary" title="Modifier">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <button class="btn btn-outline-success" title="Marquer traité">
                                        <i class="bi bi-check-lg"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <strong>MSG-2025-0002</strong>
                            </td>
                            <td>
                                <div>
                                    <div class="fw-medium">Marie Martin</div>
                                    <small class="text-muted">marie@client.fr</small>
                                </div>
                            </td>
                            <td>URGENT - Problème livraison</td>
                            <td>
                                <div>09/06/2025</div>
                                <small class="text-muted">10:15</small>
                            </td>
                            <td>
                                <span class="badge bg-danger">
                                    <i class="bi bi-exclamation-triangle-fill me-1"></i>
                                    Urgente
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-secondary">
                                    <i class="bi bi-inbox-fill me-1"></i>
                                    Reçu
                                </span>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <button class="btn btn-outline-primary" title="Voir">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                    <button class="btn btn-outline-secondary" title="Modifier">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <button class="btn btn-outline-success" title="Marquer traité">
                                        <i class="bi bi-check-lg"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <strong>MSG-2025-0003</strong>
                            </td>
                            <td>
                                <div>
                                    <div class="fw-medium">Service Client</div>
                                    <small class="text-muted">contact@abc.com</small>
                                </div>
                            </td>
                            <td>Réclamation produit défectueux</td>
                            <td>
                                <div>08/06/2025</div>
                                <small class="text-muted">16:45</small>
                            </td>
                            <td>
                                <span class="badge bg-warning text-dark">
                                    <i class="bi bi-exclamation-circle-fill me-1"></i>
                                    Haute
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-success">
                                    <i class="bi bi-check-circle-fill me-1"></i>
                                    Traité
                                </span>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <button class="btn btn-outline-primary" title="Voir">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                    <button class="btn btn-outline-secondary" title="Modifier">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection