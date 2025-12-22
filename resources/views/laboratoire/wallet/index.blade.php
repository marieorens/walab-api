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
                        <li class="breadcrumb-item active">Mon Portefeuille</li>
                    </ol>
                </div>
                <h4 class="page-title">Mon Portefeuille</h4>
            </div>
        </div>
    </div>

    <!-- Wallet Balance Card -->
    <div class="row mb-4">
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <div class="card-body text-white">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <p class="mb-1 text-white-50">Solde disponible</p>
                            <h2 class="mb-0 text-white">{{ number_format($wallet->balance, 0, ',', ' ') }} <small class="fs-5">FCFA</small></h2>
                        </div>
                        <div class="bg-white bg-opacity-25 rounded-circle p-3">
                            <i class="ri-wallet-3-line fs-2"></i>
                        </div>
                    </div>
                    <hr class="border-white-50">
                    <div class="row">
                        <div class="col-6">
                            <p class="mb-0 text-white-50 small">Commission</p>
                            <p class="mb-0 fw-bold">{{ $laboratoire->pourcentage_commission ?? 0 }}%</p>
                        </div>
                        <div class="col-6 text-end">
                            <p class="mb-0 text-white-50 small">Statut</p>
                            <span class="badge {{ $wallet->status == 'active' ? 'bg-success' : 'bg-warning' }}">
                                {{ $wallet->status == 'active' ? 'Actif' : ucfirst($wallet->status) }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Info Card -->
            <div class="card mt-3">
                <div class="card-body">
                    <h6 class="text-muted mb-3"><i class="ri-information-line me-1"></i> Comment ça marche ?</h6>
                    <ul class="list-unstyled mb-0 small">
                        <li class="mb-2">
                            <i class="ri-check-line text-success me-2"></i>
                            À chaque paiement validé, votre part est créditée automatiquement
                        </li>
                        <li class="mb-2">
                            <i class="ri-check-line text-success me-2"></i>
                            Commission plateforme: {{ 100 - ($laboratoire->pourcentage_commission ?? 0) }}%
                        </li>
                        <li class="mb-2">
                            <i class="ri-check-line text-success me-2"></i>
                            Retraits mensuels automatiques vers votre compte bancaire
                        </li>
                        <li>
                            <i class="ri-check-line text-success me-2"></i>
                            Historique complet disponible à tout moment
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <!-- Stats Cards -->
            <div class="row mb-3">
                <div class="col-md-4">
                    <div class="card bg-success-subtle h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <div class="avatar-sm rounded bg-success">
                                        <span class="avatar-title bg-transparent text-success">
                                            <i class="ri-arrow-down-circle-line fs-3"></i>
                                        </span>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <p class="mb-1 text-muted">Revenus ({{ $periode }})</p>
                                    <h4 class="mb-0 text-success">{{ number_format($stats['credits'] ?? 0, 0, ',', ' ') }}</h4>
                                    <small class="text-muted">{{ $stats['credits_count'] ?? 0 }} transactions</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card bg-danger-subtle h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <div class="avatar-sm rounded bg-danger">
                                        <span class="avatar-title bg-transparent text-danger">
                                            <i class="ri-arrow-up-circle-line fs-3"></i>
                                        </span>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <p class="mb-1 text-muted">Retraits ({{ $periode }})</p>
                                    <h4 class="mb-0 text-danger">{{ number_format($stats['debits'] ?? 0, 0, ',', ' ') }}</h4>
                                    <small class="text-muted">{{ $stats['debits_count'] ?? 0 }} retraits</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card bg-info-subtle h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <div class="avatar-sm rounded bg-info">
                                        <span class="avatar-title bg-transparent text-info">
                                            <i class="ri-line-chart-line fs-3"></i>
                                        </span>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <p class="mb-1 text-muted">Net ({{ $periode }})</p>
                                    <h4 class="mb-0 text-info">{{ number_format(($stats['credits'] ?? 0) - ($stats['debits'] ?? 0), 0, ',', ' ') }}</h4>
                                    <small class="text-muted">FCFA</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Period Selector -->
            <div class="card mb-3">
                <div class="card-body py-2">
                    <form method="GET" action="{{ route('laboratoire.wallet') }}" class="row g-2 align-items-center">
                        <div class="col-auto">
                            <label class="col-form-label">Période:</label>
                        </div>
                        <div class="col-auto">
                            <input type="month" name="periode" class="form-control form-control-sm" value="{{ $periode }}">
                        </div>
                        <div class="col-auto">
                            <button type="submit" class="btn btn-sm btn-primary">
                                <i class="ri-filter-line"></i> Filtrer
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Monthly Chart -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="ri-bar-chart-2-line me-2"></i>Évolution des revenus (6 derniers mois)</h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        @foreach($monthlyData as $month => $amount)
                            <div class="col-2">
                                <div class="position-relative">
                                    @php
                                        $maxAmount = max(array_values($monthlyData));
                                        $height = $maxAmount > 0 ? ($amount / $maxAmount * 100) : 0;
                                    @endphp
                                    <div class="bg-primary bg-opacity-25 rounded mx-auto mb-2" 
                                         style="width: 40px; height: 100px; position: relative;">
                                        <div class="bg-primary rounded position-absolute bottom-0 w-100" 
                                             style="height: {{ max($height, 5) }}%;"></div>
                                    </div>
                                    <small class="text-muted d-block">{{ \Carbon\Carbon::parse($month)->format('M') }}</small>
                                    <small class="fw-bold">{{ number_format($amount / 1000, 0) }}k</small>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Transactions -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="ri-history-line me-2"></i>Dernières Transactions</h5>
                    <a href="{{ route('laboratoire.wallet.transactions') }}" class="btn btn-sm btn-outline-primary">
                        Voir tout <i class="ri-arrow-right-line"></i>
                    </a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th>Date</th>
                                    <th>Type</th>
                                    <th>Description</th>
                                    <th class="text-end">Montant</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentTransactions as $transaction)
                                <tr>
                                    <td>
                                        <span>{{ $transaction->created_at->format('d/m/Y') }}</span>
                                        <br>
                                        <small class="text-muted">{{ $transaction->created_at->format('H:i') }}</small>
                                    </td>
                                    <td>
                                        @if($transaction->type == 'credit')
                                            <span class="badge bg-success-subtle text-success">
                                                <i class="ri-arrow-down-line"></i> Crédit
                                            </span>
                                        @else
                                            <span class="badge bg-danger-subtle text-danger">
                                                <i class="ri-arrow-up-line"></i> Débit
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <span title="{{ $transaction->description }}">
                                            {{ Str::limit($transaction->description, 50) }}
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        <strong class="{{ $transaction->type == 'credit' ? 'text-success' : 'text-danger' }}">
                                            {{ $transaction->type == 'credit' ? '+' : '-' }}{{ number_format($transaction->montant, 0, ',', ' ') }} FCFA
                                        </strong>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4 text-muted">
                                        <i class="ri-inbox-line fs-1"></i>
                                        <p class="mt-2 mb-0">Aucune transaction pour le moment</p>
                                        <small>Vos revenus apparaîtront ici après le premier paiement validé</small>
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

@endsection
