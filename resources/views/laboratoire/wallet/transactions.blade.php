@extends('laboratoire.layout')
@section('page_content')

<div class="container-fluid">
    <!-- Page Title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('laboratoire.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('laboratoire.wallet') }}">Portefeuille</a></li>
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
                    <h6 class="text-white-50">Solde Actuel</h6>
                    <h3 class="mb-0">{{ number_format($wallet->balance, 0, ',', ' ') }} FCFA</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h6 class="text-white-50">Total Crédits</h6>
                    <h3 class="mb-0">{{ number_format($wallet->transactions()->credits()->sum('montant'), 0, ',', ' ') }} FCFA</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-danger text-white">
                <div class="card-body">
                    <h6 class="text-white-50">Total Débits</h6>
                    <h3 class="mb-0">{{ number_format($wallet->transactions()->debits()->sum('montant'), 0, ',', ' ') }} FCFA</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <h6 class="text-white-50">Total Transactions</h6>
                    <h3 class="mb-0">{{ $wallet->transactions()->count() }}</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="row mb-3">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form method="GET" action="{{ route('laboratoire.wallet.transactions') }}" class="row g-3">
                        <div class="col-md-2">
                            <label class="form-label">Type</label>
                            <select name="type" class="form-select">
                                <option value="">Tous</option>
                                <option value="credit" {{ ($filters['type'] ?? '') == 'credit' ? 'selected' : '' }}>Crédit</option>
                                <option value="debit" {{ ($filters['type'] ?? '') == 'debit' ? 'selected' : '' }}>Débit</option>
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
                        <div class="col-md-4 d-flex align-items-end gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="ri-filter-line"></i> Filtrer
                            </button>
                            <a href="{{ route('laboratoire.wallet.transactions') }}" class="btn btn-secondary">
                                <i class="ri-refresh-line"></i> Réinitialiser
                            </a>
                            <a href="{{ route('laboratoire.wallet') }}" class="btn btn-outline-primary">
                                <i class="ri-arrow-left-line"></i> Retour
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
                <div class="card-header">
                    <h5 class="mb-0"><i class="ri-history-line me-2"></i>Toutes les Transactions</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th>#</th>
                                    <th>Date</th>
                                    <th>Type</th>
                                    <th>Description</th>
                                    <th>Référence</th>
                                    <th class="text-end">Montant</th>
                                    <th class="text-end">Solde Après</th>
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
                                            <span class="badge bg-success">
                                                <i class="ri-arrow-down-line"></i> Crédit
                                            </span>
                                        @else
                                            <span class="badge bg-danger">
                                                <i class="ri-arrow-up-line"></i> Débit
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <span title="{{ $transaction->description }}">
                                            {{ Str::limit($transaction->description, 40) }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($transaction->paiement_id)
                                            <small class="text-muted">Paiement #{{ $transaction->paiement_id }}</small>
                                        @elseif($transaction->commande_id)
                                            <small class="text-muted">Cmd #{{ $transaction->commande_id }}</small>
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
                                        <span>{{ number_format($transaction->montant_apres ?? 0, 0, ',', ' ') }} FCFA</span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center py-4 text-muted">
                                        <i class="ri-inbox-line fs-1"></i>
                                        <p class="mt-2 mb-0">Aucune transaction trouvée</p>
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
