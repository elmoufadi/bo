@extends('layouts.app')

@section('title', 'Modifier le Message')

@section('content')
<div class="container-fluid">
    <!-- Navigation -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('messages.index') }}" class="text-decoration-none">
                    <i class="bi bi-envelope me-1"></i>
                    Messages
                </a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('messages.show', $message->id_message) }}" class="text-decoration-none">
                    {{ $message->numero_reference }}
                </a>
            </li>
            <li class="breadcrumb-item active">Modifier</li>
        </ol>
    </nav>

    <!-- En-tête -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0">
                <i class="bi bi-pencil-square me-2 text-warning"></i>
                Modifier le Message
            </h1>
            <p class="text-muted mb-0">
                Référence : <strong>{{ $message->numero_reference }}</strong>
                <span class="badge bg-{{ $message->priorite === 'urgente' ? 'danger' : ($message->priorite === 'haute' ? 'warning' : 'success') }} ms-2">
                    {{ ucfirst($message->priorite) }}
                </span>
            </p>
        </div>
        <div>
            <a href="{{ route('messages.show', $message->id_message) }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i>
                Retour au message
            </a>
        </div>
    </div>

    <!-- Formulaire de modification -->
    <form action="{{ route('messages.update', $message->id_message) }}" method="POST" enctype="multipart/form-data" id="messageEditForm">
        @csrf
        @method('PUT')
        
        <div class="row">
            <!-- Colonne principale - Informations du message -->
            <div class="col-lg-8">
                <!-- Informations de base -->
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="bi bi-info-circle me-2"></i>
                            Informations du Message
                        </h5>
                        <div>
                            <small class="text-muted">
                                <i class="bi bi-calendar me-1"></i>
                                Reçu le {{ $message->date_reception->format('d/m/Y à H:i') }}
                            </small>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="expediteur" class="form-label">
                                        <i class="bi bi-person me-1"></i>
                                        Expéditeur <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" 
                                           class="form-control @error('expediteur') is-invalid @enderror" 
                                           id="expediteur" 
                                           name="expediteur" 
                                           value="{{ old('expediteur', $message->expediteur) }}"
                                           placeholder="Nom de l'expéditeur"
                                           required>
                                    @error('expediteur')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="email_expediteur" class="form-label">
                                        <i class="bi bi-envelope me-1"></i>
                                        Email de l'expéditeur
                                    </label>
                                    <input type="email" 
                                           class="form-control @error('email_expediteur') is-invalid @enderror" 
                                           id="email_expediteur" 
                                           name="email_expediteur" 
                                           value="{{ old('email_expediteur', $message->email_expediteur) }}"
                                           placeholder="email@example.com">
                                    @error('email_expediteur')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="objet" class="form-label">
                                        <i class="bi bi-chat-text me-1"></i>
                                        Objet du message <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" 
                                           class="form-control @error('objet') is-invalid @enderror" 
                                           id="objet" 
                                           name="objet" 
                                           value="{{ old('objet', $message->objet) }}"
                                           placeholder="Objet du message"
                                           maxlength="200"
                                           required>
                                    <div class="form-text">Maximum 200 caractères</div>
                                    @error('objet')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="priorite" class="form-label">
                                        <i class="bi bi-flag me-1"></i>
                                        Priorité <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-select @error('priorite') is-invalid @enderror" id="priorite" name="priorite" required>
                                        <option value="normale" {{ old('priorite', $message->priorite) === 'normale' ? 'selected' : '' }}>
                                            🟢 Normale
                                        </option>
                                        <option value="haute" {{ old('priorite', $message->priorite) === 'haute' ? 'selected' : '' }}>
                                            🟠 Haute
                                        </option>
                                        <option value="urgente" {{ old('priorite', $message->priorite) === 'urgente' ? 'selected' : '' }}>
                                            🔴 Urgente
                                        </option>
                                    </select>
                                    @error('priorite')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="statut" class="form-label">
                                        <i class="bi bi-gear me-1"></i>
                                        Statut <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-select @error('statut') is-invalid @enderror" id="statut" name="statut" required>
                                        <option value="recu" {{ old('statut', $message->statut) === 'recu' ? 'selected' : '' }}>
                                            📥 Reçu
                                        </option>
                                        <option value="distribue" {{ old('statut', $message->statut) === 'distribue' ? 'selected' : '' }}>
                                            📤 Distribué
                                        </option>
                                        <option value="traite" {{ old('statut', $message->statut) === 'traite' ? 'selected' : '' }}>
                                            ✅ Traité
                                        </option>
                                    </select>
                                    @error('statut')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Contenu du message -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="bi bi-file-text me-2"></i>
                            Contenu du Message
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="contenu" class="form-label">
                                Message <span class="text-danger">*</span>
                            </label>
                            <textarea class="form-control @error('contenu') is-invalid @enderror" 
                                      id="contenu" 
                                      name="contenu" 
                                      rows="8" 
                                      placeholder="Tapez le contenu du message ici..."
                                      required>{{ old('contenu', $message->contenu) }}</textarea>
                            <div class="form-text">
                                <span id="caracteres">{{ strlen($message->contenu) }}</span> caractères
                            </div>
                            @error('contenu')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Pièces jointes existantes -->
                @if($message->piecesJointes->count() > 0)
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="bi bi-paperclip me-2"></i>
                            Pièces Jointes Existantes ({{ $message->piecesJointes->count() }})
                        </h5>
                        <small class="text-muted">Gérer les fichiers attachés</small>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            @foreach($message->piecesJointes as $piece)
                            <div class="col-md-6">
                                <div class="d-flex align-items-center p-3 border rounded">
                                    <div class="me-3">
                                        @if($piece->estImage())
                                            <i class="bi bi-file-earmark-image-fill text-primary fs-2"></i>
                                        @elseif($piece->estPdf())
                                            <i class="bi bi-file-earmark-pdf-fill text-danger fs-2"></i>
                                        @elseif($piece->estDocumentOffice())
                                            <i class="bi bi-file-earmark-word-fill text-info fs-2"></i>
                                        @else
                                            <i class="bi bi-file-earmark-fill text-secondary fs-2"></i>
                                        @endif
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1">{{ $piece->nom_fichier }}</h6>
                                        <small class="text-muted">{{ $piece->taille_humain }}</small>
                                        <br>
                                        <small class="text-muted">{{ $piece->created_at->format('d/m/Y H:i') }}</small>
                                    </div>
                                    <div class="dropdown">
                                        <button class="btn btn-outline-secondary btn-sm dropdown-toggle" 
                                                data-bs-toggle="dropdown">
                                            <i class="bi bi-three-dots"></i>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li>
                                                <a class="dropdown-item" href="{{ route('pieces-jointes.preview', $piece->id_piece) }}" target="_blank">
                                                    <i class="bi bi-eye me-2"></i>Aperçu
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item" href="{{ route('pieces-jointes.download', $piece->id_piece) }}">
                                                    <i class="bi bi-download me-2"></i>Télécharger
                                                </a>
                                            </li>
                                            <li><hr class="dropdown-divider"></li>
                                            <li>
                                                <form action="{{ route('pieces-jointes.destroy', $piece->id_piece) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="dropdown-item text-danger" 
                                                            onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce fichier ?')">
                                                        <i class="bi bi-trash me-2"></i>Supprimer
                                                    </button>
                                                </form>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif

                <!-- Ajouter de nouvelles pièces jointes -->
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="bi bi-plus-circle me-2"></i>
                            Ajouter des Pièces Jointes
                        </h5>
                        <small class="text-muted">Facultatif - Max 20MB par fichier</small>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="nouvelles_pieces_jointes" class="form-label">
                                Sélectionner de nouveaux fichiers
                            </label>
                            <input type="file" 
                                   class="form-control" 
                                   id="nouvelles_pieces_jointes" 
                                   name="nouvelles_pieces_jointes[]" 
                                   multiple
                                   accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png,.gif,.txt,.zip">
                            <div class="form-text">
                                Types acceptés : PDF, Word, Excel, Images, Texte, ZIP
                            </div>
                        </div>

                        <!-- Zone de glisser-déposer -->
                        <div class="border border-dashed rounded p-4 text-center" 
                             id="dropZone"
                             style="border-color: #dee2e6; background-color: #f8f9fa;">
                            <div>
                                <i class="bi bi-cloud-upload fs-1 text-muted"></i>
                                <p class="mt-2 mb-0">
                                    <strong>Glissez et déposez</strong> vos nouveaux fichiers ici
                                    <br>
                                    <small class="text-muted">ou cliquez sur "Sélectionner de nouveaux fichiers" ci-dessus</small>
                                </p>
                            </div>
                        </div>

                        <!-- Liste des nouveaux fichiers sélectionnés -->
                        <div id="newFilesList" class="mt-3" style="display: none;">
                            <h6>Nouveaux fichiers sélectionnés :</h6>
                            <div id="newFilesContainer"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Colonne droite - Distribution et options -->
            <div class="col-lg-4">
                <!-- Distribution actuelle -->
                @if($message->distributionMessages->count() > 0)
                <div class="card mb-4">
                    <div class="card-header">
                        <h6 class="mb-0">
                            <i class="bi bi-share me-2"></i>
                            Distribution Actuelle
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="list-group list-group-flush">
                            @foreach($message->distributionMessages as $distribution)
                            <div class="list-group-item d-flex justify-content-between align-items-start px-0">
                                <div class="flex-grow-1">
                                    <h6 class="mb-1">{{ $distribution->service->nom_service }}</h6>
                                    <small class="text-muted">
                                        <i class="bi bi-clock me-1"></i>
                                        {{ $distribution->date_distribution->format('d/m/Y H:i') }}
                                    </small>
                                </div>
                                <div class="d-flex flex-column align-items-end">
                                    @if($distribution->statut_lecture === 'lu')
                                        <span class="badge bg-success mb-1">Lu</span>
                                    @else
                                        <span class="badge bg-warning">Non lu</span>
                                    @endif
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif

                <!-- Distribuer à d'autres services -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h6 class="mb-0">
                            <i class="bi bi-plus-square me-2"></i>
                            Distribuer à d'Autres Services
                        </h6>
                    </div>
                    <div class="card-body">
                        <p class="text-muted small mb-3">
                            <i class="bi bi-info-circle me-1"></i>
                            Sélectionnez des services supplémentaires
                        </p>
                        
                        @php
                            $servicesDistribues = $message->distributionMessages->pluck('id_service')->toArray();
                        @endphp

                        @foreach($services as $service)
                            @if(!in_array($service->id_service, $servicesDistribues))
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" name="nouveaux_services[]" 
                                       value="{{ $service->id_service }}" id="service_{{ $service->id_service }}">
                                <label class="form-check-label" for="service_{{ $service->id_service }}">
                                    <i class="bi bi-building me-1"></i>
                                    {{ $service->nom_service }}
                                </label>
                            </div>
                            @endif
                        @endforeach

                        @if($services->count() === count($servicesDistribues))
                            <div class="alert alert-info">
                                <i class="bi bi-info-circle me-2"></i>
                                Ce message a déjà été distribué à tous les services actifs.
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Historique des modifications -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h6 class="mb-0">
                            <i class="bi bi-clock-history me-2"></i>
                            Historique
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="timeline">
                            <div class="d-flex mb-3">
                                <div class="flex-shrink-0">
                                    <div class="bg-primary rounded-circle p-2">
                                        <i class="bi bi-envelope-fill text-white small"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="mb-1">Message créé</h6>
                                    <small class="text-muted">{{ $message->created_at->format('d/m/Y H:i') }}</small>
                                </div>
                            </div>

                            @if($message->updated_at != $message->created_at)
                            <div class="d-flex mb-3">
                                <div class="flex-shrink-0">
                                    <div class="bg-warning rounded-circle p-2">
                                        <i class="bi bi-pencil-fill text-white small"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="mb-1">Dernière modification</h6>
                                    <small class="text-muted">{{ $message->updated_at->format('d/m/Y H:i') }}</small>
                                </div>
                            </div>
                            @endif

                            @if($message->distributionMessages->count() > 0)
                            <div class="d-flex mb-3">
                                <div class="flex-shrink-0">
                                    <div class="bg-info rounded-circle p-2">
                                        <i class="bi bi-share-fill text-white small"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="mb-1">Distribué</h6>
                                    <small class="text-muted">
                                        {{ $message->distributionMessages->first()->date_distribution->format('d/m/Y H:i') }}
                                    </small>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="card">
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-warning btn-lg">
                                <i class="bi bi-check-lg me-2"></i>
                                Mettre à Jour
                            </button>
                            
                            @if($message->statut !== 'traite')
                            <form action="{{ route('messages.mark-processed', $message->id_message) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-success w-100" 
                                        onclick="return confirm('Marquer ce message comme traité ?')">
                                    <i class="bi bi-check-circle me-2"></i>
                                    Marquer Traité
                                </button>
                            </form>
                            @endif
                            
                            <a href="{{ route('messages.show', $message->id_message) }}" class="btn btn-outline-secondary">
                                <i class="bi bi-x-lg me-2"></i>
                                Annuler
                            </a>
                            
                            <button type="button" class="btn btn-outline-danger" onclick="confirmDelete()">
                                <i class="bi bi-trash me-2"></i>
                                Supprimer le Message
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <!-- Modal de confirmation de suppression -->
    <div class="modal fade" id="deleteModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="bi bi-exclamation-triangle me-2 text-danger"></i>
                        Confirmer la suppression
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Êtes-vous sûr de vouloir supprimer ce message ?</p>
                    <div class="alert alert-warning">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        <strong>Attention :</strong> Cette action est irréversible. 
                        Toutes les distributions et pièces jointes seront également supprimées.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <form action="{{ route('messages.destroy', $message->id_message) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <i class="bi bi-trash me-2"></i>
                            Supprimer définitivement
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
// Compteur de caractères pour le contenu
document.getElementById('contenu').addEventListener('input', function() {
    const count = this.value.length;
    document.getElementById('caracteres').textContent = count;
});

// Gestion des nouveaux fichiers
document.getElementById('nouvelles_pieces_jointes').addEventListener('change', function() {
    displayNewSelectedFiles(this.files);
});

// Zone de glisser-déposer
const dropZone = document.getElementById('dropZone');

dropZone.addEventListener('dragover', function(e) {
    e.preventDefault();
    this.style.backgroundColor = '#e3f2fd';
    this.style.borderColor = '#2196f3';
});

dropZone.addEventListener('dragleave', function(e) {
    e.preventDefault();
    this.style.backgroundColor = '#f8f9fa';
    this.style.borderColor = '#dee2e6';
});

dropZone.addEventListener('drop', function(e) {
    e.preventDefault();
    this.style.backgroundColor = '#f8f9fa';
    this.style.borderColor = '#dee2e6';
    
    const files = e.dataTransfer.files;
    const fileInput = document.getElementById('nouvelles_pieces_jointes');
    fileInput.files = files;
    displayNewSelectedFiles(files);
});

function displayNewSelectedFiles(files) {
    const filesList = document.getElementById('newFilesList');
    const filesContainer = document.getElementById('newFilesContainer');
    
    if (files.length > 0) {
        filesList.style.display = 'block';
        filesContainer.innerHTML = '';
        
        Array.from(files).forEach((file, index) => {
            const fileItem = document.createElement('div');
            fileItem.className = 'border rounded p-2 mb-2 d-flex align-items-center justify-content-between';
            
            const fileIcon = getFileIcon(file.type);
            const fileSize = formatFileSize(file.size);
            
            fileItem.innerHTML = `
                <div class="d-flex align-items-center">
                    <i class="${fileIcon} me-2"></i>
                    <div>
                        <strong>${file.name}</strong>
                        <br>
                        <small class="text-muted">${fileSize}</small>
                    </div>
                </div>
                <span class="badge bg-success">Nouveau</span>
            `;
            
            filesContainer.appendChild(fileItem);
        });
    } else {
        filesList.style.display = 'none';
    }
}

function getFileIcon(mimeType) {
    if (mimeType.includes('pdf')) return 'bi bi-file-earmark-pdf-fill text-danger';
    if (mimeType.includes('image')) return 'bi bi-file-earmark-image-fill text-primary';
    if (mimeType.includes('word') || mimeType.includes('document')) return 'bi bi-file-earmark-word-fill text-primary';
    if (mimeType.includes('excel') || mimeType.includes('sheet')) return 'bi bi-file-earmark-excel-fill text-success';
    if (mimeType.includes('zip')) return 'bi bi-file-earmark-zip-fill text-warning';
    return 'bi bi-file-earmark-fill text-secondary';
}

function formatFileSize(bytes) {
    if (bytes === 0) return '0 B';
    const k = 1024;
    const sizes = ['B', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
}

function confirmDelete() {
    const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
    modal.show();
}

// Validation du formulaire
document.getElementById('messageEditForm').addEventListener('submit', function(e) {
    const expediteur = document.getElementById('expediteur').value.trim();
    const objet = document.getElementById('objet').value.trim();
    const contenu = document.getElementById('contenu').value.trim();
    const priorite = document.getElementById('priorite').value;
    const statut = document.getElementById('statut').value;
    
    if (!expediteur || !objet || !contenu || !priorite || !statut) {
        e.preventDefault();
        alert('Veuillez remplir tous les champs obligatoires (marqués par *)');
        return false;
    }
    
    // Vérification de la taille des nouveaux fichiers
    const fileInput = document.getElementById('nouvelles_pieces_jointes');
    const maxSize = 20 * 1024 * 1024; // 20MB
    
    if (fileInput.files) {
        for (let file of fileInput.files) {
            if (file.size > maxSize) {
                e.preventDefault();
                alert(`Le fichier "${file.name}" dépasse la taille maximale de 20MB.`);
                return false;
            }
        }
    }
});

// Auto-sauvegarde (optionnel)
let autoSaveTimer;
function startAutoSave() {
    clearTimeout(autoSaveTimer);
    autoSaveTimer = setTimeout(function() {
        // Ici on pourrait implémenter une sauvegarde automatique via AJAX
        console.log('Auto-save would happen here');
    }, 30000); // 30 secondes
}

// Déclencher l'auto-save sur les changements
document.querySelectorAll('#expediteur, #objet, #contenu').forEach(input => {
    input.addEventListener('input', startAutoSave);
});

// Gestion des changements de statut
document.getElementById('statut').addEventListener('change', function() {
    const statut = this.value;
    const warningDiv = document.getElementById('statutWarning');
    
    // Supprimer l'ancien avertissement s'il existe
    if (warningDiv) {
        warningDiv.remove();
    }
    
    // Afficher un avertissement pour certains statuts
    if (statut === 'traite') {
        const warning = document.createElement('div');
        warning.id = 'statutWarning';
        warning.className = 'alert alert-info mt-2';
        warning.innerHTML = `
            <i class="bi bi-info-circle me-2"></i>
            <strong>Information :</strong> Marquer le message comme "Traité" indique qu'il a été complètement géré.
        `;
        this.parentNode.appendChild(warning);
    }
});

// Gestion des changements de priorité
document.getElementById('priorite').addEventListener('change', function() {
    const priorite = this.value;
    const card = this.closest('.card');
    
    // Changer la couleur de la bordure selon la priorité
    card.classList.remove('border-success', 'border-warning', 'border-danger');
    
    switch(priorite) {
        case 'urgente':
            card.classList.add('border-danger');
            break;
        case 'haute':
            card.classList.add('border-warning');
            break;
        case 'normale':
            card.classList.add('border-success');
            break;
    }
});

// Fonction pour marquer tous les nouveaux services
function toggleAllNewServices(select) {
    const checkboxes = document.querySelectorAll('input[name="nouveaux_services[]"]');
    checkboxes.forEach(checkbox => {
        checkbox.checked = select;
    });
}

// Confirmer les changements importants
function confirmImportantChanges() {
    const originalStatut = '{{ $message->statut }}';
    const originalPriorite = '{{ $message->priorite }}';
    const currentStatut = document.getElementById('statut').value;
    const currentPriorite = document.getElementById('priorite').value;
    
    let changes = [];
    
    if (originalStatut !== currentStatut) {
        changes.push(`Statut: ${originalStatut} → ${currentStatut}`);
    }
    
    if (originalPriorite !== currentPriorite) {
        changes.push(`Priorité: ${originalPriorite} → ${currentPriorite}`);
    }
    
    if (changes.length > 0) {
        const message = `Vous avez modifié :\n${changes.join('\n')}\n\nContinuer ?`;
        return confirm(message);
    }
    
    return true;
}

// Ajouter la vérification au submit
document.getElementById('messageEditForm').addEventListener('submit', function(e) {
    if (!confirmImportantChanges()) {
        e.preventDefault();
        return false;
    }
});

// Indicateur de modifications non sauvegardées
let hasUnsavedChanges = false;

function markAsChanged() {
    hasUnsavedChanges = true;
    // Changer le texte du bouton pour indiquer les modifications
    const submitBtn = document.querySelector('button[type="submit"]');
    if (submitBtn && !submitBtn.innerHTML.includes('●')) {
        submitBtn.innerHTML = '● ' + submitBtn.innerHTML;
        submitBtn.classList.add('btn-warning');
        submitBtn.classList.remove('btn-primary');
    }
}

function markAsSaved() {
    hasUnsavedChanges = false;
    const submitBtn = document.querySelector('button[type="submit"]');
    if (submitBtn) {
        submitBtn.innerHTML = submitBtn.innerHTML.replace('● ', '');
        submitBtn.classList.remove('btn-warning');
        submitBtn.classList.add('btn-primary');
    }
}

// Surveiller les changements dans le formulaire
document.querySelectorAll('#messageEditForm input, #messageEditForm textarea, #messageEditForm select').forEach(element => {
    element.addEventListener('change', markAsChanged);
    if (element.type === 'text' || element.tagName === 'TEXTAREA') {
        element.addEventListener('input', markAsChanged);
    }
});

// Avertir avant de quitter si des modifications ne sont pas sauvegardées
window.addEventListener('beforeunload', function(e) {
    if (hasUnsavedChanges) {
        e.preventDefault();
        e.returnValue = 'Vous avez des modifications non sauvegardées. Êtes-vous sûr de vouloir quitter ?';
        return e.returnValue;
    }
});

// Marquer comme sauvé après soumission réussie
document.getElementById('messageEditForm').addEventListener('submit', function() {
    markAsSaved();
});

// Raccourcis clavier
document.addEventListener('keydown', function(e) {
    // Ctrl+S pour sauvegarder
    if (e.ctrlKey && e.key === 's') {
        e.preventDefault();
        document.getElementById('messageEditForm').submit();
    }
    
    // Escape pour annuler
    if (e.key === 'Escape') {
        if (confirm('Annuler les modifications et retourner au message ?')) {
            window.location.href = '{{ route("messages.show", $message->id_message) }}';
        }
    }
});

// Toast notifications pour les actions
function showToast(message, type = 'success') {
    // Créer un toast Bootstrap
    const toastContainer = document.querySelector('.toast-container') || createToastContainer();
    
    const toast = document.createElement('div');
    toast.className = `toast align-items-center text-white bg-${type} border-0`;
    toast.setAttribute('role', 'alert');
    toast.innerHTML = `
        <div class="d-flex">
            <div class="toast-body">
                <i class="bi bi-${type === 'success' ? 'check-circle' : 'exclamation-triangle'} me-2"></i>
                ${message}
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    `;
    
    toastContainer.appendChild(toast);
    
    const bsToast = new bootstrap.Toast(toast);
    bsToast.show();
    
    // Supprimer le toast après fermeture
    toast.addEventListener('hidden.bs.toast', function() {
        toast.remove();
    });
}

function createToastContainer() {
    const container = document.createElement('div');
    container.className = 'toast-container position-fixed top-0 end-0 p-3';
    container.style.zIndex = '9999';
    document.body.appendChild(container);
    return container;
}

// Initialisation
document.addEventListener('DOMContentLoaded', function() {
    // Appliquer la couleur de bordure selon la priorité initiale
    const prioriteActuelle = document.getElementById('priorite').value;
    const card = document.getElementById('priorite').closest('.card');
    
    switch(prioriteActuelle) {
        case 'urgente':
            card.classList.add('border-danger');
            break;
        case 'haute':
            card.classList.add('border-warning');
            break;
        case 'normale':
            card.classList.add('border-success');
            break;
    }
    
    // Focus sur le premier champ modifiable
    document.getElementById('expediteur').focus();
    
    // Afficher un message de bienvenue
    showToast('Mode édition activé. Utilisez Ctrl+S pour sauvegarder rapidement.', 'info');
});
</script>
@endsection