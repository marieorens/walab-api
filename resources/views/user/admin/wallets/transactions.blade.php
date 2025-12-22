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
                        <li class="breadcrumb-item"><a href="{{ route('wallets.index') }}">Portefeuilles</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('wallets.show', $wallet->id) }}">{{ $wallet->user->laboratorie->nom ?? 'Portefeuille' }}</a></li>
                        <li class="breadcrumb-item active">Transactions</li>
                    </ol>
                </div>
                <h4 class="page-title">Historique des Transactions</h4>
            </div>
        </div>
    </div>

    <!-- Wallet Summary -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h6 class="text-white-50">Portefeuille</h6>
                    <h5 class="mb-0">{{ $wallet->user->laboratorie->nom ?? ($wallet->user->firstname ?? 'Plateforme') }}</h5>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h6 class="text-white-50">Solde Actuel</h6>
                    <h4 class="mb-0">{{ number_format($wallet->balance, 0, ',', ' ') }} FCFA</h4>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <h6 class="text-white-50">Total Crédits</h6>
                    <h4 class="mb-0">{{ number_format($wallet->transactions()->credits()->sum('montant'), 0, ',', ' ') }} FCFA</h4>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-danger text-white">
                <div class="card-body">
                    <h6 class="text-white-50">Total Débits</h6>
                    <h4 class="mb-0">{{ number_format($wallet->transactions()->debits()->sum('montant'), 0, ',', ' ') }} FCFA</h4>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="row mb-3">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form method="GET" action="{{ route('wallets.transactions', $wallet->id) }}" class="row g-3">
                        <div class="col-md-2">
                            <label class="form-label">Type</label>
                            <select name="type" class="form-select">
                                <option value="">Tous</option>
                                <option value="credit" {{ ($filters['type'] ?? '') == 'credit' ? 'selected' : '' }}>Crédit</option>
                                <option value="debit" {{ ($filters['type'] ?? '') == 'debit' ? 'selected' : '' }}>Débit</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Statut</label>
                            <select name="status" class="form-select">
                                <option value="">Tous</option>
                                <option value="completed" {{ ($filters['status'] ?? '') == 'completed' ? 'selected' : '' }}>Complété</option>
                                <option value="pending" {{ ($filters['status'] ?? '') == 'pending' ? 'selected' : '' }}>En attente</option>
                                <option value="failed" {{ ($filters['status'] ?? '') == 'failed' ? 'selected' : '' }}>Échoué</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Période</label>
                            <input type="month" name="periode" class="form-control" value="{{ $filters['periode'] ?? '' }}">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Date début</label>
                            <input type="date" name="date_debut" class="form-control" value="{{ $filters['date_debut'] ?? '' }}">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Date fin</label>
                            <input type="date" name="date_fin" class="form-control" value="{{ $filters['date_fin'] ?? '' }}">
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary me-2">
                                <i class="ri-filter-line"></i> Filtrer
                            </button>
                            <a href="{{ route('wallets.transactions', $wallet->id) }}" class="btn btn-secondary">
                                <i class="ri-refresh-line"></i>
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Transactions Table -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="ri-history-line me-2"></i>Transactions</h5>
                    <a href="{{ route('wallets.show', $wallet->id) }}" class="btn btn-sm btn-outline-secondary">
                        <i class="ri-arrow-left-line"></i> Retour
                    </a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="bg-light">
                                <tr>
                                     <th>ID</th>
                                    <th>Date</th>
                                    <th>Type</th>
                                    <th>Description</th>
                                    <th>Référence</th>
                                    <th class="text-end">Montant</th>
                                    <th class="text-end">Solde Après</th>
                                    <th class="text-center">Statut</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($transactions as $transaction)
                                <tr>
                                    <td>
                                        <small class="text-muted">#{{ $transaction->id }}</small>
                                    </td>
                                    <td>
                                        <span>{{ $transaction->created_at->format('d/m/Y') }}</span>
                                        <br>
                                        <small class="text-muted">{{ $transaction->created_at->format('H:i') }}</small>
                                    </td>
                                    <td>
                                        @if($transaction->type == 'credit')
                                            <span class="badge bg-success"><i class="ri-arrow-down-line"></i> Crédit</span>
                                        @else
                                            <span class="badge bg-danger"><i class="ri-arrow-up-line"></i> Débit</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span title="{{ $transaction->description }}">
                                            {{ Str::limit($transaction->description, 40) }}
                                        </span>
                                        @if(Str::contains($transaction->description, 'physique'))
                                            <br><span class="badge badge-soft-danger text-danger border border-danger p-0 px-1" style="font-size: 0.7rem;">DEBIT CASH</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($transaction->paiement_id)
                                            <small>Paiement #{{ $transaction->paiement_id }}</small>
                                        @elseif($transaction->commande_id)
                                            <small>Cmd #{{ $transaction->commande_id }}</small>
                                        @elseif($transaction->reference)
                                            <small>{{ $transaction->reference }}</small>
                                        @else
                                            <small class="text-muted">-</small>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <strong class="{{ $transaction->type == 'credit' ? 'text-success' : 'text-danger' }}">
                                            {{ $transaction->type == 'credit' ? '+' : '-' }}{{ number_format($transaction->montant, 0, ',', ' ') }} FCFA
                                        </strong>
                                    </td>
                                    <td class="text-end">
                                        <span class="{{ ($transaction->montant_apres ?? 0) >= 0 ? 'text-dark' : 'text-danger' }}">
                                            {{ number_format($transaction->montant_apres ?? 0, 0, ',', ' ') }} FCFA
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        @switch($transaction->status)
                                            @case('completed')
                                                <span class="badge bg-success">Complété</span>
                                                @break
                                            @case('pending')
                                                <span class="badge bg-warning">En attente</span>
                                                @break
                                            @case('failed')
                                                <span class="badge bg-danger">Échoué</span>
                                                @break
                                            @default
                                                <span class="badge bg-secondary">{{ $transaction->status }}</span>
                                        @endswitch
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="9" class="text-center py-4 text-muted">
                                        <i class="ri-file-list-line fs-1"></i>
                                        <p class="mt-2">Aucune transaction trouvée</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if($transactions->hasPages())
                <div class="card-footer">
                    {{ $transactions->appends($filters ?? [])->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

@endsection
