@extends('layout')
    @section('page_content')
    
            <!-- MAIN CONTENT-->
            <div class="main-content">
            <div class="section">
            <div class="container-fluid">
<div class="row mt-4 mb-3">
    <div class="col-12">
        <div class="overview-wrap">
            <a href="{{ route('practitioner.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i>   Retour
            </a>
        </div>
    </div>
</div>

 <div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-10">
        <form method="POST" action="{{route('practitioner.store')}}" enctype="multipart/form-data" class="form-horizontal">
            @csrf
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <strong>Ajouter un Praticien</strong>
                </div>
                @if (session('success'))
                    <div class="alert alert-success" role="alert">
                        <strong>{{ session('success') }}</strong>
                    </div>
                @endif
                <div class="card-body px-4">
                    <!-- Informations personnelles -->
                    <h5 class="mb-3 text-primary">Informations personnelles</h5>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="firstname" class="form-label">Nom <span class="text-danger">*</span></label>
                            <input type="text" id="firstname" name="firstname" placeholder="Nom" class="form-control rounded-pill focus-ring" value="{{old('firstname')}}" required>
                            @if ($errors->has('firstname'))
                                <span class="text-danger">{{ $errors->first('firstname') }}</span>
                            @endif
                        </div>
                        <div class="col-md-6">
                            <label for="lastname" class="form-label">Prénom <span class="text-danger">*</span></label>
                            <input type="text" id="lastname" name="lastname" placeholder="Prénom" class="form-control rounded-pill focus-ring" value="{{old('lastname')}}" required>
                            @if ($errors->has('lastname'))
                                <span class="text-danger">{{ $errors->first('lastname') }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" id="email" name="email" placeholder="Email" class="form-control rounded-pill focus-ring" value="{{old('email')}}" required>
                            @if ($errors->has('email'))
                                <span class="text-danger">{{ $errors->first('email') }}</span>
                            @endif
                        </div>
                        <div class="col-md-6">
                            <label for="phone" class="form-label">Téléphone <span class="text-danger">*</span></label>
                            <input type="text" id="phone" name="phone" placeholder="Téléphone" class="form-control rounded-pill focus-ring" value="{{old('phone')}}" required>
                            @if ($errors->has('phone'))
                                <span class="text-danger">{{ $errors->first('phone') }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="gender" class="form-label">Genre</label>
                            <select name="gender" id="gender" class="form-control rounded-pill focus-ring">
                                <option value="" disabled selected>Sélectionner le genre</option>
                                <option value="Masculin" {{old('gender') == "Masculin" ? 'selected' : ''}}>Masculin</option>
                                <option value="Féminin" {{old('gender') == "Féminin" ? 'selected' : ''}}>Féminin</option>
                            </select>
                            @if ($errors->has('gender'))
                                <span class="text-danger">{{ $errors->first('gender') }}</span>
                            @endif
                        </div>
                        <div class="col-md-6">
                            <label for="date_naissance" class="form-label">Date de naissance</label>
                            <input type="date" id="date_naissance" name="date_naissance" class="form-control rounded-pill focus-ring" value="{{old('date_naissance')}}">
                            @if ($errors->has('date_naissance'))
                                <span class="text-danger">{{ $errors->first('date_naissance') }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="city" class="form-label">Ville</label>
                            <input type="text" id="city" name="city" placeholder="Ville" class="form-control rounded-pill focus-ring" value="{{old('city')}}">
                            @if ($errors->has('city'))
                                <span class="text-danger">{{ $errors->first('city') }}</span>
                            @endif
                        </div>
                        <div class="col-md-6">
                            <label for="adress" class="form-label">Adresse</label>
                            <input type="text" id="adress" name="adress" placeholder="Adresse" class="form-control rounded-pill focus-ring" value="{{old('adress')}}">
                            @if ($errors->has('adress'))
                                <span class="text-danger">{{ $errors->first('adress') }}</span>
                            @endif
                        </div>
                    </div>

                    <hr class="my-4">

                    <!-- Informations professionnelles -->
                    <h5 class="mb-3 text-primary">Informations professionnelles</h5>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="order_number" class="form-label">Numéro d'ordre <span class="text-danger">*</span></label>
                            <input type="text" id="order_number" name="order_number" placeholder="Ex: MED12345" class="form-control rounded-pill focus-ring" value="{{old('order_number')}}" required>
                            @if ($errors->has('order_number'))
                                <span class="text-danger">{{ $errors->first('order_number') }}</span>
                            @endif
                        </div>
                        <div class="col-md-6">
                            <label for="profession" class="form-label">Profession <span class="text-danger">*</span></label>
                            <select name="profession" id="profession" class="form-control rounded-pill focus-ring" required onchange="toggleOtherProfession()">
                                <option value="" disabled selected>Sélectionnez la profession</option>
                                <option value="general_practitioner" {{old('profession') == "general_practitioner" ? 'selected' : ''}}>Médecin Généraliste</option>
                                <option value="specialist_doctor" {{old('profession') == "specialist_doctor" ? 'selected' : ''}}>Médecin Spécialiste</option>
                                <option value="midwife" {{old('profession') == "midwife" ? 'selected' : ''}}>Sage-femme</option>
                                <option value="nurse" {{old('profession') == "nurse" ? 'selected' : ''}}>Infirmier(ère)</option>
                                <option value="nursing_assistant" {{old('profession') == "nursing_assistant" ? 'selected' : ''}}>Aide-soignant(e)</option>
                                <option value="physiotherapist" {{old('profession') == "physiotherapist" ? 'selected' : ''}}>Kinésithérapeute</option>
                                <option value="psychologist" {{old('profession') == "psychologist" ? 'selected' : ''}}>Psychologue</option>
                                <option value="nutritionist" {{old('profession') == "nutritionist" ? 'selected' : ''}}>Nutritionniste</option>
                                <option value="other" {{old('profession') == "other" ? 'selected' : ''}}>🔹 Autre profession</option>
                            </select>
                            @if ($errors->has('profession'))
                                <span class="text-danger">{{ $errors->first('profession') }}</span>
                            @endif
                        </div>
                    </div>

                    <!-- Champ pour autre profession (caché par défaut) -->
                    <div class="row mb-3" id="otherProfessionField" style="display: none;">
                        <div class="col-md-12">
                            <label for="other_profession" class="form-label">Précisez votre profession <span class="text-danger">*</span></label>
                            <input type="text" id="other_profession" name="other_profession" placeholder="Ex: Ostéopathe, Ergothérapeute, etc." class="form-control rounded-pill focus-ring" value="{{old('other_profession')}}">
                            @if ($errors->has('other_profession'))
                                <span class="text-danger">{{ $errors->first('other_profession') }}</span>
                            @endif
                        </div>
                    </div>

                    <script>
                    function toggleOtherProfession() {
                        const professionSelect = document.getElementById('profession');
                        const otherField = document.getElementById('otherProfessionField');
                        const otherInput = document.getElementById('other_profession');
                        
                        if (professionSelect.value === 'other') {
                            otherField.style.display = 'block';
                            otherInput.required = true;
                        } else {
                            otherField.style.display = 'none';
                            otherInput.required = false;
                            otherInput.value = '';
                        }
                    }
                    
                    // Vérifier au chargement de la page si "Autre" est sélectionné
                    document.addEventListener('DOMContentLoaded', function() {
                        toggleOtherProfession();
                    });
                    </script>

                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label for="certificate" class="form-label">Autorisation d'exercice en clientèle privée <span class="text-danger">*</span></label>
                            <input type="file" id="certificate" name="certificate" class="form-control" accept=".pdf,.jpg,.jpeg,.png" required>
                            <small class="text-muted">Formats acceptés: PDF, JPG, PNG (Max: 5 MB)</small>
                            @if ($errors->has('certificate'))
                                <span class="text-danger">{{ $errors->first('certificate') }}</span>
                            @endif
                        </div>
                    </div>

                    <hr class="my-4">

                    <!-- Sécurité -->
                    <h5 class="mb-3 text-primary">Sécurité</h5>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="password" class="form-label">Mot de passe <span class="text-danger">*</span></label>
                            <input type="password" id="password" name="password" placeholder="Mot de passe" class="form-control rounded-pill focus-ring" required>
                            @if ($errors->has('password'))
                                <span class="text-danger">{{ $errors->first('password') }}</span>
                            @endif
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex flex-column align-items-center mb-3">
                                <label for="url_profil" class="form-label">Photo de profil</label>
                                <input type="file" id="url_profil" name="url_profil" class="form-control-file">
                                @if ($errors->has('url_profil'))
                                    <span class="text-danger">{{ $errors->first('url_profil') }}</span>
                                @endif
                            </div>
                        </div>
                    </div>

                </div>

                <div class="card-footer d-flex justify-content-end gap-2">
                    <a href="{{route('practitioner.index')}}" class="btn btn-secondary">
                        <i class="bi bi-x-circle"></i> Annuler
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle"></i> Enregistrer
                    </button>
                </div>
            </div>
        </form>
        </div>
    </div>
</div>

</div>
</div>
</div>

    @endsection
