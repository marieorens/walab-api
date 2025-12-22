<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8" />
    <title>Inscription Laboratoire | Walab</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Créez votre compte laboratoire Walab" name="description" />
    <meta content="Walab" name="author" />

    <link rel="shortcut icon" href="{{asset('assets/images/logo.png')}}">
    <script src="{{asset('assets/js/config.js')}}"></script>
    <link href="{{asset('assets/css/app.min.css')}}" rel="stylesheet" type="text/css" id="app-style" />
    <link href="{{asset('assets/css/icons.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('assets/css/style.css')}}" rel="stylesheet" type="" />
    
    <style>
        body {
            background: #e1faff;
            min-height: 100vh;
        }

        .account-pages {
            min-height: 100vh;
            display: flex;
            align-items: center;
            padding: 1rem 0;
        }

        .lab-card {
            border-radius: 10px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.2);
            border: none;
            overflow: hidden;
        }

        .lab-gradient {
            background: #06b6d4;
        }

        .btn-lab-gradient {
            background: #06b6d4;
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.95rem;
            transition: all 0.2s ease;
        }

        .btn-lab-gradient:hover {
            background: #0891b2;
            color: white;
            transform: translateY(-1px);
        }

        .btn-outline-purple {
            border: 1px solid #06b6d4;
            color: #06b6d4;
            background: white;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.2s ease;
        }

        .btn-outline-purple:hover {
            background: #06b6d4;
            color: white;
        }

        .text-purple {
            color: #06b6d4;
        }

        .form-control, .form-select {
            padding: 0.65rem 0.875rem;
            border: 1px solid #dee2e6;
            border-radius: 6px;
            font-size: 0.95rem;
            transition: all 0.2s ease;
            background: #fff;
        }

        .form-control:focus, .form-select:focus {
            border-color: #06b6d4;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.15);
            background: #fff;
        }

        .form-label {
            font-weight: 500;
            color: #495057;
            margin-bottom: 0.4rem;
            font-size: 0.9rem;
        }

        .feature-item {
            display: flex;
            align-items: center;
            padding: 0.65rem 0.875rem;
            background: rgba(255,255,255,0.15);
            border-radius: 8px;
            margin-bottom: 0.5rem;
            transition: all 0.2s ease;
        }

        .feature-item:hover {
            background: rgba(255,255,255,0.25);
        }

        .feature-item i {
            font-size: 1.5rem;
            margin-right: 1rem;
            flex-shrink: 0;
        }

        .feature-item span {
            font-size: 0.95rem;
        }

        .step-indicator {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-bottom: 1.25rem;
            gap: 1rem;
        }

        .step-circle {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            transition: all 0.2s ease;
            border: 2px solid #dee2e6;
            background: white;
            color: #6c757d;
            font-size: 0.875rem;
        }

        .step-circle.active {
            background: #06b6d4;
            color: white;
            border-color: #06b6d4;
        }

        .step-circle.completed {
            background: #10b981;
            color: white;
            border-color: transparent;
        }

        .step-line {
            width: 60px;
            height: 2px;
            background: #e5e7eb;
            transition: all 0.3s ease;
        }

        .step-line.completed {
            background: #10b981;
        }

        .step-label {
            text-align: center;
            margin-top: 0.5rem;
            font-size: 0.75rem;
            color: #6b7280;
            font-weight: 600;
        }

        .step-label.active {
            color: #06b6d4;
        }

        .alert-info-custom {
            background: #f0f2ff;
            border-left: 3px solid #06b6d4;
            border-radius: 6px;
            padding: 0.875rem;
        }

        .form-check-input:checked {
            background-color: #06b6d4;
            border-color: #06b6d4;
        }

        .decorative-circle {
            position: absolute;
            border-radius: 50%;
            background: rgba(255,255,255,0.1);
        }

        .circle-1 {
            width: 250px;
            height: 250px;
            bottom: -80px;
            left: -80px;
        }

        .circle-2 {
            width: 150px;
            height: 150px;
            top: 50%;
            right: -50px;
        }

        .circle-3 {
            width: 100px;
            height: 100px;
            top: 80px;
            left: 50%;
            transform: translateX(-50%);
        }

        .logo-container {
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.25rem;
        }

        .logo-container img {
            max-width: 130px;
            max-height: 130px;
        }

        .input-group .btn {
            border: 2px solid #e5e7eb;
            border-left: none;
            border-radius: 0 12px 12px 0;
        }

        .input-group .form-control {
            border-right: none;
            border-radius: 12px 0 0 12px;
        }

        .step-content {
            display: none;
        }

        .step-content.active {
            display: block;
            animation: fadeIn 0.3s ease-in;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @media (max-width: 991.98px) {
            .account-pages {
                padding: 1.5rem 0;
            }

            .step-line {
                width: 40px;
            }

            .btn-lab-gradient, .btn-outline-purple {
                padding: 0.75rem 1.5rem;
                font-size: 0.9rem;
            }
        }

        /* Toast notifications */
        .toast-container {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
        }

        .custom-toast {
            min-width: 300px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            border: none;
        }

        .custom-toast.toast-error {
            background: #fff;
            border-left: 4px solid #dc3545;
        }

        .custom-toast.toast-warning {
            background: #fff;
            border-left: 4px solid #ffc107;
        }

        .custom-toast.toast-success {
            background: #fff;
            border-left: 4px solid #28a745;
        }

        .toast-header {
            background: transparent;
            border-bottom: none;
            padding: 0.75rem 1rem;
        }

        .toast-body {
            padding: 0.5rem 1rem 0.75rem;
        }
    </style>
</head>

<body>
    <div class="account-pages">
        <div class="container">    
            <div class="row justify-content-center">
                <div class="col-lg-11 col-xl-10">         
                    <div class="card lab-card"> 
                        <div class="row g-0">
                            <!-- Panneau gauche -->
                            <div class="col-lg-5 d-none d-lg-flex py-4 px-4 lab-gradient position-relative">
                                <div class="decorative-circle circle-1"></div>
                                <div class="decorative-circle circle-2"></div>
                                <div class="decorative-circle circle-3"></div>
                                
                                <div class="text-center w-100 position-relative" style="z-index: 10;">
                                    <div class="logo-container">
                                        <img src="{{asset('assets/images/logo.png')}}" alt="logo">
                                    </div>
                                    
                                    <h3 class="text-white mb-2 fw-bold" style="font-size: 1.65rem;">Rejoignez Walab</h3>
                                    <p class="text-white opacity-75 mb-3" style="font-size: 1rem;">
                                        Intégrez notre réseau de laboratoires partenaires
                                    </p>
                                    
                                    <div class="mt-3">
                                        <div class="feature-item">
                                            <i class="ri-check-double-line text-white"></i>
                                            <span class="text-white">Gestion simplifiée des examens</span>
                                        </div>
                                        <div class="feature-item">
                                            <i class="ri-check-double-line text-white"></i>
                                            <span class="text-white">Suivi temps réel des commandes</span>
                                        </div>
                                        <div class="feature-item">
                                            <i class="ri-check-double-line text-white"></i>
                                            <span class="text-white">Interface intuitive et moderne</span>
                                        </div>
                                        <div class="feature-item">
                                            <i class="ri-check-double-line text-white"></i>
                                            <span class="text-white">Support dédié 24/7</span>
                                        </div>
                                    </div>
                                    
                                    <div class="mt-3">
                                        <i class="ri-flask-line text-white" style="font-size: 80px; opacity: 0.15;"></i>
                                    </div>
                                </div>
                            </div>

                            <!-- Panneau droit -->
                            <div class="col-lg-7">
                                <div class="d-flex flex-column h-100">
                                    <!-- Image mobile uniquement -->
                                    <div class="d-lg-none text-center pt-4">
                                        <img src="{{asset('assets/images/logo-dark.png')}}" alt="logo" style="height: 60px;">
                                        <h5 class="mt-3 mb-1 fw-bold">Rejoignez Walab</h5>
                                        <p class="opacity-75 mb-0 small">Créez votre compte laboratoire</p>
                                    </div>
                                    
                                    <div class="py-4 px-3 px-lg-4">
                                        <div class="text-center mb-2">
                                            <h4 class="fw-bold text-purple mb-1" style="font-size: 1.35rem;">Créer un compte laboratoire</h4>
                                            <p class="text-muted mb-0" style="font-size: 0.95rem;">Quelques étapes simples pour démarrer</p>
                                        </div>

                                        <!-- Indicateur d'étapes -->
                                        <div class="step-indicator">
                                            <div class="text-center">
                                                <div class="step-circle active" id="stepCircle1">1</div>
                                                <div class="step-label active">Responsable</div>
                                            </div>
                                            <div class="step-line" id="stepLine1"></div>
                                            <div class="text-center">
                                                <div class="step-circle" id="stepCircle2">2</div>
                                                <div class="step-label">Laboratoire</div>
                                            </div>
                                        </div>

                                        @if(session('error'))
                                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                                <i class="ri-error-warning-line me-2"></i>
                                                {{ session('error') }}
                                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                            </div>
                                        @endif

                                        @if($errors->any())
                                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                                <ul class="mb-0 ps-3">
                                                    @foreach($errors->all() as $error)
                                                        <li>{{ $error }}</li>
                                                    @endforeach
                                                </ul>
                                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                            </div>
                                        @endif

                                        <form action="{{ route('laboratoire.register.store') }}" method="POST" enctype="multipart/form-data" id="registrationForm">
                                            @csrf

                                            <!-- Informations du Responsable -->
                                            <div class="step-content active" id="step1">
                                                <h6 class="text-purple fw-semibold mb-3" style="font-size: 1.05rem;">
                                                    <i class="ri-user-line me-2"></i>Informations du Responsable
                                                </h6>
                                                
                                                <div class="row">
                                                    <div class="col-md-6 mb-2">
                                                        <label for="firstname" class="form-label">Prénom <span class="text-danger">*</span></label>
                                                        <input type="text" 
                                                               class="form-control @error('firstname') is-invalid @enderror" 
                                                               id="firstname" 
                                                               name="firstname" 
                                                               value="{{ old('firstname') }}"
                                                               placeholder="Jean"
                                                               required>
                                                        @error('firstname')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>

                                                    <div class="col-md-6 mb-2">
                                                        <label for="lastname" class="form-label">Nom <span class="text-danger">*</span></label>
                                                        <input type="text" 
                                                               class="form-control @error('lastname') is-invalid @enderror" 
                                                               id="lastname" 
                                                               name="lastname" 
                                                               value="{{ old('lastname') }}"
                                                               placeholder="Dupont"
                                                               required>
                                                        @error('lastname')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-6 mb-2">
                                                        <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                                        <input type="email" 
                                                               class="form-control @error('email') is-invalid @enderror" 
                                                               id="email" 
                                                               name="email" 
                                                               value="{{ old('email') }}"
                                                               placeholder="contact@laboratoire.com"
                                                               required>
                                                        @error('email')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>

                                                    <div class="col-md-6 mb-2">
                                                        <label for="phone" class="form-label">Téléphone <span class="text-danger">*</span></label>
                                                        <input type="tel" 
                                                               class="form-control @error('phone') is-invalid @enderror" 
                                                               id="phone" 
                                                               name="phone" 
                                                               value="{{ old('phone') }}"
                                                               placeholder="+229 XX XX XX XX"
                                                               required>
                                                        @error('phone')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-6 mb-2">
                                                        <label for="password" class="form-label">Mot de passe <span class="text-danger">*</span></label>
                                                        <div class="input-group">
                                                            <input type="password" 
                                                                   class="form-control @error('password') is-invalid @enderror" 
                                                                   id="password" 
                                                                   name="password" 
                                                                   placeholder="••••••••"
                                                                   required>
                                                            <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                                                <i class="ri-eye-line" id="eyeIcon"></i>
                                                            </button>
                                                        </div>
                                                        @error('password')
                                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                                        @enderror
                                                        <small class="text-muted">Minimum 8 caractères</small>
                                                    </div>

                                                    <div class="col-md-6 mb-2">
                                                        <label for="password_confirmation" class="form-label">Confirmer <span class="text-danger">*</span></label>
                                                        <div class="input-group">
                                                            <input type="password" 
                                                                   class="form-control" 
                                                                   id="password_confirmation" 
                                                                   name="password_confirmation" 
                                                                   placeholder="••••••••"
                                                                   required>
                                                            <button class="btn btn-outline-secondary" type="button" id="togglePasswordConfirm">
                                                                <i class="ri-eye-line" id="eyeIconConfirm"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="d-flex justify-content-end mt-3">
                                                    <button type="button" class="btn btn-lab-gradient" id="nextStep1">
                                                        Suivant
                                                        <i class="ri-arrow-right-line ms-1"></i>
                                                    </button>
                                                </div>
                                            </div>

                                            <!--Informations du Laboratoire -->
                                            <div class="step-content" id="step2">
                                                <h6 class="text-purple fw-semibold mb-3">
                                                    <i class="ri-building-line me-2"></i>Informations du Laboratoire
                                                </h6>
                                                
                                                <div class="mb-2">
                                                    <label for="lab_name" class="form-label">Nom du Laboratoire <span class="text-danger">*</span></label>
                                                    <input type="text" 
                                                           class="form-control @error('lab_name') is-invalid @enderror" 
                                                           id="lab_name" 
                                                           name="lab_name" 
                                                           value="{{ old('lab_name') }}"
                                                           placeholder="Laboratoire d'Analyses Médicales XYZ"
                                                           required>
                                                    @error('lab_name')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                                <div class="mb-2">
                                                    <label for="address" class="form-label">Adresse <span class="text-danger">*</span></label>
                                                    <input type="text" 
                                                           class="form-control @error('address') is-invalid @enderror" 
                                                           id="address" 
                                                           name="address" 
                                                           value="{{ old('address') }}"
                                                           placeholder="123 Avenue Steinmetz, Cotonou"
                                                           required>
                                                    @error('address')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                                <div class="mb-2">
                                                    <label for="image" class="form-label">Image du Laboratoire</label>
                                                    <input type="file" 
                                                           class="form-control @error('image') is-invalid @enderror" 
                                                           id="image" 
                                                           name="image"
                                                           accept="image/jpeg,image/png,image/jpg">
                                                    @error('image')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                    <small class="text-muted">Formats acceptés: JPG, PNG, JPEG (max 2MB)</small>
                                                </div>

                                                <div class="mb-2">
                                                    <label for="description" class="form-label">Description</label>
                                                    <textarea class="form-control @error('description') is-invalid @enderror" 
                                                              id="description" 
                                                              name="description" 
                                                              rows="3"
                                                              placeholder="Présentez votre laboratoire, vos services, vos spécialités...">{{ old('description') }}</textarea>
                                                    @error('description')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                                <div class="mb-2">
                                                    <div class="form-check">
                                                        <input type="checkbox" 
                                                               class="form-check-input @error('terms') is-invalid @enderror" 
                                                               id="terms" 
                                                               name="terms" 
                                                               required>
                                                        <label class="form-check-label small" for="terms">
                                                            J'accepte les <a href="#" class="text-purple fw-semibold">conditions d'utilisation</a> 
                                                            et la <a href="#" class="text-purple fw-semibold">politique de confidentialité</a>
                                                            <span class="text-danger">*</span>
                                                        </label>
                                                        @error('terms')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <div class="alert-info-custom mb-3">
                                                    <div class="d-flex align-items-start">
                                                        <i class="ri-information-line me-2 text-purple fs-20"></i>
                                                        <small>
                                                            <strong>Note importante :</strong> Votre compte sera validé par un administrateur. 
                                                            Vous recevrez un email de confirmation dès activation.
                                                        </small>
                                                    </div>
                                                </div>

                                                <div class="d-flex justify-content-between mt-3 gap-2">
                                                    <button type="button" class="btn btn-outline-purple" id="prevStep2">
                                                        <i class="ri-arrow-left-line me-1"></i>
                                                        Retour
                                                    </button>
                                                    <button type="submit" class="btn btn-lab-gradient">
                                                        <i class="ri-user-add-line me-1"></i>
                                                        Créer mon compte
                                                    </button>
                                                </div>
                                            </div>
                                        </form>

                                        <div class="text-center mt-3">
                                            <p class="text-muted mb-0 ">
                                                Déjà inscrit ? 
                                                <a href="{{ route('laboratoire.login') }}" class="text-purple fw-semibold">
                                                    Se connecter
                                                </a>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Toast Container -->
    <div class="toast-container" id="toastContainer"></div>

    <script src="{{asset('assets/js/vendor.min.js')}}"></script>
    <script src="{{asset('assets/js/app.min.js')}}"></script>

    <!-- Modale OTP -->
    <div class="modal fade" id="otpModal" tabindex="-1" aria-labelledby="otpModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border-radius: 20px; border: none;">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold" id="otpModalLabel">Vérification de l'email</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body pt-2">
                    <p class="text-muted">Entrez le code OTP envoyé à <span id="otpEmail" class="fw-semibold text-purple"></span></p>
                    <div class="mb-3">
                        <input type="text" class="form-control text-center" id="otpInput" placeholder="000000" maxlength="6" style="letter-spacing: 0.5rem; font-size: 1.5rem; font-weight: 600;">
                    </div>
                    <div class="d-flex justify-content-between gap-2">
                        <button type="button" class="btn btn-outline-secondary" id="resendOtpBtn">
                            <i class="ri-refresh-line me-1"></i> Renvoyer
                        </button>
                        <button type="button" class="btn btn-lab-gradient" id="verifyOtpBtn">
                            <i class="ri-check-line me-1"></i> Vérifier
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modale d'attente -->
    <div class="modal fade" id="waitingModal" tabindex="-1" aria-labelledby="waitingModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border-radius: 20px; border: none;">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold" id="waitingModalLabel">Validation en cours</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body pt-2">
                    <div class="text-center py-3">
                        <i class="ri-time-line text-purple" style="font-size: 64px;"></i>
                        <p class="mt-3 text-muted">Votre compte est en attente de validation par un administrateur. Vous serez notifié par email une fois validé.</p>
                    </div>
                    <button type="button" class="btn btn-lab-gradient w-100" data-bs-dismiss="modal">Compris</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Fonction pour afficher les toasts
        function showToast(message, type = 'error') {
            const toastContainer = document.getElementById('toastContainer');
            const toastId = 'toast-' + Date.now();
            
            const iconClass = {
                'error': 'ri-error-warning-line text-danger',
                'warning': 'ri-alert-line text-warning',
                'success': 'ri-checkbox-circle-line text-success'
            }[type] || 'ri-information-line text-info';
            
            const toastHTML = `
                <div class="toast custom-toast toast-${type} show" id="${toastId}" role="alert" aria-live="assertive" aria-atomic="true">
                    <div class="toast-header">
                        <i class="${iconClass} me-2"></i>
                        <strong class="me-auto">${type === 'error' ? 'Erreur' : type === 'warning' ? 'Attention' : 'Succès'}</strong>
                        <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                    </div>
                    <div class="toast-body">
                        ${message}
                    </div>
                </div>
            `;
            
            toastContainer.insertAdjacentHTML('beforeend', toastHTML);
            
            const toastElement = document.getElementById(toastId);
            const bsToast = new bootstrap.Toast(toastElement, { delay: 4000 });
            bsToast.show();
            
            toastElement.addEventListener('hidden.bs.toast', function() {
                toastElement.remove();
            });
        }

        // Toggle password visibility
        document.getElementById('togglePassword').addEventListener('click', function() {
            const passwordInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eyeIcon');
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.className = 'ri-eye-off-line';
            } else {
                passwordInput.type = 'password';
                eyeIcon.className = 'ri-eye-line';
            }
        });

        document.getElementById('togglePasswordConfirm').addEventListener('click', function() {
            const passwordConfirmInput = document.getElementById('password_confirmation');
            const eyeIconConfirm = document.getElementById('eyeIconConfirm');
            if (passwordConfirmInput.type === 'password') {
                passwordConfirmInput.type = 'text';
                eyeIconConfirm.className = 'ri-eye-off-line';
            } else {
                passwordConfirmInput.type = 'password';
                eyeIconConfirm.className = 'ri-eye-line';
            }
        });

        // Navigation entre les étapes
        document.getElementById('nextStep1').addEventListener('click', function() {
            // Validation de l'étape 1
            const firstname = document.getElementById('firstname').value.trim();
            const lastname = document.getElementById('lastname').value.trim();
            const email = document.getElementById('email').value.trim();
            const phone = document.getElementById('phone').value.trim();
            const password = document.getElementById('password').value;
            const passwordConfirm = document.getElementById('password_confirmation').value;

            if (!firstname || !lastname || !email || !phone || !password || !passwordConfirm) {
                showToast('Veuillez remplir tous les champs obligatoires', 'warning');
                return;
            }

            if (password !== passwordConfirm) {
                showToast('Les mots de passe ne correspondent pas', 'error');
                return;
            }

            if (password.length < 8) {
                showToast('Le mot de passe doit contenir au moins 8 caractères', 'warning');
                return;
            }

            // Passage à l'étape 2
            document.getElementById('step1').classList.remove('active');
            document.getElementById('step2').classList.add('active');
            
            // Mise à jour des indicateurs
            document.getElementById('stepCircle1').classList.remove('active');
document.getElementById('stepCircle1').classList.add('completed');
document.getElementById('stepCircle1').innerHTML = '<i class="ri-check-line"></i>';
document.getElementById('stepLine1').classList.add('completed');
document.getElementById('stepCircle2').classList.add('active');
document.querySelectorAll('.step-label')[1].classList.add('active');
document.querySelectorAll('.step-label')[0].classList.remove('active');
// Scroll vers le haut
        window.scrollTo({ top: 0, behavior: 'smooth' });
    });

    document.getElementById('prevStep2').addEventListener('click', function() {
        // Retour à l'étape 1
        document.getElementById('step2').classList.remove('active');
        document.getElementById('step1').classList.add('active');
        
        // Mise à jour des indicateurs
        document.getElementById('stepCircle1').classList.add('active');
        document.getElementById('stepCircle1').classList.remove('completed');
        document.getElementById('stepCircle1').textContent = '1';
        document.getElementById('stepLine1').classList.remove('completed');
        document.getElementById('stepCircle2').classList.remove('active');
        document.querySelectorAll('.step-label')[0].classList.add('active');
        document.querySelectorAll('.step-label')[1].classList.remove('active');
        
        // Scroll vers le haut
        window.scrollTo({ top: 0, behavior: 'smooth' });
    });

    // OTP Modal functions
    function openOtpModal() {
        const email = document.querySelector('input[name="email"]').value;
        if (email) {
            document.getElementById('otpEmail').textContent = email;
            var otpModal = new bootstrap.Modal(document.getElementById('otpModal'));
            otpModal.show();
        } else {
            showToast('Veuillez entrer votre email d\'abord', 'warning');
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('verifyOtpBtn').addEventListener('click', function() {
            const otp = document.getElementById('otpInput').value;
            const email = document.getElementById('otpEmail').textContent;

            fetch('/api/email/verify', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ email: email, otp: otp })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    if (data.requires_admin_validation) {
                        var otpModal = bootstrap.Modal.getInstance(document.getElementById('otpModal'));
                        otpModal.hide();
                        var waitingModal = new bootstrap.Modal(document.getElementById('waitingModal'));
                        waitingModal.show();
                    } else {
                        window.location.href = '{{ route("laboratoire.dashboard") }}';
                    }
                } else {
                    showToast(data.message || 'Code OTP invalide', 'error');
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
                showToast('Erreur lors de la vérification', 'error');
            });
        });

        document.getElementById('resendOtpBtn').addEventListener('click', function() {
            const email = document.getElementById('otpEmail').textContent;

            fetch('/api/email/resend', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ email: email })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast('Code OTP renvoyé avec succès', 'success');
                } else {
                    showToast(data.message || 'Erreur lors du renvoi', 'error');
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
                showToast('Erreur lors du renvoi', 'error');
            });
        });
    });
</script>
</body>
</html>