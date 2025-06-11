@extends('layouts.app')

@section('title', 'Détails du Message')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <!-- En-tête -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h3 mb-0">
                        <i class="bi bi-envelope me-2"></i>
                        Message #{{ $message->reference }}
                    </h1>
                    <p class="text-muted mb-0">
                        Reçu le {{ $message->created_at->format('d/m/Y à H:i') }}
                    </p>
                </div>
                <div class="btn-group">
                    <a href="{{ route('messages.edit', $message) }}" class="btn btn-warning">
                        <i class="bi bi-pencil me-2"></i>
                        Modifier
                    </a>
                    @if($message->statut == 'non_distribue')
                        <form action="{{ route('messages.distribute', $message) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-success">
                                <i class="bi bi-send me-2"></i>
                                Distribuer
                            </button>
                        </form>
                    @endif
                    <a href="{{ route('messages.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left me-2"></i>
                        Retour
                    </a>
                </div>
            </div>

            <!-- Informations principales -->
            <div class="card mb-4">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5 class="card-title mb-3">Informations du message</h5>
                            <dl class="row mb-0">
                                <dt class="col-sm-4">Expéditeur</dt>
                                <dd class="col-sm-8">{{ $message->expediteur }}</dd>

                                <dt class="col-sm-4">Objet</dt>
                                <dd class="col-sm-8">{{ $message->objet }}</dd>

                                <dt class="col-sm-4">Priorité</dt>
                                <dd class="col-sm-8">
                                    <span class="badge bg-{{ $message->priorite == 'urgente' ? 'danger' : ($message->priorite == 'haute' ? 'warning' : 'info') }}">
                                        {{ ucfirst($message->priorite) }}
                                    </span>
                                </dd>
                            </dl>
                        </div>
                        <div class="col-md-6">
                            <h5 class="card-title mb-3">Statut et distribution</h5>
                            <dl class="row mb-0">
                                <dt class="col-sm-4">Statut</dt>
                                <dd class="col-sm-8">
                                    <span class="badge bg-{{ $message->statut == 'traite' ? 'success' : ($message->statut == 'distribue' ? 'primary' : 'secondary') }}">
                                        {{ ucfirst($message->statut) }}
                                    </span>
                                </dd>

                                <dt class="col-sm-4">Services concernés</dt>
                                <dd class="col-sm-8">
                                    @foreach($message->services as $service)
                                        <span class="badge bg-light text-dark me-1">
                                            {{ $service->nom }}
                                        </span>
                                    @endforeach
                                </dd>

                                <dt class="col-sm-4">Dernière mise à jour</dt>
                                <dd class="col-sm-8">{{ $message->updated_at->format('d/m/Y à H:i') }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contenu du message -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Contenu du message</h5>
                </div>
                <div class="card-body">
                    <div class="message-content">
                        {!! nl2br(e($message->contenu)) !!}
                    </div>
                </div>
            </div>

            <!-- Pièces jointes -->
            @if($message->piecesJointes->count() > 0)
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-paperclip me-2"></i>
                            Pièces jointes
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @foreach($message->piecesJointes as $pieceJointe)
                                <div class="col-md-4 mb-3">
                                    <div class="card h-100">
                                        <div class="card-body">
                                            <div class="d-flex align-items-center mb-2">
                                                <i class="bi bi-file-earmark fs-4 me-2"></i>
                                                <div>
                                                    <h6 class="mb-0">{{ $pieceJointe->nom_original }}</h6>
                                                    <small class="text-muted">
                                                        {{ number_format($pieceJointe->taille / 1024, 2) }} KB
                                                    </small>
                                                </div>
                                            </div>
                                            <div class="btn-group w-100">
                                                <a href="{{ route('pieces-jointes.download', $pieceJointe) }}" 
                                                   class="btn btn-sm btn-outline-primary">
                                                    <i class="bi bi-download me-1"></i>
                                                    Télécharger
                                                </a>
                                                <a href="{{ route('pieces-jointes.preview', $pieceJointe) }}" 
                                                   class="btn btn-sm btn-outline-info" 
                                                   target="_blank">
                                                    <i class="bi bi-eye me-1"></i>
                                                    Prévisualiser
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            <!-- Historique des distributions -->
            @if($message->distributions->count() > 0)
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-clock-history me-2"></i>
                            Historique des distributions
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Service</th>
                                        <th>Date de distribution</th>
                                        <th>Statut</th>
                                        <th>Date de lecture</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($message->distributions as $distribution)
                                        <tr>
                                            <td>{{ $distribution->service->nom }}</td>
                                            <td>{{ $distribution->created_at->format('d/m/Y à H:i') }}</td>
                                            <td>
                                                <span class="badge bg-{{ $distribution->lu ? 'success' : 'warning' }}">
                                                    {{ $distribution->lu ? 'Lu' : 'Non lu' }}
                                                </span>
                                            </td>
                                            <td>
                                                {{ $distribution->lu ? $distribution->date_lecture->format('d/m/Y à H:i') : '-' }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection