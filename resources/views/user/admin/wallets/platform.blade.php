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
                        <li class="breadcrumb-item active">Plateforme</li>
                    </ol>
                </div>
                <h4 class="page-title">Portefeuille Plateforme (Commissions)</h4>
            </div>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(!$wallet)
        <div class="alert alert-warning">
            <i class="ri-alert-line me-2"></i>
            Le portefeuille plateforme n'existe pas encore. Il sera créé automatiquement lors du premier paiement traité.
        </div>
    @else
        <div class="row">
            <!-- Platform Wallet Card -->
            <div class="col-md-4">
                <div class="card border-primary">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">
                            <i class="ri-wallet-3-line me-2"></i>
                            Portefeuille Plateforme
                        </h5>
                    </div>
                    <div class="card-body text-center">
                        <h1 class="display-5 {{ $wallet->balance >= 0 ? 'text-success' : 'text-danger' }}">
                            {{ number_format($wallet->balance, 0, ',', ' ') }}
                            <small class="fs-5">FCFA</small>
                        </h1>
                        <p class="text-muted">Solde des commissions</p>
                        
                        <hr>
                        
                        <div class="row text-start">
                            <div class="col-6">
                                <p class="mb-1 text-muted small">Statut</p>
                                @switch($wallet->status)
                                    @case('active')
                                        <span class="badge bg-success">Actif</span>
                                        @break
                                    @case('suspended')
                                        <span class="badge bg-warning">Suspendu</span>
                                        @break
                                @endswitch
                            </div>
                            <div class="col-6">
                                <p class="mb-1 text-muted small">Créé le</p>
                                <span>{{ $wallet->created_at->format('d/m/Y') }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Period Selector -->
                <div class="card">
                    <div class="card-body">
                        <form method="GET" action="{{ route('wallets.platform') }}">
                            <label class="form-label">Période d'analyse</label>
                            <div class="input-group">
                                <input type="month" name="periode" class="form-control" value="{{ $periode }}">
                                <button type="submit" class="btn btn-primary">
                                    <i class="ri-calendar-line"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Stats -->
            <div class="col-md-8">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <div class="card bg-success text-white h-100">
                            <div class="card-body">
                                <h6 class="text-white-50">Commissions Reçues ({{ $periode }})</h6>
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
                                <h6 class="text-white-50">Net du Mois</h6>
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
                        <h5 class="mb-0"><i class="ri-history-line me-2"></i>Transactions Récentes</h5>
                        <a href="{{ route('wallets.transactions', $wallet->id) }}" class="btn btn-sm btn-outline-primary">
                            Voir tout
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
                                            <span title="{{ $transaction->description }}">{{ Str::limit($transaction->description, 50) }}</span>
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
    @endif
</div>

@endsection
