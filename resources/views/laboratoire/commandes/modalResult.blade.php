<!-- Modal Upload Résultat -->
<div class="modal fade" id="uploadModal{{ $commande->id }}" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header text-white" style="background:#667eea;">
                <h5 class="modal-title">
                    <i class="ri-upload-2-line me-2"></i>
                    @if($commande->resultat && $commande->resultat->pdf_url)
                        Modifier Résultat - {{ $commande->code }}
                    @else
                        Uploader Résultat - {{ $commande->code }}
                    @endif
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('laboratoire.commande.upload_resultat', $commande->id) }}" 
                  method="POST" 
                  enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    @if($commande->resultat && $commande->resultat->pdf_url)
                        <div class="alert alert-warning">
                            <i class="ri-information-line me-2"></i>
                            <strong>Mode modification :</strong>
                            <p class="mb-0 mt-2">Vous allez remplacer le résultat existant. L'ancien PDF sera supprimé.</p>
                        </div>
                        
                        <div class="alert alert-info mb-3">
                            <strong>PDF actuel :</strong>
                            <a href="{{ asset($commande->resultat->pdf_url) }}" target="_blank" class="text-decoration-underline">
                                <i class="ri-file-pdf-line me-1"></i>
                                Voir le PDF actuel
                            </a>
                            @if($commande->resultat->pdf_password)
                                <br>
                                <strong class="mt-2 d-block">Code de cryptage :</strong> 
                                <span class="badge bg-success">
                                    <i class="ri-lock-fill me-1"></i>
                                    {{ $commande->resultat->pdf_password }}
                                </span>
                                <button class="btn btn-sm btn-outline-secondary ms-2" 
                                        onclick="navigator.clipboard.writeText('{{ $commande->resultat->pdf_password }}'); alert('Code copié dans le presse-papier !');"
                                        title="Copier le code">
                                    <i class="ri-file-copy-line"></i> Copier
                                </button>
                            @endif
                        </div>
                    @else
                        <div class="alert alert-info">
                            <i class="ri-information-line me-2"></i>
                            <strong>Informations:</strong>
                            <ul class="mb-0 mt-2">
                                <li>Format accepté: PDF uniquement</li>
                                <li>Taille maximale: 10 MB</li>
                                <li>Le statut passera automatiquement à "Terminé"</li>
                            </ul>
                        </div>
                    @endif

                    <div class="mb-3">
                        <label for="pdf_url{{ $commande->id }}" class="form-label">
                            <i class="ri-file-pdf-line me-1"></i>
                            @if($commande->resultat && $commande->resultat->pdf_url)
                                Nouveau Fichier PDF <span class="text-danger">*</span>
                            @else
                                Fichier PDF <span class="text-danger">*</span>
                            @endif
                        </label>
                        <input type="file" 
                               class="form-control" 
                               id="pdf_url{{ $commande->id }}" 
                               name="pdf_url" 
                               accept=".pdf" 
                               required>
                    </div>

                    <div class="alert alert-info d-flex align-items-center mb-3">
                        <i class="ri-shield-check-line me-2 fs-4"></i>
                        <div>
                            <strong>Cryptage Automatique</strong><br>
                            <small>Le PDF sera automatiquement crypté avec un code unique à 8 caractères. Le code sera envoyé par email au patient et visible dans les détails de la commande.</small>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="ri-close-line me-1"></i>
                        Annuler
                    </button>
                    <button type="submit" class="btn text-white" style="background:#667eea;">
                        <i class="ri-upload-2-line me-1"></i>
                        @if($commande->resultat && $commande->resultat->pdf_url)
                            Modifier
                        @else
                            Uploader
                        @endif
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
