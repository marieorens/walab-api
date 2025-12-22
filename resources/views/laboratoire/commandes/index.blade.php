@extends('laboratoire.layout')

@section('page_content')
<!-- Start Content-->
<div class="container-fluid">

    <!-- Page Title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="#">{{ $laboratoire->name }}</a></li>
                        <li class="breadcrumb-item"><a href="{{route('laboratoire.dashboard')}}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Commandes</li>
                    </ol>
                </div>
                <h4 class="page-title">
                    <i class="ri-shopping-cart-line me-2"></i>
                    Gestion des Commandes
                </h4>
            </div>
        </div>
    </div>
    <!-- End Page Title -->

    <!-- Statistiques -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card widget-flat shadow-sm border-0">
                <div class="card-body">
                    <div class="float-end">
                        <i class="ri-time-line widget-icon" style="background:#e8eafc; color:#667eea;"></i>
                    </div>
                    <h5 class="text-muted fw-normal mt-0" title="En Cours">Commandes En Cours</h5>
                    <h3 class="mt-3 mb-3" style="color:#667eea;">{{ $stats_commandes['en_cours'] }}</h3>
                    <p class="mb-0 text-muted">
                        <span class="text-nowrap">En attente et en traitement</span>
                    </p>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card widget-flat shadow-sm border-0">
                <div class="card-body">
                    <div class="float-end">
                        <i class="ri-check-double-line widget-icon bg-success-lighten text-success"></i>
                    </div>
                    <h5 class="text-muted fw-normal mt-0" title="Terminées">Commandes Terminées</h5>
                    <h3 class="mt-3 mb-3 text-success">{{ $stats_commandes['terminees'] }}</h3>
                    <p class="mb-0 text-muted">
                        <span class="text-nowrap">Résultats livrés</span>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtres et Actions -->
    <div class="row mb-3">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-body py-2">
                    <div class="d-flex justify-content-between align-items-center">
                        <!-- Boutons de vue -->
                        <div class="btn-group" role="group">
                            <a href="{{route('laboratoire.commandes', ['view' => 'en_cours'])}}" 
                               class="btn btn-sm {{$view_type == 'en_cours' ? 'text-white' : ''}}" 
                               style="{{$view_type == 'en_cours' ? 'background:#667eea;' : 'border: 1px solid #667eea; color:#667eea;'}}">
                                <i class="ri-time-line me-1"></i>
                                En Cours ({{ $stats_commandes['en_cours'] }})
                            </a>
                            <a href="{{route('laboratoire.commandes', ['view' => 'historique'])}}" 
                               class="btn btn-sm {{$view_type == 'historique' ? 'text-white' : ''}}" 
                               style="{{$view_type == 'historique' ? 'background:#667eea;' : 'border: 1px solid #667eea; color:#667eea;'}}">
                                <i class="ri-history-line me-1"></i>
                                Historique
                            </a>
                        </div>

                        <!-- Recherche -->
                        <div class="input-group rounded" style="width:30%">
                            <input type="search" class="form-control rounded" 
                                   placeholder="Rechercher par code..." 
                                   id="searchInput">
                            <button class="btn text-white ms-2 rounded" style="background:#667eea;" id="searchButton">
                                <i class="ri-search-line"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Alerts -->
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="ri-checkbox-circle-line me-2"></i>
            <strong>{{ session('success') }}</strong>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="ri-error-warning-line me-2"></i>
            <strong>{{ session('error') }}</strong>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Table des Commandes -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-body p-0">
                    <div class="p-3 border-bottom">
                        <h5 class="header-title mb-0">
                            <i class="ri-list-check me-2"></i>
                            Liste des Commandes 
                            <span class="badge text-white rounded-pill ms-2" style="background:#667eea;">{{ $commandes->total() }}</span>
                        </h5>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover table-striped mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="text-center" style="width: 100px">Code</th>
                                    <th>Type</th>
                                    <th>Client</th>
                                    <th class="text-center">Date Prélèvement</th>
                                    <th class="text-center">Statut</th>
                                    <th class="text-center">Agent</th>
                                    <th class="text-center">Résultat</th>
                                    <th class="text-center" style="width: 150px">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($commandes as $commande)
                                <tr>
                                    <!-- Code -->
                                    <td class="text-center">
                                        <span class="badge bg-dark rounded-pill">
                                            {{ $commande->code }}
                                        </span>
                                    </td>

                                    <!-- Type -->
                                    <td>
                                        @if($commande->examen_id && $commande->examen)
                                            <div class="d-flex align-items-center">
                                                <i class="ri-test-tube-line me-2" style="color:#667eea;"></i>
                                                <div>
                                                    <div class="fw-semibold">{{ $commande->examen->label }}</div>
                                                    <small class="text-muted">Examen - {{ number_format($commande->examen->price, 0, ',', ' ') }} FCFA</small>
                                                </div>
                                            </div>
                                        @elseif($commande->type_bilan_id && $commande->type_bilan)
                                            <div class="d-flex align-items-center">
                                                <i class="ri-file-list-3-line me-2" style="color:#667eea;"></i>
                                                <div>
                                                    <div class="fw-semibold">{{ $commande->type_bilan->label }}</div>
                                                    <small class="text-muted">Bilan - {{ number_format($commande->type_bilan->price, 0, ',', ' ') }} FCFA</small>
                                                </div>
                                            </div>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>

                                    <!-- Client -->
                                    <td>
                                        @if($commande->client)
                                            <div class="d-flex align-items-center">
    
                                                <div>
                                                    <div class="fw-semibold">{{ $commande->client->firstname }} {{ $commande->client->lastname }}</div>
                                                    <small class="text-muted">{{ $commande->client->phone }}</small>
                                                </div>
                                            </div>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>

                                    <!-- Date -->
                                    <td class="text-center">
                                        <i class="ri-calendar-line text-muted me-1"></i>
                                        {{ $commande->date_prelevement ?? 'Non définie' }}
                                    </td>

                                    <!-- Statut -->
                                    <td class="text-center">
                                        @php
                                            $statut_class = match($commande->statut) {
                                                'pending' => 'warning',
                                                'En cours' => 'info',
                                                'Terminer' => 'success',
                                                'Annuler' => 'danger',
                                                default => 'secondary'
                                            };
                                        @endphp
                                        <span class="badge bg-{{$statut_class}} px-3 py-2">
                                            {{ $commande->statut }}
                                        </span>
                                    </td>

                                    <!-- Agent -->
                                    <td class="text-center">
                                        @if($commande->agent)
                                            <span class="text-success">
                                                <i class="ri-user-line me-1"></i>
                                                {{ $commande->agent->firstname }}
                                            </span>
                                        @else
                                            <span class="badge bg-warning-lighten text-warning">
                                                <i class="ri-time-line me-1"></i>
                                                Non assigné
                                            </span>
                                        @endif
                                    </td>

                                    <!-- Résultat -->
                                    <td class="text-center">
                                        @if($commande->resultat && $commande->resultat->pdf_url)
                                            <a href="{{ asset($commande->resultat->pdf_url) }}" 
                                               target="_blank"
                                               class="btn btn-sm btn-success">
                                                <i class="ri-file-pdf-line me-1"></i>
                                                Voir PDF
                                            </a>
                                        @elseif($commande->statut == 'Annuler')
                                            <span class="text-muted">-</span>
                                        @elseif(!$commande->agent_id)
                                            <button type="button" 
                                                    class="btn btn-sm btn-secondary"
                                                    disabled
                                                    data-bs-toggle="tooltip"
                                                    title="En attente d'assignation d'agent par l'admin">
                                                <i class="ri-time-line me-1"></i>
                                                En attente
                                            </button>
                                        @else
                                            <span class="badge bg-warning">
                                                {{ $commande->resultats_count ?? 0 }} / {{ $commande->nombre_analyses }} résultats
                                            </span>
                                        @endif
                                    </td>

                                    <!-- Actions -->
                                    <td class="text-center">
                                        <a href="{{ route('laboratoire.commande.details', $commande->first_id) }}" 
                                           class="btn btn-sm text-white" style="background:#667eea;"
                                           data-bs-toggle="tooltip" 
                                           title="Voir détails et gérer les résultats">
                                            <i class="ri-eye-line me-1"></i>
                                            Détails
                                        </a>
                                    </td>
                                </tr>
                                
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center py-5">
                                        <div class="text-muted">
                                            <i class="ri-inbox-line" style="font-size: 48px;"></i>
                                            <p class="mt-3 mb-0">Aucune commande trouvée</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if($commandes->hasPages())
                    <div class="card-footer bg-light">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="text-muted">
                                Affichage de {{ $commandes->firstItem() }} à {{ $commandes->lastItem() }} sur {{ $commandes->total() }} commandes
                            </div>
                            <div>
                                {{ $commandes->links() }}
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
