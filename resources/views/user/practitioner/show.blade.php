@extends('layout')

@section('page_content')

<style>
    /* Styles personnalisés pour la page profil */
    .profile-header {
        background: linear-gradient(135deg, #EFF6FF 0%, #FFFFFF 100%);
    }
    
    .profile-avatar {
        border: 4px solid white;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    }
    
    .verified-badge {
        position: absolute;
        bottom: -8px;
        right: -8px;
        background: #2563eb;
        border: 4px solid white;
        border-radius: 50%;
        padding: 8px;
    }
    
    .card-shadow {
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    }
    
    .btn-message {
        background: #16a34a;
        color: white;
        transition: background 0.3s;
    }
    
    .btn-message:hover {
        background: #15803d;
        color: white;
    }
    
    .info-icon {
        width: 20px;
        height: 20px;
        flex-shrink: 0;
    }
    
    @media (max-width: 768px) {
        .profile-avatar {
            width: 80px !important;
            height: 80px !important;
        }
    }
</style>

<div class="container-fluid bg-light py-4">
    <div class="container" style="max-width: 1400px;">
        
        <!-- Header avec retour -->
        <div class="mb-4">
            <a href="{{ route('practitioner.index') }}" class="btn btn-link text-decoration-none text-dark d-flex align-items-center gap-2">
                <i class="ri-arrow-left-line"></i>
                <span class="fw-medium">Retour à la liste</span>
            </a>
        </div>

        <div class="row g-4">
            
            <!-- Colonne principale - Profil -->
            <div class="col-lg-8">
                <div class="card border-0 rounded-3 card-shadow">
                    
                    <!-- En-tête profil avec photo -->
                    <div class="profile-header p-4">
                        <div class="d-flex flex-column flex-sm-row align-items-start gap-4">
                            <!-- Photo de profil -->
                            <div class="position-relative flex-shrink-0">
                                <img
                                    src="{{ asset($practitioner->user->url_profil) }}"
                                    alt="{{ $practitioner->user->firstname }} {{ $practitioner->user->lastname }}"
                                    class="rounded-3 profile-avatar"
                                    style="width: 128px; height: 128px; object-fit: cover;"
                                />
                                @if($practitioner->verification_status === 'approved')
                                    <div class="verified-badge">
                                        <i class="ri-check-line text-white" style="font-size: 20px;"></i>
                                    </div>
                                @endif
                            </div>

                            <!-- Informations principales -->
                            <div class="flex-grow-1">
                                <h1 class="h2 fw-bold text-dark mb-2">
                                    {{ $practitioner->user->firstname }} {{ $practitioner->user->lastname }}
                                </h1>
                                <p class="text-muted fs-5 mb-3">
                                    @switch($practitioner->profession)
                                        @case('general_practitioner') Médecin Généraliste @break
                                        @case('specialist_doctor') Médecin Spécialiste @break
                                        @case('midwife') Sage-femme @break
                                        @case('nurse') Infirmier(ère) @break
                                        @case('nursing_assistant') Aide-soignant(e) @break
                                        @case('physiotherapist') Kinésithérapeute @break
                                        @case('psychologist') Psychologue @break
                                        @case('nutritionist') Nutritionniste @break
                                        @default {{ $practitioner->profession }}
                                    @endswitch
                                </p>

                                <!-- Contact rapide -->
                                <div class="mb-3">
                                    <div class="d-flex align-items-center gap-2 mb-2">
                                        <i class="ri-map-pin-line text-muted info-icon"></i>
                                        <span class="text-muted small">{{ $practitioner->user->city ?? 'Non renseignée' }}</span>
                                    </div>
                                    <div class="d-flex align-items-center gap-2 mb-2">
                                        <i class="ri-mail-line text-muted info-icon"></i>
                                        <span class="text-muted small">{{ $practitioner->user->email }}</span>
                                    </div>
                                    <div class="d-flex align-items-center gap-2 mb-2">
                                        <i class="ri-phone-line text-muted info-icon"></i>
                                        <span class="text-muted small">{{ $practitioner->user->phone }}</span>
                                    </div>
                                </div>

                                <!-- Informations admin -->
                                <div class="d-flex flex-wrap gap-2 mb-3">
                                    <span class="badge 
                                        @if($practitioner->verification_status == 'approved') bg-success
                                        @elseif($practitioner->verification_status == 'pending') bg-warning
                                        @else bg-danger
                                        @endif">
                                        @if($practitioner->verification_status == 'approved') Vérifié
                                        @elseif($practitioner->verification_status == 'pending') En attente
                                        @else Rejeté
                                        @endif
                                    </span>
                                    
                                    <span class="badge 
                                        @if($practitioner->user->status == 'active') bg-success
                                        @else bg-secondary
                                        @endif">
                                        Compte: {{ $practitioner->user->status == 'active' ? 'Actif' : 'Suspendu' }}
                                    </span>
                                    
                                    <span class="badge bg-info">
                                        Profil complété: {{ $practitioner->profile_completion }}%
                                    </span>
                                </div>

                                <!-- Bouton Messagerie (visible uniquement pour admin) -->
                                @if(auth()->user() && auth()->user()->role_id == 1)
                                    <a href="http://localhost:5173/user/messagerie?practitioner_id={{ $practitioner->user_id }}" 
                                       target="_blank" 
                                       class="btn btn-message d-inline-flex align-items-center gap-2">
                                        <i class="ri-chat-3-line"></i>
                                        Envoyer un message
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Biographie -->
                    <div class="p-4 border-bottom">
                        <h2 class="h5 fw-bold text-dark mb-3">Biographie</h2>
                        @if($practitioner->bio)
                            <p class="text-muted" style="line-height: 1.7;">{{ $practitioner->bio }}</p>
                        @else
                            <p class="text-center text-muted py-3 fst-italic">Aucune biographie renseignée</p>
                        @endif
                    </div>

                    <!-- Services et tarifs -->
                    @if($practitioner->consultation_fee)
                        <div class="p-4">
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <h2 class="h5 fw-bold text-dark mb-0">Tarifs de consultation</h2>
                                @if($practitioner->verification_status === 'approved')
                                    <span class="badge bg-success d-inline-flex align-items-center gap-1">
                                        <i class="ri-check-line"></i>
                                        Vérifié
                                    </span>
                                @endif
                            </div>

                            <div class="border-top">
                                <div class="d-flex justify-content-between align-items-center py-3 border-bottom">
                                    <span class="fw-medium">Consultation générale</span>
                                    <span class="fw-bold text-dark">{{ number_format($practitioner->consultation_fee, 0, ',', ' ') }} FCFA</span>
                                </div>
                                @if($practitioner->main_specialty)
                                    <div class="d-flex justify-content-between align-items-center py-3 border-bottom">
                                        <span class="fw-medium">{{ $practitioner->main_specialty }}</span>
                                        <span class="fw-bold text-dark">{{ number_format($practitioner->consultation_fee, 0, ',', ' ') }} FCFA</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Colonne droite - Informations complémentaires -->
            <div class="col-lg-4">
                
                <!-- Informations professionnelles -->
                <div class="card border-0 rounded-3 card-shadow mb-4">
                    <div class="card-body p-4">
                        <h3 class="h6 fw-bold text-dark mb-4">Informations professionnelles</h3>
                        
                        <div class="d-flex flex-column gap-3">
                            <!-- Numéro d'ordre professionnel -->
                            @if($practitioner->order_number)
                                <div class="d-flex gap-3">
                                    <i class="ri-medal-line text-primary" style="font-size: 20px;"></i>
                                    <div>
                                        <p class="fw-semibold mb-1">Numéro d'ordre</p>
                                        <p class="text-muted small mb-0">{{ $practitioner->order_number }}</p>
                                    </div>
                                </div>
                            @endif

                            <!-- Profession -->
                            <div class="d-flex gap-3">
                                <i class="ri-stethoscope-line text-primary" style="font-size: 20px;"></i>
                                <div>
                                    <p class="fw-semibold mb-1">Profession</p>
                                    <p class="text-muted small mb-0">
                                        @switch($practitioner->profession)
                                            @case('general_practitioner') Médecin Généraliste @break
                                            @case('specialist_doctor') Médecin Spécialiste @break
                                            @case('midwife') Sage-femme @break
                                            @case('nurse') Infirmier(ère) @break
                                            @case('nursing_assistant') Aide-soignant(e) @break
                                            @case('physiotherapist') Kinésithérapeute @break
                                            @case('psychologist') Psychologue @break
                                            @case('nutritionist') Nutritionniste @break
                                            @default {{ $practitioner->profession }}
                                        @endswitch
                                    </p>
                                </div>
                            </div>

                            <!-- Spécialité principale -->
                            @if($practitioner->main_specialty)
                                <div class="d-flex gap-3">
                                    <i class="ri-heart-pulse-line text-primary" style="font-size: 20px;"></i>
                                    <div>
                                        <p class="fw-semibold mb-1">Spécialité principale</p>
                                        <p class="text-muted small mb-0">{{ $practitioner->main_specialty }}</p>
                                    </div>
                                </div>
                            @endif

                            <!-- Diplôme principal -->
                            @if($practitioner->main_diploma)
                                <div class="d-flex gap-3">
                                    <i class="ri-graduation-cap-line text-primary" style="font-size: 20px;"></i>
                                    <div>
                                        <p class="fw-semibold mb-1">Diplôme principal</p>
                                        <p class="text-muted small mb-0">{{ $practitioner->main_diploma }}</p>
                                    </div>
                                </div>
                            @endif

                            <!-- Date d'inscription -->
                            <div class="d-flex gap-3">
                                <i class="ri-calendar-line text-primary" style="font-size: 20px;"></i>
                                <div>
                                    <p class="fw-semibold mb-1">Date d'inscription</p>
                                    <p class="text-muted small mb-0">{{ \Carbon\Carbon::parse($practitioner->created_at)->format('d/m/Y à H:i') }}</p>
                                </div>
                            </div>

                            <!-- Date de validation -->
                            @if($practitioner->validated_at)
                                <div class="d-flex gap-3">
                                    <i class="ri-check-double-line text-success" style="font-size: 20px;"></i>
                                    <div>
                                        <p class="fw-semibold mb-1">Date de validation</p>
                                        <p class="text-muted small mb-0">{{ \Carbon\Carbon::parse($practitioner->validated_at)->format('d/m/Y à H:i') }}</p>
                                    </div>
                                </div>
                            @endif

                            <!-- Expérience -->
                            @if($practitioner->years_experience)
                                <div class="d-flex gap-3">
                                    <i class="ri-time-line text-primary" style="font-size: 20px;"></i>
                                    <div>
                                        <p class="fw-semibold mb-0">{{ $practitioner->years_experience }} années d'expérience</p>
                                    </div>
                                </div>
                            @endif

                            <!-- Institution affiliée -->
                            @if($practitioner->affiliated_institution)
                                <div class="d-flex gap-3">
                                    <i class="ri-building-line text-primary" style="font-size: 20px;"></i>
                                    <div>
                                        <p class="fw-semibold mb-1">Institution</p>
                                        <p class="text-muted small mb-0">{{ $practitioner->affiliated_institution }}</p>
                                    </div>
                                </div>
                            @endif

                            <!-- Adresse cabinet -->
                            @if($practitioner->office_address)
                                <div class="d-flex gap-3">
                                    <i class="ri-map-pin-line text-primary" style="font-size: 20px;"></i>
                                    <div>
                                        <p class="fw-semibold mb-1">Adresse du cabinet</p>
                                        <p class="text-muted small mb-0">{{ $practitioner->office_address }}</p>
                                    </div>
                                </div>
                            @endif

                            <!-- Téléphone professionnel -->
                            @if($practitioner->professional_phone)
                                <div class="d-flex gap-3">
                                    <i class="ri-phone-line text-primary" style="font-size: 20px;"></i>
                                    <div>
                                        <p class="fw-semibold mb-1">Téléphone professionnel</p>
                                        <p class="text-muted small mb-0">{{ $practitioner->professional_phone }}</p>
                                    </div>
                                </div>
                            @endif

                            <!-- Email professionnel -->
                            @if($practitioner->professional_email)
                                <div class="d-flex gap-3">
                                    <i class="ri-mail-line text-primary" style="font-size: 20px;"></i>
                                    <div>
                                        <p class="fw-semibold mb-1">Email professionnel</p>
                                        <p class="text-muted small mb-0">{{ $practitioner->professional_email }}</p>
                                    </div>
                                </div>
                            @endif

                            <!-- Disponibilités -->
                            @if($practitioner->availability)
                                <div class="d-flex gap-3">
                                    <i class="ri-check-line text-success" style="font-size: 20px;"></i>
                                    <div>
                                        <p class="fw-semibold mb-1">Disponible</p>
                                        <p class="text-muted small mb-0">Consultations disponibles</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Numéro d'ordre -->
                @if($practitioner->order_number)
                    <div class="card border-primary bg-primary bg-opacity-10 rounded-3 mb-4">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <i class="ri-shield-check-line text-primary" style="font-size: 20px;"></i>
                                <h3 class="h6 fw-bold text-dark mb-0">Numéro d'ordre</h3>
                            </div>
                            <p class="h4 fw-bold text-primary mb-0">{{ $practitioner->order_number }}</p>
                        </div>
                    </div>
                @endif

                <!-- Certificat d'autorisation d'exercice -->
                @if($practitioner->certificate_url)
                    <div class="card border-success bg-success bg-opacity-10 rounded-3 mb-4">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center gap-2 mb-3">
                                <i class="ri-file-text-line text-success" style="font-size: 20px;"></i>
                                <h3 class="h6 fw-bold text-dark mb-0">Autorisation d'exercice</h3>
                            </div>
                            <p class="text-muted small mb-3">Document d'autorisation d'exercice en clientèle privée</p>
                            <a href="{{ asset('storage/' . $practitioner->certificate_url) }}" 
                               target="_blank" 
                               class="btn btn-success btn-sm d-inline-flex align-items-center gap-2">
                                <i class="ri-download-2-line"></i>
                                Télécharger le certificat
                            </a>
                        </div>
                    </div>
                @else
                    <div class="card border-warning bg-warning bg-opacity-10 rounded-3 mb-4">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <i class="ri-alert-line text-warning" style="font-size: 20px;"></i>
                                <h3 class="h6 fw-bold text-dark mb-0">Certificat non fourni</h3>
                            </div>
                            <p class="text-muted small mb-0">Le praticien n'a pas encore fourni son autorisation d'exercice</p>
                        </div>
                    </div>
                @endif

                <!-- Langues parlées -->
                @if($practitioner->languages_spoken && count(json_decode($practitioner->languages_spoken, true)) > 0)
                    <div class="card border-0 rounded-3 card-shadow mb-4">
                        <div class="card-body p-4">
                            <h3 class="h6 fw-bold text-dark mb-3">Langues parlées</h3>
                            <div class="d-flex flex-wrap gap-2">
                                @foreach(json_decode($practitioner->languages_spoken, true) as $lang)
                                    <span class="badge bg-primary bg-opacity-10 text-primary">{{ $lang }}</span>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Raison du rejet (si applicable) -->
                @if($practitioner->rejection_reason)
                    <div class="alert alert-danger">
                        <h6 class="fw-bold mb-2">
                            <i class="ri-alert-line"></i> Raison du rejet
                        </h6>
                        <p class="mb-0 small">{{ $practitioner->rejection_reason }}</p>
                    </div>
                @endif

                <!-- Actions rapides admin -->
                <div class="card border-0 rounded-3 card-shadow">
                    <div class="card-body p-4">
                        <h3 class="h6 fw-bold text-dark mb-3">Actions rapides</h3>
                        <div class="d-grid gap-2">
                            <a href="{{ route('practitioner.edit', $practitioner->id) }}" class="btn btn-primary btn-sm">
                                <i class="ri-edit-line"></i> Modifier le profil
                            </a>
                            
                            @if($practitioner->verification_status == 'pending')
                                <form action="{{ route('practitioner.approve', $practitioner->id) }}" method="POST" onsubmit="return confirm('Valider ce praticien ?');">
                                    @csrf
                                    <button type="submit" class="btn btn-success btn-sm w-100">
                                        <i class="ri-check-line"></i> Valider l'inscription
                                    </button>
                                </form>
                                
                                <button onclick="rejectPractitioner({{ $practitioner->id }}, '{{ $practitioner->user->firstname }} {{ $practitioner->user->lastname }}')" class="btn btn-danger btn-sm">
                                    <i class="ri-close-line"></i> Rejeter l'inscription
                                </button>
                            @endif
                            
                            <form action="{{ route('practitioner.toggle-status', $practitioner->id) }}" method="POST" onsubmit="return confirm('{{ $practitioner->user->status == 'active' ? 'Suspendre' : 'Activer' }} ce compte ?');">
                                @csrf
                                <button type="submit" class="btn btn-{{ $practitioner->user->status == 'active' ? 'warning' : 'success' }} btn-sm w-100">
                                    <i class="ri-shield-{{ $practitioner->user->status == 'active' ? 'cross' : 'check' }}-line"></i> 
                                    {{ $practitioner->user->status == 'active' ? 'Suspendre' : 'Activer' }} le compte
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>
</div>

@endsection
