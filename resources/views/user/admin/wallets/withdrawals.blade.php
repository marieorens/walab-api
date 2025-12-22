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
                        <li class="breadcrumb-item active">Retraits</li>
                    </ol>
                </div>
                <h4 class="page-title">Gestion des Retraits Mensuels</h4>
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

    <!-- Stats & Actions -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <h5 class="card-title text-white">Retraits en Attente</h5>
                    <h2 class="mb-0">{{ $pendingCount }}</h2>
                    <small>À traiter</small>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Actions</h5>
                    <form action="{{ route('wallets.withdrawals.generate') }}" method="POST" class="row g-3">
                        @csrf
                        <div class="col-md-4">
                            <label class="form-label">Période à générer</label>
                            <input type="month" name="periode" class="form-control" value="{{ now()->subMonth()->format('Y-m') }}">
                        </div>
                        <div class="col-md-4 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary" onclick="return confirm('Générer les retraits pour cette période ?')">
                                <i class="ri-calendar-check-line me-1"></i> Générer Retraits
                            </button>
                        </div>
                    </form>
                    <hr>
                    <small class="text-muted">
                        <i class="ri-information-line"></i> 
                        Les retraits sont générés mensuellement pour chaque laboratoire ayant un solde positif.
                    </small>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="row mb-3">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form method="GET" action="{{ route('wallets.withdrawals') }}" class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label">Statut</label>
                            <select name="status" class="form-select">
                                <option value="">Tous</option>
                                <option value="pending" {{ $status == 'pending' ? 'selected' : '' }}>En attente</option>
                                <option value="processing" {{ $status == 'processing' ? 'selected' : '' }}>En cours</option>
                                <option value="completed" {{ $status == 'completed' ? 'selected' : '' }}>Complété</option>
                                <option value="rejected" {{ $status == 'rejected' ? 'selected' : '' }}>Rejeté</option>
                                <option value="cancelled" {{ $status == 'cancelled' ? 'selected' : '' }}>Annulé</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Période</label>
                            <input type="month" name="periode" class="form-control" value="{{ $periode ?? '' }}">
                        </div>
                        <div class="col-md-3 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary me-2">
                                <i class="ri-filter-line"></i> Filtrer
                            </button>
                            <a href="{{ route('wallets.withdrawals') }}" class="btn btn-secondary">
                                <i class="ri-refresh-line"></i>
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Withdrawals Table -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="ri-bank-line me-2"></i>Liste des Retraits</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="bg-primary">
                                <tr>
                                    <th class="text-white">ID</th>
                                    <th class="text-white">Laboratoire</th>
                                    <th class="text-white">Période</th>
                                    <th class="text-white text-end">Montant</th>
                                    <th class="text-white text-center">Statut</th>
                                    <th class="text-white">Date Demande</th>
                                    <th class="text-white">Date Traitement</th>
                                    <th class="text-white">Notes</th>
                                    <th class="text-white text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($withdrawals as $withdrawal)
                                <tr>
                                    <td>#{{ $withdrawal->id }}</td>
                                    <td>
                                        @if($withdrawal->wallet && $withdrawal->wallet->user && $withdrawal->wallet->user->laboratorie)
                                            <strong>{{ $withdrawal->wallet->user->laboratorie->nom }}</strong>
                                            <br>
                                            <small class="text-muted">{{ $withdrawal->wallet->user->email }}</small>
                                        @else
                                            <span class="text-muted">N/A</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark">{{ $withdrawal->periode }}</span>
                                    </td>
                                    <td class="text-end">
                                        <strong>{{ number_format($withdrawal->montant, 0, ',', ' ') }} FCFA</strong>
                                    </td>
                                    <td class="text-center">
                                        @switch($withdrawal->status)
                                            @case('pending')
                                                <span class="badge bg-warning">En attente</span>
                                                @break
                                            @case('processing')
                                                <span class="badge bg-info">En cours</span>
                                                @break
                                            @case('completed')
                                                <span class="badge bg-success">Complété</span>
                                                @break
                                            @case('rejected')
                                                <span class="badge bg-danger">Rejeté</span>
                                                @break
                                            @case('cancelled')
                                                <span class="badge bg-secondary">Annulé</span>
                                                @break
                                        @endswitch
                                    </td>
                                    <td>
                                        <small>{{ $withdrawal->created_at->format('d/m/Y H:i') }}</small>
                                    </td>
                                    <td>
                                        @if($withdrawal->processed_at)
                                            <small>{{ $withdrawal->processed_at->format('d/m/Y H:i') }}</small>
                                        @else
                                            <small class="text-muted">-</small>
                                        @endif
                                    </td>
                                    <td>
                                        @if($withdrawal->notes)
                                            <span title="{{ $withdrawal->notes }}">{{ Str::limit($withdrawal->notes, 30) }}</span>
                                        @else
                                            <small class="text-muted">-</small>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if($withdrawal->status == 'pending')
                                            <div class="btn-group btn-group-sm">
                                                <button type="button" class="btn btn-success" title="Approuver" data-bs-toggle="modal" data-bs-target="#approveModal{{ $withdrawal->id }}">
                                                    <i class="ri-check-line"></i>
                                                </button>
                                                <button type="button" class="btn btn-danger" title="Rejeter" data-bs-toggle="modal" data-bs-target="#rejectModal{{ $withdrawal->id }}">
                                                    <i class="ri-close-line"></i>
                                                </button>
                                            </div>

                                            <!-- Approve Modal -->
                                            <div class="modal fade" id="approveModal{{ $withdrawal->id }}" tabindex="-1">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header bg-success text-white">
                                                            <h5 class="modal-title">Approuver le Retrait</h5>
                                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                                        </div>
                                                        <form action="{{ route('wallets.withdrawals.process', $withdrawal->id) }}" method="POST">
                                                            @csrf
                                                            <input type="hidden" name="action" value="approve">
                                                            <div class="modal-body text-start">
                                                                <p><strong>Laboratoire:</strong> {{ $withdrawal->wallet->user->laboratorie->nom ?? 'N/A' }}</p>
                                                                <p><strong>Montant:</strong> {{ number_format($withdrawal->montant, 0, ',', ' ') }} FCFA</p>
                                                                <p><strong>Période:</strong> {{ $withdrawal->periode }}</p>
                                                                <hr>
                                                                <div class="mb-3">
                                                                    <label class="form-label">Notes (optionnel)</label>
                                                                    <textarea name="notes" class="form-control" rows="2" placeholder="Référence de virement, etc."></textarea>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                                                <button type="submit" class="btn btn-success">Approuver & Traiter</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Reject Modal -->
                                            <div class="modal fade" id="rejectModal{{ $withdrawal->id }}" tabindex="-1">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header bg-danger text-white">
                                                            <h5 class="modal-title">Rejeter le Retrait</h5>
                                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                                        </div>
                                                        <form action="{{ route('wallets.withdrawals.process', $withdrawal->id) }}" method="POST">
                                                            @csrf
                                                            <input type="hidden" name="action" value="reject">
                                                            <div class="modal-body text-start">
                                                                <p><strong>Laboratoire:</strong> {{ $withdrawal->wallet->user->laboratorie->nom ?? 'N/A' }}</p>
                                                                <p><strong>Montant:</strong> {{ number_format($withdrawal->montant, 0, ',', ' ') }} FCFA</p>
                                                                <hr>
                                                                <div class="mb-3">
                                                                    <label class="form-label">Raison du rejet</label>
                                                                    <textarea name="notes" class="form-control" rows="3" required placeholder="Expliquez la raison du rejet..."></textarea>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                                                <button type="submit" class="btn btn-danger">Rejeter</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        @elseif($withdrawal->status == 'completed')
                                            <span class="text-success"><i class="ri-check-double-line"></i></span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="9" class="text-center py-4 text-muted">
                                        <i class="ri-bank-line fs-1"></i>
                                        <p class="mt-2">Aucun retrait trouvé</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if($withdrawals->hasPages())
                <div class="card-footer">
                    {{ $withdrawals->appends(request()->query())->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

@endsection
