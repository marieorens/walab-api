@extends('laboratoire.layout')

@section('page_content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <a href="{{ route('laboratoire.profile.show') }}" class="btn btn-outline-primary">
                        <i class="ri-arrow-left-line me-1"></i>
                        Retour au profil
                    </a>
                </div>
                <h4 class="page-title">Modifier le Profil</h4>
            </div>
        </div>
    </div>

    @if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="ri-error-warning-line me-2"></i>
        <strong>Erreurs de validation :</strong>
        <ul class="mb-0 mt-2">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <div class="row">
        <div class="col-xl-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="ri-edit-line me-2 text-primary"></i>
                        Modifier les informations
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('laboratoire.profile.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">
                                    Nom du Laboratoire <span class="text-danger">*</span>
                                </label>
                                <input type="text"
                                       class="form-control @error('name') is-invalid @enderror"
                                       id="name"
                                       name="name"
                                       value="{{ old('name', $laboratory->name) }}"
                                       required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="address" class="form-label">
                                    Adresse <span class="text-danger">*</span>
                                </label>
                                <input type="text"
                                       class="form-control @error('address') is-invalid @enderror"
                                       id="address"
                                       name="address"
                                       value="{{ old('address', $laboratory->address) }}"
                                       required>
                                @error('address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror"
                                      id="description"
                                      name="description"
                                      rows="4"
                                      placeholder="Décrivez votre laboratoire, vos services, spécialités...">{{ old('description', $laboratory->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="image" class="form-label">Image du Laboratoire</label>
                            <input type="file"
                                   class="form-control @error('image') is-invalid @enderror"
                                   id="image"
                                   name="image"
                                   accept="image/*">
                            @error('image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">
                                Formats acceptés: JPG, PNG, JPEG. Taille maximale: 2MB
                            </div>
                            @if($laboratory->image)
                                <div class="mt-2">
                                    <small class="text-muted">Image actuelle:</small><br>
                                    <img src="{{ asset('storage/' . $laboratory->image) }}"
                                         alt="Image actuelle"
                                         class="img-thumbnail mt-1"
                                         style="max-width: 200px; max-height: 150px;">
                                </div>
                            @endif
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="ri-save-line me-1"></i>
                                Enregistrer les modifications
                            </button>
                            <a href="{{ route('laboratoire.profile.show') }}" class="btn btn-outline-secondary">
                                Annuler
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Aperçu -->
        <div class="col-xl-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="ri-eye-line me-2 text-primary"></i>
                        Aperçu
                    </h5>
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        <div id="image-preview" class="bg-light rounded d-flex align-items-center justify-content-center mx-auto"
                             style="width: 120px; height: 120px;">
                            @if($laboratory->image)
                                <img src="{{ asset('storage/' . $laboratory->image) }}"
                                     alt="Aperçu"
                                     class="img-fluid rounded"
                                     id="current-image"
                                     style="width: 100%; height: 100%; object-fit: cover;">
                            @else
                                <i class="ri-flask-line text-muted" style="font-size: 48px;"></i>
                            @endif
                        </div>
                    </div>

                    <div class="text-center">
                        <h6 id="name-preview">{{ $laboratory->name }}</h6>
                        <p class="text-muted small mb-2" id="address-preview">{{ $laboratory->address }}</p>
                        <p class="text-muted small" id="description-preview">
                            {{ Str::limit($laboratory->description ?? 'Aucune description', 100) }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-body">
                    <h6 class="card-title">
                        <i class="ri-information-line me-2 text-info"></i>
                        Conseils
                    </h6>
                    <ul class="list-unstyled mb-0 small text-muted">
                        <li class="mb-2">
                            <i class="ri-check-line text-success me-1"></i>
                            Choisissez une image claire et professionnelle
                        </li>
                        <li class="mb-2">
                            <i class="ri-check-line text-success me-1"></i>
                            Décrivez vos spécialités et équipements
                        </li>
                        <li>
                            <i class="ri-check-line text-success me-1"></i>
                            Maintenez vos informations à jour
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Aperçu en temps réel
    const nameInput = document.getElementById('name');
    const addressInput = document.getElementById('address');
    const descriptionInput = document.getElementById('description');
    const imageInput = document.getElementById('image');

    const namePreview = document.getElementById('name-preview');
    const addressPreview = document.getElementById('address-preview');
    const descriptionPreview = document.getElementById('description-preview');
    const imagePreview = document.getElementById('image-preview');

    function updatePreview() {
        namePreview.textContent = nameInput.value || 'Nom du laboratoire';
        addressPreview.textContent = addressInput.value || 'Adresse';
        descriptionPreview.textContent = descriptionInput.value
            ? descriptionInput.value.substring(0, 100) + (descriptionInput.value.length > 100 ? '...' : '')
            : 'Aucune description';
    }

    nameInput.addEventListener('input', updatePreview);
    addressInput.addEventListener('input', updatePreview);
    descriptionInput.addEventListener('input', updatePreview);

    // Aperçu de l'image
    imageInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                imagePreview.innerHTML = `<img src="${e.target.result}" alt="Aperçu" class="img-fluid rounded" style="width: 100%; height: 100%; object-fit: cover;">`;
            };
            reader.readAsDataURL(file);
        }
    });
});
</script>
@endsection