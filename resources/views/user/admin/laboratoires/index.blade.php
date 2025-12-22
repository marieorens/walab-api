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
                        <li class="breadcrumb-item active">Laboratoires</li>
                    </ol>
                </div>
                <h4 class="page-title"> Laboratoires</h4>
            </div>

        </div>
    </div>
    <!-- end page title -->

    <div class="row">
        <div class="d-flex justify-content-between align-items-center w-100">
            <div class="table-data__tool">
                <div class="table-data__tool-right">
                    @if($user_auth->role_id == 4)
                    <a data-bs-toggle="modal" data-bs-target="#createModal">
                        <button class="btn text-white mb-3" style="background:#667eea;">
                            <i class="zmdi zmdi-plus"></i> Ajouter
                        </button>
                    </a>
                    @endif
                </div>
            </div>

            <div class="ms-auto input-group rounded p-2 bg-light mb-3" style="width:30%">
                <input type="text" hidden value="laboratories" id="tablesearch">
                <input type="search" class="form-control rounded me-2" placeholder="Tape ici..." id="searchInput" name="query">
                <button id="searchButton" class="btn pr-4 rounded text-white me-2" style="background:#667eea;">
                    <span class="ri-search-line"></span>
                </button>
                <button onclick="window.location.reload()" class="btn pr-4 rounded text-white" style="background:#667eea;" data-bs-toggle="tooltip" data-bs-placement="top" title="Rafraîchir la liste">
                    <span class="ri-refresh-line"></span>
                </button>
            </div>
        </div>
    </div>

            <div class="row">
                <div class="col-lg-12">
                    <!-- Labos en attente -->
                    @if($laboratoires_pending->count() > 0)
                    <div class="card mb-3">
                        <div class="card-body p-0">
                           <div class="p-3">
                                <div class="card-widgets">
                                    <a href="{{route('exporter.laboratoire')}}"><i class="mdi mdi-microsoft-excel" style="color: #008000;"></i></a>
                                    <a data-bs-toggle="collapse" href="#pending-collapse" role="button" aria-expanded="true" aria-controls="pending-collapse"><i class="ri-subtract-line"></i></a>
                                    <a href="#" data-bs-toggle="remove"><i class="ri-close-line"></i></a>
                                </div>
                                <h5 class="header-title mb-0 text-warning">
                                    <i class="ri-time-line"></i> Laboratoires en attente de validation ({{ $laboratoires_pending->count() }})
                                </h5>
                            </div>

                    <div id="pending-collapse" class="collapse show">
                        <div class="table-responsive table-data">
                            <table class="table table-nowrap table-hover mb-2">
                                <thead class="bg-warning">
                                    <tr>
                                        <th class="text-white text-center">Image</th>
                                        <th class="text-white text-center">Nom</th>
                                        <th class="text-white text-center">Responsable</th>
                                        <th class="text-white text-center">Email</th>
                                        <th class="text-white text-center">Commission (%)</th>
                                        <th class="text-white text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($laboratoires_pending as $pending)
                                    <tr class="tr-shadow">
                                        <td class="text-center"><img src="{{ asset($pending->image) }}" class="rounded shadow" alt="image" style="width: 50px; height: 50px;"></td>
                                        <td class="text-center">{{$pending->name}}</td>
                                        <td class="text-center">{{$pending->user->firstname}} {{$pending->user->lastname}}</td>
                                        <td class="text-center">{{$pending->user->email}}</td>
                                        <td class="text-center"><span class="badge bg-info fs-6">{{$pending->pourcentage_commission}}%</span></td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-sm text-white" style="background:#667eea;" data-bs-toggle="modal" data-bs-target="#detailModal{{ $pending->id }}">
                                                <i class="bi bi-eye"></i> Voir détails
                                            </button>
                                            <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#validateModal{{ $pending->id }}">
                                                <i class="bi bi-check-circle"></i> Valider
                                            </button>
                                        </td>
                                    </tr>

                                    <!-- Modal détails -->
                                    <div class="modal fade" id="detailModal{{ $pending->id }}" tabindex="-1">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header bg-warning">
                                                    <h5 class="modal-title text-white">Détails du Laboratoire en attente</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <h6 style="color:#667eea;">Informations du Responsable</h6>
                                                    <div class="row mb-3">
                                                        <div class="col-md-6">
                                                            <strong>Prénom:</strong> {{$pending->user->firstname}}
                                                        </div>
                                                        <div class="col-md-6">
                                                            <strong>Nom:</strong> {{$pending->user->lastname}}
                                                        </div>
                                                    </div>
                                                    <div class="row mb-3">
                                                        <div class="col-md-6">
                                                            <strong>Email:</strong> {{$pending->user->email}}
                                                        </div>
                                                        <div class="col-md-6">
                                                            <strong>Téléphone:</strong> {{$pending->user->phone}}
                                                        </div>
                                                    </div>

                                                    <hr>
                                                    <h6 style="color:#667eea;">Informations du Laboratoire</h6>
                                                    <div class="row mb-3">
                                                        <div class="col-md-12">
                                                            <strong>Nom:</strong> {{$pending->name}}
                                                        </div>
                                                    </div>
                                                    <div class="row mb-3">
                                                        <div class="col-md-12">
                                                            <strong>Adresse:</strong> {{$pending->address}}
                                                        </div>
                                                    </div>
                                                    <div class="row mb-3">
                                                        <div class="col-md-12">
                                                            <strong>Description:</strong><br>
                                                            {{$pending->description ?? 'Aucune description'}}
                                                        </div>
                                                    </div>
                                                    <div class="row mb-3">
                                                        <div class="col-md-12">
                                                            <strong>Image:</strong><br>
                                                            <img src="{{asset($pending->image)}}" alt="image" class="img-fluid rounded" style="max-height: 300px;">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                                                    <button type="button" class="btn btn-success" data-bs-dismiss="modal" data-bs-toggle="modal" data-bs-target="#validateModal{{ $pending->id }}">
                                                        <i class="bi bi-check-circle"></i> Valider ce laboratoire
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Modal Validation -->
                                    <div class="modal fade" id="validateModal{{ $pending->id }}" tabindex="-1" aria-labelledby="validateModalLabel{{ $pending->id }}" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header bg-success text-white">
                                                    <h5 class="modal-title" id="validateModalLabel{{ $pending->id }}">
                                                        <i class="bi bi-check-circle me-2"></i>Confirmer la validation
                                                    </h5>
                                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <p class="mb-3">Êtes-vous sûr de vouloir valider ce laboratoire ?</p>
                                                    <div class="alert alert-info">
                                                        <strong>Laboratoire :</strong> {{ $pending->name }}<br>
                                                        <strong>Responsable :</strong> {{ $pending->user->firstname }} {{ $pending->user->lastname }}
                                                    </div>
                                                    <p class="text-muted small mb-0">
                                                  Une fois validé, le laboratoire pourra accéder à son espace et commencer à utiliser les services.
                                                    </p>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                                    <a href="{{route('laboratoire_valider', $pending->id)}}" class="btn btn-success">
                                                        <i class="bi bi-check-circle me-1"></i>Confirmer la validation
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                        </div>
                    </div>
                    @endif

                    <!-- Todo-->
                    <div class="card">
                        <div class="card-body p-0">
                           <div class="p-3">
                                <div class="card-widgets">
                                    <a href="{{route('exporter.laboratoire')}}"><i class="mdi mdi-microsoft-excel" style="color: #008000;"></i></a>
                                    <a data-bs-toggle="collapse" href="#yearly-sales-collapse" role="button" aria-expanded="false" aria-controls="yearly-sales-collapse"><i class="ri-subtract-line"></i></a>
                                    <a href="#" data-bs-toggle="remove"><i class="ri-close-line"></i></a>
                                </div>
                                <h5 class="header-title mb-0">Liste des Laboratoires</h5>
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

                    <div id="yearly-sales-collapse" class="collapse show">

                        <div class="table-responsive table-data">
                            <table class="table table-nowrap table-hover mb-2">
                                <thead style="background:#667eea;">
                                    <tr>
                                    <th class="text-white text-center">Image</th>
                                    <th class="text-white text-center">Nom</th>
                                    <th class="text-white text-center">Adresse</th>
                                    <th class="text-white text-center">Commission (%)</th>
                                    <th class="text-white text-center">Statut</th>
                                    <th class="text-white text-center">Description</th>
                                    <th class="text-white text-center">Actions</th>
                         
                                </tr>
                                        </thead>
                                        <tbody id="table-body">
                                        @foreach($laboratoires as $item)
                                        <tr class="tr-shadow">
                                            <td  class="  text-center"><img src="{{ asset($item->image) }}" class="rounded shadow" alt="image laboratoire" style="width: 50px; height: 50px;"></td>
                                            <td  class="text-center">{{$item->name}}</td>
                                            <td  class="text-center">{{$item->address}}</td>
                                            <td  class="text-center"><span class="badge bg-primary fs-6">{{$item->pourcentage_commission}}%</span></td>
                                            <td  class="text-center">
                                                @if($item->user && $item->user->status == 'active')
                                                    <span class="badge bg-success fs-6">
                                                        <i class="bi bi-check-circle me-1"></i>Actif
                                                    </span>
                                                @elseif($item->user && $item->user->status == 'suspended')
                                                    <span class="badge bg-danger fs-6">
                                                        <i class="bi bi-x-circle me-1"></i>Suspendu
                                                    </span>
                                                @elseif(!$item->user)
                                                    <span class="badge bg-warning fs-6">
                                                        <i class="bi bi-exclamation-circle me-1"></i>Sans compte
                                                    </span>
                                                @else
                                                    <span class="badge bg-secondary fs-6">
                                                        <i class="bi bi-dash-circle me-1"></i>Inactif
                                                    </span>
                                                @endif
                                            </td>
                                            <td  class="text-center">
                                                <button type="button" class="btn btn-link" data-toggle="modal" data-target="#descriptionModal{{ $item->id }}">
                                                    <i class="bi bi-eye"></i> Lire 
                                                </button>  
                                            </td>
                                            <td  class="text-center">
                                                <div class="table-data-feature d-flex justify-content-center align-items-center">       
                                                    <a href="{{url('laboratorie/bilan', $item->id)}}" class="btn btn-dark btn-circle mx-1 " data-bs-toggle="tooltip" data-bs-placement="top" title="Bilans">
                                                        <i class="bi bi-list-check text-white"></i>
                                                    </a>
                                                    <a href="{{url('laboratorie/examen', $item->id)}}" class="btn btn-dark btn-circle mx-1 " data-bs-toggle="tooltip" data-bs-placement="top" title="Examens">
                                                        <i class="bi bi-list-check text-white"></i>
                                                    </a>
                                                    <a data-bs-toggle="modal" data-bs-target="#viewModal{{ $item->id }}" class="btn btn-dark btn-circle mx-1" data-bs-toggle="tooltip" data-bs-placement="top" title="View">
                                                        <i class="bi bi-eye text-white"></i>
                                                    </a>
                                                    <a data-bs-toggle="modal" data-bs-target="#updateModal{{ $item->id }}" class="btn btn-dark btn-circle mx-1" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit">
                                                        <i class="bi bi-pencil-square text-white"></i>
                                                    </a>
                                                    
                                                    @if($user_auth->role_id == 4)
                                                        @if($item->user && $item->user->status == 'active')
                                                            <button type="button"
                                                               class="btn btn-circle mx-1" 
                                                               style="background-color: #ff6b6b;"
                                                               data-bs-toggle="modal" 
                                                               data-bs-target="#suspendModal{{ $item->id }}"
                                                               title="Suspendre le laboratoire">
                                                                <i class="bi bi-lock-fill text-white"></i>
                                                            </button>
                                                        @elseif($item->user && $item->user->status == 'suspended')
                                                            <button type="button"
                                                               class="btn btn-circle mx-1" 
                                                               style="background-color: #51cf66;"
                                                               data-bs-toggle="modal" 
                                                               data-bs-target="#activateModal{{ $item->id }}"
                                                               title="Activer le laboratoire">
                                                                <i class="bi bi-unlock-fill text-white"></i>
                                                            </button>
                                                        @endif
                                                        
                                                        <a data-bs-toggle="modal" data-bs-target="#confirmDeleteModal{{ $item->id }}" class="btn btn-danger btn-circle mx-1" data-bs-toggle="tooltip" data-bs-placement="top" title="Delete">
                                                            <i class="bi bi-trash text-white"></i>
                                                        </a> 
                                                    @endif  
                                                </div>
                                            </td>
                                        </tr>
                                    
                                    <!-- Modal View-->
                                    <div class="modal fade" id="viewModal{{ $item->id }}" tabindex="-1" aria-labelledby="viewModalLabel{{ $item->id }}" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered"> 
                                            <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Modification</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                            
                                            <div class="card-body">
                                                <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">
                                                    @isset($item->image)
                                                        <div class="row mb-3">
                                                            <div class="col-md-6">
                                                                <label class="form-label">
                                                                    <i class="bi bi-image"></i> Image
                                                                </label>
                                                                <img src="{{asset($item->image)}}" alt="image" class="img-fluid rounded">
                                                            </div>
                                                        </div>
                                                    @endisset
                                                    <div class="row mb-3">
                                                        <div class="col-md-6">
                                                            <label class="form-label">
                                                                <i class="bi bi-tag"></i> Nom
                                                            </label>
                                                            <p class="form-control-static">{{$item->name}}</p>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label class="form-label">
                                                                <i class="bi bi-currency-dollar"></i> Addresse
                                                            </label>
                                                            <p class="form-control-static">{{$item->address}}</p>
                                                        </div>
                                                    </div>

                                                    <div class="row mb-3">
                                                        <div class="col-md-6">
                                                            <label class="form-label">
                                                                <i class="bi bi-percent"></i> Commission
                                                            </label>
                                                            <p class="form-control-static"><span class="badge bg-primary fs-6">{{$item->pourcentage_commission}}%</span></p>
                                                        </div>
                                                    </div>

                                                    <div class="row mb-3">
                                                        <div class="col-12">
                                                            <label class="form-label">
                                                                <i class="bi bi-file-earmark-text"></i> Description
                                                            </label>
                                                            <p class="form-control-static">{{ nl2br(e($item->description)) }}</p>
                                                        </div>
                                                    </div>
                                                </form>
                                                </div>

                                            </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Modal Modifier-->
                                    <div class="modal fade" id="updateModal{{ $item->id }}" tabindex="-1" aria-labelledby="updateModalLabel{{ $item->id }}" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered"> 
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel">Modification</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <form method="POST" action="{{route('laboratoire_update', $item->id)}}" enctype="multipart/form-data" class="form-horizontal">
                                                            @csrf
                                                            <div class="row mb-3">
                                                                <div class="col-md-3">
                                                                    <label for="name" class="form-control-label">Nom</label>
                                                                </div>
                                                                <div class="col-md-9">
                                                                    <input type="text" id="name" require="True" name="name" placeholder="Nom" class="form-control rounded-pill focus:ring focus:ring-opacity-50" value="{{isset($item) ? $item->name : ''}}" required>
                                                                </div>
                                                            </div>
                                                            
                                                            <div class="row mb-3">
                                                                <div class="col-md-3">
                                                                    <label for="address" class="form-control-label">Addresse</label>
                                                                </div>
                                                                <div class="col-md-9">
                                                                    <div class="input-group">
                                                                        <input type="text" id="address" require="True" name="address" placeholder="address" class="form-control rounded-pill focus:ring focus:ring-opacity-50" value="{{isset($item) ? $item->address : ''}}" required>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="row mb-3">
                                                                <div class="col-md-3">
                                                                    <label for="pourcentage_commission" class="form-control-label">Commission (%)</label>
                                                                </div>
                                                                <div class="col-md-9">
                                                                    <div class="input-group">
                                                                        <input type="number" id="pourcentage_commission" name="pourcentage_commission" placeholder="Ex: 15" class="form-control rounded-pill focus:ring focus:ring-opacity-50" value="{{isset($item) ? $item->pourcentage_commission : 0}}" min="0" max="100" step="0.01" required>
                                                                        <span class="input-group-text">%</span>
                                                                    </div>
                                                                    <small class="text-muted">Pourcentage que le laboratoire perçoit sur les paiements en ligne</small>
                                                                </div>
                                                            </div>

                                                            <div class="row mb-3">
                                                                <div class="col-md-3">
                                                                    <label for="image" class="form-control-label">Image</label>
                                                                </div>
                                                                <div class="col-md-9">
                                                                    <div class="custom-file">
                                                                        <input type="file" id="image" name="image" class="custom-file-input" required>
                                                                    </div>
                                                                </div>
                                                                @if ($errors->has('image'))
                                                                    <span class="text-danger">{{ $errors->first('image') }}</span>
                                                                @endif
                                                            </div>

                                                            <div class="row mb-3">
                                                                <div class="col-md-3">
                                                                    <label for="description" class="form-control-label">Description</label>
                                                                </div>
                                                                <div class="col-md-9">
                                                                    <textarea name="description" id="description" rows="2" placeholder="Description..." class="form-control rounded-pill focus:ring focus:ring-opacity-50">{{isset($item) ? nl2br(e($item->description)) : ''}}</textarea>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                                                            <button type="submit" class="btn text-white" style="background:#667eea;">Enregistrer</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Modal Suprression-->
                                    <div class="modal fade" id="confirmDeleteModal{{ $item->id }}" tabindex="-1" aria-labelledby="confirmDeleteModalLabel{{ $item->id }}" aria-hidden="true">
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
                                                <a href="{{ route('laboratoire_destroy', $item->id) }}">
                                                    <button type="button" class="btn btn-danger">Confirmer</button>
                                                </a>
                                            </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Modal for Description -->
                                    <div class="modal fade" id="descriptionModal{{ $item->id }}" tabindex="-1" role="dialog" aria-labelledby="descriptionModalLabel{{ $item->id }}" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="descriptionModalLabel{{ $item->id }}" style="color:#667eea;">Description</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    {{ $item->description }}
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn text-white" style="background:#667eea;" data-dismiss="modal">D'accord</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Modal Suspension -->
                                    @if($item->user && $item->user->status == 'active')
                                    <div class="modal fade" id="suspendModal{{ $item->id }}" tabindex="-1" aria-labelledby="suspendModalLabel{{ $item->id }}" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header" style="background-color: #ff6b6b;">
                                                    <h5 class="modal-title text-white" id="suspendModalLabel{{ $item->id }}">
                                                       Suspendre le laboratoire
                                                    </h5>
                                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <p class="mb-3">Voulez-vous suspendre ce laboratoire ?</p>
                                                    <div class="alert alert-warning">
                                                        <strong>Laboratoire :</strong> {{ $item->name }}
                                                    </div>
                                                    <div class="alert alert-danger">
                                                        <p class="mb-2"><strong>Conséquences :</strong></p>
                                                        <ul class="mb-0">
                                                            <li>Le laboratoire sera immédiatement déconnecté</li>
                                                            <li>Il ne pourra plus effectuer aucune action</li>
                                                            <li>Ses services resteront visibles mais inactifs</li>
                                                        </ul>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                                    <a href="{{route('laboratoire_suspendre', $item->id)}}" class="btn text-white" style="background-color: #ff6b6b;">
                                                        Confirmer la suspension
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endif

                                    <!-- Modal Activation -->
                                    @if($item->user && $item->user->status == 'suspended')
                                    <div class="modal fade" id="activateModal{{ $item->id }}" tabindex="-1" aria-labelledby="activateModalLabel{{ $item->id }}" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header" style="background-color: #51cf66;">
                                                    <h5 class="modal-title text-white" id="activateModalLabel{{ $item->id }}">
                                                        <i class="bi bi-unlock-fill me-2"></i>Activer le laboratoire
                                                    </h5>
                                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <p class="mb-3">Voulez-vous réactiver ce laboratoire ?</p>
                                                    <div class="alert alert-warning">
                                                        <strong>Laboratoire :</strong> {{ $item->name }}
                                                    </div>
                                                    <div class="alert alert-success">
                                                        <p class="mb-2"><strong>Effets de l'activation :</strong></p>
                                                        <ul class="mb-0">
                                                            <li>Le laboratoire pourra se reconnecter</li>
                                                            <li>Toutes ses fonctionnalités seront restaurées</li>
                                                        </ul>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                                    <a href="{{route('laboratoire_activer', $item->id)}}" class="btn text-white" style="background-color: #51cf66;">
                                                        </i>Confirmer l'activation
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                    @endforeach
                                        </tbody>
                                    </table>
                                    <!-- Pagination Links -->
                                    {{ $laboratoires->links() }}
                                </div>        
                            </div>
                        </div>                           
                    </div> <!-- end card-->

        </div> <!-- end col-->
    </div>

    <!-- Modal create-->
<div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered"> 
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Laboratoire</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
            <form method="POST" action="{{route('laboratories.store')}}" enctype="multipart/form-data" class="form-horizontal">
                    @csrf
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <label for="name" class="form-control-label">Nom</label>
                        </div>
                        <div class="col-md-9">
                            <input type="text" id="name" require="True" name="name" placeholder="Nom" class="form-control rounded-pill focus:ring focus:ring-opacity-50" value="{{old('name')}}" required>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <label for="address" class="form-control-label">Addresse</label>
                        </div>
                        <div class="col-md-9">
                            <div class="input-group">
                                <input type="text" id="address" require="True" name="address" placeholder="address" class="form-control rounded-pill focus:ring focus:ring-opacity-50" value="{{old('address')}}" required>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-3">
                            <label for="pourcentage_commission" class="form-control-label">Commission (%)</label>
                        </div>
                        <div class="col-md-9">
                            <div class="input-group">
                                <input type="number" id="pourcentage_commission" name="pourcentage_commission" placeholder="Ex: 15" class="form-control rounded-pill focus:ring focus:ring-opacity-50" value="{{old('pourcentage_commission', 0)}}" min="0" max="100" step="0.01" required>
                                <span class="input-group-text">%</span>
                            </div>
                            <small class="text-muted">Pourcentage que le laboratoire perçoit sur les paiements en ligne (0-100)</small>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-3">
                            <label for="image" class="form-control-label">Image</label>
                        </div>
                        <div class="col-md-9">
                            <div class="custom-file">
                                <input type="file" id="image" name="image" class="custom-file-input" required>
                            </div>
                        </div>
                        @if ($errors->has('image'))
                            <span class="text-danger">{{ $errors->first('image') }}</span>
                        @endif
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-3">
                            <label for="description" class="form-control-label">Description</label>
                        </div>
                        <div class="col-md-9">
                            <textarea name="description" id="description" rows="2" placeholder="Description..." class="form-control rounded-pill focus:ring focus:ring-opacity-50" required>
                            {{ nl2br(e(old('description', $model->description ?? ''))) }}
                            </textarea>
                        </div>
                    </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                                                            <button type="submit" class="btn text-white" style="background:#667eea;">Enregistrer</button>
                                                        </div>
                                                    </form>            </div>

        </div>
    </div>
</div>

</div>
<!-- End Content-->


@endsection