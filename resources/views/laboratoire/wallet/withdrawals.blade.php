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
                        <li class="breadcrumb-item active">Mes Retraits</li>
                    </ol>
                </div>
                <h4 class="page-title">Historique des Retraits</h4>
            </div>
        </div>
    </div>

    <!-- Info Alert -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="alert alert-info alert-dismissible fade show" role="alert">
                <div class="d-flex align-items-center">
                    <i class="ri-information-line fs-4 me-2"></i>
                    <div>
                        <strong>Retraits mensuels automatiques</strong>
                        <p class="mb-0 small">À la fin de chaque mois, un retrait est automatiquement généré pour le montant disponible sur votre portefeuille. L'équipe Walab traite ensuite le virement vers votre compte bancaire.</p>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    </div>

    <!-- Stats -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h6 class="text-white-50">Solde Actuel</h6>
                    <h3 class="mb-0">{{ number_format($wallet->balance, 0, ',', ' ') }} FCFA</h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h6 class="text-white-50">Total Retiré</h6>
                    <h3 class="mb-0">{{ number_format($wallet->withdrawals()->where('status', 'completed')->sum('montant'), 0, ',', ' ') }} FCFA</h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <h6 class="text-white-50">En Attente</h6>
                    <h3 class="mb-0">{{ number_format($wallet->withdrawals()->where('status', 'pending')->sum('montant'), 0, ',', ' ') }} FCFA</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Withdrawals Table -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="ri-bank-line me-2"></i>Mes Retraits</h5>
                    <a href="{{ route('laboratoire.wallet') }}" class="btn btn-sm btn-outline-primary">
                        <i class="ri-arrow-left-line"></i> Retour au portefeuille
                    </a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th>#</th>
                                    <th>Période</th>
                                    <th class="text-end">Montant</th>
                                    <th class="text-center">Statut</th>
                                    <th>Demandé le</th>
                                    <th>Traité le</th>
                                    <th>Notes</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($withdrawals as $withdrawal)
                                <tr>
                                    <td>#{{ $withdrawal->id }}</td>
                                    <td>
                                        <span class="badge bg-light text-dark">{{ $withdrawal->periode }}</span>
                                    </td>
                                    <td class="text-end">
                                        <strong>{{ number_format($withdrawal->montant, 0, ',', ' ') }} FCFA</strong>
                                    </td>
                                    <td class="text-center">
                                        @switch($withdrawal->status)
                                            @case('pending')
                                                <span class="badge bg-warning">
                                                    <i class="ri-time-line"></i> En attente
                                                </span>
                                                @break
                                            @case('processing')
                                                <span class="badge bg-info">
                                                    <i class="ri-loader-4-line"></i> En cours
                                                </span>
                                                @break
                                            @case('completed')
                                                <span class="badge bg-success">
                                                    <i class="ri-check-line"></i> Complété
                                                </span>
                                                @break
                                            @case('rejected')
                                                <span class="badge bg-danger">
                                                    <i class="ri-close-line"></i> Rejeté
                                                </span>
                                                @break
                                            @case('cancelled')
                                                <span class="badge bg-secondary">
                                                    <i class="ri-forbid-line"></i> Annulé
                                                </span>
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
                                            <span class="text-muted" title="{{ $withdrawal->notes }}">
                                                {{ Str::limit($withdrawal->notes, 30) }}
                                            </span>
                                        @else
                                            <small class="text-muted">-</small>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4 text-muted">
                                        <i class="ri-bank-line fs-1"></i>
                                        <p class="mt-2 mb-0">Aucun retrait pour le moment</p>
                                        <small>Les retraits seront générés automatiquement à la fin de chaque mois</small>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if($withdrawals->hasPages())
                <div class="card-footer">
                    {{ $withdrawals->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

@endsection
