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
                        <li class="breadcrumb-item"><a href="{{ route('paiements.index') }}">Paiements</a></li>
                        <li class="breadcrumb-item active">Détails</li>
                    </ol>
                </div>
                <h4 class="page-title">Détails du Paiement #{{ $paiement->id }}</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Paiement Info -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="ri-bank-card-line me-2"></i>Informations du Paiement</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless mb-0">
                        <tr>
                            <td class="text-muted" width="40%">ID Paiement</td>
                            <td><strong>#{{ $paiement->id }}</strong></td>
                        </tr>
                        <tr>
                            <td class="text-muted">Transaction ID</td>
                            <td><code>{{ $paiement->transaction_id ?? '-' }}</code></td>
                        </tr>
                        <tr>
                            <td class="text-muted">Référence</td>
                            <td><code>{{ $paiement->reference ?? '-' }}</code></td>
                        </tr>
                        <tr>
                            <td class="text-muted">Code Commande</td>
                            <td><strong>{{ $paiement->code_commande ?? '-' }}</strong></td>
                        </tr>
                        <tr>
                            <td class="text-muted">Montant</td>
                            <td><strong class="text-success fs-5">{{ number_format($paiement->montant, 0, ',', ' ') }} FCFA</strong></td>
                        </tr>
                        <tr>
                            <td class="text-muted">Mode de paiement</td>
                            <td>
                                @if($paiement->mode)
                                    <span class="badge bg-info">{{ $paiement->mode }}</span>
                                @else
                                    <span class="badge bg-secondary">-</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td class="text-muted">Statut</td>
                            <td>
                                @switch($paiement->status)
                                    @case('approved')
                                        <span class="badge bg-success fs-6"><i class="ri-check-line"></i> Approuvé</span>
                                        @break
                                    @case('pending')
                                        <span class="badge bg-warning fs-6"><i class="ri-time-line"></i> En attente</span>
                                        @break
                                    @case('declined')
                                        <span class="badge bg-danger fs-6"><i class="ri-close-line"></i> Refusé</span>
                                        @break
                                    @case('cancelled')
                                        <span class="badge bg-secondary fs-6"><i class="ri-close-line"></i> Annulé</span>
                                        @break
                                    @default
                                        <span class="badge bg-secondary fs-6">{{ $paiement->status }}</span>
                                @endswitch
                            </td>
                        </tr>
                        <tr>
                            <td class="text-muted">Date de création</td>
                            <td>{{ $paiement->created_at->format('d/m/Y à H:i:s') }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Dernière mise à jour</td>
                            <td>{{ $paiement->updated_at->format('d/m/Y à H:i:s') }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            <!-- Commission Info -->
            @if($paiement->status == 'approved')
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="ri-percent-line me-2"></i>Répartition des Commissions</h5>
                </div>
                <div class="card-body">
                    @if($paiement->commission_processed)
                        <div class="row text-center">
                            <div class="col-6">
                                <div class="border rounded p-3 bg-light">
                                    <p class="text-muted mb-1">Part Laboratoire</p>
                                    <h4 class="text-success mb-0">{{ number_format($paiement->montant_laboratoire ?? 0, 0, ',', ' ') }} FCFA</h4>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="border rounded p-3 bg-light">
                                    <p class="text-muted mb-1">Commission Plateforme</p>
                                    <h4 class="text-primary mb-0">{{ number_format($paiement->montant_plateforme ?? 0, 0, ',', ' ') }} FCFA</h4>
                                </div>
                            </div>
                        </div>
                        <p class="text-center text-muted mt-2 mb-0">
                            <small>Taux appliqué: {{ $paiement->pourcentage_applique ?? 0 }}%</small>
                        </p>
                        <p class="text-center text-success mt-2 mb-0">
                            <i class="ri-check-double-line"></i> Commission traitée
                        </p>
                    @else
                        <div class="alert alert-warning mb-0">
                            <i class="ri-alert-line me-1"></i> Commission non encore traitée
                        </div>
                    @endif
                </div>
            </div>
            @endif
        </div>

        <!-- Commande & Client Info -->
        <div class="col-md-6">
            @if($commande)
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="ri-shopping-cart-line me-2"></i>Commande Associée</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless mb-0">
                        <tr>
                            <td class="text-muted" width="40%">Code</td>
                            <td>
                                <a href="{{ route('commandes.show', $commande->id) }}" class="fw-bold">
                                    {{ $commande->code }}
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-muted">Type</td>
                            <td>{{ $commande->type }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Statut</td>
                            <td>
                                <span class="badge bg-secondary">{{ $commande->statut }}</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-muted">Adresse</td>
                            <td>{{ $commande->adress ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Date prélèvement</td>
                            <td>{{ $commande->date_prelevement ?? '-' }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            <!-- Client -->
            @if($commande->client)
            <div class="card">
                <div class="card-header bg-secondary text-white">
                    <h5 class="mb-0"><i class="ri-user-line me-2"></i>Client</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="avatar-sm me-3">
                            <img src="{{ asset($commande->client->url_profil ?? 'assets/images/user.png') }}" 
                                 class="rounded-circle" width="40" height="40">
                        </div>
                        <div>
                            <h6 class="mb-0">{{ $commande->client->firstname }} {{ $commande->client->lastname }}</h6>
                            <small class="text-muted">{{ $commande->client->email }}</small>
                        </div>
                    </div>
                    <table class="table table-sm table-borderless mb-0">
                        <tr>
                            <td class="text-muted">Téléphone</td>
                            <td>{{ $commande->client->phone ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Ville</td>
                            <td>{{ $commande->client->city ?? '-' }}</td>
                        </tr>
                    </table>
                </div>
            </div>
            @endif

            <!-- Laboratoire -->
            @php
                $labo = $paiement->laboratoire;
                if (!$labo && $commande) {
                    if ($commande->examen && $commande->examen->laboratorie) {
                        $labo = $commande->examen->laboratorie;
                    } elseif ($commande->type_bilan && $commande->type_bilan->laboratorie) {
                        $labo = $commande->type_bilan->laboratorie;
                    }
                }
            @endphp
            @if($labo)
            <div class="card">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0"><i class="ri-hospital-line me-2"></i>Laboratoire</h5>
                </div>
                <div class="card-body">
                    <h6>{{ $labo->nom ?? $labo->name }}</h6>
                    <p class="text-muted mb-1">{{ $labo->address ?? '-' }}</p>
                    @if($labo->pourcentage_commission)
                        <span class="badge bg-info">Commission: {{ $labo->pourcentage_commission }}%</span>
                    @endif
                </div>
            </div>
            @endif
            @else
                <div class="card">
                    <div class="card-body text-center text-muted py-5">
                        <i class="ri-information-line fs-1"></i>
                        <p class="mt-2">Aucune commande associée trouvée</p>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Transactions Wallet liées -->
    @if($paiement->walletTransactions && $paiement->walletTransactions->count() > 0)
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="ri-exchange-funds-line me-2"></i>Transactions Wallet Générées</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Wallet</th>
                                    <th>Type</th>
                                    <th>Description</th>
                                    <th class="text-end">Montant</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($paiement->walletTransactions as $transaction)
                                <tr>
                                    <td>#{{ $transaction->id }}</td>
                                    <td>
                                        @if($transaction->wallet && $transaction->wallet->user)
                                            {{ $transaction->wallet->user->laboratorie->nom ?? $transaction->wallet->type }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge {{ $transaction->type == 'credit' ? 'bg-success' : 'bg-danger' }}">
                                            {{ ucfirst($transaction->type) }}
                                        </span>
                                    </td>
                                    <td>{{ $transaction->description }}</td>
                                    <td class="text-end">
                                        <strong class="{{ $transaction->type == 'credit' ? 'text-success' : 'text-danger' }}">
                                            {{ $transaction->type == 'credit' ? '+' : '-' }}{{ number_format($transaction->montant, 0, ',', ' ') }} FCFA
                                        </strong>
                                    </td>
                                    <td>{{ $transaction->created_at->format('d/m/Y H:i') }}</td>
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

    <!-- Back Button -->
    <div class="row">
        <div class="col-12">
            <a href="{{ route('paiements.index') }}" class="btn btn-secondary">
                <i class="ri-arrow-left-line me-1"></i> Retour à la liste
            </a>
        </div>
    </div>
</div>

@endsection
