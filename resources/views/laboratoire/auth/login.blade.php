<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8" />
    <title>Connexion Laboratoire | Walab</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Espace laboratoire Walab" name="description" />
    <meta content="Walab" name="author" />

    <link rel="shortcut icon" href="{{ asset('assets/images/logo.png') }}">

    <script src="{{ asset('assets/js/config.js') }}"></script>

    <link href="{{ asset('assets/css/app.min.css') }}" rel="stylesheet" type="text/css" id="app-style" />

    <link href="{{ asset('assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet" type="" />

    <style>
        .lab-gradient {
            background: #e1faff;
        }

        .lab-card {
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
        }

        .form-label {
            font-size: 0.95rem;
        }

        .form-control {
            font-size: 0.95rem;
        }

        .form-check-label {
            font-size: 0.95rem;
        }
    </style>
</head>

<body class="position-relative lab-gradient">
    <div class="account-pages position-relative">
        <div class="container">
            <div class="row justify-content-center" style="min-height: 100vh; align-items: center;">
                <div class="col-lg-9 col-xl-8">
                    <div class="card overflow-hidden lab-card">
                        <div class="row g-0">
                            <div class="col-lg-6 d-none d-lg-flex p-4 align-items-center"
                                style="background: linear-gradient(135deg, #0891b2 0%, #06b6d4 100%);">
                                <div class="text-center w-100">
                                    <img src="{{ asset('assets/images/logo.png') }}" alt="logo" class="mb-4"
                                        style="max-width: 120px;">
                                    <h3 class="text-white mb-3" style="font-size: 1.65rem;">Espace Laboratoire</h3>
                                    <p class="text-white px-3" style="font-size: 1rem;">
                                        Gérez vos analyses, examens et bilans en toute simplicité
                                    </p>
                                    <div class="mt-4">
                                        <i class="ri-flask-line text-white" style="font-size: 80px; opacity: 0.3;"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="d-flex flex-column h-100">
                                    <div class="auth-brand pt-4 px-4 d-lg-none">
                                        <a href="#" class="logo-dark text-center d-block">
                                            <img src="{{ asset('assets/images/logo-dark.png') }}" alt="logo"
                                                height="45">
                                        </a>
                                    </div>
                                    <div class="p-4 my-auto">
                                        <h4 class="mb-1" style="font-size: 1.5rem;">Bienvenue !</h4>
                                        <p class="text-muted mb-4" style="font-size: 1rem;">Connectez-vous à votre espace laboratoire</p>

                                        @if (session('success'))
                                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                                <i class="ri-check-line me-2"></i> {{ session('success') }}
                                                <button type="button" class="btn-close"
                                                    data-bs-dismiss="alert"></button>
                                            </div>
                                        @endif

                                        @if (session('error'))
                                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                                <i class="ri-error-warning-line me-2"></i> {{ session('error') }}
                                                <button type="button" class="btn-close"
                                                    data-bs-dismiss="alert"></button>
                                            </div>
                                        @endif

                                        <!-- form -->
                                        <form method="POST" action="{{ route('laboratoire.login.store') }}">
                                            @csrf
                                            <div class="mb-3">
                                                <label for="email" class="form-label">Adresse e-mail</label>
                                                <input class="form-control @error('email') is-invalid @enderror"
                                                    type="email" id="email" name="email"
                                                    placeholder="laboratoire@example.com" value="{{ old('email') }}"
                                                    required>
                                                @error('email')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="mb-3">
                                                <label for="password" class="form-label">Mot de passe</label>
                                                <div class="input-group">
                                                    <input class="form-control @error('password') is-invalid @enderror"
                                                        type="password" id="password" name="password"
                                                        placeholder="Entrez votre mot de passe" required>
                                                    <button class="btn btn-outline-secondary" type="button"
                                                        id="togglePassword">
                                                        <i class="ri-eye-line" id="eyeIcon"></i>
                                                    </button>
                                                </div>
                                                @error('password')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="mb-3">
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input" id="checkbox-signin"
                                                        name="remember">
                                                    <label class="form-check-label" for="checkbox-signin">Se souvenir de
                                                        moi</label>
                                                </div>
                                            </div>

                                            <div class="mb-0 text-start">
                                                <button class="btn w-100"
                                                    style="background: linear-gradient(135deg, #0891b2 0%, #06b6d4 100%); color: white;"
                                                    type="submit">
                                                    <i class="ri-login-circle-fill me-1"></i>
                                                    <span class="fw-bold">Se connecter</span>
                                                </button>
                                            </div>

                                            <div class="mt-3">
                                                <button type="button" class="btn btn-outline-primary w-100"
                                                    onclick="openOtpModal()">
                                                    Vérifier mon email
                                                </button>
                                            </div>
                                        </form>

                                        <div class="text-center mt-4">
                                            <p class="text-muted mb-2" style="font-size: 0.95rem;">
                                                Vous n'avez pas de compte ?
                                                <a href="{{ route('laboratoire.register') }}"
                                                    class="text-purple fw-bold">
                                                    Inscrivez-vous
                                                </a>
                                            </p>
                                            <p class="text-muted" style="font-size: 0.9rem;">
                                                <i class="ri-shield-check-line"></i>
                                                Connexion sécurisée
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

    <script src="{{ asset('assets/js/vendor.min.js') }}"></script>

    <script src="{{ asset('assets/js/app.min.js') }}"></script>

    <!-- Modale de vérification OTP -->
    <div class="modal fade" id="otpModal" tabindex="-1" aria-labelledby="otpModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="otpModalLabel">Vérification de l'email</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3" id="emailField" style="display: none;">
                        <label for="modalEmailInput" class="form-label">Adresse email</label>
                        <input type="email" class="form-control" id="modalEmailInput"
                            placeholder="Entrez votre email">
                    </div>
                    <p>Entrez le code OTP envoyé à <span id="otpEmail"></span></p>
                    @if (session('dev_otp'))
                        <div class="alert alert-info">
                            <strong>Mode développement :</strong> Code OTP : <code>{{ session('dev_otp') }}</code>
                        </div>
                    @endif
                    <div class="mb-3">
                        <input type="text" class="form-control" id="otpInput"
                            placeholder="Entrez le code à 6 chiffres" maxlength="6">
                    </div>
                    <div class="d-flex justify-content-between">
                        <button type="button" class="btn btn-outline-primary" id="resendOtpBtn">Renvoyer le
                            code</button>
                        <button type="button" class="btn btn-primary" id="verifyOtpBtn">Vérifier</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modale d'attente de validation -->
    <div class="modal fade" id="waitingModal" tabindex="-1" aria-labelledby="waitingModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="waitingModalLabel">Validation en cours</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Votre compte est en attente de validation par un administrateur. Vous serez notifié par email une
                        fois validé.</p>
                </div>
            </div>
        </div>
    </div>

    <script>
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

        function openOtpModal() {
            document.getElementById('emailField').style.display = 'block';
            const emailInput = document.getElementById('modalEmailInput');
            const email = document.querySelector('input[name="email"]').value;
            if (email) {
                emailInput.value = email;
                document.getElementById('otpEmail').textContent = email;
            }
            var otpModal = new bootstrap.Modal(document.getElementById('otpModal'));
            otpModal.show();
        }

        document.addEventListener('DOMContentLoaded', function() {
            @if (session('requires_verification'))
                document.getElementById('otpEmail').textContent = '{{ session('email') }}';
                @if (session('dev_otp'))
                    document.getElementById('modalEmailInput').value = '{{ session('email') }}';
                @endif
                var otpModal = new bootstrap.Modal(document.getElementById('otpModal'));
                otpModal.show();
            @endif

            // Vérifier OTP
            document.getElementById('verifyOtpBtn').addEventListener('click', function() {
                const otp = document.getElementById('otpInput').value;
                let email = document.getElementById('otpEmail').textContent;
                if (document.getElementById('emailField').style.display !== 'none') {
                    email = document.getElementById('modalEmailInput').value;
                    document.getElementById('otpEmail').textContent = email;
                }

                fetch('/api/email/verify', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            email: email,
                            otp: otp
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            if (data.requires_admin_validation) {
                                var otpModal = bootstrap.Modal.getInstance(document.getElementById(
                                    'otpModal'));
                                otpModal.hide();
                                var waitingModal = new bootstrap.Modal(document.getElementById(
                                    'waitingModal'));
                                waitingModal.show();
                            } else {
                                window.location.href = '{{ route('laboratoire.dashboard') }}';
                            }
                        } else {
                            alert(data.message || 'Code OTP invalide');
                        }
                    })
                    .catch(error => {
                        console.error('Erreur:', error);
                        alert('Erreur lors de la vérification');
                    });
            });

            // Renvoyer OTP
            document.getElementById('resendOtpBtn').addEventListener('click', function() {
                let email = document.getElementById('otpEmail').textContent;
                if (document.getElementById('emailField').style.display !== 'none') {
                    email = document.getElementById('modalEmailInput').value;
                }

                fetch('/api/email/resend', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            email: email
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert('Code OTP renvoyé');
                        } else {
                            alert(data.message || 'Erreur lors du renvoi');
                        }
                    })
                    .catch(error => {
                        console.error('Erreur:', error);
                        alert('Erreur lors du renvoi');
                    });
            });
        });
    </script>
</body>

</html>
