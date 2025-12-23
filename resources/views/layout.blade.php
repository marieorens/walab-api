
<!DOCTYPE html>
<html lang="en">
@php
    use App\Models\Frais;
    use Illuminate\Support\Facades\Auth;

    $fraisData = Frais::find(1);
    $fraisMontant = $fraisData ? $fraisData->frais : 15;
    $fraisPourcentage = $fraisData ? $fraisData->pourcentage_majoration : 40;
    
    // Définir $user_auth pour éviter les erreurs
    $user_auth = Auth::user();
@endphp
<head>
    <meta charset="utf-8" />
    <title>Dashboard | Walab</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="A fully responsive admin theme which can be used to build CRM, CMS,ERP etc." name="description" />
    <meta content="Techzaa" name="author" />

    <!-- App favicon -->
    <link rel="shortcut icon" href="{{asset('assets/images/logo.png')}}">

        <!-- Vite assets -->
        @vite(["resources/css/app.css", "resources/js/app.js"])
    @stack('styles')
</head>

<body>
<!-- Begin page -->
<div class="wrapper">


    <div class="navbar-custom">
        <div class="topbar container-fluid">
            <div class="d-flex align-items-center gap-1">


                <!-- Topbar Brand Logo -->
                <div class="logo-topbar">
                    <!-- Logo light -->
                    <a href="" class="logo-light">
                            <span class="logo-lg">
                                <img src="{{asset('assets/images/logo.png')}}" alt="logo" height="70">
                            </span>
                        <span class="logo-sm">
                                <img src="{{asset('assets/images/logo_dark_fond.png')}}" alt="small logo" height="100">
                            </span>
                    </a>

                    <!-- Logo Dark -->
                    <a href="" class="logo-dark">
                            <span class="logo-lg">
                                <img src="{{asset('assets/images/logo-dark.png')}}" alt="dark logo" height="70">
                            </span>
                        <span class="logo-sm">
                                <img src="{{asset('assets/images/logo-dark.png')}}" alt="small logo" height="100">
                            </span>
                    </a>
                </div>

                <!-- Sidebar Menu Toggle Button -->
                <button class="button-toggle-menu">
                    <i class="ri-menu-line"></i>
                </button>

                <!-- Horizontal Menu Toggle Button -->
                <button class="navbar-toggle" data-bs-toggle="collapse" data-bs-target="#topnav-menu-content">
                    <div class="lines">
                        <span></span>
                        <span></span>
                        <span></span>
                    </div>
                </button>

            </div>

            <ul class="topbar-menu d-flex align-items-center gap-3">
                <li class="dropdown d-lg-none">
                    <a class="nav-link dropdown-toggle arrow-none" data-bs-toggle="dropdown" href="#" role="button"
                       aria-haspopup="false" aria-expanded="false">
                        <i class="ri-search-line fs-22"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-animated dropdown-lg p-0">
                        <form class="p-3">
                            <input type="search" class="form-control" placeholder="Search ..."
                                   aria-label="Recipient's username">
                        </form>
                    </div>
                </li>

                <li>
                    <a href="{{ route('notifications.index') }}" class="nav-link d-inline-flex align-items-center" style="position: relative;">
                        <i class="ri-notification-3-line fs-22"></i>
                        <span id="notification-bell-badge" 
                              class="badge rounded-pill bg-danger"
                              style="display: none; font-size: 9px; position: absolute; top: 0; right: 0; padding: 2px 5px; min-width: 18px;">
                            0
                        </span>
                    </a>
                </li>

                <li class="d-none d-sm-inline-block">
                    <div class="nav-link">
                        <button type="button" class="btn btn-link" data-bs-toggle="modal" data-bs-target="#createFrais">
                            <i class="ri-car-line fs-22"></i>
                            <span>Frais</span>
                        </button>
                    </div>
                </li>

                <li class="d-none d-sm-inline-block">
                    <div class="nav-link" id="light-dark-mode">
                        <i class="ri-moon-line fs-22"></i>
                    </div>
                </li>

                <li class="dropdown">
                    <a class="nav-link dropdown-toggle arrow-none nav-user" data-bs-toggle="dropdown" href="#"
                       role="button" aria-haspopup="false" aria-expanded="false">
                            <span class="account-user-avatar">
                                <img src="{{ asset($user_auth->url_profil) }}" alt="user-image" width="32"
                                     class="rounded-circle">
                            </span>
                        <span class="d-lg-block d-none">
                                <h5 class="my-0 fw-normal">{{$user_auth->firstname}} <i
                                        class="ri-arrow-down-s-line d-none d-sm-inline-block align-middle"></i></h5>
                            </span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end dropdown-menu-animated profile-dropdown">
                        <!-- item-->
                        <div class=" dropdown-header noti-title">
                            <h6 class="text-overflow m-0">Bienvenue !</h6>
                        </div>

                        <!-- item-->
                        <a href="{{route('users.index')}}" class="dropdown-item">
                            <i class="ri-account-circle-line fs-18 align-middle me-1"></i>
                            <span>Mon compte</span>
                        </a>

                        <!-- item-->
                        <a href="{{url('/logout')}}" class="dropdown-item">
                            <i class="ri-logout-box-line fs-18 align-middle me-1"></i>
                            <span>Logout</span>
                        </a>
                    </div>
                </li>
            </ul>
        </div>
    </div>

    <div class="leftside-menu">

        <!-- Brand Logo Light -->
        <a href="" class="logo logo-light">
                <span class="logo-lg">
                    <img src="{{asset('assets/images/logo.png')}}" alt="logo">
                </span>
            <span class="logo-sm">
                    <img src="{{asset('assets/images/logo.png')}}" alt="small logo">
                </span>
        </a>

        <!-- Brand Logo Dark -->
        <a href="" class="logo logo-dark">
                <span class="logo-lg">
                    <img src="{{asset('assets/images/logo-dark.png')}}" alt="dark logo" height="50">
                </span>
            <span class="logo-sm">
                    <img src="{{asset('assets/images/logo.png')}}" alt="small logo">
                </span>
        </a>

        <!-- Sidebar -left -->
        <div class="h-100" id="leftside-menu-container" data-simplebar>
            <!--- Sidemenu -->
            <ul class="side-nav">

                <li class="side-nav-title">Principal</li>

                <li class="side-nav-item">
                    <a href="{{route('home')}}" class="side-nav-link">
                        <i class="ri-dashboard-3-line"></i>
                        <span> Dashboard </span>
                    </a>
                </li>

                @if($user_auth->role_id == 4)
                    <li class="side-nav-item">
                        <a href="{{route('admins.index')}}" class="side-nav-link">
                            <i class="ri-pages-line"></i>
                            <span> Admin </span>
                        </a>
                    </li>
                    <li class="side-nav-item">
                        <a href="{{route('clients.index')}}" class="side-nav-link">
                            <i class="ri-pages-line"></i>
                            <span> Clients </span>
                        </a>
                    </li>
                    <li class="side-nav-item">
                        <a href="{{route('agents.index')}}" class="side-nav-link">
                            <i class="ri-pages-line"></i>
                            <span> Agents </span>
                        </a>
                    </li>
                    <li class="side-nav-item">
                        <a href="{{route('practitioner.index')}}" class="side-nav-link">
                            <i class="ri-nurse-line"></i>
                            <span> Praticiens </span>
                        </a>
                    </li>

                    <li class="side-nav-item">
                        <a class="side-nav-link" href="{{ route('villes.index') }}">
                            <i class="fas fa-fw fa-map-marker-alt"></i>
                            <span>Villes</span>
                        </a>
                    </li>
                @endif

                @if(in_array($user_auth->role_id, [1, 4]))
                    <li class="side-nav-item">
                        <a href="{{ route('blog.index') }}" class="side-nav-link">
                            <i class="ri-article-line"></i>
                            <span> Blog </span>
                        </a>
                    </li>
                @endif

                <li class="side-nav-item">
                    <a href="{{route('newletter.index')}}" class="side-nav-link">
                        <i class="ri-mail-line"></i>
                        <span> Newsletters </span>
                    </a>
                </li>

                <li class="side-nav-item">

                    <a href="{{route('laboratories.index')}}" class="side-nav-link">
                        <i class="ri-group-2-line"></i>
                        <span> Laboratoires </span>
                    </a>

                </li>

                <li class="side-nav-item">

                    <a href="{{route('commandes.index')}}" class="side-nav-link">
                        <i class="ri-donut-chart-fill"></i>
                        <span> Commandes </span>
                    </a>

                </li>

                @if($user_auth->role_id == 4)
                    <li class="side-nav-item">
                        <a href="{{route('paiements.index')}}" class="side-nav-link">
                            <i class="ri-bank-card-line"></i>
                            <span> Paiements </span>
                        </a>
                    </li>
                    <li class="side-nav-item">
                        <a href="{{route('wallets.index')}}" class="side-nav-link">
                            <i class="ri-wallet-3-line"></i>
                            <span> Portefeuilles </span>
                        </a>
                    </li>
                @endif

                <li class="side-nav-item  logout-link">
                    <a href="{{url('/logout')}}" class="side-nav-link  py-1">
                        <i class="fas fa-sign-out-alt"></i>
                        <span class="'logout">Déconnexion</span>
                    </a>
                </li>
            </ul>
            <!--- End Sidemenu -->

            <div class="clearfix"></div>
        </div>
    </div>

    <div class="content-page">
        <div class="content">


            @yield('page_content')


        </div>
        <!-- content -->

        <!-- Footer Start -->
        <footer class="footer">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12 text-center">
                        <script>document.write(new Date().getFullYear())</script> © Walab - Designed by<b>Nerdx</b>
                    </div>
                </div>
            </div>
        </footer>
        <!-- end Footer -->

        <!-- Modal for Description -->
        <div class="modal fade" id="createFrais" tabindex="-1" aria-labelledby="createFraisLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title" id="createFraisLabel">Configuration des Tarifs</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form method="POST" action="{{ route('frais') }}" class="form-horizontal">
                        @csrf
                        <div class="modal-body">

                            <!-- Frais de base -->
                            <div class="mb-3">
                                <label for="frais" class="form-label fw-bold">Frais de déplacement (Base)</label>
                                <div class="input-group">
                                    <input type="number" required id="frais" name="frais" class="form-control" value="{{ $fraisMontant }}">
                                    <span class="input-group-text">FCFA</span>
                                </div>
                                <small class="text-muted">Prix pour un seul laboratoire.</small>
                            </div>

                            <hr>

                            <!-- Pourcentage Majoration -->
                            <div class="mb-3">
                                <label for="pourcentage" class="form-label fw-bold">Majoration par Labo Supplémentaire</label>
                                <div class="input-group">
                                    <input type="number" required id="pourcentage" name="pourcentage" class="form-control" value="{{ $fraisPourcentage }}" min="0" max="100">
                                    <span class="input-group-text">%</span>
                                </div>
                                <small class="text-muted">Exemple: 40% (Ajoute 40% du prix de base pour chaque labo en plus).</small>
                            </div>

                        </div>
                        <div class="modal-footer bg-light">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                            <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


</div>
<!-- END wrapper -->


<!-- Vendor js -->
<script src="{{asset('assets/js/vendor.min.js')}}"></script>

<!-- Daterangepicker js -->
<script src="{{asset('assets/vendor/daterangepicker/moment.min.js')}}"></script>
<script src="{{asset('assets/vendor/daterangepicker/daterangepicker.js')}}"></script>

<!-- Apex Charts js -->
<script src="{{asset('assets/vendor/apexcharts/apexcharts.min.js')}}"></script>

<!-- Vector Map js -->
<script src="{{asset('assets/vendor/admin-resources/jquery.vectormap/jquery-jvectormap-1.2.2.min.js')}}"></script>
<script
    src="{{asset('assets/vendor/admin-resources/jquery.vectormap/maps/jquery-jvectormap-world-mill-en.js')}}"></script>

<!-- Dashboard App js -->
<script src="{{asset('assets/js/pages/dashboard.js')}}"></script>


<!-- App js -->
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

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script type="text/javascript">

    // Safety: guard event listeners to avoid errors when elements are not present on the page
    (function () {
        const myForm = document.getElementById('myForm');
        if (myForm) {
            myForm.addEventListener('submit', function(event) {
                const submitButton = document.getElementById('myButton');
                if (submitButton) {
                    const spinner = submitButton.querySelector('.spinner-border');
                    if (spinner) spinner.classList.remove('d-none');
                    submitButton.disabled = true;
                    submitButton.innerHTML = 'En cours...';
                }
            });
        }

        const myForm1 = document.getElementById('myForm1');
        if (myForm1) {
            myForm1.addEventListener('submit', function(event) {
                const submitButton1 = document.getElementById('myButton1');
                if (submitButton1) {
                    submitButton1.disabled = true;
                    submitButton1.innerHTML = 'En cours...';
                }
            });
        }

        let tableBody = document.getElementById('table-body');

        // Fonction de recherche
        function performSearch() {
            const queryEl = document.getElementById('searchInput');
            const tableEl = document.getElementById('tablesearch');
            const query = queryEl ? queryEl.value : '';
            const table = tableEl ? tableEl.value : '';
            
            if (!query || query.trim() === '') {
                alert('Veuillez entrer un terme de recherche');
                return;
            }
            
            const url = `/search?query=${encodeURIComponent(query)}&table=${encodeURIComponent(table)}`;
            fetch(url)
                .then(response => response.json())
                .then(data => {
                    if (tableBody) tableBody.innerHTML = '';
                    console.log(data)
                    if (tableBody) {
                        if (data && data.length > 0) {
                            tableBodyFunc(table, data);
                        } else {
                            tableBody.innerHTML = `
                                <tr>
                                    <td colspan="100%" class="text-center py-4">
                                        <i class="ri-search-line" style="font-size: 48px; color: #ccc;"></i>
                                        <p class="text-muted mt-2">Aucun résultat trouvé pour "${query}"</p>
                                    </td>
                                </tr>
                            `;
                        }
                    }
                })
                .catch(error => {
                    console.error('Erreur de recherche:', error);
                    if (tableBody) {
                        tableBody.innerHTML = `
                            <tr>
                                <td colspan="100%" class="text-center py-4">
                                    <i class="ri-error-warning-line" style="font-size: 48px; color: #ff6b6b;"></i>
                                    <p class="text-danger mt-2">Erreur lors de la recherche</p>
                                </td>
                            </tr>
                        `;
                    }
                });
        }

        const searchButton = document.getElementById('searchButton');
        if (searchButton) {
            searchButton.addEventListener('click', performSearch);
        }
        
        // Recherche avec la touche Entrée
        const searchInput = document.getElementById('searchInput');
        if (searchInput) {
            searchInput.addEventListener('keypress', function(event) {
                if (event.key === 'Enter') {
                    event.preventDefault();
                    performSearch();
                }
            });
        }
    })();


    function tableBodyFunc(table, data){
        if(table == "users_admin"){
            data.forEach(item => {
                let row =
                    `<tr>
                    <td class="text-center">
                        <div class="mb-3">
                            <img id="imagePreview" src="{{asset('')}}${item.url_profil}" alt="profile" class="avatar-sm rounded-circle shadow border border-primary">
                        </div>
                    </td>
                    <td class="text-center">${item.firstname}</td>
                    <td class="text-center">${item.lastname}</td>
                    <td class="text-center">${item.email}</td>
                    <td class="text-center">${item.phone || 'N/A'}</td>
                    <td class="text-center">${item.city || 'N/A'}</td>
                    <td class="text-center">${item.adress || 'N/A'}</td>
                    <td class="text-center">
                        <div class="table-data-feature">
                                    <a href="{{ url('/user/account', '') }}/${item.id}" class="btn btn-dark btn-circle " data-bs-toggle="tooltip" data-bs-placement="top" title="View">
                                        <i class="bi bi-eye text-white"></i>
                                    </a>
                                    <a href="{{ url('/admins', '') }}/${item.id}/edit" class="btn btn-dark btn-circle " data-bs-toggle="tooltip" data-bs-placement="top" title="Edit">
                                        <i class="bi bi-pencil-square text-white"></i>
                                    </a>
                                    <a href="{{ route('admins_destroy', '') }}/${item.id}" class="btn btn-danger btn-circle" data-bs-toggle="tooltip" data-bs-placement="top" title="Delete" onclick="return confirm('Voulez-vous vraiment supprimer cet admin ?')">
                                        <i class="bi bi-trash text-white"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>`;
                tableBody.innerHTML += row;
            });
        }
        else if(table == "users_agent"){
            data.forEach(item => {
                let row =
                    `<tr>
                    <td class="text-center">
                        <div class="mb-3">
                            <img id="imagePreview" src="{{asset('')}}${item.url_profil}" alt="profile" class="avatar-sm rounded-circle shadow border border-primary">
                        </div>
                    </td>
                    <td class="text-center">${item.firstname}</td>
                    <td class="text-center">${item.lastname}</td>
                    <td class="text-center">${item.email}</td>
                    <td class="text-center">${item.phone || 'N/A'}</td>
                    <td class="text-center">${item.city || 'N/A'}</td>
                    <td class="text-center">${item.adress || 'N/A'}</td>
                    <td class="text-center">
                        <div class="table-data-feature">
                                    <a href="{{ url('/user/account', '') }}/${item.id}" class="btn btn-dark btn-circle " data-bs-toggle="tooltip" data-bs-placement="top" title="View">
                                        <i class="bi bi-eye text-white"></i>
                                    </a>
                                    <a href="{{ url('/agents', '') }}/${item.id}/edit" class="btn btn-dark btn-circle " data-bs-toggle="tooltip" data-bs-placement="top" title="Edit">
                                        <i class="bi bi-pencil-square text-white"></i>
                                    </a>
                                    <a href="{{ route('agents_destroy', '') }}/${item.id}" class="btn btn-danger btn-circle" data-bs-toggle="tooltip" data-bs-placement="top" title="Delete" onclick="return confirm('Voulez-vous vraiment supprimer cet agent ?')">
                                        <i class="bi bi-trash text-white"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>`;
                tableBody.innerHTML += row;
            });
        }
        else if(table == "users_client"){
            data.forEach(item => {
                let row =
                    `<tr>
                    <td class="text-center">
                        <div class="mb-3">
                            <img id="imagePreview" src="{{asset('')}}${item.url_profil}" alt="profile" class="avatar-sm rounded-circle shadow border border-primary">
                        </div>
                    </td>
                    <td class="text-center">${item.firstname}</td>
                    <td class="text-center">${item.lastname}</td>
                    <td class="text-center">${item.email}</td>
                    <td class="text-center">${item.phone || 'N/A'}</td>
                    <td class="text-center">${item.city || 'N/A'}</td>
                    <td class="text-center">${item.adress || 'N/A'}</td>
                    <td class="text-center">
                        <div class="table-data-feature">
                                    <a href="{{ url('/user/account', '') }}/${item.id}" class="btn btn-dark btn-circle " data-bs-toggle="tooltip" data-bs-placement="top" title="View">
                                        <i class="bi bi-eye text-white"></i>
                                    </a>
                                    <a href="{{ url('/clients', '') }}/${item.id}/edit" class="btn btn-dark btn-circle " data-bs-toggle="tooltip" data-bs-placement="top" title="Edit">
                                        <i class="bi bi-pencil-square text-white"></i>
                                    </a>
                                    <a href="{{ route('clients_destroy', '') }}/${item.id}" class="btn btn-danger btn-circle" data-bs-toggle="tooltip" data-bs-placement="top" title="Delete" onclick="return confirm('Voulez-vous vraiment supprimer ce client ?')">
                                        <i class="bi bi-trash text-white"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>`;
                tableBody.innerHTML += row;
            });
        }
        else if(table == "users_practitioner"){
            data.forEach(item => {
                let row =
                    `<tr>
                    <td class="text-center">
                        <div class="mb-3">
                            <img id="imagePreview" src="{{asset('')}}${item.url_profil}" alt="profile" class="avatar-sm rounded-circle shadow border border-primary">
                        </div>
                    </td>
                    <td class="text-center">${item.firstname}</td>
                    <td class="text-center">${item.lastname}</td>
                    <td class="text-center">${item.email}</td>
                    <td class="text-center">${item.phone || 'N/A'}</td>
                    <td class="text-center">${item.city || 'N/A'}</td>
                    <td class="text-center">${item.adress || 'N/A'}</td>
                    <td class="text-center">
                        <div class="table-data-feature">
                                    <a href="{{ url('/user/account', '') }}/${item.id}" class="btn btn-dark btn-circle " data-bs-toggle="tooltip" data-bs-placement="top" title="View">
                                        <i class="bi bi-eye text-white"></i>
                                    </a>
                                    <a href="{{ url('/practitioners', '') }}/${item.id}/edit" class="btn btn-dark btn-circle " data-bs-toggle="tooltip" data-bs-placement="top" title="Edit">
                                        <i class="bi bi-pencil-square text-white"></i>
                                    </a>
                                    <a href="{{ route('practitioners_destroy', '') }}/${item.id}" class="btn btn-danger btn-circle" data-bs-toggle="tooltip" data-bs-placement="top" title="Delete" onclick="return confirm('Voulez-vous vraiment supprimer ce praticien ?')">
                                        <i class="bi bi-trash text-white"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>`;
                tableBody.innerHTML += row;
            });
        }
        else if(table == "laboratories"){
            data.forEach(item => {
                let status_badge = '';
                if (item.status === 'active') {
                    status_badge = '<span class="badge bg-success">Actif</span>';
                } else if (item.status === 'suspended') {
                    status_badge = '<span class="badge bg-warning">Suspendu</span>';
                } else {
                    status_badge = '<span class="badge bg-secondary">Inactif</span>';
                }
                
                let row = `
                <tr class="tr-shadow">
                    <td class="text-center"><img src="{{ asset('') }}${item.image}" class="rounded shadow" alt="image" style="width: 50px; height: 50px;"></td>
                    <td class="text-center">${item.name}</td>
                    <td class="text-center">${item.address || 'N/A'}</td>
                    <td class="text-center"><span class="badge bg-info fs-6">${item.pourcentage_commission || 0}%</span></td>
                    <td class="text-center">${status_badge}</td>
                    <td class="text-center">
                        <button type="button" class="btn btn-link" data-toggle="modal" data-target="#descriptionModal${item.id}">
                            <i class="bi bi-eye"></i> Voir
                        </button>
                    </td>
                    <td class="text-center">
                        <div class="table-data-feature">
                            <a href="{{ url('/laboratories', '') }}/${item.id}" class="btn btn-dark btn-circle mx-1" title="View">
                                <i class="bi bi-eye text-white"></i>
                            </a>
                            <a href="{{ url('/laboratories', '') }}/${item.id}/edit" class="btn btn-dark btn-circle mx-1" title="Edit">
                                <i class="bi bi-pencil-square text-white"></i>
                            </a>
                        </div>
                    </td>
                </tr>`;
                tableBody.innerHTML += row;
            });
        }
        else if(table == "examens"){
            data.forEach(item => {
                let row = `
                <tr class="tr-shadow">
                    <td  class="  text-center"><img src="{{ asset('') }}${item.icon}" class="rounded shadow" alt="image_examen" style="width: 50px; height: 50px;"></td>
                    <td  class=" text-center">${item.label}</td>
                    <td  class=" text-center">${item.price} FCFA</td>
                    <td  class=" text-center">
                        <button type="button" class="btn btn-link" data-toggle="modal" data-target="#descriptionModal${item.id}">
                            <i class="bi bi-eye"></i> Lire
                        </button>
                    </td>
                    <td  class=" text-center">
                       <div class="table-data-feature d-flex align-items-center">
                                            <a data-bs-toggle="modal" data-bs-target="#viewModal${item.id}" class="btn btn-dark btn-circle mx-1" data-bs-toggle="tooltip" data-bs-placement="top" title="View">
                                                <i class="bi bi-eye text-white"></i>
                                            </a>
                                            <a data-bs-toggle="modal" data-bs-target="#updateModal${item.id}" class="btn btn-dark btn-circle mx-1" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit">
                                                <i class="bi bi-pencil-square text-white"></i>
                                            </a>
                                            <a data-bs-toggle="modal" data-bs-target="#confirmDeleteModal${item.id}" class="btn btn-danger btn-circle mx-1" data-bs-toggle="tooltip" data-bs-placement="top" title="Delete">
                                                <i class="bi bi-trash text-white">
                                            </a>
                                        </div>
                                    </td>
                                </tr>

                                <!-- Modal View-->
                                <div class="modal fade" id="viewModal${item.id}" tabindex="-1" aria-labelledby="viewModalLabel${item.id}" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Modification</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">

                                        <div class="card-body">
                                                <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">
                                                    @isset($item->icon)
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">
                            <i class="bi bi-image"></i> Image
                        </label>
                        <img src="{{asset('')}}${item.icon}" alt="image" class="img-fluid rounded">
                                                            </div>
                                                        </div>
                                                    @endisset

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">
                            <i class="bi bi-tag"></i> Nom
                        </label>
                        <p class="form-control-static">${item.label}</p>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label class="form-label">
                                                                <i class="bi bi-currency-dollar"></i> Prix
                                                            </label>
                                                            <p class="form-control-static">${item.price} FCFA</p>
                                                        </div>
                                                    </div>

                                                    <div class="row mb-3">
                                                        <div class="col-12">
                                                            <label class="form-label">
                                                                <i class="bi bi-file-earmark-text"></i> Description
                                                            </label>
                                                            <p class="form-control-static">${item.description}</p>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>

                                        </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Modal Modifier-->
                                <div class="modal fade" id="updateModal${item.id}" tabindex="-1" aria-labelledby="updateModalLabel${item.id}" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Modification</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <form method="POST" action="{{route('examen_update', '')}}/${item.id}" enctype="multipart/form-data" class="form-horizontal">
                                                    @csrf
                <div class="row mb-3">
                    <div class="col-md-3">
                        <label for="label" class="form-control-label">Nom</label>
                    </div>
                    <div class="col-md-9">
                        <input type="text" id="label" require="True" name="label" placeholder="Nom" class="form-control rounded-pill focus:ring focus:ring-opacity-50" value="{{old('label')}}">
                                                        </div>
                                                        @if ($errors->has('label'))
                <span class="text-danger">{{ $errors->first('label') }}</span>
                                                        @endif
                </div>
                <div class="row mb-3">
                    <div class="col-md-3">
                        <label for="laboratorie_id" class="form-control-label">Laboratoire</label>
                    </div>
                    <div class="col-md-9">
                        <select require="True" name="laboratorie_id" id="laboratorie_id" class="form-control rounded-pill focus:ring focus:ring-opacity-50" data-toggle="select2">
                            <option>Select</option>
@isset($laboratories)
                @foreach($laboratories as $item_lab)
                <option value="{{$item_lab->id}}">{{$item_lab->name}}</option>
                                                                    @endforeach
                @endisset
                </select>
            </div>
@if ($errors->has('laboratorie_id'))
                <span class="text-danger">{{ $errors->first('laboratorie_id') }}</span>
                                                        @endif
                </div>
                <div class="row mb-3">
                    <div class="col-md-3">
                        <label for="price" class="form-control-label">Prix</label>
                    </div>
                    <div class="col-md-9">
                        <div class="input-group">
                            <input type="number" require="True" id="price" name="price" placeholder="Prix" class="form-control rounded-pill focus:ring focus:ring-opacity-50" value="{{old('price')}}">
                                                                <div class="input-group-append">
                                                                    <span class="input-group-text rounded-pill">FCFA</span>
                                                                </div>
                                                            </div>
                                                            @if ($errors->has('price'))
                <span class="text-danger">{{ $errors->first('price') }}</span>
                                                            @endif
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-3">
                    <label for="icon" class="form-control-label">Image</label>
                </div>
                <div class="col-md-9">
                    <div class="custom-file">
                        <input type="file" id="icon" name="icon" class="custom-file-input">
                    </div>
                </div>
@if ($errors->has('icon'))
                <span class="text-danger">{{ $errors->first('icon') }}</span>
                                                        @endif
                </div>

                <div class="row mb-3">
                    <div class="col-md-3">
                        <label for="description" class="form-control-label">Description</label>
                    </div>
                    <div class="col-md-9">
                        <textarea name="description" id="description" rows="2" placeholder="Description..." class="form-control rounded-pill focus:ring focus:ring-opacity-50">{{old('description')}}</textarea>
                                                        </div>
                                                        @if ($errors->has('description'))
                <span class="text-danger">{{ $errors->first('description') }}</span>
                                                        @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                    <button type="submit" class="btn btn-primary">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>
</div>
</div>

<!-- Modal Suprression-->
<div class="modal fade" id="confirmDeleteModal${item.id}" tabindex="-1" aria-labelledby="confirmDeleteModalLabel${item.id}" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="confirmDeleteModalLabel">Confirmer la suppression</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            Êtes-vous sûr de vouloir supprimer cet élément ?
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                            <a href="{{ route('examen_destroy', '') }}/${item.id}">
                                                <button type="button" class="btn btn-danger">Confirmer</button>
                                            </a>
                                        </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Modal for Description -->
                                <div class="modal fade" id="descriptionModal${item.id}" tabindex="-1" role="dialog" aria-labelledby="descriptionModalLabel${item.id}" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class=" text-primary modal-title" id="descriptionModalLabel${item.id}">Description</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                ${item.description}
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-primary" data-dismiss="modal">D'accord</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
            `;
                tableBody.innerHTML += row;
            });
        } else if(table == "type_bilans"){
            data.forEach(item => {
                let row = `
            <tr class="tr-shadow">
                <td  class="  text-center"><img src="{{ asset('') }}${item.icon}" class="rounded shadow" alt="image_examen" style="width: 50px; height: 50px;"></td>
                <td  class=" text-center">${item.label}</td>
                <td  class=" text-center">${item.price} FCFA</td>
                <td  class=" text-center">
                    <button type="button" class="btn btn-link" data-toggle="modal" data-target="#descriptionModal${item.id}">
                        <i class="bi bi-eye"></i> Lire
                    </button>
                </td>
                <td  class=" text-center">
                    <div class="table-data-feature d-flex align-items-center">
                                            <a href="#viewModal${item.id}" data-bs-toggle="modal" data-bs-target="#viewModal${item.id}" class="btn btn-dark btn-circle mx-1" data-bs-toggle="tooltip" data-bs-placement="top" title="View">
                                                <i class="bi bi-eye text-white"></i>
                                            </a>
                                            <a data-bs-toggle="modal" data-bs-target="#updateModal${item.id}" class="btn btn-dark btn-circle mx-1" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit">
                                                <i class="bi bi-pencil-square text-white"></i>
                                            </a>
                                            <a data-bs-toggle="modal" data-bs-target="#confirmDeleteModal${item.id}" class="btn btn-danger btn-circle mx-1" data-bs-toggle="tooltip" data-bs-placement="top" title="Delete">
                                                <i class="bi bi-trash text-white">
                                            </a>

                                        </div>
                                    </td>
                                </tr>

                                <!-- Modal View-->
                                <div class="modal fade" id="viewModal${item.id}" tabindex="-1" aria-labelledby="viewModalLabel${item.id}" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Modification</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">

                                        <div class="card-body">
                                                <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">
                                                @isset($item->icon)
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">
                            <i class="bi bi-image"></i> Image
                        </label>
                        <img src="{{asset('')}}${item.icon}" alt="image" class="img-fluid rounded">
                                                            </div>
                                                        </div>
                                                    @endisset

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">
                            <i class="bi bi-tag"></i> Nom
                        </label>
                        <p class="form-control-static">${item.label}</p>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label class="form-label">
                                                                <i class="bi bi-currency-dollar"></i> Prix
                                                            </label>
                                                            <p class="form-control-static">${item.price} FCFA</p>
                                                        </div>
                                                    </div>

                                                    <div class="row mb-3">
                                                        <div class="col-12">
                                                            <label class="form-label">
                                                                <i class="bi bi-file-earmark-text"></i> Description
                                                            </label>
                                                            <p class="form-control-static">${item.description}</p>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>

                                        </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Modal Modifier-->
                                <div class="modal fade" id="updateModal${item.id}" tabindex="-1" aria-labelledby="updateModalLabel${item.id}" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Modification</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <form method="POST" action="{{route('bilan_update', '')}}/${item.id}" enctype="multipart/form-data" class="form-horizontal">
                                                    @csrf
                <div class="row mb-3">
                    <div class="col-md-3">
                        <label for="label" class="form-control-label">Nom</label>
                    </div>
                    <div class="col-md-9">
                        <input type="text" id="label" require="True" name="label" placeholder="Nom" class="form-control rounded-pill focus:ring focus:ring-opacity-50" value="{{old('label')}}">
                                                        </div>
                                                        @if ($errors->has('label'))
                <span class="text-danger">{{ $errors->first('label') }}</span>
                                                        @endif
                </div>
                <div class="row mb-3">
                    <div class="col-md-3">
                        <label for="laboratorie_id" class="form-control-label">Laboratoire</label>
                    </div>
                    <div class="col-md-9">
                        <select require="True" name="laboratorie_id" id="laboratorie_id" class="form-control rounded-pill focus:ring focus:ring-opacity-50" data-toggle="select2">
                            <option>Select</option>
@isset($laboratories)
                @foreach($laboratories as $item_lab)
                <option value="{{$item_lab->id}}">{{$item_lab->name}}</option>
                                                                    @endforeach
                @endisset
                </select>
            </div>
@if ($errors->has('laboratorie_id'))
                <span class="text-danger">{{ $errors->first('laboratorie_id') }}</span>
                                                        @endif
                </div>
                <div class="row mb-3">
                    <div class="col-md-3">
                        <label for="price" class="form-control-label">Prix</label>
                    </div>
                    <div class="col-md-9">
                        <div class="input-group">
                            <input type="number" require="True" id="price" name="price" placeholder="Prix" class="form-control rounded-pill focus:ring focus:ring-opacity-50" value="{{old('price')}}">
                                                                <div class="input-group-append">
                                                                    <span class="input-group-text rounded-pill">FCFA</span>
                                                                </div>
                                                            </div>
                                                            @if ($errors->has('price'))
                <span class="text-danger">{{ $errors->first('price') }}</span>
                                                            @endif
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-3">
                    <label for="icon" class="form-control-label">Image</label>
                </div>
                <div class="col-md-9">
                    <div class="custom-file">
                        <input type="file" id="icon" name="icon" class="custom-file-input">
                    </div>
                </div>
@if ($errors->has('icon'))
                <span class="text-danger">{{ $errors->first('icon') }}</span>
                                                        @endif
                </div>

                <div class="row mb-3">
                    <div class="col-md-3">
                        <label for="description" class="form-control-label">Description</label>
                    </div>
                    <div class="col-md-9">
                        <textarea name="description" id="description" rows="2" placeholder="Description..." class="form-control rounded-pill focus:ring focus:ring-opacity-50">{{old('description')}}</textarea>
                                                        </div>
                                                        @if ($errors->has('description'))
                <span class="text-danger">{{ $errors->first('description') }}</span>
                                                        @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                    <button type="submit" class="btn btn-primary">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>
</div>
</div>

<!-- Modal Suprression-->
<div class="modal fade" id="confirmDeleteModal${item.id}" tabindex="-1" aria-labelledby="confirmDeleteModalLabel${item.id}" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="confirmDeleteModalLabel">Confirmer la suppression</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            Êtes-vous sûr de vouloir supprimer cet élément ?
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                            <a href="{{ route("bilan_destroy", "") }}/${item.id}">
                                                <button type="button" class="btn btn-danger">Confirmer</button>
                                            </a>
                                        </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Modal for Description -->
                                <div class="modal fade" id="descriptionModal${item.id}" tabindex="-1" role="dialog" aria-labelledby="descriptionModalLabel${item.id}" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class=" text-primary modal-title" id="descriptionModalLabel${item.id}">Description</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                ${item.description}
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-primary" data-dismiss="modal"> D\'accord</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
            `;
                tableBody.innerHTML += row;
            });
        } else if(table == "laboratories"){
            data.forEach(item => {
                let row = `
            <tr class="tr-shadow">
                <td  class=" text-center">${item.name}</td>
                <td  class="text-wrap text-center">${item.address}</td>
                <td  class=" text-center">
                    <button type="button" class="btn btn-link" data-toggle="modal" data-target="#descriptionModal${item.id}">
                        <i class="bi bi-eye"></i> Lire
                    </button>
                </td>
                <td  class=" text-center">
                    <div class="table-data-feature d-flex align-items-center">
                        <a href="{{url("laboratoire/bilan", "")}}/${item.id}" class="btn btn-dark btn-circle mx-1 " data-bs-toggle="tooltip" data-bs-placement="top" title="Bilans">
                            <i class="bi bi-list-check text-white"></i>
                        </a>
                        <a href="{{url("laboratoire/examen", "")}}/${item.id}" class="btn btn-dark btn-circle mx-1 " data-bs-toggle="tooltip" data-bs-placement="top" title="Examens">
                            <i class="bi bi-list-check text-white"></i>
                        </a>
                        <a data-bs-toggle="modal" data-bs-target="#viewModal${item.id}" class="btn btn-dark btn-circle mx-1" data-bs-toggle="tooltip" data-bs-placement="top" title="View">
                                    <i class="bi bi-eye text-white"></i>
                                </a>
                                <a data-bs-toggle="modal" data-bs-target="#updateModal${item.id}" class="btn btn-dark btn-circle mx-1" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit">
                                    <i class="bi bi-pencil-square text-white"></i>
                                </a>
                                <a data-bs-toggle="modal" data-bs-target="#confirmDeleteModal${item.id}" class="btn btn-danger btn-circle mx-1" data-bs-toggle="tooltip" data-bs-placement="top" title="Delete">
                                    <i class="bi bi-trash text-white">
                                </a>
                            </div>
                        </td>
                    </tr>

                                    <!-- Modal View-->
                                    <div class="modal fade" id="viewModal${item.id}" tabindex="-1" aria-labelledby="viewModalLabel${item.id}" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Modification</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">

                                            <div class="card-body">
                                                <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">

                                                    <div class="row mb-3">
                                                        <div class="col-md-6">
                                                            <label class="form-label">
                                                                <i class="bi bi-tag"></i> Nom
                                                            </label>
                                                            <p class="form-control-static">${item.name}</p>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label class="form-label">
                                                                <i class="bi bi-currency-dollar"></i> Addresse
                                                            </label>
                                                            <p class="form-control-static">${item.address}</p>
                                                        </div>
                                                    </div>

                                                    <div class="row mb-3">
                                                        <div class="col-12">
                                                            <label class="form-label">
                                                                <i class="bi bi-file-earmark-text"></i> Description
                                                            </label>
                                                            <p class="form-control-static">${item.description}</p>
                                                        </div>
                                                    </div>
                                                </form>
                                                </div>

                                            </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="modal fade" id="updateModal${item.id}" tabindex="-1" aria-labelledby="updateModalLabel${item.id}" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel">Modification</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <form method="POST" action="{{route("laboratoire_update", "")}}/${item.id}" enctype="multipart/form-data" class="form-horizontal">
                                                            @csrf
                <div class="row mb-3">
                    <div class="col-md-3">
                        <label for="name" class="form-control-label">Nom</label>
                    </div>
                    <div class="col-md-9">
                        <input type="text" id="name" require="True" name="name" placeholder="Nom" class="form-control rounded-pill focus:ring focus:ring-opacity-50" value="${item.name}">
                                                                </div>
                                                            </div>

                                                            <div class="row mb-3">
                                                                <div class="col-md-3">
                                                                    <label for="address" class="form-control-label">Addresse</label>
                                                                </div>
                                                                <div class="col-md-9">
                                                                    <div class="input-group">
                                                                        <input type="text" id="address" require="True" name="address" placeholder="address" class="form-control rounded-pill focus:ring focus:ring-opacity-50" value="${item.address}">
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="row mb-3">
                                                                <div class="col-md-3">
                                                                    <label for="description" class="form-control-label">Description</label>
                                                                </div>
                                                                <div class="col-md-9">
                                                                    <textarea name="description" id="description" rows="2" placeholder="Description..." class="form-control rounded-pill focus:ring focus:ring-opacity-50">${item.description}</textarea>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                                                            <button type="submit" class="btn btn-primary">Enregistrer</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Modal Suprression-->
                                    <div class="modal fade" id="confirmDeleteModal${item.id}" tabindex="-1" aria-labelledby="confirmDeleteModalLabel${item.id}" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="confirmDeleteModalLabel">Confirmer la suppression</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                Êtes-vous sûr de vouloir supprimer cet élément ?
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                                <a href="{{ route("laboratoire_destroy", "") }}/${item.id}">
                                                    <button type="button" class="btn btn-danger">Confirmer</button>
                                                </a>
                                            </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Modal for Description -->
                                    <div class="modal fade" id="descriptionModal${item.id}" tabindex="-1" role="dialog" aria-labelledby="descriptionModalLabel${item.id}" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class=" text-primary modal-title" id="descriptionModalLabel${item.id}">Description</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    ${item.description}
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-primary" data-dismiss="modal">D\'accord</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
            `;
                tableBody.innerHTML += row;
            });
        } else if(table == "commandes"){
            data.forEach(item => {
                let row = `
            <tr class="tr-shadow">
                <td  class=" text-center">${item.code}</td>
                <td  class=" text-center">${item.type}</td>
                <td  class=" text-center">${item.adress}</td>
                <td  class=" text-primary text-center">${item.statut}</td>
                 <td class="text-center">
                    ${item.client_id ? `${item.client.firstname} ${item.client.lastname}` : ""}
                </td>
                 <td class="text-wrap text-center ${!item.agent_id ? "text-primary" : ""}">
                    ${item.agent_id ? `${item.agent.firstname} ${item.agent.lastname}` : "<span>En attente d\'assignation</span>"}
                </td>
                    <td class="text-center">${item.examen_id ? item.examen.label : ""}  ${item.type_bilan_id ? item.type_bilan.label : ""}
                </td>

                <td  class=" text-center">
                    <div class="table-data-feature">
                    `
                if(item.statut == "Terminer"){
                    row = row + `
                    <a data-bs-toggle="modal" data-bs-target="#resultatview${item.id}"
                        class="btn btn-dark btn-circle mx-1" data-bs-toggle="tooltip"
                        data-bs-placement="top" title="Voir Résultat">
                        <i class="bi bi-arrow-bar-down text-white"></i>
                    </a>
                    `
                }

                if(item.statut != "En attente"){
                    row = row + `
                    <a data-bs-toggle="modal" data-bs-target="#resultatcreate${item.id}"
                        class="btn btn-dark btn-circle mx-1" data-bs-toggle="tooltip"
                        data-bs-placement="top" title="Créer Resultat">
                        <i class="bi bi-arrow-bar-up text-white"></i>
                    </a>
                    `
                }

                row = row +   `
                            <a data-bs-toggle="modal" data-bs-target="#assigner${item.id}" class="btn btn-dark btn-circle" data-bs-toggle="tooltip" data-bs-placement="top" title="Assigner Agent">
                                <i class="bi bi-person-badge text-white"></i>
                            </a>

                            <a data-bs-toggle="modal" data-bs-target="#viewModal${item.id}" class="btn btn-dark btn-circle " data-bs-toggle="tooltip" data-bs-placement="top" title="View">
                                <i class="bi bi-eye text-white"></i>
                            </a>
                        </div>
                    </td>
            </tr>


                                        <!-- Modal Resultat view-->
                                        <div class="modal fade" id="resultatview${item.id}" tabindex="-1" aria-labelledby="resultatviewLabel${item.id}" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel">Résultat de la commande</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">
                                                            <div class="row mb-3">
                                                                <div class="col-md-6">
                                                                    <label for="code_commande" class="form-label">
                                                                        <i class="bi bi-barcode"></i> Code Commande
                                                                    </label>
                                                                    <p class="form-control-static">${item.code}</p>
                                                                </div>

                                                                <div class="col-md-6">
                                                                    <label for="fichier" class="form-label">
                                                                        <i class="bi bi-file-earmark-text"></i> Fichier
                                                                    </label>`
                if(item.statut == "Terminer"){
                    row = row +   `
                                                                        <iframe src="{{ asset("") }}${item.resultat.pdf_url}" width="100%" height="600px" class="rounded shadow-sm"></iframe>
                                                                        `
                }

                row = row +   `
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>

                                        <!-- Modal Resultat create-->
                                        <div class="modal fade" id="resultatcreate${item.id}" tabindex="-1" aria-labelledby="resultatcreateLabel${item.id}" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel">Résultat de la commande</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form method="POST" action="{{route("resultats.store")}}" enctype="multipart/form-data" class="form-horizontal">
                                                            @csrf
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="code_commande" class="form-label">
                            <i class="bi bi-barcode"></i> Code Commande
                        </label>
                        <input type="text" require="True" id="code_commande" name="code_commande" placeholder="Code Commande" class="form-control rounded-pill focus:ring focus:ring-opacity-50" value="${item.code}">
                                                                </div>
                                                                @if ($errors->has("code_commande"))
                <span class="text-danger">{{ $errors->first("code_commande") }}</span>
                                                                @endif

                <div class="col-md-6">
                    <label for="pdf_url" class="form-label">
                        <i class="bi bi-file-earmark-text"></i> Fichier
                    </label>
                    <div class="input-group">
                        <input type="file" require="True" id="pdf_url" name="pdf_url" class="form-control-file">
                    </div>
                </div>
@if ($errors->has("pdf_url"))
                <span class="text-danger">{{ $errors->first("pdf_url") }}</span>
                                                                @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                    <button type="submit" class="btn btn-primary">Enregistrer</button>
                </div>
            </form>
        </div>

    </div>
</div>
</div>

<!-- Modal Assigner-->
<div class="modal fade" id="assigner${item.id}" tabindex="-1" aria-labelledby="assignerLabel${item.id}" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel">Assigner la commande à un Agent</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form method="POST" action="{{url("/commandes/update/assigne")}}" enctype="multipart/form-data" class="form-horizontal">
                                                            @csrf
                <div class="card-header bg-primary text-white rounded-top">
                    <strong>Assigner</strong>
                </div>
                <div class="card shadow-sm rounded-lg">
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-3">
                                <label for="code_commande" class="form-label">Code Commande</label>
                            </div>
                            <div class="col-md-9">
                                <input type="text" id="code_commande" name="code_commande" placeholder="Code Commande" class="form-control rounded-pill shadow-sm focus-ring" value="${item.code}">
                                                                            <input type="text" id="id" name="id" placeholder="id" hidden class="form-control rounded-pill" value="${item.id}">
                                                                        </div>
                                                                    </div>

                                                                    <div class="row mb-3">
                                                                        <div class="col-md-3">
                                                                            <label for="agent_id" class="form-label">Agent</label>
                                                                        </div>
                                                                        <div class="col-md-9">
                                                                            <select name="agent_id" id="agent_id" class="form-select rounded-pill shadow-sm focus-ring">
                                                                                <option value="" disabled selected>Select Agent</option>
                                                                                @isset($agents)
                @foreach($agents as $item_ag)
                <option value="{{ $item_ag->id }}">{{ $item_ag->firstname }} {{ $item_ag->lastname }}</option>
                                                                                    @endforeach
                @endisset
                </select>
            </div>
        </div>
    </div>

    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
        <button type="submit" class="btn btn-primary">Enregistrer</button>
    </div>

</div>
</form>
</div>

</div>
</div>
</div>

<!-- Modal View-->
<div class="modal fade" id="viewModal${item.id}" tabindex="-1" aria-labelledby="viewModalLabel${item.id}" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel">Modification</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">

                                                <div class="card-body">
                                                    <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">
                                                        <div class="row mb-3">

                                                            <div class="col-md-6">
                                                                <label for="code" class="form-label">
                                                                    <i class="bi bi-barcode"></i> Code
                                                                </label>
                                                            <p class="form-control-static">${item.code}</p>
                                                            </div>

                                                            @isset($item->agent_id)
                <div class="col-md-6">
                    <label for="agent" class="form-label">
                        <i class="bi bi-person-check"></i> Agent
                    </label> `
                if(item.agent){
                    row = row +   `
                                                                            <p class="form-control-static">${item.agent.firstname} ${item.agent.lastname}</p>
                                                                        `
                }

                row = row +   `
                                                                </div>
                                                            @endisset
                </div>

                    <div class="row mb-3">
@isset($item->client_id)
                <div class="col-md-6">
                            <label for="client" class="form-label">
                                <i class="bi bi-person-fill"></i> Client
                            </label>
`
                if(item.client){
                    row = row +   `
                                                                            <p class="form-control-static">${item.client.firstname} ${item.client.lastname}</p>
                                                                        `
                }

                row = row +   `
                                                                    </div>
                                                                @endisset

                @isset($item)
                <div class="col-md-6">
                    <label for="type" class="form-label">
                        <i class="bi bi-file-earmark-text"></i> Type
                    </label>
                    <p class="form-control-static">${item.type}</p>
                                                                    </div>
                                                                @endisset
                </div>

                <div class="row mb-3">
@isset($item->examen_id)
                <div class="col-md-6">
                    <label for="examen" class="form-label">
                        <i class="bi bi-file-text"></i> Examen
                    </label>
`
                if(item.examen){
                    row = row +   `
                                                                            <p class="form-control-static">${item.examen.label}</p>
                                                                        `
                }

                row = row +   `

                                                                    </div>
                                                                @endisset

                @isset($item->type_bilan_id)
                <div class="col-md-6">
                    <label for="type_bilan" class="form-label">
                        <i class="bi bi-file-earmark-medical"></i> Type de bilan
                    </label>
`
                if(item.type_bilan){
                    row = row +   `
                                                                           <p class="form-control-static">${item.type_bilan.label}</p>
                                                                        `
                }

                row = row +   `

                                                                    </div>
                                                                @endisset
                </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="statut" class="form-label">
                                <i class="bi bi-check-circle"></i> Statut
                            </label>
                            <p class="form-control-static">${item.statut}</p>
                                                                    </div>

                                                                    <div class="col-md-6">
                                                                        <label for="adress" class="form-label">
                                                                            <i class="bi bi-house-door"></i> Adresse
                                                                        </label>
                                                                        <p class="form-control-static">${item.adress}</p>
                                                                    </div>
                                                                </div>
                                                        </form>
                                                    </div>

                                                </div>
                                                </div>
                                            </div>
                                        </div>
            `;
                tableBody.innerHTML += row;
            });
        }

    }
</script>

<!-- Table Search Script - Recherche côté client sans perdre la pagination -->
<script src="{{asset('assets/js/table-search.js')}}"></script>

@stack('scripts')
</body>

</html>

