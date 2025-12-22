@extends('laboratoire.layout')

@section('page_content')
<style>
    .avatar-md {
        width: 48px;
        height: 48px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }
</style>
<!-- Start Content-->
<div class="container-fluid">

    <!-- Page Title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="#">{{ $laboratoire->name }}</a></li>
                        <li class="breadcrumb-item"><a href="{{route('laboratoire.dashboard')}}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{route('laboratoire.commandes')}}">Commandes</a></li>
                        <li class="breadcrumb-item active">{{ $commande->code }}</li>
                    </ol>
                </div>
                <h4 class="page-title">
                    <a href="{{route('laboratoire.commandes')}}" class="btn btn-sm btn-secondary me-2">
                        <i class="ri-arrow-left-line"></i>
                    </a>
                    Détails Commande #{{ $commande->code }}
                </h4>
            </div>
        </div>
    </div>
    <!-- End Page Title -->

    <!-- Alerts -->
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="ri-checkbox-circle-line me-2"></i>
            <strong>{{ session('success') }}</strong>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="ri-error-warning-line me-2"></i>
            <strong>{{ session('error') }}</strong>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <!-- Informations Principales -->
        <div class="col-lg-8">
            <!-- Carte Détails Commande -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header text-white" style="background:#667eea;">
                    <h5 class="mb-0">
                        <i class="ri-file-list-3-line me-2"></i>
                        Informations de la Commande
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">Code Commande</label>
                            <div class="fw-bold">
                                <span class="badge bg-dark rounded-pill fs-5">{{ $commande->code }}</span>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">Statut Global</label>
                            <div>
                                @php
                                    // Calculer le statut global basé sur les résultats
                                    $total = $sous_commandes->count();
                                    $avec_resultat = $sous_commandes->filter(fn($sc) => $sc->resultat && $sc->resultat->pdf_url)->count();
                                    
                                    if($avec_resultat == 0) {
                                        $statut_global = 'pending';
                                        $statut_class = 'warning';
                                        $statut_icon = 'ri-time-line';
                                        $statut_text = 'En attente';
                                    } elseif($avec_resultat < $total) {
                                        $statut_global = 'En cours';
                                        $statut_class = 'info';
                                        $statut_icon = 'ri-loader-4-line';
                                        $statut_text = "En cours ($avec_resultat/$total)";
                                    } else {
                                        $statut_global = 'Terminer';
                                        $statut_class = 'success';
                                        $statut_icon = 'ri-check-double-line';
                                        $statut_text = 'Terminé';
                                    }
                                @endphp
                                <span class="badge bg-{{$statut_class}} px-3 py-2 fs-5">
                                    <i class="{{$statut_icon}} me-1"></i>
                                    {{ $statut_text }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">Date de Prélèvement</label>
                            <div class="fw-bold">
                                <i class="ri-calendar-line me-1" style="color:#667eea;"></i>
                                {{ $commande->date_prelevement ?? 'Non définie' }}
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">Date de Commande</label>
                            <div class="fw-bold">
                                <i class="ri-calendar-check-line text-muted me-1"></i>
                                {{ $commande->created_at->format('d/m/Y à H:i') }}
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12 mb-3">
                            <label class="text-muted small">Adresse de Prélèvement</label>
                            <div class="fw-bold">
                                <i class="ri-map-pin-line text-danger me-1"></i>
                                {{ $commande->adress }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- NOUVELLE SECTION : Liste des Examens/Bilans de la commande -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header" style="background:#667eea; color:white;">
                    <h5 class="mb-0">
                        <i class="ri-list-check-2 me-2"></i>
                        Analyses Demandées ({{ $sous_commandes->count() }})
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="ps-3" style="width: 50%">Analyse</th>
                                    <th class="text-center">Prix</th>
                                    <th class="text-center">Statut</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($sous_commandes as $sc)
                                <tr class="{{ $sc->resultat && $sc->resultat->pdf_url ? 'table-success' : '' }}">
                                    <td class="ps-3">
                                        <div class="d-flex align-items-center">
                                            @if($sc->examen)
                                                <div class="avatar-sm me-2" style="background:#e8eafc; border-radius:6px; width:40px; height:40px; display:flex; align-items:center; justify-content:center;">
                                                    <i class="ri-test-tube-line" style="color:#667eea; font-size:20px;"></i>
                                                </div>
                                                <div>
                                                    <div class="fw-bold">{{ $sc->examen->label }}</div>
                                                    <small class="text-muted">Examen</small>
                                                </div>
                                            @elseif($sc->type_bilan)
                                                <div class="avatar-sm me-2" style="background:#e8eafc; border-radius:6px; width:40px; height:40px; display:flex; align-items:center; justify-content:center;">
                                                    <i class="ri-file-list-3-line" style="color:#667eea; font-size:20px;"></i>
                                                </div>
                                                <div>
                                                    <div class="fw-bold">{{ $sc->type_bilan->label }}</div>
                                                    <small class="text-muted">Bilan</small>
                                                </div>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="text-center fw-bold text-success">
                                        @if($sc->examen)
                                            {{ number_format($sc->examen->price, 0, ',', ' ') }}
                                        @elseif($sc->type_bilan)
                                            {{ number_format($sc->type_bilan->price, 0, ',', ' ') }}
                                        @endif
                                        FCFA
                                    </td>
                                    <td class="text-center">
                                        @if($sc->resultat && $sc->resultat->pdf_url)
                                            <span class="badge bg-success">
                                                <i class="ri-check-line me-1"></i> Uploadé
                                            </span>
                                        @else
                                            <span class="badge bg-warning">
                                                <i class="ri-time-line me-1"></i> En attente
                                            </span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if($sc->resultat && $sc->resultat->pdf_url)
                                            <!-- Résultat déjà uploadé -->
                                            <div class="btn-group btn-group-sm">
                                                <a href="{{ asset($sc->resultat->pdf_url) }}" 
                                                   target="_blank" 
                                                   class="btn btn-success btn-sm"
                                                   title="Voir le PDF">
                                                    <i class="ri-file-pdf-line"></i>
                                                </a>
                                                <button type="button" 
                                                        class="btn btn-danger btn-sm"
                                                        onclick="if(confirm('Supprimer ce résultat ?')) { window.location.href='{{ route('laboratoire.commande.delete_resultat', $sc->id) }}'; }"
                                                        title="Supprimer">
                                                    <i class="ri-delete-bin-line"></i>
                                                </button>
                                            </div>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer bg-light">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <strong>Total:</strong> 
                            <span class="text-success fs-5">
                                @php
                                    $total_price = $sous_commandes->sum(function($sc) {
                                        return $sc->examen ? $sc->examen->price : ($sc->type_bilan ? $sc->type_bilan->price : 0);
                                    });
                                @endphp
                                {{ number_format($total_price, 0, ',', ' ') }} FCFA
                            </span>
                        </div>
                        <div class="text-muted small">
                            {{ $sous_commandes->filter(fn($sc) => $sc->resultat && $sc->resultat->pdf_url)->count() }} / {{ $sous_commandes->count() }} résultats uploadés
                        </div>
                    </div>
                    
                    @if($commande->agent_id)
                    <!-- Boutons Actions -->
                    <div class="mt-3 d-flex justify-content-end gap-2">
                        @if($sous_commandes->filter(fn($sc) => !$sc->resultat || !$sc->resultat->pdf_url)->count() > 0)
                        <button type="button" 
                                class="btn btn-primary"
                                data-bs-toggle="modal" 
                                data-bs-target="#uploadGlobalModal">
                            <i class="ri-upload-2-line me-2"></i>
                            Uploader des Résultats
                        </button>
                        @endif
                        
                        @if($sous_commandes->filter(fn($sc) => $sc->resultat && $sc->resultat->pdf_url)->count() > 0 && $commande->statut != 'Terminer')
                        <button type="button" 
                                class="btn btn-success"
                                data-bs-toggle="modal" 
                                data-bs-target="#terminerCommandeModal">
                            <i class="ri-check-double-line me-2"></i>
                            Terminer la Commande
                        </button>
                        @endif
                    </div>
                    @endif
                </div>
            </div>

            <!-- Carte Client -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        <i class="ri-user-line me-2"></i>
                        Informations Client
                    </h5>
                </div>
                <div class="card-body">
                    @if($commande->client)
                        <div class="d-flex align-items-center mb-3">
                            <div class="avatar-lg me-1">
                                <span class="avatar-title rounded-circle fs-3" style="background:#667eea;">
                                    {{ strtoupper(substr($commande->client->firstname, 0, 1)) }}{{ strtoupper(substr($commande->client->lastname, 0, 1)) }}
                                </span>
                            </div>
                            <div>
                                <h5 class="mb-1">{{ $commande->client->firstname }} {{ $commande->client->lastname }}</h5>
                                <p class="text-muted mb-0">
                                    <i class="ri-phone-line me-1"></i>
                                    {{ $commande->client->phone }}
                                </p>
                                <p class="text-muted mb-0">
                                    <i class="ri-mail-line me-1"></i>
                                    {{ $commande->client->email }}
                                </p>
                            </div>
                        </div>
                    @else
                        <p class="text-muted mb-0">Aucune information client disponible</p>
                    @endif
                </div>
            </div>

            <!-- Carte Agent (si assigné) -->
            @if($commande->agent)
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        <i class="ri-user-settings-line me-2"></i>
                        Agent Assigné
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar-md me-3">
                            <span class="avatar-title rounded-circle bg-success fs-4">
                                {{ strtoupper(substr($commande->agent->firstname, 0, 1)) }}{{ strtoupper(substr($commande->agent->lastname, 0, 1)) }}
                            </span>
                        </div>
                        <div>
                            <h6 class="mb-1">{{ $commande->agent->firstname }} {{ $commande->agent->lastname }}</h6>
                            <p class="text-muted mb-0">
                                <i class="ri-phone-line me-1"></i>
                                {{ $commande->agent->phone }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- Colonne Droite -->
        <div class="col-lg-4">
            <!-- Timeline de Progression -->
            <div class="card shadow-sm border-0">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        <i class="ri-time-line me-2"></i>
                        Progression
                    </h5>
                </div>
                <div class="card-body">
                    <div class="timeline-alt py-0">
                        <!-- Commande créée -->
                        <div class="timeline-item">
                            <i class="ri-checkbox-circle-fill bg-success timeline-icon"></i>
                            <div class="timeline-item-info">
                                <h5 class="mt-0 mb-1">Commande Créée</h5>
                                <p class="text-muted mb-0">
                                    <small>{{ $commande->created_at->format('d/m/Y à H:i') }}</small>
                                </p>
                            </div>
                        </div>

                        <!-- Agent assigné -->
                        @if($commande->agent_id)
                        <div class="timeline-item">
                            <i class="ri-user-add-fill timeline-icon" style="background:#667eea;"></i>
                            <div class="timeline-item-info">
                                <h5 class="mt-0 mb-1">Agent Assigné</h5>
                                <p class="mb-0">{{ $commande->agent->firstname }}</p>
                                <p class="text-muted mb-0">
                                    <small>{{ $commande->updated_at->format('d/m/Y à H:i') }}</small>
                                </p>
                            </div>
                        </div>
                        @else
                        <div class="timeline-item">
                            <i class="ri-time-line bg-warning timeline-icon"></i>
                            <div class="timeline-item-info">
                                <h5 class="mt-0 mb-1">En Attente d'Assignation</h5>
                                <p class="text-muted mb-0">
                                    <small>Par WALAB</small>
                                </p>
                            </div>
                        </div>
                        @endif

                        <!-- Prélèvement prévu -->
                        <div class="timeline-item">
                            <i class="ri-calendar-check-fill timeline-icon" style="background:#667eea;"></i>
                            <div class="timeline-item-info">
                                <h5 class="mt-0 mb-1">Prélèvement Prévu</h5>
                                <p class="text-muted mb-0">
                                    <small>{{ $commande->date_prelevement ?? 'Date non définie' }}</small>
                                </p>
                            </div>
                        </div>

                        <!-- Résultats -->
                        @php
                            $resultats_count = $sous_commandes->filter(fn($sc) => $sc->resultat && $sc->resultat->pdf_url)->count();
                            $total_count = $sous_commandes->count();
                        @endphp
                        
                        @if($resultats_count > 0)
                        <div class="timeline-item">
                            <i class="ri-file-pdf-fill {{ $resultats_count == $total_count ? 'bg-success' : 'bg-info' }} timeline-icon"></i>
                            <div class="timeline-item-info">
                                <h5 class="mt-0 mb-1">
                                    @if($resultats_count == $total_count)
                                        Tous les Résultats Livrés
                                    @else
                                        Résultats Partiels
                                    @endif
                                </h5>
                                <p class="text-muted mb-0">
                                    <small>{{ $resultats_count }} / {{ $total_count }} analyses</small>
                                </p>
                            </div>
                        </div>
                        @else
                        <div class="timeline-item">
                            <i class="ri-file-upload-line bg-secondary timeline-icon"></i>
                            <div class="timeline-item-info">
                                <h5 class="mt-0 mb-1">En Attente de Résultats</h5>
                                <p class="text-muted mb-0">
                                    <small>{{ $total_count }} analyses à traiter</small>
                                </p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Upload Global (pour plusieurs analyses) -->
<div class="modal fade" id="uploadGlobalModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="background:#667eea; color:white;">
                <h5 class="modal-title">
                    <i class="ri-upload-cloud-2-line me-2"></i>
                    Uploader des Résultats
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('laboratoire.commande.upload_batch') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="code_commande" value="{{ $commande->code }}">
                
                <div class="modal-body">
                    <!-- Sélection des analyses concernées -->
                    <div class="mb-4">
                        <label class="form-label fw-bold">
                            <i class="ri-checkbox-multiple-line me-1"></i>
                            Sélectionnez les analyses concernées par ce fichier :
                        </label>
                        <div class="alert alert-info small mb-3">
                            <i class="ri-information-line me-1"></i>
                            Cochez toutes les analyses dont les résultats sont présents dans le fichier PDF que vous allez uploader.
                        </div>
                        
                        <div class="border rounded p-3" style="max-height: 300px; overflow-y: auto;">
                            @foreach($sous_commandes as $sc)
                                @if(!$sc->resultat || !$sc->resultat->pdf_url)
                                <div class="form-check mb-2 p-2 border-bottom">
                                    <input class="form-check-input analyse-checkbox" 
                                           type="checkbox" 
                                           name="commande_ids[]" 
                                           value="{{ $sc->id }}"
                                           id="check_{{ $sc->id }}">
                                    <label class="form-check-label d-flex align-items-center" for="check_{{ $sc->id }}">
                                        @if($sc->examen)
                                            <i class="ri-test-tube-line text-primary me-2"></i>
                                            <strong>{{ $sc->examen->label }}</strong>
                                        @elseif($sc->type_bilan)
                                            <i class="ri-file-list-3-line text-success me-2"></i>
                                            <strong>{{ $sc->type_bilan->label }}</strong>
                                        @endif
                                    </label>
                                </div>
                                @endif
                            @endforeach
                        </div>
                        
                        <div class="mt-2 text-muted small" id="selectionCount">
                            <i class="ri-checkbox-circle-line me-1"></i>
                            <span id="countText">Aucune analyse sélectionnée</span>
                        </div>
                    </div>

                    <!-- Upload du fichier -->
                    <div class="mb-3">
                        <label for="pdf_file" class="form-label fw-bold">
                            <i class="ri-file-pdf-line me-1"></i>
                            Fichier PDF des résultats *
                        </label>
                        <input type="file" 
                               class="form-control" 
                               id="pdf_file" 
                               name="pdf_url" 
                               accept=".pdf"
                               required>
                        <small class="text-muted">Format PDF uniquement</small>
                    </div>

                    <!-- Information cryptage automatique -->
                    <div class="alert alert-info d-flex align-items-center">
                        <i class="ri-shield-check-line me-2 fs-4"></i>
                        <div>
                            <strong>Cryptage Automatique Activé</strong><br>
                            <small>Le PDF sera automatiquement crypté avec un code unique à 8 caractères. Le patient recevra le code par email et vous pourrez le consulter dans les détails de la commande.</small>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="ri-close-line me-1"></i> Annuler
                    </button>
                    <button type="submit" class="btn btn-primary" id="submitBtn" disabled>
                        <i class="ri-upload-2-line me-1"></i> Uploader les Résultats
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const checkboxes = document.querySelectorAll('.analyse-checkbox');
    const countText = document.getElementById('countText');
    const submitBtn = document.getElementById('submitBtn');

    function updateSelection() {
        const checked = document.querySelectorAll('.analyse-checkbox:checked').length;
        
        if (checked === 0) {
            countText.textContent = 'Aucune analyse sélectionnée';
            submitBtn.disabled = true;
        } else if (checked === 1) {
            countText.textContent = '1 analyse sélectionnée';
            submitBtn.disabled = false;
        } else {
            countText.textContent = checked + ' analyses sélectionnées';
            submitBtn.disabled = false;
        }
    }

    checkboxes.forEach(cb => {
        cb.addEventListener('change', updateSelection);
    });
});
</script>

<!-- Modal Terminer la Commande -->
<div class="modal fade" id="terminerCommandeModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title">
                    <i class="ri-check-double-line me-2"></i>
                    Terminer la Commande
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('laboratoire.commande.terminer', $commande->code) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="alert alert-warning">
                        <i class="ri-information-line me-2"></i>
                        <strong>Attention :</strong> Cette action marquera la commande comme <strong>Terminée</strong>.
                    </div>
                    
                    <p class="mb-3">
                        Résultats uploadés : <strong>{{ $sous_commandes->filter(fn($sc) => $sc->resultat && $sc->resultat->pdf_url)->count() }} / {{ $sous_commandes->count() }}</strong>
                    </p>
                    
                    @if($sous_commandes->filter(fn($sc) => !$sc->resultat || !$sc->resultat->pdf_url)->count() > 0)
                    <div class="alert alert-info small">
                        <i class="ri-alert-line me-1"></i>
                        Il reste <strong>{{ $sous_commandes->filter(fn($sc) => !$sc->resultat || !$sc->resultat->pdf_url)->count() }}</strong> analyse(s) sans résultat. Vous pouvez quand même terminer la commande si nécessaire.
                    </div>
                    @endif
                    
                    <p class="text-muted small mb-0">
                        Une fois terminée, le client sera notifié et pourra télécharger les résultats disponibles.
                    </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="ri-close-line me-1"></i> Annuler
                    </button>
                    <button type="submit" class="btn btn-success">
                        <i class="ri-check-line me-1"></i> Confirmer et Terminer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
