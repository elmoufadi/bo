@extends('layouts.app')

@section('title', 'Tableau de bord')

@section('content')
<div class="container-fluid">
    @if(auth()->user()->estAdmin())
        {{-- Vue Admin --}}
        <div class="row">
            <div class="col-12">
                <h1 class="h3 mb-4">Tableau de bord administrateur</h1>
            </div>
        </div>

        {{-- Statistiques générales --}}
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <h5 class="card-title">Messages reçus</h5>
                        <h2 class="card-text">{{ $messages_recus }}</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <h5 class="card-title">Messages distribués</h5>
                        <h2 class="card-text">{{ $messages_distribues }}</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-info text-white">
                    <div class="card-body">
                        <h5 class="card-title">Messages traités</h5>
                        <h2 class="card-text">{{ $messages_traites }}</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-warning text-white">
                    <div class="card-body">
                        <h5 class="card-title">Messages urgents</h5>
                        <h2 class="card-text">{{ $messages_urgents }}</h2>
                    </div>
                </div>
            </div>
        </div>

        {{-- Actions Rapides --}}
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Actions Rapides</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex flex-wrap gap-2">
                            <a href="{{ route('users.create') }}" class="btn btn-outline-primary">
                                <i class="fas fa-user-plus me-2"></i> Ajouter Utilisateur
                            </a>
                            <a href="#" class="btn btn-outline-info">
                                <i class="fas fa-file-alt me-2"></i> Rapports
                            </a>
                            <a href="#" class="btn btn-outline-warning">
                                <i class="fas fa-shield-alt me-2"></i> Sécurité
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Services --}}
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">État des services</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-3">
                            <span>Services actifs : {{ $services_actifs }}</span>
                            <span>Services inactifs : {{ $services_inactifs }}</span>
                        </div>
                        <div class="progress">
                            <div class="progress-bar bg-success" role="progressbar" 
                                style="width: {{ ($services_actifs / ($services_actifs + $services_inactifs)) * 100 }}%">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Messages non lus par service</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Service</th>
                                        <th>Messages non lus</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($services as $service)
                                    <tr>
                                        <td>{{ $service->nom }}</td>
                                        <td>{{ $service->distributions_count }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Derniers messages --}}
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Derniers messages</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Référence</th>
                                        <th>Expéditeur</th>
                                        <th>Objet</th>
                                        <th>Priorité</th>
                                        <th>Statut</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($derniers_messages as $message)
                                    <tr>
                                        <td>{{ $message->numero_reference }}</td>
                                        <td>{{ $message->expediteur }}</td>
                                        <td>{{ $message->objet }}</td>
                                        <td>
                                            <span class="badge bg-{{ $message->priorite === 'urgente' ? 'danger' : ($message->priorite === 'haute' ? 'warning' : 'info') }}">
                                                {{ $message->priorite }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $message->statut === 'traite' ? 'success' : ($message->statut === 'distribue' ? 'primary' : 'secondary') }}">
                                                {{ $message->statut }}
                                            </span>
                                        </td>
                                        <td>{{ $message->date_reception->format('d/m/Y H:i') }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        {{-- Vue Opérateur --}}
        <div class="row">
            <div class="col-12">
                <h1 class="h3 mb-4">Tableau de bord opérateur</h1>
            </div>
        </div>

        {{-- Statistiques opérateur --}}
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <h5 class="card-title">Messages non lus</h5>
                        <h2 class="card-text">{{ $messages_non_lus }}</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card bg-danger text-white">
                    <div class="card-body">
                        <h5 class="card-title">Messages urgents</h5>
                        <h2 class="card-text">{{ $messages_urgents }}</h2>
                    </div>
                </div>
            </div>
        </div>

        {{-- Derniers messages --}}
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Derniers messages</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Référence</th>
                                        <th>Expéditeur</th>
                                        <th>Objet</th>
                                        <th>Priorité</th>
                                        <th>Service</th>
                                        <th>Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($derniers_messages as $message)
                                    <tr>
                                        <td>{{ $message->numero_reference }}</td>
                                        <td>{{ $message->expediteur }}</td>
                                        <td>{{ $message->objet }}</td>
                                        <td>
                                            <span class="badge bg-{{ $message->priorite === 'urgente' ? 'danger' : ($message->priorite === 'haute' ? 'warning' : 'info') }}">
                                                {{ $message->priorite }}
                                            </span>
                                        </td>
                                        <td>{{ $message->distributions->first()->service->nom }}</td>
                                        <td>{{ $message->date_reception->format('d/m/Y H:i') }}</td>
                                        <td>
                                            <a href="{{ route('messages.show', $message) }}" class="btn btn-sm btn-primary">
                                                <i class="fas fa-eye"></i> Voir
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection 