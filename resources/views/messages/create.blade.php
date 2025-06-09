@extends('layouts.app')

@section('title', 'Nouveau Message')

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
            <li class="breadcrumb-item active">Nouveau Message</li>
        </ol>
    </nav>

    <!-- En-tête -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0">
                <i class="bi bi-plus-circle-fill me-2 text-success"></i>
                Nouveau Message
            </h1>
            <p class="text-muted mb-0">Création d'un nouveau message pour le bureau d'ordre</p>
        </div>
        <div>
            <a href="{{ route('messages.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i>
                Retour à la liste
            </a>
        </div>
    </div>

    <!-- Formulaire de création -->
    <form action="{{ route('messages.store') }}" method="POST" enctype="multipart/form-data" id="messageForm">
        @csrf
        
        <div class="row">
            <!-- Colonne principale - Informations du message -->
            <div class="col-lg-8">
                <!-- Informations de base -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="bi bi-info-circle me-2"></i>
                            Informations du Message
                        </h5>
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
                                           class="form-control" 
                                           id="expediteur" 
                                           name="expediteur" 
                                           placeholder="Nom de l'expéditeur"
                                           required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="email_expediteur" class="form-label">
                                        <i class="bi bi-envelope me-1"></i>
                                        Email de l'expéditeur
                                    </label>
                                    <input type="email" 
                                           class="form-control" 
                                           id="email_expediteur" 
                                           name="email_expediteur" 
                                           placeholder="email@example.com">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label for="objet" class="form-label">
                                        <i class="bi bi-chat-text me-1"></i>
                                        Objet du message <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" 
                                           class="form-control" 
                                           id="objet" 
                                           name="objet" 
                                           placeholder="Objet du message"
                                           maxlength="200"
                                           required>
                                    <div class="form-text">Maximum 200 caractères</div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="priorite" class="form-label">
                                        <i class="bi bi-flag me-1"></i>
                                        Priorité <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-select" id="priorite" name="priorite" required>
                                        <option value="">Sélectionner une priorité</option>
                                        <option value="normale" selected>
                                            <span class="text-success">🟢 Normale</span>
                                        </option>
                                        <option value="haute">
                                            <span class="text-warning">🟠 Haute</span>
                                        </option>
                                        <option value="urgente">
                                            <span class="text-danger">🔴 Urgente</span>
                                        </option>
                                    </select>
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
                            <textarea class="form-control" 
                                      id="contenu" 
                                      name="contenu" 
                                      rows="8" 
                                      placeholder="Tapez le contenu du message ici..."
                                      required></textarea>
                            <div class="form-text">
                                <span id="caracteres">0</span> caractères
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pièces jointes -->
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="bi bi-paperclip me-2"></i>
                            Pièces Jointes
                        </h5>
                        <small class="text-muted">Facultatif - Max 20MB par fichier</small>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="pieces_jointes" class="form-label">
                                Sélectionner des fichiers
                            </label>
                            <input type="file" 
                                   class="form-control" 
                                   id="pieces_jointes" 
                                   name="pieces_jointes[]" 
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
                                    <strong>Glissez et déposez</strong> vos fichiers ici
                                    <br>
                                    <small class="text-muted">ou cliquez sur "Sélectionner des fichiers" ci-dessus</small>
                                </p>
                            </div>
                        </div>

                        <!-- Liste des fichiers sélectionnés -->
                        <div id="filesList" class="mt-3" style="display: none;">
                            <h6>Fichiers sélectionnés :</h6>
                            <div id="filesContainer"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Colonne droite - Distribution et options -->
            <div class="col-lg-4">
                <!-- Distribution aux services -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h6 class="mb-0">
                            <i class="bi bi-share me-2"></i>
                            Distribution aux Services
                        </h6>
                    </div>
                    <div class="card-body">
                        <p class="text-muted small mb-3">
                            <i class="bi bi-info-circle me-1"></i>
                            Sélectionnez les services qui doivent recevoir ce message
                        </p>
                        
                        <!-- Services disponibles -->
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" name="services[]" 
                                   value="1" id="service_direction">
                            <label class="form-check-label" for="service_direction">
                                <i class="bi bi-building me-1"></i>
                                Direction Générale
                            </label>
                        </div>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" name="services[]" 
                                   value="2" id="service_commercial">
                            <label class="form-check-label" for="service_commercial">
                                <i class="bi bi-graph-up me-1"></i>
                                Service Commercial
                            </label>
                        </div>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" name="services[]" 
                                   value="3" id="service_logistique">
                            <label class="form-check-label" for="service_logistique">
                                <i class="bi bi-truck me-1"></i>
                                Service Logistique
                            </label>
                        </div>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" name="services[]" 
                                   value="4" id="service_comptabilite">
                            <label class="form-check-label" for="service_comptabilite">
                                <i class="bi bi-calculator me-1"></i>
                                Comptabilité
                            </label>
                        </div>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" name="services[]" 
                                   value="5" id="service_rh">
                            <label class="form-check-label" for="service_rh">
                                <i class="bi bi-people me-1"></i>
                                Ressources Humaines
                            </label>
                        </div>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" name="services[]" 
                                   value="6" id="service_it">
                            <label class="form-check-label" for="service_it">
                                <i class="bi bi-laptop me-1"></i>
                                Informatique
                            </label>
                        </div>

                        <div class="mt-3">
                            <button type="button" class="btn btn-outline-primary btn-sm" 
                                    onclick="toggleAllServices(true)">
                                <i class="bi bi-check-all me-1"></i>
                                Tout sélectionner
                            </button>
                            <button type="button" class="btn btn-outline-secondary btn-sm ms-2" 
                                    onclick="toggleAllServices(false)">
                                <i class="bi bi-x-lg me-1"></i>
                                Tout désélectionner
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Options supplémentaires -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h6 class="mb-0">
                            <i class="bi bi-gear me-2"></i>
                            Options
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="auto_distribute" checked>
                            <label class="form-check-label" for="auto_distribute">
                                <i class="bi bi-lightning me-1"></i>
                                Distribution automatique
                            </label>
                            <div class="form-text">Le message sera automatiquement distribué aux services sélectionnés</div>
                        </div>

                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="notification_email">
                            <label class="form-check-label" for="notification_email">
                                <i class="bi bi-envelope me-1"></i>
                                Notification par email
                            </label>
                            <div class="form-text">Envoyer un email de notification aux services</div>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="card">
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-success btn-lg">
                                <i class="bi bi-check-lg me-2"></i>
                                Créer le Message
                            </button>
                            <button type="button" class="btn btn-outline-secondary" onclick="saveDraft()">
                                <i class="bi bi-save me-2"></i>
                                Sauvegarder comme brouillon
                            </button>
                            <a href="{{ route('messages.index') }}" class="btn btn-outline-danger">
                                <i class="bi bi-x-lg me-2"></i>
                                Annuler
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script>
// Compteur de caractères pour le contenu
document.getElementById('contenu').addEventListener('input', function() {
    const count = this.value.length;
    document.getElementById('caracteres').textContent = count;
});

// Gestion des fichiers
document.getElementById('pieces_jointes').addEventListener('change', function() {
    displaySelectedFiles(this.files);
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
    const fileInput = document.getElementById('pieces_jointes');
    fileInput.files = files;
    displaySelectedFiles(files);
});

function displaySelectedFiles(files) {
    const filesList = document.getElementById('filesList');
    const filesContainer = document.getElementById('filesContainer');
    
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
                <button type="button" class="btn btn-outline-danger btn-sm" onclick="removeFile(${index})">
                    <i class="bi bi-trash"></i>
                </button>
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

function removeFile(index) {
    // Note: En réalité, on ne peut pas supprimer des fichiers spécifiques d'un input file
    // Cette fonction est là pour l'exemple, dans un vrai projet on utiliserait AJAX
    alert('Pour supprimer un fichier, veuillez resélectionner tous les fichiers souhaités.');
}

function toggleAllServices(select) {
    const checkboxes = document.querySelectorAll('input[name="services[]"]');
    checkboxes.forEach(checkbox => {
        checkbox.checked = select;
    });
}

function saveDraft() {
    alert('Fonctionnalité de brouillon à implémenter');
}

// Validation du formulaire
document.getElementById('messageForm').addEventListener('submit', function(e) {
    const expediteur = document.getElementById('expediteur').value.trim();
    const objet = document.getElementById('objet').value.trim();
    const contenu = document.getElementById('contenu').value.trim();
    const priorite = document.getElementById('priorite').value;
    
    if (!expediteur || !objet || !contenu || !priorite) {
        e.preventDefault();
        alert('Veuillez remplir tous les champs obligatoires (marqués par *)');
        return false;
    }
    
    // Vérification de la taille des fichiers
    const fileInput = document.getElementById('pieces_jointes');
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
</script>
@endsection