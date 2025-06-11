@extends('layouts.app')

@section('title', 'Nouveau Message')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">
                        <i class="bi bi-plus-circle me-2"></i>
                        Nouveau Message
                    </h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('messages.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <!-- Expéditeur -->
                        <div class="mb-3">
                            <label for="expediteur" class="form-label">Expéditeur</label>
                            <input type="text" 
                                   class="form-control @error('expediteur') is-invalid @enderror" 
                                   id="expediteur" 
                                   name="expediteur" 
                                   value="{{ old('expediteur') }}" 
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
                                   value="{{ old('objet') }}" 
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
                                      required>{{ old('contenu') }}</textarea>
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
                                <option value="normale" {{ old('priorite') == 'normale' ? 'selected' : '' }}>Normale</option>
                                <option value="haute" {{ old('priorite') == 'haute' ? 'selected' : '' }}>Haute</option>
                                <option value="urgente" {{ old('priorite') == 'urgente' ? 'selected' : '' }}>Urgente</option>
                            </select>
                            @error('priorite')
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
                                                   {{ in_array($service->id, old('services', [])) ? 'checked' : '' }}>
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

                        <!-- Pièces jointes -->
                        <div class="mb-3">
                            <label for="pieces_jointes" class="form-label">Pièces jointes</label>
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
                            <a href="{{ route('messages.index') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left me-2"></i>
                                Retour
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save me-2"></i>
                                Enregistrer
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
    // Prévisualisation des pièces jointes
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