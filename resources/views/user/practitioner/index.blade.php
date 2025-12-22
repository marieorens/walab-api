@extends('layout')
    @section('page_content')

        <!-- Start Content-->
            <div class="container-fluid">

                <!-- start page title -->
                <div class="row">
                    <div class="col-12">
                        <div class="page-title-box">
                            <div class="page-title-right">
                                <ol class="breadcrumb m-0">
                                    <li class="breadcrumb-item"><a href="#">Walab</a></li>
                                    <li class="breadcrumb-item"><a href="{{route('home')}}">Dashboards</a></li>
                                    <li class="breadcrumb-item active">Praticiens</li>
                                </ol>
                            </div>
                            <h4 class="page-title ">Praticiens</h4>
                        </div>
                    </div>
                </div>
                <!-- end page title -->

                <div class="row">
                    <div class="d-flex justify-content-between align-items-center w-100">
                        <div class="table-data__tool">
                            <div class="table-data__tool-right">
                                <a href="{{route('practitioner.create')}}">
                                    <button class="btn btn-primary mb-3">
                                        <i class="zmdi zmdi-plus"></i> Ajouter un praticien
                                    </button>
                                </a>
                            </div>
                        </div>

                        <div class="ms-auto input-group rounded p-2 bg-light mb-3" style="width:30%">
                            <input type="text" hidden value="users_practitioner" id="tablesearch">
                            <input type="search" class="form-control rounded me-2" placeholder="Rechercher un praticien..." id="searchInput" name="query">
                            <button id="searchButton" class="btn btn-primary pr-4 rounded">
                                <span class="ri-search-line"></span>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- SECTION: Praticiens Validés -->
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body p-0">
                                <div class="p-3">
                                    <div class="card-widgets">
                                        <a href="{{route('exporter.practitioner')}}"><i class="mdi mdi-microsoft-excel" style="color: #008000;"></i></a>
                                        <a data-bs-toggle="collapse" href="#approved-collapse" role="button" aria-expanded="true" aria-controls="approved-collapse"><i class="ri-subtract-line"></i></a>
                                        <a href="#" data-bs-toggle="remove"><i class="ri-close-line"></i></a>
                                    </div>
                                    <h5 class="header-title mb-0">
                                        <i class="ri-shield-check-line text-success"></i> Praticiens Validés 
                                        <span class="badge bg-success">{{ count($approvedPractitioners) }}</span>
                                    </h5>
                                </div>

                                @if (session('success'))
                                    <div class="alert alert-success" role="alert" style="margin-left: auto; margin-right: auto; max-width: fit-content;">
                                        <strong>{{ session('success') }}</strong>
                                    </div>
                                @endif
                                @if (session('error'))
                                    <div class="alert alert-danger" role="alert" style="margin-left: auto; margin-right: auto; max-width: fit-content;">
                                        <strong>{{ session('error') }}</strong>
                                    </div>
                                @endif

                                <div id="approved-collapse" class="collapse show">
                                    @if(count($approvedPractitioners) > 0)
                                    <div class="table-responsive table-data">
                                        <table class="table table-nowrap table-hover mb-0">
                                            <thead class="bg-success">
                                                <tr>  
                                                    <th class="text-white text-center">Profile</th>
                                                    <th class="text-white text-center">Nom</th>
                                                    <th class="text-white text-center">Prénom</th>
                                                    <th class="text-white text-center">Email</th>
                                                    <th class="text-white text-center">Téléphone</th>
                                                    <th class="text-white text-center">N° Ordre</th>
                                                    <th class="text-white text-center">Profession</th>
                                                    <th class="text-white text-center">Certificat</th>
                                                    <th class="text-white text-center">Statut</th>
                                                    <th class="text-white text-center">Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($approvedPractitioners as $item)
                                                    <tr>
                                                        <td class="text-center">
                                                            <div class="mb-3">
                                                                <img src="{{asset($item->user->url_profil) }}" alt="profile" class="avatar-sm rounded-circle shadow border border-primary">
                                                            </div>
                                                        </td>
                                                        <td class="text-center">{{$item->user->firstname}}</td>
                                                        <td class="text-center">{{$item->user->lastname}}</td>
                                                        <td class="text-center">{{$item->user->email}}</td>
                                                        <td class="text-center">{{$item->user->phone}}</td>
                                                        <td class="text-center">{{$item->order_number}}</td>
                                                        <td class="text-center">
                                                            @switch($item->profession)
                                                                @case('general_practitioner') Médecin Généraliste @break
                                                                @case('specialist_doctor') Médecin Spécialiste @break
                                                                @case('midwife') Sage-femme @break
                                                                @case('nurse') Infirmier(ère) @break
                                                                @case('nursing_assistant') Aide-soignant(e) @break
                                                                @case('physiotherapist') Kinésithérapeute @break
                                                                @case('psychologist') Psychologue @break
                                                                @case('nutritionist') Nutritionniste @break
                                                                @default {{$item->profession}}
                                                            @endswitch
                                                        </td>
                                                        <td class="text-center">
                                                            @if($item->certificate_url)
                                                                <a href="{{ asset('storage/' . $item->certificate_url) }}" target="_blank" class="btn btn-sm btn-info" data-bs-toggle="tooltip" title="Télécharger le certificat">
                                                                    <i class="bi bi-download"></i>
                                                                </a>
                                                            @else
                                                                <span class="text-muted small">Non fourni</span>
                                                            @endif
                                                        </td>
                                                        <td class="text-center">
                                                            @if($item->verification_status == 'pending')
                                                                <span class="badge bg-warning">En attente</span>
                                                            @elseif($item->verification_status == 'approved')
                                                                <span class="badge bg-success">Validé</span>
                                                            @else
                                                                <span class="badge bg-danger">Rejeté</span>
                                                            @endif
                                                            
                                                            @if($item->user->status != 'active')
                                                                <span class="badge bg-secondary">Suspendu</span>
                                                            @endif
                                                        </td>
                                                        <td class="text-center">
                                                            <div class="table-data-feature">
                                                                <form action="{{ route('practitioner.toggle-status', $item->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('{{$item->user->status == 'active' ? 'Suspendre' : 'Activer'}} le compte de {{$item->user->firstname}} {{$item->user->lastname}} ?');">
                                                                    @csrf
                                                                    <button type="submit" class="btn {{ $item->user->status != 'active' ? 'btn-success' : 'btn-danger' }} btn-circle" data-bs-toggle="tooltip" data-bs-placement="top" title="{{($item->user->status == 'active') ? 'Suspendre' : 'Activer'}}">
                                                                        <i class="mdi mdi-account-lock-open text-white"></i>
                                                                    </button>
                                                                </form>
                                                                
                                                                <a href="{{ route('practitioner.show', $item->id) }}" class="btn btn-dark btn-circle" data-bs-toggle="tooltip" data-bs-placement="top" title="Voir le profil complet">
                                                                    <i class="bi bi-eye text-white"></i>
                                                                </a>
                                                                
                                                                <a href="{{ route('practitioner.edit', $item->id) }}" class="btn btn-dark btn-circle" data-bs-toggle="tooltip" data-bs-placement="top" title="Modifier">
                                                                    <i class="bi bi-pencil-square text-white"></i>
                                                                </a>
                                                                
                                                                <form action="{{ route('practitioner.destroy', $item->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('ATTENTION : Supprimer définitivement {{$item->user->firstname}} {{$item->user->lastname}} ? Cette action est irréversible.');">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit" class="btn btn-danger btn-circle" data-bs-toggle="tooltip" data-bs-placement="top" title="Supprimer">
                                                                        <i class="bi bi-trash text-white"></i>
                                                                    </button>
                                                                </form>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    @else
                                        <div class="text-center p-4">
                                            <i class="ri-user-search-line" style="font-size: 48px; color: #ccc;"></i>
                                            <p class="text-muted mt-2">Aucun praticien validé pour le moment</p>
                                        </div>
                                    @endif
                                </div>
                            </div>                           
                        </div>
                    </div>
                </div>

                <!-- SECTION: Inscriptions en Attente de Validation -->
                <div class="row mt-4">
                    <div class="col-lg-12">
                        <div class="card border-warning">
                            <div class="card-body p-0">
                                <div class="p-3 bg-light">
                                    <div class="card-widgets">
                                        <a data-bs-toggle="collapse" href="#pending-collapse" role="button" aria-expanded="true" aria-controls="pending-collapse"><i class="ri-subtract-line"></i></a>
                                    </div>
                                    <h5 class="header-title mb-0">
                                        <i class="ri-time-line text-warning"></i> Inscriptions en Attente de Validation 
                                        <span class="badge bg-warning">{{ count($pendingPractitioners) }}</span>
                                    </h5>
                                    <small class="text-muted">Ces praticiens doivent être validés avant d'accéder à la plateforme</small>
                                </div>

                                <div id="pending-collapse" class="collapse show">
                                    @if(count($pendingPractitioners) > 0)
                                    <div class="table-responsive table-data">
                                        <table class="table table-nowrap table-hover mb-0">
                                            <thead class="bg-warning">
                                                <tr>  
                                                    <th class="text-white text-center">Profile</th>
                                                    <th class="text-white text-center">Nom</th>
                                                    <th class="text-white text-center">Prénom</th>
                                                    <th class="text-white text-center">Email</th>
                                                    <th class="text-white text-center">Téléphone</th>
                                                    <th class="text-white text-center">N° Ordre</th>
                                                    <th class="text-white text-center">Profession</th>
                                                    <th class="text-white text-center">Certificat</th>
                                                    <th class="text-white text-center">Date d'inscription</th>
                                                    <th class="text-white text-center">Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($pendingPractitioners as $item)
                                                    <tr class="table-warning">
                                                        <td class="text-center">
                                                            <div class="mb-3">
                                                                <img src="{{asset($item->user->url_profil) }}" alt="profile" class="avatar-sm rounded-circle shadow border border-warning">
                                                            </div>
                                                        </td>
                                                        <td class="text-center">{{$item->user->firstname}}</td>
                                                        <td class="text-center">{{$item->user->lastname}}</td>
                                                        <td class="text-center">{{$item->user->email}}</td>
                                                        <td class="text-center">{{$item->user->phone}}</td>
                                                        <td class="text-center"><strong>{{$item->order_number}}</strong></td>
                                                        <td class="text-center">
                                                            @switch($item->profession)
                                                                @case('general_practitioner') Médecin Généraliste @break
                                                                @case('specialist_doctor') Médecin Spécialiste @break
                                                                @case('midwife') Sage-femme @break
                                                                @case('nurse') Infirmier(ère) @break
                                                                @case('nursing_assistant') Aide-soignant(e) @break
                                                                @case('physiotherapist') Kinésithérapeute @break
                                                                @case('psychologist') Psychologue @break
                                                                @case('nutritionist') Nutritionniste @break
                                                                @default {{$item->profession}}
                                                            @endswitch
                                                        </td>
                                                        <td class="text-center">
                                                            @if($item->certificate_url)
                                                                <a href="{{ asset('storage/' . $item->certificate_url) }}" target="_blank" class="btn btn-sm btn-warning" data-bs-toggle="tooltip" title="Télécharger le certificat">
                                                                    <i class="bi bi-download text-white"></i>
                                                                </a>
                                                            @else
                                                                <span class="badge bg-danger">Non fourni</span>
                                                            @endif
                                                        </td>
                                                        <td class="text-center">
                                                            <small>{{ \Carbon\Carbon::parse($item->created_at)->format('d/m/Y H:i') }}</small>
                                                        </td>
                                                        <td class="text-center">
                                                            <div class="table-data-feature">
                                                                <form action="{{ route('practitioner.approve', $item->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Valider l\'inscription de {{$item->user->firstname}} {{$item->user->lastname}} ?\n\nCe praticien aura accès à la plateforme.');">
                                                                    @csrf
                                                                    <button type="submit" class="btn btn-success btn-circle" data-bs-toggle="tooltip" data-bs-placement="top" title="Valider l'inscription">
                                                                        <i class="mdi mdi-check-circle text-white"></i>
                                                                    </button>
                                                                </form>
                                                                
                                                                <button onclick="rejectPractitioner({{ $item->id }}, '{{$item->user->firstname}} {{$item->user->lastname}}')" class="btn btn-danger btn-circle" data-bs-toggle="tooltip" data-bs-placement="top" title="Rejeter l'inscription">
                                                                    <i class="mdi mdi-close-circle text-white"></i>
                                                                </button>
                                                                
                                                                <a href="{{ route('practitioner.show', $item->id) }}" class="btn btn-dark btn-circle" data-bs-toggle="tooltip" data-bs-placement="top" title="Voir le profil complet">
                                                                    <i class="bi bi-eye text-white"></i>
                                                                </a>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    @else
                                        <div class="text-center p-4">
                                            <i class="ri-checkbox-circle-line" style="font-size: 48px; color: #28a745;"></i>
                                            <p class="text-success mt-2">Aucune inscription en attente</p>
                                        </div>
                                    @endif
                                </div>
                            </div>                           
                        </div>
                    </div>
                </div>
                
            </div>
        <!-- End Content-->

    @endsection

    @section('scripts')
    <script>
        function rejectPractitioner(id, name) {
            const reason = prompt(`Rejeter l'inscription de ${name}\n\nVeuillez indiquer la raison du rejet:`);
            
            if (reason && reason.trim() !== '') {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/practitioner/${id}/reject`;
                
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                const csrfInput = document.createElement('input');
                csrfInput.type = 'hidden';
                csrfInput.name = '_token';
                csrfInput.value = csrfToken;
                
                const reasonInput = document.createElement('input');
                reasonInput.type = 'hidden';
                reasonInput.name = 'rejection_reason';
                reasonInput.value = reason;
                
                form.appendChild(csrfInput);
                form.appendChild(reasonInput);
                document.body.appendChild(form);
                form.submit();
            } else if (reason !== null) {
                alert('La raison du rejet est obligatoire.');
            }
        }
    </script>
    @endsection
