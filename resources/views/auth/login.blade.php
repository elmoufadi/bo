@extends('layouts.app')

@section('title', 'Connexion')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6 col-lg-4">
        <div class="card">
            <div class="card-header bg-primary text-white text-center">
                <h4 class="mb-0">
                    <i class="bi bi-box-arrow-in-right me-2"></i>
                    Connexion
                </h4>
            </div>
            <div class="card-body p-4">
                <form method="POST" action="{{ route('login.post') }}">
                    @csrf
                    
                    <!-- Nom d'utilisateur -->
                    <div class="mb-3">
                        <label for="nom" class="form-label">
                            <i class="bi bi-person me-1"></i>
                            Nom d'utilisateur
                        </label>
                        <input 
                            type="text" 
                            class="form-control @error('nom') is-invalid @enderror" 
                            id="nom" 
                            name="nom" 
                            value="{{ old('nom') }}" 
                            required 
                            autofocus
                            placeholder="Entrez votre nom"
                        >
                        @error('nom')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Mot de passe -->
                    <div class="mb-4">
                        <label for="mot_de_passe" class="form-label">
                            <i class="bi bi-lock me-1"></i>
                            Mot de passe
                        </label>
                        <div class="input-group">
                            <input 
                                type="password" 
                                class="form-control @error('mot_de_passe') is-invalid @enderror" 
                                id="mot_de_passe" 
                                name="mot_de_passe" 
                                required
                                placeholder="Entrez votre mot de passe"
                            >
                            <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                <i class="bi bi-eye" id="toggleIcon"></i>
                            </button>
                        </div>
                        @error('mot_de_passe')
                            <div class="invalid-feedback d-block">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Bouton de connexion -->
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="bi bi-box-arrow-in-right me-2"></i>
                            Se connecter
                        </button>
                    </div>
                </form>
            </div>
            <div class="card-footer text-center text-muted">
                <small>
                    <i class="bi bi-shield-lock me-1"></i>
                    Connexion sécurisée
                </small>
            </div>
        </div>
        
        <!-- Informations de test -->
        <div class="card mt-3">
            <div class="card-header bg-info text-white">
                <h6 class="mb-0">
                    <i class="bi bi-info-circle me-1"></i>
                    Comptes de test
                </h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-6">
                        <strong>Admin:</strong><br>
                        <small class="text-muted">Nom: admin<br>Pass: admin123</small>
                    </div>
                    <div class="col-6">
                        <strong>Opérateur:</strong><br>
                        <small class="text-muted">Nom: operateur<br>Pass: operateur123</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Toggle password visibility
    document.getElementById('togglePassword').addEventListener('click', function () {
        const password = document.getElementById('mot_de_passe');
        const icon = document.getElementById('toggleIcon');
        
        if (password.type === 'password') {
            password.type = 'text';
            icon.classList.remove('bi-eye');
            icon.classList.add('bi-eye-slash');
        } else {
            password.type = 'password';
            icon.classList.remove('bi-eye-slash');
            icon.classList.add('bi-eye');
        }
    });
</script>
@endsection