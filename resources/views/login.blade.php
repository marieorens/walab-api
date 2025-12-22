<!DOCTYPE html>
<html lang="en">

  <!-- Mirrored from techzaa.getappui.com/velonic/layouts/index.html by HTTrack Website Copier/3.x [XR&CO'2014], Wed, 24 Jul 2024 15:10:06 GMT -->
<head>
        <meta charset="utf-8" />
        <title>Login | Walab</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta content="A fully responsive admin theme which can be used to build CRM, CMS,ERP etc." name="description" />
        <meta content="Techzaa" name="author" />

         <!-- App favicon -->
         <link rel="shortcut icon" href="{{asset('assets/images/logo.png')}}">

    <!-- Theme Config Js -->
    <script src="{{asset('assets/js/config.js')}}"></script>

    <!-- App css -->
    <link href="{{asset('assets/css/app.min.css')}}" rel="stylesheet" type="text/css" id="app-style" />

    <!-- Icons css -->
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
            padding: 2rem 0;
        }
        
        .login-card {
            border-radius: 24px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
            border: none;
            overflow: hidden;
        }

        .login-image-side {
            background: linear-gradient(135deg, #0891b2 0%, #06b6d4 100%);
            position: relative;
            padding: 3rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
            min-height: 600px;
        }

        .login-image-side::before {
            content: '';
            position: absolute;
            bottom: -50px;
            left: -50px;
            width: 200px;
            height: 200px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
        }

        .login-image-side::after {
            content: '';
            position: absolute;
            top: 50%;
            right: -30px;
            width: 120px;
            height: 120px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
        }

        .login-image-container {
            position: relative;
            z-index: 10;
            text-align: center;
            color: white;
        }

        .login-image-container h3 {
            font-size: 2rem;
            font-weight: 800;
            margin-bottom: 1rem;
            color: white;
        }

        .login-image-container p {
            font-size: 1rem;
            opacity: 0.95;
            line-height: 1.6;
        }

        .login-features {
            margin-top: 2rem;
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .feature-item {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            color: white;
            font-size: 0.95rem;
        }

        .feature-icon {
            width: 40px;
            height: 40px;
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .login-form-side {
            padding: 3rem 2.5rem;
            background: white;
        }

        .auth-brand {
            margin-bottom: 2rem;
            text-align: center;
        }

        .auth-brand img {
            height: 60px;
        }

        .login-title {
            font-size: 1.75rem;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 0.5rem;
        }

        .login-subtitle {
            color: #6b7280;
            margin-bottom: 2rem;
            font-size: 0.95rem;
        }

        .form-label {
            font-weight: 600;
            color: #374151;
            margin-bottom: 0.5rem;
            font-size: 0.9rem;
        }

        .form-control {
            padding: 0.75rem 1rem;
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            font-size: 0.95rem;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: #06b6d4;
            box-shadow: 0 0 0 3px rgba(6, 182, 212, 0.1);
        }

        .password-input-wrapper {
            position: relative;
        }

        .password-input-wrapper .form-control {
            padding-right: 3rem;
        }

        .password-toggle-icon {
            position: absolute;
            right: 1rem;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #6b7280;
            transition: color 0.2s ease;
            z-index: 10;
            font-size: 1.2rem;
            user-select: none;
        }

        .password-toggle-icon:hover {
            color: #06b6d4;
        }

        .btn-login {
            background: linear-gradient(135deg, #06b6d4 0%, #0891b2 100%);
            border: none;
            padding: 0.875rem;
            border-radius: 12px;
            font-weight: 600;
            font-size: 1rem;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(6, 182, 212, 0.3);
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(6, 182, 212, 0.4);
            background: linear-gradient(135deg, #0891b2 0%, #06b6d4 100%);
        }

        .form-check-input:checked {
            background-color: #06b6d4;
            border-color: #06b6d4;
        }

        .decorative-top-circle {
            position: absolute;
            top: 20px;
            left: 50%;
            width: 100px;
            height: 100px;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 50%;
            transform: translateX(-50%);
        }

        /* Mobile logo */
        .mobile-logo {
            display: block;
            text-align: center;
            margin-bottom: 2rem;
        }

        .mobile-logo img {
            height: 50px;
        }

        @media (max-width: 991.98px) {
            .account-pages {
                padding: 1.5rem 0;
            }

            .login-form-side {
                padding: 2rem 1.5rem;
            }
            
            .login-image-side {
                min-height: auto;
                padding: 2rem;
            }

            .login-image-container h3 {
                font-size: 1.5rem;
            }

            .login-image-container p {
                font-size: 0.9rem;
            }

            .auth-brand img {
                height: 50px;
            }

            .mobile-logo {
                display: none;
            }
        }

        @media (min-width: 992px) {
            .mobile-logo {
                display: none;
            }
        }
    </style>

</head>


<body class="position-relative">
    <div class="account-pages position-relative">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10 col-xl-9">
                    <div class="card login-card">
                        <div class="row g-0">
                            <!-- Partie gauche -->
                            <div class="col-lg-5 d-none d-lg-block">
                                <div class="login-image-side">
                                    <div class="decorative-top-circle"></div>
                                    
                                    <div class="login-image-container">
                                        <div class="mb-4">
                                            <a href="#" class="logo-light">
                                                <img class="w-50 h-auto" src="{{asset('assets/images/logo.png')}}" alt="logo">
                                            </a>
                                        </div>
                                        
                                        <h3>Bienvenue sur l'espace<br/>WALAB Admin</h3>
                                        <p>Gérez votre laboratoire médical avec une plateforme moderne et sécurisée</p>
                                        
                                        <div class="login-features">
                                            <div class="feature-item">
                                                <div class="feature-icon">
                                                    <i class="ri-shield-check-line" style="font-size: 1.25rem;"></i>
                                                </div>
                                                <span>Sécurité renforcée</span>
                                            </div>
                                            <div class="feature-item">
                                                <div class="feature-icon">
                                                    <i class="ri-dashboard-line" style="font-size: 1.25rem;"></i>
                                                </div>
                                                <span>Tableau de bord complet</span>
                                            </div>
                                            <div class="feature-item">
                                                <div class="feature-icon">
                                                    <i class="ri-time-line" style="font-size: 1.25rem;"></i>
                                                </div>
                                                <span>Gestion en temps réel</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Partie droite - Formulaire -->
                            <div class="col-lg-7">
                                <div class="login-form-side">
                                    <!-- Logo mobile uniquement -->
                                    <div class="mobile-logo d-lg-none">
                                        <img src="{{asset('assets/images/logo.png')}}" alt="logo">
                                    </div>

                                    <!-- Logo desktop uniquement -->
                                    <div class="auth-brand d-none d-lg-block">
                                        <a href="#" class="logo-dark">
                                            <img src="{{asset('assets/images/logo-dark.png')}}" alt="dark logo">
                                        </a>
                                    </div>

                                    <h4 class="login-title">Connexion Admin</h4>
                                    <p class="login-subtitle">Saisissez vos identifiants pour accéder à votre espace d'administration</p>

                                    <!-- form -->
                                    <form method="POST" action="{{route("login.store")}}">
                                        @csrf
                                        
                                        <div class="mb-3">
                                            <label for="email" class="form-label">Adresse e-mail</label>
                                            <input class="form-control" type="email" id="email" name="email" placeholder="admin@walab.com" required="">
                                        </div>
                                        @error('email')
                                            <div class="text-danger mb-3" role="alert">{{ $message }}</div>
                                        @enderror

                                        <div class="mb-3">
                                            <label for="password" class="form-label">Mot de passe</label>
                                            <div class="password-input-wrapper">
                                                <input class="form-control" type="password" id="password" name="password" placeholder="••••••••" required="">
                                                <i class="ri-eye-off-line password-toggle-icon" id="togglePassword" onclick="togglePasswordVisibility()"></i>
                                            </div>
                                        </div>
                                        @error('password')
                                            <div class="text-danger mb-3" role="alert">{{ $message }}</div>
                                        @enderror

                                        <div class="mb-4">
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input" id="checkbox-signin">
                                                <label class="form-check-label" for="checkbox-signin" style="font-size: 0.9rem; color: #6b7280;">Rester connecté</label>
                                            </div>
                                        </div>

                                        <div class="mb-0">
                                            <button class="btn btn-login w-100 text-white" type="submit">
                                                <i class="ri-login-circle-fill me-2"></i>
                                                <span class="fw-bold">Se connecter</span>
                                            </button>
                                        </div>
                                    </form>
                                    <!-- end form-->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Vendor js -->
    <script src="{{asset('assets/js/vendor.min.js')}}"></script>

    <!-- App js -->
    <script src="{{asset('assets/js/app.min.js')}}"></script>

    <script>
        function togglePasswordVisibility() {
            const passwordInput = document.getElementById('password');
            const toggleIcon = document.getElementById('togglePassword');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.remove('ri-eye-off-line');
                toggleIcon.classList.add('ri-eye-line');
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.remove('ri-eye-line');
                toggleIcon.classList.add('ri-eye-off-line');
            }
        }
    </script>

</body>

</html>