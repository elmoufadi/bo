@extends('layouts.app')

@section('title', 'Détail du Message')

@section('content')
<div class="container-fluid">
    <!-- Navigation et actions -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('messages.index') }}" class="text-decoration-none">
                            <i class="bi bi-envelope me-1"></i>
                            Messages
                        </a>
                    </li>
                    <li class="breadcrumb-item active">MSG-2025-0001</li>
                </ol>
            </nav>
            <h1 class="h3 mb-0 mt-2">
                <i class="bi bi-envelope-open-fill me-2 text-primary"></i>
                Détail du Message
            </h1>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-outline-secondary">
                <i class="bi bi-pencil me-1"></i>
                Modifier
            </button>
            <button class="btn btn-outline-success">
                <i class="bi bi-check-lg me-1"></i>
                Marquer Traité
            </button>
            <div class="dropdown">
                <button class="btn btn-outline-primary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                    <i class="bi bi-three-dots me-1"></i>
                    Actions
                </button>
                <ul class="dropdown-menu">
                    <li>
                        <a class="dropdown-item" href="#">
                            <i class="bi bi-share me-2"></i>
                            Distribuer aux services
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="#">
                            <i class="bi bi-printer me-2"></i>
                            Imprimer
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="#">
                            <i class="bi bi-download me-2"></i>
                            Exporter PDF
                        </a>
                    </li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <a class="dropdown-item text-danger" href="#">
                            <i class="bi bi-trash me-2"></i>
                            Supprimer
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Colonne principale - Détails du message -->
        <div class="col-lg-8">
            <!-- Informations principales -->
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="bi bi-info-circle me-2"></i>
                        Informations du Message
                    </h5>
                    <div class="d-flex gap-2">
                        <span class="badge bg-danger fs-6">
                            <i class="bi bi-exclamation-triangle-fill me-1"></i>
                            URGENTE
                        </span>
                        <span class="badge bg-primary fs-6">
                            <i class="bi bi-share-fill me-1"></i>
                            DISTRIBUÉ
                        </span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <dl class="row">
                                <dt class="col-sm-4">Référence :</dt>
                                <dd class="col-sm-8">
                                    <span class="badge bg-light text-dark">MSG-2025-0001</span>
                                </dd>
                                
                                <dt class="col-sm-4">Expéditeur :</dt>
                                <dd class="col-sm-8">
                                    <strong>Jean Dupont</strong>
                                </dd>
                                
                                <dt class="col-sm-4">Email :</dt>
                                <dd class="col-sm-8">
                                    <a href="mailto:jean.dupont@client.com" class="text-decoration-none">
                                        <i class="bi bi-envelope me-1"></i>
                                        jean.dupont@client.com
                                    </a>
                                </dd>
                                
                                <dt class="col-sm-4">Date réception :</dt>
                                <dd class="col-sm-8">
                                    <i class="bi bi-calendar me-1"></i>
                                    09/06/2025 à 14:30
                                    <small class="text-muted">(il y a 2 heures)</small>
                                </dd>
                            </dl>
                        </div>
                        <div class="col-md-6">
                            <dl class="row">
                                <dt class="col-sm-4">Priorité :</dt>
                                <dd class="col-sm-8">
                                    <span class="badge bg-danger">
                                        <i class="bi bi-exclamation-triangle-fill me-1"></i>
                                        Urgente
                                    </span>
                                </dd>
                                
                                <dt class="col-sm-4">Statut :</dt>
                                <dd class="col-sm-8">
                                    <span class="badge bg-primary">
                                        <i class="bi bi-share-fill me-1"></i>
                                        Distribué
                                    </span>
                                </dd>
                                
                                <dt class="col-sm-4">Pièces jointes :</dt>
                                <dd class="col-sm-8">
                                    <span class="badge bg-info">
                                        <i class="bi bi-paperclip me-1"></i>
                                        2 fichiers
                                    </span>
                                </dd>
                                
                                <dt class="col-sm-4">Créé le :</dt>
                                <dd class="col-sm-8">
                                    <i class="bi bi-clock me-1"></i>
                                    09/06/2025 à 14:30
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Objet et contenu -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-chat-text me-2"></i>
                        Contenu du Message
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Objet :</label>
                        <div class="p-3 bg-light rounded">
                            <h6 class="mb-0">URGENT - Problème de livraison commande #12345</h6>
                        </div>
                    </div>
                    
                    <div>
                        <label class="form-label fw-bold">Message :</label>
                        <div class="p-3 border rounded bg-white">
                            <p class="mb-3">Bonjour,</p>
                            <p class="mb-3">
                                Nous avons un problème urgent avec la commande #12345. La livraison était prévue 
                                aujourd'hui mais nous n'avons rien reçu. Nos clients attendent et nous risquons 
                                de perdre des ventes importantes.
                            </p>
                            <p class="mb-3">
                                Pourriez-vous vérifier le statut de cette commande et nous donner une estimation 
                                de livraison mise à jour ? C'est très urgent.
                            </p>
                            <p class="mb-0">
                                Merci de traiter en priorité.<br>
                                Cordialement,<br>
                                <strong>Jean Dupont</strong>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pièces jointes -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-paperclip me-2"></i>
                        Pièces Jointes (2)
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="d-flex align-items-center p-3 border rounded">
                                <div class="me-3">
                                    <i class="bi bi-file-earmark-pdf-fill text-danger fs-1"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-1">bon_commande_12345.pdf</h6>
                                    <small class="text-muted">245 KB</small>
                                </div>
                                <div class="dropdown">
                                    <button class="btn btn-outline-secondary btn-sm dropdown-toggle" 
                                            data-bs-toggle="dropdown">
                                        <i class="bi bi-three-dots"></i>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li>
                                            <a class="dropdown-item" href="#">
                                                <i class="bi bi-eye me-2"></i>Aperçu
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="#">
                                                <i class="bi bi-download me-2"></i>Télécharger
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex align-items-center p-3 border rounded">
                                <div class="me-3">
                                    <i class="bi bi-file-earmark-image-fill text-primary fs-1"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-1">photo_probleme.jpg</h6>
                                    <small class="text-muted">1.2 MB</small>
                                </div>
                                <div class="dropdown">
                                    <button class="btn btn-outline-secondary btn-sm dropdown-toggle" 
                                            data-bs-toggle="dropdown">
                                        <i class="bi bi-three-dots"></i>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li>
                                            <a class="dropdown-item" href="#">
                                                <i class="bi bi-eye me-2"></i>Aperçu
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="#">
                                                <i class="bi bi-download me-2"></i>Télécharger
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Colonne droite - Distribution et historique -->
        <div class="col-lg-4">
            <!-- Distribution aux services -->
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">
                        <i class="bi bi-share me-2"></i>
                        Distribution aux Services
                    </h6>
                    <button class="btn btn-outline-primary btn-sm">
                        <i class="bi bi-plus"></i>
                        Ajouter
                    </button>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        <div class="list-group-item d-flex justify-content-between align-items-start">
                            <div class="flex-grow-1">
                                <h6 class="mb-1">Service Commercial</h6>
                                <small class="text-muted">
                                    <i class="bi bi-clock me-1"></i>
                                    Distribué il y a 1h
                                </small>
                            </div>
                            <div class="d-flex flex-column align-items-end">
                                <span class="badge bg-success mb-1">Lu</span>
                                <small class="text-muted">14:45</small>
                            </div>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-start">
                            <div class="flex-grow-1">
                                <h6 class="mb-1">Service Logistique</h6>
                                <small class="text-muted">
                                    <i class="bi bi-clock me-1"></i>
                                    Distribué il y a 1h
                                </small>
                            </div>
                            <div class="d-flex flex-column align-items-end">
                                <span class="badge bg-warning">Non lu</span>
                                <small class="text-muted">14:45</small>
                            </div>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-start">
                            <div class="flex-grow-1">
                                <h6 class="mb-1">Direction</h6>
                                <small class="text-muted">
                                    <i class="bi bi-clock me-1"></i>
                                    Distribué il y a 30min
                                </small>
                            </div>
                            <div class="d-flex flex-column align-items-end">
                                <span class="badge bg-success mb-1">Lu</span>
                                <small class="text-muted">15:00</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Historique des actions -->
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="bi bi-clock-history me-2"></i>
                        Historique des Actions
                    </h6>
                </div>
                <div class="card-body p-0">
                    <div class="timeline">
                        <div class="list-group list-group-flush">
                            <div class="list-group-item">
                                <div class="d-flex">
                                    <div class="flex-shrink-0">
                                        <div class="bg-success rounded-circle p-2">
                                            <i class="bi bi-eye-fill text-white"></i>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h6 class="mb-1">Message lu par Direction</h6>
                                        <small class="text-muted">Il y a 30 minutes</small>
                                    </div>
                                </div>
                            </div>
                            <div class="list-group-item">
                                <div class="d-flex">
                                    <div class="flex-shrink-0">
                                        <div class="bg-primary rounded-circle p-2">
                                            <i class="bi bi-share-fill text-white"></i>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h6 class="mb-1">Distribué à Direction</h6>
                                        <small class="text-muted">Il y a 1 heure</small>
                                    </div>
                                </div>
                            </div>
                            <div class="list-group-item">
                                <div class="d-flex">
                                    <div class="flex-shrink-0">
                                        <div class="bg-success rounded-circle p-2">
                                            <i class="bi bi-eye-fill text-white"></i>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h6 class="mb-1">Message lu par Commercial</h6>
                                        <small class="text-muted">Il y a 1 heure</small>
                                    </div>
                                </div>
                            </div>
                            <div class="list-group-item">
                                <div class="d-flex">
                                    <div class="flex-shrink-0">
                                        <div class="bg-primary rounded-circle p-2">
                                            <i class="bi bi-share-fill text-white"></i>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h6 class="mb-1">Distribué aux services</h6>
                                        <small class="text-muted">Il y a 1 heure</small>
                                    </div>
                                </div>
                            </div>
                            <div class="list-group-item">
                                <div class="d-flex">
                                    <div class="flex-shrink-0">
                                        <div class="bg-secondary rounded-circle p-2">
                                            <i class="bi bi-envelope-fill text-white"></i>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h6 class="mb-1">Message reçu</h6>
                                        <small class="text-muted">Il y a 2 heures</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection