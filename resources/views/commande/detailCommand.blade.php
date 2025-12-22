@extends('layout')

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

<div class="container-fluid">
    <!-- Page Title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('commandes.index') }}">Commandes</a></li>
                        <li class="breadcrumb-item active">{{ $commande->code }}</li>
                    </ol>
                </div>
                <h4 class="page-title">
                    <a href="{{ route('commandes.index') }}" class="btn btn-sm btn-secondary me-2">
                        <i class="bi bi-arrow-left"></i>
                    </a>
                    Détails Commande #{{ $commande->code }}
                </h4>
            </div>
        </div>
    </div>

    <!-- Alerts -->
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-2"></i>
            <strong>{{ session('success') }}</strong>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle me-2"></i>
            <strong>{{ session('error') }}</strong>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-lg-8">
            <!-- Analyses Demandées -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-list-check me-2"></i>
                        Analyses Demandées ({{ $sous_commandes->count() }})
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="bg-light">
                                <tr>
                                    @if($commande->statut != 'Terminer')
                                    <th class="text-center" style="width: 50px">
                                        <input type="checkbox" id="selectAll" class="form-check-input">
                                    </th>
                                    @endif
                                    <th class="ps-3">Analyse</th>
                                    <th class="text-center">Prix</th>
                                    <th class="text-center">Statut</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($sous_commandes as $sc)
                                <tr class="{{ $sc->resultat && $sc->resultat->pdf_url ? 'table-success' : '' }}">
                                    @if($commande->statut != 'Terminer')
                                    <td class="text-center">
                                        @if(!$sc->resultat || !$sc->resultat->pdf_url)
                                        <input type="checkbox" 
                                               class="form-check-input analyse-checkbox-inline" 
                                               value="{{ $sc->id }}">
                                        @else
                                        <i class="bi bi-check-circle text-success"></i>
                                        @endif
                                    </td>
                                    @endif
                                    <td class="ps-3">
                                        <div class="d-flex align-items-center">
                                            @if($sc->examen)
                                                <div class="avatar-sm me-2 bg-light rounded d-flex align-items-center justify-content-center">
                                                    <i class="bi bi-clipboard-pulse text-primary" style="font-size:20px;"></i>
                                                </div>
                                                <div>
                                                    <div class="fw-bold">{{ $sc->examen->label }}</div>
                                                    <small class="text-muted">Examen</small>
                                                    @if($sc->examen->laboratorie)
                                                    <br>
                                                    <small class="badge bg-info text-white">
                                                        <i class="bi bi-building me-1"></i>{{ $sc->examen->laboratorie->name }}
                                                    </small>
                                                    @endif
                                                </div>
                                            @elseif($sc->type_bilan)
                                                <div class="avatar-sm me-2 bg-light rounded d-flex align-items-center justify-content-center">
                                                    <i class="bi bi-file-medical text-success" style="font-size:20px;"></i>
                                                </div>
                                                <div>
                                                    <div class="fw-bold">{{ $sc->type_bilan->label }}</div>
                                                    <small class="text-muted">Bilan</small>
                                                    @if($sc->type_bilan->laboratorie)
                                                    <br>
                                                    <small class="badge bg-info text-white">
                                                        <i class="bi bi-building me-1"></i>{{ $sc->type_bilan->laboratorie->name }}
                                                    </small>
                                                    @endif
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
                                                <i class="bi bi-check-lg me-1"></i> Uploadé
                                            </span>
                                        @else
                                            <span class="badge bg-warning">
                                                <i class="bi bi-clock me-1"></i> En attente
                                            </span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if($sc->resultat && $sc->resultat->pdf_url)
                                            <div class="d-flex flex-column align-items-center gap-2">
                                                <div class="btn-group btn-group-sm">
                                                    <a href="{{ asset($sc->resultat->pdf_url) }}" 
                                                       target="_blank" 
                                                       class="btn btn-success btn-sm"
                                                       title="Télécharger le PDF (protégé par mot de passe)">
                                                        <i class="bi bi-file-pdf"></i>
                                                    </a>
                                                    <button type="button" 
                                                            class="btn btn-danger btn-sm"
                                                            onclick="if(confirm('Supprimer ce résultat ?')) { window.location.href='{{ route('commande.admin_delete_resultat', $sc->id) }}'; }">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </div>
                                                @if($sc->resultat->pdf_password)
                                                    <div class="mt-2">
                                                        <span class="badge bg-primary">
                                                            <i class="bi bi-lock-fill me-1"></i>
                                                            Code PDF: <strong>{{ $sc->resultat->pdf_password }}</strong>
                                                        </span>
                                                        <button class="btn btn-sm btn-outline-secondary ms-2" 
                                                                onclick="navigator.clipboard.writeText('{{ $sc->resultat->pdf_password }}'); alert('Code copié !');"
                                                                title="Copier le code">
                                                            <i class="bi bi-clipboard"></i>
                                                        </button>
                                                    </div>
                                                @endif
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
                    
                    @if($commande->statut != 'Terminer')
                    <div class="mt-3 d-flex justify-content-end gap-2">
                        @if($sous_commandes->filter(fn($sc) => !$sc->resultat || !$sc->resultat->pdf_url)->count() > 0)
                        <button type="button" 
                                class="btn btn-primary"
                                data-bs-toggle="modal" 
                                data-bs-target="#uploadGlobalModal">
                            <i class="bi bi-upload me-2"></i>
                            Uploader des Résultats
                        </button>
                        @endif
                        
                        @if($sous_commandes->filter(fn($sc) => $sc->resultat && $sc->resultat->pdf_url)->count() > 0)
                        <button type="button" 
                                class="btn btn-success"
                                data-bs-toggle="modal" 
                                data-bs-target="#terminerCommandeModal">
                            <i class="bi bi-check-circle me-2"></i>
                            Terminer la Commande
                        </button>
                        @endif
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Informations Commande -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        <i class="bi bi-info-circle me-2"></i>
                        Informations Commande
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <small class="text-muted">Code Commande</small>
                        <h5 class="mb-0">{{ $commande->code }}</h5>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted">Statut</small>
                        <div>
                            @php
                                $statut_class = match($commande->statut) {
                                    'pending' => 'warning',
                                    'En cours' => 'info',
                                    'Terminer' => 'success',
                                    'Annuler' => 'danger',
                                    default => 'secondary'
                                };
                            @endphp
                            <span class="badge bg-{{$statut_class}}">{{ $commande->statut }}</span>
                        </div>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted">Date Prélèvement</small>
                        <h6 class="mb-0">{{ $commande->date_prelevement ?? 'Non définie' }}</h6>
                    </div>
                    <div class="mb-0">
                        <small class="text-muted">Date Création</small>
                        <h6 class="mb-0">{{ $commande->created_at->format('d/m/Y H:i') }}</h6>
                    </div>
                </div>
            </div>

            <!-- Client -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        <i class="bi bi-person me-2"></i>
                        Client
                    </h5>
                </div>
                <div class="card-body">
                    @if($commande->client)
                        <div class="d-flex align-items-center mb-3">
                            <div class="avatar-lg me-3">
                                <span class="avatar-title rounded-circle bg-primary fs-3">
                                    {{ strtoupper(substr($commande->client->firstname, 0, 1)) }}{{ strtoupper(substr($commande->client->lastname, 0, 1)) }}
                                </span>
                            </div>
                            <div>
                                <h5 class="mb-1">{{ $commande->client->firstname }} {{ $commande->client->lastname }}</h5>
                                <p class="text-muted mb-0">
                                    <i class="bi bi-telephone me-1"></i>
                                    {{ $commande->client->phone }}
                                </p>
                                <p class="text-muted mb-0">
                                    <i class="bi bi-envelope me-1"></i>
                                    {{ $commande->client->email }}
                                </p>
                            </div>
                        </div>
                    @else
                        <p class="text-muted mb-0">Aucune information client</p>
                    @endif
                </div>
            </div>

            <!-- Agent -->
            @if($commande->agent)
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        <i class="bi bi-person-badge me-2"></i>
                        Agent Assigné
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar-md me-3">
                            <span class="avatar-title rounded-circle bg-success fs-4">
                                {{ strtoupper(substr($commande->agent->firstname, 0, 1)) }}
                            </span>
                        </div>
                        <div>
                            <h6 class="mb-0">{{ $commande->agent->firstname }} {{ $commande->agent->lastname }}</h6>
                            <p class="text-muted mb-0">{{ $commande->agent->phone }}</p>
                        </div>
                    </div>
                </div>
            </div>

            @if($commande->qr_code_base64 && $commande->agent_id == $user_auth->id)
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        <i class="bi bi-qr-code me-2"></i>
                        QR Code de Vérification
                    </h5>
                </div>
                <div class="card-body text-center">
                    <div class="mb-3">
                        <img src="{{ $commande->qr_code_base64 }}" 
                             alt="QR Code" 
                             class="img-fluid rounded" 
                             style="max-width: 300px; border: 2px solid #dee2e6; padding: 10px; background: white;">
                    </div>
                    
                    <div class="alert alert-info mb-3">
                        <i class="bi bi-info-circle me-2"></i>
                        <strong>Instructions:</strong> Le client doit scanner ce QR code pour vérifier votre identité.
                    </div>

                    @if($commande->is_verified)
                        <div class="alert alert-success mb-0">
                            <i class="bi bi-check-circle-fill me-2"></i>
                            <strong>Vérifié</strong> - Le client a confirmé votre identité le {{ \Carbon\Carbon::parse($commande->verified_at)->format('d/m/Y à H:i') }}
                        </div>
                    @else
                        <div class="alert alert-warning mb-0">
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            <strong>En attente</strong> - Le client n'a pas encore scanné le QR code
                        </div>
                    @endif

                    @if($commande->token_expires_at)
                        <p class="text-muted small mt-2 mb-0">
                            <i class="bi bi-clock me-1"></i>
                            Expire le {{ \Carbon\Carbon::parse($commande->token_expires_at)->format('d/m/Y à H:i') }}
                        </p>
                    @endif
                </div>
            </div>
            @endif
            @endif
        </div>
    </div>
</div>

<!-- Modal Upload Global -->
<div class="modal fade" id="uploadGlobalModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="bi bi-upload me-2"></i>
                    Uploader des Résultats
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('commande.admin_upload_batch') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="code_commande" value="{{ $commande->code }}">
                
                <div class="modal-body">
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle me-1"></i>
                        <span id="selectionInfo">Sélectionnez les analyses dans le tableau ci-dessus</span>
                    </div>
                    
                    <input type="hidden" name="commande_ids" id="selectedIds" value="">

                    <div class="mb-3">
                        <label for="pdf_file" class="form-label fw-bold">
                            <i class="bi bi-file-pdf me-1"></i>
                            Fichier PDF *
                        </label>
                        <input type="file" 
                               class="form-control" 
                               name="pdf_url" 
                               accept=".pdf"
                               required>
                    </div>

                    <div class="alert alert-info d-flex align-items-center mb-3">
                        <i class="bi bi-shield-check me-2 fs-4"></i>
                        <div>
                            <strong>🔒 Cryptage Automatique</strong><br>
                            <small>Le PDF sera automatiquement crypté. Le code sera envoyé au patient par email et visible dans les détails de la commande.</small>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary" id="submitUpload" disabled>
                        <i class="bi bi-upload me-1"></i> Uploader
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Terminer -->
<div class="modal fade" id="terminerCommandeModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title">
                    <i class="bi bi-check-circle me-2"></i>
                    Terminer la Commande
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('commande.admin_terminer', $commande->code) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="alert alert-warning">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        Cette action marquera la commande comme <strong>Terminée</strong>.
                    </div>
                    
                    <p>Résultats: <strong>{{ $sous_commandes->filter(fn($sc) => $sc->resultat && $sc->resultat->pdf_url)->count() }} / {{ $sous_commandes->count() }}</strong></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-success">Confirmer</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const checkboxes = document.querySelectorAll('.analyse-checkbox-inline');
    const selectAll = document.getElementById('selectAll');
    const submitBtn = document.getElementById('submitUpload');
    const selectedIdsInput = document.getElementById('selectedIds');
    const selectionInfo = document.getElementById('selectionInfo');

    function updateSelection() {
        const checked = Array.from(checkboxes).filter(cb => cb.checked);
        const count = checked.length;
        
        if (count > 0) {
            selectionInfo.textContent = `${count} analyse(s) sélectionnée(s)`;
            submitBtn.disabled = false;
            selectedIdsInput.value = checked.map(cb => cb.value).join(',');
        } else {
            selectionInfo.textContent = 'Sélectionnez les analyses dans le tableau ci-dessus';
            submitBtn.disabled = true;
            selectedIdsInput.value = '';
        }
    }

    if (selectAll) {
        selectAll.addEventListener('change', function() {
            checkboxes.forEach(cb => cb.checked = this.checked);
            updateSelection();
        });
    }

    checkboxes.forEach(cb => {
        cb.addEventListener('change', updateSelection);
    });
});
</script>

@endsection
