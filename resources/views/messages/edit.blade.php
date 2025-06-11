@extends('layouts.app')

@section('title', 'Modifier le Message')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-warning text-white">
                    <h4 class="mb-0">
                        <i class="bi bi-pencil me-2"></i>
                        Modifier le Message #{{ $message->reference }}
                    </h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('messages.update', $message) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <!-- Expéditeur -->
                        <div class="mb-3">
                            <label for="expediteur" class="form-label">Expéditeur</label>
                            <input type="text" 
                                   class="form-control @error('expediteur') is-invalid @enderror" 
                                   id="expediteur" 
                                   name="expediteur" 
                                   value="{{ old('expediteur', $message->expediteur) }}" 
                                   required>
                            @error('expediteur')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Objet -->
                        <div class="mb-3">
                            <label for="objet" class="form-label">Objet</label>
                            <input type="text" 
                                   class="form-control @error('objet') is-invalid @enderror" 
                                   id="objet" 
                                   name="objet" 
                                   value="{{ old('objet', $message->objet) }}" 
                                   required>
                            @error('objet')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Contenu -->
                        <div class="mb-3">
                            <label for="contenu" class="form-label">Contenu</label>
                            <textarea class="form-control @error('contenu') is-invalid @enderror" 
                                      id="contenu" 
                                      name="contenu" 
                                      rows="5" 
                                      required>{{ old('contenu', $message->contenu) }}</textarea>
                            @error('contenu')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Priorité -->
                        <div class="mb-3">
                            <label for="priorite" class="form-label">Priorité</label>
                            <select class="form-select @error('priorite') is-invalid @enderror" 
                                    id="priorite" 
                                    name="priorite" 
                                    required>
                                <option value="normale" {{ old('priorite', $message->priorite) == 'normale' ? 'selected' : '' }}>Normale</option>
                                <option value="haute" {{ old('priorite', $message->priorite) == 'haute' ? 'selected' : '' }}>Haute</option>
                                <option value="urgente" {{ old('priorite', $message->priorite) == 'urgente' ? 'selected' : '' }}>Urgente</option>
                            </select>
                            @error('priorite')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Statut -->
                        <div class="mb-3">
                            <label for="statut" class="form-label">Statut</label>
                            <select class="form-select @error('statut') is-invalid @enderror" 
                                    id="statut" 
                                    name="statut" 
                                    required>
                                <option value="non_distribue" {{ old('statut', $message->statut) == 'non_distribue' ? 'selected' : '' }}>Non distribué</option>
                                <option value="distribue" {{ old('statut', $message->statut) == 'distribue' ? 'selected' : '' }}>Distribué</option>
                                <option value="traite" {{ old('statut', $message->statut) == 'traite' ? 'selected' : '' }}>Traité</option>
                            </select>
                            @error('statut')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Services concernés -->
                        <div class="mb-3">
                            <label class="form-label">Services concernés</label>
                            <div class="row">
                                @foreach($services as $service)
                                    <div class="col-md-4">
                                        <div class="form-check">
                                            <input class="form-check-input" 
                                                   type="checkbox" 
                                                   name="services[]" 
                                                   value="{{ $service->id }}" 
                                                   id="service_{{ $service->id }}"
                                                   {{ in_array($service->id, old('services', $servicesDistribues)) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="service_{{ $service->id }}">
                                                {{ $service->nom }}
                                            </label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            @error('services')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Pièces jointes existantes -->
                        @if($message->piecesJointes->count() > 0)
                            <div class="mb-3">
                                <label class="form-label">Pièces jointes actuelles</label>
                                <div class="list-group">
                                    @foreach($message->piecesJointes as $pieceJointe)
                                        <div class="list-group-item d-flex justify-content-between align-items-center">
                                            <div>
                                                <i class="bi bi-file-earmark me-2"></i>
                                                {{ $pieceJointe->nom_original }}
                                                <small class="text-muted ms-2">
                                                    ({{ number_format($pieceJointe->taille / 1024, 2) }} KB)
                                                </small>
                                            </div>
                                            <div class="btn-group">
                                                <a href="{{ route('pieces-jointes.download', $pieceJointe) }}" 
                                                   class="btn btn-sm btn-outline-primary">
                                                    <i class="bi bi-download"></i>
                                                </a>
                                                <form action="{{ route('pieces-jointes.destroy', $pieceJointe) }}" 
                                                      method="POST" 
                                                      class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" 
                                                            class="btn btn-sm btn-outline-danger"
                                                            onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette pièce jointe ?')">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- Ajouter de nouvelles pièces jointes -->
                        <div class="mb-3">
                            <label for="pieces_jointes" class="form-label">Ajouter des pièces jointes</label>
                            <input type="file" 
                                   class="form-control @error('pieces_jointes.*') is-invalid @enderror" 
                                   id="pieces_jointes" 
                                   name="pieces_jointes[]" 
                                   multiple>
                            <div class="form-text">
                                Vous pouvez sélectionner plusieurs fichiers. Formats acceptés : PDF, DOC, DOCX, XLS, XLSX, JPG, PNG
                            </div>
                            @error('pieces_jointes.*')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Boutons d'action -->
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('messages.show', $message) }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left me-2"></i>
                                Retour
                            </a>
                            <button type="submit" class="btn btn-warning">
                                <i class="bi bi-save me-2"></i>
                                Enregistrer les modifications
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Prévisualisation des nouvelles pièces jointes
    document.getElementById('pieces_jointes').addEventListener('change', function(e) {
        const files = e.target.files;
        const preview = document.createElement('div');
        preview.className = 'mt-2';
        
        for(let i = 0; i < files.length; i++) {
            const file = files[i];
            const fileInfo = document.createElement('div');
            fileInfo.className = 'alert alert-info';
            fileInfo.innerHTML = `
                <i class="bi bi-file-earmark me-2"></i>
                ${file.name} (${(file.size / 1024).toFixed(2)} KB)
            `;
            preview.appendChild(fileInfo);
        }
        
        const existingPreview = document.querySelector('.pieces-jointes-preview');
        if(existingPreview) {
            existingPreview.remove();
        }
        
        preview.className += ' pieces-jointes-preview';
        this.parentNode.appendChild(preview);
    });
</script>
@endsection