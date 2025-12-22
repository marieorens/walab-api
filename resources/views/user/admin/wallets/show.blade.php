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
                        <li class="breadcrumb-item active">Détails</li>
                    </ol>
                </div>
                <h4 class="page-title">Détails du Portefeuille</h4>
            </div>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <!-- Wallet Info Card -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="ri-wallet-3-line me-2"></i>
                        @if($wallet->type == 'plateforme')
                            Portefeuille Plateforme
                        @else
                            Portefeuille Laboratoire
                        @endif
                    </h5>
                </div>
                <div class="card-body">
                    @if($wallet->user && $wallet->user->laboratorie)
                        <div class="mb-3">
                            <label class="text-muted small">Laboratoire</label>
                            <h5>{{ $wallet->user->laboratorie->nom }}</h5>
                        </div>
                        <div class="mb-3">
                            <label class="text-muted small">Propriétaire</label>
                            <p class="mb-0">{{ $wallet->user->firstname }} {{ $wallet->user->lastname }}</p>
                            <small class="text-muted">{{ $wallet->user->email }}</small>
                        </div>
                        <div class="mb-3">
                            <label class="text-muted small">Commission</label>
                            <p class="mb-0">{{ $wallet->user->laboratorie->pourcentage_commission ?? 0 }}%</p>
                        </div>
                    @elseif($wallet->user)
                        <div class="mb-3">
                            <label class="text-muted small">Propriétaire</label>
                            <h5>{{ $wallet->user->firstname }} {{ $wallet->user->lastname }}</h5>
                            <small class="text-muted">{{ $wallet->user->email }}</small>
                        </div>
                    @endif

                    <hr>

                    <div class="mb-3">
                        <label class="text-muted small">Solde Actuel</label>
                        <h3 class="{{ $wallet->balance >= 0 ? 'text-success' : 'text-danger' }}">
                            {{ number_format($wallet->balance, 0, ',', ' ') }} FCFA
                        </h3>
                    </div>

                    <div class="mb-3">
                        <label class="text-muted small">Statut</label>
                        <div>
                            @switch($wallet->status)
                                @case('active')
                                    <span class="badge bg-success fs-6">Actif</span>
                                    @break
                                @case('suspended')
                                    <span class="badge bg-warning fs-6">Suspendu</span>
                                    @break
                                @case('blocked')
                                    <span class="badge bg-danger fs-6">Bloqué</span>
                                    @break
                            @endswitch
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="text-muted small">Créé le</label>
                        <p class="mb-0">{{ $wallet->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="d-grid gap-2">
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#adjustModal">
                            <i class="ri-add-line me-1"></i> Ajuster le Solde
                        </button>
                        @if($wallet->status == 'active')
                            <form action="{{ route('wallets.suspend', $wallet->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-warning w-100" onclick="return confirm('Suspendre ce portefeuille ?')">
                                    <i class="ri-pause-line me-1"></i> Suspendre
                                </button>
                            </form>
                        @else
                            <form action="{{ route('wallets.activate', $wallet->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-success w-100">
                                    <i class="ri-play-line me-1"></i> Activer
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats Card -->
        <div class="col-md-8">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <div class="card bg-success text-white h-100">
                        <div class="card-body">
                            <h6 class="text-white-50">Crédits ({{ $periode }})</h6>
                            <h3 class="mb-0">{{ number_format($stats['credits'] ?? 0, 0, ',', ' ') }} FCFA</h3>
                            <small>{{ $stats['credits_count'] ?? 0 }} transactions</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="card bg-danger text-white h-100">
                        <div class="card-body">
                            <h6 class="text-white-50">Débits ({{ $periode }})</h6>
                            <h3 class="mb-0">{{ number_format($stats['debits'] ?? 0, 0, ',', ' ') }} FCFA</h3>
                            <small>{{ $stats['debits_count'] ?? 0 }} transactions</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="card bg-info text-white h-100">
                        <div class="card-body">
                            <h6 class="text-white-50">Solde du Mois</h6>
                            <h3 class="mb-0">{{ number_format(($stats['credits'] ?? 0) - ($stats['debits'] ?? 0), 0, ',', ' ') }} FCFA</h3>
                            <small>Période: {{ $periode }}</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="card bg-primary text-white h-100">
                        <div class="card-body">
                            <h6 class="text-white-50">Total Transactions</h6>
                            <h3 class="mb-0">{{ ($stats['credits_count'] ?? 0) + ($stats['debits_count'] ?? 0) }}</h3>
                            <small>Ce mois</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Transactions -->
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Transactions Récentes</h5>
                    <a href="{{ route('wallets.transactions', $wallet->id) }}" class="btn btn-sm btn-outline-primary">
                        Voir tout
                    </a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Type</th>
                                    <th>Description</th>
                                    <th class="text-end">Montant</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($transactions as $transaction)
                                <tr>
                                    <td>
                                        <small>{{ $transaction->created_at->format('d/m/Y H:i') }}</small>
                                    </td>
                                    <td>
                                        @if($transaction->type == 'credit')
                                            <span class="badge bg-success">Crédit</span>
                                        @else
                                            <span class="badge bg-danger">Débit</span>
                                        @endif
                                    </td>
                                    <td>
                                        {{ $transaction->description }}
                                        @if(Str::contains($transaction->description, 'physique'))
                                            <br><span class="badge badge-soft-danger text-danger border border-danger p-0 px-1" style="font-size: 0.7rem;">DEBIT CASH</span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <strong class="{{ $transaction->type == 'credit' ? 'text-success' : 'text-danger' }}">
                                            {{ $transaction->type == 'credit' ? '+' : '-' }}{{ number_format($transaction->montant, 0, ',', ' ') }} FCFA
                                        </strong>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center py-3 text-muted">
                                        Aucune transaction
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Adjust Modal -->
<div class="modal fade" id="adjustModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Ajuster le Solde</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('wallets.adjust', $wallet->id) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Type d'ajustement</label>
                        <select name="type" class="form-select" required>
                            <option value="credit">Crédit (+)</option>
                            <option value="debit">Débit (-)</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Montant (FCFA)</label>
                        <input type="number" name="montant" class="form-control" required min="1" step="1">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <input type="text" name="description" class="form-control" required placeholder="Raison de l'ajustement">
                    </div>
                    <div class="alert alert-info mb-0">
                        <small>Solde actuel: <strong>{{ number_format($wallet->balance, 0, ',', ' ') }} FCFA</strong></small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Appliquer</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
