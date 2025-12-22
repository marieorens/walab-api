@extends('laboratoire.layout')

@section('page_content')
<div class="container-fluid">

    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Walab</a></li>
                        <li class="breadcrumb-item active">Tableau de bord</li>
                    </ol>
                </div>
                <h4 class="page-title">
                    <i class="ri-dashboard-3-line me-2"></i>
                    Bienvenue, {{ $laboratoire->name }}
                </h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xxl-3 col-sm-6">
            <div class="card widget-flat text-white" style="background:#667eea;">
                <div class="card-body">
                    <div class="float-end">
                        <i class="ri-test-tube-line widget-icon"></i>
                    </div>
                    <h6 class="text-uppercase mt-0" title="Examens">Examens</h6>
                    <h2 class="my-2">{{ $stats['total_examens'] }}</h2>
                    <p class="mb-0">
                        <span class="badge badge-light-lighten me-1">
                            <i class="ri-arrow-right-line"></i> Total
                        </span>
                    </p>
                </div>
            </div>
        </div>

        <div class="col-xxl-3 col-sm-6">
            <div class="card widget-flat text-bg-success">
                <div class="card-body">
                    <div class="float-end">
                        <i class="ri-file-list-3-line widget-icon"></i>
                    </div>
                    <h6 class="text-uppercase mt-0" title="Bilans">Bilans</h6>
                    <h2 class="my-2">{{ $stats['total_bilans'] }}</h2>
                    <p class="mb-0">
                        <span class="badge badge-light-lighten me-1">
                            <i class="ri-arrow-right-line"></i> Total
                        </span>
                    </p>
                </div>
            </div>
        </div>

        <div class="col-xxl-3 col-sm-6">
            <div class="card widget-flat text-bg-warning">
                <div class="card-body">
                    <div class="float-end">
                        <i class="ri-file-text-line widget-icon"></i>
                    </div>
                    <h6 class="text-uppercase mt-0" title="Résultats">Résultats</h6>
                    <h2 class="my-2">{{ $stats['total_resultats'] }}</h2>
                    <p class="mb-0">
                        <span class="badge badge-light-lighten me-1">
                            <i class="ri-arrow-right-line"></i> Publiés
                        </span>
                    </p>
                </div>
            </div>
        </div>

        <div class="col-xxl-3 col-sm-6">
            <div class="card widget-flat text-bg-danger">
                <div class="card-body">
                    <div class="float-end">
                        <i class="ri-time-line widget-icon"></i>
                    </div>
                    <h6 class="text-uppercase mt-0" title="En attente">En attente</h6>
                    <h2 class="my-2">{{ $stats['commandes_en_attente'] }}</h2>
                    <p class="mb-0">
                        <span class="badge badge-light-lighten me-1">
                            <i class="ri-arrow-right-line"></i> Commandes
                        </span>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="header-title mb-0">
                        <i class="ri-building-line me-2"></i>
                        Informations du laboratoire
                    </h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-borderless mb-0">
                            <tbody>
                                <tr>
                                    <th scope="row" style="width: 40%;">Nom :</th>
                                    <td>{{ $laboratoire->name }}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Adresse :</th>
                                    <td>{{ $laboratoire->address }}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Description :</th>
                                    <td>{{ $laboratoire->description }}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Statut :</th>
                                    <td>
                                        <span class="badge bg-success">
                                            <i class="ri-checkbox-circle-line"></i> Actif
                                        </span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="header-title mb-0">
                        <i class="ri-shopping-cart-line me-2"></i>
                        Commandes récentes
                    </h4>
                </div>
                <div class="card-body">
                    @if($recent_commandes->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover table-nowrap mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Code</th>
                                        <th>Date</th>
                                        <th>Statut</th>
                                        <th class="text-end">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recent_commandes as $commande)
                                    <tr>
                                        <td>
                                            <span class="fw-semibold">#{{ $commande->code }}</span>
                                        </td>
                                        <td>{{ $commande->created_at->format('d/m/Y H:i') }}</td>
                                        <td>
                                            @if($commande->statut == 'pending')
                                                <span class="badge bg-warning">En attente</span>
                                            @elseif($commande->statut == 'progress')
                                                <span class="badge text-white" style="background:#667eea;">En cours</span>
                                            @else
                                                <span class="badge bg-success">Terminé</span>
                                            @endif
                                        </td>
                                        <td class="text-end">
                                            <a href="#" class="btn btn-sm text-white" style="background:#667eea;">
                                                <i class="ri-eye-line"></i> Voir
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="ri-inbox-line" style="font-size: 48px; color: #ccc;"></i>
                            <p class="text-muted mt-2">Aucune commande récente</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
