<!DOCTYPE html>
<html lang="fr">

@php
    // Définir $user_auth pour éviter les erreurs
    $user_auth = Auth::user();
@endphp
<head>
    <meta charset="utf-8" />
    <title>Dashboard Laboratoire | Walab</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Espace laboratoire Walab" name="description" />
    <meta content="Walab" name="author" />

    <!-- Vite assets -->
    @vite(["resources/css/app.css", "resources/js/app.js"])

    <style>
        .lab-sidebar {
            background: linear-gradient(180deg, #667eea 0%, #764ba2 100%);
        }
        .lab-sidebar .nav-link {
            color: rgba(255, 255, 255, 0.8);
            transition: all 0.3s;
        }
        .lab-sidebar .nav-link:hover,
        .lab-sidebar .nav-link.active {
            color: white;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 8px;
        }
        .navbar-custom {
            background: white;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
    </style>
</head>

<body>
    <div class="wrapper">
        
        <div class="navbar-custom">
            <div class="topbar container-fluid">
                <div class="d-flex align-items-center gap-1">
                    <button class="button-toggle-menu">
                        <i class="ri-menu-line"></i>
                    </button>

                    <button class="navbar-toggle" data-bs-toggle="collapse" data-bs-target="#topnav-menu-content">
                        <div class="lines">
                            <span></span>
                            <span></span>
                            <span></span>
                        </div>
                    </button>
                </div>

                <ul class="topbar-menu d-flex align-items-center gap-3">
                    <li>
                        <a href="{{ route('notifications.index') }}" class="nav-link d-inline-flex align-items-center" style="position: relative;">
                            <i class="ri-notification-3-line font-22"></i>
                            <span id="notification-bell-badge" 
                                  class="badge rounded-pill bg-danger"
                                  style="display: none; font-size: 9px; position: absolute; top: 0; right: 0; padding: 2px 5px; min-width: 18px;">
                                0
                            </span>
                        </a>
                    </li>

                    <li class="d-none d-md-inline-block">
                        <a class="nav-link" href="#" data-toggle="fullscreen">
                            <i class="ri-fullscreen-line font-22"></i>
                        </a>
                    </li>

                    <li class="dropdown">
                        <a class="nav-link dropdown-toggle arrow-none nav-user" data-bs-toggle="dropdown" href="#" role="button"
                            aria-haspopup="false" aria-expanded="false">
                            <span class="account-user-avatar">
                                <i class="ri-flask-line" style="font-size: 24px;"></i>
                            </span>
                            <span class="d-lg-block d-none">
                                <h5 class="my-0 fw-normal">{{ Auth::user()->laboratorie->name ?? 'Laboratoire' }}</h5>
                            </span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end dropdown-menu-animated profile-dropdown">
                            <!-- item-->
                            <div class=" dropdown-header noti-title">
                                <h6 class="text-overflow m-0">Bienvenue !</h6>
                            </div>

                            <a href="{{ route('laboratoire.profile.show') }}" class="dropdown-item">
                                <i class="ri-account-circle-line fs-18 align-middle me-1"></i>
                                <span>Profil Laboratoire</span>
                            </a>
                            <a href="{{ route('laboratoire.logout') }}" class="dropdown-item">
                                <i class="ri-logout-box-line fs-18 align-middle me-1"></i>
                                <span>Déconnexion</span>
                            </a>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
      
        <div class="leftside-menu lab-sidebar">
            <!-- Brand Logo Light -->
            <a href="{{ route('laboratoire.dashboard') }}" class="logo logo-light">
                <span class="logo-lg">
                    <img src="{{asset('assets/images/logo.png')}}" alt="logo" height="40">
                </span>
                <span class="logo-sm">
                    <img src="{{asset('assets/images/logo.png')}}" alt="small logo" height="40">
                </span>
            </a>

            <div class="h-100" id="leftside-menu-container" data-simplebar>

                <ul class="side-nav">

                    <li class="side-nav-title text-white-50">Navigation</li>

                    <li class="side-nav-item">
                        <a href="{{ route('laboratoire.dashboard') }}" class="side-nav-link">
                            <i class="ri-dashboard-3-line"></i>
                            <span> Tableau de bord </span>
                        </a>
                    </li>

                    <li class="side-nav-title text-white-50 mt-2">Gestion</li>

                    <li class="side-nav-item">
                        <a href="{{ route('laboratoire.examens') }}" class="side-nav-link">
                            <i class="ri-test-tube-line"></i>
                            <span> Examens </span>
                        </a>
                    </li>

                    <li class="side-nav-item">
                        <a href="{{ route('laboratoire.commandes') }}" class="side-nav-link">
                            <i class="ri-shopping-cart-line"></i>
                            <span> Commandes </span>
                        </a>
                    </li>

                    <li class="side-nav-title text-white-50 mt-2">Finances</li>

                    <li class="side-nav-item">
                        <a href="{{ route('laboratoire.wallet') }}" class="side-nav-link">
                            <i class="ri-wallet-3-line"></i>
                            <span> Mon Portefeuille </span>
                        </a>
                    </li>

                    <li class="side-nav-title text-white-50 mt-2">Paramètres</li>

                    <li class="side-nav-item">
                        <a href="#" class="side-nav-link">
                            <i class="ri-building-line"></i>
                            <span> Mon Laboratoire </span>
                        </a>
                    </li>

                </ul>

                <div class="clearfix"></div>
            </div>
        </div>

       
        <div class="content-page">
            <div class="content">
                @yield('page_content')
            </div> <!-- content -->

            <!-- Footer Start -->
            <footer class="footer">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12 text-center">
                            <script>document.write(new Date().getFullYear())</script> © Walab - Espace Laboratoire
                        </div>
                    </div>
                </div>
            </footer>
           
        </div>

    </div>
  
    <script src="{{asset('assets/js/vendor.min.js')}}"></script>

    
    <script src="{{asset('assets/js/app.min.js')}}"></script>

    <!-- Notification Badge Script -->
    <script>
        // Fonction pour mettre à jour le badge de notifications
        async function updateNotificationBadge() {
            try {
                const response = await fetch('{{ route("notifications.unreadCount") }}', {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    credentials: 'same-origin'
                });
                
                if (!response.ok) {
                    console.error('Badge update failed:', response.status);
                    return;
                }
                
                const data = await response.json();
                console.log('Badge update:', data);
                
                if (data.success) {
                    const badge = document.getElementById('notification-bell-badge');
                    if (badge) {
                        const count = data.unread_count || 0;
                        if (count > 0) {
                            badge.textContent = count > 99 ? '99+' : count;
                            badge.style.display = 'inline-block';
                        } else {
                            badge.style.display = 'none';
                        }
                    }
                }
            } catch (error) {
                console.error('Erreur lors de la mise à jour du badge:', error);
            }
        }
        
        // Mettre à jour au chargement
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', updateNotificationBadge);
        } else {
            updateNotificationBadge();
        }
        
        // Mettre à jour toutes les 30 secondes
        setInterval(updateNotificationBadge, 30000);
    </script>

    <!-- Initialize Bootstrap tooltips -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize all tooltips
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        });
    </script>

</body>
</html>
