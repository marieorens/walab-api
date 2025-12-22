@extends('layout')
@section('page_content')

<div class="container-fluid">
    <!-- Page Title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="#">Walab</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Paiements</li>
                    </ol>
                </div>
                <h4 class="page-title">Gestion des Paiements</h4>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5 class="text-white-50">Total Encaissé</h5>
                            <h3 class="mb-0">{{ number_format($stats['total_montant'], 0, ',', ' ') }} FCFA</h3>
                        </div>
                        <div class="align-self-center">
                            <i class="ri-money-dollar-circle-line fs-1 text-white-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5 class="text-white-50">Payés</h5>
                            <h3 class="mb-0">{{ $stats['payes'] }}</h3>
                        </div>
                        <div class="align-self-center">
                            <i class="ri-checkbox-circle-line fs-1 text-white-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5 class="text-white-50">En Attente</h5>
                            <h3 class="mb-0">{{ $stats['en_attente'] }}</h3>
                        </div>
                        <div class="align-self-center">
                            <i class="ri-time-line fs-1 text-white-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-danger text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5 class="text-white-50">Échoués</h5>
                            <h3 class="mb-0">{{ $stats['echoues'] }}</h3>
                        </div>
                        <div class="align-self-center">
                            <i class="ri-close-circle-line fs-1 text-white-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="row mb-3">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form method="GET" action="{{ route('paiements.index') }}" class="row g-3">
                        <div class="col-md-2">
                            <label class="form-label">Recherche</label>
                            <input type="text" name="search" class="form-control" placeholder="Transaction, Code..." value="{{ request('search') }}">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Statut</label>
                            <select name="status" class="form-select">
                                <option value="">Tous</option>
                                <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approuvé</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>En attente</option>
                                <option value="declined" {{ request('status') == 'declined' ? 'selected' : '' }}>Refusé</option>
                                <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Annulé</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Laboratoire</label>
                            <select name="laboratoire_id" class="form-select">
                                <option value="">Tous</option>
                                @foreach($laboratoires as $labo)
                                    <option value="{{ $labo->id }}" {{ request('laboratoire_id') == $labo->id ? 'selected' : '' }}>{{ $labo->nom ?? $labo->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Date début</label>
                            <input type="date" name="date_debut" class="form-control" value="{{ request('date_debut') }}">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Date fin</label>
                            <input type="date" name="date_fin" class="form-control" value="{{ request('date_fin') }}">
                        </div>
                        <div class="col-md-2 d-flex align-items-end gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="ri-filter-line"></i> Filtrer
                            </button>
                            <a href="{{ route('paiements.index') }}" class="btn btn-secondary">
                                <i class="ri-refresh-line"></i>
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Paiements Table -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="ri-bank-card-line me-2"></i>Liste des Paiements ({{ $paiements->total() }})</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="bg-primary">
                                <tr>
                                    <th class="text-white">ID</th>
                                    <th class="text-white">Date</th>
                                    <th class="text-white">Commande</th>
                                    <th class="text-white">Laboratoire</th>
                                    <th class="text-white text-end">Montant</th>
                                    <th class="text-white text-center">Mode</th>
                                    <th class="text-white text-center">Statut</th>
                                    <th class="text-white text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($paiements as $paiement)
                                <tr>
                                    <td>
                                        <strong>#{{ $paiement->id }}</strong>
                                        @if($paiement->transaction_id)
                                            <br><small class="text-muted">{{ Str::limit($paiement->transaction_id, 15) }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        <span>{{ $paiement->created_at->format('d/m/Y') }}</span>
                                        <br><small class="text-muted">{{ $paiement->created_at->format('H:i') }}</small>
                                    </td>
                                    <td>
                                        @if($paiement->code_commande)
                                            <span class="text-primary fw-bold">{{ $paiement->code_commande }}</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($paiement->laboratoire)
                                            {{ $paiement->laboratoire->nom ?? $paiement->laboratoire->name }}
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <strong>{{ number_format($paiement->montant, 0, ',', ' ') }} FCFA</strong>
                                    </td>
                                    <td class="text-center">
                                        @if($paiement->mode)
                                            <span class="badge bg-info">{{ $paiement->mode }}</span>
                                        @else
                                            <span class="badge bg-light text-dark">-</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @switch($paiement->status)
                                            @case('approved')
                                                <span class="badge bg-success">
                                                    <i class="ri-check-line"></i> Approuvé
                                                </span>
                                                @break
                                            @case('pending')
                                                <span class="badge bg-warning">
                                                    <i class="ri-time-line"></i> En attente
                                                </span>
                                                @break
                                            @case('declined')
                                                <span class="badge bg-danger">
                                                    <i class="ri-close-line"></i> Refusé
                                                </span>
                                                @break
                                            @case('cancelled')
                                                <span class="badge bg-secondary">
                                                    <i class="ri-close-line"></i> Annulé
                                                </span>
                                                @break
                                            @default
                                                <span class="badge bg-secondary">{{ $paiement->status }}</span>
                                        @endswitch
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('paiements.show', $paiement->id) }}" class="btn btn-sm btn-outline-primary" title="Voir détails">
                                            <i class="ri-eye-line"></i>
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center py-4 text-muted">
                                        <i class="ri-bank-card-line fs-1"></i>
                                        <p class="mt-2 mb-0">Aucun paiement trouvé</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if($paiements->hasPages())
                <div class="card-footer">
                    {{ $paiements->appends(request()->query())->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

@endsection
