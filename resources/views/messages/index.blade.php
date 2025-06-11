@extends('layouts.app')

@section('title', 'Messages')

@section('content')
<div class="container-fluid">
    <!-- En-tête avec actions -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3">
            <i class="bi bi-envelope me-2"></i>
            Messages
        </h1>
        <div>
            @if(auth()->user()->role !== 'admin')
                <a href="{{ route('messages.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-lg me-2"></i>
                    Nouveau message
                </a>
            @endif
        </div>
    </div>

    <!-- Filtres -->
    <div class="card mb-4">
        <div class="card-body">
            <form action="{{ route('messages.index') }}" method="GET" class="row g-3">
                <div class="col-md-3">
                    <label for="search" class="form-label">Recherche</label>
                    <input type="text" class="form-control" id="search" name="search" 
                           value="{{ request('search') }}" placeholder="Rechercher...">
                </div>
                <div class="col-md-2">
                    <label for="priority" class="form-label">Priorité</label>
                    <select class="form-select" id="priority" name="priority">
                        <option value="">Toutes</option>
                        <option value="normale" {{ request('priority') == 'normale' ? 'selected' : '' }}>Normale</option>
                        <option value="haute" {{ request('priority') == 'haute' ? 'selected' : '' }}>Haute</option>
                        <option value="urgente" {{ request('priority') == 'urgente' ? 'selected' : '' }}>Urgente</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="status" class="form-label">Statut</label>
                    <select class="form-select" id="status" name="status">
                        <option value="">Tous</option>
                        <option value="non_distribue" {{ request('status') == 'non_distribue' ? 'selected' : '' }}>Non distribué</option>
                        <option value="distribue" {{ request('status') == 'distribue' ? 'selected' : '' }}>Distribué</option>
                        <option value="traite" {{ request('status') == 'traite' ? 'selected' : '' }}>Traité</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="date" class="form-label">Date</label>
                    <input type="date" class="form-control" id="date" name="date" 
                           value="{{ request('date') }}">
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-search me-2"></i>
                        Filtrer
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Liste des messages -->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Référence</th>
                            <th>Expéditeur</th>
                            <th>Objet</th>
                            <th>Priorité</th>
                            <th>Statut</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($messages as $message)
                            <tr>
                                <td>{{ $message->reference }}</td>
                                <td>{{ $message->expediteur }}</td>
                                <td>{{ $message->objet }}</td>
                                <td>
                                    <span class="badge bg-{{ $message->priorite == 'urgente' ? 'danger' : ($message->priorite == 'haute' ? 'warning' : 'info') }}">
                                        {{ ucfirst($message->priorite) }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $message->statut == 'traite' ? 'success' : ($message->statut == 'distribue' ? 'primary' : 'secondary') }}">
                                        {{ ucfirst($message->statut) }}
                                    </span>
                                </td>
                                <td>{{ $message->created_at->format('d/m/Y H:i') }}</td>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ route('messages.show', $message) }}" class="btn btn-info btn-sm" title="Voir">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('messages.edit', $message) }}" class="btn btn-warning btn-sm" title="Modifier">
                                            <i class="fas fa-pencil-alt"></i>
                                        </a>
                                        @if($message->statut == 'non_distribue')
                                            <form action="{{ route('messages.distribute', $message) }}" 
                                                  method="POST" 
                                                  class="d-inline">
                                                @csrf
                                                <button type="submit" 
                                                        class="btn btn-sm btn-success" 
                                                        title="Distribuer">
                                                    <i class="bi bi-send"></i>
                                                </button>
                                            </form>
                                        @endif
                                        <form action="{{ route('messages.destroy', $message) }}" method="POST" class="d-inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce message ?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" title="Supprimer">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4">
                                    <i class="bi bi-inbox text-muted fs-1 d-block mb-2"></i>
                                    Aucun message trouvé
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-4">
                {{ $messages->links() }}
            </div>
        </div>
    </div>
</div>
@endsection