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
                        <li class="breadcrumb-item active">Portefeuilles</li>
                    </ol>
                </div>
                <h4 class="page-title">Gestion des Portefeuilles</h4>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row row-cols-1 row-cols-md-5 mb-4 g-2">
        <div class="col">
            <div class="card bg-primary text-white text-center h-100 p-2 mb-0">
                <h6 class="text-white opacity-75">Commissions</h6>
                <h4 class="mb-0">{{ number_format($platformStats['total_commissions'] ?? 0, 0, ',', ' ') }}</h4>
            </div>
        </div>
        <div class="col">
            <div class="card bg-success text-white text-center h-100 p-2 mb-0">
                <h6 class="text-white opacity-75">Revenus Labos</h6>
                <h4 class="mb-0">{{ number_format($platformStats['total_lab_earnings'] ?? 0, 0, ',', ' ') }}</h4>
            </div>
        </div>
        <div class="col">
            <div class="card bg-info text-white text-center h-100 p-2 mb-0">
                <h6 class="text-white opacity-75">Transactions</h6>
                <h4 class="mb-0">{{ $platformStats['total_transactions'] ?? 0 }}</h4>
            </div>
        </div>
        <div class="col">
            <div class="card bg-warning text-white text-center h-100 p-2 mb-0">
                <h6 class="text-white opacity-75">Actifs</h6>
                <h4 class="mb-0">{{ $platformStats['active_wallets'] ?? 0 }}</h4>
            </div>
        </div>
        <div class="col">
            <div class="card bg-danger text-white text-center h-100 p-2 mb-0">
                <h6 class="text-white opacity-75">Dettes Labos</h6>
                <h4 class="mb-0">{{ number_format($platformStats['total_debts'] ?? 0, 0, ',', ' ') }}</h4>
                <small style="font-size: 0.7rem;">{{ $platformStats['debt_count'] ?? 0 }} débiteurs</small>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body d-flex gap-3 flex-wrap">
                    <a href="{{ route('wallets.platform') }}" class="btn btn-outline-primary">
                        <i class="ri-wallet-3-line me-1"></i> Portefeuille Plateforme
                    </a>
                    <a href="{{ route('wallets.withdrawals') }}" class="btn btn-outline-success">
                        <i class="ri-bank-line me-1"></i> Retraits Mensuels
                    </a>
                    <form action="{{ route('wallets.withdrawals.generate') }}" method="POST" class="d-inline">
                        @csrf
                        <input type="hidden" name="periode" value="{{ now()->subMonth()->format('Y-m') }}">
                        <button type="submit" class="btn btn-outline-warning" onclick="return confirm('Générer les retraits pour le mois dernier ?')">
                            <i class="ri-calendar-check-line me-1"></i> Générer Retraits Mensuels
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>{{ session('success') }}</strong>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>{{ session('error') }}</strong>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Filters -->
    <div class="row mb-3">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form method="GET" action="{{ route('wallets.index') }}" class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label">Type</label>
                            <select name="type" class="form-select">
                                <option value="">Tous</option>
                                <option value="laboratoire" {{ request('type') == 'laboratoire' ? 'selected' : '' }}>Laboratoire</option>
                                <option value="plateforme" {{ request('type') == 'plateforme' ? 'selected' : '' }}>Plateforme</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Statut</label>
                            <select name="status" class="form-select">
                                <option value="">Tous</option>
                                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Actif</option>
                                <option value="suspended" {{ request('status') == 'suspended' ? 'selected' : '' }}>Suspendu</option>
                                <option value="blocked" {{ request('status') == 'blocked' ? 'selected' : '' }}>Bloqué</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Période</label>
                            <input type="month" name="periode" value="{{ $periode }}" class="form-control">
                        </div>
                        <div class="col-md-3 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary me-2">
                                <i class="ri-search-line"></i> Filtrer
                            </button>
                            <a href="{{ route('wallets.index') }}" class="btn btn-secondary">
                                <i class="ri-refresh-line"></i>
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Top Laboratoires -->
    @if(!empty($topLabos) && count($topLabos) > 0)
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="ri-trophy-line me-2"></i>Top 5 Laboratoires ({{ $periode }})</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Laboratoire</th>
                                    <th>Revenus</th>
                                    <th>Transactions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($topLabos as $index => $labo)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $labo['nom'] }}</td>
                                    <td>{{ number_format($labo['total'], 0, ',', ' ') }} FCFA</td>
                                    <td>{{ $labo['count'] }}</td>
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

    <!-- Wallets Table -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="ri-wallet-line me-2"></i>Liste des Portefeuilles</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="bg-primary">
                                <tr>
                                    <th class="text-white">Propriétaire</th>
                                    <th class="text-white">Type</th>
                                    <th class="text-white text-end">Solde</th>
                                    <th class="text-white text-center">Statut</th>
                                    <th class="text-white text-center">Transactions</th>
                                    <th class="text-white text-center">Dernière Activité</th>
                                    <th class="text-white text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($wallets as $wallet)
                                <tr>
                                    <td>
                                        @if($wallet->user && $wallet->user->laboratorie)
                                            <strong>{{ $wallet->user->laboratorie->nom }}</strong>
                                            <br><small class="text-muted">{{ $wallet->user->email }}</small>
                                        @elseif($wallet->user)
                                            {{ $wallet->user->firstname }} {{ $wallet->user->lastname }}
                                            <br><small class="text-muted">{{ $wallet->user->email }}</small>
                                        @else
                                            <span class="text-muted">N/A</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($wallet->type == 'plateforme')
                                            <span class="badge bg-primary">Plateforme</span>
                                        @else
                                            <span class="badge bg-info">Laboratoire</span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <strong class="{{ $wallet->balance >= 0 ? 'text-success' : 'text-danger' }}">
                                            {{ number_format($wallet->balance, 0, ',', ' ') }} FCFA
                                        </strong>
                                    </td>
                                    <td class="text-center">
                                        @switch($wallet->status)
                                            @case('active')
                                                <span class="badge bg-success">Actif</span>
                                                @break
                                            @case('suspended')
                                                <span class="badge bg-warning">Suspendu</span>
                                                @break
                                            @case('blocked')
                                                <span class="badge bg-danger">Bloqué</span>
                                                @break
                                            @default
                                                <span class="badge bg-secondary">{{ $wallet->status }}</span>
                                        @endswitch
                                    </td>
                                    <td class="text-center">
                                        {{ $wallet->transactions_count ?? $wallet->transactions()->count() }}
                                    </td>
                                    <td class="text-center">
                                        <small>{{ $wallet->updated_at ? $wallet->updated_at->diffForHumans() : 'N/A' }}</small>
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('wallets.show', $wallet->id) }}" class="btn btn-outline-primary" title="Voir">
                                                <i class="ri-eye-line"></i>
                                            </a>
                                            <a href="{{ route('wallets.transactions', $wallet->id) }}" class="btn btn-outline-info" title="Transactions">
                                                <i class="ri-history-line"></i>
                                            </a>
                                            <button type="button" class="btn btn-outline-success" title="Ajuster" data-bs-toggle="modal" data-bs-target="#adjustModal{{ $wallet->id }}">
                                                <i class="ri-add-line"></i>
                                            </button>
                                            @if($wallet->status == 'active')
                                                <form action="{{ route('wallets.suspend', $wallet->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-outline-warning" title="Suspendre" onclick="return confirm('Suspendre ce portefeuille ?')">
                                                        <i class="ri-pause-line"></i>
                                                    </button>
                                                </form>
                                            @else
                                                <form action="{{ route('wallets.activate', $wallet->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-outline-success" title="Activer">
                                                        <i class="ri-play-line"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>

                                <!-- Adjust Modal -->
                                <div class="modal fade" id="adjustModal{{ $wallet->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Ajuster le solde</h5>
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
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4 text-muted">
                                        <i class="ri-wallet-line fs-1"></i>
                                        <p class="mt-2">Aucun portefeuille trouvé</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if($wallets->hasPages())
                <div class="card-footer">
                    {{ $wallets->appends(request()->query())->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

@endsection
