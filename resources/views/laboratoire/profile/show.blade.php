@extends('laboratoire.layout')

@section('page_content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <a href="{{ route('laboratoire.profile.edit') }}" class="btn btn-primary">
                        <i class="ri-edit-line me-1"></i>
                        Modifier le profil
                    </a>
                </div>
                <h4 class="page-title">Profil du Laboratoire</h4>
            </div>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="ri-check-circle-line me-2"></i>
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <div class="row">
        <!-- Informations principales -->
        <div class="col-xl-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="ri-flask-line me-2 text-primary"></i>
                        Informations du Laboratoire
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold text-muted">Nom du Laboratoire</label>
                                <p class="mb-0 fs-5">{{ $laboratory->name }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold text-muted">Adresse</label>
                                <p class="mb-0">{{ $laboratory->address }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="form-label fw-bold text-muted">Description</label>
                                <p class="mb-0">{{ $laboratory->description ?? 'Aucune description' }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold text-muted">Statut</label>
                                <span class="badge bg-{{ $laboratory->user->status === 'active' ? 'success' : 'warning' }} fs-6">
                                    {{ $laboratory->user->status === 'active' ? 'Actif' : 'En attente' }}
                                </span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold text-muted">Date d'inscription</label>
                                <p class="mb-0">{{ $laboratory->created_at->format('d/m/Y') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Image du laboratoire -->
        <div class="col-xl-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="ri-image-line me-2 text-primary"></i>
                        Image du Laboratoire
                    </h5>
                </div>
                <div class="card-body text-center">
                    @if($laboratory->image)
                        <img src="{{ asset('storage/' . $laboratory->image) }}"
                             alt="Image du laboratoire"
                             class="img-fluid rounded shadow"
                             style="max-height: 200px; object-fit: cover;">
                    @else
                        <div class="bg-light rounded d-flex align-items-center justify-content-center"
                             style="height: 200px;">
                            <i class="ri-flask-line text-muted" style="font-size: 64px;"></i>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Informations du propriétaire -->
            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="ri-user-line me-2 text-primary"></i>
                        Propriétaire
                    </h5>
                </div>
                <div class="card-body">
                    <div class="text-center">
                        <div class="avatar-lg mx-auto mb-3">
                            @if($laboratory->user->url_profil && $laboratory->user->url_profil !== 'profile/profile.png')
                                <img src="{{ asset($laboratory->user->url_profil) }}"
                                     alt="Photo de profil"
                                     class="img-fluid rounded-circle">
                            @else
                                <div class="avatar-title bg-primary rounded-circle">
                                    <i class="ri-user-line fs-1"></i>
                                </div>
                            @endif
                        </div>
                        <h6 class="mb-1">{{ $laboratory->user->firstname }} {{ $laboratory->user->lastname }}</h6>
                        <p class="text-muted mb-2">{{ $laboratory->user->email }}</p>
                        <p class="text-muted mb-0">{{ $laboratory->user->phone }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection